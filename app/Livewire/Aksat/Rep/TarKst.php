<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\tar_kst;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class TarKst extends BaseWidget
{
    public $no;
    protected static ?string $heading="";
    #[On('TarKstNo')]
    public function TarKstNo($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('لا توجد بيانات')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,15])
            ->defaultSort('tar_date')
            ->paginated(function (){
                return tar_kst::where('no',$this->no)->count()>5;
            })
            ->query(function (tar_kst $main){
                $main=tar_kst::where('no',$this->no);
                return $main;
            })
            ->queryStringIdentifier('TraKst')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->state(fn(Model $record): string=> $record->tar_type==1?'من الفائض':'من خصم')
                    ->color(fn(Model $record): string=> $record->letters==1?'primary':'info')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(new HtmlString('<span style="font-size: smaller;color: #00bb00">ترجيع مبالغ&nbsp;&nbsp;</span>')),

                Tables\Columns\TextColumn::make('tar_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('التاريخ'),
                Tables\Columns\TextColumn::make('kst')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('المبلغ'),

            ])
            ->actions([
                Tables\Actions\Action::make('del')
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->iconSize(IconSize::Small)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Model $record){
                        if ($record->tar_type==1)
                          over_kst::where('no',$record->no)
                              ->where('kst',$record->kst)
                              ->where('letters',1)
                              ->update(['letters'=>0]);
                        if ($record->tar_type==3){

                            kst_trans::insert([
                                'ser'=>$record->ser,
                                'no'=>$record->no,
                                'kst_date'=>$record->kst_date,
                                'ksm_type'=>2,
                                'chk_no'=>0,
                                'kst'=>$record->kst,
                                'ksm_date'=>$record->ksm_date,
                                'ksm'=>$record->kst,
                                'kst_notes'=>null,
                                'inp_date'=>date('Y-m-d'),
                                'emp'=>auth::user()->empno,
                            ]);

                            $main=main::find($record->no);
                            $sul_pay = kst_trans::where('no', $record->no)->where('ksm', '!=', null)->sum('ksm');
                            $main->sul_pay=$sul_pay;
                            $main->raseed = $main->sul - $sul_pay;
                            $main->save();

                        }
                        $record->delete();
                        $this->dispatch('showMe',no: $this->no);
                    }),
            ]);
    }
}

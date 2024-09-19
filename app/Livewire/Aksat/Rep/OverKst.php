<?php

namespace App\Livewire\AKsat\Rep;

use App\Enums\KsmType;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\Operations;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\tar_kst;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class OverKst extends BaseWidget
{
    public $no;
    protected static ?string $heading="";
    #[On('OverKstNo')]
    public function OverKstNo($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('لا توجد بيانات')
            ->paginated(function (){
                return over_kst::where('no',$this->no)->count()>5;
            })
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,15])
            ->defaultSort('tar_date')
            ->query(function (over_kst $main){
                $main=over_kst::where('no',$this->no);
                return $main;
            })
            ->queryStringIdentifier('OverKst')
            ->columns([
                Tables\Columns\TextColumn::make('ser')
                    ->rowIndex()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(new HtmlString('<span style="font-size: smaller;color: #00bb00">خصم بالفائض&nbsp;&nbsp;</span>')),
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
                    ->visible(function (Model $record){
                        return $record->letters==0 ;
                    })
                    ->action(function (Model $record){
                       $record->delete();
                        $this->dispatch('showMe',no: $this->no);
                    }),
                Tables\Actions\Action::make('tar')
                    ->form([
                        Section::make([
                            Radio::make('ksm_type')
                                ->hiddenLabel()
                                ->inline()
                                ->columnSpan(2)
                                ->options(KsmType::class),
                            DatePicker::make('tar_date')
                                ->required()
                                ->label('التاريح'),

                        ])

                    ])
                    ->fillForm(fn (): array => [
                        'ksm_type' => KsmType::المصرف,
                        'tar_date'=>date('Y-m-d'),
                    ])
                    ->modalCancelActionLabel('عودة')
                    ->modalSubmitActionLabel('تحزين')
                    ->modalHeading('ترجيع مبلغ')
                    ->modalWidth(MaxWidth::Small)
                    ->action(function (array $data,over_kst $record,){
                        $record->update(['letters'=>1]);
                        $main=main::find($this->no)->first();
                        tar_kst::insert([
                            'no' => $this->no,
                            'name' => $main->name,
                            'bank' => $main->bank,
                            'acc' => $main->acc,
                            'kst' => $record->kst,
                            'tar_type' => 1,
                            'tar_date' => $data['tar_date'],
                            'ksm_date' => null,
                            'ser' => 0,
                            'kst_date' => null,
                            'emp' => Auth::user()->empno,
                            'ksm_type' => $data['ksm_type'],
                        ]);
                        $this->dispatch('showMe',no: $this->no);
                    })
                    ->tooltip('ترجيع')
                    ->iconButton()
                    ->iconSize(IconSize::Small)
                    ->icon('heroicon-o-arrow-turn-down-left')
                    ->visible(function (Model $record){
                        return $record->letters==0 ;
                    })

                    ->color('blue')


            ])
            ;
    }
}

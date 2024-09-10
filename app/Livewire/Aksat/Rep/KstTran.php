<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\Operations;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class KstTran extends BaseWidget
{
    public $no;
    public $WithKsm=True;
    protected static ?string $heading="";
    #[On('KstTranNo')]
    public function KstTranNo($no){
        $this->no=$no;
    }
    #[On('TakeWithKsm')]
    public function TakeWithKsm($withksm){
        $this->WithKsm=$withksm;
    }
    public function table(Table $table): Table
    {
        return $table

            ->emptyStateHeading('لا توجد أقساط مخصومة')
            ->emptyStateDescription('لم يتم خصم أقساط بعد')
            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([5,12,15,50])
            ->defaultSort('ser')
            ->query(function (kst_trans $main){
                $main=kst_trans::where('no',$this->no)
                ->when($this->WithKsm,function ($query){
                    return $query->where('ksm','!=',0);
                });
                return $main;
            })
            ->queryStringIdentifier('KstTran')
            ->columns([
                Tables\Columns\TextColumn::make('ser')
                    ->size(TextColumnSize::ExtraSmall)
                    ->color('primary')
                    ->sortable()
                    ->label('ت'),
                Tables\Columns\TextColumn::make('kst_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->toggleable()
                    ->sortable()
                    ->label('ت.الاستحقاق'),
                Tables\Columns\TextColumn::make('ksm_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->toggleable()
                    ->sortable()
                    ->label('ت.الخصم'),
                Tables\Columns\TextColumn::make('ksm')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الخصم'),
                Tables\Columns\TextColumn::make('ksm_type')
                    ->size(TextColumnSize::ExtraSmall)
                    ->toggleable()
                    ->label('طريقة الدفع'),
                Tables\Columns\TextColumn::make('kst_note')
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('ملاحظات'),

            ])
            ->actions([
                Tables\Actions\Action::make('del')
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->iconSize(IconSize::Small)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(function (Model $record){
                        return $record->ksm!=null && $record->ksm!=0;
                    })
                    ->action(function (Model $record){
                        DB::connection(Auth()->user()->company)->beginTransaction();
                        try {
                            kst_trans::where('no', $this->no)->where('ser', $record->ser)->update([
                                'ksm' => 0,
                                'ksm_date' => null,
                                'kst_notes' => null,
                                'emp' => auth::user()->empno,
                            ]);

                            $sul_pay = kst_trans::where('no', $this->no)->where('ksm', '!=', null)->sum('ksm');
                            $sul = main::where('no', $this->no)->first();
                            $raseed = $sul->sul - $sul_pay;
                            main::where('no', $this->no)->update(['sul_pay' => $sul_pay, 'raseed' => $raseed]);

                            Operations::insert(['Proce' => 'قسط', 'Oper' => 'الغاء', 'no' => $this->no, 'created_at' => Carbon::now(), 'emp' => auth::user()->empno,]);
                            $this->dispatch('showMe',no: $this->no);
                            DB::connection(Auth()->user()->company)->commit();

                        } catch (\Exception $e) {
                            DB::connection(Auth()->user()->company)->rollback();

                            Notification::make()
                                ->title('حدث خطأ !!')
                                ->danger()
                                ->send();
                        }

                    }),
                Tables\Actions\Action::make('edit')
                    ->iconButton()
                    ->iconSize(IconSize::Small)
                    ->icon('heroicon-o-pencil')
                    ->visible(function (Model $record){
                        return $record->ksm!=null && $record->ksm!=0;
                    })
                    ->color('blue')
                    ->action(function (){
                        //
                    })

            ], position: Tables\Enums\ActionsPosition::BeforeColumns);
    }
}

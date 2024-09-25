<?php

namespace App\Livewire\Aksat\Rep;

use App\Enums\KsmType;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\Family;
use App\Models\Operations;
use App\Models\Victim;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                Tables\Columns\TextColumn::make('kst_notes')
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
                        return $record->ksm!=null && $record->ksm!=0  &&
                            Auth::user()->can('الغاء أقساط');
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
                            info($e);
                            Notification::make()
                                ->title('حدث خطأ !!')
                                ->danger()
                                ->send();
                        }

                    }),
                Tables\Actions\Action::make('edit')
                    ->form([
                        Section::make([
                            Radio::make('ksm_type')
                             ->hiddenLabel()
                             ->inline()
                             ->columnSpan(2)
                             ->options(KsmType::class),
                            DatePicker::make('ksm_date')
                                ->required()
                                ->label('التاريح'),
                            TextInput::make('ksm')
                                ->required()
                                ->gt(0)
                                ->label('القسط'),
                            TextInput::make('kst_notes')
                                ->columnSpan(2)
                                ->label('ملاحظات'),
                        ]) ->columns(2)

                    ])
                    ->fillForm(fn (kst_trans $record): array => [
                        'ksm_date' => $record->ksm_date,'ksm'=>$record->ksm,
                        'kst_notes'=>$record->kst_notes,'ksm_type'=>$record->ksm_type,
                    ])
                    ->modalCancelActionLabel('عودة')
                    ->modalSubmitActionLabel('تحزين')
                    ->modalHeading('تعديل قسط')
                    ->action(function (array $data,kst_trans $record,){
                        $record->update(['ksm_date'=>$data['ksm_date'],'ksm'=>$data['ksm'],
                            'kst_notes'=>$data['kst_notes'],'ksm_type'=>$data['ksm_type']]);
                        $sul_pay = kst_trans::where('no', $this->no)->where('ksm', '!=', null)->sum('ksm');
                        $sul = main::where('no', $this->no)->first();
                        $raseed = $sul->sul - $sul_pay;
                        main::where('no', $this->no)->update(['sul_pay' => $sul_pay, 'raseed' => $raseed]);
                        $this->dispatch('showMe',no: $this->no);
                    })
                    ->iconButton()
                    ->iconSize(IconSize::Small)
                    ->icon('heroicon-o-pencil')
                    ->visible(function (Model $record){
                        return $record->ksm!=null && $record->ksm!=0 &&
                            Auth::user()->canany(['ادخال أقساط','ادخال حوافظ']);
                    })
                    ->color('blue')


            ], position: Tables\Enums\ActionsPosition::BeforeColumns);
    }
}

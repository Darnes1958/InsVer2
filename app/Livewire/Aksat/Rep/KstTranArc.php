<?php

namespace App\Livewire\Aksat\Rep;

use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\TextSize;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\aksat\TransArc;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;

class KstTranArc extends BaseWidget
{
    public $no;
    protected static ?string $heading="";
    #[On('KstTranNoArc')]
    public function KstTranNoArc($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table

            ->emptyStateHeading('لا توجد بيانات')
            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([5,12,15,50])
            ->defaultSort('ser')
            ->query(function (){
                $main=TransArc::where('no',$this->no);
                return $main;
            })
            ->queryStringIdentifier('KstTranArc')
            ->columns([
                TextColumn::make('ser')
                    ->action(function(kst_trans $record){
                        return true;
                    })
                    ->size(TextSize::ExtraSmall)
                    ->color('primary')
                    ->sortable()
                    ->label('ت'),
                TextColumn::make('kst_date')
                    ->toggleable()
                    ->size(TextSize::ExtraSmall)
                    ->sortable()
                    ->label('ت.الاستحقاق'),
                TextColumn::make('ksm_date')
                    ->toggleable()
                    ->size(TextSize::ExtraSmall)
                    ->sortable()
                    ->size(TextSize::ExtraSmall)
                    ->label('ت.الخصم'),
                TextColumn::make('ksm')
                    ->size(TextSize::ExtraSmall)
                    ->label('الخصم'),
                TextColumn::make('ksm_type')
                    ->toggleable()
                    ->size(TextSize::ExtraSmall)
                    ->label('طريقة الدفع'),


                TextColumn::make('kst_note')
                    ->toggleable()
                    ->label('ملاحظات'),
            ]);
    }
}

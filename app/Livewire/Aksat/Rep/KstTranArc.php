<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\aksat\TransArc;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
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
            ->query(function (TransArc $main){
                $main=TransArc::where('no',$this->no);
                return $main;
            })
            ->queryStringIdentifier('KstTranArc')
            ->columns([
                Tables\Columns\TextColumn::make('ser')
                    ->action(function(kst_trans $record){
                        info($record->no);
                    })
                    ->size(TextColumnSize::ExtraSmall)
                    ->color('primary')
                    ->sortable()
                    ->label('ت'),
                Tables\Columns\TextColumn::make('kst_date')
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->sortable()
                    ->label('ت.الاستحقاق'),
                Tables\Columns\TextColumn::make('ksm_date')
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->sortable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('ت.الخصم'),
                Tables\Columns\TextColumn::make('ksm')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الخصم'),
                Tables\Columns\TextColumn::make('ksm_type')
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('طريقة الدفع'),


                Tables\Columns\TextColumn::make('kst_note')
                    ->toggleable()
                    ->label('ملاحظات'),
            ]);
    }
}

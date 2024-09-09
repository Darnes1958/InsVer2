<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;

class KstTran extends BaseWidget
{
    public $no;
    protected static ?string $heading="";
    #[On('KstTranNo')]
    public function KstTranNo($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table

            ->emptyStateHeading('لا توجد بيانات')
            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([5,12,15,50])
            ->defaultSort('ser')
            ->query(function (kst_trans $main){
                $main=kst_trans::where('no',$this->no);
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
            ]);
    }
}

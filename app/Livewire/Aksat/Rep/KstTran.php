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
    #[On('takeNo')]
    public function takeNo($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(15)
            ->paginationPageOptions([5,10,15,50])
            ->defaultSort('no','desc')
            ->query(function (kst_trans $main){
                $main=kst_trans::where('no',$this->no);
                return $main;
            })
            ->columns([
                Tables\Columns\TextColumn::make('ser')
                    ->action(function(kst_trans $record){
                        info($record->no);
                    })
                    ->color('primary')
                    ->sortable()
                    ->label('ت'),
                Tables\Columns\TextColumn::make('kst_date')
                    ->toggleable()
                    ->sortable()
                    ->label('ت.الاستحقاق'),
                Tables\Columns\TextColumn::make('ksm_date')
                    ->toggleable()
                    ->sortable()
                    ->label('ت.الخصم'),
                Tables\Columns\TextColumn::make('ksm')
                    ->label('الخصم'),
                Tables\Columns\TextColumn::make('kst_note')
                    ->toggleable()
                    ->label('ملاحظات'),
            ]);
    }
}

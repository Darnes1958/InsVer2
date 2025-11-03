<?php

namespace App\Filament\Resources\Buys\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BuysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('الرقم الالي'),
                TextColumn::make('Supplier.name')
                    ->searchable()
                    ->sortable()
                    ->label('اسم المورد'),
                TextColumn::make('order_date')
                    ->searchable()
                    ->sortable()
                    ->label('التاريخ'),
                TextColumn::make('tot')
                    ->searchable()
                    ->sortable()
                    ->label('اجمالي الفاتورة'),
                TextColumn::make('cash')
                    ->label('المدفوع'),
                TextColumn::make('not_cash')
                    ->label('الباقي'),
                TextColumn::make('notes')
                    ->label('ملاحظات'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}

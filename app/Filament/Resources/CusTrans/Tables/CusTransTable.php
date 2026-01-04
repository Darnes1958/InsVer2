<?php

namespace App\Filament\Resources\CusTrans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CusTransTable
{
    public static function configure(Table $table): Table
    {
        return $table


            ->columns([
                TextColumn::make('Customer.Company')
                 ->searchable(),
                TextColumn::make('TransDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('Val')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ValType')
                    ,
                TextColumn::make('Notes')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\StopKsts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StopKstsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('no'),
               TextColumn::make('name'),
               TextColumn::make('acc'),
               TextColumn::make('bankname.bank_name'),
               TextColumn::make('stop_date'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
               DeleteAction::make(),
                //EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

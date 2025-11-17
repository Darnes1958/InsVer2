<?php

namespace App\Filament\Resources\StoreExpViews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Svg\Tag\Text;

class StoreExpViewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('per_type'),
                TextColumn::make('per_date'),
                TextColumn::make('per_no'),
                TextColumn::make('st_name'),
                TextColumn::make('hall_name'),

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

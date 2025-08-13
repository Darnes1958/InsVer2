<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaemaResource\Pages;
use App\Filament\Resources\KaemaResource\RelationManagers;

use App\Models\excel\Kaema;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KaemaResource extends Resource
{
    protected static ?string $model = Kaema::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('acc')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kst'),
                Tables\Columns\TextColumn::make('sul_date'),

                Tables\Columns\TextColumn::make('bankcode'),
                Tables\Columns\TextColumn::make('no_bank'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
              //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaemas::route('/'),
            'create' => Pages\CreateKaema::route('/create'),
            'edit' => Pages\EditKaema::route('/{record}/edit'),
        ];
    }
}

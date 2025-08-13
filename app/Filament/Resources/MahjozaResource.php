<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahjozaResource\Pages;
use App\Filament\Resources\MahjozaResource\RelationManagers;
use App\Models\excel\Mahjoza;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MahjozaResource extends Resource
{
    protected static ?string $model = Mahjoza::class;

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
                TextColumn::make('MainOrArc')
                    ->state(function (Model $record){
                        if ($record->MainOrArc==1) return 'قائم';
                        if ($record->MainOrArc==2) return 'أرشيف';
                    })
                    ->color(function (Model $record){
                        if ($record->MainOrArc==1) return 'success';
                        if ($record->MainOrArc==2) return 'info';
                    }),
                TextColumn::make('no'),

                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('acc')->searchable()->sortable(),
                TextColumn::make('aksat_tot'),
                TextColumn::make('aksat_count'),
                TextColumn::make('sal_date'),
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
            'index' => Pages\ListMahjozas::route('/'),
            'create' => Pages\CreateMahjoza::route('/create'),
            'edit' => Pages\EditMahjoza::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use App\Filament\Resources\KaemaResource\Pages\ListKaemas;
use App\Filament\Resources\KaemaResource\Pages\CreateKaema;
use App\Filament\Resources\KaemaResource\Pages\EditKaema;
use App\Filament\Resources\KaemaResource\Pages;
use App\Filament\Resources\KaemaResource\RelationManagers;

use App\Models\excel\Kaema;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KaemaResource extends Resource
{
    protected static ?string $model = Kaema::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                TextColumn::make('kst'),
                TextColumn::make('sul_date'),
                TextColumn::make('bankcode'),
                TextColumn::make('no_bank'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKaemas::route('/'),
            'create' => CreateKaema::route('/create'),
            'edit' => EditKaema::route('/{record}/edit'),
        ];
    }
}

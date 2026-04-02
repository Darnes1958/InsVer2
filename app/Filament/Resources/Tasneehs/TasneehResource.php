<?php

namespace App\Filament\Resources\Tasneehs;

use App\Filament\Resources\Tasneehs\Pages\ManageTasneehs;
use App\Models\Tasneeh;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TasneehResource extends Resource
{
    protected static ?string $model = Tasneeh::class;
    protected static string | \UnitEnum | null $navigationGroup='Setting';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Company'),
                TextInput::make('CompanyName')
                    ->required(),
                TextInput::make('CompanyNameSuffix')
                    ->required(),
                TextInput::make('CompanyImg'),
                TextInput::make('CompCode')
                    ->numeric(),
                TextInput::make('info'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Company')
                    ->searchable(),
                TextColumn::make('CompanyName')
                    ->searchable(),
                TextColumn::make('CompanyNameSuffix')
                    ->searchable(),
                TextColumn::make('CompanyImg')
                    ->searchable(),
                TextColumn::make('CompCode')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('info')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTasneehs::route('/'),
        ];
    }
}

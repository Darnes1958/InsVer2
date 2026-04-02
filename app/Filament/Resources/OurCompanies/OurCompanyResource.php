<?php

namespace App\Filament\Resources\OurCompanies;

use App\Filament\Resources\OurCompanies\Pages\ManageOurCompanies;
use App\Models\OurCompany;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class OurCompanyResource extends Resource
{
    protected static ?string $model = OurCompany::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup='Setting';
    protected static ?string $navigationLabel='Secound Verion';

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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('Company')
                    ->placeholder('-'),
                TextEntry::make('CompanyName'),
                TextEntry::make('CompanyNameSuffix'),
                TextEntry::make('CompanyImg')
                    ->placeholder('-'),
                TextEntry::make('CompCode')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('info')
                    ->placeholder('-'),
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
                ViewAction::make(),
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
            'index' => ManageOurCompanies::route('/'),
        ];
    }
}

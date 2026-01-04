<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use App\Filament\Resources\ExcelSetingResource\Pages\ListExcelSetings;
use App\Filament\Resources\ExcelSetingResource\Pages\CreateExcelSeting;
use App\Filament\Resources\ExcelSetingResource\Pages\EditExcelSeting;
use App\Filament\Resources\ExcelSetingResource\Pages;
use App\Filament\Resources\ExcelSetingResource\RelationManagers;
use App\Models\ExcelSeting;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExcelSetingResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }
    protected static ?string $model = ExcelSeting::class;
    protected static string | \UnitEnum | null $navigationGroup='Setting';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank')->required(),
                TextInput::make('headRowNo')->required(),
                TextInput::make('ksm_date')->required(),
                TextInput::make('name')->required(),
                TextInput::make('acc')->required(),
                TextInput::make('ksm')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank'),
                TextColumn::make('headRowNo'),
                TextColumn::make('ksm_date'),
                TextColumn::make('name'),
                TextColumn::make('acc'),
                TextColumn::make('ksm'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListExcelSetings::route('/'),
            'create' => CreateExcelSeting::route('/create'),
            'edit' => EditExcelSeting::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Sells;

use App\Filament\Resources\Sells\Pages\CreateSells;
use App\Filament\Resources\Sells\Pages\EditSells;
use App\Filament\Resources\Sells\Pages\ListSells;
use App\Filament\Resources\Sells\Schemas\SellsForm;
use App\Filament\Resources\Sells\Tables\SellsTable;
use App\Models\Sells;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SellsResource extends Resource
{
    protected static ?string $model = \App\Models\sell\sells::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel='مبيعات';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('ادخال مبيعات');
    }

    public static function form(Schema $schema): Schema
    {
        return SellsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SellsTable::configure($table);
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
            'index' => ListSells::route('/'),
            'create' => CreateSells::route('/create'),
            'edit' => EditSells::route('/{record}/edit'),
        ];
    }
}

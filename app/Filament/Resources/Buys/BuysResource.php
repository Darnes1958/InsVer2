<?php

namespace App\Filament\Resources\Buys;

use App\Filament\Resources\Buys\Pages\CreateBuys;
use App\Filament\Resources\Buys\Pages\EditBuys;
use App\Filament\Resources\Buys\Pages\ListBuys;
use App\Filament\Resources\Buys\Schemas\BuysForm;
use App\Filament\Resources\Buys\Tables\BuysTable;
use App\Models\Buys;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BuysResource extends Resource
{
    protected static ?string $model = \App\Models\buy\buys::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel='مشتريات';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('ادخال مشتريات');
    }

    public static function form(Schema $schema): Schema
    {
        return BuysForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuysTable::configure($table);
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
            'index' => ListBuys::route('/'),
            'create' => CreateBuys::route('/create'),
            'edit' => EditBuys::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Trans;

use App\Filament\Resources\Trans\Pages\CreateTrans;
use App\Filament\Resources\Trans\Pages\EditTrans;
use App\Filament\Resources\Trans\Pages\ListTrans;
use App\Filament\Resources\Trans\Schemas\TransForm;
use App\Filament\Resources\Trans\Tables\TransTable;
use App\Models\trans\trans;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransResource extends Resource
{
    protected static ?string $model = trans::class;
    protected static ?string $navigationLabel='ايصالات زبائن';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TransForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransTable::configure($table);
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
            'index' => ListTrans::route('/'),
            'create' => CreateTrans::route('/create'),
            'edit' => EditTrans::route('/{record}/edit'),
        ];
    }
}

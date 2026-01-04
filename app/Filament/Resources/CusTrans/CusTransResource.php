<?php

namespace App\Filament\Resources\CusTrans;

use App\Filament\Resources\CusTrans\Pages\CreateCusTrans;
use App\Filament\Resources\CusTrans\Pages\EditCusTrans;
use App\Filament\Resources\CusTrans\Pages\ListCusTrans;
use App\Filament\Resources\CusTrans\Schemas\CusTransForm;
use App\Filament\Resources\CusTrans\Tables\CusTransTable;
use App\Models\CusTrans;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CusTransResource extends Resource
{
    protected static ?string $model = CusTrans::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }
    public static function form(Schema $schema): Schema
    {
        return CusTransForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CusTransTable::configure($table);
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
            'index' => ListCusTrans::route('/'),
            'create' => CreateCusTrans::route('/create'),
            'edit' => EditCusTrans::route('/{record}/edit'),
        ];
    }
}

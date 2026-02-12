<?php

namespace App\Filament\Resources\Masrofat\MasCenters;

use App\Filament\Resources\Masrofat\MasCenters\Pages\CreateMasCenters;
use App\Filament\Resources\Masrofat\MasCenters\Pages\EditMasCenters;
use App\Filament\Resources\Masrofat\MasCenters\Pages\ListMasCenters;
use App\Filament\Resources\Masrofat\MasCenters\Schemas\MasCentersForm;
use App\Filament\Resources\Masrofat\MasCenters\Tables\MasCentersTable;
use App\Models\masr\MasCenters;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MasCentersResource extends Resource
{
    protected static ?string $model = MasCenters::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup='اعدادات';
    protected static ?string $navigationLabel='ربط الشركات بنقاط البيع';

    public static function form(Schema $schema): Schema
    {
        return MasCentersForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasCentersTable::configure($table);
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
            'index' => ListMasCenters::route('/'),
            'create' => CreateMasCenters::route('/create'),
            'edit' => EditMasCenters::route('/{record}/edit'),
        ];
    }
}

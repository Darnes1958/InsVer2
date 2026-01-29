<?php

namespace App\Filament\Resources\Aksat\LongPeriods;

use App\Filament\Resources\Aksat\LongPeriods\Pages\CreateLongPeriod;
use App\Filament\Resources\Aksat\LongPeriods\Pages\EditLongPeriod;
use App\Filament\Resources\Aksat\LongPeriods\Pages\ListLongPeriods;
use App\Filament\Resources\Aksat\LongPeriods\Schemas\LongPeriodForm;
use App\Filament\Resources\Aksat\LongPeriods\Tables\LongPeriodsTable;
use App\Models\Aksat\LongPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LongPeriodResource extends Resource
{
    protected static ?string $model = LongPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel='تجديد الصلاحية';

    public static function form(Schema $schema): Schema
    {
        return LongPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LongPeriodsTable::configure($table);
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
            'index' => ListLongPeriods::route('/'),
            'create' => CreateLongPeriod::route('/create'),
            'edit' => EditLongPeriod::route('/{record}/edit'),
        ];
    }
}

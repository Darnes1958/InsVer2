<?php

namespace App\Filament\Resources\StopKsts;

use App\Filament\Resources\StopKsts\Pages\CreateStopKst;
use App\Filament\Resources\StopKsts\Pages\EditStopKst;
use App\Filament\Resources\StopKsts\Pages\ListStopKsts;
use App\Filament\Resources\StopKsts\Schemas\StopKstForm;
use App\Filament\Resources\StopKsts\Tables\StopKstsTable;
use App\Models\OverTar\stop_kst;
use App\Models\StopKst;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StopKstResource extends Resource
{
    protected static ?string $model = stop_kst::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel='إيقاف أقساط';

    public static function form(Schema $schema): Schema
    {
        return StopKstForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StopKstsTable::configure($table);
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
            'index' => ListStopKsts::route('/'),
            'create' => CreateStopKst::route('/create'),
            'edit' => EditStopKst::route('/{record}/edit'),
        ];
    }
}

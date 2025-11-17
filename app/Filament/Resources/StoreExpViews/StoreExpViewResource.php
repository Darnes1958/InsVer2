<?php

namespace App\Filament\Resources\StoreExpViews;

use App\Filament\Resources\StoreExpViews\Pages\CreateStoreExpView;
use App\Filament\Resources\StoreExpViews\Pages\EditStoreExpView;
use App\Filament\Resources\StoreExpViews\Pages\ListStoreExpViews;
use App\Filament\Resources\StoreExpViews\Schemas\StoreExpViewForm;
use App\Filament\Resources\StoreExpViews\Tables\StoreExpViewsTable;
use App\Models\StoreExpView;
use App\Models\stores\store_exp_view;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StoreExpViewResource extends Resource
{
    protected static ?string $model = store_exp_view::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StoreExpViewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreExpViewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->selectRaw('distinct st_no,per_no,exp_date,st_name');
    }
    public static function getPages(): array
    {
        return [
            'index' => ListStoreExpViews::route('/'),
            'create' => CreateStoreExpView::route('/create'),
            'edit' => EditStoreExpView::route('/{record}/edit'),
        ];
    }
}

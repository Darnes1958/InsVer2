<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\FromExcelResource\Pages\ListFromExcels;
use App\Filament\Resources\FromExcelResource\Pages\CreateFromExcel;
use App\Filament\Resources\FromExcelResource\Pages\EditFromExcel;
use App\Filament\Resources\FromExcelResource\Pages;
use App\Filament\Resources\FromExcelResource\RelationManagers;
use App\Filament\Resources\FromExcelResource\Widgets\FromExcelWidget;
use App\Models\FromExcel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FromExcelResource extends Resource
{
    protected static ?string $model = FromExcel::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('no')->searchable()->sortable(),
              TextColumn::make('name')->searchable()->sortable(),
              TextColumn::make('acc')->searchable()->sortable(),
              TextColumn::make('ksm_date'),
              TextColumn::make('ksm')->numeric('3','.',','),
              TextColumn::make('hafitha_tajmeehy'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
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
            'index' => ListFromExcels::route('/'),
            'create' => CreateFromExcel::route('/create'),
            'edit' => EditFromExcel::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
      return [
        FromExcelWidget::class,
      ];
    }

}

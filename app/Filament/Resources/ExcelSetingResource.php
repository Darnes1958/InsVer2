<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExcelSetingResource\Pages;
use App\Filament\Resources\ExcelSetingResource\RelationManagers;
use App\Models\ExcelSeting;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExcelSetingResource extends Resource
{
    protected static ?string $model = ExcelSeting::class;
    protected static ?string $navigationGroup='Setting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListExcelSetings::route('/'),
            'create' => Pages\CreateExcelSeting::route('/create'),
            'edit' => Pages\EditExcelSeting::route('/{record}/edit'),
        ];
    }
}

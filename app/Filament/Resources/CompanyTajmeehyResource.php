<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyTajmeehyResource\Pages;
use App\Filament\Resources\CompanyTajmeehyResource\RelationManagers;
use App\Models\bank\BankTajmeehy;
use App\Models\CompanyTajmeehy;
use App\Models\ExcelSeting;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CompanyTajmeehyResource extends Resource
{
    protected static ?string $model = CompanyTajmeehy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('company')->default(Auth::user()->company),
                Select::make('bank_id')
                    ->options(ExcelSeting::all()->pluck('bank','id'))->required(),
                Select::make('taj_id')
                    ->options(BankTajmeehy::all()->pluck('TajName','TajNo'))->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('company',Auth::user()->company);
            })
            ->columns([
                TextColumn::make('company'),
                TextColumn::make('bank.bank'),
                TextColumn::make('taj.TajName'),
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
            'index' => Pages\ListCompanyTajmeehies::route('/'),
            'create' => Pages\CreateCompanyTajmeehy::route('/create'),
            'edit' => Pages\EditCompanyTajmeehy::route('/{record}/edit'),
        ];
    }
}

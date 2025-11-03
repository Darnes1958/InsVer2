<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Actions\EditAction;
use App\Filament\Resources\CompanyTajmeehyResource\Pages\ListCompanyTajmeehies;
use App\Filament\Resources\CompanyTajmeehyResource\Pages\CreateCompanyTajmeehy;
use App\Filament\Resources\CompanyTajmeehyResource\Pages\EditCompanyTajmeehy;
use App\Filament\Resources\CompanyTajmeehyResource\Pages;
use App\Filament\Resources\CompanyTajmeehyResource\RelationManagers;
use App\Models\bank\BankTajmeehy;
use App\Models\CompanyTajmeehy;
use App\Models\ExcelSeting;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
    protected static string | \UnitEnum | null $navigationGroup='Setting';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company')->default(Auth::user()->company),
                Select::make('bank_id')
                    ->options(ExcelSeting::all()->pluck('bank','id'))->required(),
                Select::make('taj_id')
                    ->options(BankTajmeehy::all()->pluck('TajName','TajNo'))->required(),
            ]);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
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
            ->recordActions([
                EditAction::make(),
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
            'index' => ListCompanyTajmeehies::route('/'),
            'create' => CreateCompanyTajmeehy::route('/create'),
            'edit' => EditCompanyTajmeehy::route('/{record}/edit'),
        ];
    }
}

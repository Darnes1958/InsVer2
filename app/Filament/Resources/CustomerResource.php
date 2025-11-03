<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class CustomerResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
    }
    protected static string | \UnitEnum | null $navigationGroup='Setting';
    protected static ?string $model = Customer::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Company')
                    ->unique(ignoreRecord: true)
                 ->required(),
                TextInput::make('CompanyName')
                    ->required(),
                TextInput::make('CompanyNameSuffix')
                    ->required(),
                TextInput::make('CompCode')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('message'),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Company')
                   ->searchable()
                   ->sortable(),
                TextColumn::make('CompanyName')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('CompanyNameSuffix')
                    ,
                TextColumn::make('CompCode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('message')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation()
                ->visible(function (Customer $record) {
                    $query = " IF EXISTS
       ( SELECT name FROM master.dbo.sysdatabases  WHERE name = ?  )
          BEGIN
            SELECT 1 AS Message
          END
        ELSE
         BEGIN
            SELECT 0 AS Message
         END
                               ";

                    $db = DB::select($query, [$record->Company]);
info($db);
                    return $db[0]->Message=='0';
                }),
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}

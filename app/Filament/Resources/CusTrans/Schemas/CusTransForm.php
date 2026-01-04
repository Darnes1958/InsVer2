<?php

namespace App\Filament\Resources\CusTrans\Schemas;

use App\Enums\CusValType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CusTransForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('Customer', 'Company')
                    ->searchable()
                    ->preload()
                    ->required()
                    ,
                DatePicker::make('TransDate')
                    ->default(now())
                    ->required(),
                TextInput::make('Val')
                    ->required()
                    ->numeric(),
                Radio::make('ValType')
                    ->default(2)
                    ->options(CusValType::class)
                    ->required()
            ,
                TextInput::make('Notes'),

            ]);
    }
}

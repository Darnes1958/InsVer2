<?php

namespace App\Filament\Resources\CusTrans\Schemas;

use App\Enums\CusValType;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Main;
use App\Models\Main_arc;
use App\Models\OurCompany;
use App\Models\Tasneeh;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
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
                MorphToSelect::make('transable')
                    ->types([
                        Type::make(Customer::class)
                            ->titleAttribute('Company')
                            ->label('الاصدار الأول'),
                        Type::make(OurCompany::class)
                            ->titleAttribute('Company')
                            ->label('الاصدار الثاني'),
                        Type::make(Account::class)
                            ->titleAttribute('Company')
                            ->label('المحاسبة'),
                        Type::make(Tasneeh::class)
                            ->titleAttribute('Company')
                            ->label('التصنيع'),
                    ])
                    ->searchable()
                    ->preload()
                    ->label('المنظومة'),

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

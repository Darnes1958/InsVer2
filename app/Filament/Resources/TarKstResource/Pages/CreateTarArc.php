<?php

namespace App\Filament\Resources\TarKstResource\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use App\Filament\Resources\TarKstResource;
use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;

class CreateTarArc extends CreateRecord
{
    protected static string $resource = TarKstResource::class;
    public  function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                DatePicker::make('tar_date')
                    ->default(now())
                    ->label('التاريخ'),
                Select::make('no')
                    ->visible(function ($operation){
                        return $operation=='create';
                    })
                    ->options(MainArc::all()->pluck('name', 'no'))
                    ->live()
                    ->afterStateUpdated(function ($state,Set $set,Get $get){
                        $main=MainArc::where('no',$get('no'))->first();
                        $set('bank',$main->bank);
                        $set('acc',$main->acc);
                        $set('name',$main->name);

                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('رقم العقد'),

                TextInput::make('kst')
                    ->label('المبلغ')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Hidden::make('name'),
                Hidden::make('bank'),
                Hidden::make('acc'),
                Hidden::make('inp_date')->default(now()),
                Hidden::make('tar_type')->default(4),
            ]);
    }
}

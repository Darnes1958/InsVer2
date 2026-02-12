<?php

namespace App\Filament\Resources\Masrofat\MasCenters\Schemas;

use App\Models\bank\Companies;
use App\Models\masr\MasCenters;
use App\Models\stores\halls_names;
use App\Models\stores\stores_names;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class MasCentersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('CenterNo')
                    ->default(function (){return MasCenters::max('CenterNo')+1;})
                    ->required()
                    ->unique(ignoreRecord: true),
                Radio::make('CenterWho')
                    ->default(1)
                    ->live()
                    ->options([
                        1=>'صالة',
                        2=>'مخزن',
                    ]),
                Select::make('WhoId')
                    ->options(function (Get $get): Collection {
                        if ($get('CenterWho')==1 ) {
                            return halls_names::query()
                                ->pluck('hall_name','hall_no');
                        }
                        else
                        {
                            return stores_names::query()
                                ->pluck('st_name','st_no');
                        }
                    })
                   ->searchable()
                   ->preload()
                   ->live()
                   ->afterStateUpdated(function ($state,Set $set,Get $get) {
                       if ($get('CenterWho')==1)
                           $set('CenterName',halls_names::find($state)->hall_name);
                       else
                           $set('CenterName',stores_names::find($state)->st_name);
                   })
                   ->required(),
                TextInput::make('CenterName')
                  ->readOnly()
                  ->required(),
                Select::make('company_id')
                 ->options(Companies::all()->pluck('CompName', 'CompNo')->toArray())
                 ->searchable()
                 ->required()
                 ->preload(),
            ]);
    }
}

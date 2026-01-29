<?php

namespace App\Filament\Resources\Aksat\LongPeriods\Schemas;

use App\Models\aksat\LongPeriod;
use App\Models\aksat\main;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LongPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('main_no')
                    ->relationship('main','name',
                        modifyQueryUsing: fn (Builder $query) => $query->whereNotIn('no',LongPeriod::all()->pluck('main_no')->toArray()))
                    ->searchable()
                    ->preload()

                    ->required(),
                Hidden::make('status')
                    ->default(1),
            ]);
    }
}

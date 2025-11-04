<?php

namespace App\Filament\Resources\Sells\Pages;

use App\Filament\Resources\Sells\SellsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSells extends ListRecords
{
    protected static string $resource = SellsResource::class;

    protected ?string $heading='';
}

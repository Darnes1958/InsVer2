<?php

namespace App\Filament\Resources\Buys\Pages;

use App\Filament\Resources\Buys\BuysResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuys extends ListRecords
{
    protected static string $resource = BuysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

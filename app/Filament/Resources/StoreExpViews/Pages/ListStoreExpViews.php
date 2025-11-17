<?php

namespace App\Filament\Resources\StoreExpViews\Pages;

use App\Filament\Resources\StoreExpViews\StoreExpViewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoreExpViews extends ListRecords
{
    protected static string $resource = StoreExpViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

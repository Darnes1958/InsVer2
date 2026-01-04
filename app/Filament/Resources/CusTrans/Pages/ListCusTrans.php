<?php

namespace App\Filament\Resources\CusTrans\Pages;

use App\Filament\Resources\CusTrans\CusTransResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCusTrans extends ListRecords
{
    protected static string $resource = CusTransResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

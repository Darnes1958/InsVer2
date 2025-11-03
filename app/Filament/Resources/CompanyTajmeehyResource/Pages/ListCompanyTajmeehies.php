<?php

namespace App\Filament\Resources\CompanyTajmeehyResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CompanyTajmeehyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyTajmeehies extends ListRecords
{
    protected static string $resource = CompanyTajmeehyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

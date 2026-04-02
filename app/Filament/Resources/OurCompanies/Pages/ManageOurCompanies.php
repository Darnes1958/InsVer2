<?php

namespace App\Filament\Resources\OurCompanies\Pages;

use App\Filament\Resources\OurCompanies\OurCompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOurCompanies extends ManageRecords
{
    protected static string $resource = OurCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

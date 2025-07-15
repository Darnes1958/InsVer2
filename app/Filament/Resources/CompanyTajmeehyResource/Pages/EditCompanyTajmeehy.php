<?php

namespace App\Filament\Resources\CompanyTajmeehyResource\Pages;

use App\Filament\Resources\CompanyTajmeehyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyTajmeehy extends EditRecord
{
    protected static string $resource = CompanyTajmeehyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

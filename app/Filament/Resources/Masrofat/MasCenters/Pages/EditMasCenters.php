<?php

namespace App\Filament\Resources\Masrofat\MasCenters\Pages;

use App\Filament\Resources\Masrofat\MasCenters\MasCentersResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMasCenters extends EditRecord
{
    protected static string $resource = MasCentersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

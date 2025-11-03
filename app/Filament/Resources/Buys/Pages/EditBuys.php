<?php

namespace App\Filament\Resources\Buys\Pages;

use App\Filament\Resources\Buys\BuysResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuys extends EditRecord
{
    protected static string $resource = BuysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

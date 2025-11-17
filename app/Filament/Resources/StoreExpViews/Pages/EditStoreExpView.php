<?php

namespace App\Filament\Resources\StoreExpViews\Pages;

use App\Filament\Resources\StoreExpViews\StoreExpViewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStoreExpView extends EditRecord
{
    protected static string $resource = StoreExpViewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

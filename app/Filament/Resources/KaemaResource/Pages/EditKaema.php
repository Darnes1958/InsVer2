<?php

namespace App\Filament\Resources\KaemaResource\Pages;

use App\Filament\Resources\KaemaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKaema extends EditRecord
{
    protected static string $resource = KaemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

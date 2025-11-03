<?php

namespace App\Filament\Resources\TarKstResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TarKstResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTarKst extends EditRecord
{
    protected static string $resource = TarKstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

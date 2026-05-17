<?php

namespace App\Filament\Resources\StopKsts\Pages;

use App\Filament\Resources\StopKsts\StopKstResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStopKst extends EditRecord
{
    protected static string $resource = StopKstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

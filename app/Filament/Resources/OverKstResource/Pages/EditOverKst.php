<?php

namespace App\Filament\Resources\OverKstResource\Pages;

use App\Filament\Resources\OverKstResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOverKst extends EditRecord
{
    protected static string $resource = OverKstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

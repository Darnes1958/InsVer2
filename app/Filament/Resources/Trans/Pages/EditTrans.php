<?php

namespace App\Filament\Resources\Trans\Pages;

use App\Filament\Resources\Trans\TransResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrans extends EditRecord
{
    protected static string $resource = TransResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

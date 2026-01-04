<?php

namespace App\Filament\Resources\CusTrans\Pages;

use App\Filament\Resources\CusTrans\CusTransResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCusTrans extends EditRecord
{
    protected static string $resource = CusTransResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

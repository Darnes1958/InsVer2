<?php

namespace App\Filament\Resources\Aksat\LongPeriods\Pages;

use App\Filament\Resources\Aksat\LongPeriods\LongPeriodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLongPeriod extends EditRecord
{
    protected static string $resource = LongPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

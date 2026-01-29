<?php

namespace App\Filament\Resources\Aksat\LongPeriods\Pages;

use App\Filament\Resources\Aksat\LongPeriods\LongPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLongPeriods extends ListRecords
{
    protected static string $resource = LongPeriodResource::class;
    protected ?string $heading='تجديد الصلاحية';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

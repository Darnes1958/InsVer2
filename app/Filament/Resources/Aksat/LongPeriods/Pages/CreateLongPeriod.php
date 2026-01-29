<?php

namespace App\Filament\Resources\Aksat\LongPeriods\Pages;

use App\Filament\Resources\Aksat\LongPeriods\LongPeriodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLongPeriod extends CreateRecord
{
    protected static string $resource = LongPeriodResource::class;
    protected ?string $heading='';

}

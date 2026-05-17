<?php

namespace App\Filament\Resources\StopKsts\Pages;

use App\Filament\Resources\StopKsts\StopKstResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStopKsts extends ListRecords
{
    protected static string $resource = StopKstResource::class;
    protected ?string $heading='شاشة ايقاف الأقساط';

    protected function getHeaderActions(): array
    {
        return [
           // CreateAction::make(),
        ];
    }
}

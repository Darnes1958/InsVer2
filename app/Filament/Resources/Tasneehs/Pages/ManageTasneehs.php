<?php

namespace App\Filament\Resources\Tasneehs\Pages;

use App\Filament\Resources\Tasneehs\TasneehResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTasneehs extends ManageRecords
{
    protected static string $resource = TasneehResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

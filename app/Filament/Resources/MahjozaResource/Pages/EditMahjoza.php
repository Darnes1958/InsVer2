<?php

namespace App\Filament\Resources\MahjozaResource\Pages;

use App\Filament\Resources\MahjozaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMahjoza extends EditRecord
{
    protected static string $resource = MahjozaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

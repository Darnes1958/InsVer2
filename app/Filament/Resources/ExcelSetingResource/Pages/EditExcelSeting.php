<?php

namespace App\Filament\Resources\ExcelSetingResource\Pages;

use App\Filament\Resources\ExcelSetingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExcelSeting extends EditRecord
{
    protected static string $resource = ExcelSetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

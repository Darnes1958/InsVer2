<?php

namespace App\Filament\Resources\ExcelSetingResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ExcelSetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExcelSetings extends ListRecords
{
    protected static string $resource = ExcelSetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

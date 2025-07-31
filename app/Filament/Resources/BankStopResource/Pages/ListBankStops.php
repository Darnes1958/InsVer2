<?php

namespace App\Filament\Resources\BankStopResource\Pages;

use App\Filament\Resources\BankStopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankStops extends ListRecords
{
    protected static string $resource = BankStopResource::class;
    protected ?string $heading=' ';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة'),
        ];
    }
}

<?php

namespace App\Filament\Resources\BankStopResource\Pages;

use App\Filament\Resources\BankStopResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankStop extends CreateRecord
{
    protected static string $resource = BankStopResource::class;
    protected ?string $heading='';
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\BankStopResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\BankStopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankStop extends EditRecord
{
    protected static string $resource = BankStopResource::class;
    protected ?string $heading=' ';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): ?string
    {
        return  self::getResource()::getUrl('index');
    }
}

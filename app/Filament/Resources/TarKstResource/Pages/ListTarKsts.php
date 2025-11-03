<?php

namespace App\Filament\Resources\TarKstResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TarKstResource;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListTarKsts extends ListRecords
{
    protected static string $resource = TarKstResource::class;
protected ?string $heading='استفسار وبحث وادخال ترجيعات';
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('ترجيع مبالغ لعقد'),
            Actions\Action::make('inpArc')
             ->url(CreateTarArc::getUrl())
             ->label('ترجيع مبالغ لعقد من الارشيف'),

        ];
    }
}

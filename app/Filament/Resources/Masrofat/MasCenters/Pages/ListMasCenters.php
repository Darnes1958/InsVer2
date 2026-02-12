<?php

namespace App\Filament\Resources\Masrofat\MasCenters\Pages;

use App\Filament\Resources\Masrofat\MasCenters\MasCentersResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListMasCenters extends ListRecords
{
    protected static string $resource = MasCentersResource::class;
    protected ?string $heading='';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(function (){
                return Auth::id()==1;
            }),
        ];
    }
}

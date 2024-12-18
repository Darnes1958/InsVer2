<?php

namespace App\Filament\Resources\OverKstResource\Pages;

use App\Filament\Resources\OverKstResource;
use App\Livewire\Traits\PublicTrait;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Response;

class ListOverKsts extends ListRecords
{
    use PublicTrait;
    public $by;
    public  $letters;
    protected static string $resource = OverKstResource::class;

    protected ?string $heading='اقساط بالفائض';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
             ->iconButton()
             ->icon('heroicon-o-printer')
             ->color('blue')
             ->visible(function (){

                 return $this->table->getFilters()['taj']->getState()['taj_id'];
             })
             ->action(function (){
                 info($this->table->getFilters()['taj']->getState()['letters']);
          //       return Response::download(self::ret_spatie($this->getTableQueryForExport()->get(),
            //         'PrnView.aksat.pdf-over2'), 'filename.pdf', self::ret_spatie_header());
             })
             ,
        ];
    }
}

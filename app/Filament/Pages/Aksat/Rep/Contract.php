<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Models\aksat\main;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Livewire\Attributes\On;

class Contract extends Page implements HasInfolists
{
    use InteractsWithInfolists;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.aksat.rep.contract';
    protected ?string $heading="";

    public $searchData;
    public $infoData;

    public $no;
    public  $Main;
    public $showInfo=false;

    #[On('showMe')]
    public function showMe($no){
        $this->no=$no;
        $this->showInfo=true;
    }

  public function mount(): void
  {
      $this->Main=main::first();
      $this->searchForm->fill([]);
  }

  protected function getForms(): array
  {
    return array_merge(parent::getForms(), [
      "searchForm" => $this->makeForm()
        ->model(main::class)
        ->schema($this->getsearchFormSchema())
        ->statePath('searchData'),

    ]);
  }

  public function chkNo(){
      $res=main::find($this->no);
      if  ($res) {
          $this->Main=main::find($this->no);
          $this->showInfo=true;
      }

      $this->dispatch('takeNo',no: $this->no);
  }
  public function doSearch()
  {
      $this->no=null;
  }
  protected function getsearchFormSchema(): array
  {
    return [
      Section::make()
       ->schema([
         TextInput::make('no')
          ->label('')
          ->extraInputAttributes(['style' => 'font-size:.75em;padding:0.5em;'])
          ->placeholder('رقم العقد')
          ->live()
             ->afterStateUpdated(function ($state){
                 $this->no=$state;
             })
          ->extraAttributes( ['wire:keydown.enter' => 'chkNo' ]) ,
         TextInput::make('mysearch')
           ->label('')
           ->extraInputAttributes(['style' => 'font-size:.75em;padding:0.5em;'])
          ->extraAttributes(['wire:click' => 'doSearch' ])
           ->placeholder('بحث برقم الحساب او الاسم')
           ->live()
             ->columnSpan(3)
           ->afterStateUpdated(function ($state){
             $this->dispatch('takeSearch',mysearch: $state,no: $this->no);

           })
       ])->columns(4)


    ];
  }
  public function mainInfolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->record($this->Main)
            ->schema([
                TextEntry::make('name')
                 ->color('primary')
                 ->hiddenLabel()
                 ->columnSpan(2),
                TextEntry::make('jeha')
                    ->inlineLabel()
                    ->columnSpan(2)
                    ->label('رقم الزبون') ,
            ])->columns(4);
    }


}

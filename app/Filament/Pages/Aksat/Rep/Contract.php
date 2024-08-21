<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Models\aksat\main;
use App\Models\NewModel\Nmain;
use App\Models\sell\sells;
use App\Models\stores\halls_names;
use App\Models\stores\stores_names;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
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
    public $Order_no;
    public $showInfo=false;

    #[On('showMe')]
    public function showMe($no){
        $this->Main=Nmain::find($no);
        $this->Order_no=sells::find($this->Main->order_no);

        $this->no=$no;
        $this->searchForm->fill(['no'=>$no]);
        $this->showInfo=true;
    }

  public function mount(): void
  {
      $this->Main=Nmain::first();
      $this->Order_no=sells::find($this->Main->order_no);
      $this->searchForm->fill([]);
  }

  protected function getForms(): array
  {
    return array_merge(parent::getForms(), [
      "searchForm" => $this->makeForm()
        ->model(Nmain::class)
        ->schema($this->getsearchFormSchema())
        ->statePath('searchData'),

    ]);
  }

  public function chkNo(){

     $res=Nmain::find($this->no);
      if  ($res) {
          $this->Main=$res;
          $this->showInfo=true;
      } else $this->showInfo=false;

      $this->dispatch('KstTranNo',no: $this->no);

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
                 if (!$state) {
                   $this->chkNo();
                 }
             })
          ->extraAttributes( ['wire:keydown.enter' => 'chkNo' ]) ,

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
                 ->columnSpan(3),
                TextEntry::make('jeha')
                    ->prefix(new HtmlString('<span class="text-white "> رقم الزبون&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->color('info')
                    ->columnSpan(3),
                TextEntry::make('acc')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> رقم الحساب&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('bank.bank_name')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> المصرف&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('place.place_name')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> جهة العمل&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('sell_point')
                    ->prefix(new HtmlString('<span class="text-white "> نقطة البيع&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->color('info')
                    ->state(function (){
                        if ($this->Order_no->sell_type==1) return stores_names::find($this->Order_no->place_no)->st_name;
                        else return halls_names::find($this->Order_no->place_no)->hall_name;
                    })

                    ->columnSpan(3),
                TextEntry::make('sul_date')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> تاريخ العقد&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()

                    ->columnSpan(2),
                TextEntry::make('sul_tot')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> ج.الفاتورة&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('cash')
                    ->color('info')
                    ->state(function (){
                        return $this->Order_no->cash;
                    })
                    ->prefix(new HtmlString('<span class="text-white "> المدفوع&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('sul')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> اجمالي التقسيط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('sul_pay')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> المسدد&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('raseed')
                    ->color('danger')
                    ->prefix(new HtmlString('<span class="text-white "> المطلوب&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('kst_count')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> عدد الأقساط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('kst')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> القسط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('kst_raseed')
                    ->state(function (){
                        if ($this->Main->raseed<=0) $kst_raseed=0;
                        else {
                            if ($this->Main->raseed<=$this->Main->kst) $kst_raseed=1;
                            else {
                                $kst_raseed=ceil($this->Main->raseed/$this->Main->kst);
                            }
                        }
                        return $kst_raseed;
                    })
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-white "> متبقية&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('notes')
                    ->color('success')
                    ->visible(function (Nmain $record){
                        return $record->notes!=null;
                    })
                    ->Label('ملاحظات')
                    ->columnSpan(6),

            ])->columns(6);
    }


}

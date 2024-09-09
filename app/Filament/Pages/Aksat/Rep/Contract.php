<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Livewire\Aksat\Rep\TarKst;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\Customers;
use App\Models\NewModel\Nmain;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\tar_kst;
use App\Models\sell\rep_sell_tran;
use App\Models\sell\sells;
use App\Models\stores\halls_names;
use App\Models\stores\stores_names;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public $bank;
    public $Taj;
    public  $Main;
    public $Order_no;
    public $showInfo=false;
    public $showOver=false;
    public $showTar=false;
    public $showArc=false;
    public $By=false;
    public $arcOver;
    public $arcNo;
    public function mount(): void
    {
        $this->Main=Nmain::first();
        $this->Order_no=sells::find($this->Main->order_no);
        $this->searchForm->fill(['By'=>$this->By,]);
    }
    #[On('ArcData')]
    public function ArcData($arcNo,$arcOver)
    {
        $this->arcOver=$arcOver;
        $this->arcNo=$arcNo;
    }
    #[On('showMe')]
    public function showMe($no){
        $this->Main=Nmain::find($no);
        $this->Order_no=sells::find($this->Main->order_no);
        $this->no=$no;
        $this->searchForm->fill(['no'=>$no,'By'=>$this->By,'bank'=>$this->bank,'Taj'=>$this->Taj]);
        $this->showInfo=true;
        $this->showOver= over_kst::where('no',$this->no)->exists();
        $this->showTar= tar_kst::where('no',$this->no)->exists();
        $this->showArc= MainArc::where('jeha',$this->Main->jeha)->exists();
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
          $this->showOver= over_kst::where('no',$this->no)->exists();
          $this->showTar= tar_kst::where('no',$this->no)->exists();
          $this->showArc= MainArc::where('jeha',$this->Main->jeha)->exists();
          $this->dispatch('MainItemOrder',order_no: $this->Main->order_no);
          $this->dispatch('OverKstNo',no: $this->no);
          $this->dispatch('TarKstNo',no: $this->no);
          $this->dispatch('ContJeha',jeha: $this->Main->jeha);

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
          ->hiddenLabel()
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
         Select::make('bank')
            ->placeholder('فلترة بالمصرف')
            ->visible(function (){
                return !$this->By;
            })
            ->searchable()
            ->live()
            ->hiddenLabel()
            ->options(bank::all()->pluck('bank_name','bank_no'))
           ->afterStateUpdated(function ($state){
               $this->bank=$state;
               $this->dispatch('takeBank',bank: $state,by: $this->By) ;
           })
           ->columnSpan(2),
           Select::make('Taj')
               ->placeholder('فلترة بالتجميعي')
               ->visible(function (){
                   return $this->By;
               })
               ->searchable()
               ->live()
               ->hiddenLabel()
               ->options(BankTajmeehy::all()->pluck('TajName','TajNo'))
               ->afterStateUpdated(function ($state){
                   $this->Taj=$state;
                   $this->dispatch('takeBank',bank: $state,by: $this->By) ;
               })
               ->columnSpan(2),
        Checkbox::make('By')
         ->label('بالتجميعي')
         ->live()
         ->afterStateUpdated(function ($state){
             $this->bank=null;
             $this->Taj=null;
             $this->By=$state;
         }),
           \Filament\Forms\Components\Actions::make([
               \Filament\Forms\Components\Actions\Action::make('print1')
                   ->label('طباعة')
                   ->outlined()
                   ->visible($this->showInfo)
                   ->url(function (){
                       if ($this->no) return route('pdfmain', $this->no);
                   }),
               \Filament\Forms\Components\Actions\Action::make('print2')
                   ->label('طباعة نموذج')
                   ->outlined()
                   ->visible($this->showInfo)
                   ->url(function (){
                       if ($this->no) return route('pdfmaincont', $this->no);
                   }),

               ])
               ->columnSpan(2) ,
           Placeholder::make('lastcont')
               ->hiddenLabel()
               ->visible($this->showInfo && MainArc::where('jeha',$this->Main->jeha)->count()>0)
               ->content(new HtmlString('<span style="color: #00bb00"> عقود سابقة ('.MainArc::where('jeha',$this->Main->jeha)->count().')</span>')),
           Placeholder::make('livecont')
               ->hiddenLabel()
               ->visible($this->showInfo && main::where('jeha',$this->Main->jeha)->where('no','!=',$this->Main->no)->count()>0)
               ->content(new HtmlString('<span style="color: yellow"> عقود قائمة ('.main::where('jeha',$this->Main->jeha)->where('no','!=',$this->Main->no)->count().')</span>')),
           \Filament\Forms\Components\Actions::make([
               \Filament\Forms\Components\Actions\Action::make('toArchif')
                   ->label('نقل للأرشيف')
                   ->color('info')
                   ->visible($this->showInfo)
                   ->outlined()
                   ->requiresConfirmation()
                   ->action(function (){
                       DB::connection(Auth()->user()->company)->beginTransaction();
                       try {
                           $oldRecord=main::find($this->no);
                           $newRecord = $oldRecord->replicate();
                           $newRecord->setTable('MainArc');
                           $newRecord->no=$this->no;
                           $newRecord->save();

                           kst_trans::query()
                               ->where('no', $this->no)
                               ->each(function ($oldTran) {
                                   $newTran = $oldTran->replicate();
                                   $newTran->setTable('TransArc');
                                   $newTran->save();
                                   $oldTran->delete();
                               });
                           over_kst::query()
                               ->where('no', $this->no)
                               ->each(function ($oldTran) {
                                   $newTran = $oldTran->replicate();
                                   $newTran->setTable('over_kst_a');
                                   $newTran->save();
                                   $oldTran->delete();
                               });
                           $oldRecord->delete();
                           DB::connection(Auth()->user()->company)->commit();
                           $this->Main=Nmain::first();
                           $this->no=null;
                           $this->showInfo=false;
                           $this->dispatch('resetSearch');

                       } catch (\Exception $e) {

                           DB::connection(Auth()->user()->company)->rollback();
                           Notification::make()
                               ->title('حدث خطأ')
                               ->send();
                       }
                   }),
           ])->columnSpan(2) ,
       ])
       ->columns(12)
          ->extraAttributes(['class' => 'flush'])



    ];
  }
  public function mainInfolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->record($this->Main)
            ->schema([
                TextEntry::make('name')
                 ->color('primary')
                    ->size(function (){
                        if (strlen($this->Main->name)>50) return TextEntry\TextEntrySize::ExtraSmall;
                        else return TextEntry\TextEntrySize::Small;
                    })
                 ->extraEntryWrapperAttributes(['style' => 'height: 16px;'])
                 ->hiddenLabel()
                 ->columnSpan(3),
                TextEntry::make('jeha')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white " > رقم الزبون&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->color('info')
                    ->columnSpan(3),
                TextEntry::make('acc')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white " > رقم الحساب&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->hiddenLabel()
                    ->size(TextEntry\TextEntrySize::ExtraSmall)
                    ->columnSpan(3),
                TextEntry::make('bank.bank_name')
                    ->color('primary')
                    ->size(function (){
                        if (strlen($this->Main->name)>40) return TextEntry\TextEntrySize::ExtraSmall;
                        else return TextEntry\TextEntrySize::Small;
                    })
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('place.place_name')
                    ->color('info')
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> جهة العمل&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('sell_point')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> نقطة البيع&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->hiddenLabel()
                    ->size(TextEntry\TextEntrySize::ExtraSmall)
                    ->color('info')
                    ->state(function (){
                        if ($this->Order_no->sell_type==1) return stores_names::find($this->Order_no->place_no)->st_name;
                        else return halls_names::find($this->Order_no->place_no)->hall_name;
                    })

                    ->columnSpan(3),
                TextEntry::make('sul_date')
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ت.العقد&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()

                    ->columnSpan(2),
                TextEntry::make('sul_tot')
                    ->color('info')
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ج.الفاتورة&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->columnSpan(2),
                TextEntry::make('cash')
                    ->color('info')
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->state(function (){
                        return $this->Order_no->cash;
                    })
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> المدفوع&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->columnSpan(2),
                TextEntry::make('sul')
                    ->color('info')
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ج.التقسيط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->columnSpan(2),
                TextEntry::make('sul_pay')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> المسدد&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->columnSpan(2),
                TextEntry::make('raseed')
                    ->color('danger')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white"> المطلوب&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->columnSpan(2),
                TextEntry::make('kst_count')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> عدد الأقساط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->columnSpan(2),
                TextEntry::make('kst')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> القسط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
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
                    ->extraEntryWrapperAttributes(['style' => 'height:10px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> متبقية&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('notes')
                    ->color('success')
                    ->visible(function (Nmain $record){
                        return $record->notes!=null;
                    })
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ملاحظات&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->columnSpan(6),

            ])->columns(6);
    }


}

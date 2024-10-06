<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Enums\KsmType;
use App\Livewire\Aksat\Rep\TarKst;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\Customers;
use App\Models\NewModel\Nmain;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\stop_kst;
use App\Models\OverTar\tar_kst;
use App\Models\sell\rep_sell_tran;
use App\Models\sell\sells;
use App\Models\stores\halls_names;
use App\Models\stores\stores_names;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class Contract extends Page implements HasInfolists
{
    use InteractsWithInfolists;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.aksat.rep.contract';
    protected static ?string $navigationLabel='استفسار وتعديل بيانات عقود';
    protected ?string $heading="";

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('استفسار عقود فقط');
    }

    public $searchData;
    public $kstData;
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
    public $showCont=false;
    public $By=false;

    public $arcOver;
    public $arcNo;
    public function mount(): void
    {
        $this->Main=Nmain::first();
        $this->Order_no=sells::find($this->Main->order_no);
        $this->searchForm->fill(['By'=>$this->By,]);
        $this->kstForm->fill(['WithKsm'=>true,]);
    }
    #[On('ArcData')]
    public function ArcData($arcNo,$arcOver)
    {
        $this->arcOver=$arcOver;
        $this->arcNo=$arcNo;
    }
    #[On('showMe')]
    public function showMe($no){
        $this->no=$no;
        $this->chkNo($no);
    }


  protected function getForms(): array
  {
    return array_merge(parent::getForms(), [
      "searchForm" => $this->makeForm()
        ->model(Nmain::class)
        ->schema($this->getsearchFormSchema())
        ->statePath('searchData'),
        "kstForm" => $this->makeForm()
            ->model(kst_trans::class)
            ->schema($this->getkstFormSchema())
            ->statePath('kstData'),

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
          $this->showCont=main::where('jeha',$this->Main->jeha)->count()>1;
          $this->dispatch('MainItemOrder',order_no: $this->Main->order_no);
          $this->dispatch('OverKstNo',no: $this->no);
          $this->dispatch('TarKstNo',no: $this->no);
          $this->dispatch('ContJeha',jeha: $this->Main->jeha,no: $this->Main->no);
          if ($this->showArc) $this->dispatch('changeLimit',limit: 5);
          else $this->dispatch('changeLimit',limit: 10);

      } else $this->showInfo=false;

      $this->dispatch('KstTranNo',no: $this->no);

  }
  public function doSearch()
  {
      $this->no=null;
  }

  protected function getkstFormSchema(): array
  {
      return [
          Section::make()
           ->schema([
               Checkbox::make('WithKsm')
                   ->label(new HtmlString('<span style="font-size: smaller">المخصومة فقط</span>'))
                   ->visible(function (){return $this->showInfo;})
                   ->live()
                   ->afterStateUpdated(function ($state){
                       $this->dispatch('TakeWithKsm',withksm: $state) ;
                   })->columnSpan(3),
               Actions::make([
                   Actions\Action::make('ادخال_قسط')
                       ->icon('heroicon-o-plus')
                       ->iconButton()
                       ->form([
                           Section::make([
                               Radio::make('ksm_type')
                                   ->hiddenLabel()
                                   ->inline()
                                   ->columnSpan(2)
                                   ->options(KsmType::class),
                               DatePicker::make('ksm_date')
                                   ->required()
                                   ->label('التاريح'),
                               TextInput::make('ksm')
                                   ->required()
                                   ->gt(0)
                                   ->label('القسط'),
                               TextInput::make('kst_notes')
                                   ->columnSpan(2)
                                   ->label('ملاحظات'),
                           ]) ->columns(2)

                       ])
                       ->fillForm(function (){
                           return [
                               'ksm'=>$this->Main->kst,
                               'ksm_date'=>date('Y-m-d'),
                               'ksm_type'=>KsmType::المصرف,
                           ];
                       })
                       ->modalCancelActionLabel('عودة')
                       ->modalSubmitActionLabel('تحزين')
                       ->modalHeading('ادخال قسط')
                       ->action(function (array $data){
                           $ksm=$data['ksm'];
                           $over=0;

                           if ($this->Main->raseed<=0) {
                               $over=$ksm;
                               $ksm=0;


                           }

                           if (($ksm>$this->Main->raseed) && ($this->Main->raseed>0)) {
                               $ksm=$this->Main->raseed;
                               $over=$ksm-$this->Main->raseed;
                           }

                           if ($ksm!=0){
                                   $results=kst_trans::where('no',$this->no)->where(function ($query) {
                                       $query->where('ksm', '=', null)
                                           ->orWhere('ksm', '=', 0);
                                   })->min('ser');
                                   $ser= empty($results)? 0 : $results;

                                   if ($ser!=0) {
                                       kst_trans::where('no',$this->no)->where('ser',$ser)->update([
                                           'ksm'=>$ksm,
                                           'ksm_date'=>$data['ksm_date'],
                                           'ksm_type'=>$data['ksm_type'],
                                           'inp_date'=>date('Y-m-d'),
                                           'kst_notes'=>$data['kst_notes'],
                                           'emp'=>auth::user()->empno,
                                       ]);

                                   } else
                                   {
                                       $max=(kst_trans::where('no',$this->no)->max('ser'))+1;

                                       DB::connection(Auth()->user()->company)->table('kst_trans')->insert([
                                           'ser'=>$max,
                                           'no'=>$this->no,
                                           'kst_date'=>$data['ksm_date'],
                                           'ksm_type'=>$data['ksm_type'],
                                           'chk_no'=>0,
                                           'kst'=>$ksm,
                                           'ksm_date'=>$data['ksm_date'],
                                           'ksm'=>$ksm,
                                           'kst_notes'=>$data['kst_notes'],
                                           'inp_date'=>date('Y-m-d'),
                                           'emp'=>auth::user()->empno,
                                       ]);
                                   }

                               }

                           if ($over!=0) {

                                   over_kst::insert([
                                       'no'=>$this->no,
                                       'name'=>$this->Main->name,
                                       'bank'=>$this->Main->bank,
                                       'acc'=>$this->Main->acc,
                                       'kst'=>$over,
                                       'tar_type'=>1,
                                       'tar_date'=>$data['ksm_date'],
                                       'letters'=>0,
                                       'emp'=>auth::user()->empno,
                                   ]);
                               }

                           $sul_pay = kst_trans::where('no', $this->no)->where('ksm', '!=', null)->sum('ksm');
                           $sul = main::where('no', $this->no)->first();
                           $raseed = $sul->sul - $sul_pay;
                           main::where('no', $this->no)->update(['sul_pay' => $sul_pay, 'raseed' => $raseed]);
                           $this->dispatch('showMe',no: $this->no);
                       })
                       ->visible(function (){return Auth::user()->canany(['ادخال أقساط','ادخال حوافظ']) && $this->showInfo ;}),

               ]),


           ])
          ->columns(4)
      ];
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
         })
         ->extraAttributes(['style' => 'margin: 4px;']),
           Actions::make([
              Actions\Action::make('طباعة')
                   ->iconButton()
                   ->icon('heroicon-s-printer')
                   ->color('primary')
                   ->visible(function (){return $this->showInfo;})
                   ->url(function (){
                       if ($this->no) return route('pdfmain', $this->no);
                   })->extraAttributes(['style' => 'margin: 2px;']),
               Actions\Action::make('طباعة_نموذج')
                   ->iconButton()
                   ->icon('heroicon-s-document')
                   ->color('primary')
                   ->visible(function (){return $this->showInfo;})
                   ->url(function (){
                       if ($this->no) return route('pdfmaincont', $this->no);
                   }),


               ])
               ->columnSpan(2) ,
           Actions::make([
               Actions\Action::make('ايقاف')
                   ->icon('heroicon-o-no-symbol')
                   ->modalWidth(MaxWidth::Small)
                   ->color('danger')
                   ->visible(function () {return $this->Main->raseed<=0 && $this->showInfo
                   && !stop_kst::where('no',$this->Main->no)->first() &&
                       Auth::user()->can('ادخال فائض وترجيع');})
                   ->iconButton()
                   ->form([
                       Section::make([
                           DatePicker::make('stop_date')
                               ->required()
                               ->label('التاريح'),
                       ])
                   ])
                   ->fillForm(function (){
                       return [
                           'stop_date'=>date('Y-m-d'),
                       ];
                   })
                   ->modalCancelActionLabel('عودة')
                   ->modalSubmitActionLabel('تحزين')
                   ->modalHeading('رسالة ايقاف الخصم')
                   ->extraAttributes(['style' => 'margin: 2px;'])
                   ->action(function (array $data){
                       stop_kst::on(Auth()->user()->company)->insert([
                           'no'=>$this->no,'name'=>$this->Main->name,'bank'=>$this->Main->bank,
                           'acc'=>$this->Main->acc,'stop_type'=>1,'stop_date'=>$data['stop_date'],
                           'letters'=>0,'emp'=>auth::user()->empno,
                       ]);

                       $this->dispatch('showMe',no: $this->no);
                   }),
               Actions\Action::make('الغاء_الايقاف')
                   ->link()
                   ->tooltip('الغاء رسالة الايقاف')
                   ->label('هذا العقد موقوف')
                   ->icon('heroicon-o-no-symbol')
                   ->color('danger')
                   ->visible(function () {return $this->Main->raseed<=0 && $this->showInfo
                       && stop_kst::where('no',$this->Main->no)->first() &&
                       Auth::user()->can('ادخال فائض وترجيع');})

                   ->extraAttributes(['style' => 'margin: 2px;'])
                   ->requiresConfirmation()
                   ->modalHeading('هل انت متأكد من الغاء رسالة الابقاف')
                   ->action(function (array $data){
                       stop_kst::where('no',$this->Main->no)->delete();
                       $this->dispatch('showMe',no: $this->no);
                   }),
               Actions\Action::make('نقل_للأرشيف')
                   ->icon('heroicon-s-archive-box-arrow-down')
                   ->iconButton()
                   ->color('info')
                   ->visible(function (){return $this->showInfo && $this->Main->raseed<=0 &&
                       Auth::user()->can('ادخال عقود');})
                   ->outlined()
                   ->requiresConfirmation()
                   ->extraAttributes(['style' => 'margin: 2px;'])
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
           ])->columnSpan(2),

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
                        if (strlen($this->Main->name)>30) return TextEntry\TextEntrySize::ExtraSmall;
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
                    ->size(TextEntry\TextEntrySize::ExtraSmall)
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
                    ->size(TextEntry\TextEntrySize::ExtraSmall)
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
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
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

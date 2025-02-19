<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Models\aksat\MainArc;
use App\Models\NewModel\Nmain;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\over_kst_a;
use App\Models\OverTar\tar_kst;
use App\Models\sell\sells;
use App\Models\stores\halls_names;
use App\Models\stores\stores_names;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class MainArcModal extends Page implements HasInfolists
{
    use InteractsWithInfolists;
    protected ?string $heading="";
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.aksat.rep.main-arc-modal';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public $no;
    public  $Main;
    public $order_no;
    public $showOver=0;
    public $showTar=0;

    #[On('showMainArcMolal')]
    public function showMainArcMolal($no){
        $this->no = $no;
        $this->Main=MainArc::where('no',$no)->first();
        $this->order_no=sells::find($this->Main->order_no);
        $this->showOver= over_kst_a::where('no',$this->no)->count();
        $this->showTar= tar_kst::where('no',$this->no)->count();
        $this->dispatch('KstTranNoArc',no: $no);
        $this->dispatch('MainItemOrderArc',order_no: $this->order_no->order_no);
        $this->dispatch('OverKstNoArc',no: $no);
        $this->dispatch('TarKstNoArc',no: $no);
    }
    public function mount(): void
    {
      $this->Main=MainArc::first();
        $this->order_no=sells::find($this->Main->order_no);
    }
    public function mainArcInfolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->record($this->Main)
            ->schema([
                TextEntry::make('name')
                    ->color('primary')
                    ->extraEntryWrapperAttributes(['style' => 'height: 16px;'])
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('jeha')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> رقم الزبون&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->color('info')
                    ->columnSpan(3),
                TextEntry::make('acc')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="ttext-gray-600 dark:text-white "> رقم الحساب&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('bank.bank_name')
                    ->color('primary')
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('place.place_name')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> جهة العمل&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->columnSpan(3),
                TextEntry::make('sell_point')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> نقطة البيع&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->color('info')
                    ->state(function (){
                        if ($this->order_no->sell_type==1) return stores_names::find($this->order_no->place_no)->st_name;
                        else return halls_names::find($this->order_no->place_no)->hall_name;
                    })

                    ->columnSpan(3),
                TextEntry::make('sul_date')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ت.العقد&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->columnSpan(2),
                TextEntry::make('sul_tot')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ج.الفاتورة&nbsp;&nbsp;</span>'))
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('cash')
                    ->color('info')
                    ->state(function (){
                        return $this->order_no->cash;
                    })
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> المدفوع&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('sul')
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ج.التقسيط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('sul_pay')
                    ->color('info')
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> المسدد&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('raseed')
                    ->color('danger')
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white"> المطلوب&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('kst_count')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> عدد الأقساط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->columnSpan(2),
                TextEntry::make('kst')
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> القسط&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
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
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])
                    ->color('info')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> متبقية&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->columnSpan(2),
                TextEntry::make('notes')
                    ->color('success')
                    ->prefix(new HtmlString('<span class="text-gray-600 dark:text-white "> ملاحظات&nbsp;&nbsp;</span>'))
                    ->hiddenLabel()
                    ->visible(function (MainArc $record){
                        return $record->notes!=null;
                    })
                    ->extraEntryWrapperAttributes(['style' => 'height: 12px;'])

                    ->columnSpan(6),

            ])->columns(6);
    }
}

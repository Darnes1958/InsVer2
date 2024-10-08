<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\Contract;
use App\Models\aksat\main;
use App\Models\bank\bank;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use PhpParser\Node\Expr\BinaryOp\Mod;
use Filament\Tables\Columns\TextColumn;

class MainSearch extends BaseWidget
{

  public $theKey;
  public $bank;
  public $limit=10;
  public $By=false;
  public $showSearch = true;
  protected static ?string $heading="";

  public function Do($no,$order_no,$jeha)
  {
      $this->dispatch('KstTranNo',no: $no);
      $this->dispatch('showMe',no: $no);
      $this->dispatch('MainItemOrder',order_no: $order_no);
      $this->dispatch('OverKstNo',no: $no);
      $this->dispatch('TarKstNo',no: $no);
      $this->dispatch('ContJeha',jeha: $jeha,no: $no);
  }
    #[On('takeBank')]
    public function takeBank($bank,$by){
        $this->By=$by;
        $this->bank=$bank;
    }
    #[On('resetSearch')]
    public function resetSearch(){
        $this->resetTable();
    }
    #[On('changeLimit')]
    public function changeLimit($limit){$this->limit=$limit;}

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('no','desc')
            ->paginated(false)
         //   ->defaultSort('no','desc')
            ->searchPlaceholder('بحث برقم الحساب او الاسم')
            ->queryStringIdentifier('MainSearch')

            ->query(function (main $main){
              $main=main::query()
                  ->when($this->bank,function ($q){
                      if (!$this->By)
                         $q->where('bank',$this->bank);
                      else
                          $q->whereIn('bank',bank::where('bank_tajmeeh',$this->bank)->pluck('bank_no'));
              })
                 ->limit($this->limit);


             return $main;
            })
            ->columns([
              Tables\Columns\TextColumn::make('no')
                  ->action(function (main $record){
                      $this->Do($record->no,$record->order_no,$record->jeha);
                  })
                  ->color('primary')
                  ->size(TextColumnSize::ExtraSmall)
                  ->label(new HtmlString('<span  style="font-size: smaller;">الرقم</span>')),
              TextColumn::make('name')
                  ->searchable()
                  ->action(function (main $record): void{
                      $this->Do($record->no,$record->order_no,$record->jeha);                     }
                  )
                  ->limit(25)
                  ->tooltip(function (TextColumn $column): ?string {
                      $state = $column->getState();
                      if (strlen($state) < 50) {
                          return null;
                      }
                      return $state;
                  })
                  ->size(TextColumnSize::ExtraSmall)
                  ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">الاسم</span>')),
              TextColumn::make('acc')
                  ->searchable()
                  ->action(function (main $record){
                      $this->Do($record->no,$record->order_no,$record->jeha);
                  })
                  ->size(TextColumnSize::ExtraSmall)
                  ->color('info')
                  ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">رقم الحساب</span>')),
              TextColumn::make('sul')
                  ->action(function (main $record){
                      $this->Do($record->no,$record->order_no,$record->jeha);
                  })
                  ->numeric(
                      decimalPlaces: 0,
                      decimalSeparator: '',
                      thousandsSeparator: ',',
                  )
                    ->size(TextColumnSize::ExtraSmall)
                  ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">الاجمالي</span>')),
              TextColumn::make('kst')
                  ->action(function (main $record){
                      $this->Do($record->no,$record->order_no,$record->jeha);
                  })
                  ->numeric(
                      decimalPlaces: 0,
                      decimalSeparator: '',
                      thousandsSeparator: '',
                  )
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">القسط</span>')),
            ])
            ;

    }
}

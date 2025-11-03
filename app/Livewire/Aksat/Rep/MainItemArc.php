<?php

namespace App\Livewire\Aksat\Rep;
use Filament\Support\Enums\TextSize;
use App\Models\sell\sell_tran;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class MainItemArc extends BaseWidget
{
    public $order_no;
    protected static ?string $heading="";
    #[On('MainItemOrderArc')]
    public function MainItemOrderArc($order_no){
        $this->order_no=$order_no;
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->emptyStateHeading('لا توجد بيانات')
            ->query(function (sell_tran $main){
                $main=sell_tran::where('order_no',$this->order_no);
                return $main;
            })
            ->columns([
                TextColumn::make('item_no')
                    ->color('primary')
                    ->size(TextSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">رقم الصنف</span>')),
                TextColumn::make('item.item_name')
                    ->size(TextSize::ExtraSmall)
                    ->limit(25)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) < 25) {
                            return null;
                        }
                        return $state;
                    })
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">اسم الصنف</span>')),

                TextColumn::make('quant')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: '',
                    )
                    ->size(TextSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">الكمية</span>')),
                TextColumn::make('price')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->size(TextSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">السعر</span>')),
            ]);
    }}

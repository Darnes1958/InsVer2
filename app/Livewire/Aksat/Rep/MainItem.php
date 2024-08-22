<?php

namespace App\Livewire\Aksat\Rep;
use App\Models\sell\sell_tran;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class MainItem extends BaseWidget
{
    public $order_no;
    protected static ?string $heading="";
    #[On('MainItemOrder')]
    public function MainItemOrder($order_no){
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
                Tables\Columns\TextColumn::make('item_no')
                    ->color('primary')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">رقم الصنف</span>')),
                Tables\Columns\TextColumn::make('item.item_name')
                    ->size(TextColumnSize::ExtraSmall)
                    ->limit(25)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) < 25) {
                            return null;
                        }
                        return $state;
                    })
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">اسم الصنف</span>')),

                Tables\Columns\TextColumn::make('quant')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: '',
                    )
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">الكمية</span>')),
                Tables\Columns\TextColumn::make('price')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(new HtmlString('<span class="text-sky-700 " style="font-size: smaller;">السعر</span>')),
            ]);
    }}

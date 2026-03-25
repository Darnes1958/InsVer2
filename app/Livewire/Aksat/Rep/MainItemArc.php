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
            ->query(function (){
                $main=sell_tran::where('order_no',$this->order_no);
                return $main;
            })
            ->columns([
                TextColumn::make('item_no')
                    ->color('primary')
                    ->size(TextSize::ExtraSmall)
                    ->extraHeaderAttributes(['class' => "text-sky-700" , 'style' => "font-size: smaller;"])
                ->label('رقم الصنف'),
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
                    ->extraHeaderAttributes(['class' => "text-sky-700" , 'style' => "font-size: smaller;"])
                  ->label('اسم الصنف'),

                TextColumn::make('quant')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: '',
                    )
                    ->size(TextSize::ExtraSmall)
                    ->extraHeaderAttributes(['class' => "text-sky-700" , 'style' => "font-size: smaller;"])
                  ->label('الكمية'),
                TextColumn::make('price')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->size(TextSize::ExtraSmall)
                    ->extraHeaderAttributes(['class' => "text-sky-700" , 'style' => "font-size: smaller;"])
                 ->label('السعر'),
            ]);
    }}

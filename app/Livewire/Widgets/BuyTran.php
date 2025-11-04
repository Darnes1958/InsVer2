<?php

namespace App\Livewire\Widgets;

use App\Models\buy\buy_tran;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BuyTran extends BaseWidget
{
  public function mount($order_no){
    $this->order_no=$order_no;
  }
  protected static ?string $heading='';
  public $order_no;
    public function table(Table $table): Table
    {
        return $table
          ->query(function (){
            return buy_tran::where('order_no',$this->order_no);

          })
            ->columns([
              TextColumn::make('item_no')
                ->label('رقم الصنف')
                ->sortable(),
              TextColumn::make('Item.item_name')
                ->label('اسم الصنف')
                ->sortable(),
              TextColumn::make('quant')
                ->label('الكمية')
                ->sortable(),
              TextColumn::make('price')
                ->label('سعر الشراء')
                ->numeric(
                  decimalPlaces: 2,
                  decimalSeparator: '.',
                  thousandsSeparator: ',',
                )
                ->sortable(),
              TextColumn::make('subtot')
                ->label('المجموع')
                ->numeric(
                  decimalPlaces: 2,
                  decimalSeparator: '.',
                  thousandsSeparator: ',',
                )
                ->sortable(),
            ]);
    }
}

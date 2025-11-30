<?php

namespace App\Livewire\Widgets;



use App\Models\stores\store_exp_view;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;


class PerTran extends BaseWidget
{
  public function mount($per_no){
    $this->per_no=$per_no;
  }
  public function getTableRecordKey(Model|array $record): string
  {
      return uniqid();
  }

    protected static ?string $heading='';
  public $per_no;
    public function table(Table $table): Table
    {
        return $table
          ->query(function (){
            return store_exp_view::where('per_no',$this->per_no);

          })
            ->queryStringIdentifier('trans')
            ->columns([
              TextColumn::make('item_no')
                ->label('رقم الصنف')
                ->sortable(),
              TextColumn::make('item_name')
                ->label('اسم الصنف')
                ->sortable(),
              TextColumn::make('quant')
                ->label('الكمية')
                ->sortable(),

            ])
           ;
    }
}

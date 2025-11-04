<?php

namespace App\Filament\Resources\FromExcelResource\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use App\Models\Dateofexcel;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class FromExcelWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
              $dateofexcel= Dateofexcel::where('taj_id',Auth::user()->IsAdmin);
              return $dateofexcel;
              }
            )
            ->defaultSort('date_begin','desc')
            ->columns([
                TextColumn::make('date_begin'),
                TextColumn::make('date_end'),
                TextColumn::make('taj_id'),
            ])
         ->recordActions([
           DeleteAction::make(),

          ]);
    }
}

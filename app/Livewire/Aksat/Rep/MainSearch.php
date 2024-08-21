<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\Contract;
use App\Models\aksat\main;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use PhpParser\Node\Expr\BinaryOp\Mod;
use Filament\Tables\Columns\TextColumn;

class MainSearch extends BaseWidget
{

  public $theKey;
  public $showSearch = true;
  protected static ?string $heading="";

  public function Do($no)
  {
      $this->dispatch('takeNo',no: $no);
      $this->dispatch('showMe',no: $no);
  }

    public function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,50])
         //   ->defaultSort('no','desc')
            ->searchPlaceholder('بحث برقم الحساب او الاسم')
            ->query(function (main $main){
              $main=main::query();


             return $main;
            })

            ->columns([
              Tables\Columns\TextColumn::make('no')
                  ->action(function (main $record){
                      $this->Do($record->no);
                  })
                  ->color('primary')
                  ->size(TextColumnSize::ExtraSmall)
                  ->label('الرقم'),
              TextColumn::make('name')
                  ->searchable()
                  ->action(function (main $record): void{
                      $this->Do($record->no);                      }
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
                ->label('الاسم'),
              TextColumn::make('acc')
                  ->searchable()
                  ->action(function (main $record){
                      $this->Do($record->no);
                  })
                  ->size(TextColumnSize::ExtraSmall)
                  ->color('info')
                ->label('رقم الحساب'),
              TextColumn::make('sul')
                  ->action(function (main $record){
                      $this->Do($record->no);
                  })
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الاجمالي'),
              TextColumn::make('kst')
                  ->action(function (main $record){
                      $this->Do($record->no);
                  })
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('القسط'),
            ])
            ;

    }
}

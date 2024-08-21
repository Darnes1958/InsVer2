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
  public $mysearch;
  public $theKey;
  protected static ?string $heading="";
  #[On('takeSearch')]
  public function takeSearch($mysearch,$no){
    $this->mysearch=$mysearch;
    $this->theKey=$no;
  }

    public function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,50])
            ->defaultSort('no','desc')
            ->query(function (main $main){
              $main=main::query();



             return $main;

            })

            ->columns([
              Tables\Columns\TextColumn::make('no')
                  ->action(function (main $record){
                      $this->theKey=$record->no;
                      $this->dispatch('takeNo',no: $this->theKey);
                      $this->dispatch('showMe',no: $this->theKey);
                  })
                  ->color('primary')
                  ->size(TextColumnSize::ExtraSmall)
                  ->label('الرقم'),
              TextColumn::make('name')
                  ->searchable()
                  ->action(function (main $record): void{

                          $this->theKey=$record->no;
                          $this->dispatch('takeNo',no: $this->theKey);
                          $this->dispatch('showMe',no: $this->theKey);
                      }
                  )
                  ->limit(25)

                  ->size(TextColumnSize::ExtraSmall)
                ->label('الاسم'),
              TextColumn::make('acc')
                  ->searchable()
                  ->size(TextColumnSize::ExtraSmall)
                  ->color('info')
                ->label('رقم الحساب'),
              TextColumn::make('sul')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الاجمالي'),
              TextColumn::make('kst')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('القسط'),
            ])
            ;

    }
}

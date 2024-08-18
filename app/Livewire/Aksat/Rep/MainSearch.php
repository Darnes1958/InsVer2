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

class MainSearch extends BaseWidget
{
  public $mysearch;
  public $no;
  protected static ?string $heading="";
  #[On('takeSearch')]
  public function takeSearch($mysearch,$no){
    $this->mysearch=$mysearch;
    $this->no=$no;
  }

    public function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,50])
            ->defaultSort('no','desc')
            ->query(function (){
              return Contract::
                when($this->no,function ($q){
                    $q->where('no',$this->no);
                })
                ->when(!$this->no,function ($q){
                      $q->where('name', 'like', '%'.$this->mysearch.'%')
                        ->orwhere('acc', 'like', '%'.$this->mysearch.'%');
                  });


            })

            ->columns([
              Tables\Columns\TextColumn::make('no')
                  ->action(function ($state){
                      $this->no=$state;
                      $this->dispatch('takeNo',no: $this->no);
                      $this->dispatch('showMe',no: $this->no);
                  })
                  ->color('primary')
                  ->size(TextColumnSize::ExtraSmall)
                  ->label('الرقم'),
              Tables\Columns\TextColumn::make('name')
                  ->action(function (Contract $record){
                      $this->no=$record->no;
                      $this->dispatch('takeNo',no: $this->no);
                      $this->dispatch('showMe',no: $this->no);
                  })
                  ->limit(25)
                  ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                      $state = $column->getState();
                      if (strlen($state) <= $column->getCharacterLimit()) {
                          return null;
                      }

                      // Only render the tooltip if the column content exceeds the length limit.
                      return $state;
                  })
                  ->size(TextColumnSize::ExtraSmall)
                ->label('الاسم'),
              Tables\Columns\TextColumn::make('acc')
                  ->size(TextColumnSize::ExtraSmall)
                  ->color('info')
                ->label('رقم الحساب'),
                Tables\Columns\TextColumn::make('sul')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الاجمالي'),
                Tables\Columns\TextColumn::make('kst')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('القسط'),
            ])
            ;

    }
}

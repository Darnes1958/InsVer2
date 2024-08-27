<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\kst_trans;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\over_kst_a;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class OverKstArc extends BaseWidget
{
    public $no;
    protected static ?string $heading="";
    #[On('OverKstNoArc')]
    public function OverKstNoArc($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(new HtmlString('<span style="font-size: smaller;color: #00bb00">خصم بالفائض&nbsp;&nbsp;</span>'))
            ->emptyStateHeading('لا توجد بيانات')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,15])
            ->defaultSort('tar_date')
            ->query(function (over_kst_a $main){
                $main=over_kst_a::where('no',$this->no);
                return $main;
            })
            ->columns([
                Tables\Columns\TextColumn::make('ser')
                    ->rowIndex()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('ت'),
                Tables\Columns\TextColumn::make('tar_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('التاريخ'),
                Tables\Columns\TextColumn::make('kst')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('المبلغ'),

            ]);
    }
}

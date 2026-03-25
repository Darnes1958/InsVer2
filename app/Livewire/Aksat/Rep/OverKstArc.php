<?php

namespace App\Livewire\Aksat\Rep;

use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\TextSize;
use App\Models\aksat\kst_trans;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\over_kst_a;
use Filament\Tables;
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
            ->query(function (){
                $main=over_kst_a::where('no',$this->no);
                return $main;
            })
            ->queryStringIdentifier('OverKstArc')
            ->columns([
                TextColumn::make('ser')
                    ->rowIndex()
                    ->size(TextSize::ExtraSmall)
                    ->label('ت'),
                TextColumn::make('tar_date')
                    ->size(TextSize::ExtraSmall)
                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->size(TextSize::ExtraSmall)
                    ->label('المبلغ'),

            ]);
    }
}

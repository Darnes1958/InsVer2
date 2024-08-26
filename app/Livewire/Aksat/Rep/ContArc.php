<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\MainArc;
use App\Models\OverTar\tar_kst;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class ContArc extends BaseWidget
{
    public $jeha;
    protected static ?string $heading="";
    #[On('ContJeha')]
    public function ContJeha($jeha){

        $this->jeha=$jeha;
    }
    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(new HtmlString('<span style="font-size: smaller;color: #00bb00">عقود سابقة (أرشيف)&nbsp;&nbsp;</span>'))
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,15])
            ->defaultSort('sul_date')
            ->query(function (MainArc $main){
                $main=MainArc::where('jeha',$this->jeha);
                return $main;
            })
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الرقم'),
                Tables\Columns\TextColumn::make('sul_date')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('التاريخ'),

                Tables\Columns\TextColumn::make('sul')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الاجمالي'),
                Tables\Columns\TextColumn::make('kst')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('القسط'),

            ]);
    }
}

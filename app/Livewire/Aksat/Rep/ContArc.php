<?php

namespace App\Livewire\Aksat\Rep;

use App\Models\aksat\MainArc;
use App\Models\OverTar\over_kst_a;
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

    public function Do($no){
        $over=over_kst_a::where('no',$no)->count();
        $this->dispatch('ArcData',arcNo: $no,arcOver: $over);
        $this->dispatch('showMainArcMolal',no: $no);
        $this->dispatch('open-modal', id: 'mymainModal',no: $no,over: $over);
    }
    public function table(Table $table): Table
    {
        return $table

            ->paginated(false)
            ->defaultSort('sul_date')
            ->query(function (MainArc $main){
                $main=MainArc::where('jeha',$this->jeha);
                return $main;
            })

            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->tooltip('انقر للعرض')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('الرقم'),
                Tables\Columns\TextColumn::make('sul_date')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->tooltip('انقر للعرض')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('التاريخ'),

                Tables\Columns\TextColumn::make('sul')
                    ->size(TextColumnSize::ExtraSmall)
                    ->tooltip('انقر للعرض')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->label('الاجمالي'),
                Tables\Columns\TextColumn::make('kst')
                    ->size(TextColumnSize::ExtraSmall)
                    ->tooltip('انقر للعرض')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->label('القسط'),
            ]);
    }
}

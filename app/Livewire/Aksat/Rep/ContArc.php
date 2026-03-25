<?php

namespace App\Livewire\Aksat\Rep;

use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\TextSize;
use App\Models\aksat\MainArc;
use App\Models\OverTar\over_kst_a;
use App\Models\OverTar\tar_kst;
use Filament\Tables;
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
    public function ContJeha($jeha,$no){

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
            ->query(function (){
                $main=MainArc::where('jeha',$this->jeha);
                return $main;
            })

            ->recordUrl(null)
            ->columns([
                TextColumn::make('no')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->tooltip('انقر للعرض')
                    ->size(TextSize::ExtraSmall)
                    ->extraHeaderAttributes([ 'style' => "font-size: smaller;color: #00bb00;"])
                  ->label('عقود سابقة'),
                TextColumn::make('sul_date')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->tooltip('انقر للعرض')
                    ->size(TextSize::ExtraSmall)
                    ->label('التاريخ'),

                TextColumn::make('sul')
                    ->size(TextSize::ExtraSmall)
                    ->tooltip('انقر للعرض')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->label('الاجمالي'),
                TextColumn::make('kst')
                    ->size(TextSize::ExtraSmall)
                    ->tooltip('انقر للعرض')
                    ->action(function (MainArc $record){$this->Do($record->no);})
                    ->label('القسط'),
            ]);
    }
}

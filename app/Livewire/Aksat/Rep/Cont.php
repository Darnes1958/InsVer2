<?php

namespace App\Livewire\Aksat\Rep;

use Filament\Support\Enums\TextSize;
use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use App\Models\OverTar\over_kst_a;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class Cont extends BaseWidget
{
    public $jeha;
    public $no;
    protected static ?string $heading="";
    #[On('ContJeha')]
    public function ContJeha($jeha,$no){
        $this->jeha=$jeha;
        $this->no=$no;
    }

    public function Do($no){
        $this->dispatch('showMe',no: $no);    }
    public function table(Table $table): Table
    {
        return $table

            ->paginated(false)
            ->defaultSort('sul_date')
            ->query(function (){
                $main=main::where('jeha',$this->jeha)->where("no",'!=',$this->no);
                return $main;
            })

            ->recordUrl(null)
            ->columns([
                TextColumn::make('no')
                    ->action(function (main $record){$this->Do($record->no);})
                    ->tooltip('انقر للعرض')
                    ->size(TextSize::ExtraSmall)
                    ->extraHeaderAttributes([ 'style' => "font-size: smaller;color: #00bb00;"])
                    ->label('عقود قائمة'),
                TextColumn::make('sul_date')
                    ->action(function (main $record){$this->Do($record->no);})
                    ->tooltip('انقر للعرض')
                    ->size(TextSize::ExtraSmall)
                    ->label('التاريخ '),

                TextColumn::make('sul')
                    ->size(TextSize::ExtraSmall)
                    ->tooltip('انقر للعرض')
                    ->action(function (main $record){$this->Do($record->no);})
                    ->label('الاجمالي'),
                TextColumn::make('kst')
                    ->size(TextSize::ExtraSmall)
                    ->tooltip('انقر للعرض')
                    ->action(function (main $record){$this->Do($record->no);})
                    ->label('القسط'),
            ]);
    }
}

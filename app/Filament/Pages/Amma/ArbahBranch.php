<?php

namespace App\Filament\Pages\Amma;

use App\Livewire\Traits\PublicTrait;
use App\Models\aksat\main_sells_bank_view;
use App\Models\aksat\place;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArbahBranch extends Page  implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    use PublicTrait;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.amma.arbah-branch';

    protected ?string $heading='تقرير بالارباح حسب المصارف ونقاط البيع';
    protected static ?string $navigationLabel='الارباح حسب المصارف واماكن الييع';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    public $Date1;
    public $Date2;

    public function getTableRecordKey(Model $record): string
    {
     return $record->place_name;
    }
    public function mount(): void {
        $date = now();

        $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();
        $this->Date1=$startOfYear;
        $this->Date2=$endOfYear;
        $this->form->fill(['Date1' => $this->Date1, 'Date2' => $this->Date2]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('Date1')
                    ->afterStateUpdated(function ($state) {$this->Date1=$state;})
                    ->live()
                    ->label('من تاريخ'),
                DatePicker::make('Date2')
                    ->afterStateUpdated(function ($state) {$this->Date2=$state;})
                    ->live()
                    ->label('إلي تاريخ'),
            ])->columns(8);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                return main_sells_bank_view::query()
                 ->select('place_name','TajName',DB::raw('sum(rebh) as rebh,sum(sul) as sul'))
                 ->where('sul_date','>=',$this->Date1)
                 ->where('sul_date','<=',$this->Date2)
                 ->groupBy('place_name','TajName')
                 ->orderBy('place_name')   ;
                            })
            ->columns([
                TextColumn::make('place_name')
                 ->label('نقطة البيع'),
                TextColumn::make('TajName')
                    ->label('المصرف'),
                TextColumn::make('sul')
                    ->label('اجمالي العقود'),
                TextColumn::make('rebh')
                    ->label('الربح'),
            ])
            ;
    }
}

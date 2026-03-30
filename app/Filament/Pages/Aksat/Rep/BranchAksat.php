<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Enums\BankTaj;
use App\Models\aksat\main_sells_bank_view;
use App\Models\bank\BankTajmeehy;
use App\Models\stores\halls_names;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchAksat extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    protected string $view = 'filament.pages.aksat.rep.branch-aksat';

    protected ?string $heading='تقرير بالأقساط المحصلة خلال فترة حسب الفروع';
    protected static ?string $navigationLabel='الأقساط المحصلة حسب الفروع';


    public $Date1;
    public $Date2;
    public $taj_id;


   public function getTableRecordKey(Model|array $record): string
   {
       return $record->hall_name;
   }
    public function mount(): void {
        $date = now();

        $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();
        $this->Date1=$startOfYear;
        $this->Date2=$endOfYear;
        $this->form->fill(['Date1' => $this->Date1, 'Date2' => $this->Date2]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('taj_id')
                 ->options(BankTajmeehy::all()->pluck('TajName', 'TajNo'))
                 ->afterStateUpdated(fn($state)=>$this->taj_id=$state)
                 ->searchable()
                    ->live()
                 ->columnSpan(2)
                 ->preload()
                 ->label('المصرف التجميعي'),
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
                return halls_names::query()
                    ->selectRaw('hall_name,dbo.ksmValue(?,hall_no,?,?) as ksmValue,dbo.ksmCount(?,hall_no,?,?) ksmCount',
                        [$this->taj_id,$this->Date1,$this->Date2,$this->taj_id,$this->Date1,$this->Date2]);
            })
            ->defaultKeySort(false)

            ->columns([
                TextColumn::make('hall_name')
                    ->label('الفرع'),
                TextColumn::make('ksmValue')
                    ->summarize(Sum::make()->label('')->numeric(2,'.',','))
                    ->numeric(2,'.',',')
                    ->label('اجمالي الاقساط المخصومة'),
                TextColumn::make('ksmCount')
                    ->summarize(Sum::make()->label('')->numeric(0,'.',','))
                    ->numeric(0,'',',')
                    ->label('عدد الاقساط المخصومة'),
            ]);
    }


}

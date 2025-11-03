<?php

namespace App\Filament\Pages\Aksat\Rep;

use Filament\Schemas\Components\Section;
use App\Exports\KhamlaXls;
use App\Exports\Khasf;
use App\Exports\MosdadaXls;
use App\Exports\Motakra;
use App\Models\aksat\kst_trans;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\stop_kst;
use App\Models\Wrongkst;
use App\Traits\reportTrait;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RepBankAll extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable,reportTrait;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.aksat.rep.rep-bank-all';

    protected static ?string $navigationLabel='اجمالي المصارف';
    protected ?string $heading='';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('عقود');
    }

    public $bankData;
    public $By='Bank';
    public $from='main';

    public function mount(): void
    {
        $this->bankForm->fill([
            'By'=>$this->By,
            'from'=>$this->from,
        ]);
    }
    protected function getForms(): array
    {
        return array_merge(parent::getForms(), [
            "bankForm" => $this->makeForm()
                ->model(bank::class)
                ->components($this->getbankFormSchema())
                ->statePath('bankData'),

        ]);
    }
    protected function getbankFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    Radio::make('By')
                        ->afterStateUpdated(function ($state){$this->By=$state;})
                        ->hiddenLabel()
                        ->inline()
                        ->inlineLabel(false)
                        ->live()
                        ->columnSpan(2)
                        ->options([
                            'Bank'=>'فروع',
                            'Taj'=>'تجميعي'
                        ]),
                    Radio::make('from')
                        ->afterStateUpdated(function ($state){$this->from=$state;})
                        ->hiddenLabel()
                        ->inline()
                        ->inlineLabel(false)
                        ->live()
                        ->columnSpan(2)
                        ->options([
                            'main'=>'القائمة',
                            'MainArc'=>'الأرشيف'
                        ]),
                ])
                ->columns(6)
        ];
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(function (){

                if ($this->By=='Bank') return   bank::has($this->from);
                if ($this->By=='Taj') return   BankTajmeehy::has($this->from);

            }
            )
            ->paginated([5,10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('لا توجد بيانات')
            ->columns([
                TextColumn::make('bank_name')
                    ->hidden(fn(): bool=>$this->By=='Taj')
                    ->label('الاسم'),
                TextColumn::make('TajName')
                    ->hidden(fn(): bool=>$this->By=='Bank')
                    ->label('المصرف التجميعي'),
                TextColumn::make('main_count')
                    ->visible(function (){return $this->from=='main';})
                    ->counts('main')
                    ->summarize(Sum::make()->label(''))
                    ->label('عدد العقود'),
                TextColumn::make('main_sum_sul')
                    ->visible(function (){return $this->from=='main';})
                    ->sum('main','sul')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('اجمالي العقود'),
                TextColumn::make('main_sum_sul_pay')
                    ->visible(function (){return $this->from=='main';})
                    ->sum('main','sul_pay')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('المسدد'),
                TextColumn::make('main_sum_raseed')
                    ->visible(function (){return $this->from=='main';})
                    ->sum('main','raseed')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('الرصيد'),

                TextColumn::make('mainarc_count')
                    ->visible(function (){return $this->from=='MainArc';})
                    ->counts('mainarc')
                    ->summarize(Sum::make()->label(''))
                    ->label('عدد العقود'),
                TextColumn::make('mainarc_sum_sul')
                    ->visible(function (){return $this->from=='MainArc';})
                    ->sum('mainarc','sul')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('اجمالي العقود'),
                TextColumn::make('mainarc_sum_sul_pay')
                    ->visible(function (){return $this->from=='MainArc';})
                    ->sum('mainarc','sul_pay')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('المسدد'),
                TextColumn::make('mainarc_sum_raseed')
                    ->visible(function (){return $this->from=='MainArc';})
                    ->sum('mainarc','raseed')
                    ->summarize(Sum::make()->label('')->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('الرصيد'),

            ]);
    }

}

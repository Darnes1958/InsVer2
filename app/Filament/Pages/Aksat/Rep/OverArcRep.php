<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Enums\BankTaj;
use App\Enums\Morahel;
use App\Livewire\AKsat\Rep\OverKst;
use App\Livewire\Traits\PublicTrait;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\over_kst_a;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class OverArcRep extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    use PublicTrait;

    protected static ?string $model =over_kst::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.aksat.rep.over-rep';
    protected static ?string $navigationLabel='الفائض من الأرشيف';
    protected static ?int $navigationSort=6;
    protected ?string $heading='الخصم بالفائض من الأرشيف';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('فائض وترجيع');
    }

    public $By='taj';
    public $letters=0;
    public $bank_id;
    public $taj_id;
    public $bank_name;
    public $Date1;
    public $Date2;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Radio::make('By')
                ->hiddenLabel()
                ->afterStateUpdated(function ($state) {$this->By=$state;})
                ->live()
                ->options(BankTaj::class),
               Radio::make('letters')
               ->hiddenLabel()
               ->afterStateUpdated(function ($state) {$this->letters=$state;})
               ->live()
               ->options(Morahel::class),
                Select::make('taj_id')
                    ->options(BankTajmeehy::all()->pluck('TajName', 'TajNo'))
                    ->searchable()
                    ->afterStateUpdated(function ($state) {$this->taj_id=$state;
                        $this->bank_name=BankTajmeehy::find($state)->TajName;})
                    ->visible(function () { return $this->By=='taj';})
                    ->columnSpan(2)
                    ->live()
                    ->label('المصرف التجميعي'),
                Select::make('bank_id')
                    ->options(bank::all()->pluck('bank_name', 'bank_no'))
                    ->afterStateUpdated(function ($state) {$this->bank_id=$state;
                    $this->bank_name=bank::find($state)->bank_name;})
                    ->searchable()
                    ->columnSpan(2)
                    ->live()
                    ->visible(function () { return $this->By=='bank';})
                    ->label('فرع المصرف'),
                DatePicker::make('Date1')
                    ->afterStateUpdated(function ($state) {$this->Date1=$state;})
                    ->live()
                    ->label('من تاريخ'),
                DatePicker::make('Date2')
                    ->afterStateUpdated(function ($state) {$this->Date2=$state;})
                    ->live()
                    ->label('إلي تاريخ'),
                Actions::make([
                    Action::make('print')
                     ->label('طباعة')
                        ->icon('heroicon-o-printer')
                        ->color('blue')

                        ->action(function (){
                                   $arr=[];
                                   $arr['bank_name']=$this->bank_name;
                                   $date='';
                                   if ($this->Date1) $date='من تاريخ '.$this->Date1;
                                   if ($this->Date2) $date=$date.' إلي تاريخ '.$this->Date2;
                                   $arr['date']=$date;
                                   $arr['Table']='over_kst_a';
                                   $arr['letters']=$this->letters;
                                   return Response::download(self::ret_spatie($this->getTableQueryForExport()->get(),
                                     'PrnView.aksat.pdf-over',$arr), 'filename.pdf', self::ret_spatie_header());
                        })
                    ,
                ])->verticalAlignment(VerticalAlignment::End)

            ])->columns(10);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                return over_kst_a::query()
                    ->where('letters',$this->letters)
                    ->when($this->By=='bank',function($query){
                        $query->where('bank',$this->bank_id);
                    })
                    ->when($this->By=='taj',function($query){
                        $query->whereIn('bank',bank::where('bank_tajmeeh',$this->taj_id)->pluck('bank_no'));
                    })
                    ->when($this->Date1,function($query){
                        $query->where('tar_date','>=',$this->Date1);
                    })
                    ->when($this->Date2,function($query){
                        $query->where('tar_date','<=',$this->Date2);
                    })
                    ;
            })
            ->striped()
            ->pluralModelLabel('الفائض')
            ->columns([
                    self::getMy('no'),
                    self::getMy('name'),
                    self::getMy('acc'),
                    self::getMy('tar_date'),
                    self::getMy('kst')
                    ->label('المبلغ')
                        ->summarize(Sum::make()->numeric(
                            decimalPlaces: 2,
                            decimalSeparator: '.',
                            thousandsSeparator: ',',
                        )->label(''))
                        ->numeric(
                            decimalPlaces: 2,
                            decimalSeparator: '.',
                            thousandsSeparator: ',',
                        ),
            ]

            );
    }
}

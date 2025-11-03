<?php

namespace App\Filament\Pages\Aksat\Rep;

use Filament\Schemas\Schema;
use App\Enums\BankTaj;
use App\Enums\Morahel;
use App\Enums\Mosahah;
use App\Livewire\Traits\PublicTrait;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;

use App\Models\OverTar\over_kst;
use App\Models\OverTar\tar_kst;
use App\Models\OverTar\wrong_Kst;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class Wrong extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    use PublicTrait;

    protected static ?string $model =wrong_Kst::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.aksat.rep.wrong';
    protected static string | \UnitEnum | null $navigationGroup='فائض وترجيع';
    protected static ?string $navigationLabel='بالخطأ';
    protected static ?int $navigationSort=7;
    protected ?string $heading='أقساط واردة بالخطأ';


    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('فائض وترجيع');
    }
    public $By='taj';
    public $morahel=0;
    public $bank_id;
    public $taj_id;
    public $bank_name;
    public $Date1;
    public $Date2;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Radio::make('By')
                    ->hiddenLabel()
                    ->afterStateUpdated(function ($state) {$this->By=$state;})
                    ->live()
                    ->options(BankTaj::class),
                Radio::make('morahel')
                    ->hiddenLabel()
                    ->afterStateUpdated(function ($state) {$this->morahel=$state;})
                    ->live()
                    ->options(Mosahah::class),
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


            ])->columns(10);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                return wrong_Kst::query()
                    ->where('morahel',$this->morahel)
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
            ->defaultSort('tar_date','desc')
            ->striped()
            ->pluralModelLabel('بالخطأ')
            ->columns([
                    self::getMy('no')->visible(function (){return $this->morahel==2;}),
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

            )
            ->recordActions([
                \Filament\Actions\Action::make('تصحيح')
                    ->label('')
                    ->icon('heroicon-o-check')
                    ->iconButton()
                    ->visible(function ($record){return $record->morahel->value==0;})
                    ->color('success')
                    ->modalSubmitAction(fn (\Filament\Actions\Action $action) => $action->label('تصحيح'))
                    ->schema([
                        Select::make('main_id')
                            ->label('العقد')
                            ->options(function ($record) {
                                return main::all()
                                    ->pluck('name', 'no');
                            })

                            ->searchable()
                            ->preload()
                            ->required()
                    ])

                    ->action(function ($record,array $data) {
                        $wrong=wrong_Kst::where('acc',$record->acc)->get();
                        foreach ($wrong as $wr) {
                            self::ksm_kst($data['main_id'],$wr->kst,$wr->tar_date,$wr->h_no);
                            $wr->morahel=2;
                            $wr->no=$data['main_id'];
                            $wr->save();
                        }
                        $taj=bank::find($record->bank)->bank_tajmeeh;
                        main::find($data['main_id'])
                            ->update(['acc'=>$record->acc,'bank'=>$record->bank,'taj_id'=>$taj]);

                    })

            ]);
    }

}

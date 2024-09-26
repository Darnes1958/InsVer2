<?php

namespace App\Filament\Pages\Aksat\Rep;

use App\Exports\KhamlaXls;
use App\Exports\Khasf;
use App\Exports\MosdadaXls;
use App\Exports\Motakra;
use App\Models\aksat\kst_trans;
use App\Models\aksat\MainArc;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\stop_kst;
use App\Traits\reportTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Actions;
use App\Models\aksat\main;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;

use App\Models\Customers;
use ArPHP\I18N\Arabic;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class Reports extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable,reportTrait;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.aksat.rep.reports';
    protected static ?string $navigationLabel='تقارير عقود';
    protected ?string $heading='';

    public $bankData;

    public $bank=0;
    public $taj=0;
    public $By='Bank';
    public $from='main';
    public $baky=5;
    public $months=3;
    public $repName='mosdada';

    public $khamlaType='all';
    public function mount()
    {
        $this->bankForm->fill([
            'baky'=>$this->baky,
            'By'=>$this->By,
            'months'=>$this->months,
            'khamlaType'=>$this->khamlaType,
            'repName'=>$this->repName,
            'from'=>$this->from,
        ]);
    }
    protected function getForms(): array
    {
        return array_merge(parent::getForms(), [
            "bankForm" => $this->makeForm()
                ->model(bank::class)
                ->schema($this->getbankFormSchema())
                ->statePath('bankData'),

        ]);
    }

    protected function getbankFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                 Select::make('repName')
                  ->hiddenLabel()
                  ->prefix('التقرير')
                  ->live()
                  ->afterStateUpdated(function ($state){
                      $this->repName=$state;
                      info($this->repName);
                  })
                  ->options([
                    'mosdada'=>'العقود المسددة',
                    'khamla'=>'العقود الخاملة',
                    'motakra'=>'كشف التأخير',
                    'khasf'=>'كشف بالأسماء'
                  ])
                   ->columnSpan(2) ,
                 Select::make('bank')
                   ->prefix('المصرف')
                   ->visible(function (){return $this->By=='Bank';})
                   ->hiddenLabel()
                   ->searchable()
                   ->options(bank::all()->pluck('bank_name', 'bank_no'))
                   ->preload()
                   ->columnSpan(3)
                   ->afterStateUpdated(function ($state){$this->bank=$state;})
                   ->live(),
                    Select::make('taj')
                        ->prefix('التجميعي')
                        ->visible(function (){return $this->By=='Taj';})
                        ->hiddenLabel()
                        ->searchable()
                        ->options(BankTajmeehy::all()->pluck('TajName', 'TajNo'))
                        ->preload()
                        ->columnSpan(3)
                        ->afterStateUpdated(function ($state){$this->taj=$state;})
                        ->live(),
                  TextInput::make('baky')
                   ->prefix('الباقي')
                   ->required()
                   ->visible(function () {return $this->repName=='mosdada';})
                   ->afterStateUpdated(function ($state){$this->baky=$state;})
                   ->hiddenLabel()
                   ->columnSpan(2)
                   ->live(),
                    TextInput::make('months')
                        ->prefix('الأشهر')
                        ->required()
                        ->visible(function () {return $this->repName=='khamla';})
                        ->afterStateUpdated(function ($state){$this->months=$state;})
                        ->hiddenLabel()
                        ->columnSpan(2)
                        ->live(),
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
                        ->visible(function (){return $this->repName=='khasf'; })
                        ->options([
                            'main'=>'القائمة',
                            'MainArc'=>'الأرشيف'
                        ]),

                    Radio::make('khamlaType')
                        ->afterStateUpdated(function ($state){$this->khamlaType=$state;})
                        ->hiddenLabel()
                        ->inline()
                        ->inlineLabel(false)
                        ->live()
                        ->visible(function () {return $this->repName=='khamla' || $this->repName=='motakra';})
                        ->columnSpan(2)
                        ->options([
                            'all'=>'كل العقود',
                            'some'=>'لم تسدد بعد'
                        ]),
                    Actions::make([
                        Actions\Action::make('print')
                            ->iconButton()
                            ->hidden(function (){return $this->bank==0 && $this->taj==0 ;})
                            ->url(function (){
                                $bank_name=' ';
                                if ($this->By=='Bank') $bank_name=bank::find($this->bank)->bank_name;
                                if ($this->By=='taj') $bank_name=BankTajmeehy::find($this->taj)->TajName;
                                if ($this->repName=='mosdada')
                                    return route('pdfmosdada',['ByTajmeehy'=>$this->By,'TajNo'=>$this->taj,
                                    'bank_no'=>$this->bank,'baky'=>$this->baky,'bank_name'=>$bank_name]);
                                if ($this->repName=='khasf')
                                    return route('pdfkhasf',['ByTajmeehy'=>$this->By,'TajNo'=>$this->taj,
                                        'bank_no'=>$this->bank,'bank_name'=>$bank_name,'from'=>$this->from]);
                                if ($this->repName=='khamla')
                                    return route('pdfkamla',['ByTajmeehy'=>$this->By,'TajNo'=>$this->taj,
                                        'bank_no'=>$this->bank,'months'=>$this->months,'bank_name'=>$bank_name,'RepRadio'=>$this->khamlaType]);
                                if ($this->repName=='motakra')
                                   return route('pdfbefore',['ByTajmeehy'=>$this->By,'TajNo'=>$this->taj,
                                       'bank_no'=>$this->bank,'bank_name'=>$bank_name,'Not_pay'=>$this->khamlaType]);

                            })
                            ->color('blue')
                            ->icon('heroicon-o-printer'),
                        Actions\Action::make('اكسل')
                            ->hidden(function (){return $this->bank==0 && $this->taj==0 ;})
                            ->action(function (){
                                $bank_name=' ';
                                if ($this->By=='Bank') $bank_name=bank::find($this->bank)->bank_name;
                                if ($this->By=='taj') $bank_name=BankTajmeehy::find($this->taj)->TajName;
                                if ($this->repName=='mosdada')
                                   return Excel::download(new MosdadaXls($this->By,$this->taj,$this->bank,$this->baky,$bank_name), 'Mosdada.xlsx');
                                if ($this->repName=='khasf')
                                    return Excel::download(new Khasf($this->By,$this->taj,$this->bank,$this->from), 'Khasf.xlsx');

                                if ($this->repName=='khamla')
                                   return Excel::download(new KhamlaXls($this->By,$this->taj,$this->bank,$this->months,$this->khamlaType,$bank_name), 'Khamla.xlsx');
                                if ($this->repName=='motakra')
                                    return Excel::download(new Motakra($this->By,$this->taj,$this->bank,$this->khamlaType,$bank_name), 'Khamla.xlsx');

                            })
                            ->color('success')
                            ->link(),
                    ])->columnSpan(1)
                ])
                ->columns(12)
        ];
    }

    public function getTableRecordKey(Model $record): string
    {
        if ($this->repName=='khamla') return 'no';

        return $record->getKey();

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
               if ($this->bank==0 && $this->taj==0 ) $res= null;
                if ($this->repName=='mosdada') {$res=$this->retMosdada($this->bank,$this->taj,$this->By,$this->baky);}
                if ($this->repName=='khamla') {$res=$this->retKhamal($this->bank,$this->taj,$this->By,$this->months,$this->khamlaType);}
                if ($this->repName=='motakra') {$res=$this->retMotakra($this->bank,$this->taj,$this->By,$this->khamlaType);}
                if ($this->repName=='khasf') {$res=$this->retkhasf($this->bank,$this->taj,$this->By,$this->from);}

                return $res;
            }
            )
            ->paginated([5,10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('لا توجد بيانات')
            ->defaultSort('no')
            ->bulkActions([
                BulkAction::make('toStop')
                    ->visible(function (){return $this->repName=='mosdada';})
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->button()
                    ->color('yellow')
                    ->icon('heroicon-o-no-symbol')
                    ->label('إيقاف الخصم')
                    ->action(function (Collection $records)  {
                        foreach ($records as $record) {
                            stop_kst::insert([
                                'no'=>$record->no,'name'=>$record->name,'bank'=>$record->bank,'acc'=>$record->acc,
                                'stop_type'=>1,'stop_date'=>now(),'letters'=>0,'emp'=>auth::user()->empno,
                            ]);
                        }
                    }),
                BulkAction::make('toArch')
                    ->visible(function (){return $this->repName=='mosdada';})
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->button()
                    ->color('info')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->label('نقل للأرشيف')
                    ->action(function (Collection $records)  {
                        foreach ($records as $record) {
                            $newRecord = $record->replicate();
                            $newRecord->setTable('MainArc');
                            $newRecord->no=$record->no;
                            $newRecord->save();
                            kst_trans::query()
                                ->where('no', $record->no)
                                ->each(function ($oldTran) {
                                    $newTran = $oldTran->replicate();
                                    $newTran->setTable('TransArc');
                                    $newTran->save();
                                    $oldTran->delete();
                                });
                            over_kst::query()
                                ->where('no', $record->no)
                                ->each(function ($oldTran) {
                                    $newTran = $oldTran->replicate();
                                    $newTran->setTable('over_kst_a');
                                    $newTran->save();
                                    $oldTran->delete();
                                });

                            $record->delete();
                        }
                    })
            ])

            ->columns([
                TextColumn::make('ser')
                    ->rowIndex()
                    ->label('ت'),
                TextColumn::make('no')
                    ->label('رقم العفد')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('acc')
                    ->label('رقم الحساب')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sul_date')
                    ->label('تاريخ العقد'),
                TextColumn::make('sul')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('قيمة التقسيط'),
                TextColumn::make('kst_count')
                    ->hidden(function (){
                        return $this->repName=='khamla';
                    })
                    ->label('ع. الاقساط'),
                TextColumn::make('sul_pay')
                    ->hidden(function (){
                        return $this->repName=='khamla' && $this->khamlaType=='some';
                    })
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('المسدد'),
                TextColumn::make('raseed')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('الرصيد'),
                TextColumn::make('kst_raseed')
                    ->visible(function (){return $this->repName=='khamla';})
                    ->state(function ($record){
                        if ($record->raseed<=$record->kst) return 1;
                        else
                            return ceil($record->raseed/$record->kst);
                    })
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('ع.المتبقية'),
                TextColumn::make('ksm_date')
                    ->visible(function (){
                        return $this->repName=='khamla' && $this->khamlaType=='all';
                    })
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('ت.أخر قسط سدد'),
                TextColumn::make('pay_count')
                    ->visible(function (){
                        return $this->repName=='motakra' ;
                    })
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('عدد المسددة'),
                TextColumn::make('late')
                    ->visible(function (){
                        return $this->repName=='motakra' ;
                    })
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('عدد المتأخرة'),
                TextColumn::make('kst_late')
                    ->visible(function (){
                        return $this->repName=='motakra' ;
                    })
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '',
                        thousandsSeparator: ',',
                    )
                    ->label('مجموعها'),

            ]);
    }

}

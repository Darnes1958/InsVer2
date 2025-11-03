<?php

namespace App\Filament\Resources\FromExcelResource\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use EightyNine\ExcelImport\ExcelImportAction;
use App\Filament\Resources\FromExcelResource;
use App\Filament\Resources\FromExcelResource\Widgets\FromExcelWidget;
use App\Imports\FromExcelImport;
use App\Livewire\Aksat\Rep\KstTran;
use App\Models\aksat\hafitha;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\aksat\main_deleted;
use App\Models\aksat\MainArc;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\CompanyTajmeehy;
use App\Models\Dateofexcel;
use App\Models\excel\FromExcelModel;
use App\Models\ExcelSeting;
use App\Models\FromExcel;
use App\Models\User;
use App\Traits\AksatTrait;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListFromExcels extends ListRecords
{
    use AksatTrait;
    protected static string $resource = FromExcelResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('Do')
                ->color('success')
                ->fillForm(function (){
                    $bank=Auth::user()->empno;
                    if (CompanyTajmeehy::query()
                         ->where('bank_id',$bank)
                         ->where('company',Auth::user()->company)
                         ->count() ==1) {
                        $taj=CompanyTajmeehy::query()
                            ->where('bank_id',$bank)
                            ->where('company',Auth::user()->company)->first()->taj_id;
                    } else $taj=null;
                    return ['taj' => $taj,'bank'=>Auth::user()->empno,];
                })
                ->schema([
                    Select::make('bank')
                        ->options(ExcelSeting::all()->pluck('bank','id'))
                        ->label('المصرف')
                        ->afterStateUpdated(function (Set $set,$state){
                            if (CompanyTajmeehy::query()
                                    ->where('bank_id',$state)
                                    ->where('company',Auth::user()->company)
                                    ->count() ==1) {
                                $set('taj', CompanyTajmeehy::query()
                                    ->where('bank_id', $state)
                                    ->where('company', Auth::user()->company)->first()->taj_id);
                            } else $set('taj', null);
                        })
                        ->live()
                        ->required(),
                    Select::make('taj')
                        ->label('المصرف التجميعي')
                        ->live()
                        ->options(fn (Get $get): Collection => BankTajmeehy::query()
                            ->whereIn('TajNo',CompanyTajmeehy::where('bank_id', $get('bank'))
                                ->where('company',Auth::user()->company)->pluck('taj_id'))

                            ->pluck('TajName', 'TajNo'))

                        ->searchable()
                        ->preload()
                        ->required(),

                ])
                ->action(function (array $data){
                    FromExcel::truncate();
                    User::find(Auth::id())->update(['empno'=>$data['bank'],'IsAdmin'=>$data['taj']]);

                }),

            ExcelImportAction::make()
                ->slideOver()
                ->before(function (){
                    FromExcel::truncate();
                })
                ->after(function (){
                    $beginDate=FromExcel::min('ksm_date');
                    $endDate=FromExcel::max('ksm_date');
                    $res=Dateofexcel::where('taj_id',Auth::user()->IsAdmin)
                        ->whereBetween('date_begin',[$beginDate,$endDate])->first();
                    if ($res){
                        FromExcel::truncate();
                        Notification::make()
                            ->title('يوجد تداخل في تاريخ الحافظة مع حافظة سابقة لنفس المصرف ')
                            ->send();
                        return false;
                    }

                    Dateofexcel::create([
                            'taj_id'=>Auth::user()->IsAdmin,
                            'date_begin'=>FromExcel::min('ksm_date'),
                            'date_end'=>FromExcel::max('ksm_date'),
                        ]
                    );



                })
                ->color('danger')
                ->use(FromExcelImport::class),
          Action::make('Tarheel aksat')
              ->disabled()
            ->action(function () {
                $res = FromExcel::query()->orderBy('acc')->get();
                $taj = Auth::user()->isIdmin;
                $haf=hafitha::on(Auth()->user()->company)->max('hafitha_no')+1;
                hafitha::insert([
                    'hafitha_no'=> $haf,
                    'bank'=> bank::where('bank_tajmeeh',$taj)->min('bank_no'),
                    'hafitha_date'=>date('Y-m-d'),
                    'hafitha_tot'=>0,
                    'hafitha_state'=>0,
                    'kst_morahel'=>0,
                    'kst_over'=>0,
                    'kst_half_over'=>0,
                    'kst_wrong'=>0,
                    'kst_wrong_after'=>0,
                ]);
                foreach ($res as $rec) {
                    $acc = $rec->acc;
                    $ksm = $rec->ksm;
                    $ksm_date=$rec->ksm_date;
                    $main = main::where('taj_id', $taj)->get();
                    if ($main->count() > 0) $no = $main[0]->no;
                    else $no = null;

                    if ($no)
                        if ($main->count() > 1)
                            foreach ($main as $oneNo)
                                if ($oneNo->kst == $ksm) $no = $oneNo->no;

                    if ($no) {
                        self::Fill_From_Excel($no,$ksm,$ksm_date,$haf,$rec->id);
                    }
                    else {
                        $no = MainArc::where('taj_id', $taj)
                            ->where('acc', $acc)->first();
                        if ($no) {

                        } else {
                            $res = main_deleted::where('taj_id', $taj)->where('acc', $acc)->first();
                            if ($res) {
                            } else {
                            }
                        }
                    }

                }

            })




        ];
    }
  protected function getFooterWidgets(): array
  {
    return [
      FromExcelWidget::class,
    ];

  }
}

<?php

namespace App\Filament\Resources\FromExcelResource\Pages;

use App\Filament\Resources\FromExcelResource;
use App\Filament\Resources\FromExcelResource\Widgets\FromExcelWidget;
use App\Imports\FromExcelImport;
use App\Models\bank\BankTajmeehy;
use App\Models\CompanyTajmeehy;
use App\Models\Dateofexcel;
use App\Models\ExcelSeting;
use App\Models\FromExcel;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ListFromExcels extends ListRecords
{
    protected static string $resource = FromExcelResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('Do')
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

                ->form([
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

            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->slideOver()
                ->before(function (){
                    FromExcel::truncate();
                })
                ->color('danger')
                ->use(FromExcelImport::class),
          Actions\Action::make('check')
            ->action(function (array $data){
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


        ];
    }
  protected function getFooterWidgets(): array
  {
    return [
      FromExcelWidget::class,
    ];

  }
}

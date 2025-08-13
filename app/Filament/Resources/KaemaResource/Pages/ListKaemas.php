<?php

namespace App\Filament\Resources\KaemaResource\Pages;

use App\Filament\Resources\KaemaResource;
use App\Imports\FromExcelImport;
use App\Imports\KaemaModelImport;
use App\Models\aksat\main;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\Customer;
use App\Models\Dateofexcel;
use App\Models\excel\Kaema;
use App\Models\FromExcel;
use Filament\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Raw;

class ListKaemas extends ListRecords
{
    protected static string $resource = KaemaResource::class;
    public $taj_id;
    public $withDelete=false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Prepere')
                ->form([
                    Select::make('taj_id')
                        ->options(BankTajmeehy::all()->pluck('TajName', 'TajNo'))
                        ->preload()
                        ->searchable()
                        ->required()
                        ->afterStateUpdated(function ($state){
                            $this->taj_id=$state;
                        })
                        ->label('المصرف التجميعي'),
                    Checkbox::make('withDelete')
                        ->afterStateUpdated(function ($state){
                            $this->withDelete=$state;
                        })
                        ->default(false)
                ])
                ->action(function (array $data){
                    if ($this->withDelete)  Kaema::truncate();

                })
                ->color('success'),
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->slideOver()
                ->color('danger')
                ->use(KaemaModelImport::class),
            Actions\Action::make('Do')

             ->action(function (){
                 Kaema::where('Taj',null)->update(['Taj' => $this->taj_id]);

                 if (Customer::where('Company',Auth::user()->company)->first()->oneBanke==1)
                 {
                     $bank=bank::where('bank_tajmeehy',$this->taj_id)->first();
                     Kaema::where('Taj',$this->taj_id)->update(['bank'=>$bank->bank_no,'bankcode'=>$bank->bank_code]);
                 } else
                     Kaema::join('bank','kaema.Taj','bank.bank_tajmeeh')
                         ->where('kaema.Taj',$this->taj_id)
                         ->where('kaema.bankcode',DB::raw('bank.bank_code'))
                         ->update(['bank'=>DB::raw('bank.bank_no')]);

                 Kaema::join('main','kaema.Taj','main.taj_id')
                         ->where('kaema.Taj',$this->taj_id)
                         ->where('kaema.acc',DB::raw("main.acc"))
                         ->update(['kaema.no'=>DB::raw("main.no"),'MainOrArc'=>1]);
                 Kaema::join('MainArc','kaema.Taj','MainArc.taj_id')
                     ->where('kaema.Taj',$this->taj_id)
                     ->where('kaema.acc',DB::raw("MainArc.acc"))
                     ->update(['kaema.no'=>DB::raw("MainArc.no"),'MainOrArc'=>2]);

             })
             ->color('success'),
        ];
    }
}

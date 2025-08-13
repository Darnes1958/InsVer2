<?php

namespace App\Filament\Resources\KaemaResource\Pages;

use App\Filament\Resources\KaemaResource;
use App\Imports\FromExcelImport;
use App\Imports\KaemaModelImport;
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
                 $TajNo=$this->taj_id;
                 if (Customer::where('Company',Auth::user()->company)->first()->oneBanke==1)
                 {
                     $bank=bank::where('bank_tajmeehy',$this->taj_id)->first();
                     Kaema::where('Taj',$this->taj_id)->update(['bank'=>$bank->bank_no,'bankcode'=>'bank_code']);
                 } else
                     Kaema::where('Taj',$this->taj_id)->update(['bank'=>$bank->bank_no,'bankcode'=>'bank_code']);
                     DB::connection(Auth()->user()->company)
                         ->statement( DB::raw("update Kaema set bank=bank_no from bank where Taj='$TajNo' and bank_tajmeeh='$TajNo' and bankcode=bank_code") );

                 DB::connection(Auth()->user()->company)->statement( DB::raw("update Kaema set Kaema.no=main.no,MainOrArc=1
            from main where main.bank=Kaema.bank and main.acc=kaema.acc") );
                 DB::connection(Auth()->user()->company)->statement( DB::raw("update Kaema set Kaema.no=mainarc.no,MainOrArc=2
             from mainarc where  mainarc.bank=Kaema.bank and mainarc.acc=kaema.acc and Kaema.no is null") );
             })
             ->color('success'),
        ];
    }
}

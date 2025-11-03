<?php

namespace App\Filament\Resources\MahjozaResource\Pages;

use Filament\Actions\Action;
use EightyNine\ExcelImport\ExcelImportAction;
use App\Filament\Resources\MahjozaResource;
use App\Imports\KaemaModelImport;
use App\Imports\MahjozaModelImport;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\Customer;
use App\Models\excel\Kaema;
use App\Models\excel\Mahjoza;
use Filament\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListMahjozas extends ListRecords
{
    protected static string $resource = MahjozaResource::class;
    public $taj_id;
    public $withDelete=false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Prepere')
                ->schema([
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
                    if ($this->withDelete)  Mahjoza::truncate();

                })
                ->color('success'),
            ExcelImportAction::make()
                ->slideOver()
                ->color('danger')
                ->use(MahjozaModelImport::class),
            Action::make('Do')

                ->action(function (){
                    Mahjoza::where('Taj',null)->update(['Taj' => $this->taj_id]);

                    if (Customer::where('Company',Auth::user()->company)->first()->oneBanke==1)
                    {
                        $bank=bank::where('bank_tajmeehy',$this->taj_id)->first();
                        Mahjoza::where('Taj',$this->taj_id)->update(['bankcode'=>$bank->bank_code]);
                    } else
                        Mahjoza::where('Taj',$this->taj_id)->update(['bankcode'=>DB::raw('substring(acc,1,3)')]);

                        Mahjoza::join('bank','Mahjoza.Taj','bank.bank_tajmeeh')
                            ->where('Mahjoza.Taj',$this->taj_id)
                            ->where('Mahjoza.bankcode',DB::raw('bank.bank_code'))
                            ->update(['bank'=>DB::raw('bank.bank_no')]);

                    Mahjoza::join('main','Mahjoza.Taj','main.taj_id')
                        ->where('Mahjoza.Taj',$this->taj_id)
                        ->where('Mahjoza.acc',DB::raw("main.acc"))
                        ->update(['Mahjoza.no'=>DB::raw("main.no"),'MainOrArc'=>1]);
                    Mahjoza::join('MainArc','Mahjoza.Taj','MainArc.taj_id')
                        ->where('Mahjoza.Taj',$this->taj_id)
                        ->where('Mahjoza.acc',DB::raw("MainArc.acc"))
                        ->update(['Mahjoza.no'=>DB::raw("MainArc.no"),'MainOrArc'=>2]);

                })
                ->color('success'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Masrofat\Masrofats\Pages;

use App\Filament\Resources\Masrofat\Masrofats\MasrofatResource;
use App\Livewire\Traits\PublicTrait;
use App\Models\masr\MasCenters;
use App\Models\masr\MasTypeDetails;
use App\Models\masr\MasTypes;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ManageMasrofats extends ManageRecords
{
    use PublicTrait;
    protected static string $resource = MasrofatResource::class;
    protected ?string $heading='المصروفات';
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalHeading('إضافة مصروفات'),
            Action::make('print')
                ->label('طباعة')
                ->icon('heroicon-o-printer')
                ->color('blue')
                ->action(function (){
                    $filter=$this->table->getFilters();
                    $arr=[];
                    $MasType='';
                    $MasTypeDetail='';
                    $MasCenter='';
                    $res=MasTypes::find($filter['MasType']->getState())->first();
                    if ($res) $MasType=$res->MasTypeName;

                    $res=MasTypeDetails::find($filter['MasTypeDetail']->getState())->first();
                    if ($res) $MasTypeDetail=$res->DetailName;

                    $res=MasCenters::find($filter['MasCenter']->getState())->first();
                    if ($res) $MasCenter=$res->CenterName;

                    $arr['MasType']=$MasType;
                    $arr['MasTypeDetail']=$MasTypeDetail;
                    $arr['MasCenter']=$MasCenter;
            //        $arr['Date1']=$filter['MasDate']->getState()['Date1'];
                   // $arr['Date2']=$filter['MasDate']->getState()['Date2'];

                    $date='';
                    if ($filter['MasDate']->getState()['Date1']) $date='من تاريخ '.$filter['MasDate']->getState()['Date1'];
                    if ($filter['MasDate']->getState()['Date2']) $date=$date.' إلي تاريخ '.$filter['MasDate']->getState()['Date2'];
                    $arr['date']=$date;

                    return Response::download(self::ret_spatie($this->getTableQueryForExport()->get(),
                        'PrnView.masrofat.masrofat',$arr), 'filename.pdf', self::ret_spatie_header());
                }),
            Action::make('prnSumType')
                ->label('إجمالي البنود')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action(function (){
                    $filter=$this->table->getFilters();
                    $arr=[];
                    $MasType='';
                    $MasTypeDetail='';
                    $MasCenter='';
                    $res=MasTypes::find($filter['MasType']->getState())->first();
                    if ($res) $MasType=$res->MasTypeName;

                    $res=MasTypeDetails::find($filter['MasTypeDetail']->getState())->first();
                    if ($res) $MasTypeDetail=$res->DetailName;

                    $res=MasCenters::find($filter['MasCenter']->getState())->first();
                    if ($res) $MasCenter=$res->CenterName;

                    $arr['MasType']=$MasType;
                    $arr['MasTypeDetail']=$MasTypeDetail;
                    $arr['MasCenter']=$MasCenter;

                    $date='';
                    if ($filter['MasDate']->getState()['Date1']) $date='من تاريخ '.$filter['MasDate']->getState()['Date1'];
                    if ($filter['MasDate']->getState()['Date2']) $date=$date.' إلي تاريخ '.$filter['MasDate']->getState()['Date2'];
                    $arr['date']=$date;

                    $res=MasTypes::query()
                        ->join('Masrofat','MasTypes.MasTypeNo','=','Masrofat.MasType')
                        ->selectRaw('MasTypes.MasTypeNo,MasTypes.MasTypeName,sum(Masrofat.Val) as Val')
                        ->when($MasCenter,function ($q) use ($filter){
                            $q->where('MasCenter',$filter['MasCenter']->getState());
                        })
                        ->when($filter['MasDate']->getState()['Date1'],function ($q) use ($filter){
                            $q->where('MasDate','>=',$filter['MasDate']->getState()['Date1']);
                        })
                        ->when($filter['MasDate']->getState()['Date2'],function ($q) use ($filter){
                            $q->where('MasDate','<=',$filter['MasDate']->getState()['Date2']);
                        })
                        ->groupBy('MasTypes.MasTypeNo','MasTypes.MasTypeName')
                        ->get();

                    return Response::download(self::ret_spatie($res,
                        'PrnView.masrofat.masSumType',$arr), 'filename.pdf', self::ret_spatie_header());
                }),
            Action::make('prnSumCenter')
                ->label('إجمالي الفروع')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->action(function (){
                    $filter=$this->table->getFilters();
                    $arr=[];
                    $MasType='';
                    $MasTypeDetail='';
                    $MasCenter='';
                    $res=MasTypes::find($filter['MasType']->getState())->first();
                    if ($res) $MasType=$res->MasTypeName;

                    $res=MasTypeDetails::find($filter['MasTypeDetail']->getState())->first();
                    if ($res) $MasTypeDetail=$res->DetailName;

                    $res=MasCenters::find($filter['MasCenter']->getState())->first();
                    if ($res) $MasCenter=$res->CenterName;

                    $arr['MasType']=$MasType;
                    $arr['MasTypeDetail']=$MasTypeDetail;
                    $arr['MasCenter']=$MasCenter;

                    $date='';
                    if ($filter['MasDate']->getState()['Date1']) $date='من تاريخ '.$filter['MasDate']->getState()['Date1'];
                    if ($filter['MasDate']->getState()['Date2']) $date=$date.' إلي تاريخ '.$filter['MasDate']->getState()['Date2'];
                    $arr['date']=$date;

                    $res=MasCenters::query()
                        ->join('Masrofat','MasCenters.CenterNo','=','Masrofat.MasCenter')
                        ->selectRaw('MasCenters.CenterNo,MasCenters.CenterName,sum(Masrofat.Val) as Val')
                        ->when($filter['MasDate']->getState()['Date1'],function ($q) use ($filter){
                            $q->where('MasDate','>=',$filter['MasDate']->getState()['Date1']);
                        })
                        ->when($filter['MasDate']->getState()['Date2'],function ($q) use ($filter){
                            $q->where('MasDate','<=',$filter['MasDate']->getState()['Date2']);
                        })
                        ->groupBy('MasCenters.CenterNo','MasCenters.CenterName')
                        ->get();

                    return Response::download(self::ret_spatie($res,
                        'PrnView.masrofat.masSumCenter',$arr), 'filename.pdf', self::ret_spatie_header());
                }),
        ];
    }
}

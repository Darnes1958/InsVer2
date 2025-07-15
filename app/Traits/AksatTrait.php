<?php

namespace App\Traits;


use App\Livewire\Aksat\Rep\KstTran;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\Fromexcel;
use App\Models\OverTar\over_kst;

use Illuminate\Support\Facades\Auth;


trait AksatTrait
{

    public static function StoreOver($main,$ksm_date,$ksm,$haf=0){
        $res=over_kst::insert([
            'no'=>$main->no,'name'=>$main->name,'bank'=>$main->bank,'acc'=>$main->acc,'kst'=>$ksm,
            'tar_type'=>1,'tar_date'=>$ksm_date,'letters'=>0,'emp'=>1,'h_no'=>$haf,]);

        return $res;
    }
    public static function StoreTran($main_id,$ksm_date,$ksm,$haf,$ksm_type_id=2,$notes=null)
    {
        $min=kst_trans::
        where([
            ['no', $main_id],
            ['ksm', null],])
            ->orwhere([
                ['no',  $main_id],
                ['ksm', 0],])
            ->min('ser');
        if ($min==null)
        {$min=KstTran::where('no',$main_id)->max('ser')+1;
            KstTran::insert([
                'ser'=>$min,'no'=>$main_id,'kst_date'=>$ksm_date,'ksm_type'=>2,'chk_no'=>0,'kst'=>$ksm,'ksm_date'=>$ksm_date,'ksm'=>$ksm,'emp'=>1,
                'h_no'=>$haf,'inp_date'=>date('Y-m-d'),]);
        }
        else {
            KstTran::where('no',$main_id)->where('ser',$min)->update([
                'h_no'=>$haf,'ksm'=>$ksm,'ksm_date'=>$ksm_date,'emp'=>1,'inp_date'=>date('Y-m-d'),'ksm_type'=>2,]);
        }

    }
    public static function Fill_From_Excel($main_id,$ksm,$ksm_date,$haf,$from_id)
    {
        $main= main::find($main_id);
        if ($main->raseed<=0) {
            self::StoreOver($main,$ksm_date,$ksm,$haf);
        }

        if ($main->raseed>0){
            $over_id=null;
            if ($main->raseed<$ksm)
            {
                self::StoreOver($main,$ksm_date,$ksm-$main->raseed,$haf);
                $ksm=$main->raseed;

            }
            self::StoreTran($main_id,$ksm_date,$ksm,$haf);
        }

    }


}

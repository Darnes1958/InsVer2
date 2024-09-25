<?php

namespace App\Traits;

use App\Models\aksat\main;
use App\Models\aksat\main_trans_view2;
use App\Models\aksat\main_view;
use App\Models\aksat\MainArc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait reportTrait
{
    public function retMotakra($bank_no,$TajNo,$By,$khamlaType) {
        $res=DB::connection(Auth()->user()->company)->table('settings')
            ->where('no',3)->first();
        $DAY_OF_KSM=$res->s1;
        $day=Carbon::now()->day;
        $month=Carbon::now()->month;
        $year=Carbon::now()->year;
        $Month=$month.'\\'.$year;
        if ($day<$DAY_OF_KSM) {
            $myDate = '28/'.$month.'/'.$year;
            $date = Carbon::createFromFormat('d/m/Y', $myDate)->format('Y-m-d');
        } else $date=date('Y-m-d');
        DB::connection(Auth()->user()->company)->table('late')->delete();
        if ($By=='Taj')
            DB::connection(Auth()->user()->company)->statement( 'insert into late select main.no,count(*) late,:emp
                            from main,kst_trans where main.no=kst_trans.no and bank in (select bank_no from bank where bank_tajmeeh=:Taj) and (ksm=0 or ksm is null)
                                  and kst_date<=:wdate
                                  group by main.no  ',
                array('Taj'=> $TajNo,'emp'=>Auth::user()->empno,'wdate'=>$date ));
        else
            DB::connection(Auth()->user()->company)->statement( 'insert into late select main.no,count(*) late,:emp
                            from main,kst_trans where main.no=kst_trans.no and bank=:bank and (ksm=0 or ksm is null)
                                  and kst_date<=:wdate
                                  group by main.no  ',
                array('bank'=> $bank_no,'emp'=>Auth::user()->empno,'wdate'=>$date ));


        return    main::join('late','main.no','=','late.no')
                    ->selectRaw('acc,name,sul_date,sul,kst_count,sul_pay,raseed,main.kst,main.no,round((sul_pay/kst),0) pay_count,late,
                               late*main.kst kst_late')
                    ->when($By=='Bank',function ($q) use($bank_no){
                        return $q->where('bank',$bank_no);
                    })
                    ->when($By=='Taj',function ($q) use($TajNo){
                        return $q->whereIn('bank', function($q) use($TajNo){
                            $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
                    })
                    ->when($khamlaType=='some',function($q){
                        return $q->where([
                            ['sul_pay',0],
                            ['late', '>', 0],
                            ['kst','!=',0],
                            ]);})
                    ->when( $khamlaType=='all',function ($q) {
                        return $q->where([
                            ['late', '>', 0],
                            ['kst','!=',0],
                            ]);});
    }
    public function retMosdada($bank_no,$TajNo,$By,$baky)
    {

        $main = main::query()
            ->when($By=='Bank',function($q) use ($bank_no){
                $q->where('bank', '=', $bank_no);
            })
            ->when($By=='Taj',function($q) use ($TajNo){
                $q-> whereIn('bank', function($q) use ($TajNo){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
            })
            ->where('raseed','<=',$baky)
        ;
        return $main;
    }
    public function retkhasf($bank_no,$TajNo,$By,$from)
    {
        if ($from=='main')
        $main = main::query()
            ->when($By=='Bank',function($q) use ($bank_no){
                $q->where('bank', '=', $bank_no);
            })
            ->when($By=='Taj',function($q) use ($TajNo){
                $q-> whereIn('bank', function($q) use ($TajNo){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
            });
        else
            $main = MainArc::query()
                ->when($By=='Bank',function($q) use ($bank_no){
                    $q->where('bank', '=', $bank_no);
                })
                ->when($By=='Taj',function($q) use ($TajNo){
                    $q-> whereIn('bank', function($q) use ($TajNo){
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
                });

        return $main;
    }
    public function retKhamal($bank_no,$TajNo,$By,$months,$khamlaType) {
        info('i am here');
        DB::connection(Auth()->user()->company)->table('late')->delete();
        if ($By=='Bank')
            DB::connection(Auth()->user()->company)->statement( 'insert into late select main.no,DATEDIFF(month,max(ksm_date),getdate()),:emp
                            from main,kst_trans where main.no=kst_trans.no and (SUL_PAY)<(SUL-1) and main.no<>0 and sul_pay<>0
                            and bank=:bank group by main.no having DATEDIFF(month,max(ksm_date),getdate())>=:months ',
                array('bank'=> $bank_no,'emp'=>Auth::user()->empno,'months'=>$months ));
        if ($By=='Taj')
            DB::connection(Auth()->user()->company)->statement( 'insert into late select main.no,DATEDIFF(month,max(ksm_date),getdate()),:emp
                        from main,kst_trans where main.no=kst_trans.no and (SUL_PAY)<(SUL-1) and main.no<>0 and sul_pay<>0
                        and bank in (select bank_no from bank where bank_tajmeeh=:taj)
                        group by main.no having DATEDIFF(month,max(ksm_date),getdate())>=:months ',
                array('taj'=> $TajNo,'emp'=>Auth::user()->empno,'months'=>$months ));

        if ($By=='Bank')
            DB::connection(Auth()->user()->company)->statement('insert into late select main.no,DATEDIFF(month,sul_date,getdate()),:emp from main
                            where  sul_pay=0 and  main.no<>0 and DATEDIFF(month,sul_date,getdate())>=:months and bank=:bank ',
                array('bank'=> $bank_no,'emp'=>Auth::user()->empno,'months'=>$months ));

        if ($By=='Taj')
            DB::connection(Auth()->user()->company)->statement('insert into late select main.no,DATEDIFF(month,sul_date,getdate()),:emp from main
                    where  sul_pay=0 and  main.no<>0 and DATEDIFF(month,sul_date,getdate())>=:months
                    and bank in (select bank_no from bank where bank_tajmeeh=:taj) ',
                array('taj'=> $TajNo,'emp'=>Auth::user()->empno,'months'=>$months ));

        if ($khamlaType=='all') {

            $first=main_trans_view2::
            selectRaw('no,name,sul_date,sul,sul_pay,raseed,kst,bank_name,acc,order_no,max(ksm_date) as ksm_date')
                ->when($By=='Bank',function($q) use($bank_no){
                    $q->where('bank', '=', $bank_no);
                })
                ->when($By=='Taj',function($q) use($TajNo){
                    $q-> whereIn('bank', function($q) use($TajNo){
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
                })
                ->where('sul_pay','!=',0)

                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('late')
                        ->whereColumn('main_trans_view2.no', 'late.no')
                        ->where('emp',Auth::user()->empno);
                })
                ->groupBy('no','name','sul_date','sul','sul_pay','raseed','kst','bank_name','acc','order_no');
            $second=main_view::
            selectraw('no,name,sul_date,sul,sul_pay,raseed,kst,bank_name,acc,order_no,null as ksm_date')
                ->when($By=='Bank',function($q) use($bank_no){
                    $q->where('bank', '=', $bank_no);
                })
                ->when($By=='Taj',function($q) use($TajNo){
                    $q-> whereIn('bank', function($q) use($TajNo){
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
                })

                ->where('sul_pay',0)

                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('late')
                        ->whereColumn('main_view.no', 'late.no')
                        ->where('emp',Auth::user()->empno);
                })
                ->union($first) ; }

        if ($khamlaType=='some'){

            $second= main_view::
            selectraw('no,name,sul_date,sul,sul_pay,raseed,kst,bank_name,acc,order_no,null as ksm_date')
                ->when($By=='Bank',function($q) use($bank_no){
                    $q->where('bank', '=', $bank_no);
                })
                ->when($By=='Taj',function($q) use($TajNo){
                    $q-> whereIn('bank', function($q) use($TajNo){
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
                })
                ->where('sul_pay',0)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('late')
                        ->whereColumn('main_view.no', 'late.no')
                        ->where('emp', Auth::user()->empno);
                });
        }


        return $second;

    }
}

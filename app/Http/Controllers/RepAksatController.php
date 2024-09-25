<?php

namespace App\Http\Controllers;

use App\Models\aksat\kst_trans;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\bank\Companies;
use App\Models\Customers;
use App\Models\excel\MahjozaModel;
use App\Models\jeha\jeha;
use App\Models\sell\rep_sell_tran;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepAksatController extends Controller
{
    public  function convertToArabic($html, int $line_length = 100, bool $hindo = false, $forcertl = false): string
    {
        $Arabic = new \ArPHP\I18N\Arabic();
        $p = $Arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $Arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]), $line_length, $hindo, $forcertl);
            $html   = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        return $html;
    }
    function PdfMain($no){

    $RepDate=date('Y-m-d');
    $cus=Customers::where('Company',Auth::user()->company)->first();
    $res=DB::connection(Auth()->user()->company)->table('main_view')
        ->where('no',  $no)
        ->first();
    $res2=DB::connection(Auth()->user()->company)->table('kst_tran_view')
        ->where('no',  $no)
        ->orderBy('ser')
        ->get();
    $res3=rep_sell_tran::where('order_no',$res->order_no)->get();
    $res4=MahjozaModel::where('no',$no)->first();

    $reportHtml = view('PrnView.aksat.Pdf-main',
        ['res'=>$res,'res2'=>$res2,'res3'=>$res3,'res4'=>$res4,'cus'=>$cus])->render();
    $arabic = new Arabic();
    $p = $arabic->arIdentify($reportHtml);

    for ($i = count($p)-1; $i >= 0; $i-=2) {
        $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
        $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
    }

    $pdf = PDF::loadHTML($reportHtml);
    return $pdf->download('report.pdf');

}
    function PdfMainCont($no){

        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();
        $res=DB::connection(Auth()->user()->company)->table('main_view')
            ->where('no',  $no)
            ->first();

        $items=rep_sell_tran::where('order_no',$res->order_no)->get();
        $item_name='';
        foreach($items as $item) {
            $item_name=$item_name.' / '.$item->item_name;}

        $taj=bank::where('bank_no',$res->bank)->first()->bank_tajmeeh;
        $tajacc=BankTajmeehy::where('TajNo',$taj)->first()->TajAcc;
        $tajname=BankTajmeehy::where('TajNo',$taj)->first()->TajName;
        $CompNo=BankTajmeehy::where('TajNo',$taj)->first()->CompNo;;
        $company=Companies::where('CompNo',$CompNo)->first();


        $mindate=kst_trans::where('no',$no)->min('kst_date');
        $mdate=Carbon::parse($mindate) ;
        $mmdate=$mdate->month.'-'.$mdate->year;

        $maxdate=kst_trans::where('no',$no)->max('kst_date');
        $xdate=Carbon::parse($maxdate) ;
        $xxdate=$xdate->month.'-'.$xdate->year;

        $jeha=jeha::where('jeha_no',$res->jeha)->first();

        $reportHtml = view('PrnView.aksat.Pdf-main-Cont2',
            ['tajname'=>$tajname,'res'=>$res,'mindate'=>$mmdate,'maxdate'=>$xxdate,'company'=>$company,'cus'=>$cus,'TajAcc'=>$tajacc,'item_name'=>$item_name,'jeha'=>$jeha])->render();
       $reportHtml=$this->convertToArabic($reportHtml);

        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    public function PdfMosdada(Request $request){
        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();
        $res=DB::connection(Auth()->user()->company)->table('main_view')
            ->when($request->ByTajmeehy=='Bank',function($q) use($request){
                $q->where('bank', '=', $request->bank_no);
            })
            ->when($request->ByTajmeehy=='Taj',function($q) use($request){
                $q-> whereIn('bank', function($q)  use($request){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$request->TajNo);});
            })
            ->where('raseed','<=',$request->baky)
            ->get();
        $sum=DB::connection(Auth()->user()->company)->table('main')
            ->selectRaw('sum(sul_tot) as sul_tot,sum(dofa) as dofa,sum(sul) as sul,
             sum(sul_pay) as sul_pay,sum(raseed) as raseed')
            ->when($request->ByTajmeehy=='Bank',function($q) use($request){
                $q->where('bank', '=', $request->bank_no);
            })
            ->when($request->ByTajmeehy=='Taj',function($q) use($request){
                $q-> whereIn('bank', function($q)  use($request){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$request->TajNo);});
            })
            ->where('raseed','<=',$request->baky)
            ->first();
        $reportHtml = view('PrnView.aksat.pdf-mosdada',
            ['pdfdetail'=>$res,'cus'=>$cus,'bank_name'=>$request->bank_name,'sum'=>$sum,'RepDate'=>$RepDate])->render();
        $reportHtml=$this->convertToArabic($reportHtml);
        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    public function PdfKhasf(Request $request){

        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();

        $res=DB::connection(Auth()->user()->company)->table($request->from)
            ->when($request->ByTajmeehy=='Bank',function($q) use($request){
                $q->where('bank', '=', $request->bank_no);
            })
            ->when($request->ByTajmeehy=='Taj',function($q) use($request){
                $q-> whereIn('bank', function($q)  use($request){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$request->TajNo);});
            })

            ->get();

        $reportHtml = view('PrnView.aksat.pdf-khasf',
            ['res'=>$res,'cus'=>$cus,'bank_name'=>$request->bank_name,'RepDate'=>$RepDate,
                'By'=>$request->ByTajmeehy,'from'=>$request->from])->render();
        $reportHtml=$this->convertToArabic($reportHtml);

        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    function PdfKamla(Request $request){

        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();
        if ($request->RepRadio=='all') {
            $first = DB::connection(Auth()->user()->company)->table('main_trans_view2')
                ->selectRaw('no,name,sul_date,sul,sul_pay,raseed,kst,bank_name,acc,order_no,max(ksm_date) as ksm_date')
                ->when($request->ByTajmeehy=='Taj',function($q) use ($request) {
                    $q-> whereIn('bank', function($q) use ($request) {
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$request->TajNo);});
                })
                ->where('sul_pay','!=',0)

                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('late')
                        ->whereColumn('main_trans_view2.no', 'late.no')
                        ->where('emp', Auth::user()->empno);
                })
                ->groupBy('no', 'name', 'sul_date', 'sul', 'sul_pay', 'raseed', 'kst', 'bank_name', 'acc', 'order_no');
            $second = DB::connection(Auth()->user()->company)->table('main_view')
                ->selectraw('no,name,sul_date,sul,sul_pay,raseed,kst,bank_name,acc,order_no,null as ksm_date')
                ->when($request->ByTajmeehy=='Taj',function($q) use ($request) {
                    $q-> whereIn('bank', function($q) use ($request) {
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$request->TajNo);});
                })
                ->where('sul_pay','=',0)

                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('late')
                        ->whereColumn('main_view.no', 'late.no')
                        ->where('emp', Auth::user()->empno);
                })
                ->union($first)
                ->get();
        }
        if ($request->RepRadio=='some'){

            $second=DB::connection(Auth()->user()->company)->table('main_view')
                ->selectraw('no,name,sul_date,sul,sul_pay,raseed,kst,bank_name,acc,order_no,null as ksm_date')
                ->when($request->ByTajmeehy=='Taj',function($q) use ($request) {
                    $q-> whereIn('bank', function($q) use ($request) {
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$request->TajNo);});
                })
                ->where('sul_pay','=',0)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('late')
                        ->whereColumn('main_view.no', 'late.no')
                        ->where('emp',Auth::user()->empno);
                })

                ->get();

        }



        $reportHtml = view('PrnView.aksat.pdf-kamla',
            ['res'=>$second,'cus'=>$cus,'bank_name'=>$request->bank_name,'months'=>$request->months,'RepDate'=>$RepDate,'RepRadio'=>$request->RepRadio])->render();
        $reportHtml=$this->convertToArabic($reportHtml);

        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }

    function PdfBefore(Request $request){
        $res=DB::connection(Auth()->user()->company)->table('settings')->where('no',3)->first();
        $DAY_OF_KSM=$res->s1;
        $day=Carbon::now()->day;
        $month=Carbon::now()->month;
        $year=Carbon::now()->year;

        $Month=$month.'\\'.$year;

        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();
        $RepTable= DB::connection(Auth()->user()->company)->table('main')
            ->join('late','main.no','=','late.no')
            ->selectRaw('acc,name,sul_date,sul,kst_count,sul_pay,raseed,main.kst,main.no,round((sul_pay/kst),0) pay_count,late,
                               late*main.kst kst_late')
            ->when($request->ByTajmeehy=='Bank',function ($q){
                return $q->where('bank',\request()->bank_no);
            })
            ->when($request->ByTajmeehy=='Taj',function ($q){
                return $q->whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',\request()->TajNo);});
            })

            ->when($request->Not_pay=='some',function($q){
                return $q->where([

                    ['sul_pay',0],
                    ['late', '>', 0],
                    ['kst','!=',0],]);})
            ->when( $request->Not_pay=='all',function ($q) {
                return $q->where([

                    ['late', '>', 0],
                    ['kst','!=',0],]);})
            ->get(15);


        $reportHtml = view('PrnView.aksat.pdf-before',
            ['res'=>$RepTable,'cus'=>$cus,'bank_name'=>$request->bank_name,'TajName'=>$request->bank_name,'ByTajmeehy'=>$request->ByTajmeehy,'month'=>$Month,'RepDate'=>$RepDate,'Not_pay'=>$request->Not_pay])->render();
        $reportHtml=$this->convertToArabic($reportHtml);

        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
}

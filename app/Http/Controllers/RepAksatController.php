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
    }  function PdfMain($no){

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
}

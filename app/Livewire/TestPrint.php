<?php

namespace App\Livewire;

use App\Models\Customers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TestPrint extends Component
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
    public function printme()
    {
        $this->PdfM('Bank',1,0,2,'any bank name');
    }
    public function PdfM($ByTajmeehy,$bank_no,$TajNo,$baky,$bank_name){
        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();
        $res=DB::connection(Auth()->user()->company)->table('main_view')
            ->when($ByTajmeehy=='Bank',function($q) use($ByTajmeehy,$bank_no){
                $q->where('bank', '=', $bank_no);
            })
            ->when($ByTajmeehy=='Taj',function($q) use($TajNo){
                $q-> whereIn('bank', function($q)  use($TajNo){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
            })
            ->where('raseed','<=',$baky)
            ->get();
        $sum=DB::connection(Auth()->user()->company)->table('main')
            ->selectRaw('sum(sul_tot) as sul_tot,sum(dofa) as dofa,sum(sul) as sul,
             sum(sul_pay) as sul_pay,sum(raseed) as raseed')
            ->when($ByTajmeehy=='Bank',function($q) use($bank_no){
                $q->where('bank', '=', $bank_no);
            })
            ->when($ByTajmeehy=='Taj',function($q) use($TajNo){
                $q-> whereIn('bank', function($q)  use($TajNo){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$TajNo);});
            })
            ->where('raseed','<=',$baky)
            ->first();
        $reportHtml = view('PrnView.aksat.pdf-mosdada',
            ['pdfdetail'=>$res,'cus'=>$cus,'bank_name'=>$bank_name,'sum'=>$sum,'RepDate'=>$RepDate])->render();
        $reportHtml=$this->convertToArabic($reportHtml);
        $pdf = PDF::loadHTML($reportHtml);

        return response()->streamDownload(function () use ($reportHtml) {
            echo $reportHtml; // Echo download contents directly...
        }, 'invoice.pdf');



      //  return $pdf->download('report.pdf')->base64();


    }

    public function render()
    {
        return view('livewire.test-print');
    }
}

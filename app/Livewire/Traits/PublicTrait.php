<?php
namespace App\Livewire\Traits;




use App\Models\Customer;
use App\Models\OurCompany;
use Spatie\LaravelPdf\Facades\Pdf;
use Exception;
use App\Enums\TarType;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;


use App\Models\Salary;

use App\Models\Setting;
use Carbon\Carbon;
use DateTime;
use Faker\Core\File;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;

trait PublicTrait {



    protected static function getMy($name): TextColumn
    {
        if ($name === 'no') $label='رقم العقد';
        if ($name === 'acc') $label='رقم الحساب';
        if ($name === 'name') $label='الاسم';
        if ($name === 'kst') $label='القسط';
        if ($name === 'sul_date') $label='تاريخ العقد';
        if ($name === 'tar_date') $label='التاريخ';
        if ($name === 'tar_type') $label='نوع الترجيع';
        if ($name === 'morahel') $label='الحالة';
        if ($name === 'stop_date') $label='تاريخ التوقف';
        if ($name === 'notes') $label='ملاحظات';

        return TextColumn::make($name)
                ->label($label)
                ->searchable()
                ->sortable();
    }



    public static function ret_spatie_header(){
        return       $headers = [
            'Content-Type' => 'application/pdf',
        ];

    }
    public static function ret_spatie($res,$blade,$arr=[])
    {
        if(!\Illuminate\Support\Facades\File::exists(Auth::user()->company)) \Illuminate\Support\Facades\File::makeDirectory(Auth::user()->company);
        $cus=Customer::where('Company',Auth::user()->company)->first();
        Pdf::view($blade,
            ['res'=>$res,'arr'=>$arr,'cus'=>$cus])
            ->footerView('PrnView.footer')
            ->withBrowsershot(function (Browsershot $shot) {
                $shot->noSandbox()
                    ->setChromePath(Setting::first()->exePath);
            })
            ->margins(10, 60, 40, 10, Unit::Pixel)
            ->save(Auth::user()->company.'/invoice-2023-04-10.pdf');
        return public_path().'/'.Auth::user()->company.'/invoice-2023-04-10.pdf';

    }
    public static function ret_spatie_land($res,$blade,$arr=[])
    {
        Pdf::view($blade,
            ['res'=>$res,'arr'=>$arr])
            ->footerView('PrnView.footer')
            ->withBrowsershot(function (Browsershot $shot) {
                $shot->noSandbox()
                    ->setChromePath(Setting::first()->exePath);
            })
            ->landscape()
            ->save(Auth::user()->company.'/invoice-2023-04-10.pdf');
        return public_path().'/'.Auth::user()->company.'/invoice-2023-04-10.pdf';

    }

    public static function ksm_kst($no,$kst,$date,$h_no=0,$ksm_type=2){
        $main=main::find($no);
        $ksm=$kst;
        $over=0;

        if ($main->raseed<=0) {
            $ksm=0;
            $over=$kst;
        }
        if ($main->raseed>0 && $ksm>$main->raseed ) {
            $ksm=$main->raseed;
            $over=$kst-$main->raseed;
        }

        DB::connection(Auth()->user()->company)->beginTransaction();
        try {
            if ($ksm!=0){
                $results=kst_trans::where('no',$main->no)->where(function ($query) {
                    $query->where('ksm', '=', null)
                        ->orWhere('ksm', '=', 0);
                })->min('ser');
                $ser= empty($results)? 0 : $results;

                if ($ser!=0) {
                    kst_trans::where('no',$main->no)->where('ser',$ser)->update([
                        'ksm'=>$ksm,
                        'ksm_date'=>$date,
                        'ksm_type'=>$ksm_type,
                        'inp_date'=>date('Y-m-d'),
                        'h_no'=>$h_no,
                        'emp'=>auth::user()->empno,
                    ]);

                } else
                {
                    $max=(kst_trans::where('no',$main->no)->max('ser'))+1;

                    kst_trans::insert([
                        'ser'=>$max,
                        'no'=>$main->no,
                        'kst_date'=>$date,
                        'ksm_type'=>$ksm_type,
                        'h_no'=>$h_no,
                        'chk_no'=>0,
                        'kst'=>$ksm,
                        'ksm_date'=>$date,
                        'ksm'=>$ksm,
                        'inp_date'=>date('Y-m-d'),
                        'emp'=>auth::user()->empno,
                    ]);
                }

            }
            if ($over!=0) {

                DB::connection(Auth()->user()->company)->table('over_kst')->insert([
                    'no'=>$main->no,
                    'h_no'=>$h_no,
                    'name'=>$main->name,
                    'bank'=>$main->bank,
                    'acc'=>$main->acc,
                    'kst'=>$over,
                    'tar_type'=>1,
                    'tar_date'=>$date,
                    'letters'=>0,
                    'emp'=>auth::user()->empno,
                ]);
            }
             $main->sul_pay=$main->sul_pay+$ksm;
             $main->raseed=$main->raseed-$ksm;
             $main->save();

            DB::connection(Auth()->user()->company)->commit();


        } catch (Exception $e) {
            DB::connection(Auth()->user()->company)->rollback();


        }


    }



}

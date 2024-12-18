<?php
namespace App\Livewire\Traits;



use App\Enums\AccLevel;
use App\Models\Rent;
use App\Models\Renttran;
use App\Models\Salary;
use App\Models\Salarytran;

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
        \Spatie\LaravelPdf\Facades\Pdf::view($blade,
            ['res'=>$res,'arr'=>$arr])
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
        \Spatie\LaravelPdf\Facades\Pdf::view($blade,
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



}

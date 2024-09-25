<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('filament.admin.auth.login'));
});

Route::controller(\App\Http\Controllers\RepAksatController::class)->group(function (){
    route::get('/pdfmain/{no}', 'PdfMain')->name('pdfmain') ;
    route::get('/pdfmaincont/{no}', 'PdfMainCont')->name('pdfmaincont') ;
    route::get('/pdfmosdada/{ByTajmeehy?}/{TajNo?}/{bank_no?}/{baky?}/{bank_name?}', 'PdfMosdada')->name('pdfmosdada') ;
    route::get('/pdfkhasf/{ByTajmeehy?}/{TajNo?}/{bank_no?}/{bank_name?}/{from?}', 'PdfKhasf')->name('pdfkhasf') ;
    route::get('/pdfkamla/{ByTajmeehy?}/{TajNo?}/{bank_no?}/{months?}/{bank_name?}/{RepRadio?}', 'PdfKamla')->name('pdfkamla') ;
    route::get('/pdfbefore/{ByTajmeehy?}/{TajNo?}/{bank_no?}/{bank_name?}/{Not_pay?}', 'PdfBefore')->name('pdfbefore') ;
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('filament.admin.auth.login'));
});

Route::controller(\App\Http\Controllers\RepAksatController::class)->group(function (){
    route::get('/pdfmain/{no}', 'PdfMain')->name('pdfmain') ;
    route::get('/pdfmaincont/{no}', 'PdfMainCont')->name('pdfmaincont') ;
});

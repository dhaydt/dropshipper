<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use LaravelQRCode\Facades\QRCode;
use Madnest\Madzipper\Facades\Madzipper;

Route::get('zip-extract', function () {
    Madzipper::make('test-zip.zip')->extractTo('public');
});

Route::get('/view-test', function () {
    view('welcome');
});


Route::get('qr-code', function () {
    return QRCode::text('Laravel QR Code Generator!')
        ->setOutfile('storage/app/public/deal/2021-10-30-617d68a9a7e8b.png')
        ->png();
});

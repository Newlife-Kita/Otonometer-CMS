<?php

use App\Http\Controllers\InformasiController;
use App\Import\BidangkeuanganImportTest;
use App\Repositories\BidangRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/download-info/{nama}', function ($nama) {
    return streamFile($nama);
});

Route::get('check-version', function() {
    return response(['version' => '1.0.2'], 200);
});

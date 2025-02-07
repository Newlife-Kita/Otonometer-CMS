<?php

use App\Http\Controllers\VersionController;
use Illuminate\Support\Facades\Route;

use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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

Auth::routes();
Auth::routes(['register' => false]);

Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return redirect('dashboard');
})->name('clear-cache');

Route::get('/', function () {
    return view('welcome');
});
Route::get('home', function () {
    return redirect('dashboard');
});
Route::get('register', function () {
    return redirect('/');
});
Route::post('register', function () {
    return redirect('/');
});

Route::get('/dashboard', 'Webcore\HomeController@index')->name('dashboard');
Route::get('profile', 'Webcore\HomeController@profile')->name('profile');
Route::post('profile/submit', 'Webcore\HomeController@update_profile')->name('profile.submit');

Route::resource('permissiongroups', 'Webcore\PermissiongroupController');
Route::resource('permissions', 'Webcore\PermissionController');
Route::resource('roles', 'Webcore\RoleController');
Route::post('users/permissions', 'Webcore\UserController@permissions')->name('users.permissions');
Route::resource('users', 'Webcore\UserController');
Route::resource('admin', 'Webcore\AdminController');


Route::resource('datarans', 'DataranController');
// Route::post('importDataran', 'DataranController@import');

Route::get('wilayahs/download', ['as' => 'wilayahs.download', 'uses' => 'WilayahController@download_template']);
Route::get('wilayahs/ajax', ['as' => 'wilayahs.ajax', 'uses' => 'WilayahController@ajax']);
Route::get('wilayahs/{id}/cities', ['as' => 'wilayahs.cities', 'uses' => 'WilayahController@indexChild']);
Route::get('wilayahs/{id}/create', ['as' => 'wilayahs.createcities', 'uses' => 'WilayahController@createChild']);
Route::get('wilayahs/{id}/edit-cities', ['as' => 'wilayahs.editcities', 'uses' => 'WilayahController@editChild']);

Route::post('wilayahs/upload', ['as' => 'wilayahs.upload', 'uses' => 'WilayahController@uploadExcel']);
Route::resource('wilayahs', 'WilayahController');
// Route::post('importWilayah', 'WilayahController@import');

Route::resource('satuans', 'SatuanController');
// Route::post('importSatuan', 'SatuanController@import');

Route::resource('komisis', 'KomisiController');
// Route::post('importKomisi', 'KomisiController@import');

Route::resource('jabatans', 'JabatanController');
// Route::post('importJabatan', 'JabatanController@import');

Route::get('sudins', ['as' => 'sudins.index', 'uses' => 'SudinController@index']);
Route::post('sudins', ['as' => 'sudins.store', 'uses' => 'SudinController@store']);
Route::post('sudins/simpan-pejabat-sudin', ['as' => 'sudins.store_pejabat', 'uses' => 'SudinController@store_pejabat']);
Route::post('sudins/update-pejabat-sudin', ['as' => 'sudins.update_pejabat', 'uses' => 'SudinController@update_pejabat']);
Route::post('sudins/upload/{id}', ['as' => 'sudins.upload', 'uses' => 'SudinController@upload']);
Route::post('sudins/upload-pejabat/{id}', ['as' => 'sudins.upload_pejabat', 'uses' => 'SudinController@upload_pejabat']);
Route::get('sudins/{wilayah}/preview', ['as' => 'sudins.preview', 'uses' => 'SudinController@preview']);
Route::delete('sudins/{id}/preview', ['as' => 'sudins.preview_delete', 'uses' => 'SudinController@destroy_preview']);
Route::get('sudins/{id}/edit-preview', ['as' => 'sudins.edit_preview', 'uses' => 'SudinController@edit_preview']);
Route::patch('sudins/{id}/preview', ['as' => 'sudins.update_preview', 'uses' => 'SudinController@update_preview']);
Route::post('sudins/{id}/img/upload', ['as' => 'sudins.img_upload', 'uses' => 'SudinController@upload_logo']);
Route::post('sudins/{wilayah}/submit', ['as' => 'sudins.submit', 'uses' => 'SudinController@submit']);
Route::delete('sudins/{wilayah}/cancel', ['as' => 'sudins.cancel', 'uses' => 'SudinController@cancel']);
Route::get('sudins/{id}/preview-pejabat', ['as' => 'sudins.preview_pejabat', 'uses' => 'SudinController@preview_pejabat']);
Route::delete('sudins/{id]/preview-pejabat', ['as' => 'sudins.preview_pejabat_delete', 'uses' => 'SudinController@destroy_preview_pejabat']);
Route::patch('sudins/{id}/preview-pejabat', ['as' => 'sudins.update_preview_pejabat', 'uses' => 'SudinController@update_preview_pejabat']);
Route::get('sudins/{id}/edit-preview-pejabat', ['as' => 'sudins.edit_preview_pejabat', 'uses' => 'SudinController@edit_preview_pejabat']);
Route::get('sudins/template/{id}', ['as' => 'sudins.template', 'uses' => 'SudinController@template']);
Route::get('sudins/template-pejabat/{id}', ['as' => 'sudins.pejabat_template', 'uses' => 'SudinController@template_pejabat']);
Route::post('sudins/pejabat/{id}/img/upload', ['as' => 'sudins.pejabat_img_upload', 'uses' => 'SudinController@upload_photo_pejabat']);
Route::post('sudins/pejabat/{id}/submit', ['as' => 'sudins.submit_pejabat', 'uses' => 'SudinController@submit_pejabat']);
Route::delete('sudins/pejabat/{id}/cancel', ['as' => 'sudins.cancel_pejabat', 'uses' => 'SudinController@cancel_pejabat']);
Route::patch('sudins/{id}', ['as' => 'sudins.update', 'uses' => 'SudinController@update']);
Route::delete('sudins/{id}', ['as' => 'sudins.destroy', 'uses' => 'SudinController@destroy']);
Route::get('sudins/download', ['as' => 'sudins.download', 'uses' => 'SudinController@download']);
Route::get('sudins/download-pejabat/{id}', ['as' => 'sudins.download_2', 'uses' => 'SudinController@download2']);
Route::get('sudins/{id}', ['as' => 'sudins.show', 'uses' => 'SudinController@show']);
Route::get('sudins/{id}/create', ['as' => 'sudins.create', 'uses' => 'SudinController@create']);
Route::get('sudins/{wilayah}/{id}/pejabat', ['as' => 'sudins.pejabat', 'uses' => 'SudinController@pejabat_show']);
Route::get('sudins/{wilayah}/{id}/create', ['as' => 'sudins.create_pejabat', 'uses' => 'SudinController@pejabat_create']);
Route::get('sudins/{wilayah}/{id}/edit', ['as' => 'sudins.edit', 'uses' => 'SudinController@edit']);
Route::get('sudins/{wilayah}/{id}/{pejabat}/edit', ['as' => 'sudins.edit_pejabat', 'uses' => 'SudinController@edit_pejabat']);
Route::delete('sudins/{wilayah}/{id}/{pejabat}', ['as' => 'sudins.destroy_pejabat', 'uses' => 'SudinController@destroy_pejabat']);
// Route::resource('sudins', 'SudinController');
// Route::post('importSudin', 'SudinController@import');

Route::get('pejabat-sudin', ['as' => 'pejabatsudins.index', 'uses' => 'PejabatsudinController@index']);
Route::post('pejabat-sudin', ['as' => 'pejabatsudins.store', 'uses' => 'PejabatsudinController@store']);
Route::delete('pejabat-sudin/{id}/cancel', ['as' => 'pejabatsudins.cancel', 'uses' => 'PejabatsudinController@cancel']);
Route::delete('pejabat-sudin/{id}/preview', ['as' => 'pejabatsudins.preview_delete', 'uses' => 'PejabatsudinController@previewDelete']);
Route::patch('pejabat-sudin/{id}/preview', ['as' => 'pejabatsudins.preview_update', 'uses' => 'PejabatsudinController@previewUpdate']);
Route::get('pejabat-sudin/{id}/edit-preview', ['as' => 'pejabatsudins.edit_preview', 'uses' => 'PejabatsudinController@editPreview']);
Route::get('pejabat-sudin/{id}/preview', ['as' => 'pejabatsudins.preview', 'uses' => 'PejabatsudinController@preview']);
Route::post('pejabat-sudin/{id}/submit', ['as' => 'pejabatsudins.submit', 'uses' => 'PejabatsudinController@submit']);
Route::get('pejabat-sudin/{id}/upload', ['as' => 'pejabatsudins.create_excel', 'uses' => 'PejabatsudinController@createExcel']);
Route::post('pejabat-sudin/{id}/upload', ['as' => 'pejabatsudins.store_excel', 'uses' => 'PejabatsudinController@storeExcel']);
Route::post('pejabat-sudin/{id}/img/upload', ['as' => 'pejabatsudins.img_upload', 'uses' => 'PejabatsudinController@uploadPhoto']);
Route::get('pejabat-sudin/{id}/template', ['as' => 'pejabatsudins.template', 'uses' => 'PejabatsudinController@template']);
Route::get('pejabat-sudin/{id}/dinas', ['as' => 'pejabatsudins.dinas', 'uses' => 'PejabatsudinController@Dinas']);
Route::get('pejabat-sudin/{id}/create', ['as' => 'pejabatsudins.create', 'uses' => 'PejabatsudinController@create']);
Route::patch('pejabat-sudin/{id}', ['as' => 'pejabatsudins.update', 'uses' => 'PejabatsudinController@update']);
Route::delete('pejabat-sudin/{id}', ['as' => 'pejabatsudins.destroy', 'uses' => 'PejabatsudinController@destroy']);
Route::get('pejabat-sudin/{id}', ['as' => 'pejabatsudins.show', 'uses' => 'PejabatsudinController@show']);
Route::get('pejabat-sudin/{id}/edit', ['as' => 'pejabatsudins.edit', 'uses' => 'PejabatsudinController@edit']);
// Route::get('pejabat-sudin/{id}/sudin', ['as'=> 'pejabatsudins.indexsudin', 'uses' => 'PejabatsudinController@indexsudin']);
// Route::post('importPejabatsudin', 'PejabatsudinController@import');


Route::get('pejabat-wilayah', ['as' => 'pejabatwilayahs.index', 'uses' => 'PejabatwilayahController@index']);
Route::post('pejabat-wilayah', ['as' => 'pejabatwilayahs.store', 'uses' => 'PejabatwilayahController@store']);
Route::get('pejabat-wilayah/upload', ['as' => 'pejabatwilayahs.create_excel_all', 'uses' => 'PejabatwilayahController@createExcelAll']);
Route::post('pejabat-wilayah/upload', ['as' => 'pejabatwilayahs.store_excel_all', 'uses' => 'PejabatwilayahController@storeExcelAll']);
Route::get('pejabat-wilayah/template', ['as' => 'pejabatwilayahs.template_all', 'uses' => 'PejabatwilayahController@templateAll']);
Route::get('pejabat-wilayah/preview', ['as' => 'pejabatwilayahs.preview_all', 'uses' => 'PejabatwilayahController@previewAll']);
Route::post('pejabat-wilayah/submit-all', ['as' => 'pejabatwilayahs.submit_all', 'uses' => 'PejabatwilayahController@submitAll']);
Route::delete('pejabat-wilayah/cancel-all', ['as' => 'pejabatwilayahs.cancel_all', 'uses' => 'PejabatwilayahController@cancelAll']);
Route::get('pejabat-wilayah/download', ['as' => 'pejabatwilayahs.download', 'uses' => 'PejabatwilayahController@downloadAll']);
Route::get('pejabat-wilayah/{id}/edit-preview-all', ['as' => 'pejabatwilayahs.edit_preview_all', 'uses' => 'PejabatwilayahController@editPreviewAll']);
Route::delete('pejabat-wilayah/{id}/preview-all', ['as' => 'pejabatwilayahs.preview_delete_all', 'uses' => 'PejabatwilayahController@previewDeleteAll']);
Route::patch('pejabat-wilayah/{id}/preview-all', ['as' => 'pejabatwilayahs.preview_update_all', 'uses' => 'PejabatwilayahController@previewUpdateAll']);
Route::delete('pejabat-wilayah/{id}/cancel', ['as' => 'pejabatwilayahs.cancel', 'uses' => 'PejabatwilayahController@cancel']);
Route::delete('pejabat-wilayah/{id}/preview', ['as' => 'pejabatwilayahs.preview_delete', 'uses' => 'PejabatwilayahController@previewDelete']);
Route::patch('pejabat-wilayah/{id}/preview', ['as' => 'pejabatwilayahs.preview_update', 'uses' => 'PejabatwilayahController@previewUpdate']);
Route::get('pejabat-wilayah/{id}/edit-preview', ['as' => 'pejabatwilayahs.edit_preview', 'uses' => 'PejabatwilayahController@editPreview']);
Route::get('pejabat-wilayah/{id}/preview', ['as' => 'pejabatwilayahs.preview', 'uses' => 'PejabatwilayahController@preview']);
Route::post('pejabat-wilayah/{id}/submit', ['as' => 'pejabatwilayahs.submit', 'uses' => 'PejabatwilayahController@submit']);
Route::get('pejabat-wilayah/{id}/upload', ['as' => 'pejabatwilayahs.create_excel', 'uses' => 'PejabatwilayahController@createExcel']);
Route::post('pejabat-wilayah/{id}/upload', ['as' => 'pejabatwilayahs.store_excel', 'uses' => 'PejabatwilayahController@storeExcel']);
Route::post('pejabat-wilayah/{id}/img/upload', ['as' => 'pejabatwilayahs.img_upload', 'uses' => 'PejabatwilayahController@uploadPhoto']);
Route::get('pejabat-wilayah/{id}/template', ['as' => 'pejabatwilayahs.template', 'uses' => 'PejabatwilayahController@template']);
Route::get('pejabat-wilayah/{id}/create', ['as' => 'pejabatwilayahs.create', 'uses' => 'PejabatwilayahController@create']);
Route::patch('pejabat-wilayah/{id}', ['as' => 'pejabatwilayahs.update', 'uses' => 'PejabatwilayahController@update']);
Route::delete('pejabat-wilayah/{id}', ['as' => 'pejabatwilayahs.destroy', 'uses' => 'PejabatwilayahController@destroy']);
Route::get('pejabat-wilayah/{id}', ['as' => 'pejabatwilayahs.show', 'uses' => 'PejabatwilayahController@show']);
Route::get('pejabat-wilayah/{id}/edit', ['as' => 'pejabatwilayahs.edit', 'uses' => 'PejabatwilayahController@edit']);
// Route::resource('pejabatwilayahs', 'PejabatwilayahController');
// Route::post('importPejabatwilayah', 'PejabatwilayahController@import');

Route::get('anggota-dprd', ['as' => 'dprds.index', 'uses' => 'DprdController@index']);
Route::post('anggota-dprd', ['as' => 'dprds.store', 'uses' => 'DprdController@store']);
Route::Get('anggota-dprd/download', ['as' => 'dprds.download', 'uses' => 'DprdController@downloadAll']);
Route::delete('anggota-dprd/{id}/cancel', ['as' => 'dprds.cancel', 'uses' => 'DprdController@cancel']);
Route::delete('anggota-dprd/{id}/preview', ['as' => 'dprds.preview_delete', 'uses' => 'DprdController@previewDelete']);
Route::patch('anggota-dprd/{id}/preview', ['as' => 'dprds.preview_update', 'uses' => 'DprdController@previewUpdate']);
Route::get('anggota-dprd/{id}/edit-preview', ['as' => 'dprds.edit_preview', 'uses' => 'DprdController@editPreview']);
Route::get('anggota-dprd/{id}/preview', ['as' => 'dprds.preview', 'uses' => 'DprdController@preview']);
Route::post('anggota-dprd/{id}/submit', ['as' => 'dprds.submit', 'uses' => 'DprdController@submit']);
Route::get('anggota-dprd/{id}/upload', ['as' => 'dprds.create_excel', 'uses' => 'DprdController@createExcel']);
Route::post('anggota-dprd/{id}/upload', ['as' => 'dprds.store_excel', 'uses' => 'DprdController@storeExcel']);
Route::post('anggota-dprd/{id}/img/upload', ['as' => 'dprds.img_upload', 'uses' => 'DprdController@uploadPhoto']);
Route::get('anggota-dprd/{id}/template', ['as' => 'dprds.template', 'uses' => 'DprdController@template']);
Route::get('anggota-dprd/{id}/create', ['as' => 'dprds.create', 'uses' => 'DprdController@create']);
Route::patch('anggota-dprd/{id}', ['as' => 'dprds.update', 'uses' => 'DprdController@update']);
Route::delete('anggota-dprd/{id}', ['as' => 'dprds.destroy', 'uses' => 'DprdController@destroy']);
Route::get('anggota-dprd/{id}', ['as' => 'dprds.show', 'uses' => 'DprdController@show']);
Route::get('anggota-dprd/{id}/edit', ['as' => 'dprds.edit', 'uses' => 'DprdController@edit']);
// Route::resource('dprds', 'DprdController');
// Route::post('importDprd', 'DprdController@import');

Route::get('pimpinan-dprd', ['as' => 'pimpinandprds.index', 'uses' => 'PimpinanDprdController@index']);
Route::post('pimpinan-dprd', ['as' => 'pimpinandprds.store', 'uses' => 'PimpinanDprdController@store']);
Route::get('pimpinan-dprd/upload', ['as' => 'pimpinandprds.create_excel_all', 'uses' => 'PimpinanDprdController@createExcelAll']);
Route::post('pimpinan-dprd/upload', ['as' => 'pimpinandprds.store_excel_all', 'uses' => 'PimpinanDprdController@storeExcelAll']);
Route::get('pimpinan-dprd/template', ['as' => 'pimpinandprds.template_all', 'uses' => 'PimpinanDprdController@templateAll']);
Route::get('pimpinan-dprd/preview', ['as' => 'pimpinandprds.preview_all', 'uses' => 'PimpinanDprdController@previewAll']);
Route::delete('pimpinan-dprd/cancel', ['as' => 'pimpinandprds.cancel_all', 'uses' => 'PimpinanDprdController@cancelAll']);
Route::post('pimpinan-dprd/submit', ['as' => 'pimpinandprds.submit_all', 'uses' => 'PimpinanDprdController@submitAll']);
Route::get('pimpinan-dprd/download', ['as' => 'pimpinandprds.download', 'uses' => 'PimpinanDprdController@downloadAll']);
Route::get('pimpinan-dprd/{id}/edit-preview-all', ['as' => 'pimpinandprds.edit_preview_all', 'uses' => 'PimpinanDprdController@editPreviewAll']);
Route::patch('pimpinan-dprd/{id}/preview-all', ['as' => 'pimpinandprds.preview_update_all', 'uses' => 'PimpinanDprdController@previewUpdateAll']);
Route::delete('pimpinan-dprd/{id}/preview-all', ['as' => 'pimpinandprds.preview_delete_all', 'uses' => 'PimpinanDprdController@previewDeleteAll']);
Route::delete('pimpinan-dprd/{id}/cancel', ['as' => 'pimpinandprds.cancel', 'uses' => 'PimpinanDprdController@cancel']);
Route::delete('pimpinan-dprd/{id}/preview', ['as' => 'pimpinandprds.preview_delete', 'uses' => 'PimpinanDprdController@previewDelete']);
Route::patch('pimpinan-dprd/{id}/preview', ['as' => 'pimpinandprds.preview_update', 'uses' => 'PimpinanDprdController@previewUpdate']);
Route::get('pimpinan-dprd/{id}/edit-preview', ['as' => 'pimpinandprds.edit_preview', 'uses' => 'PimpinanDprdController@editPreview']);
Route::get('pimpinan-dprd/{id}/preview', ['as' => 'pimpinandprds.preview', 'uses' => 'PimpinanDprdController@preview']);
Route::post('pimpinan-dprd/{id}/submit', ['as' => 'pimpinandprds.submit', 'uses' => 'PimpinanDprdController@submit']);
Route::get('pimpinan-dprd/{id}/upload', ['as' => 'pimpinandprds.create_excel', 'uses' => 'PimpinanDprdController@createExcel']);
Route::post('pimpinan-dprd/{id}/upload', ['as' => 'pimpinandprds.store_excel', 'uses' => 'PimpinanDprdController@storeExcel']);
Route::post('pimpinan-dprd/{id}/img/upload', ['as' => 'pimpinandprds.img_upload', 'uses' => 'PimpinanDprdController@uploadPhoto']);
Route::get('pimpinan-dprd/{id}/template', ['as' => 'pimpinandprds.template', 'uses' => 'PimpinanDprdController@template']);
Route::get('pimpinan-dprd/{id}/create', ['as' => 'pimpinandprds.create', 'uses' => 'PimpinanDprdController@create']);
Route::patch('pimpinan-dprd/{id}', ['as' => 'pimpinandprds.update', 'uses' => 'PimpinanDprdController@update']);
Route::delete('pimpinan-dprd/{id}', ['as' => 'pimpinandprds.destroy', 'uses' => 'PimpinanDprdController@destroy']);
Route::get('pimpinan-dprd/{id}', ['as' => 'pimpinandprds.show', 'uses' => 'PimpinanDprdController@show']);
Route::get('pimpinan-dprd/{id}/edit', ['as' => 'pimpinandprds.edit', 'uses' => 'PimpinanDprdController@edit']);


Route::get('datawilayahs', ['as' => 'datawilayahs.index', 'uses' => 'DatawilayahController@index']);
Route::post('datawilayahs', ['as' => 'datawilayahs.store', 'uses' => 'DatawilayahController@store']);
Route::get('datawilayahs/template', ['as' => 'datawilayahs.template', 'uses' => 'DatawilayahController@template']);
Route::delete('datawilayahs/{tahun}/cancel', ['as' => 'datawilayahs.cancel', 'uses' => 'DatawilayahController@cancel']);
Route::delete('datawilayahs/{id}/preview', ['as' => 'datawilayahs.preview_delete', 'uses' => 'DatawilayahController@previewDelete']);
Route::patch('datawilayahs/{id}/preview', ['as' => 'datawilayahs.preview_update', 'uses' => 'DatawilayahController@previewUpdate']);
Route::get('datawilayahs/{id}/edit-preview', ['as' => 'datawilayahs.edit_preview', 'uses' => 'DatawilayahController@editPreview']);
Route::get('datawilayahs/{tahun}/preview', ['as' => 'datawilayahs.preview', 'uses' => 'DatawilayahController@preview']);
Route::post('datawilayahs/{tahun}/submit', ['as' => 'datawilayahs.submit', 'uses' => 'DatawilayahController@submit']);
Route::get('datawilayahs/upload', ['as' => 'datawilayahs.create_excel', 'uses' => 'DatawilayahController@createExcel']);
Route::post('datawilayahs/upload', ['as' => 'datawilayahs.store_excel', 'uses' => 'DatawilayahController@storeExcel']);
Route::get('datawilayahs/{id}/create', ['as' => 'datawilayahs.create', 'uses' => 'DatawilayahController@create']);
Route::patch('datawilayahs/{id}', ['as' => 'datawilayahs.update', 'uses' => 'DatawilayahController@update']);
Route::delete('datawilayahs/{id}', ['as' => 'datawilayahs.destroy', 'uses' => 'DatawilayahController@destroy']);
Route::get('datawilayahs/{id}', ['as' => 'datawilayahs.show', 'uses' => 'DatawilayahController@show']);
Route::get('datawilayahs/{id}/edit', ['as' => 'datawilayahs.edit', 'uses' => 'DatawilayahController@edit']);

// Route::post('importDatawilayah', 'DatawilayahController@import');

Route::resource('ekonomis', 'EkonomiController');
// Route::post('importBidangrill', 'BidangrillController@import');

Route::resource('kategoris', 'KategoriController');
// Route::post('importKategori', 'KategoriController@import');

Route::get('sektor-bidang/check-tree/{id}', ['as' => 'sektor-bidang.check-tree', 'uses' => 'BidangController@check_tree']);

Route::get('sektor-bidang/keuangan', ['as' => 'sektor-bidang.keuangan.index', 'uses' => 'BidangController@keuangan_index']);
Route::get('sektor-bidang/keuangan/{id}', ['as' => 'sektor-bidang.keuangan.show', 'uses' => 'BidangController@show']);
Route::delete('sektor-bidang/keuangan/{id}', ['as' => 'sektor-bidang.keuangan.destroy', 'uses' => 'BidangController@destroy']);
// Route::get('sektor-bidang/keuangan/{id}/list', ['as' => 'sektor-bidang.keuangan.child_index', 'uses' => 'BidangController@keuangan_childs']);
Route::get('sektor-bidang/keuangan/{id}/create', ['as' => 'sektor-bidang.keuangan.create', 'uses' => 'BidangController@keuangan_create']);
Route::get('sektor-bidang/keuangan/{id}/edit', ['as' => 'sektor-bidang.keuangan.edit', 'uses' => 'BidangController@edit']);
Route::patch('sektor-bidang/keuangan/{id}/update', ['as' => 'sektor-bidang.keuangan.update', 'uses' => 'BidangController@update']);
Route::post('sektor-bidang/keuangan/{id}/store', ['as' => 'sektor-bidang.keuangan.store', 'uses' => 'BidangController@store']);

Route::get('sektor-bidang/ekonomi', ['as' => 'sektor-bidang.ekonomi.index', 'uses' => 'BidangController@ekonomi_index']);
Route::get('sektor-bidang/ekonomi/{id}', ['as' => 'sektor-bidang.ekonomi.show', 'uses' => 'BidangController@show']);
Route::delete('sektor-bidang/ekonomi/{id}', ['as' => 'sektor-bidang.ekonomi.destroy', 'uses' => 'BidangController@destroy']);
// Route::get('sektor-bidang/ekonomi/{id}/list', ['as' => 'sektor-bidang.ekonomi.child_index', 'uses' => 'BidangController@ekonomi_childs']);
Route::get('sektor-bidang/ekonomi/{id}/create', ['as' => 'sektor-bidang.ekonomi.create', 'uses' => 'BidangController@ekonomi_create']);
Route::get('sektor-bidang/ekonomi/{id}/edit', ['as' => 'sektor-bidang.ekonomi.edit', 'uses' => 'BidangController@edit']);
Route::patch('sektor-bidang/ekonomi/{id}/update', ['as' => 'sektor-bidang.ekonomi.update', 'uses' => 'BidangController@update']);
Route::post('sektor-bidang/ekonomi/{id}/store', ['as' => 'sektor-bidang.ekonomi.store', 'uses' => 'BidangController@store']);

Route::get('sektor-bidang/statistik', ['as' => 'sektor-bidang.statistik.index', 'uses' => 'BidangController@statistik_index']);
Route::get('sektor-bidang/statistik/grouping', ['as' => 'sektor-bidang.statistik.grouping', 'uses' => 'BidangController@statistik_grouping']);
Route::get('sektor-bidang/statistik/form-grouping/{id}', ['as' => 'sektor-bidang.statistik.form-grouping', 'uses' => 'BidangController@statistik_form_grouping']);
Route::get('sektor-bidang/statistik/{id}', ['as' => 'sektor-bidang.statistik.show', 'uses' => 'BidangController@show']);
Route::delete('sektor-bidang/statistik/{id}', ['as' => 'sektor-bidang.statistik.destroy', 'uses' => 'BidangController@destroy']);
// Route::get('sektor-bidang/statistik/{id}/list', ['as' => 'sektor-bidang.statistik.child_index', 'uses' => 'BidangController@statistik_childs']);
Route::get('sektor-bidang/statistik/{id}/create', ['as' => 'sektor-bidang.statistik.create', 'uses' => 'BidangController@statistik_create']);
Route::get('sektor-bidang/statistik/{id}/edit', ['as' => 'sektor-bidang.statistik.edit', 'uses' => 'BidangController@statistik_edit']);
Route::patch('sektor-bidang/statistik/{id}/update', ['as' => 'sektor-bidang.statistik.update', 'uses' => 'BidangController@statistik_update']);
Route::post('sektor-bidang/statistik/{id}/store', ['as' => 'sektor-bidang.statistik.store', 'uses' => 'BidangController@statistik_store']);
Route::post('sektor-bidang/statistik/grouping', ['as' => 'sektor-bidang.statistik.store_grouping', 'uses' => 'BidangController@statistik_store_grouping']);

Route::patch('sektor-bidang/{id}/update', ['as' => 'bidangs.update', 'uses' => 'BidangController@update']);

Route::get('nomenklatur', ['as' => 'nomenklaturs.index', 'uses' => 'NomenklaturController@index']);
Route::get('nomenklatur/create', ['as' => 'nomenklaturs.create', 'uses' => 'NomenklaturController@create']);
Route::post('nomenklatur/store', ['as' => 'nomenklaturs.store', 'uses' => 'NomenklaturController@store']);
// Route::get('nomenklatur/{tahun}', ['as' => 'nomenklaturs.show', 'uses' => 'NomenklaturController@show']);
Route::get('nomenklatur/{id}/edit', ['as' => 'nomenklaturs.edit', 'uses' => 'NomenklaturController@edit']);
Route::patch('nomenklatur/{id}', ['as' => 'nomenklaturs.update', 'uses' => 'NomenklaturController@update']);
Route::delete('nomenklatur/{tahun}/delete', ['as' => 'nomenklaturs.destroy', 'uses' => 'NomenklaturController@destroy']);

Route::get('set-tahun-data', ['as' => 'nomenklaturtahuns.index', 'uses' => 'NomenklaturtahunController@index']);
Route::get('set-tahun-data/create', ['as' => 'nomenklaturtahuns.create', 'uses' => 'NomenklaturtahunController@create']);
Route::post('set-tahun-data/store', ['as' => 'nomenklaturtahuns.store', 'uses' => 'NomenklaturtahunController@store']);
Route::get('set-tahun-data/{id}', ['as' => 'nomenklaturtahuns.show', 'uses' => 'NomenklaturtahunController@show']);
Route::get('set-tahun-data/{tahun}/edit', ['as' => 'nomenklaturtahuns.edit', 'uses' => 'NomenklaturtahunController@edit']);
Route::patch('set-tahun-data/{tahun}/update', ['as' => 'nomenklaturtahuns.update', 'uses' => 'NomenklaturtahunController@update']);
Route::delete('set-tahun-data/{id}', ['as' => 'nomenklaturtahuns.destroy', 'uses' => 'NomenklaturtahunController@destroy']);
// Route::resource('nomenklaturtahuns', 'NomenklaturtahunController');

Route::get('data-keuangan', ['as' => 'data-keuangan.index', 'uses' => 'BidangnilaiController@index_keuangan']);
Route::get('data-keuangan/getdata', ['as' => 'data-keuangan.getdata', 'uses' => 'BidangnilaiController@ajax_sektor']);
Route::patch('data-keuangan/updatedata', ['as' => 'data-keuangan.updatedata', 'uses' => 'BidangnilaiController@ajax_update']);
Route::delete('data-keuangan/deleteupdated', ['as' => 'data-keuangan.deleteupdated', 'uses' => 'BidangnilaiController@ajax_update_delete']);
Route::delete('data-keuangan/deleteupdatedall', ['as' => 'data-keuangan.deleteupdatedall', 'uses' => 'BidangnilaiController@ajax_update_delete_all']);
Route::post('data-keuangan/publishupdate', ['as' => 'data-keuangan.publishupdate', 'uses' => 'BidangnilaiController@ajax_update_publish']);
Route::get('data-keuangan/preview', ['as' => 'data-keuangan.preview', 'uses' => 'BidangnilaiController@preview_keuangan']);
Route::get('data-keuangan/form-upload', ['as' => 'data-keuangan.uploadform', 'uses' => 'BidangnilaiController@form_keuangan']);
Route::get('data-keuangan/form-delete', ['as' => 'data-keuangan.deleteform', 'uses' => 'BidangnilaiController@form_delete_keuangan']);
Route::get('data-keuangan/get-sektor', ['as' => 'data-keuangan.getsektor', 'uses' => 'BidangnilaiController@get_sektor_keuangan']);
Route::get('data-keuangan/download', ['as' => 'data-keuangan.download', 'uses' => 'BidangnilaiController@download_keuangan']);
Route::get('data-keuangan/province/download', ['as' => 'data-keuangan.download.province', 'uses' => 'BidangnilaiController@download_keuangan_province']);
Route::get('data-keuangan/download-template', ['as' => 'data-keuangan.templateExcel', 'uses' => 'BidangnilaiController@template_keuangan']);
Route::post('data-keuangan/upload', ['as' => 'data-keuangan.upload', 'uses' => 'BidangnilaiController@upload_keuangan']);
Route::post('data-keuangan/publish', ['as' => 'data-keuangan.publish', 'uses' => 'BidangnilaiController@publish_keuangan']);
Route::post('data-keuangan/edit', ['as' => 'data-keuangan.edit', 'uses' => 'BidangnilaiController@edit_keuangan']);
Route::post('data-keuangan/delete', ['as' => 'data-keuangan.delete', 'uses' => 'BidangnilaiController@delete_keuangan']);
Route::post('data-keuangan/delete-upload', ['as' => 'data-keuangan.deleteUpload', 'uses' => 'BidangnilaiController@delete_upload_keuangan']);
Route::get('data-keuangan/download-upload', ['as' => 'data-keuangan.downloadUpload', 'uses' => 'BidangnilaiController@download_upload_keuangan']);

Route::get('data-ekonomi', ['as' => 'data-ekonomi.index', 'uses' => 'BidangnilaiController@index_ekonomi']);
Route::get('data-ekonomi/getdata', ['as' => 'data-ekonomi.getdata', 'uses' => 'BidangnilaiController@ajax_sektor']);
Route::patch('data-ekonomi/updatedata', ['as' => 'data-ekonomi.updatedata', 'uses' => 'BidangnilaiController@ajax_update']);
Route::delete('data-ekonomi/deleteupdated', ['as' => 'data-ekonomi.deleteupdated', 'uses' => 'BidangnilaiController@ajax_update_delete']);
Route::delete('data-ekonomi/deleteupdatedall', ['as' => 'data-ekonomi.deleteupdatedall', 'uses' => 'BidangnilaiController@ajax_update_delete_all']);
Route::post('data-ekonomi/publishupdate', ['as' => 'data-ekonomi.publishupdate', 'uses' => 'BidangnilaiController@ajax_update_publish']);
Route::get('data-ekonomi/preview', ['as' => 'data-ekonomi.preview', 'uses' => 'BidangnilaiController@preview_ekonomi']);
Route::get('data-ekonomi/form-upload', ['as' => 'data-ekonomi.uploadform', 'uses' => 'BidangnilaiController@form_ekonomi']);
Route::get('data-ekonomi/form-delete', ['as' => 'data-ekonomi.deleteform', 'uses' => 'BidangnilaiController@form_delete_ekonomi']);
Route::get('data-ekonomi/get-sektor', ['as' => 'data-ekonomi.getsektor', 'uses' => 'BidangnilaiController@get_sektor_ekonomi']);
Route::get('data-ekonomi/download', ['as' => 'data-ekonomi.download', 'uses' => 'BidangnilaiController@download_ekonomi']);
Route::get('data-ekonomi/province/download', ['as' => 'data-ekonomi.download.province', 'uses' => 'BidangnilaiController@download_ekonomi_province']);
Route::get('data-ekonomi/download-template', ['as' => 'data-ekonomi.templateExcel', 'uses' => 'BidangnilaiController@template_ekonomi']);
Route::post('data-ekonomi/upload', ['as' => 'data-ekonomi.upload', 'uses' => 'BidangnilaiController@upload_ekonomi']);
Route::post('data-ekonomi/publish', ['as' => 'data-ekonomi.publish', 'uses' => 'BidangnilaiController@publish_ekonomi']);
// Route::post('data-ekonomi/edit', ['as' => 'data-ekonomi.edit', 'uses' => 'BidangnilaiController@edit_ekonomi']);
Route::post('data-ekonomi/delete', ['as' => 'data-ekonomi.delete', 'uses' => 'BidangnilaiController@delete_ekonomi']);
Route::post('data-ekonomi/delete-upload', ['as' => 'data-ekonomi.deleteUpload', 'uses' => 'BidangnilaiController@delete_upload_ekonomi']);
Route::get('data-ekonomi/download-upload', ['as' => 'data-ekonomi.downloadUpload', 'uses' => 'BidangnilaiController@download_upload_ekonomi']);

Route::get('data-statistik', ['as' => 'data-statistik.index', 'uses' => 'BidangnilaiController@index_statistik']);
Route::get('data-statistik/getdata', ['as' => 'data-statistik.getdata', 'uses' => 'BidangnilaiController@ajax_sektor']);
Route::patch('data-statistik/updatedata', ['as' => 'data-statistik.updatedata', 'uses' => 'BidangnilaiController@ajax_update']);
Route::delete('data-statistik/deleteupdated', ['as' => 'data-statistik.deleteupdated', 'uses' => 'BidangnilaiController@ajax_update_delete']);
Route::delete('data-statistik/deleteupdatedall', ['as' => 'data-statistik.deleteupdatedall', 'uses' => 'BidangnilaiController@ajax_update_delete_all']);
Route::post('data-statistik/publishupdate', ['as' => 'data-statistik.publishupdate', 'uses' => 'BidangnilaiController@ajax_update_publish']);
Route::get('data-statistik/preview', ['as' => 'data-statistik.preview', 'uses' => 'BidangnilaiController@previews_tatistik']);
Route::get('data-statistik/form-upload', ['as' => 'data-statistik.uploadform', 'uses' => 'BidangnilaiController@form_statistik']);
Route::get('data-statistik/form-delete', ['as' => 'data-statistik.deleteform', 'uses' => 'BidangnilaiController@form_delete_statistik']);
Route::get('data-statistik/get-sektor', ['as' => 'data-statistik.getsektor', 'uses' => 'BidangnilaiController@get_sektor_statistik']);
Route::get('data-statistik/download', ['as' => 'data-statistik.download', 'uses' => 'BidangnilaiController@download_statistik']);
Route::get('data-statistik/province/download', ['as' => 'data-statistik.download.province', 'uses' => 'BidangnilaiController@download_statistik_province']);
Route::get('data-statistik/download-template', ['as' => 'data-statistik.templateExcel', 'uses' => 'BidangnilaiController@template_statistik']);
Route::post('data-statistik/upload', ['as' => 'data-statistik.upload', 'uses' => 'BidangnilaiController@upload_statistik']);
Route::post('data-statistik/publish', ['as' => 'data-statistik.publish', 'uses' => 'BidangnilaiController@publish_statistik']);
// Route::post('data-statistik/edit', ['as' => 'data-statistik.edit', 'uses' => 'BidangnilaiController@edit_statistik']);
Route::post('data-statistik/delete', ['as' => 'data-statistik.delete', 'uses' => 'BidangnilaiController@delete_statistik']);
Route::post('data-statistik/delete-upload', ['as' => 'data-statistik.deleteUpload', 'uses' => 'BidangnilaiController@delete_upload_statistik']);
Route::get('data-statistik/download-upload', ['as' => 'data-statistik.downloadUpload', 'uses' => 'BidangnilaiController@download_upload_statistik']);

Route::get('data-catatan-sektor/catatan', ['as' => 'bidangcatatan.preview', 'uses' => 'BidangnilaiController@catatan']);
Route::post('data-catatan-sektor/submit', ['as' => 'bidangcatatan.submit', 'uses' => 'BidangnilaiController@catatan_submit']);

Route::resource('partais', 'PartaiController');
// Route::post('importPartai', 'PartaiController@import');

Route::resource('bahasas', 'BahasaController'); // Sudah cukup untuk menyakup fungsionalitas pada module
// Route::post('importBahasa', 'BahasaController@import');

Route::resource('pakets', 'PaketController');
// Route::post('importPaket', 'PaketController@import');

Route::get('members/log-register', ['as' => 'members.log-register', 'uses' => 'MemberController@logRegister']);
Route::get('members/ajax-log-register', ['as' => 'members.ajax-log-register', 'uses' => 'MemberController@ajaxLogRegister']);
Route::resource('members', 'MemberController');

// Route::post('importMember', 'MemberController@import');

Route::resource('memberpakets', 'MemberpaketController');
// Route::post('importMemberpaket', 'MemberpaketController@import');

Route::resource('memberaktifitas', 'MemberaktifitasController');
// Route::post('importMemberaktifitas', 'MemberaktifitasController@import');

Route::resource('jobs', 'JobController');
// Route::post('importJob', 'JobController@import');

Route::resource('refrences', 'RefrenceController');
// Route::post('importRefrence', 'RefrenceController@import');

Route::resource('settings', 'SettingController');
// Route::post('importSetting', 'SettingController@import');


Route::resource('notes', 'NoteController');
// Route::post('importNote', 'NoteController@import');


Route::resource('kodepos', 'KodeposController');
Route::post('kodepos/hapus-kelurahan', ['as' => 'kodepos.hapuskelurahan', 'uses' => 'KodeposController@destroy_kelurahan']);
// Route::post('importKodepos', 'KodeposController@import');

Route::resource('informasis', 'InformasiController');
Route::get('informasis/download/{filename}', ['as' => 'informasis.download', 'uses' => 'InformasiController@download']);
// Route::post('importInformasi', 'InformasiController@import');


Route::resource('sumberdatas', 'SumberdataController');
// Route::post('importSumberdata', 'SumberdataController@import');

Route::resource('versions', 'VersionController');

Route::get('app-activity/log', ['as' => 'log.index', 'uses' => 'AppActivityController@indexForLog']);
Route::get('app-activity/log/daily', ['as' => 'log.daily', 'uses' => 'AppActivityController@getLogDataAjax']);
Route::get('app-activity/log/download', ['as' => 'log.download', 'uses' => 'AppActivityController@downloadLogDataExcel']);
Route::get('app-activity/region', ['as' => 'regions.index', 'uses' => 'AppActivityController@indexForRegion']);
Route::get('app-activity/region/log', ['as' => 'regions.log', 'uses' => 'AppActivityController@getRegionLogAjax']);
Route::get('app-activity/region/download', ['as' => 'regions.download', 'uses' => 'AppActivityController@downloadLogWilayahExcel']);

Route::get('log-upload', ['as' => 'log-data.upload', 'uses' => 'LogUploadController@index']);
Route::get('log-publish', ['as' => 'log-data.publish', 'uses' => 'LogUploadController@index_submit']);
Route::get('log-summary', ['as' => 'log-data.summary', 'uses' => 'LogSummaryDataController@index']);
// Route::post('importLogUpload', 'LogUploadController@import');


Route::get('app-structure', ['as' => 'app-structure.page', 'uses' => 'AppStructureController@tree']);
Route::get('app-structure/tree', ['as' => 'app-structure.tree', 'uses' => 'AppStructureController@tree']);
Route::get('app-structure/create', ['as' => 'app-structure.page.create', 'uses' => 'AppStructureController@pageCreate']);
Route::post('app-structure', ['as' => 'app-structure.page.store', 'uses' => 'AppStructureController@pageStore']);
Route::get('app-structure/{slug}/edit', ['as' => 'app-structure.page.edit', 'uses' => 'AppStructureController@pageEdit']);
Route::patch('app-structure/{slug}', ['as' => 'app-structure.page.update', 'uses' => 'AppStructureController@pageUpdate']);
Route::delete('app-structure/{slug}', ['as' => 'app-structure.page.delete', 'uses' => 'AppStructureController@pageDestroy']);
Route::get('app-structure/{slug}/node', ['as' => 'app-structure.node', 'uses' => 'AppStructureController@node']);
Route::get('app-structure/{slug}/node/create', ['as' => 'app-structure.node.create', 'uses' => 'AppStructureController@nodeCreate']);
Route::post('app-structure/{slug}/node', ['as' => 'app-structure.node.store', 'uses' => 'AppStructureController@nodeStore']);
Route::get('app-structure/node/{slug}/edit', ['as' => 'app-structure.node.edit', 'uses' => 'AppStructureController@nodeEdit']);
Route::patch('app-structure/node/{slug}', ['as' => 'app-structure.node.update', 'uses' => 'AppStructureController@nodeUpdate']);
Route::delete('app-structure/node/{slug}', ['as' => 'app-structure.node.delete', 'uses' => 'AppStructureController@nodeDestroy']);


// Route::post('importAppStructure', 'AppStructureController@import');

Route::get('app-text', ['as' => 'app-text.page', 'uses' => 'AppTextController@index']);
Route::get('app-text/{slug}/node', ['as' => 'app-text.node', 'uses' => 'AppTextController@node']);
Route::get('app-text/text/{slug}/edit', ['as' => 'app-text.text.edit', 'uses' => 'AppTextController@nodeEdit']);
Route::patch('app-text/text/{slug}', ['as' => 'app-text.text.update', 'uses' => 'AppTextController@nodeUpdate']);
// Route::post('importAppText', 'AppTextController@import');


Route::prefix('panel-admin/')->group(function() {
    Route::get('wilayah', ['as' => 'panel.wilayahs.index', 'uses' => 'Panel\WilayahController@index']);
    Route::get('wilayah/ajax', ['as' => 'panel.wilayahs.ajax', 'uses' => 'Panel\WilayahController@ajax']);
    Route::post('wilayah/update', ['as' => 'panel.wilayahs.update', 'uses' => 'Panel\WilayahController@update']);
    
    Route::get('data-wilayah', ['as' => 'panel.datawilayahs.index', 'uses' => 'Panel\DatawilayahController@index']);
    Route::get('data-wilayah/ajax', ['as' => 'panel.datawilayahs.ajax', 'uses' => 'Panel\DatawilayahController@ajax']);
    Route::post('data-wilayah/submit', ['as' => 'panel.datawilayahs.submit', 'uses' => 'Panel\DatawilayahController@submit']);
    Route::post('data-wilayah/delete', ['as' => 'panel.datawilayahs.delete', 'uses' => 'Panel\DatawilayahController@delete']);

    Route::get('kodepos', ['as' => 'panel.kodepos.index', 'uses' => 'Panel\KodeposController@index']);
    Route::get('kodepos/{id}/create', ['as' => 'panel.kodepos.create', 'uses' => 'Panel\KodeposController@create']);
    Route::get('kodepos/{id}/edit', ['as' => 'panel.kodepos.edit', 'uses' => 'Panel\KodeposController@edit']);
    Route::post('kodepos/update', ['as' => 'panel.kodepos.update', 'uses' => 'Panel\KodeposController@update']);
    Route::post('kodepos/store', ['as' => 'panel.kodepos.store', 'uses' => 'Panel\KodeposController@store']);
    Route::delete('kodepos/{id}/delete', ['as' => 'panel.kodepos.destroy', 'uses' => 'Panel\KodeposController@destroy']);

    Route::get('suku-dinas-pemerintahan', ['as' => 'panel.sudins.index', 'uses' => 'Panel\SudinController@index']);
    Route::get('suku-dinas-pemerintahan/create', ['as' => 'panel.sudins.create', 'uses' => 'Panel\SudinController@create']);
    Route::get('suku-dinas-pemerintahan/{id}/edit', ['as' => 'panel.sudins.edit', 'uses' => 'Panel\SudinController@edit']);
    Route::post('suku-dinas-pemerintahan/store', ['as' => 'panel.sudins.store', 'uses' => 'Panel\SudinController@store']);
    Route::patch('suku-dinas-pemerintahan/{id}/update', ['as' => 'panel.sudins.update', 'uses' => 'Panel\SudinController@update']);
    Route::delete('suku-dinas-pemerintahan/{id}/delete', ['as' => 'panel.sudins.destroy', 'uses' => 'Panel\SudinController@destroy']);
   
    Route::get('pejabat-suku-dinas', ['as' => 'panel.pejabat_sudins.index', 'uses' => 'Panel\PejabatsudinController@index']);
    Route::get('pejabat-suku-dinas/create', ['as' => 'panel.pejabat_sudins.create', 'uses' => 'Panel\PejabatsudinController@create']);
    Route::get('pejabat-suku-dinas/{id}/edit', ['as' => 'panel.pejabat_sudins.edit', 'uses' => 'Panel\PejabatsudinController@edit']);
    Route::post('pejabat-suku-dinas/store', ['as' => 'panel.pejabat_sudins.store', 'uses' => 'Panel\PejabatsudinController@store']);
    Route::patch('pejabat-suku-dinas/{id}/update', ['as' => 'panel.pejabat_sudins.update', 'uses' => 'Panel\PejabatsudinController@update']);
    Route::delete('pejabat-suku-dinas/{id}/delete', ['as' => 'panel.pejabat_sudins.destroy', 'uses' => 'Panel\PejabatsudinController@destroy']);
   
    Route::get('pejabat-daerah', ['as' => 'panel.pejabat_wilayahs.index', 'uses' => 'Panel\PejabatwilayahController@index']);
    Route::get('pejabat-daerah/create', ['as' => 'panel.pejabat_wilayahs.create', 'uses' => 'Panel\PejabatwilayahController@create']);
    Route::get('pejabat-daerah/{id}/edit', ['as' => 'panel.pejabat_wilayahs.edit', 'uses' => 'Panel\PejabatwilayahController@edit']);
    Route::post('pejabat-daerah/store', ['as' => 'panel.pejabat_wilayahs.store', 'uses' => 'Panel\PejabatwilayahController@store']);
    Route::patch('pejabat-daerah/{id}/update', ['as' => 'panel.pejabat_wilayahs.update', 'uses' => 'Panel\PejabatwilayahController@update']);
    Route::delete('pejabat-daerah/{id}/delete', ['as' => 'panel.pejabat_wilayahs.destroy', 'uses' => 'Panel\PejabatwilayahController@destroy']);

    Route::get('anggota-dprd', ['as' => 'panel.anggota_dprds.index', 'uses' => 'Panel\DprdController@index']);
    Route::get('anggota-dprd/create', ['as' => 'panel.anggota_dprds.create', 'uses' => 'Panel\DprdController@create']);
    Route::get('anggota-dprd/{id}/edit', ['as' => 'panel.anggota_dprds.edit', 'uses' => 'Panel\DprdController@edit']);
    Route::post('anggota-dprd/store', ['as' => 'panel.anggota_dprds.store', 'uses' => 'Panel\DprdController@store']);
    Route::patch('anggota-dprd/{id}/update', ['as' => 'panel.anggota_dprds.update', 'uses' => 'Panel\DprdController@update']);
    Route::delete('anggota-dprd/{id}/delete', ['as' => 'panel.anggota_dprds.destroy', 'uses' => 'Panel\DprdController@destroy']);
    
    Route::get('pimpinan-dprd', ['as' => 'panel.pimpinan_dprds.index', 'uses' => 'Panel\DprdController@index_pimpinan']);
    Route::get('pimpinan-dprd/create', ['as' => 'panel.pimpinan_dprds.create', 'uses' => 'Panel\DprdController@create_pimpinan']);
    Route::get('pimpinan-dprd/{id}/edit', ['as' => 'panel.pimpinan_dprds.edit', 'uses' => 'Panel\DprdController@edit_pimpinan']);
    Route::post('pimpinan-dprd/store', ['as' => 'panel.pimpinan_dprds.store', 'uses' => 'Panel\DprdController@store_pimpinan']);
    Route::patch('pimpinan-dprd/{id}/update', ['as' => 'panel.pimpinan_dprds.update', 'uses' => 'Panel\DprdController@update_pimpinan']);
    Route::delete('pimpinan-dprd/{id}/delete', ['as' => 'panel.pimpinan_dprds.destroy', 'uses' => 'Panel\DprdController@destroy_pimpinan']);
    
    Route::get('data-keuangan', ['as' => 'panel.data_keuangan.index', 'uses' => 'Panel\BidangnilaiController@index_keuangan']);
    Route::get('data-keuangan/{id}/edit', ['as' => 'panel.data_keuangan.edit', 'uses' => 'Panel\BidangnilaiController@edit_keuangan']);
    Route::post('data-keuangan/store', ['as' => 'panel.data_keuangan.update', 'uses' => 'Panel\BidangnilaiController@update_keuangan']);
    
    Route::get('data-statistik', ['as' => 'panel.data_statistik.index', 'uses' => 'Panel\BidangnilaiController@index_statistik']);
    Route::get('data-statistik/{id}/edit', ['as' => 'panel.data_statistik.edit', 'uses' => 'Panel\BidangnilaiController@edit_statistik']);
    Route::post('data-statistik/store', ['as' => 'panel.data_statistik.update', 'uses' => 'Panel\BidangnilaiController@update_statistik']);

    Route::get('data-ekonomi', ['as' => 'panel.data_ekonomi.index', 'uses' => 'Panel\BidangnilaiController@index_ekonomi']);
    Route::get('data-ekonomi/{id}/edit', ['as' => 'panel.data_ekonomi.edit', 'uses' => 'Panel\BidangnilaiController@edit_ekonomi']);
    Route::post('data-ekonomi/store', ['as' => 'panel.data_ekonomi.update', 'uses' => 'Panel\BidangnilaiController@update_ekonomi']);

});

Route::get('kirim-email', function() {
    $mail_config = [
        'fromname' => 'Nurul Irhamni Budi',
        'sendermail' => 'nurul.irhamni@redtech.co.id',
        'receipts' => 'redtech.teamx@gmail.com',
        'subject' => "Test Kirim Email Lokal laptop"
    ];
    $mail_data = [
        'logo' => 'ini logo perusahaan'
    ];

    Mail::send('email-test', $mail_data, function ($message) use ($mail_config)
    {
        $message->from($mail_config['sendermail'], $mail_config['fromname']);
        $message->to($mail_config['receipts']);
        $message->subject($mail_config['subject']);
    });

    return 'terkirim';
});


Route::resource('homepagePicts', 'HomepagePictController');
// Route::post('importHomepagePict', 'HomepagePictController@import');
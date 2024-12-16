<?php

use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/', 'Auth\LoginController@showLoginForm');
Auth::routes([
                'register' => false, // Registration Routes...
                'reset' => false, // Password Reset Routes...
                'verify' => false, // Email Verification Routes...
            ]); /*standar untuk login, logout, lupa password, register */

Route::post('/login2', 'Auth\LoginController@login2')->name('login2');

Route::get('/home', 'HomeController@index')->name('home'); /*halaman dashboard */
Route::get('/profile', 'Auth\ProfileController@index')->name('profile');
Route::post('/profile/gantipassword', 'Auth\ProfileController@gantipassword')->name('gantipassword');


Route::get('image-crop', 'ImageController@imageCrop');
Route::post('image-crop', 'ImageController@imageCropPost')->name('uploadimage');



Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('/getruang', 'HomeController@index')->name('home'); /*halaman dashboard */

    Route::post('getruang', ['uses' => 'HomeController@getruang', 'as' => 'getruang']);
    Route::post('getaset', ['uses' => 'HomeController@getaset', 'as' => 'getaset']);


    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_loaddatatable', ['uses' => 'Admin\RolesController@loaddatatable', 'as' => 'roles.loaddatatable']);
    Route::post('roles_hapusdata', ['uses' => 'Admin\RolesController@hapusdata', 'as' => 'roles.hapusdata']);
    Route::post('roles_hapusdipilih', ['uses' => 'Admin\RolesController@hapusdipilih', 'as' => 'roles.hapusdipilih']);

    Route::resource('users', 'Admin\UsersController');
    Route::post('users_loaddatatable', ['uses' => 'Admin\UsersController@loaddatatable', 'as' => 'users.loaddatatable']);
    Route::post('users_sync', ['uses' => 'Admin\UsersController@sync', 'as' => 'users.sync']);
    Route::post('users_hapusdata', ['uses' => 'Admin\UsersController@hapusdata', 'as' => 'users.hapusdata']);
    Route::post('users_hapusdipilih', ['uses' => 'Admin\UsersController@hapusdipilih', 'as' => 'users.hapusdipilih']);

    Route::get('user/roles/{user}', ['uses' => 'Admin\UsersController@rolesuser', 'as' => 'users.roles']);
    Route::post('user/simpanroles', ['uses' => 'Admin\UsersController@simpanrolesuser', 'as' => 'users.simpanroles']);
    Route::get('user/permissions/{user}', ['uses' => 'Admin\UsersController@permissionsuser', 'as' => 'users.permissions']);
    Route::post('user/simpanpermissions', ['uses' => 'Admin\UsersController@simpanpermissionsuser', 'as' => 'users.simpanpermissions']);

    Route::resource('setting', 'Admin\SettingController');


    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {

        //master_api_key
        Route::resource('api_key', 'Admin\master\M_Api_keyController');
        Route::post('api_key_loaddatatable', ['uses' => 'Admin\master\M_Api_keyController@loaddatatable', 'as' => 'api_key.loaddatatable']);
        Route::post('api_key_hapusdata', ['uses' => 'Admin\master\M_Api_keyController@hapusdata', 'as' => 'api_key.hapusdata']);
        Route::post('api_key_hapusdipilih', ['uses' => 'Admin\master\M_Api_keyController@hapusdipilih', 'as' => 'api_key.hapusdipilih']);
        Route::get('api_key/qr/{id}', ['uses' => 'Admin\master\M_Api_keyController@qr', 'as' => 'api_key.qr']);

        //device
        // Route::resource('device', 'Admin\master\MDeviceController');
        // Route::post('device_loaddatatable', ['uses' => 'Admin\master\MDeviceController@loaddatatable', 'as' => 'device.loaddatatable']);
        // Route::post('device_hapusdata', ['uses' => 'Admin\master\MDeviceController@hapusdata', 'as' => 'device.hapusdata']);
        // Route::post('device_hapusdipilih', ['uses' => 'Admin\master\MDeviceController@hapusdipilih', 'as' => 'device.hapusdipilih']);
        // Route::get('device/aktifkan/{id}', ['uses' => 'Admin\master\MDeviceController@aktifkan', 'as' => 'device.aktifkan']);
        // Route::post('device/reset', ['uses' => 'Admin\master\MDeviceController@reset', 'as' => 'device.reset']);


        //status
        Route::resource('status', 'Admin\master\MStatusController');
        Route::post('status_loaddatatable', ['uses' => 'Admin\master\MStatusController@loaddatatable', 'as' => 'status.loaddatatable']);

        //jenis
        Route::resource('jenis', 'Admin\master\MJenisController');
        Route::post('jenis_loaddatatable', ['uses' => 'Admin\master\MJenisController@loaddatatable', 'as' => 'jenis.loaddatatable']);

        //kondisi
        Route::resource('kondisi', 'Admin\master\MKondisiController');
        Route::post('kondisi_loaddatatable', ['uses' => 'Admin\master\MKondisiController@loaddatatable', 'as' => 'kondisi.loaddatatable']);
        Route::post('kondisi_hapusdata', ['uses' => 'Admin\master\MKondisiController@hapusdata', 'as' => 'kondisi.hapusdata']);
        Route::post('kondisi_hapusdipilih', ['uses' => 'Admin\master\MKondisiController@hapusdipilih', 'as' => 'kondisi.hapusdipilih']);

        //divisi
        Route::resource('divisi', 'Admin\master\MDivisiController');
        Route::post('divisi_loaddatatable', ['uses' => 'Admin\master\MDivisiController@loaddatatable', 'as' => 'divisi.loaddatatable']);
        Route::post('divisi_hapusdata', ['uses' => 'Admin\master\MDivisiController@hapusdata', 'as' => 'divisi.hapusdata']);
        Route::post('divisi_hapusdipilih', ['uses' => 'Admin\master\MDivisiController@hapusdipilih', 'as' => 'divisi.hapusdipilih']);

        //satuan
        Route::resource('satuan', 'Admin\master\MSatuanController');
        Route::post('satuan_loaddatatable', ['uses' => 'Admin\master\MSatuanController@loaddatatable', 'as' => 'satuan.loaddatatable']);
        Route::post('satuan_hapusdata', ['uses' => 'Admin\master\MSatuanController@hapusdata', 'as' => 'satuan.hapusdata']);
        Route::post('satuan_hapusdipilih', ['uses' => 'Admin\master\MSatuanController@hapusdipilih', 'as' => 'satuan.hapusdipilih']);

        //jenis_pengadaan
        Route::resource('jenis_pengadaan', 'Admin\master\MJenisPengadaanController');
        Route::post('jenis_pengadaan_loaddatatable', ['uses' => 'Admin\master\MJenisPengadaanController@loaddatatable', 'as' => 'jenis_pengadaan.loaddatatable']);
        Route::post('jenis_pengadaan_hapusdata', ['uses' => 'Admin\master\MJenisPengadaanController@hapusdata', 'as' => 'jenis_pengadaan.hapusdata']);
        Route::post('jenis_pengadaan_hapusdipilih', ['uses' => 'Admin\master\MJenisPengadaanController@hapusdipilih', 'as' => 'jenis_pengadaan.hapusdipilih']);

        //jenis_maintenance
        Route::resource('jenis_maintenance', 'Admin\master\MJenisMaintenanceController');
        Route::post('jenis_maintenance_loaddatatable', ['uses' => 'Admin\master\MJenisMaintenanceController@loaddatatable', 'as' => 'jenis_maintenance.loaddatatable']);
        Route::post('jenis_maintenance_hapusdata', ['uses' => 'Admin\master\MJenisMaintenanceController@hapusdata', 'as' => 'jenis_maintenance.hapusdata']);
        Route::post('jenis_maintenance_hapusdipilih', ['uses' => 'Admin\master\MJenisMaintenanceController@hapusdipilih', 'as' => 'jenis_maintenance.hapusdipilih']);

        //lokasi
        Route::resource('lokasi', 'Admin\master\MLokasiController');
        Route::post('lokasi_loaddatatable', ['uses' => 'Admin\master\MLokasiController@loaddatatable', 'as' => 'lokasi.loaddatatable']);
        Route::post('lokasi_hapusdata', ['uses' => 'Admin\master\MLokasiController@hapusdata', 'as' => 'lokasi.hapusdata']);
        Route::post('lokasi_hapusdipilih', ['uses' => 'Admin\master\MLokasiController@hapusdipilih', 'as' => 'lokasi.hapusdipilih']);

        //ruang
        Route::resource('ruang', 'Admin\master\MRuangController');
        Route::post('ruang_loaddatatable', ['uses' => 'Admin\master\MRuangController@loaddatatable', 'as' => 'ruang.loaddatatable']);
        Route::post('ruang_hapusdata', ['uses' => 'Admin\master\MRuangController@hapusdata', 'as' => 'ruang.hapusdata']);
        Route::post('ruang_hapusdipilih', ['uses' => 'Admin\master\MRuangController@hapusdipilih', 'as' => 'ruang.hapusdipilih']);
        Route::get('ruang/ambildata/{id}', ['uses' => 'Admin\master\MRuangController@ambildata', 'as' => 'ruang.ambildata']);

        //barang
        Route::resource('barang', 'Admin\master\MBarangController');
        Route::post('barang_loaddatatable', ['uses' => 'Admin\master\MBarangController@loaddatatable', 'as' => 'barang.loaddatatable']);
        Route::post('barang_hapusdata', ['uses' => 'Admin\master\MBarangController@hapusdata', 'as' => 'barang.hapusdata']);
        Route::post('barang_hapusdipilih', ['uses' => 'Admin\master\MBarangController@hapusdipilih', 'as' => 'barang.hapusdipilih']);


        //barang_sub
        Route::resource('barang_sub', 'Admin\master\MBarangSubController');
        Route::post('barang_sub_loaddatatable', ['uses' => 'Admin\master\MBarangSubController@loaddatatable', 'as' => 'barang_sub.loaddatatable']);
        Route::post('barang_sub_hapusdata', ['uses' => 'Admin\master\MBarangSubController@hapusdata', 'as' => 'barang_sub.hapusdata']);
        Route::post('barang_sub_hapusdipilih', ['uses' => 'Admin\master\MBarangSubController@hapusdipilih', 'as' => 'barang_sub.hapusdipilih']);
        Route::get('barang_sub/ambildata/{id}', ['uses' => 'Admin\master\MBarangSubController@ambildata', 'as' => 'barang_sub.ambildata']);



        //Aset
        Route::resource('aset', 'Admin\master\MAsetController');
        Route::post('aset_loaddatatable', ['uses' => 'Admin\master\MAsetController@loaddatatable', 'as' => 'aset.loaddatatable']);
        Route::post('aset_hapusdata', ['uses' => 'Admin\master\MAsetController@hapusdata', 'as' => 'aset.hapusdata']);
        Route::post('aset_hapusdipilih', ['uses' => 'Admin\master\MAsetController@hapusdipilih', 'as' => 'aset.hapusdipilih']);
        Route::get('aset/ambildatatanah/{id}', ['uses' => 'Admin\master\MAsetController@ambildatatanah', 'as' => 'aset.ambildatatanah']);
        Route::post('aset/simpandatatanah', ['uses' => 'Admin\master\MAsetController@simpandatatanah', 'as' => 'aset.simpandatatanah']);
        Route::get('aset/ambildatakendaraan/{id}', ['uses' => 'Admin\master\MAsetController@ambildatakendaraan', 'as' => 'aset.ambildatakendaraan']);
        Route::post('aset/simpandatakendaraan', ['uses' => 'Admin\master\MAsetController@simpandatakendaraan', 'as' => 'aset.simpandatakendaraan']);
        Route::post('aset/maintenance', ['uses' => 'Admin\master\MAsetController@maintenance', 'as' => 'aset.maintenance']);
        Route::post('aset/simpanmaintenance', ['uses' => 'Admin\master\MAsetController@simpanmaintenance', 'as' => 'aset.simpanmaintenance']);
        Route::post('aset/hapusmaintenance', ['uses' => 'Admin\master\MAsetController@hapusmaintenance', 'as' => 'aset.hapusmaintenance']);

        Route::get('aset/ambildatabangunan/{id}', ['uses' => 'Admin\master\MAsetController@ambildatabangunan', 'as' => 'aset.ambildatabangunan']);
        Route::post('aset/simpandatabangunan', ['uses' => 'Admin\master\MAsetController@simpandatabangunan', 'as' => 'aset.simpandatabangunan']);
        Route::post('aset/exportexcel', ['uses' => 'Admin\master\MAsetController@exportexcel', 'as' => 'aset.exportexcel']);

    });


    Route::group(['prefix' => 'aset', 'as' => 'aset.'], function () {
        //mutasi
        Route::resource('mutasi', 'Admin\Aset\MutasiController');
        Route::post('mutasi_loaddatatable', ['uses' => 'Admin\Aset\MutasiController@loaddatatable', 'as' => 'mutasi.loaddatatable']);

    });


    Route::group(['prefix' => 'transaksi', 'as' => 'transaksi.'], function () {
        //approval pengajuan toko
        Route::resource('cetaklabel', 'Admin\Transaksi\LabelController');
        Route::post('cetaklabel-loaddatatable', ['uses' => 'Admin\Transaksi\LabelController@loaddatatable', 'as' => 'cetaklabel.loaddatatable']);
        Route::post('cetaklabel-getruang', ['uses' => 'Admin\Transaksi\LabelController@getruang', 'as' => 'cetaklabel.getruang']);
        Route::post('cetaklabel-loaddatatable_tambah', ['uses' => 'Admin\Transaksi\LabelController@loaddatatable_tambah', 'as' => 'cetaklabel.loaddatatable_tambah']);
        Route::post('cetaklabel-storedipilih', ['uses' => 'Admin\Transaksi\LabelController@storedipilih', 'as' => 'cetaklabel.storedipilih']);
        Route::post('cetaklabel-storelokasi', ['uses' => 'Admin\Transaksi\LabelController@storelokasi', 'as' => 'cetaklabel.storelokasi']);
        Route::post('cetaklabel-storeruang', ['uses' => 'Admin\Transaksi\LabelController@storeruang', 'as' => 'cetaklabel.storeruang']);
        Route::post('cetaklabel-hapusdata', ['uses' => 'Admin\Transaksi\LabelController@hapusdata', 'as' => 'cetaklabel.hapusdata']);
        Route::post('cetaklabel-hapusdipilih', ['uses' => 'Admin\Transaksi\LabelController@hapusdipilih', 'as' => 'cetaklabel.hapusdipilih']);
        Route::post('cetaklabel-kosongkan', ['uses' => 'Admin\Transaksi\LabelController@kosongkan', 'as' => 'cetaklabel.kosongkan']);
        Route::get('cetaklabel/cetak/{id}', ['uses' => 'Admin\Transaksi\LabelController@cetak', 'as' => 'cetaklabel.cetak']);

    });

    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        //approval pengajuan toko
        Route::resource('opname', 'Admin\Report\OpnameController');
        Route::post('opname-loaddatatable', ['uses' => 'Admin\Report\OpnameController@loaddatatable', 'as' => 'opname.loaddatatable']);
        Route::post('opname-exportexcel', ['uses' => 'Admin\Report\OpnameController@exportexcel', 'as' => 'opname.exportexcel']);
        Route::get('opname/image/{uniq_id}', ['uses' => 'Admin\Report\OpnameController@image', 'as' => 'opname.image']);

        Route::resource('penyusutan', 'Admin\Report\PenyusutanController');
        Route::post('penyusutan-loaddatatable', ['uses' => 'Admin\Report\PenyusutanController@loaddatatable', 'as' => 'penyusutan.loaddatatable']);
        Route::post('penyusutan-detail', ['uses' => 'Admin\Report\PenyusutanController@detail', 'as' => 'penyusutan.detail']);
        Route::post('penyusutan-exportexcel', ['uses' => 'Admin\Report\PenyusutanController@exportexcel', 'as' => 'penyusutan.exportexcel']);



    });

});





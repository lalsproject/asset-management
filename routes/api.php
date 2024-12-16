<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});

*/


Route::group(['middleware' => ['api', 'key'], 'prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {

    Route::post('test', ['uses' => 'DownloadController@test', 'as' => 'test']);
    Route::group(['prefix' => 'download', 'as' => 'download.'], function () {

        Route::post('android', ['uses' => 'DownloadController@android', 'as' => 'android']);
        Route::post('cetak', ['uses' => 'DownloadController@cetak', 'as' => 'cetak']);
        Route::post('imgopname', ['uses' => 'DownloadController@imgopname', 'as' => 'imgopname']);
    });
    Route::group(['prefix' => 'upload', 'as' => 'upload.'], function () {

        Route::post('transfer', ['uses' => 'UploadController@transfer', 'as' => 'transfer']);
    });
});


// Route::group(['middleware' => ['api', 'public'], 'prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {


// 	/* hanya untuk aktivasi device via APP */
//     Route::group(['middleware' => ['key']], function () {

//         Route::post('device/aktivasi', ['uses' => 'AktivasiController@aktivasi', 'as' => 'device.aktivasi']);

//         Route::group(['middleware' => ['device']], function () {
        
//             Route::post('device/reset', ['uses' => 'AktivasiController@reset', 'as' => 'device.reset']);

//             Route::post('login', ['uses' => 'RegisterController@login', 'as' => 'login']);

//             Route::group(['middleware' => ['user']], function () {

//                 Route::post('master', ['uses' => 'MasterController@master', 'as' => 'master']);











//             });
//         });
//     });
// });

<?php

namespace App\Http\Controllers\Auth;

use App\Fungsi\Guzzle;
use App\Fungsi\Project;


use App\Helpers;
use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated()
    {
        // dd("Sukses");
        $x = Project::cekpenyusutan();

        if( !Storage::disk('local')->exists("backup/"."backup_".Date("Ymd").".zip")) {
            // dd("x");
            $x = Project::backupdb();
        };

        // Do your oauth script here
        // Put it in the session
    }

    // public function login2(Request $request)
    // { 

    //     // dd($request->all());
    //     // https://kodingin.com/2-cara-membuat-login-pada-laravel/
    //     $email = $request->input('email');
    //     $password   = $request->input('password');;


    //     $queryParams = Array(); //$request->only('user_fullname','user_email', 'user_phone');
    //     $queryParams["email"] = $email;
    //     $queryParams["password"] = $password;

    //     $r = Guzzle::makeRequest_hriss("POST", "/v1/user/login", $queryParams );

    //     $hasilapi = json_decode($r);

    //     if ($hasilapi->success) {
    //         $cek = User::where('id', $hasilapi->data->user->id)->first();
    //         if (is_null($cek)) {
    //             $cek = User::Create([
    //                 "id" => $hasilapi->data->user->id,
    //                 "name" => $hasilapi->data->user->name,
    //                 "email" => $hasilapi->data->user->email,
    //                 "password" => Helpers::rndstr(15),
    //             ]);

    //         } else {
    //             $cek->name =  $hasilapi->data->user->name;
    //             $cek->email =  $hasilapi->data->user->email;
    //             $cek->save();
    //         }

    //         if(Auth::loginUsingId($hasilapi->data->user->id)){
    //             return Helpers::responseJson(true, "", "Login berhasil" );
    //         } else {
    //             return Helpers::responseJson(false, "", "Login Gagal. Kesalahan server" );
    //         }

    //     } else {
    //         return Helpers::responseJson(false, "", "Login Gagal. Data tidak sesuai" );

    //     }


    //     // $User = User::Where('email',$email)->first();
    //     // if (!empty($User)) {
    //     //     //Jika ditemukan. Ambil header
    //     //     $header = $User->userheader;
    //     // }

    //     // // dd($header);
    //     // $pwd = strtoupper($pwd);

    //     // if (empty($header)) {
    //     //     //dari variable user tidak ada. Cb login dengan nama
    //     //     $header = UserHeader::where('nama', strtoupper($email))->first();
    //     // }

    //     // if (empty($header)) {
    //     //     return Helpers::responseJson(false, "", "Login Gagal. Data tidak ditemukan" );
    //     // }

    //     // $e_pwd = Crypt::Enkripsi("D",$header->password);
    //     // $e_pwd = strtoupper($e_pwd);

    //     // if ($pwd == $e_pwd) {

    //     //     $User = User::Where('name',$header->nama)->first();
    //     //     if (empty($User)) {
    //     //         return Helpers::responseJson(false, "", "Login Gagal. Data belum mapping" );
    //     //     }


    //     //     if(Auth::loginUsingId($User->id)){
    //     //         return Helpers::responseJson(true, "", "Login berhasil" );
    //     //     } else {
    //     //         return Helpers::responseJson(false, "", "Login Gagal. Kesalahan server" );
    //     //     }
    //     // } else {
    //     //     return Helpers::responseJson(false, "", "Login Gagal. Data tidak sesuai" );
    //     // }


    // }



}

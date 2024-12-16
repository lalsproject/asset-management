<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Model\M_Aset;
use App\Model\M_Status;
use App\Model\M_Kondisi;
use App\Model\M_Jenis;
use App\Model\M_Divisi;
use App\Model\M_Barang;
use App\Model\M_BarangSub;
use App\Model\M_Satuan;
use App\Model\M_Ruang;
use App\Model\M_Lokasi;
use App\Model\M_JenisPengadaan;
use App\Model\M_JenisMaintenance;
use App\Model\M_Tanah;
use App\Model\M_Kendaraan;

use App\Helpers;

use App\Model\T_Aset;
use App\Model\T_Maintenance;

use App\Fungsi\Guzzle;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

use DB;
use Hash;


class RegisterController extends Controller
{

    public function login(Request $request) 
    {
        $email = $request->input('email');
        $password   = $request->input('password');;

        $queryParams = Array(); //$request->only('user_fullname','user_email', 'user_phone');
        $queryParams["email"] = $email;
        $queryParams["password"] = $password;

        $r = Guzzle::makeRequest_hriss("POST", "/v1/user/login", $queryParams );

        $hasilapi = json_decode($r);

        if ($hasilapi->success) {
            $user = User::where('id', $hasilapi->data->user->id)->first();
            if (is_null($user)) {
                $user = User::Create([
                    "id" => $hasilapi->data->user->id,
                    "name" => $hasilapi->data->user->name,
                    "email" => $hasilapi->data->user->email,
                    "password" => Helpers::rndstr(15),
                ]);

            } else {
                $user->name =  $hasilapi->data->user->name;
                $user->email =  $hasilapi->data->user->email;
                $user->save();
            }

            $token = Helpers::generateToken();
            $user->remember_token = $token;
            $user->save();

            $lokasi = M_Lokasi::select('id','kode','deskripsi')->get();
            $ruang = M_Ruang::select('id','lokasi_id','kode','deskripsi')->get();
            $barang = M_Barang::select('id','kode','deskripsi')->get();
            $barang_sub = M_BarangSub::select('id','barang_id','kode','deskripsi')->get();
            $status = M_Status::select('id','deskripsi','warna')->get();
            $kondisi = M_Kondisi::select('id','deskripsi','warna')->get();
            $jenis = M_Jenis::select('id','deskripsi','warna')->get();
            $divisi = M_Divisi::select('id','deskripsi')->get();
            $satuan = M_Satuan::select('id','deskripsi')->get();
            $jenis_maintenance = M_JenisMaintenance::select('id','deskripsi')->get();
            $jenis_pengadaan = M_JenisPengadaan::select('id','kode','deskripsi')->get();
            $aset = M_Aset::all();

            $rv = array(
                'token'     => $token,
                'name'      => $user->name,
                'email'     => $user->email,
                'lokasi'    => $lokasi,
                'ruang'    => $ruang,
                'barang'    => $barang,
                'barang_sub'    => $barang_sub,
                'status'    => $status,
                'kondisi'    => $kondisi,
                'jenis'    => $jenis,
                'divisi'    => $divisi,
                'satuan'    => $satuan,
                'jenis_maintenance'    => $jenis_maintenance,
                'jenis_pengadaan'    => $jenis_pengadaan,
            );        


            return Helpers::Response($request, true, $rv, "OK", 200);

        } else {
            return Helpers::Response($request, false, "", "Data tidak ditemukan atau password salah", 200);
        }


    }



}

<?php

namespace App\Http\Controllers\Admin;

use App\Helpers;
use App\Model\Data;

use App\Guzzle;
use App\M_Provinsi;
use App\M_Dati2;
use App\M_Kecamatan;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;


use Illuminate\Support\Facades\Log;


class SettingController extends Controller
{

    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('general_setting'))
        {
            return redirect()->route('home');
        }


        // $data = Data::Where('pkey','notifikasi_pengajuan_aset')->first();
        // if (is_null($data)) {
        //     $notifikasi_pengajuan_aset = 'nej.finss@gmail.com';
        // } else {
        //     $notifikasi_pengajuan_aset = $data->nstring;
        // }




        // $notifikasi = Array();
        // $notifikasi['pengajuan_aset'] = $notifikasi_pengajuan_aset;


        return view('admin.setting.index', compact('notifikasi'));
    }

    public function store(Request $request)
    { 
        // dd($request->all());

        try {

            $all = $request->all();

            foreach ($all as $key => $value) {
                if ($key != '_token') {
                    $hapus = Data::Where('pkey', $key)->delete();
                    $create = Data::create(['pkey'=> $key , 'nstring' => $value,  'updated' =>  Helpers::generateUpdated() ]);
                }

            }

            return Helpers::responseJson(true, "" , "Simpan data notifikasi berhasil");

        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Simpan data notifikasi gagal");
        }

    }

}

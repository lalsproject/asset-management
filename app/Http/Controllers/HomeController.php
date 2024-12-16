<?php

namespace App\Http\Controllers;

use App\Helpers;

use App\Model\M_Aset;
use App\Model\M_Lokasi;
use App\Model\M_Ruang;
use App\Fungsi\Project;

use DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // Nilai Penyusutan tahun ini
        $q = DB::select(DB::raw("Select coalesce(sum(nilai),0) as n from t_penyusutan where date_format(periode,'%Y')='".Date('Y')."'"));
        $susut = $q[0]->n;  //Jumlah penyusutan tahun ini

        $q = DB::select(DB::raw("Select coalesce(sum(nilai),0) as n from t_penyusutan where periode >='".Date('Y-m-d')."'"));
        $sisa = $q[0]->n;  //Jumlah penyusutan yang akan datang

        $q = DB::select(DB::raw("select count(aset_id) as n from
                            (
                                select aset_id, max(periode) as p from t_penyusutan group by aset_id
                            ) as x where date_format(p,'%Y') = '".Date('Y')."'"));
        $selesai_susut_ty = $q[0]->n; // Barang selesai susut tahun ini

        $q = DB::select(DB::raw("select count(aset_id) as n from
                            (
                                select aset_id, max(periode) as p from t_penyusutan group by aset_id
                            ) as x where p <= '".Date('Y-m-')."02'"));
        $selesai_susut_tm = $q[0]->n;

        
      
        return view('home', compact('susut', 'sisa', 'selesai_susut_ty','selesai_susut_tm'));

    }

    public function getruang(Request $request) {

        $lokasi_id = $request->input("lokasi_id");

        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function getaset(Request $request) {

        // dd($request->all());

        $kode = $request->input("kode");

        $aset = M_Aset::where('kode', $kode)->first();

        if(empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }


        $aset->lokasi_id = $aset->ruang->lokasi->id;

        $aset->list_ruang = $aset->ruang->lokasi->has_ruang;

        $result = Project::dataaset($kode); 
        $result->aset = $aset;

        if( $result->success) {
            return Helpers::responseJson(true, $result, "OK" );

        } else {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }
        
        $result = $x[0];
        
        $table = $this::datatable($request);
    }
}

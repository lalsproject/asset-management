<?php

namespace App\Http\Controllers\Admin\Report;

use App\Model\T_Penyusutan;
use App\Model\M_Kondisi;
use App\Model\M_Barang;
use App\Model\M_BarangSub;
use App\Model\M_Ruang;

use App\Helpers;
use App\Fungsi\Project;

use App\Exports\PenyusutanExport;
use Maatwebsite\Excel\Facades\Excel;


use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;
use Form;
// use Carbon\Carbon;

class PenyusutanController extends Controller
{

    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('report_penyusutan'))
        {
            return redirect()->route('home');
        }

        $x = Project::cekpenyusutan();

        $tahun = Array();
        $s = Date('Y');

        for ($i=-10; $i <11 ; $i++) { 
            $tahun[ $s + $i] = $s + $i;
        }

        $table = $this::datatable(null);

        return view('admin.report.penyusutan.index', compact('table', 'tahun'));
    }

   
    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data, $periode)
    {
        $form = "<button class='btn btn-xs btn-primary' onclick=\"Detail('".$data->id."','".$periode."')\">".number_format($data->n)."</button> ";
        return $form;
    }

    function GenerateDatatable($request) {

        // dd($request->all());

        if (!empty($request)) {
            $periode = $request->input('filter_periode');
            $kriteria = $request->input('filter_kriteria');
        }

        if (empty($periode)) {
            $periode=Array();
            $periode[] = Date('Y');
        }


        $sql = "select susut.*, aset.* from
                (
                    Select aset_id, sum(nilai) as n 
                        from t_penyusutan where date_format(periode,'%Y') in ('0'";
        foreach ($periode as $key => $value) {
            $sql .= ",'".$value."'";
        }                        
                        
        $sql .= ")
                        group by aset_id
                ) as susut left join
                (
                    select m_aset.id, m_aset.kode, pengadaan, harga, jumlah_susut, concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset, 
                            m_aset.seri, m_lokasi.deskripsi as lokasi, m_ruang.deskripsi as ruang
                                from m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id
                                            left join m_ruang on m_aset.ruang_id = m_ruang.id
                                            left join m_lokasi on m_ruang.lokasi_id = m_lokasi.id
                ) as aset on aset.id = susut.aset_id ";


        if (!empty($kriteria)) {
            $sql .= " where kode like '%".$kriteria."%'
                        or namaaset like '%".$kriteria."%'
                        or seri like '%".$kriteria."%'
                        or lokasi like '%".$kriteria."%'
                        or ruang like '%".$kriteria."%'";
        }

        return $sql;

    }

    public function datatable($request)
    {

        if (!empty($request)) {
            $periode = $request->input('filter_periode');
            $take = $request->input('take');
            $halaman = $request->input('halaman');
        }

        if (empty($periode)) {
            $periode=Array();
            $periode[] = Date('Y');
        }

        $f_periode = "0";
        foreach ($periode as $key => $value) {
            $f_periode .=",".$value;
        }

        $f_periode = substr($f_periode,2);


        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;

        $sql = $this::GenerateDatatable($request);

        $data = DB::SELECT(DB::RAW($sql));
        $count = count($data);

        $result['count'] = $count;
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $final = array_slice($data, $skip, $take); 

        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                      <th>Kode</th>
                      <th>Nama Aset</th>
                      <th>Seri</th>
                      <th>Lokasi</th>
                      <th>Ruang</th>
                      <th>Harga</th>
                      <th>Jumlah Susut</th>
                      <th>Nilai Penyusutan</th>
                      <th>Periode</th>
                    </tr>
                  </thead>
                  <tbody>";

        foreach ($final as $detail) {

            $table .= "<tr>
                            <td align='left'>".$detail->kode."</td>
                            <td align='left'>".$detail->namaaset."</td>
                            <td align='left'>".$detail->seri."</td>
                            <td align='left'>".$detail->lokasi."</td>
                            <td align='left'>".$detail->ruang."</td>
                            <td align='right'>".number_format($detail->harga)."</td>
                            <td align='right'>".number_format($detail->jumlah_susut)."</td>
                            <td align='right'>".$this::formAction($detail, $f_periode)."</td>
                            <td align='right'>".$f_periode."</td>
                        </tr>
                        ";

        }

        $table .= "</table>";
        $result = array(
            'table' => $table,
            'pagination' => $pagination,
        );

        return $result;
    }

    public function detail(Request $request) {
        $aset_id = $request->input('aset_id');
        $periode = $request->input('periode');
        $semua = $request->input('semua');
        
        $sql = "SELECT * from t_penyusutan where aset_id=".$aset_id;

        if (empty($semua)) {
            $sql .=" and date_format(periode,'%Y') in (".$periode.") ";
        }

        $final = DB::select(DB::raw($sql));
        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                      <th>Periode</th>
                      <th>Nilai</th>
                    </tr>
                  </thead>
                  <tbody>";

        $barisbeda = 1;
        foreach ($final as $detail) {
            $d_periode = date_format(date_create($detail->periode),'Y');            
            if (str_contains($periode,$d_periode) ) {
                if ($barisbeda == 1) {
                    $barisbeda = 0;
                    $s = " style='background: #cee8ed'";
                } else {
                    $barisbeda = 1;
                    $s = " style='background: #d1fffd'";
                }
            } else {
                $s = "";
            }

            $table .= "<tr ".$s.">
                            <td align='left'>".date_format(date_create($detail->periode),'Y-m')."</td>
                            <td align='right'>".number_format($detail->nilai)."</td>
                        </tr>
                        ";

        }
        $table .= "</table>";

        // $a = Project::dataaset($aset_id);

        $result = Array();
        $result['table'] = $table;
        $result['aset'] = Project::dataaset($aset_id);

        return Helpers::responseJson(true, $result, "OK" );
    }

    public function exportexcel(Request $request) {
        if (!empty($request)) {
            $periode = $request->input('filter_periode');
        }

        if (empty($periode)) {
            $periode=Array();
            $periode[] = Date('Y');
        }

        $f_periode = "0";
        foreach ($periode as $key => $value) {
            $f_periode .=",".$value;
        }

        $f_periode = substr($f_periode,2);

        $sql = $this::GenerateDatatable($request);
        $penyusutan = DB::SELECT(DB::RAW($sql));
        $data = [ 
                'penyusutan' => $penyusutan,
                'periode' => $f_periode
                    ];

        return Excel::download(new PenyusutanExport($data), 'PenyusutanAset.xlsx');
    }
}

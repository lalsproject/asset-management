<?php

namespace App\Http\Controllers\Admin\Transaksi;

use App\Model\T_Label;
use App\Model\M_Lokasi;
use App\Model\M_Ruang;
use App\Model\M_Aset;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class LabelController extends Controller
{

    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return redirect()->route('home');
        }

        $lokasi = M_Lokasi::pluck("deskripsi", 'id')->toArray();
        $ruang = M_Ruang::where('lokasi_id', array_key_first($lokasi))->pluck("deskripsi", 'id')->toArray();
        $table_tambah = $this::datatable_tambah(array_key_first($ruang),1);
        $table = $this::datatable(null);


        return view('admin.transaksi.label.index', compact('table', 'lokasi','ruang', 'table_tambah'));
    }

    public function store(Request $request)
    { 
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        $label = T_Label::create(['aset_id' => $id,
                                    'updated' => Helpers::generateUpdated()
                                    ]);
        $table = $this::datatable($request, $label);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function storedipilih(Request $request)
    { 
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $ids = $request->input('ids');
        foreach ($ids as $key => $value) {
            $label = T_Label::create(['aset_id' => $value,
                    'updated' => Helpers::generateUpdated()
                    ]);
        }
        $table = $this::datatable($request, $label);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function storelokasi(Request $request)
    { 
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $lokasi_id = $request->input('lokasi_id');
        $ruang = M_Ruang::where('lokasi_id', $lokasi_id)->pluck('id','id')->toArray();

        $aset = M_Aset::wherein('ruang_id', $ruang)->pluck('id')->toArray();

        foreach ($aset as $key => $value) {
            $label = T_Label::create(['aset_id' => $value,
                    'updated' => Helpers::generateUpdated()
                    ]);
        }
        $table = $this::datatable($request, $label);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function storeruang(Request $request)
    { 
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $ruang_id = $request->input('ruang_id');
        $aset = M_Aset::where('ruang_id', $ruang_id)->pluck('id')->toArray();

        foreach ($aset as $key => $value) {
            $label = T_Label::create(['aset_id' => $value,
                    'updated' => Helpers::generateUpdated()
                    ]);
        }
        $table = $this::datatable($request, $label);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request)
    {

        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        // dd($request->all());

        $id = $request->input('id');

        try {

            $label = T_Label::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($label)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $label->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "Hapus data berhasil" );
    }

    public function hapusdipilih(Request $request)
    {
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = T_Label::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "Proses hapus pilihan dijalankan" );
    }

    public function kosongkan(Request $request)
    {
        if(!Auth()->user()->hasAnyPermission('cetak_label'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $x = T_Label::where('id','>',0)->delete();

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "Proses hapus pilihan dijalankan" );
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {
        $form = "<button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->label_id.")\">Hapus</button> ";
                
        return $form;
    }

    public function datatable($request, $dataproses = null){

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('filter_kriteria');
            $halaman = $request->input('halaman');
        }

        $result = array();

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;

        $sql = "select label_id, m_aset.id, m_aset.kode, concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset, m_aset.seri, coalesce(pengguna,'') as user
                	from (m_aset inner join 
                    (select id as label_id, aset_id from t_label where status = 0 ) as lbl
                    on m_aset.id = lbl.aset_id)
                    left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id ";
        
        if(!empty($kriteria)){
                    
            $sql .= " where concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) like '%".$kriteria."%'
                    or m_aset.kode like '%".$kriteria."%'";
        }

        $result['sql'] = $sql;


        $data = DB::SELECT(DB::RAW($sql));
        $count = count($data);

        $result['count'] = $count;

        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $label = array_slice($data, $skip, $take); 


        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Kode</th>
                      <th>Nama Aset</th>
                      <th>Seri</th>
                      <th>User</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

                  foreach ($label as $detail) {

                    $table .= "<tr>
                              <td align='center'>".Helpers::formCheckbox($detail->label_id)."</td>
                          <td align='left'>".$detail->kode."</td>
                          <td align='left'>".$detail->namaaset."</td>
                          <td align='left'>".$detail->seri."</td>
                          <td align='left'>".$detail->user."</td>
                              <td align='center'>".$this::formAction($detail)."</td>
                            </tr>
                        ";
                // }
            }
    
            $table .= "</table>";
        $result['table'] = $table;
        $result['pagination'] = $pagination;

        return $result;
    }





    public function getruang(Request $request) {

        $lokasi_id = $request->input('lokasi_id');

        $ruang = M_Ruang::where('lokasi_id', $lokasi_id)->pluck('deskripsi', 'id')->toarray();

        $result = Array();
        $result['ruang'] = $ruang;
        $result['table'] = $this::datatable_tambah(array_key_first($ruang), 1);        
        return Helpers::responseJson(true, $result, "OK" );
    }

    public function loaddatatable_tambah(Request $request) {

        $ruang_id = $request->input('ruang_id');
        $halaman = $request->input('halaman');

        $table = $this::datatable_tambah($ruang_id, $halaman);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction_tambah($data)
    {
        $form = "<button class='btn btn-xs btn-success' onclick=\"TambahDataCetak(".$data->id.")\">Tambah</button> ";
                
        return $form;
    }

    public function datatable_tambah($ruang_id, $halaman)
    {

        $take = 30;
        $skip = ($halaman - 1) * $take;

        $sql = "select m_aset.id, m_aset.kode, concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset, m_aset.seri, coalesce(pengguna,'') as user
                	from m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id
                    where m_aset.ruang_id = ".$ruang_id;

        $data = DB::SELECT(DB::RAW($sql));
        $count = count($data);

        $pagination = Helpers::Pagination("LoadDataTambah", $count, $take, $halaman);
        $aset = array_slice($data, $skip, $take); 


        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaDataTambah\" onclick=\"PilihSemuaDataTambah()\">
                            </th>
                      <th>Kode</th>
                      <th>Deskripsi</th>
                      <th>Seri</th>
                      <th>User</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";


        foreach ($aset as $detail) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox2($detail->id)."</td>
                      <td align='left'>".$detail->kode."</td>
                      <td align='left'>".$detail->namaaset."</td>
                      <td align='left'>".$detail->seri."</td>
                      <td align='left'>".$detail->user."</td>
                          <td align='center'>".$this::formAction_tambah($detail)."</td>
                        </tr>
                    ";
            // }
        }

        $table .= "</table>";
        $result = array(
            'table' => $table,
            'pagination' => $pagination,
        );        

        return $result;
    }

    public function cetak($id) {
        return view('admin.transaksi.label.cetak');
    }

}

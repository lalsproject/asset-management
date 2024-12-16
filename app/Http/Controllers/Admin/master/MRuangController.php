<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_Lokasi;
use App\Model\M_Ruang;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MRuangController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_ruang'))
        {
            return redirect()->route('admin.');
        }

        $lokasi = M_Lokasi::pluck('deskripsi', 'id')->toArray();

        $table = $this::datatable(null);

        return view('admin.master.ruang.index', compact('table','lokasi'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_ruang'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $ruang = M_Ruang::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $ruang = M_Ruang::findOrFail($id);
                $ruang->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $ruang->updated =  Helpers::generateUpdated();
            $ruang->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $ruang);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_ruang')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');

        try {
            $ruang = M_Ruang::findOrFail($id);

            if (empty($ruang)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $ruang->delete();

        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Gagal Menghapus data" );
        }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('master_ruang')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        try {
            if ($request->input('ids')) {
                $entries = M_Ruang::whereIn('id', $request->input('ids'))->get();

                foreach ($entries as $entry) {
                    $entry->delete();
                }
            }
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Gagal Menghapus data" );
        }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",".$data->lokasi_id.",'".$data->kode."','".$data->deskripsi."')\">Edit</button> 
                <button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        return $form;
    }

    public function datatable($request, $dataproses = null)
    {

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('filter_kriteria');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $ruang = M_Ruang::where('id','!=', '0');

        if(!empty($kriteria)){
            $ruang = $ruang->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($ruang->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $ruang = $ruang->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Kode</th>
                      <th>Deskripsi</th>
                      <th>Lokasi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->kode."</td>
                      <td align='left'>".$dataproses->deskripsi."</td>
                      <td align='left'>".$dataproses->lokasi->deskripsi."</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($ruang as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$detail->kode."</td>
                          <td align='left'>".$detail->deskripsi."</td>
                          <td align='left'>".$detail->lokasi->deskripsi."</td>
                          <td align='center'>".$this::formAction($detail)."</td>
                        </tr>
                    ";
            }
        }

        $table .= "</table>";
        $result = array(
            'table' => $table,
            'pagination' => $pagination,
        );        

        return $result;
    }

    public function ambildata($id)
    {
        try {

            $ruangs = M_Ruang::where('lokasi_id', $id)
                                ->get();
            $ruang = Array();
            foreach ($ruangs as $value) {
                $ruang[$value->id] = $value->kode.' - '.$value->deskripsi;
            }

            return Helpers::responseJson(true, $ruang, "OK" );
         } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Gagal mengambil data" );
        }
    }


}

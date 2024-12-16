<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_JenisMaintenance;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MJenisMaintenanceController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_jenis_maintenance'))
        {
            return redirect()->route('admin.');
        }
        $table = $this::datatable(null);

        return view('admin.master.jenis_maintenance.index', compact('table'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_jenis_maintenance'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $jenis_maintenance = M_JenisMaintenance::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $jenis_maintenance = M_JenisMaintenance::findOrFail($id);
                $jenis_maintenance->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $jenis_maintenance->updated =  Helpers::generateUpdated();
            $jenis_maintenance->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $jenis_maintenance);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_jenis_maintenance')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        DB::beginTransaction();
        try {

            $jenis_maintenance = M_JenisMaintenance::findOrFail($id);
            if (empty($jenis_maintenance)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $jenis_maintenance->delete();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, "", "Data gagal dihapus. Kemungkinan masih digunakan pada data lain." );
        }

        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('master_jenis_maintenance')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        DB::beginTransaction();

        try {
            if ($request->input('ids')) {
                $entries = M_JenisMaintenance::whereIn('id', $request->input('ids'))->get();

                foreach ($entries as $entry) {
                    $entry->delete();
                }
            }
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();

            return Helpers::responseJson(false, "", "Data gagal dihapus. Kemungkinan masih digunakan pada data lain." );
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

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",'".$data->deskripsi."')\">Edit</button> 
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
        $jenis_maintenance = M_JenisMaintenance::where('id','!=', '0');

        if(!empty($kriteria)){
            $jenis_maintenance = $jenis_maintenance->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($jenis_maintenance->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $jenis_maintenance = $jenis_maintenance->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Deskripsi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->deskripsi."</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($jenis_maintenance as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$detail->deskripsi."</td>
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
}

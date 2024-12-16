<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_Barang;
use App\Model\M_BarangSub;
use App\Model\M_Satuan;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MBarangSubController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_barang_sub'))
        {
            return redirect()->route('admin.');
        }

        $barangs = M_Barang::get();
        $barang = Array();
        foreach ($barangs as $value) {
            $barang[$value->id] = $value->kode.' - '.$value->deskripsi;
        }

        $table = $this::datatable(null);
        $satuan = M_Satuan::pluck('deskripsi', 'id')->toArray();

        return view('admin.master.barang_sub.index', compact('table','barang','satuan'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_barang_sub'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $barangsub = M_BarangSub::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $barangsub = M_BarangSub::findOrFail($id);
                $barangsub->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $barangsub->updated =  Helpers::generateUpdated();
            $barangsub->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $barangsub);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_barang_sub')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        DB::beginTransaction();

        try {
            $barangsub = M_BarangSub::findOrFail($id);

            if (empty($barangsub)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $barangsub->delete();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, "", "Gagal Menghapus data" );
        }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('master_barang_sub')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        DB::beginTransaction();

        try {
            if ($request->input('ids')) {
                $entries = M_BarangSub::whereIn('id', $request->input('ids'))->get();

                foreach ($entries as $entry) {
                    $entry->delete();
                }
            }
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
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

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",".$data->barang_id.",".$data->satuan_id.",'".$data->kode."','".$data->deskripsi."')\">Edit</button> 
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
        $barangsub = M_BarangSub::where('id','!=', '0');

        if(!empty($kriteria)){
            $barangsub = $barangsub->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($barangsub->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $barangsub = $barangsub->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Kode</th>
                      <th>Deskripsi</th>
                      <th>Barang</th>
                      <th>Satuan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->kode."</td>
                      <td align='left'>".$dataproses->deskripsi."</td>
                      <td align='left'>".$dataproses->barang->deskripsi."</td>
                      <td align='left'>".$dataproses->satuan->deskripsi."</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($barangsub as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$detail->kode."</td>
                          <td align='left'>".$detail->deskripsi."</td>
                          <td align='left'>".$detail->barang->deskripsi."</td>
                          <td align='left'>".$detail->satuan->deskripsi."</td>
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

            $barang_subs = M_BarangSub::where('barang_id', $id)
                                ->get();
            $barang_sub = Array();
            foreach ($barang_subs as $value) {
                $barang_sub[$value->id] = $value->kode.' - '.$value->deskripsi;
            }

            return Helpers::responseJson(true, $barang_sub, "OK" );
         } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Gagal mengambil data" );
        }
    }


}

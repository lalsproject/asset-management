<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_Satuan;
use App\Model\M_Barang;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MBarangController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_barang'))
        {
            return redirect()->route('admin.');
        }

        $satuan = M_Satuan::pluck('deskripsi', 'id')->toArray();

        $table = $this::datatable(null);

        return view('admin.master.barang.index', compact('table','satuan'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_barang'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        // try {
        // dd($simpan);

            if ($simpan =='baru') {
                $id= 0;
                $barang = M_Barang::create($request->all());
                $pesan = "Data berhasil disimpan";
            } else {
                $id = $request->input('id');

                $barang = M_Barang::findOrFail($id);
                $barang->update($request->all());
                $pesan = "Data berhasil diupdate";
            }

            $barang->updated =  Helpers::generateUpdated();
            $barang->save();
            DB::commit();

        // } catch(\Exception $exception){
        //     DB::rollBack();
        //     return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        // }

        $table = $this::datatable($request, $barang);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request)
    {

        // dd($request->all());
        if (! Gate::allows('master_barang')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        DB::beginTransaction();

        // try {
            $barang = M_Barang::findOrFail($id);

            if (empty($barang)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $barang->delete();
            DB::commit();

        // } catch(\Exception $exception){
        //     DB::rollBack();
        //     return Helpers::responseJson(false, "", "Gagal Menghapus data" );
        // }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        // dd($request->all());

        if (! Gate::allows('master_barang')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        DB::beginTransaction();

        try {
            if ($request->input('ids')) {
                $entries = M_Barang::whereIn('id', $request->input('ids'))->get();


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

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",'".$data->kode."','".$data->deskripsi."')\">Edit</button>
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
        $barang = M_Barang::where('id','!=', '0');

        if(!empty($kriteria)){
            $barang = $barang->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($barang->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $barang = $barang->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Kode</th>
                      <th>Deskripsi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->kode."</td>
                      <td align='left'>".$dataproses->deskripsi."</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($barang as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$detail->kode."</td>
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

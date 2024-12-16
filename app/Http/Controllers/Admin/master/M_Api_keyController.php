<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_Api_key;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class M_Api_keyController extends Controller
{

    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_api_key'))
        {
            return redirect()->route('home');
        }


        $status = Array();
        $status[1] = "Aktif";
        $status[0] = "Non Aktif";
        $table = $this::datatable(null);

        return view('admin.master.api_key.index', compact('table','status'));
    }

    public function store(Request $request)
    { 
        // $result = new \stdClass();
        // if (!Auth::User()->akses(Array(2))) {
        //     return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        // }

        if(!Auth()->user()->hasAnyPermission('master_api_key'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }


        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $api_key = M_Api_key::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $api_key = M_Api_key::findOrFail($id);
                $api_key->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $api_key->updated =  Helpers::generateUpdated();
            $api_key->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $api_key);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request) {

        if(!Auth()->user()->hasAnyPermission('master_api_key'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');

        try {

            $api_key = M_Api_key::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($api_key)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $api_key->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "Hapus data berhasil" );
    }

    public function hapusdipilih(Request $request) {
        if(!Auth()->user()->hasAnyPermission('master_api_key'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = M_Api_key::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "Proses hapus pilihan dijalankan" );
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {
        //        function EditData(id, serial, deskripsi, harga, status_id ){


        $form = "<button class='btn btn-xs btn-info'  onclick=\"EditData(".$data->id.",'".$data->deskripsi."','".$data->token."')\">Edit</button> ";
        $form .= "<button class='btn btn-xs btn-danger'  onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        $form .= "<button class='btn btn-xs btn-primary' onclick=\"QR(".$data->id.")\">QR</button> ";
                
        return $form;
    }

    public function datatable($request, $dataproses = null)
    {

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('kriteria');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $api_key = M_Api_key::where('id','!=', '0');

        if(!empty($kriteria)){
            $api_key = $api_key->where('deskripsi','LIKE','%'.$kriteria.'%');
        }
        
        $count = count($api_key->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $api_key = $api_key->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Deskripsi</th>
                      <th>Token</th>
                      <th>Aktif</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

       

        foreach ($api_key as $detail) {
                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                      <td align='left'>".$detail->deskripsi."</td>
                      <td align='left'>".$detail->token."</td>
                      <td align='center'>".$this::status($detail)."</td>
                          <td align='center'>".$this::formAction($detail)."</td>
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

    public function status($api) {

        try
        {
            $status_id = $api->aktif;
            if (is_null($status_id)) {
                return "-";
            }

            if ($status_id == 1) {
                $rv = Helpers::WarnaLabel("Aktif", "HIJAU");
                return $rv;

            } else {
                $rv = Helpers::WarnaLabel("Non Aktif", "MERAH");
                return $rv;

            }

        } catch(\Exception $exception){
            return "E";
        }

    }

    
    public function qr($id)
    {

        // dd($id);
        if (! Gate::allows('master_api_key')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $api_key = M_Api_key::where('id',$id)->first();

        if (empty($api_key)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        
        $qr = \QrCode::size(200)
                ->backgroundColor(255, 255, 255)
                ->generate($api_key->token);

        $e_qr = base64_encode($qr);
        return Helpers::responseJson(true, $e_qr, "OK" );
    }

}

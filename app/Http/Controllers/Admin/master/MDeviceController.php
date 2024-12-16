<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_Device;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MDeviceController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_device'))
        {
            return redirect()->route('admin.');
        }
        $table = $this::datatable(null);

        return view('admin.master.device.index', compact('table'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_device'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        

            if ($simpan =='baru') {
                $id= 0;
                $device = M_Device::create($request->all());
                $pesan = "Data berhasil disimpan";

            } else {
                $id = $request->input('id');
        
                $device = M_Device::findOrFail($id);
                $device->update($request->all());
                $pesan = "Data berhasil diupdate";
        }

            $device->updated =  Helpers::generateUpdated();
            $device->save();
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $device);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('master_device')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        try {

            $device = M_Device::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($device)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $device->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {

        if (! Gate::allows('master_device')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = M_Device::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
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

        if (strlen($data->token) < 5) {
            $form = "<button class='btn btn-xs btn-success' id='btnAktifkan' onclick=\"Aktifkan(".$data->id.",'".$data->deskripsi."')\">Aktifkan</button> ";
        } else {
            $form = "<button class='btn btn-xs btn-warning' id='btnReset' onclick=\"Reset(".$data->id.")\">Reset</button> ";
        }

        $form .= "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",'".$data->deskripsi."')\">Edit</button> 
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
        $device = M_Device::where('id','!=', '0');

        if(!empty($kriteria)){
            $device = $device->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($device->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $device = $device->skip($skip)->take($take)->get();

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

        foreach ($device as $detail) {
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

    public function aktifkan($id)
    {

        if (! Gate::allows('master_device')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $device = M_device::Where('id',$id)->first();
        if (empty($device)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (strlen($device->token) > 5) {
            return Helpers::responseJson(false, "", "Device sudah diaktifkan" );
        }


        $cek = false;
        $otp = Helpers::rndstr(10);

        do {
            $otp = Helpers::rndstr(10);
            $cotp = M_Device::where('otp', $otp)->first();
            if (empty($cotp)) {
                $cek = true;
            }

        } while ($cek == false);

        $device->otp = $otp;
        $device->token = "";
        $device->hardware_id = "";
        $device->save();


        $qr = \QrCode::size(200)
                ->backgroundColor(255, 255, 255)
                ->generate($otp);

        $e_qr = base64_encode($qr);
        return Helpers::responseJson(true, $e_qr, "OK" );
    }


    public function reset(Request $request)
    {
        if (! Gate::allows('master_device')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');

        $device = M_device::Where('id',$id)->first();
        if (empty($device)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $device->otp = "";
        $device->token = "";
        $device->hardware_id = "";
        $device->save();

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }


    
}

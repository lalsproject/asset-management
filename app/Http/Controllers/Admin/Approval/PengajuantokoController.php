<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Model\T_AsetPengajuan;
use App\Model\M_Aset;
use App\Model\M_Status;
use App\Model\M_Kondisi;
use App\Model\M_Jenis;
use App\Model\M_Divisi;
use App\Model\M_Barang;
use App\Model\M_BarangSub;
use App\Model\M_Satuan;
use App\Model\M_Ruang;
use App\Model\M_Lokasi;
use App\Model\M_JenisPengadaan;
use App\Model\M_JenisMaintenance;
use App\Model\M_Tanah;
use App\Model\M_Kendaraan;
use App\Model\M_Bangunan;


use App\Http\Controllers\Admin\master\MAsetController;

use App\Exports\PengajuanExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class PengajuantokoController extends Controller
{

    public function index()
    {

        if(!Auth()->user()->hasAnyPermission(['transaksi_approvalaset','transaksi_approvalaset_proses']))
        {
            return redirect()->route('home');
        }



        $status = M_Status::pluck('deskripsi', 'id')->toArray();
        $jenis = M_Jenis::orderby('id')->pluck('deskripsi', 'id')->toArray();
        $kondisi = M_Kondisi::pluck('deskripsi', 'id')->toArray();
        $divisi = M_Divisi::pluck('deskripsi', 'id')->toArray();
        $jenis_pengadaan = M_JenisPengadaan::pluck('deskripsi', 'id')->toArray();
        $jenis_maintenance = M_JenisMaintenance::pluck('deskripsi', 'id')->toArray();

        $lokasi = M_Lokasi::pluck('deskripsi', 'id')->toArray();
        $ruang = M_Ruang::where('lokasi_id',array_key_first($lokasi))
                            ->pluck('deskripsi', 'id')->toArray();

        $barangs = M_Barang::get();
        $barang = Array();
        foreach ($barangs as $value) {
            $barang[$value->id] = $value->kode.' - '.$value->deskripsi;
        }

        $barang_subs = M_BarangSub::where('barang_id',array_key_first($barang))
                            ->get();
        $barang_sub = Array();
        foreach ($barang_subs as $value) {
            $barang_sub[$value->id] = $value->kode.' - '.$value->deskripsi;
        }


        $status_pengajuan = Array();
        $status_pengajuan[0] = "Draft";
        $status_pengajuan[1] = "Disetujui";
        $status_pengajuan[9] = "Ditolak";
        $table = $this::datatable(null);

        return view('admin.approval.pengajuantoko.index', compact('table','status_pengajuan','status','jenis','kondisi','divisi','jenis_pengadaan','lokasi','ruang','barang','barang_sub','jenis_maintenance'));
    }

    public function edit($id)
    {

        if(!Auth()->user()->hasAnyPermission(['transaksi_approvalaset_proses'])) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $pengajuan = T_AsetPengajuan::where('id', $id)->first();
        if (empty($pengajuan)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $aset = M_Aset::Where('id',$pengajuan->aset_id)->first();
        if (empty($aset)) {
            $barang_id = 0;
            $barang_sub = 0;
            $lokasi_id = 0;
            $ruang = 0;
        } else {
            $barang_id =  $aset->barang_sub->barang_id;
    
            $barang_subs = M_BarangSub::where('barang_id',$barang_id)
                                ->get();
            $barang_sub = Array();
            foreach ($barang_subs as $value) {
                $barang_sub[$value->id] = $value->kode.' - '.$value->deskripsi;
            }
    
            $lokasi_id = $aset->ruang->lokasi_id;
            $ruang = M_Ruang::where('lokasi_id',$lokasi_id)
                                ->pluck('deskripsi', 'id')->toArray();

        }

        $result = Array();
        $result['pengajuan'] = $pengajuan;
        $result['aset'] = $aset;
        $result['barang'] =$barang_id;
        $result['barang_sub'] =$barang_sub;
        $result['lokasi'] =$lokasi_id;
        $result['ruang'] =$ruang;

        return Helpers::responseJson(true, $result, "OK" );
    }

    public function store(Request $request)
    { 

        // dd($request->all());

        // $result = new \Class();
        // if (!Auth::User()->akses(Array(2))) {
        //     return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        // }

        if(!Auth()->user()->hasAnyPermission('transaksi_approvalaset_proses'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }


        $simpan = $request->input('simpan');

        DB::beginTransaction();

        try {        


            $id = $request->input('id');
            $ruang_id = $request->input('ruang_id');
            $barang_sub_id = $request->input('barang_sub_id');
            $keterangan = $request->input('keterangan');
            $generate = MAsetController:: GenerateKodeAset($ruang_id, $barang_sub_id);

            $c = $request->all();
            $c['kode'] = $generate['kode'];
            $c['no_urut'] = $generate['urut'];
            $c['barang_id'] = $generate['barang_id'];
            $c['updated'] = Helpers::generateUpdated();

            $aset = M_Aset::create($c);
            $pesan = "Data berhasil disimpan";

            $aset = M_Aset::where('kode', $generate['kode'])->first();
            $pengajuan = T_AsetPengajuan::where('id', $id)->first();
            $pengajuan->approved =  Helpers::generateUpdated();
            $pengajuan->updated =  Helpers::generateUpdated();
            $pengajuan->status =  1;
            $pengajuan->aset_id =  $aset->id;
            $pengajuan->keterangan2 = $keterangan;
            $pengajuan->save();

            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }


    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data, $proses = false)
    {

        $form = "";
        if ($data->status == 0) {
            if ($proses) {
                $form = "<button class='btn btn-xs btn-success' id='btnEdit' onclick=\"Detail(".$data->id.")\">Setujui</button> ";
                $form .= "<button class='btn btn-xs btn-danger' id='btnReject' onclick=\"Reject(".$data->id.")\">Reject</button> ";
            }

        } elseif ($data->status == 1) {
            $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"Detail(".$data->id.")\">Detail</button> ";

        } else {
            $form="";
        }


        return $form;
    }

    public function datatable($request, $dataproses = null)
    {
        $bolehproses = false;
        if(Auth()->user()->hasAnyPermission('transaksi_approvalaset_proses'))
        {
            $bolehproses = true;
        }

        // dd($request->all());

        if (!empty($request)) {
            $take = $request->input('take');
            $halaman = $request->input('halaman');
            $kriteria = $request->input('kriteria');
            $filter_status = $request->input('filter_status');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $api_key = T_AsetPengajuan::where('id','!=', '0');

        if(!empty($kriteria)){
            $api_key = $api_key->where('deskripsi','LIKE','%'.$kriteria.'%');
        }
        
        if(!empty($filter_status)){
            $api_key = $api_key->wherein('status',$filter_status);
        } else {
            $api_key = $api_key->wherein('status',[0]);
        }


        
        $count = count($api_key->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $api_key = $api_key->skip($skip)->take($take)->get();

        // dd($api_key);

        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>SKU</th>
                        <th>Deskripsi</th>
                        <th>Created</th>
                        <th>Approved / Rejected</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        foreach ($api_key as $detail) {

                $table .= "<tr>
                            <td align='left'>".$detail->cabang_nama."</td>
                            <td align='left'>".$detail->produk_id."</td>
                            <td align='left'>".$detail->deskripsi."</td>
                            <td align='left'>".$detail->created."</td>
                            <td align='left'>".$detail->approved."</td>
                            <td align='center'>".$this::status($detail)."</td>
                            <td align='center'>".$this::formAction($detail, $bolehproses)."</td>
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
            $status_id = $api->status;
            if (is_null($status_id)) {
                $rv = Helpers::WarnaLabel("Draft", "ABU-ABU");
                return $rv;
            }

            if ($status_id == 1) {
                $rv = Helpers::WarnaLabel("Disetujui", "HIJAU");
                return $rv;

            } elseif ($status_id == 0) {
                $rv = Helpers::WarnaLabel("Draft", "ABU-ABU");
                return $rv;

            } else {
                $rv = Helpers::WarnaLabel("Ditolak", "MERAH");
                return $rv;

            }

        } catch(\Exception $exception){
            return "E";
        }

    }


    public function reject(Request $request)
    { 
        if(!Auth()->user()->hasAnyPermission('transaksi_approvalaset_proses'))
        {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        $keterangan2 = $request->input('keterangan2');

        DB::beginTransaction();

        try {        
        
            $pengajuan = T_AsetPengajuan::findOrFail($id);

            if(is_null($pengajuan)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );

            }

            $pengajuan->update([
                                    "status" => 9,
                                    "approved" =>  Helpers::generateUpdated(),
                                    "updated" =>  Helpers::generateUpdated(),
                                    "keterangan2" => $keterangan2
                                ]);
            $pesan = "Data berhasil diupdate";

            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function exportexcel(Request $request) {

        return Excel::download(new PengajuanExport($request), 'PengajuanToko.xlsx');
    }



}

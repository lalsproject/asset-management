<?php

namespace App\Http\Controllers\Admin\master;

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

use App\Helpers;
use App\Fungsi\Project;

use App\Model\T_Aset;
use App\Model\T_Maintenance;

use App\Exports\AsetExport;
use Maatwebsite\Excel\Facades\Excel;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MAsetController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_aset'))
        {
            return redirect()->route('admin.');
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

        $table = $this::datatable(null);

        return view('admin.master.aset.index', compact('table','status','jenis','kondisi','divisi','jenis_pengadaan','lokasi','ruang','barang','barang_sub','jenis_maintenance'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_aset'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        $simpan = $request->input('simpan');

        DB::beginTransaction();

        // try {

            if ($simpan =='baru') {
                $id= 0;

                $ruang_id = $request->input('ruang_id');
                $barang_sub_id = $request->input('barang_sub_id');
                $generate = $this:: GenerateKodeAset($ruang_id, $barang_sub_id);

                $c = $request->all();
                $c['kode'] = $generate['kode'];
                $c['no_urut'] = $generate['urut'];
                $c['barang_id'] = $generate['barang_id'];
                $c['updated'] = Helpers::generateUpdated();

                $aset = M_Aset::create($c);
                $pesan = "Data berhasil disimpan";

                $id = $aset->id;

            } else {
                $c = $request->all();

                $id = $c['id'];

                $aset = M_Aset::Where('id',$id)->first();
                if (empty($aset)) {
                    return Helpers::responseJson(false, "", "Data tidak ditemukan" );
                }


                $c['updated'] = Helpers::generateUpdated();
                $aset->update($c);

                $c['aset_id'] = $id;
                $c['no_urut'] = $aset->no_urut;
                $c['kode'] = $aset->kode;
                T_Aset::create($c);

                $pesan = "Data berhasil diupdate";
            }

            $x = Project::susunpenyusutan($id);

            DB::commit();

        // } catch(\Exception $exception){
        //     DB::rollBack();
        //     return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        // }

        $table = $this::datatable($request, $aset);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }


    public function edit($id)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $aset = M_Aset::Where('id',$id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

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

        $result = Array();
        $result['aset'] = $aset;
        $result['barang'] =$barang_id;
        $result['barang_sub'] =$barang_sub;
        $result['lokasi'] =$lokasi_id;
        $result['ruang'] =$ruang;

        return Helpers::responseJson(true, $result, "OK" );
    }

    public static function GenerateKodeAset_pendek($ruang_id, $barang_sub_id) {
        $ruang = M_Ruang::Where('id', $ruang_id)->first();
        if (empty($ruang)) {
            $k_lokasi = Helpers::rndstr(5);
        } else {
            $k_lokasi = $ruang->lokasi->kode;
        }

        $barang_sub = M_BarangSub::where('id', $barang_sub_id)->first();
        if (empty($barang_sub)) {
            $k_barang = Helpers::rndstr(6);
            $barang_id = 0;
        } else {
            $k_barang = $barang_sub->barang->kode;
            $barang_id = $barang_sub->barang_id;
        }

        $aset = M_Aset::Where('barang_id',$barang_id)
                    ->max('no_urut');

        if (empty($aset)) {
            $urut = 1;
        } else {
            $urut = $aset + 1;
        }

        $dapatkode = false;

        do {
            if (strlen($urut) > 4) {
                $k_urut = $urut;
            } else {
                $k_urut = str_repeat("0", 4 - strlen($urut)).$urut;
            }

            $kode = $k_barang."-".$k_urut."/".$k_lokasi;

            $cek = M_Aset::where('kode', 'like', $kode."%")->first();

            if (!empty($cek)) {
                // dd($cek);
                $urut++;
            } else {
                // dd($kode);
                $dapatkode = true;
            }
        } while ($dapatkode == false);

        // dd($kode);

        $result = Array();
        $result['kode'] = $kode;
        $result['urut'] = $urut;
        $result['barang_id'] = $barang_id;
        $result['lokasi'] = $k_lokasi;

        // dd($kode);
        return $result;
    }


    public static function GenerateKodeAset($ruang_id, $barang_sub_id) {
        $ruang = M_Ruang::Where('id', $ruang_id)->first();
        if (empty($ruang)) {
            $k_ruang = Helpers::rndstr(5);
            $k_lokasi = Helpers::rndstr(5);
        } else {
            $k_ruang = $ruang->kode;
            $k_lokasi = $ruang->lokasi->kode;
        }

        $barang_sub = M_BarangSub::where('id', $barang_sub_id)->first();
        if (empty($barang_sub)) {
            $k_barang_sub = Helpers::rndstr(6);
            $barang_id = 0;

        } else {
            $k_barang_sub = $barang_sub->kode;
            $barang_id = $barang_sub->barang_id;
        }

        $aset = M_Aset::Where('ruang_id',$ruang_id)
                    ->where('barang_sub_id', $barang_sub_id)
                    ->max('no_urut');

        if (empty($aset)) {
            $urut = 1;
        } else {
            $urut = $aset + 1;
        }

        if (strlen($urut) > 4) {
            $k_urut = $urut;
        } else {
            $k_urut = str_repeat("0", 4 - strlen($urut)).$urut;
        }

        $kode = $k_lokasi."/".$k_barang_sub."/".$k_urut;

        $result = Array();
        $result['kode'] = $kode;
        $result['urut'] = $urut;
        $result['barang_id'] = $barang_id;
        $result['lokasi'] = $k_lokasi;
        return $result;
    }

    public function hapusdata(Request $request)
    {

        if(!Auth()->user()->hasAnyPermission('hapus_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }


        $id = $request->input('id');
        DB::beginTransaction();

        try {
            $barang = M_Aset::findOrFail($id);

            if (empty($barang)) {
                return Helpers::responseJson(false, "", "Data tidak ditemukan" );
            }

            $barang->delete();
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
        if(!Auth()->user()->hasAnyPermission('hapus_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        // dd($request->all());


        DB::beginTransaction();

        try {
            if ($request->input('ids')) {
                $entries = M_Aset::whereIn('id', $request->input('ids'))->get();

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

    public static function formAction($data, $boleh_hapus = false)    {
        $jenis = $data->jenis_id;
        $form ="<button class='btn btn-xs btn-primary' id='btnMaintenance' onclick=\"Maintenance(".$data->id.")\">Maintenance</button> ";

        if ($jenis == 2) {
            $form .="<button class='btn btn-xs btn-success' id='btnTanah' onclick=\"DataTanah(".$data->id.")\">Detail Tanah</button> ";
        }

        if ($jenis == 3) {
            $form .="<button class='btn btn-xs btn-warning' id='btnKendaraan' onclick=\"DataBangunan(".$data->id.")\">Detail Bangunan</button> ";
        }

        if ($jenis == 4) {
            $form .="<button class='btn btn-xs btn-warning' id='btnKendaraan' onclick=\"DataKendaraan(".$data->id.")\">Detail Kendaraan</button> ";
        }

        $form .= " <button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.")\">Edit</button> ";
        if ($boleh_hapus) {
            $form .= " <button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        }

        if (file_exists( env('APP_PUBLIC_IMG') . 'aset/aset_' . $data->id . '.jpg')) {
            $form .= " <button class='btn btn-xs btn-success' id='btnGambar' onclick=\"Image(".$data->id.")\">Gambar</button> ";


            // return url(env('APP_URL_IMG').'aset/aset_' . $id . '.jpg?'.Helpers::rndstr(6));
        }


        return $form;
    }

    public function datatable($request, $dataproses = null) {

        // dd($request->all());

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('kriteria');
            $lokasi = $request->input('filter_lokasi');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;

        $boleh_hapus = false;
        if(Auth()->user()->hasAnyPermission('hapus_aset')) {
            $boleh_hapus = true;
        }


        $barang = M_Aset::where('id','!=', '0');

        if (!empty($lokasi)) {
            $ruang = M_Ruang::wherein('lokasi_id', $lokasi)->pluck('id')->toarray();
            $barang = $barang->wherein('ruang_id', $ruang);

        }


        if(!empty($kriteria)){
            $barang = $barang->where('tipe','LIKE','%'.$kriteria.'%')
                            ->orwhere('seri','LIKE','%'.$kriteria.'%')
                            ->orwhere('kode','LIKE','%'.$kriteria.'%')
                            ->orwhere('qr','LIKE','%'.$kriteria.'%')
                           ;
        }

        $count = count($barang->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $barang = $barang->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover  table-bordered no-wrap'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                        </th>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th>Seri</th>
                        <th>Ruang</th>
                        <th>Status</th>
                        <th>Jenis</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>";

        if (!empty($dataproses)) {
            $status = $dataproses->status;
            $jenis = $dataproses->jenis;
            $kondisi = $dataproses->kondisi;
            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->kode."</td>
                      <td align='left'>".$dataproses->barang_sub->deskripsi."</td>
                      <td align='left'>".$dataproses->tipe."</td>
                      <td align='left'>".$dataproses->seri."</td>
                      <td align='left'>".$dataproses->ruang->deskripsi."</td>
                      <td align='left'>".Helpers::WarnaLabel($status->deskripsi, $status->warna)."</td>
                      <td align='left'>".Helpers::WarnaLabel($jenis->deskripsi, $jenis->warna)."</td>
                      <td align='left'>".Helpers::WarnaLabel($kondisi->deskripsi, $kondisi->warna)."</td>
                      <td align='center'>".$this::formAction($dataproses, $boleh_hapus)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($barang as $detail) {
            if ($detail->id != $dataproses_id) {
                $status = $detail->status;
                $jenis = $detail->jenis;
                $kondisi = $detail->kondisi;

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($detail->id)."</td>
                          <td align='left'>".$detail->kode."</td>
                          <td align='left'>".$detail->barang_sub->deskripsi."</td>
                          <td align='left'>".$detail->tipe."</td>
                            <td align='left'>".$detail->seri."</td>
                            <td align='left'>".$detail->ruang->deskripsi."</td>
                          <td align='left'>".Helpers::WarnaLabel($status->deskripsi, $status->warna)."</td>
                          <td align='left'>".Helpers::WarnaLabel($jenis->deskripsi, $jenis->warna)."</td>
                          <td align='left'>".Helpers::WarnaLabel($kondisi->deskripsi, $kondisi->warna)."</td>
                          <td align='center'>".$this::formAction($detail, $boleh_hapus)."</td>
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

    public function ambildatatanah($id)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $aset = M_Aset::Where('id',$id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if ($aset->jenis_id != 2) {
            return Helpers::responseJson(false, "", "Aset bukan jenis tanah" );
        }

        $tanah = M_Tanah::Where('aset_id', $id)->first();

        $result = array(
            'aset' => $aset->kode." - ".$aset->barang_sub->deskripsi,
            'tanah' => $tanah,
        );

        return Helpers::responseJson(true, $result, "OK" );
    }

    public function simpandatatanah(Request $request)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $aset_id = $request->input('aset_id');


        $aset = M_Aset::Where('id',$aset_id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if ($aset->jenis_id != 2) {
            return Helpers::responseJson(false, "", "Aset bukan jenis tanah" );
        }

        try {

            $c = $request->all();
            $c['updated'] = Helpers::generateUpdated();

            $tanah = M_Tanah::Where('aset_id', $aset_id)->first();

            if (!empty($tanah)) {
                $tanah->update($c);
            } else {
                $tanah = M_Tanah::Create($c);
            }

            return Helpers::responseJson(true, $tanah, "Data berhasil disimpan" );
        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Gagal Menyimpan data" );
        }
    }

    public function ambildatakendaraan($id)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $aset = M_Aset::Where('id',$id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if ($aset->jenis_id != 4) {
            return Helpers::responseJson(false, "", "Aset bukan jenis kendaraan" );
        }

        $kendaraan = M_Kendaraan::Where('aset_id', $id)->first();

        $result = array(
            'aset' => $aset->kode." - ".$aset->barang_sub->deskripsi,
            'kendaraan' => $kendaraan,
        );

        return Helpers::responseJson(true, $result, "OK" );
    }

    public function simpandatakendaraan(Request $request)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $aset_id = $request->input('aset_id');

        $aset = M_Aset::Where('id',$aset_id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if ($aset->jenis_id != 4) {
            return Helpers::responseJson(false, "", "Aset bukan jenis kendaraan" );
        }

        try {

            $c = $request->all();

            $berlaku_stnk = $request->input('berlaku_stnk');
            //dd($berlaku_stnk);

            if (!empty($berlaku_stnk)) {
                $remind_stnk ="2000".date_format(date_create($berlaku_stnk),"/m/d");
                $c['remind_stnk'] = $remind_stnk;
            }

            $c['updated'] = Helpers::generateUpdated();
            $kendaraan = M_Kendaraan::Where('aset_id', $aset_id)->first();

            if (!empty($kendaraan)) {
                $kendaraan->update($c);
            } else {
                $kendaraan = M_Kendaraan::Create($c);
            }

            return Helpers::responseJson(true, $kendaraan, "Data berhasil disimpan" );
        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Gagal Menyimpan data" );
        }
    }



    // Maintenance ========================================
    public function maintenance(Request $request) {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $take = $request->input('take');
        $aset_id = $request->input('aset_id');
        if (empty($aset_id)) { $aset_id = $request->input('id'); }

        $halaman = $request->input('halaman');

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;

        $jenis_maintenance= M_JenisMaintenance::pluck('deskripsi','id')->toArray();
        $maintenance = T_Maintenance::where('aset_id',$aset_id);

        $count = count($maintenance->get());
        $pagination = Helpers::Pagination("LoadDataMaintenance", $count, $take, $halaman);
        $maintenance = $maintenance->orderby('id','desc')->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover  table-bordered no-wrap'>
                  <thead>
                    <tr>
                        <th>Jenis Maintenance</th>
                        <th>Keterangan</th>
                        <th>Vendor</th>
                        <th>Harga</th>
                        <th>Updated</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>";


        foreach ($maintenance as $detail) {

            if (array_key_exists($detail->jenis_maintenance_id, $jenis_maintenance)) {
                $j = $jenis_maintenance[$detail->jenis_maintenance_id];

            } else {
                $j="";
            }
            $table .= "<tr>
                        <td align='left'>".$j."</td>
                        <td align='left'>".$detail->keterangan."</td>
                        <td align='left'>".$detail->vendor."</td>
                        <td align='right'>".number_format($detail->harga)."</td>
                        <td align='left'>".$detail->updated."</td>
                        <td align='center'><button class='btn btn-xs btn-danger' onclick=\"HapusMaintenance(".$detail->id.")\">Hapus</button></td>
                    </tr>
                ";
        }

        $table .= "</table>";
        $result = array(
            'table' => $table,
            'pagination' => $pagination,
        );
        $result['aset_id'] = $aset_id;

        $aset = Project::dataaset($aset_id);
        if ($aset->success) {
            $result['aset'] = $aset->kode." - ".$aset->namaaset.", ".$aset->seri;
        } else {
            $result['aset'] = "Tidak ditemukan";
        }

        return Helpers::responseJson(true, $result, "OK" );

    }

    public function simpanmaintenance(Request $request) {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $aset_id = $request->input('aset_id');

        $aset = M_Aset::Where('id',$aset_id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        try {

            $c = $request->all();
            $c['updated'] = Helpers::generateUpdated();
            $maintenance = T_Maintenance::Create($c);

            return $this::maintenance($request);

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Gagal Menyimpan data" );
        }
    }

    public function hapusmaintenance(Request $request) {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $aset_id = $request->input('aset_id');
        $id = $request->input('id');

        $maintenance = T_Maintenance::Where('id',$id)->where('aset_id', $aset_id)->first();
        if (empty($maintenance)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        try {
            $maintenance = $maintenance->delete();

            return $this::maintenance($request);

        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Gagal Menyimpan data" );
        }
    }


    public function ambildatabangunan($id)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $aset = M_Aset::Where('id',$id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if ($aset->jenis_id != 3) {
            return Helpers::responseJson(false, "", "Aset bukan jenis bangunan" );
        }

        $tanah = M_Bangunan::Where('aset_id', $id)->first();

        $result = array(
            'aset' => $aset->kode." - ".$aset->barang_sub->deskripsi,
            'tanah' => $tanah,
        );

        return Helpers::responseJson(true, $result, "OK" );
    }

    public function simpandatabangunan(Request $request)
    {
        if (! Gate::allows('master_aset')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $aset_id = $request->input('aset_id');


        $aset = M_Aset::Where('id',$aset_id)->first();
        if (empty($aset)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if ($aset->jenis_id != 3) {
            return Helpers::responseJson(false, "", "Aset bukan jenis bangunan" );
        }

        try {

            $c = $request->all();
            $c['updated'] = Helpers::generateUpdated();

            $tanah = M_Bangunan::Where('aset_id', $aset_id)->first();

            if (!empty($tanah)) {
                $tanah->update($c);
            } else {
                $tanah = M_Bangunan::Create($c);
            }

            return Helpers::responseJson(true, $tanah, "Data berhasil disimpan" );
        } catch(\Exception $exception){
            return Helpers::responseJson(false, $exception, "Gagal Menyimpan data" );
        }
    }

    public function exportexcel(Request $request) {

        return Excel::download(new AsetExport($request), 'Aset.xlsx');
    }


}

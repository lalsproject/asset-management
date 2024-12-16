<?php

namespace App\Http\Controllers\Admin\Aset;

use App\Model\M_Aset;
use App\Model\T_Mutasi;
use App\Model\M_Lokasi;
use App\Model\M_Ruang;
use App\Helpers;

use App\Http\Controllers\Admin\master\MAsetController;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
// use Carbon\Carbon;

class MutasiController extends Controller
{
    public function index()
    {

        if(!Auth()->user()->hasAnyPermission('aset_mutasi'))
        {
            return redirect()->route('admin.');
        }
        $table = $this::datatable(null);

        $lokasi = M_Lokasi::pluck('deskripsi','id')->toArray();

        return view('admin.aset.mutasi.index', compact('table','lokasi'));
    }

    public function store(Request $request)
    { 
        // dd($request->all());

        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('aset_mutasi'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        DB::beginTransaction();

        try {        
            // dd($request->all());
            // $c = $request->all();
            $kode = $request->input("kode");
            $ruang_id = $request->input("ruang");

            $aset = M_Aset::where("kode", $kode)->first();
            if (empty($aset)) {
                return Helpers::responseJson(false, "", "Data Aset tidak ditemukan" );
            }

            $c['aset_id'] = $aset->id;
            $c['b_ruang_id'] = $aset->ruang_id;
            $c['b_kode'] = $aset->kode;
            
            $g = MAsetController::GenerateKodeAset($ruang_id, $aset->barang_sub_id);
            
            $c['a_kode'] = $g['kode'];
            $c['a_ruang_id'] = $ruang_id;
            $c['updated'] =  Helpers::generateUpdated();;

            // dd($c);

            if ($c['b_ruang_id'] == $c['a_ruang_id']) {
                return Helpers::responseJson(false, "", "Tidak ada perubahan pada data Aset" );

            }

            $create = T_Mutasi::create($c);

            $aset->kode = $g['kode'];
            $aset->no_urut = $g['urut'];
            $aset->ruang_id = $ruang_id;
            $aset->save();

            // $u = $aset::update($u);

            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, null);

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
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

        $sql = "select t_aset_mutasi.*, concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset, r1.deskripsi as ruangasal, r2.deskripsi as ruangtujuan
                    from (t_aset_mutasi inner join m_aset on t_aset_mutasi.aset_id = m_aset.id)
                    left join m_barang_sub on m_barang_sub.id = m_aset.barang_sub_id
                    left join m_ruang as r1 on r1.id = t_aset_mutasi.b_ruang_id
                    left join m_ruang as r2 on r2.id = t_aset_mutasi.a_ruang_id ";

        if(!empty($kriteria)){
            $sql .= " where t_aset_mutasi.a_kode like '%".$kriteria."%' 
                            or t_aset_mutasi.b_kode like '%".$kriteria."%' 
                            or r1.deskripsi like '%".$kriteria."%' 
                            or r2.deskripsi like '%".$kriteria."%' 
                            or m_barang_sub.deskripsi like '%".$kriteria."%' 
                            or m_aset.tipe like '%".$kriteria."%' ";
        }

        $sql .= " order by t_aset_mutasi.id desc";

        $data = DB::SELECT(DB::RAW($sql));
        $count = count($data);

        $result['count'] = $count;

        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $final = array_slice($data, $skip, $take); 


        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                      <th>Tanggal</th>
                      <th>Nama Aset</th>
                      <th>Ruang Asal</th>
                      <th>Ruang Tujuan</th>
                      <th>Kode Sebelumnya</th>
                      <th>Kode Baru</th>
                    </tr>
                  </thead>
                  <tbody>";

        foreach ($final as $detail) {

                $table .= "<tr>
                          <td align='left'>".date_format(date_create($detail->created_at),'Y-m-d')."</td>
                          <td align='left'>".$detail->namaaset."</td>
                          <td align='left'>".$detail->ruangasal."</td>
                          <td align='left'>".$detail->ruangtujuan."</td>
                          <td align='left'>".$detail->b_kode."</td>
                          <td align='left'>".$detail->a_kode."</td>

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

    public function aktifkan($id)
    {

        if (! Gate::allows('aset_mutasi')) {
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
        if (! Gate::allows('aset_mutasi')) {
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

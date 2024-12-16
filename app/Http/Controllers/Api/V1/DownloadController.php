<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
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

use App\Model\T_Opname;

use App\Helpers;

use App\Model\T_Aset;
use App\Model\T_Maintenance;
use App\Model\T_Label;

use App\Http\Controllers\Admin\master\MAsetController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


use DB;
use Hash;
use ZipArchive;


class DownloadController extends Controller
{

    public function test(Request $request) {

        return Helpers::Response($request, true, "OK", "Pengaturan sesuai", 200) ;

    }
    public function cetak(Request $request) {

        DB::statement("update t_label set status = 1 limit 30");

        $sql= "select t_label.id as label_id, m_aset.*, m_lokasi.deskripsi as lokasi, m_ruang.deskripsi as ruang,
                    m_barang.deskripsi as barang, m_barang_sub.deskripsi as barangsub,
                    concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset
                FROM t_label inner join m_aset on t_label.aset_id = m_aset.id
                    left join m_ruang on m_ruang.id = m_aset.ruang_id
                    left join m_lokasi on m_ruang.lokasi_id = m_lokasi.id
                    left join m_barang_sub on m_barang_sub.id = m_aset.barang_sub_id
                    left join m_barang on m_barang.id = m_barang_sub.barang_id
                WHERE t_label.status = 1";

        $data=DB::select(DB::raw($sql));

        $hapus = T_Label::where('status', 1)->delete();

        return Helpers::Response($request, true, $data, "List Label", 200) ;

    }
    public function android(Request $request) 
    {

        //https://stackoverflow.com/questions/30212390/laravel-middleware-return-variable-to-controller
        $user = $request->get('user');

        // dd($request->all());

        $result = Array();
        $result['aset'] = M_Aset::get();
        $result['status'] = M_Status::get();
        $result['kondisi'] = M_Kondisi::get();
        $result['jenis'] = M_Jenis::get();
        $result['divisi'] = M_Divisi::get();
        $result['barang'] = M_Barang::get();
        $result['barangsub'] = M_BarangSub::get();
        $result['satuan'] = M_Satuan::get();
        $result['ruang'] = M_Ruang::get();
        $result['lokasi'] = M_Lokasi::get();
        $result['jenispengadaan'] = M_JenisPengadaan::get();
        $result['jenismaintenance'] = M_JenisMaintenance::get();
        // $result['tanah'] = M_Tanah::get();
        // $result['kendaraan'] = M_Kendaraan::get();

        $u = User::all(); 
        // dd($u);
        $l_user= Array();
        foreach ($u as $key => $value) {
            $d_user = Array();
            $d_user['id'] = $value->id;
            $d_user['name'] = $value->name;
            $d_user['email'] = $value->email;
            $d_user['password'] = $value->password;

            $l_user[] = $d_user;
        }
        // dd($l_user);

        $result['user'] = $l_user;  //db::raw(db::select("select * from users"));



        // dd($result['user']);
        return Helpers::Response($request, true, $result, "OK", 200);

    }

    public function imgopname(Request $request) 
    {

        // dd($request->all());
        $lokasi_id = $request->input("lokasi_id");

        $ruang_ids = M_Ruang::where("lokasi_id", $lokasi_id)->pluck('id')->toArray();
        $aset_ids = M_Aset::wherein("ruang_id", $ruang_ids)->pluck('id')->toArray();

		if (Storage::disk('local')->exists("imgopname.json")) {
            Storage::disk('local')->delete("imgopname.json");
        }
        
		$zip_file = Storage::disk('local')->path("imgopname.zip");

        $publicpath = env('APP_PUBLIC_IMG_ASET','/home/u1434218/public_html/aset');

		if(file_exists($zip_file)) {
				unlink ($zip_file); 
		}


        $opname = T_Opname::wherein("aset_id", $aset_ids)->get();

        Storage::disk('local')->put('imgopname.json', json_encode($opname));

		$zip = new ZipArchive();
		if ($zip->open($zip_file, ZipArchive::CREATE )  === TRUE) {
			$t = Storage::disk('local')->path("imgopname.json");
			if (is_readable($t)) {
				$zip->addFile($t, "imgopname.json") ;
			}
        } else {
            return Helpers::Response($request, false, "File zip gagal dibuat", "Gagal download data image opname", 200);
        }

		foreach ($opname as $key => $value) {

            if (file_exists($publicpath.$value->uniq_id.'.jpg')) {
                $t = $publicpath.$value->uniq_id.'.jpg';
                if (is_readable($t)) {
                    $zip->addFile($t, $value->uniq_id.'.jpg') ;
                }
            }
        }

        $zip->close(); 


        return response()->download($zip_file);

    }


    public function ambildataaset($request) 
    {

        $ruang_id = $request->input('ruang_id');

        $data = $this::sumaryaset($ruang_id);
        return Helpers::Response($request, true, $data, "OK", 200);
    }

    public static function sumaryaset($ruang_id) {
        $aset = M_Aset::where('ruang_id',$ruang_id)->get();

        $sql = "SELECT max(id) as n FROM t_opname where ruang_id =".$ruang_id.
                                " and ruang_id2 =".$ruang_id.
                                " and aset_id != 0".
                                " and date_format(created_at, '%Y-%m-%d') ='". Date('Y-m-d')."' GROUP BY `aset_id`";

        $sudahscan = db::select(db::raw($sql)  );

        $sql = "SELECT max(id) as n FROM t_opname where ruang_id != ".$ruang_id.
            " and ruang_id2 =".$ruang_id." and date_format(created_at, '%Y-%m-%d') ='". Date('Y-m-d')."' GROUP BY `aset_id`";

        $salahruang = db::select(db::raw($sql)  );

        $sql = "SELECT id  FROM t_opname where aset_id = 0".
        " and ruang_id2 =".$ruang_id." and date_format(created_at, '%Y-%m-%d') ='". Date('Y-m-d')."'";

        $tanpalabel = db::select(db::raw($sql)  );

        // $tanpalabel = T_Opname::where('aset_id','0')
        //                     ->where('ruang_id2',$ruang_id)
        //                     ->where(db::raw( "date_format(created_at, '%Y-%m-%d') ='". Date('Y-m-d')."'"  ))
        //                     ->get();

        // dd($tanpalabel);

        $data = Array();
        $data['aset'] = $aset;
        $data['jumlah'] = count($aset);
        $data['sudahscan'] = count($sudahscan);
        $data['salahruang'] = count($salahruang);
        $data['tanpalabel'] = count($tanpalabel);
        return $data;
    }

    public function getaset($request) 
    {

        $kode = $request->input('kode');

        $aset = M_Aset::where('kode',$kode)->first();
        if (is_null($aset)) {
            //tidak ditemukan berdasarkan kode. Cari berdasarkan QR
            $aset = M_Aset::where('qr',$kode)->first();
            if (is_null($aset)) {
                return Helpers::Response($request, false, "", "Data tidak ditemukan", 200);
            }
        }

        $aset->deskripsi = $aset->barang_sub->deskripsi;

        return Helpers::Response($request, true, $aset, "OK", 200);
    }

    public function tambahaset($request) 
    {

        // dd($request->all());

        $user = $request->get('user');
        // dd($user->name);

        $barang_sub_id = $request->input('barang_sub_id');
        $ruang_id = $request->input('ruang_id');
        $tipe = $request->input('tipe');
        if (is_null($tipe)) {
            return Helpers::Response($request, false, "", "Tipe belum diisi ", 200);
        }

        

        $qr = $request->input('qr');
        if (is_null($qr)) {
            $qr = "";
        }

        // Cek QR
        if (strlen($qr) > 2 ) {
            $cek = M_Aset::WHERE('qr', $qr)->first();
            // dd($cek);
            if (!is_null($cek)) {
                return Helpers::Response($request, false, $cek, "QR ".$qr. " sudah dipakai oleh aset ".$cek->kode, 200);
            }
        }


        $generate = MAsetController::GenerateKodeAset($ruang_id, $barang_sub_id);

        $c = $request->all();
        $c['kode'] = $generate['kode'];
        $c['barang_id'] = $generate['barang_id'];
        $c['no_urut'] = $generate['urut'];
        $c['updated'] = Helpers::generateUpdatedAPI($user);
        $c['qr'] = $qr;



        $aset = M_Aset::create($c);

        if($request->hasFile('foto'))
        {
            $file = $request->foto;

            // $originalname = $file->getClientOriginalName();

            // dd($originalname);
            $fn = "aset_".$aset->id.".jpg";
            $path = $file->storeAs('aset', $fn, 'img');

        }

        // dd($aset);

        return Helpers::Response($request, true, $aset, "OK", 200);


    }


    public function opnameaset($request) 
    {

        // dd($request->all());
       
        $user = $request->get('user');
        $device = $request->get('device');
        // dd($device);

        // $barang_sub_id = $request->input('barang_sub_id');
        $aset_id = $request->input('aset_id');
        $ruang_id = $request->input('ruang_id');
        $ruang_id2 = $request->input('ruang_id2');

        // $qr = $request->input('qr');

        // Cek QR
        $aset = M_Aset::WHERE('id', $aset_id)->first();
        // dd($cek);
        if (is_null($aset)) {
            return Helpers::Response($request, false, $aset_id, "Data aset tidak ditemukan ", 200);
        }

        $c = $request->all();
        $c['device_id'] = $device->id;
        $c['user_id'] = $user->id;
        $c['updated'] = Helpers::generateUpdatedAPI($user);

        // dd($c);

        $opname = T_Opname::create($c);

        if($request->hasFile('foto'))
        {
            $file = $request->foto;

            // $originalname = $file->getClientOriginalName();

            // dd($originalname);
            $fn = "opname_".$opname->id.".jpg";
            $path = $file->storeAs('aset', $fn, 'img');

        }

        //Update data lokasi dan pengguna terbaru
        if (!is_null($c["pengguna"])) {
            $aset->pengguna = $c["pengguna"];
        }
        if (!is_null($c["bujur"]) ) {
            if ($c['bujur'] !=0 ) {
                $aset->bujur = $c["bujur"];
            }
        }
        if ((!is_null($c["lintang"])) && $c['lintang'] !=0 ) {
            $aset->lintang = $c["lintang"];
        }
        $aset->last_opname = Date('Y-m-d H:i:s');

        $aset->save();

        $data = $this::sumaryaset($ruang_id2);
        // dd($aset);

        return Helpers::Response($request, true, $data, "Data stock Opname berhasil disimpan", 200);


    }

    public function opnametanpalabel($request) 
    {

        // dd($request->all());
       
        $user = $request->get('user');
        $device = $request->get('device');
        // dd($device);

        // $barang_sub_id = $request->input('barang_sub_id');
        $ruang_id = $request->input('ruang_id');

        // $qr = $request->input('qr');


        $c = $request->all();
        $c['aset_id'] = 0;
        $c['ruang_id2'] = $ruang_id;
        $c['device_id'] = $device->id;
        $c['user_id'] = $user->id;
        $c['updated'] = Helpers::generateUpdatedAPI($user);

        $opname = T_Opname::create($c);

        if($request->hasFile('foto'))
        {
            $file = $request->foto;

            // $originalname = $file->getClientOriginalName();

            // dd($originalname);
            $fn = "opname_".$opname->id.".jpg";
            $path = $file->storeAs('aset', $fn, 'img');

        }


        $data = $this::sumaryaset($ruang_id);
        // dd($aset);

        return Helpers::Response($request, true, $data, "Data stock Opname Tanpa Label berhasil disimpan", 200);
    }

    public function detailopname($request) {
        $ruang_id = $request->input('ruang_id');
        $jenis = $request->input('jenis');

        if ($jenis == 'jumlahdata') {
            $sql = "select m_aset.id, m_aset.kode, m_barang_sub.deskripsi, opn.id as opn_id 
                    from m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id
                        left join
                    (
                    SELECT id, aset_id FROM t_opname where ruang_id2 = ".$ruang_id." and date_format(created_at, '%Y-%m-%d') ='".Date('Y-m-d')."' GROUP BY `aset_id`
                    ) as opn on opn.aset_id = m_aset.id where m_aset.ruang_id = ".$ruang_id ;
        
        } elseif ($jenis == "belumscan") {
            $sql = "select m_aset.id, m_aset.kode, m_barang_sub.deskripsi, opn.id as opn_id 
                    from m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id
                        left join
                    (
                    SELECT id, aset_id FROM t_opname where ruang_id2 = ".$ruang_id." and date_format(created_at, '%Y-%m-%d') ='".Date('Y-m-d')."' GROUP BY `aset_id`
                    ) as opn on opn.aset_id = m_aset.id where m_aset.ruang_id = ".$ruang_id." and opn.aset_id is null ";

        } elseif ($jenis == "sudahscan") {
            $sql = "select m_aset.id, m_aset.kode, m_barang_sub.deskripsi, opn.id as opn_id 
                    from m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id
                        left join
                    (
                    SELECT id, aset_id FROM t_opname where ruang_id2 = ".$ruang_id." and date_format(created_at, '%Y-%m-%d') ='".Date('Y-m-d')."' GROUP BY `aset_id`
                    ) as opn on opn.aset_id = m_aset.id where m_aset.ruang_id = ".$ruang_id." and opn.aset_id is not null ";

        } elseif ($jenis == "salahruang") {
            $sql = "select m_aset.id,m_aset.ruang_id, m_aset.kode, m_barang_sub.deskripsi, opn.id as opn_id 
                    from (m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id)
                        right join
                    (
                    SELECT id, aset_id FROM t_opname where ruang_id2 = ".$ruang_id." and ruang_id != ".$ruang_id." and date_format(created_at, '%Y-%m-%d') ='".Date('Y-m-d')."' GROUP BY `aset_id`
                    ) as opn on opn.aset_id = m_aset.id ";
        

        } elseif ($jenis == "tanpalabel") {
            $sql = "SELECT id, deskripsi, keterangan, kondisi_id FROM t_opname where aset_id = 0 and ruang_id2 = ".$ruang_id." and date_format(created_at, '%Y-%m-%d') = '".Date('Y-m-d')."'";
        

        }



        $rep = db::select(db::raw($sql)  );
        $data = Array();
        $data['list'] = $rep;
        return Helpers::Response($request, true, $data, "OK", 200);
    }

}

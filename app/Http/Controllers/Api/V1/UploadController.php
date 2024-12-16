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
use App\Model\T_Mutasi;

use App\Helpers;

use App\Model\T_Aset;
use App\Model\T_Maintenance;

use App\Http\Controllers\Admin\master\MAsetController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


use DB;
use Hash;
use ZipArchive;
use File;


class UploadController extends Controller
{


    public function transfer(Request $request)
    {


        // dd($request->all());



        //https://stackoverflow.com/questions/30212390/laravel-middleware-return-variable-to-controller
        $user = $request->get('user');

        $id = $request->input("id");

        if($request->hasFile('file')) {
            $file = $request->file('file');
            // dd($file);

            $filename = $request->file->getClientOriginalName();

            if ($id !=  substr($filename,0,strlen($id)) ) {
                return Helpers::Response($request, false, "", "Id tidak sesuai", 200);
            }

            $path = "tmp/".$filename;
            Storage::disk('local')->put($path, file_get_contents($request->file));

            // $file_path = storage_path("tmp/".$filename);
            $file_path = Storage::disk('local')->path($path);

            // dd($file_path);

            $zip = new ZipArchive;
            $res = $zip->open($file_path);
            // dd($res);
            if ($res === TRUE) {
              $zip->extractTo(Storage::disk('local')->path("extract"));
              $zip->close();

              $files = Storage::disk('local')->allFiles("extract");

                foreach ($files as $key => $value) {
                    $ext = substr($value, -4);
                    if ($ext == ".jpg") {
                        if (Storage::disk('local')->exists(str_replace("extract/transfer","public/imgopname",$value))) {

                            Storage::disk('local')->delete(str_replace("extract/transfer","public/imgopname",$value));

                        }
                        Storage::disk('local')->move($value, str_replace("extract/transfer","public/imgopname",$value)  );
                    }
                }

                $json = File::get(env("APP_STORAGE_TRANSFER").$id.".json" );
                $d = json_decode($json);

                // dd($d);

                // Opname --------------------
                $res_opname = Array();
                $res_opname[] = "CEK DATA";

                foreach ($d->opname as $key => $value) {

                    $uniq = $value->uniq_id;
                    $cek = T_Opname::where('uniq_id', $uniq)->first();

                    // $v['id']   = $value->id;
                    $v['aset_id']   = $value->aset_id;
                    $v['lokasi_id']   = $value->lokasi_id;
                    $v['ruang_id']   = $value->ruang_id;
                    $v['ruang_id2']   = $value->ruang_id2;
                    $v['tanggal']   = $value->tanggal;
                    $v['kondisi_id']   = $value->kondisi_id;
                    $v['user_id']   = $value->user_id;
                    $v['keterangan']   = $value->keterangan;
                    $v['pengguna']   = $value->pengguna;
                    $v['uniq_id']   = $value->uniq_id;
                    $v['lintang']   = $value->lintang;
                    $v['bujur']   = $value->bujur;

                    if (empty($cek)) {
                        $cek = T_Opname::create($v);
                    } else {
                        $cek->update($v);
                    }

                    $res_opname[$cek->id] = $uniq;
                }

                // Mutasi --------------------
                $resmutasi = Array();
                $resmutasi["CEK DATA"] = "CEK DATA";

                foreach ($d->mutasi as $key => $value) {

                    $aset_id = $value->aset_id;
                    $uniq = $value->uniq_id;
                    $ruang_id = $value->a_ruang;
                    $updated = $value->updated;

                    $cek = T_Mutasi::where('uniq_id', $uniq)->first();
                    if (empty($cek)) {
        
                        $aset = M_Aset::where("id", $aset_id)->first();
                        if (!empty($aset)) {

                            $c['aset_id'] = $aset->id;
                            $c['b_ruang_id'] = $aset->ruang_id;
                            $c['b_kode'] = $aset->kode;
                            
                            $g = MAsetController::GenerateKodeAset($ruang_id, $aset->barang_sub_id);
                            
                            
                            $c['a_kode'] = $g['kode'];
                            $c['a_ruang_id'] = $ruang_id;
                            $c['updated'] = $updated;
                            $c['uniq_id'] = $uniq;
                
                            // dd($c);
                
                            if ($c['b_ruang_id'] == $c['a_ruang_id']) {
                                $resmutasi[$uniq] = "Tidak ada perubahan. Tidak disimpan pada server";
                
                            } else {
                
                                $create = T_Mutasi::create($c);
                
                                $aset->kode = $g['kode'];
                                $aset->no_urut = $g['urut'];
                                $aset->ruang_id = $ruang_id;
                                $aset->save();
        
                                $resmutasi[$uniq] = "Berhasil";
                            }

                        } else {
                            $resmutasi[$uniq] = "Aset tidak ditemukan";
                        }
            

                    } else {
                        $resmutasi[$uniq] = "Sudah Ada";
                    }
                
                }
                $res = Array();
                $res['opname'] = $res_opname;
                $res['mutasi'] = $resmutasi;
                
                return Helpers::Response($request, true, $res, "OK", 200);

            } else {
                return Helpers::Response($request, false, "", "Gagal extract data", 200);
            }


        } else {
            return Helpers::Response($request, false, "", "Tidak ada data", 200);

        }
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

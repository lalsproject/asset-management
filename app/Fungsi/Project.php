<?php 
namespace App\Fungsi;

use GuzzleHttp\Client;
use App\Helpers;
use ZipArchive;


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
use App\Model\T_Aset;
use App\Model\T_Maintenance;
use App\Model\T_Label;
use App\Model\T_Penyusutan;


use DB;
use File;
use Illuminate\Support\Facades\Storage;



class Project
{
 
    public static function dataaset($id)
    {

		$sql = "select m_aset.id, m_aset.kode, concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset, m_aset.seri, coalesce(pengguna,'') as user
                	from (select * from m_aset where id='".$id."') as m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id";

		$x = DB::select(DB::raw($sql));

		if (count($x) > 0) {
			$result = $x[0];
			$result->success =true;
		} else {
			$sql = "select m_aset.id, m_aset.kode, concat(coalesce(m_barang_sub.deskripsi,''),', ',tipe) as namaaset, m_aset.seri, coalesce(pengguna,'') as user
                	from (select * from m_aset where kode='".$id."') as m_aset left join m_barang_sub on m_aset.barang_sub_id = m_barang_sub.id";

			// dd($sql);
			$x = DB::select(DB::raw($sql));

			if (count($x) > 0) {
				$result = $x[0];
				$result->success =true;
			} else {
				$result = new \stdClass();
				$result->success =false;
			}
		}

		return $result;

    }

	public static function cekpenyusutan() {
 		$sql = "select *, harga - total as selisih from 
            (
                select m_aset.id, m_aset.harga, coalesce(sum(susut.nilai),0) as total
                    from m_aset left join t_penyusutan as susut on m_aset.id = susut.aset_id
                    group by m_aset.id
            ) as x where  harga - total < -0.5 or harga - total > 0.5 ";

        $data = DB::select(DB::raw($sql));

        foreach ($data as $key => $value) {
            $x = self::susunpenyusutan($value->id);
        }
	}
    

    public static function susunpenyusutan($aset_id)
    {

		$aset = M_Aset::where('id', $aset_id)->first();

		if (is_null($aset)) {
			return "Tidak ditemukan";
		}

		$hapus = T_Penyusutan::where('aset_id', $aset_id)->delete();

		$jumlah_susut = $aset->jumlah_susut;
		if ($jumlah_susut == 0) {$jumlah_susut = 1;}
        $penyusutan = $aset->harga / $jumlah_susut;
        $pengadaan = $aset->pengadaan;

		$periode = date('Y-m-d', strtotime('1 month', strtotime( $pengadaan )));
		
        for ($i=0; $i < $jumlah_susut ; $i++) {
			$create = T_Penyusutan::create([
							'aset_id' => $aset_id,
							'nilai' => $penyusutan,
							'periode' => $periode,
						]);

			$periode = date('Y-m-d', strtotime('1 month', strtotime( $periode )));
        }

		return "OK";

    }

	public static function  backupdb() {
		$backup = Array();

		$st = DB::select(DB::raw("show tables"));
		$backup=Array();


        $x = get_object_vars($st[0]);
        $kn= Array_key_first($x);
    
        //  dd($kn);
        
		foreach ($st as $key => $value) {
            $x = get_object_vars($value);
			$table = DB::select(DB::raw("select * from ". $x[$kn]));
			$backup[$x[$kn]] = $table;
		}

		if (Storage::disk('local')->exists("temp/backup.json")) {
            Storage::disk('local')->delete("temp/backup.json");
        }

        Storage::disk('local')->put('temp/backup.json', json_encode($backup));

		$file_path = Storage::disk('local')->path("backup/"."backup_".Date("Ymd").".zip");

		if(file_exists($file_path)) {
				unlink ($file_path); 
		}

		// dd($file_path);
		$zip = new ZipArchive();
		if ($zip->open($file_path, ZipArchive::CREATE )  === TRUE) {

				// $zip->addFile("file_path","file_name");
			$t = Storage::disk('local')->path("temp/backup.json");
			// dd($t);

			// $zip->addFile($t,"backup.json") ;
			if (is_readable($t)) {
				$zip->addFile($t) ;
			}
				// or die ("ERROR: Could not add the file ");
			
			// close and save archive
			// dd($zip);

			$zip->close(); 
		} else {
			dd ("Tidak bisa membuat backup");
		}
	}


	public static function  Restoredb() {

		$bjson =  File::get(Storage::disk('local')->path("temp/backup.json")); 

		$ojson = json_decode($bjson);

		$exclude_table = [
							'migration',
							'model_has_permissions',
							'model_has_roles',
							'permissions',
							'role_has_permissions',
							'roles',
						];

		foreach ($ojson as $key => $value) {

			if (in_array($key,$exclude_table)) {

			}else {


				$x = DB::statement("TRUNCATE ".$key);
				foreach ($value as $k => $v) {
					$sql = "INSERT INTO ".$key;

					$sql .= " (";
					$sv = "(";
					foreach ($v as $k1 => $v1) {
						$sql .= "`".$k1."`,";
						$sv .= "'".$v1."',";
					}

					$sv = substr($sv,0,strlen($sv)-1);
					$sql = substr($sql,0,strlen($sql)-1);

					$sv .= ")";

					$sql .= ") VALUES ".$sv;

					db::statement($sql);

				}
			}
			// dd($value);

		}

		dd("SELESAI");

	}



	/* Upload data dengan data post di body */
    public static function makeRequest_body($requestUrl, $body, $apikey = null)
    {

		$env_url = env('SS_API_URL', 'https://jasamarga.rasata.mu.id/api').$requestUrl;

		$headers = Array();
		$headers["Content-Type"] = "application/json";
		
		$headers["username"] =  env('SS_API_USERNAME', 'API-INTRASS');
		$headers["password"] =  env('SS_API_PASSWORD', 'yerWW23ab_8&6fsf');
		
        $client = new \GuzzleHttp\Client(['headers' => $headers]);

        try {
	        $response = $client->request("POST", $env_url, [
	            'body' => $body
	        ]);

	 		$httpcode = $response->getStatusCode();


	        $response = $response->getBody()->getContents();

        } catch(\Exception $exception){
		    $response = $exception;
	 		$httpcode = 0;
		}

		$simpan = T_Apilog::create([
					"method" => "POST",
					"url" => $env_url,
					"request" => $body,
					"result" => $response,
					"httpcode" => $httpcode,
					"updated" => Helpers::generateUpdated(True, $apikey),

				]);

		return $response;

    }





}

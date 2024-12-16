<?php

namespace App\Http\Controllers\Admin\Report;

use App\Model\T_Opname;
use App\Model\M_Kondisi;
use App\Model\M_Barang;
use App\Model\M_BarangSub;
use App\Model\M_Ruang;
use App\Model\M_Aset;

use App\Helpers;

use App\Exports\OpnameExport;
use App\Exports\OpnameExportSummary;
use Maatwebsite\Excel\Facades\Excel;

use App\Mail\ReportOpname;
use Illuminate\Support\Facades\Mail;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;
use Form;
// use Carbon\Carbon;
use ZipArchive;


class OpnameController extends Controller
{

    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('report_opname'))
        {
            return redirect()->route('home');
        }

        $kondisi = M_Kondisi::pluck('deskripsi','id');
        $ruang_status = [1 => "Sesuai Ruang", 2 => "Berbeda Ruang"];

        // $status = Array();
        // $status[1] = "Aktif";
        // $status[0] = "Non Aktif";
        $table = $this::datatable(null);

        return view('admin.report.opname.index', compact('table', 'kondisi', 'ruang_status'));
    }

    // public function store(Request $request)
    // {

    // }


    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {
        //http://maps.google.com/maps?q=loc:-6.26577,106.54324

        $form = "<button class='btn btn-xs btn-info' onclick=\"Gambar('".$data->uniq_id."')\">Gambar</button> 
                <button class='btn btn-xs btn-warning' onclick=\"Maps('".$data->lintang."','".$data->bujur."',)\">Maps</button> ";
        return $form;
    }

    function GenerateDatatable($request) {

        // dd($request->all());

        if (!empty($request)) {
            $kode = $request->input('filter_kode');
            $tanggal = $request->input('filter_tanggal');
            $kondisi = $request->input('filter_kondisi');
            $ruang_status = $request->input('filter_ruang_status');
        }

        if (!empty($tanggal) && strlen($tanggal) > 25) {
            $aw = substr($tanggal,6,4)."-".substr($tanggal,3,2)."-".substr($tanggal,0,2);
            $ak = substr($tanggal,24,4)."-".substr($tanggal,21,2)."-".substr($tanggal,18,2);
        } else {
            $aw = Date('Y-m-d', strtotime("-1 month"));
            $ak = Date('Y-m-d');
        }

        $opname = T_Opname::where('created_at','>=', $aw.' 00:00:00')->where('created_at','<=', $ak.' 23:59:59');

        if (!empty($kode)) {
            $aset_id = M_Aset::where('kode','like',"%".$kode."%")->pluck('id')->toarray();
            $opname = $opname->wherein('aset_id', $aset_id);
        }
        if (!empty($kondisi)) {
            $opname = $opname->wherein('kondisi_id', $kondisi);
        }
        if (!empty($ruang_status)) {
            $sql = "";
            if (in_array(1, $ruang_status )) {
                $sql .= "ruang_id = ruang_id2 ";
            }

            if (in_array(2, $ruang_status )) {
                if (strlen($sql) > 5) {

                    $sql .= " or ";
                }
                $sql .= " ruang_id != ruang_id2  ";
            }


            $opname = $opname->whereraw($sql);
        }

        return $opname;

    }

    public function datatable($request)
    {
        if (!empty($request)) {
            $take = $request->input('take');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;

        $kondisi = M_Kondisi::pluck('deskripsi','id');
        $kondisi_warna = M_Kondisi::pluck('warna','id');

        $opname = $this::GenerateDatatable($request);

        $count = count($opname->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $opname = $opname->skip($skip)->take($take)->get();

        $a_barang = M_Barang::pluck("deskripsi","id")->toArray();
        $a_barangsub = M_BarangSub::pluck("deskripsi","id")->toArray();
        $a_ruang = M_Ruang::pluck("deskripsi","id")->toArray();


        $table = "<table class='table table-hover text-nowrap' width='100%'>
                  <thead>
                    <tr>
                      <th>Tanggal</th>
                      <th>Kode</th>
                      <th>Barang</th>
                      <th>Sub Barang</th>
                      <th>Tipe / Seri</th>
                      <th>Keterangan</th>
                      <th>Pengguna</th>
                      <th>Kondisi</th>
                      <th>Ruang Seharusnya</th>
                      <th>Ruang Opname</th>
                      <th>Updated</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        foreach ($opname as $detail) {

            $aset = $detail->aset;
            if (empty($aset)) {
                $kode = "";
                $tipe = "";
                $seri = "";

                $barang = "";
                $sub_barang = "";
            } else {
                $kode = $aset->kode;
                $tipe = $aset->tipe;
                $seri = $aset->seri;

                $sub_barang = "";
                $barang = "";

                if (array_key_exists($aset->barang_id, $a_barang)) {
                    $barang = $a_barang[$aset->barang_id];
                }
                if (array_key_exists($aset->barang_sub_id, $a_barangsub)) {
                    $sub_barang = $a_barangsub[$aset->barang_sub_id];
                }
            }

            if ($detail->aset_id == 0) {
                $kode = '-- TANPA LABEL --';
            }

            $table .= "<tr>
                            <td align='center'>".date_format($detail->created_at,"d-m-Y")."</td>
                            <td align='left'>".$kode."</td>
                            <td align='left'>".$barang."</td>
                            <td align='left'>".$sub_barang."</td>
                            <td align='left'>".$tipe." - ".$seri."</td>
                            <td align='left'>".$detail->keterangan."</td>
                            <td align='left'>".$detail->pengguna."</td>

                            <td align='center'>".$this::kondisi($detail, $kondisi, $kondisi_warna)."</td>
                            <td align='left'>".$this::ruang($detail, $a_ruang)."</td>
                            <td align='left'>".$this::ruang2($detail, $a_ruang)."</td>
                            <td align='left'>".$detail->updated."</td>
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

    public function kondisi($detail, $kondisi, $kondisi_warna) {

        try
        {
            $kondisi_id = $detail->kondisi_id;
            if (is_null($kondisi_id)) {
                return "-";
            }

            // dd(isset($kondisi[$kondisi_id]));

            if (isset($kondisi[$kondisi_id])) {
                $rv = Helpers::WarnaLabel($kondisi[$kondisi_id], $kondisi_warna[$kondisi_id]);
                return $rv;

            } else {
                $rv = Helpers::WarnaLabel("-", "ABU-ABU");
                return $rv;

            }

        } catch(\Exception $exception){
            return "E";
        }

    }

    public function ruang($detail, $a_ruang) {

        try
        {
            $ruang_id = $detail->ruang_id;

            $des = "";
            if (array_key_exists($ruang_id, $a_ruang)) {
                $des = $a_ruang[$ruang_id];
            }

            return $des;


        } catch(\Exception $exception){
            return "E";
        }

    }

    public function ruang2($detail, $a_ruang) {

        try
        {
            $ruang_id = $detail->ruang_id;
            $ruang_id2 = $detail->ruang_id2;

            $des = "";
            if (array_key_exists($ruang_id2, $a_ruang)) {
                $des = $a_ruang[$ruang_id2];
            }


            if ($ruang_id == $ruang_id2) {
                return $des;
            } else {
                return  Helpers::WarnaLabel($des, "MERAH");
            }


        } catch(\Exception $exception){
            return "E";
        }

    }


    public function image($uniq_id) {
        $filename = $uniq_id.".jpg";
        if (Storage::disk('local')->exists("public/imgopname/".$filename)) {
            $path = "public/imgopname/".$filename; // get the DB field

            return response(Storage::disk('local')->get($path), 200)
                ->header( 'Content-Type', Storage::mimeType($path) );
        } else {
            return Helpers::responseJson(false, "", "File tidak ditemukan" );
        }
    }



    public function exportexcel(Request $request) {
        // dd($request->all());

        $tipe = $request->input("tipe");

        $kondisi = M_Kondisi::pluck('deskripsi','id');
        $ruang = M_Ruang::pluck('deskripsi','id');
        $opname = $this::GenerateDatatable($request);

        $data = [ 'kondisi' => $kondisi,
                'ruang' => $ruang,
                'opname' => $opname->get()
                    ];

        if ($tipe == "Export Excel") {
            return Excel::download(new OpnameExport($data), 'OpnameAset.xlsx');

        } elseif ($tipe == "Export Excel Summary") {

            return $this::ExportExcelSummary($opname, $kondisi, $ruang);
            


        } else {
            // Email
            Excel::store(new OpnameExport($data), 'OpnameAset.xlsx', 'local');    

            $file_path = Storage::disk('local')->path("opnameaset.zip");

            if(file_exists($file_path)) {
				unlink ($file_path); 
		    }

            $zip = new ZipArchive();
            if ($zip->open($file_path, ZipArchive::CREATE )  === TRUE) {
    
                $t = Storage::disk('local')->path("OpnameAset.xlsx");
                if (is_readable($t)) {
                    $zip->addFile($t, "OpnameAset.xlsx") ;
                }
    
                $zip->close(); 

            } else {
                return Helpers::responseJson(false, "", "Gagal membuat file attachment" );
            }
            
            $tujuan = $request->input("tujuan");

            $mailto = explode(',', $tujuan);

            // dd($mailto);
            $x = Mail::to($mailto)->send(new ReportOpname($request->all()));

            return Helpers::responseJson(true, "", "Email telah dikirimkan" );
        }
    }

    function ExportExcelSummary($opname, $kondisi, $ruang) {

        // dd($opname->get());
        $a_aset = $opname->groupby('aset_id')->pluck('aset_id');

        $aset = M_Aset::Wherein('id', $a_aset)->get();

        $data = Array();
        $data['aset'] = $aset;
        $data['kondisi'] = $kondisi;
        $data['ruang'] = $ruang;



        return Excel::download(new OpnameExportSummary($data), 'OpnameAsetSummary.xlsx');




        // dd($request->all());

    }

}

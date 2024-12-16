<?php

namespace App\Http\Controllers\Admin\Master;

use App\Model\M_Status;
use App\Helpers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Form;
use Carbon\Carbon;

class MStatusController extends Controller
{
    public function index()
    {
        if(!Auth()->user()->hasAnyPermission('master_status'))
        {
            return redirect()->route('admin.');
        }

        $xwarna = Helpers::warna();
        $warna = Array();

        foreach ($xwarna as $key => $value) {
            $warna[$key] = $key;
        }

        $table = $this::datatable(null);

        return view('admin.master.status.index', compact('table','warna'));
    }

    public function store(Request $request)
    { 
        $result = new \stdClass();
        if(!Auth()->user()->hasAnyPermission('master_status'))
        {
            $pesan = "Anda tidak memiliki hak akses untuk master";
            return Helpers::responseJson($pesan,"" );
        }

        DB::beginTransaction();

        try {        

            $id = $request->input('id');
            $deskripsi = $request->input('deskripsi');
            $status = M_Status::findOrFail($id);
            $status->update([
                            "deskripsi" => $deskripsi,
                            "warna" => $request->input('warna'),
                            "updated" => Helpers::generateUpdated(),
                        ]);
            $pesan = "Data berhasil diupdate";
            DB::commit();

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::responseJson(false, $exception, "Terdapat kegagalan transaksi" );
        }

        $table = $this::datatable($request, $status);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function loaddatatable(Request $request) {

        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public static function formAction($data)
    {

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.",'".$data->deskripsi."','".$data->warna."')\">Edit</button> ";
        return $form;
    }

    public function datatable($request, $dataproses = null)
    {

        $warna = Helpers::warna();

        if (!empty($request)) {
            $take = $request->input('take');
            $kriteria = $request->input('filter_kriteria');
            $halaman = $request->input('halaman');
        }

        if (empty($halaman)) {$halaman = 1;}
        if (empty($take)) {$take = 30;}
        $skip = ($halaman - 1) * $take;
        $status = M_Status::where('id','!=', '0');

        if(!empty($kriteria)){
            $status = $status->where('deskripsi','LIKE','%'.$kriteria.'%');
        }

        $count = count($status->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $status = $status->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th>Inisial</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='left'>".$dataproses->deskripsi_init."</td>
                      <td align='left'>". Helpers::WarnaLabel($dataproses->deskripsi, $dataproses->warna)."</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($status as $detail) {
            if ($detail->id != $dataproses_id) {

                $table .= "<tr>
                            <td align='left'>".$detail->deskripsi_init."</td>
                            <td align='left'>".Helpers::WarnaLabel($detail->deskripsi, $detail->warna)."</td>
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

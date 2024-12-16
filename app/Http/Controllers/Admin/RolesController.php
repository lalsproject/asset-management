<?php

namespace App\Http\Controllers\Admin;

use App\Helpers;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('user_security')) {
            return abort(401);
        }

        $table = $this::datatable(null);

        return view('admin.roles.index', compact('table'));
    }

    public function store(Request $request)
    {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $simpan = $request->input('simpan');
        $name = $request->input('name');
        $name = str_replace(' ', '_', $name);

        if ($simpan == 'baru') {
            $role = Role::create([
                        'name' => $name
                    ]);
            $pesan = "Data berhasil disimpan";
        } else {
            $id = $request->input('id');
            $role = Role::findOrFail($id);
            $role->update([
                        'name' => $name
                    ]);
            $pesan = "Data berhasil diupdate";
        }

        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->syncPermissions($permissions);

        $table = $this::datatable($request, $role);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function edit($id)
    {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $role = Role::findOrFail($id);
        if (empty($role)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $permissions = $role->permissions()->pluck('name');

        $result = array(
            'role' => $role,
            'permissions' => $permissions,
        );        
        return Helpers::responseJson(true, $result, "OK" );
    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        try {

            $role = Role::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($role)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $role->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();

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
        $roles = Role::where('id','!=','0');

        if(!empty($kriteria)){
            $roles = $roles->where('name','LIKE','%'.$kriteria.'%');
        }

        $count = count($roles->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $roles = $roles->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                        <th style=\"text-align:center;\">
                            <input type=\"checkbox\" id=\"PilihSemuaData\" onclick=\"PilihSemuaData()\">
                            </th>
                      <th>Deskripsi</th>
                      <th>Permission</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {
            $p = $dataproses->permissions()->pluck('name');
            $table .= "<tr>
                      <td align='center'>".Helpers::formCheckbox($dataproses->id)."</td>
                      <td align='left'>".$dataproses->name."</td>
                      <td align='left'>";

            foreach ($p as $value) {
                $table .="<label class='btn btn-xs btn-info'>".$value."</label> ";
            }

            $table .= "</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($roles as $role) {
            if ($role->id != $dataproses_id) {
                $p = $role->permissions()->pluck('name');

                $table .= "<tr>
                          <td align='center'>".Helpers::formCheckbox($role->id)."</td>
                          <td align='left'>".$role->name."</td>
                          <td align='left'>";

                foreach ($p as $value) {
                    $table .="<label class='btn btn-xs btn-info'>".$value."</label> ";
                }

                $table .= "</td>
                          <td align='center'>".$this::formAction($role)."</td>
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


    public static function formAction($data)
    {

        $form = "<button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.")\">Edit</button>
                <button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        return $form;
    }



}

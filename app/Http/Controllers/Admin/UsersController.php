<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Auth;
use DB;
use App\Helpers;
use App\Fungsi\Guzzle;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TmUsers;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('user_security')) {
            return abort(401);
        }

        $table = $this::datatable(null);
        $roles = Role::all()->pluck('name');

        return view('admin.users.index', compact('table','roles'));
    }
 
    // public function sync(Request $request)
    // {

        
    //     $queryParams = Array(); //$request->only('user_fullname','user_email', 'user_phone');
    //     $r = Guzzle::makeRequest_hriss("POST", "/v1/user/list", $queryParams );

    //     $hasilapi = json_decode($r);

    //     if ($hasilapi->success) {

    //         $sql = "UPDATE users set name='<-- HAPUS -->'";
    //         DB::statement($sql);

    //         foreach ($hasilapi->data as $key => $value) {
    //             $this::IsiUser($value);
    //         }

    //         $sql = "DELETE FROM users where name='<-- HAPUS -->'";
    //         DB::statement($sql);


    //         $table = $this::datatable($request);
    //         return Helpers::responseJson(true, $table, "OK" );
    //     } else {
    //         return Helpers::responseJson(false, "", "Gagal Sync" );

    //     }
    // }

    // public static function IsiUser($data) {
    //     $cek = User::where('id', $data->id)->first();
    //     if (is_null($cek)) {
    //         $cek = User::Create([
    //             "id" => $data->id,
    //             "name" => $data->name,
    //             "email" => $data->email,
    //             "password" => Helpers::rndstr(15),
    //         ]);

    //     } else {
    //         $cek->name =  $data->name;
    //         $cek->email =  $data->email;
    //         $cek->save();
    //     }

    // }

    public function store(Request $request)
    {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        // dd($request->all());
        $simpan = $request->input('simpan');
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');

        try {
            if ($simpan == 'baru') {

                $user = User::Where('email',$email)->first();
                if (!empty($user)) {
                    return Helpers::responseJson(false, "", "Email sudah digunakan oleh user lain" );
                }

                $user = User::create(['email'=>$email, 'name'=>$name, 'password' =>$password]);
                $pesan = "Data berhasil disimpan";
            } else {
                $id = $request->input('id');
                $user = User::Where('email',$email)
                            ->Where('id','!=', $id)
                            ->first();
                if (!empty($user)) {
                    return Helpers::responseJson(false, "", "Email sudah digunakan oleh user lain" );
                }

                $user = User::findOrFail($id);
                $user->update(['email'=>$email, 'name'=>$name]);
                
                if (trim($password) != "" ) {
                    $user->update(['password'=>$password]);
                }  

                $pesan = "Data berhasil diupdate";
            }

        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }


        $table = $this::datatable($request, $user);
        $table['pesan'] = $pesan;

        return Helpers::responseJson(true, $table, "OK" );
    }

    public function edit($id)
    {
         if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $user = User::findOrFail($id);
        if (empty($user)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        return Helpers::responseJson(true, $user, "OK" );

    }

    public function hapusdata(Request $request)
    {

        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $id = $request->input('id');
        try {

            $user = User::findOrFail($id);
        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        if (empty($user)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $user->delete();
        
        $table = $this::datatable($request);
        return Helpers::responseJson(true, $table, "OK" );
    }

    public function hapusdipilih(Request $request)
    {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

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

        $form = "<button class='btn btn-xs btn-warning' id='btnRole' onclick=\"Role(".$data->id.")\">Role</button>  
                <button class='btn btn-xs btn-success' id='btnPermission' onclick=\"Permission(".$data->id.")\">Permission</button> 
                <button class='btn btn-xs btn-info' id='btnEdit' onclick=\"EditData(".$data->id.")\">Edit</button> 
                <button class='btn btn-xs btn-danger' id='btnHapus' onclick=\"HapusData(".$data->id.")\">Hapus</button> ";
        return $form;
    }


    public function rolesuser($user) {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $user = User::findOrFail($user);
        if (empty($user)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }


        $roles = $user->getRoleNames();

        $result = array(
            'name' => $user->name,
            'roles' => $roles,
        );        

        return Helpers::responseJson(true, $result, "OK" );
    }

    public function simpanrolesuser(Request $request) {

        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $user_id = $request->input('user_id');

        try {
            $user = User::findOrFail($user_id);

            $role = $request->input('role') ? $request->input('role') : [];

            $user->syncRoles($role);

        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $table = $this::datatable($request, $user);
        return Helpers::responseJson(true, $table, "OK" );
    }


    public function permissionsuser($user) {
        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }

        $user = User::findOrFail($user);
        if (empty($user)) {
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $permissions = $user->getDirectPermissions()->pluck("name");

        $result = array(
            'name' => $user->name,
            'permissions' => $permissions,
        );        

        return Helpers::responseJson(true, $result, "OK" );
    }


    public function simpanpermissionsuser(Request $request) {


        if (! Gate::allows('user_security')) {
            return Helpers::responseJson(false, "", "Anda tidak berhak mengakses" );
        }
        $user_id = $request->input('user_id');

        try {
            $user = User::findOrFail($user_id);

            $permission = $request->input('permission') ? $request->input('permission') : [];

            $user->syncPermissions($permission);

        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Data tidak ditemukan" );
        }

        $table = $this::datatable($request, $user);
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
        $users = User::where('id','!=', '0');

        if(!empty($kriteria)){
            $users = $users->where('name','LIKE','%'.$kriteria.'%');
        }

        $count = count($users->get());
        $pagination = Helpers::Pagination("LoadData", $count, $take, $halaman);
        $users = $users->skip($skip)->take($take)->get();

        $table = "<table class='table table-hover'>
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Special Permission</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>";

        if (!empty($dataproses)) {

            $table .= "<tr>
                      <td align='left'>".$dataproses->name."</td>
                      <td align='left'>".$dataproses->email."</td>
                      <td align='left'>";

            $roles = $dataproses->getRoleNames();
            foreach ($roles as $value) {
                $table .="<label class='btn btn-xs btn-warning'>".$value."</label> ";
            }

            $table .= "</td><td>";
            $permissions = $dataproses->getDirectPermissions()->pluck("name");

            foreach ($permissions as $value) {
                $table .="<label class='btn btn-xs btn-success'>".$value."</label> ";
            }

            $table .= "</td>
                      <td align='center'>".$this::formAction($dataproses)."</td>
                    </tr>
                ";
            $dataproses_id = $dataproses->id;
        } else {
            $dataproses_id = 0;
        }

        foreach ($users as $user) {
            if ($user->id != $dataproses_id) {

                $table .= "<tr>
                          <td align='left'>".$user->name."</td>
                          <td align='left'>".$user->email."</td>
                          <td align='left'>";

                $roles = $user->getRoleNames();
                foreach ($roles as $value) {
                    $table .="<label class='btn btn-xs btn-warning'>".$value."</label> ";
                }

                $table .= "</td><td>";
                $permissions = $user->getDirectPermissions()->pluck("name");

                foreach ($permissions as $value) {
                    $table .="<label class='btn btn-xs btn-success'>".$value."</label> ";
                }

                $table .= "</td>
                          <td align='center'>".$this::formAction($user)."</td>
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

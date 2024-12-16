<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use DB;
use Hash;
use App\Helpers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TmUsers;

class ProfileController extends Controller
{
  /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $userid = Auth::user()->id;
        $user = User::findOrFail($userid);

        return view('auth.profile', compact('user'));

    }

    public function gantipassword(Request $request) {
        $user = Auth::getUser();
        if (Hash::check($request->get('password_lama'), $user->password)) {
            $user->password = $request->get('password');
            $user->save();

            return Helpers::responseJson(True, "", "Password berhasil diupdate" );

            //return redirect($this->redirectTo)->with('success', 'Password change successfully!');
        } else {
            return Helpers::responseJson(false, "", "Password Lama Tidak sesuai" );
        }

    }


}

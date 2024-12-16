<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Hash;
use Auth;
use App\Helpers;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'name', 
        'email', 
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


        /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }


    public function adminlte_image()
    {
        $id = Auth::user()->id;

        if (file_exists( public_path() . '/img/avatar/Avatar_' . $id . '.jpg')) {
            return url('/img/avatar/Avatar_' . $id . '.jpg?'.Helpers::rndstr(6));
        } else {
            return url('/img/avatar/noavatar.png?'.Helpers::rndstr(6));
        }     

        //return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return Auth::user()->email;
    }

    public function adminlte_profile_url()
    {
        return 'profile';
    }
}

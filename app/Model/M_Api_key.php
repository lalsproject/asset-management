<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Api_key extends Model{
    protected $table = 't_key';
    protected $fillable = [ 'deskripsi', 
                            'token', 
                            'aktif', 
                            'updated', 
                        ];

}

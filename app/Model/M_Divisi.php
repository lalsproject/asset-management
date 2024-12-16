<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Divisi extends Model{
    protected $table = 'm_divisi';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];


}

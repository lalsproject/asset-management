<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Kondisi extends Model{
    protected $table = 'm_kondisi';
    protected $fillable = [ 'deskripsi', 
                            'uraian', 
                            'warna', 
                            'updated', 
                        ];


}

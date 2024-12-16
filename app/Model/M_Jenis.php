<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Jenis extends Model{
    protected $table = 'm_jenis';
    protected $fillable = [ 'deskripsi', 
                            'deskripsi_init', 
                            'warna', 
                            'updated', 
                        ];


}

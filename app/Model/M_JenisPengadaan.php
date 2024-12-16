<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_JenisPengadaan extends Model{
    protected $table = 'm_jenis_pengadaan';
    protected $fillable = [ 'kode', 
                            'deskripsi', 
                            'updated', 
                        ];


}

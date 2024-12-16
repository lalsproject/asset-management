<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Lokasi extends Model{
    protected $table = 'm_lokasi';
    protected $fillable = [ 'kode', 
                            'deskripsi', 
                            'updated', 
                        ];


    public function has_ruang(){
        return $this->hasMany(M_Ruang::class,'lokasi_id');
    }

}

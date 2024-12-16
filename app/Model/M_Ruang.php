<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Ruang extends Model{
    protected $table = 'm_ruang';
    protected $fillable = [ 'lokasi_id', 
                            'kode', 
                            'deskripsi', 
                            'updated', 
                        ];


    public function lokasi(){
    	return $this->belongsTo(M_Lokasi::class,'lokasi_id');
    }



}

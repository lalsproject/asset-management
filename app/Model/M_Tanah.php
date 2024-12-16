<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Tanah extends Model{
    protected $table = 'm_tanah';
    protected $fillable = [ 'aset_id', 
                            'deskripsi', 
                            'alamat', 
                            'luas_tanah', 
                            'luas_bangunan', 
                            'no_sertifikat', 
                            'jenis_sertifikat', 
                            'keterangan', 
                            'updated', 
                        ];

    public function aset(){
    	return $this->belongsTo(M_Aset::class,'aset_id');
    }

}

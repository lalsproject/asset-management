<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Mutasi extends Model{
    protected $table = 't_aset_mutasi';
    protected $fillable = [ 
                            'aset_id', 
                            'b_ruang_id', 
                            'a_ruang_id', 
                            'b_kode', 
                            'a_kode', 
                            'updated',
                            'uniq_id', 

                ];

    public function aset(){
        return $this->belongsTo(M_Aset::class,'aset_id');
    }

}

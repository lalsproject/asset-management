<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_BarangSub extends Model{
    protected $table = 'm_barang_sub';
    protected $fillable = [ 'satuan_id', 
                            'barang_id', 
                            'kode', 
                            'deskripsi', 
                            'updated', 
                        ];


    public function barang(){
    	return $this->belongsTo(M_Barang::class,'barang_id');
    }

    public function satuan(){
    	return $this->belongsTo(M_Satuan::class,'satuan_id');
    }

}

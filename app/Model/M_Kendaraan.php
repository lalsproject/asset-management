<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Kendaraan extends Model{
    protected $table = 'm_kendaraan';
    protected $fillable = [ 'aset_id', 
                            'merk_type', 
                            'no_polisi', 
                            'no_bpkb', 
                            'no_mesin', 
                            'no_rangka', 
                            'tahun_pembuatan', 
                            'tanggal_pembelian', 
                            'berlaku_stnk', 
                            'remind_stnk', 
                            'asal', 
                            'keterangan', 
                            'updated', 
                        ];

    public function aset(){
    	return $this->belongsTo(M_Aset::class,'aset_id');
    }

}

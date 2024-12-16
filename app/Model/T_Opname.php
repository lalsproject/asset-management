<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Opname extends Model{
    protected $table = 't_opname';
    protected $fillable = [ 'aset_id', 
                            'kondisi_id', 
                            'device_id', 
                            'lokasi_id', 
                            'ruang_id', 
                            'ruang_id2', 
                            'tanggal', 
                            'keterangan', 
                            'pengguna', 
                            'lintang', 
                            'bujur', 
                            'uniq_id', 
                            'user_id', 
                            'updated', 
                        ];


    // public function barang_sub(){
    // 	return $this->belongsTo(M_BarangSub::class,'barang_sub_id');
    // }

    public function ruang(){
    	return $this->belongsTo(M_Ruang::class,'ruang_id');
    }

    // public function jenis_pengadaan(){
    // 	return $this->belongsTo(M_JenisPengadaan::class,'jenis_pengadaan_id');
    // }

    // public function divisi(){
    // 	return $this->belongsTo(M_Divisi::class,'divisi_id');
    // }

    // public function status(){
    // 	return $this->belongsTo(M_Status::class,'status_id');
    // }

    public function aset(){
    	return $this->belongsTo(M_Aset::class,'aset_id');
    }

    public function kondisi(){
    	return $this->belongsTo(M_Kondisi::class,'kondisi_id');
    }


    public function ruang2(){
    	return $this->belongsTo(M_Ruang::class,'ruang_id2');
    }


}

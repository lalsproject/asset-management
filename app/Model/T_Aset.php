<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Aset extends Model{
    protected $table = 't_aset';
    protected $fillable = [ 'aset_id', 
                            'kode', 
                            'barang_sub_id', 
                            'ruang_id', 
                            'jenis_pengadaan_id', 
                            'divisi_id', 
                            'status_id', 
                            'jenis_id', 
                            'kondisi_id', 
                            'no_urut', 
                            'tipe', 
                            'seri', 
                            'pengadaan', 
                            'tgl_input', 
                            'jumlah_susut', 
                            'harga', 
                            'keterangan', 
                            'supplier', 
                            'pengguna', 
                            'last_opname', 
                            'lintang', 
                            'bujur', 
                            'updated', 
                        ];


    public function barang_sub(){
    	return $this->belongsTo(M_BarangSub::class,'barang_sub_id');
    }

    public function ruang(){
    	return $this->belongsTo(M_Ruang::class,'ruang_id');
    }

    public function jenis_pengadaan(){
    	return $this->belongsTo(M_JenisPengadaan::class,'jenis_pengadaan_id');
    }

    public function divisi(){
    	return $this->belongsTo(M_Divisi::class,'divisi_id');
    }

    public function status(){
    	return $this->belongsTo(M_Status::class,'status_id');
    }

    public function jenis(){
    	return $this->belongsTo(M_Jenis::class,'jenis_id');
    }

    public function kondisi(){
    	return $this->belongsTo(M_Kondisi::class,'kondisi_id');
    }



}

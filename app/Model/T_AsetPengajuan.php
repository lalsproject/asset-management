<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_AsetPengajuan extends Model{
    protected $table = 't_aset_pengajuan';
    protected $fillable = [ 'pengajuan_id', 
                            'cabang', 
                            'cabang_nama', 
                            'produk_id', 
                            'harga', 
                            'deskripsi', 
                            'created', 
                            'approved', 
                            'status', 
                            'aset_id', 
                            'keterangan', 
                            'keterangan2', 
                            'updated', 
                        ];
}

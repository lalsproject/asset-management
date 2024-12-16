<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Maintenance extends Model{
    protected $table = 't_maintenance';
    protected $fillable = [ 'aset_id', 
                            'jenis_maintenance_id', 
                            'keterangan', 
                            'vendor', 
                            'harga', 
                            'updated', 
                        ];

    public function aset(){
        return $this->belongsTo(M_Aset::class,'aset_id');
    }

    public function jenis_maintenance(){
        return $this->belongsTo(M_JenisMaintenance::class,'jenis_maintenance_id');
    }

}

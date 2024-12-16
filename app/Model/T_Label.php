<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Label extends Model{
    protected $table = 't_label';
    protected $fillable = [ 'aset_id', 
                            'status', 
                            'updated', 
                            'tercetak', 
                        ];

    public function aset(){
        return $this->belongsTo(M_Aset::class,'aset_id');
    }

}

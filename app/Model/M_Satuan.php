<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Satuan extends Model{
    protected $table = 'm_satuan';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];


}

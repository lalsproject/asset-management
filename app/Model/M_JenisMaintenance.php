<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_JenisMaintenance extends Model{
    protected $table = 'm_jenis_maintenance';
    protected $fillable = [ 'deskripsi', 
                            'updated', 
                        ];


}

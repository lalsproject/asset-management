<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Status extends Model{
    protected $table = 'm_status';
    protected $fillable = [ 'deskripsi', 
                            'deskripsi_init', 
                            'warna', 
                            'updated', 
                        ];


}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Barang extends Model{
    protected $table = 'm_barang';
    protected $fillable = [ 'kode', 
                            'deskripsi', 
                            'updated', 
                        ];


 



}

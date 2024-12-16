<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Penyusutan extends Model{
    protected $table = 't_penyusutan';
    protected $fillable = [ 'aset_id', 
                            'periode', 
                            'nilai', 
                        ];


}

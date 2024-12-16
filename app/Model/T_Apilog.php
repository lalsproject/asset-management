<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class T_Apilog extends Model{
    protected $table = 't_apilog';
    protected $fillable = [ 'url', 
                            'request', 
                            'result', 
                            'updated', 
                        ];
}

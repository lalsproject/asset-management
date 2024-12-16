<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Data extends Model{
    protected $table = 'data';
    protected $fillable = [ 'pkey', 
                            'nstring', 
                            'ninteger', 
                            'ndouble', 
                            'ndate', 
                            'updated', 
                        ];


}

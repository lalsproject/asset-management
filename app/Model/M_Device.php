<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class M_Device extends Model{
    protected $table = 'm_device';
    protected $fillable = [ 'deskripsi', 
                            'hardware_id', 
                            'token', 
                            'otp', 
                            'updated', 
                        ];
}

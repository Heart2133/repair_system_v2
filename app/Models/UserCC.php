<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCC extends Model
{
    protected $table = 'u_cc';

    protected $fillable = [
        'id',
        'user_id',
        'section_cc_id',
        'request_id',
    ];
}

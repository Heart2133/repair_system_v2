<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model
{
    protected $table = 'u_department';

    protected $fillable = [
        'user_id',
        'department_id',
        'department',
    ];
}

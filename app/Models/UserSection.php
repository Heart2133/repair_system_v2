<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSection extends Model
{
    protected $table = 'u_section';

    protected $fillable = [
        'id',
        'u_id',
        'section_id',
        'section',
    ];
}

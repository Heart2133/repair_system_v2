<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Section extends Model
{
    protected $table = 'ho_sections'; // สำคัญมาก

    protected $fillable = [
        'section_en',
        'section_th'
    ];

    public function users()
{
    return $this->belongsToMany(
        User::class,
        'u_section',
        'section',
        'u_id'
    );
}
}


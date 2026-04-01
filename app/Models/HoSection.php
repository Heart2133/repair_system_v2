<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSection extends Model
{
    protected $table = 'ho_sections';

public function announcements()
{
    return $this->belongsToMany(
        Announcement::class,
        'announcement_section',
        'section_id',
        'announcement_id'
    );
}
}

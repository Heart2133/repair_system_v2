<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class JobPosition extends Model
{
    protected $table = 'job_positions';

    protected $fillable = [
        'name',
        'section_id',
        'user_id'
    ];

    public function getNameLabelAttribute()
{
    $labels = [
        'requester' => 'พนักงานผู้ร้องขอ',
        'head_requester' => 'หัวหน้าแผนกของผู้ร้อง',
        'executive' => 'ผู้บริหาร',
        'head_owner' => 'หัวหน้าแผนกที่รับผิดชอบ',
        'owner_staff' => 'ผู้ปฏิบัติงานภายในแผนกเจ้าของงาน',
    ];

    return $labels[$this->name] ?? $this->name;
}

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

     public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

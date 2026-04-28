<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
use App\Models\DamageReportItem;
use App\Models\DamageReportEmployee;

class DamageReport extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_code', 'branch_code');
    }

    public function items()
    {
        return $this->hasMany(DamageReportItem::class, 'damage_report_id');
    }

    public function employees()
    {
        return $this->hasMany(DamageReportEmployee::class, 'damage_report_id');
    }
}

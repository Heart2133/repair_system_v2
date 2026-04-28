<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageReportEmployee extends Model
{
    protected $table = 'damage_report_employees';

    protected $fillable = [
        'damage_report_id',
        'emp_code',
        'emp_name',
        'percent',
        'amount'
    ];
}

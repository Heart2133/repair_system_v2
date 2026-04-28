<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageReportItem extends Model
{
    protected $table = 'damage_report_items';

    protected $fillable = [
        'damage_report_id',
        'product_code',
        'product_name',
        'price',
        'qty',
        'total'
    ];
}

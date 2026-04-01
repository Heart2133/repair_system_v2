<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branchs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'branch_code',
        'sap_code',
        'branch_desc',
        'branch_desc_en',
        'line_id',
        'branch_active',
        'company_name',
        'company_name_en',
        'company_addr',
        'company_addr_en',
        'company_tel',
        'company_fax'
    ];
}

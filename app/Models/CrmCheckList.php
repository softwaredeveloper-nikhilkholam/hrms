<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmCheckList extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_check_lists';

    /**
     * The attributes that are mass assignable.
     *
     * This array protects against mass assignment vulnerabilities.
     * Only the fields listed here can be filled using methods like `create()` or `update()`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empId',
        'taskId',
        'forDate',
        'status',
        'remark',
        'active',
        'updated_by',
        // 'created_by' is also a common field to add here if you track who creates a record.
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitorModel extends Model
{
    protected $table            = 'visitors';
    protected $primaryKey       = 'visitors_id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    /* ===============================
       Allowed Columns (IMPORTANT)
    =============================== */
    protected $allowedFields = [
        'visitors_code',
        'chapter_code',
        'user_code',
        'member_id',
        'visitor_name',
        'company',
        'phone',
        'email',
        'date_visited',
        'status',
        'note',
        'created_at',
        'updated_at'
    ];

    /* ===============================
       Auto Dates
    =============================== */
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $dateFormat = 'datetime';
}

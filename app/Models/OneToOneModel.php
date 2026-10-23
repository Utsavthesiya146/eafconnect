<?php

namespace App\Models;

use CodeIgniter\Model;

class OneToOneModel extends Model
{
    protected $table      = 'one_to_ones';
    protected $primaryKey = 'f2f_id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'f2f_code',
        'from_user',
        'to_user',
        'date',
        'location',
        'topics',
        'status',
        'note',
        'created_at',
        'updated_at'
    ];
}

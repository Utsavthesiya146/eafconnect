<?php

namespace App\Models;

use CodeIgniter\Model;

class TyfcbModel extends Model
{
    protected $table = 'tyfcb';
    protected $primaryKey = 'mitb_id';
    protected $allowedFields = [
        'mitb_code',
        'referral_code',
        'from_user',
        'to_user',
        'amount',
        'description',
        'category',
        'business_type',
        'chapter_code',
        'status',
        'note',
        'created_at',
        'updated_at'
    ];

    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Optional: Return type as array
    protected $returnType = 'array';
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferralModel extends Model
{
    protected $table = 'referrals';
    protected $primaryKey = 'referral_id';
    protected $allowedFields = [
        'referral_code',
        'from_user',
        'to_user',
        'business',
        'contact_number',
        'temperature',
        'description',
        'status',
        'note',
        'admin_note',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

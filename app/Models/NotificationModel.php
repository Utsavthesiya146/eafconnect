<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'n_id';
    protected $allowedFields = [
        'n_code',
        'user_code',
        'message',
        'status',
        'created_at'
    ];
}

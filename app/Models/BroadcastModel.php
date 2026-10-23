<?php

namespace App\Models;

use CodeIgniter\Model;

class BroadcastModel extends Model
{
    protected $table = 'broadcast';
    protected $primaryKey = 'broadcast_id';
    protected $allowedFields = [
        'broadcast_code',
        'user_code',
        'chapter_code',
        'message',
        'image',
        'video',
        'note',
        'status',
        'created_at'
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table = 'conatct_us';
    protected $primaryKey = 'contact_id';

    protected $allowedFields = [
        'contact_code',
        'name',
        'email',
        'subject',
        'phone',
        'message',
        'user_code',
        'status',
        'note'
    ];
}
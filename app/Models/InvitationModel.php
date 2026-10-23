<?php

namespace App\Models;

use CodeIgniter\Model;

class InvitationModel extends Model
{
    protected $table      = 'invitations';
    protected $primaryKey = 'invitations_id';
    protected $allowedFields = [
        'invitations_code',
        'user_code',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_company',
        'message',
        'status',
        'file',
        'created_at',
        'updated_at'
    ];
}

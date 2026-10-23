<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'user_id';
    protected $returnType = 'object';
    protected $useAutoIncrement = true;

    /*
    |--------------------------------------------------------------------------
    | Allowed Fields (important in CI4)
    |--------------------------------------------------------------------------
    */
   protected $allowedFields = [
    'user_code',
    'name',
    'last_name',
    'email',
    'password',
    'phone',
    'alt_phone',
    'whatsapp',
    'address',
    'city',
    'state',
    'country',
    'pincode',
    'profile_photo',
    'gender',
    'designation',
    'business_name',
    'industry',
    'member_role',
    'member_type',
    'status',
    'join_date',
    'created_at',
    'chapter_code',
    'start_year',
    'gst_number',
];
}


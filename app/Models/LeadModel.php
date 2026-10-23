<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadModel extends Model
{
    protected $table      = 'leads';
    protected $primaryKey = 'lead_id';
    protected $allowedFields = [
        'lead_code',
        'name',
        'email',
        'phone',
        'business',
        'location',
        'about',
        'status',
        'created_at',
        'gst',
        'category',
        'since',
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table      = 'events';
    protected $primaryKey = 'event_id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    /*
    |--------------------------------------------------------------------------
    | Allowed Fields (CI4 Protection)
    |--------------------------------------------------------------------------
    */
    protected $allowedFields = [
        'event_code',
        'title',
        'description',
        'date',
        'mode',
        'meeting_link',
        'chapter_code',
        'location',
        'note',
        'created_by',
        'updated_on'
    ];

    /*
    |--------------------------------------------------------------------------
    | Auto Date Handling (Optional)
    |--------------------------------------------------------------------------
    */
    protected $useTimestamps = false;

    protected $dateFormat = 'datetime';

    protected $createdField  = '';
    protected $updatedField  = '';
}

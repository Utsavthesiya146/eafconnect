<?php

namespace App\Models;

use CodeIgniter\Model;

class ChapterModel extends Model
{
    protected $table      = 'chapters';
    protected $primaryKey = 'chapters_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'chapter_code',
        'chapter_name',
        'chapter_description',
        'location',
        'leader_code',
        'chapter_status',
        'updated_at'
    ];
}

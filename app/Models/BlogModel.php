<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table      = 'blogs';
    protected $primaryKey = 'blogs_id';

    protected $returnType = 'object';

    protected $allowedFields = [
        'blogs_code',
        'slug',
        'blogs_name',
        'blogs_image',
        'blogs_category',
        'blogs_title',
        'blogs_discription',
        'blogs_discription2',
        'blogs_status',
        'company_code',
        'author_name',
        'seo_title',
        'seo_discription',
        'seo_keyword'
    ];
}

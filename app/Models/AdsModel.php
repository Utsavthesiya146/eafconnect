<?php

namespace App\Models;

use CodeIgniter\Model;

class AdsModel extends Model
{
    protected $table      = 'ads';
    protected $primaryKey = 'ads_id';

    protected $returnType = 'object';

    protected $allowedFields = [
        'ads_code',
        'slug',
        'ads_name',
        'ads_image',
        'ads_category',
        'ads_title',
        'ads_discription',
        'ads_discription2',
        'ads_status',
        'company_code',
        'chapter_code',
        'user_code',
        'from_date',
        'to_date',
        'author_name',
        'seo_title',
        'seo_discription',
        'seo_keyword'
    ];
}

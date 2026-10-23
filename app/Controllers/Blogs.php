<?php

namespace App\Controllers;

use App\Models\CommonModel;
use App\Models\BlogModel;

class Blogs extends BaseController
{
    public function index()
    {
        helper('text');

        $model = new BlogModel();

        $data['blogs'] = $model->where('blogs_status', '1')
            ->orderBy('blogs_id', 'DESC')
            ->paginate(9);

        $data['pager'] = $model->pager;

        return view('blogs', $data);
    }

    public function details($slug = null)
    {
        helper('text');

        $model = new BlogModel();

        $blog = $model->where('slug', $slug)
            ->where('blogs_status', '1')
            ->first();

        if (!$blog) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['blog'] = $blog;

        // ✅ FIX OBJECT ERROR
        $data['related'] = $model->where('blogs_category', $blog->blogs_category)
            ->where('slug !=', $slug)
            ->where('blogs_status', '1')
            ->orderBy('blogs_id', 'DESC')
            ->findAll(4);

        return view('blogs_details', $data);
    }
}

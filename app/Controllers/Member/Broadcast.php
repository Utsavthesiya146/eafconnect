<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Broadcast extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $model = new \App\Models\BroadcastModel();

        /* ========= REQUEST VALUES ========= */
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('perPage') ?? 12);
        $search  = trim($this->request->getGet('search'));
        $status  = $this->request->getGet('status');

        /* ========= QUERY ========= */
        $builder = $model;

        // Search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }

        // Status Filter (1 = Active , 2 = Hidden)
        if (!empty($status)) {
            $builder->where('status', $status);
        } else {
            $builder->where('status', '1'); // show active only
        }

        // Latest First
        $builder->orderBy('broadcast_id', 'DESC');

        /* ========= PAGINATION ========= */
        $data['broadcast'] = $builder->paginate($perPage, 'default', $page);
        $data['pager']     = $model->pager;

        /* ========= COUNT ========= */
        $data['count_all'] = $model->where('status', '1')->countAllResults();

        /* ========= EXTRA ========= */
        $data['perPage'] = $perPage;
        $data['search']  = $search;
        $data['status']  = $status;

        return view('member/broadcast', $data);
    }
}

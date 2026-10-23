<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\OneToOneModel;

class F2f extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }
        $model = new OneToOneModel();
        $commonModel = new CommonModel();
        /* ========= REQUEST VALUES ========= */
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');
        /* ========= QUERY ========= */
        $builder = $model;
        $builder->select("
        one_to_ones.*,

        from_user.name AS from_name,
        from_user.email AS from_email,
        COALESCE(from_user.profile_photo,'empty.png') AS from_profile,

        to_user.name AS to_name,
        to_user.email AS to_email,
        COALESCE(to_user.profile_photo,'empty.png') AS to_profile
    ");
        $builder->join(
            'users AS from_user',
            'from_user.user_code = one_to_ones.from_user',
            'left'
        );
        $builder->join(
            'users AS to_user',
            'to_user.user_code = one_to_ones.to_user',
            'left'
        );
        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $builder->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('one_to_ones.location', $search)
                ->groupEnd();
        }
        /* ========= STATUS ========= */
        if (!empty($status)) {
            $builder->where('one_to_ones.status', $status);
        }
        $builder->orderBy('one_to_ones.f2f_id', 'DESC');
        /* ========= PAGINATION ========= */
        $data['connect'] = $builder->paginate($perPage, 'default', $page);
        $data['pager']   = $builder->pager;
        $data['perPage'] = $perPage;
        $data['member'] = $commonModel
            ->exe_query("
        SELECT 
            u.user_code,
            u.name,
            u.email,
            cm.chapter_code AS member_chapter_code,
            c.chapter_name,
            c.chapter_code AS chapter_code
        FROM users AS u
        INNER JOIN chapter_members AS cm
            ON cm.user_code = u.user_code
        INNER JOIN chapters AS c
            ON c.chapter_code = cm.chapter_code
        WHERE u.status = '1'
    ")
            ->getResultArray();
        return view('admin/f2f', $data);
    }



    public function delete_f2f($f2f_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM one_to_ones  WHERE f2f_code='" . esc($f2f_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_f2f_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="f2f_code" value="' . esc($row->f2f_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->date) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->location) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->topics) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this F2F?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_f2f_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $f2f_code = $this->request->getPost('f2f_code');
        if (!$f2f_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("one_to_ones", ['f2f_code' => $f2f_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ F2F removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }



    public function edit_f2f($f2f_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM one_to_ones  WHERE f2f_code='" . esc($f2f_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">F2F not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        // Safe query
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="edit_f2f_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change F2F Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="f2f_code" value="' . esc($row->f2f_code) . '">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="fw-bold">date</label>
                    <input type="date" name="date" class="form-control" value="' . esc($row->date) . '">
                </div>
                    <div class="col-md-6">
                    <label class="fw-bold">location</label>
                    <input type="text" name="location" class="form-control" value="' . esc($row->location) . '">
                </div>
                   <div class="col-md-12">
                    <label class="fw-bold">status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">' . esc(ucfirst($row->status)) . '</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                  <div class="col-md-12">
                    <label class="fw-bold">topics</label>
                    <input type="text" name="topics" class="form-control" value="' . esc($row->topics) . '">
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">summary</label>
                    <textarea name="summary" class="form-control">' . esc($row->summary) . '</textarea>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Note</label>
                    <textarea name="note" class="form-control">' . esc($row->note) . '</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 m-1 p-1" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white px-3 m-1 p-1" style="background-color:#1b4d8f;">Update</button>
        </div>
    </form>
</div>
';

        return $this->response->setBody($output);
    }

    public function edit_f2f_action()
    {
        $f2f_code = $this->request->getPost('f2f_code');
        if (!$f2f_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Referral code missing'
            ]);
        }
        $data = [
            'date' => $this->request->getPost('date'),
            'location' => $this->request->getPost('location'),
            'status' => $this->request->getPost('status'),
            'topics' => $this->request->getPost('topics'),
            'summary' => $this->request->getPost('summary'),
            'note' => $this->request->getPost('note'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'one_to_ones',
            $data,
            ['f2f_code' => $f2f_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ F2F updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }


    public function get_filtered_data()
    {
        $request = \Config\Services::request();
        $user_code = session()->get('user_code');
        $search  = $request->getPost('search');
        $date    = $request->getPost('date');
        $status  = $request->getPost('status');
        $to_user = $request->getPost('to_user');

        $model = new OneToOneModel();

        $model->select("
        one_to_ones.*,

        from_user.name AS from_name,
        from_user.email AS from_email,
        COALESCE(from_user.profile_photo,'empty.png') AS from_profile,

        to_user.name AS to_name,
        to_user.email AS to_email,
        COALESCE(to_user.profile_photo,'empty.png') AS to_profile
    ");

        $model->join('users AS from_user', 'from_user.user_code = one_to_ones.from_user', 'left');
        $model->join('users AS to_user', 'to_user.user_code = one_to_ones.to_user', 'left');

        // ✅ SEARCH
        if (!empty($search)) {
            $model->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('one_to_ones.location', $search)
                ->orLike('one_to_ones.topics', $search)
                ->groupEnd();
        }

        // ✅ STATUS
        if (!empty($status)) {
            $model->where('one_to_ones.status', $status);
        }

        // ✅ DATE
        if (!empty($date)) {
            $model->where('one_to_ones.date', $date);
        }

        // ✅ TO USER
        if (!empty($to_user)) {
            $model->where('one_to_ones.to_user', $to_user);
        }
        $model->orderBy('one_to_ones.f2f_id', 'DESC');

        $users = $model->findAll();

        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No 121 found.</div>';
        }

        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/f2f_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }
}

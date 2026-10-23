<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\OneToOneModel;

class Visitor extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model       = new \App\Models\VisitorModel();
        $commonModel = new CommonModel();

        /* ========= REQUEST VALUES ========= */

        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);

        $search  = trim($this->request->getGet('search') ?? '');
        $status  = trim($this->request->getGet('status') ?? '');

        /* ========= QUERY ========= */

        $builder = $model
            ->select("
            visitors.*,

            u.name  AS member_name,
            u.email AS member_email,
            u.phone AS member_phone,

            COALESCE(u.profile_photo,'empty.png') AS member_profile,

            c.chapter_name
        ")
            ->join('users AS u', 'u.user_code = visitors.user_code', 'left')
            ->join('chapters AS c', 'c.chapter_code = visitors.chapter_code', 'left');

        /* ========= SEARCH ========= */

        if ($search != '') {

            $builder->groupStart()

                ->like('visitors.visitor_name', $search)
                ->orLike('visitors.company', $search)
                ->orLike('visitors.phone', $search)
                ->orLike('visitors.email', $search)

                ->orLike('u.name', $search)
                ->orLike('u.email', $search)

                ->orLike('c.chapter_name', $search)

                ->groupEnd();
        }

        /* ========= STATUS ========= */

        if ($status != '') {
            $builder->where('visitors.status', $status);
        }

        /* ========= ORDER ========= */

        $builder->orderBy('visitors.visitors_id', 'DESC');

        /* ========= PAGINATION ========= */

        $data['connect'] = $builder->paginate($perPage);

        $data['pager']   = $model->pager;
        $data['perPage'] = $perPage;

        /* ========= EVENTS ========= */

        $data['events'] = $commonModel
            ->exe_query("
            SELECT * 
            FROM events
            ORDER BY event_id DESC
        ")
            ->getResultArray();

        return view('admin/visitor', $data);
    }
    public function AddNewvisitorData()
    {
        helper(['form']);

        $session     = \Config\Services::session();
        $commonModel = new CommonModel();
        $user_code   = $session->get('user_code');

        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired. Please login again.'
            ]);
        }

        $data = $this->request->getPost();

        $visitors_code = $commonModel->generateSerial(
            'visitors',
            'visitors_code',
            'VC'
        );
        $insert = [
            'visitors_code'    => $visitors_code,
            'user_code'    => (string)$user_code,
            'visitor_name'      => $data['visitor_name'] ?? null,
            'company'     => $data['company'] ?? null,
            'email'       => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'date_visited' => $data['date_visited'] ?? null,
        ];

        $insertId = $commonModel->insert_data('visitors', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add MITB'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Visitor Added Successfully'
        ]);
    }


    public function delete_v($visitors_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM visitors  WHERE visitors_code='" . esc($visitors_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_v_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="visitors_code" value="' . esc($row->visitors_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->visitor_name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->company) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->phone) . '</p>
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

    public function delete_v_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $visitors_code = $this->request->getPost('visitors_code');
        if (!$visitors_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("visitors", ['visitors_code' => $visitors_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Visitor removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }



    public function edit_v($visitors_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM visitors  WHERE visitors_code='" . esc($visitors_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">F2F not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        // Safe query
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="edit_v_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Visitors Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="visitors_code" value="' . esc($row->visitors_code) . '">
            <div class="row g-2">
                   <div class="col-md-12">
                    <label class="fw-bold">status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">' . esc(ucfirst($row->status)) . '</option>
                        <option value="scheduled">Scheduled</option>
               <option value="Attended">Attended</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">summary</label>
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

    public function edit_v_action()
    {
        $visitors_code = $this->request->getPost('visitors_code');
        if (!$visitors_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'visitors code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'note' => $this->request->getPost('note'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'visitors',
            $data,
            ['visitors_code' => $visitors_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ visitor updated successfully!'
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

        $model = new \App\Models\VisitorModel();

        $model->select("
        visitors.*,
        u.name AS member_name,
        u.email AS member_email,
        COALESCE(u.profile_photo,'empty.png') AS member_profile,
        c.chapter_name
    ");

        $model->join('users AS u', 'u.user_code = visitors.user_code', 'left');
        $model->join('chapters AS c', 'c.chapter_code = visitors.chapter_code', 'left');

        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $model->groupStart()
                ->like('visitors.visitor_name', $search)
                ->orLike('visitors.company', $search)
                ->orLike('visitors.phone', $search)
                ->orLike('visitors.email', $search)
                ->orLike('visitors.note', $search)
                ->orLike('u.name', $search)
                ->orLike('u.email', $search)
                ->groupEnd();
        }

        /* ========= STATUS ========= */
        if (!empty($status)) {
            $model->where('visitors.status', $status);
        }

        /* ========= DATE ========= */
        if (!empty($date)) {
            $model->where('visitors.date_visited', $date);
        }

        /* ========= USER FILTER ========= */


        $model->orderBy('visitors.visitors_id', 'DESC');

        $users = $model->findAll();

        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No Visitor Found</div>';
        }

        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/visitor_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }
}

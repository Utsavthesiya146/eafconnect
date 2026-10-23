<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\EventModel;
use App\Models\InvitationModel;

class Invitations extends BaseController
{


    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model = new \App\Models\InvitationModel();

        $perPage = 10;
        $invitations = $model

            ->orderBy('invitations_id', 'DESC')
            ->paginate($perPage, 'default');

        $pager = $model->pager;

        $data = [
            'invitations' => $invitations,
            'pager'       => $pager,
            'perPage'     => $perPage
        ];

        return view('admin/invitations', $data);
    }
    public function AddNewInvitationsData()
    {
        helper(['form']);
        $commonModel = new \App\Models\CommonModel();
        $data = $this->request->getPost();

        // Start session
        $session = session();
        $user_code = $session->get('user_code');

        $invitations_code = $commonModel->get_serial3(
            'invitations', // Table name
            'INV',
            'invitations_code'
        );
        $insert = [
            'invitations_code' => $invitations_code,
            'user_code'        => $user_code,
            'guest_name'       => $data['guest_name'] ?? '',
            'guest_email'      => $data['guest_email'] ?? '',
            'guest_phone'      => $data['guest_phone'] ?? '',
            'guest_company'    => $data['guest_company'] ?? '',
            'message'          => $data['message'] ?? '',
            'status'           => 'sent',
        ];

        $commonModel->insert_data('invitations', $insert);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Invitation Created Successfully'
        ]);
    }

    public function delete_invitation($invitations_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM  invitations  WHERE invitations_code='" . esc($invitations_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_invitation_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="invitations_code" value="' . esc($row->invitations_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->guest_name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->guest_email) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->guest_phone) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Invitations?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_invitation_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $invitations_code = $this->request->getPost('invitations_code');
        if (!$invitations_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("invitations", ['invitations_code' => $invitations_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ invitations removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }


    public function edit_invitation($invitations_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM invitations WHERE invitations_code='" . esc($invitations_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody(
                '<p class="text-center text-danger">User not found!</p>'
            );
        }
        $row = $user[0];

        $output = '

<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="edit_invitation_action" method="post">

        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#ffffff;">Edit invitations</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="invitations_code" value="' . esc($row->invitations_code) . '">
          
                    <div class="row g-2">
                        <div class="col-md-12">
                    <label class="fw-bold">status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">' . esc(ucfirst($row->status)) . '</option>
                        <option value="sent">sent</option>
                        <option value="viewed">viewed</option>
                        <option value="Attended">Attended</option>
                        <option value="declined">declined</option>
                    </select>
                </div>
                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="fw-bold">Name</label>
                            <input type="text" name="guest_name"   value="' . esc($row->guest_name) . '" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Email</label>
                            <input type="email" name="guest_email"  value="' . esc($row->guest_email) . '" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Phone (Optional)</label>
                            <input type="text" name="guest_phone"  value="' . esc($row->guest_phone) . '" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Company (Optional)</label>
                            <input type="text" name="guest_company"  value="' . esc($row->guest_company) . '" class="form-control">
                        </div>
                        <!-- Title -->
                        <div class="col-md-12">
                            <label class="fw-bold">Message (Optional)</label>
                               <textarea name="message" class="form-control">' . esc($row->message) . '</textarea>
                        </div>
                    </div>
        </div>

        <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white" style="background:#1b4d8f;">Update</button>
        </div>

    </form>
</div>';

        return $this->response->setBody($output);
    }
    public function edit_invitation_action()
    {
        $invitations_code = $this->request->getPost('invitations_code');
        if (!$invitations_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'invitations_code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'guest_name' => $this->request->getPost('guest_name'),
            'guest_email' => $this->request->getPost('guest_email'),
            'guest_phone' => $this->request->getPost('guest_phone'),
            'guest_company' => $this->request->getPost('guest_company'),
            'message' => $this->request->getPost('message'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'invitations',
            $data,
            ['invitations_code' => $invitations_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Invitations Updated successfully!'
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

        $model = new \App\Models\InvitationModel();

        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $model->groupStart()
                ->like('guest_name', $search)
                ->orLike('guest_email', $search)
                ->orLike('guest_phone', $search)
                ->orLike('guest_company', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }

        /* ========= STATUS ========= */
        if (!empty($status)) {
            $model->where('status', $status);
        }

        /* ========= DATE ========= */
        if (!empty($date)) {
            // ✅ Correct way (if datetime column)
            $model->where('DATE(created_at)', $date);
        }


        /* ========= ORDER ========= */
        $model->orderBy('invitations_id', 'DESC');

        $users = $model->findAll();

        /* ========= NO DATA ========= */
        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No Invitations Found</div>';
        }

        /* ========= OUTPUT ========= */
        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/invitations_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }
}

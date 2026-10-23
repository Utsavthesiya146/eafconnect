<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\ContactModel;

class Contact extends BaseController
{


    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model = new \App\Models\ContactModel();

        $perPage = 10;
        $leads = $model

            ->orderBy('contact_id', 'DESC')
            ->paginate($perPage, 'default');

        $pager = $model->pager;

        $data = [
            'leads' => $leads,
            'pager'       => $pager,
            'perPage'     => $perPage
        ];

        return view('admin/contact_us', $data);
    }

    public function delete_contact($contact_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM  conatct_us  WHERE contact_code='" . esc($contact_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_contact_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="contact_code" value="' . esc($row->contact_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->email) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->phone) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Leads?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_contact_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $contact_code = $this->request->getPost('contact_code');
        if (!$contact_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("conatct_us", ['contact_code' => $contact_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Contact removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }


    public function edit_contact($contact_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM  conatct_us  WHERE contact_code='" . esc($contact_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0];

        $output = '

<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="edit_contact_action" method="post">

        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#ffffff;">Edit Leads</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="contact_code" value="' . esc($row->contact_code) . '">
          
                    <div class="row g-2">
                        <div class="col-md-12">
                    <label class="fw-bold">status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">' . esc(ucfirst($row->status)) . '</option>
                        <option value="sent">sent</option>
                        <option value="viewed">viewed</option>
                        <option value="accepted">accepted</option>
                        <option value="declined">declined</option>
                    </select>
                </div>
                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="fw-bold">Guest Name</label>
                            <input type="text" name="name"   value="' . esc($row->name) . '" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Guest Email</label>
                            <input type="email" name="email"  value="' . esc($row->email) . '" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Guest Phone (Optional)</label>
                            <input type="text" name="phone"  value="' . esc($row->phone) . '" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Guest Company (Optional)</label>
                            <input type="text" name="subject"  value="' . esc($row->subject) . '" class="form-control">
                        </div>
                        <!-- Title -->
                        <div class="col-md-12">
                            <label class="fw-bold">Personal Message (Optional)</label>
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
    public function edit_contact_action()
    {
        $contact_code = $this->request->getPost('contact_code');
        if (!$contact_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'contact_code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'conatct_us',
            $data,
            ['contact_code' => $contact_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ leads Updated successfully!'
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

        $search  = $request->getPost('search');
        $date    = $request->getPost('date');
        $status  = $request->getPost('status');

        $model = new \App\Models\ContactModel();

        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $model->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('subject', $search)
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
            $model->where('DATE(created_on)', $date);
        }


        /* ========= ORDER ========= */
        $model->orderBy('contact_id', 'DESC');

        $users = $model->findAll();

        /* ========= NO DATA ========= */
        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No Contact Found</div>';
        }

        /* ========= OUTPUT ========= */
        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/contact_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }
}

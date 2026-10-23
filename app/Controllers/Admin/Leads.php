<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\LeadModel;

class Leads extends BaseController
{

    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $leadModel = new \App\Models\LeadModel();
  
        $commonModel = new CommonModel();
        $perPage = 10;

        $leads = $leadModel
            ->orderBy('lead_id', 'DESC')
            ->paginate($perPage);
        $member = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();

        $data = [
            'leads'   => $leads,
            'member'   => $member,
            'pager'   => $leadModel->pager,
            'perPage' => $perPage,
    
        ];

        return view('admin/leads', $data);
    }

    public function delete_leads($lead_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM  leads  WHERE lead_code='" . esc($lead_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_leads_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="lead_code" value="' . esc($row->lead_code) . '">
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

    public function delete_leads_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $lead_code = $this->request->getPost('lead_code');
        if (!$lead_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("leads", ['lead_code' => $lead_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ leads removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }


    public function edit_leads($lead_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM  leads  WHERE lead_code='" . esc($lead_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0];

        $output = '

<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="edit_leads_action" method="post">

        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#ffffff;">Edit Leads</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="lead_code" value="' . esc($row->lead_code) . '">
          
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
                            <input type="text" name="company"  value="' . esc($row->company) . '" class="form-control">
                        </div>
                                   <div class="col-md-12">
                            <input name="business" type="text"
                                class="form-control rounded-3 shadow-none"
                                placeholder="Business Name*" value="' . esc($row->business) . '"  required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Business Category</label>
                            <input name="category" type="text"
                                class="form-control rounded-3 shadow-none"
                                placeholder="Business Category*" value="' . esc($row->category) . '"  required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Since</label>
                            <input name="since" type="date"
                                class="form-control rounded-3 shadow-none"
                                placeholder="Since*" value="' . esc($row->since) . '"  required>
                        </div>
                        <div class="col-md-12">
                            <input name="location" type="text"
                                class="form-control rounded-3 shadow-none"
                                placeholder="Location*" value="' . esc($row->location) . '"  required>
                        </div>

                        <div class="col-md-12">
                            <input name="gst" type="text"
                                class="form-control rounded-3 shadow-none"
                                placeholder="GST Number" value="' . esc($row->gst) . '" >
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



    public function add_member($lead_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM  leads  WHERE lead_code='" . esc($lead_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0];
        $output = '
<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="AddNewMemeberData" method="post">
        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#ffffff;">Add Member</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-3">
    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold">First Name</label>
                            <input type="text" name="name"  value="' . esc($row->name) . '"  class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Last Name</label>
                            <input type="text" name="last_name"  value="' . esc($row->last_name) . '"  class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Email</label>
                            <input type="email" name="email"  value="' . esc($row->email) . '"  class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">👁</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Account Type</label>
                            <select name="role" class="form-select">
                                <option value="member">Member</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="2">Pending</option>
                                <option value="3">Blocked</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Member Role</label>
                            <select name="member_role" class="form-select">
                                <option value="Member">Member</option>
                                <option>President</option>
                                <option>Vice President</option>
                                <option>Secretary Treasurer</option>
                                <option>Chapter Growth</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Join Date</label>
                            <input type="date" name="join_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Member Type</label>
                            <select name="member_type" class="form-select">
                                <option value="Normal">Normal</option>
                                <option value="Gold">Gold Member 👑</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="fw-bold">Profile Photo</label>
                            <div class="d-flex gap-3 align-items-center">
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*">
                                <img id="imagePreview"
                                    style="width:60px;height:60px;display:none;object-fit:cover"
                                    class="rounded-circle border">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Business Name</label>
                            <input type="text" name="business_name"  value="' . esc($row->business) . '"  class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Business Category</label>
                            <input type="text" name="industry"  value="' . esc($row->category) . '"  class="form-control">
                        </div>

                        <div class="col-md-6">s
                            <label class="fw-bold">Location</label>
                            <input type="text" name="address"  class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Since</label>
                            <input type="date" name="start_year" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">GST</label>
                            <input type="text" name="gst_number"  value="' . esc($row->gst) . '"  class="form-control">
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



    public function edit_leads_action()
    {
        $lead_code = $this->request->getPost('lead_code');
        if (!$lead_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'lead_code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'company' => $this->request->getPost('company'),
            'message' => $this->request->getPost('message'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'leads',
            $data,
            ['lead_code' => $lead_code]
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

        $model = new \App\Models\LeadModel();

        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $model->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('company', $search)
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
        $model->orderBy('lead_id', 'DESC');

        $users = $model->findAll();

        /* ========= NO DATA ========= */
        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No leads Found</div>';
        }

        /* ========= OUTPUT ========= */
        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/leads_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }
}

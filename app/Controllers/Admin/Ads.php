<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\AdsModel;

class Ads extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }
        $model = new AdsModel();

        $commonModel = new CommonModel();
        /* ========= REQUEST VALUES ========= */
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');

        /* ========= QUERY BUILDER ========= */
        $builder = $model;

        // Search Filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('ads_name', $search)
                ->orLike('ads_category', $search)
                ->groupEnd();
        }

        // Status Filter
        if (!empty($status)) {
            $builder->where('ads_status', $status);
        }

        /* ========= ORDER ========= */
        $builder->orderBy("FIELD(ads_status,1,2,3)", "", false);
        $builder->orderBy("ads_id", "DESC");

        /* ========= PAGINATION ========= */
        $data['users'] = $builder->paginate($perPage, 'default', $page); // table data
        $data['pager'] = $builder->pager; // pagination links

        /* ========= COUNT ========= */
        $data['count_all'] = $model->countAll();
        /* ========= MEMBER LIST ========= */
        $data['member'] = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();
        $data['chapters'] = $commonModel
            ->exe_query("SELECT chapter_code,chapter_name FROM chapters WHERE chapter_status='1'")
            ->getResultArray();

        /* ========= PASS FILTER VALUES ========= */
        $data['perPage'] = $perPage;
        $data['search']  = $search;
        $data['status']  = $status;

        return view('admin/ads', $data);
    }
    /* ======================================================
   CONTROLLER FUNCTION (FIXED)
====================================================== */
    public function AddNewAdsData()
    {
        helper(['form']);
        $commonModel = new CommonModel();
        $data = $this->request->getPost();
        /* ================= Ads CODE ================= */
        $ads_code = $commonModel->generateSerial('ads', 'ads_code', 'ADS');
        /* ================= IMAGE UPLOAD ================= */
        $photo = 'empty.png';

        $file = $this->request->getFile('ads_image');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $path = FCPATH . 'uploads/ads/';   // fixed folder name

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

            if (in_array($file->getMimeType(), $allowedTypes)) {
                $newName = $file->getRandomName();
                $file->move($path, $newName);
                $photo = $newName;
            }
        }

        /* ================= INSERT DATA ================= */
        $insert = [

            'ads_code'         => $ads_code,
            'slug'               => url_title($data['ads_name'], '-', true),
            'ads_name'         => $data['ads_name'] ?? '',
            'ads_image'        => $photo, // fixed

            'chapter_code'        => $data['chapter_code'] ?? '',
            'from_date'     => $data['from_date'] ?? '',
            'to_date'        => $data['to_date'] ?? '',


            'ads_title'        => $data['ads_title'] ?? '',
            'ads_category'     => $data['ads_category'] ?? '',
            'ads_discription'  => $data['ads_discription'] ?? '',
            'ads_status'       => '1',
        ];

        $insertId = $commonModel->insert_data('ads', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to Add Ads'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Ads Added Successfully'
        ]);
    }


    public function delete_ads($ads_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM ads  WHERE ads_code='" . esc($ads_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_ads_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="ads_code" value="' . esc($row->ads_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;">Name: <span style="color:#05008D;">' . esc($row->ads_name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;"> ' . esc($row->ads_category) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this user?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_ads_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $ads_code = $this->request->getPost('ads_code');
        if (!$ads_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("ads", ['ads_code' => $ads_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Ads removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }


    public function edit_ads($ads_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM ads  WHERE  ads_code='" . esc($ads_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
            <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
                      <form id="edit_ads_action" method="post">
                    <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
                        <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Ads Status</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <input type="hidden" name="ads_code" value="' . $row->ads_code . '">
                          <div class="row">
                        <div class="col-md 12 mb-3">
                            <label class="form-label">Ads Status</label>
                              <select class="form-control" name="ads_status"  required>';
        if ($row->ads_status == 1) {
            $output .= '<option value="1" selected>Active</option>';
        } elseif ($row->ads_status == 2) {
            $output .= '<option value="2" selected>Hold</option>';
        } else {
            $output .= '<option value="3" selected>Block</option>';
        }
        $output .= '
                                <option value="1">Active</option>
                                <option value="2">Pending</option>
                                <option value="3">Block</option>
                            </select>
                        </div>    
                         <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Name</label>
                        <input type="text" name="ads_name"  value="' . $row->ads_name . '" class="form-control form-control-sm" placeholder="Ads Name" required>
                    </div>
  <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Category</label>
                        <input type="text" name="ads_category"  value="' . $row->ads_category . '" class="form-control form-control-sm" placeholder="Ads Category" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Description</label>
                        <textarea class="form-control form-control-sm" id="summernote" name="ads_discription" rows="4">' . $row->ads_discription . '</textarea>
                    </div>
  <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">From</label>
                                <input type="date" name="from_date" value="' . $row->from_date . '" class="form-control form-control-sm" placeholder="Ads Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">To</label>
                                <input type="date" name="to_date" value="' . $row->to_date . '" class="form-control form-control-sm" placeholder="Ads Title" required>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer justify-content-end border-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 m-1 p-1" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="status_c" class="btn btn-sm text-white px-3 m-1 p-1" style="background-color:#1b4d8f;">Update</button>
                    </div>
                </form>
            </div>
        </div>>';

        return $this->response->setBody($output);
    }
    public function edit_ads_action()
    {
        $ads_code = $this->request->getPost('ads_code');

        if (!$ads_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ads_code missing'
            ]);
        }

        $data = [
            'ads_status' => $this->request->getPost('ads_status'),
            'from_date' => $this->request->getPost('from_date'),
            'to_date' => $this->request->getPost('to_date'),
            'ads_name' => $this->request->getPost('ads_name'),
            'ads_category' => $this->request->getPost('ads_category'),
            'ads_discription' => $this->request->getPost('ads_discription'),
        ];

        $model = new CommonModel();
        $update = $model->update_row(
            'ads',
            $data,
            ['ads_code' => $ads_code]
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Ads updated successfully!'
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
        $search = $request->getPost('search');
        $status = $request->getPost('status');

        $AdsModel = new AdsModel();
        $usersQuery = $AdsModel->builder();

        if (!empty($search)) {
            $usersQuery->groupStart()
                ->like('ads_name', $search)
                ->orLike('ads_category', $search)
                ->orLike('ads_title', $search)
                ->groupEnd();
        }

        if (!empty($status)) {
            $usersQuery->where('ads_status', $status);
        }

        $users = $usersQuery->get()->getResult();

        if (!$users) {
            echo '<div class="alert alert-warning text-center">No users found for the selected filters.</div>';
            return;
        }

        $output = '';
        $i = 1;
        foreach ($users as $row) {
            $output .= view('include/admin/card/ads_card', [
                'row' => $row,
                'i' => $i++
            ]);
        }

        echo $output;
    }
}

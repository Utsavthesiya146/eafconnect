<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\BlogModel;

class Blogs extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        // ✅ Use Blog Model (not UserModel)
        $model = new \App\Models\BlogModel();

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
                ->like('blogs_name', $search)
                ->orLike('blogs_category', $search)
                ->groupEnd();
        }

        // Status Filter
        if (!empty($status)) {
            $builder->where('blogs_status', $status);
        }

        /* ========= ORDER ========= */
        $builder->orderBy("FIELD(blogs_status,1,2,3)", "", false);
        $builder->orderBy("blogs_id", "DESC");

        /* ========= PAGINATION ========= */
        $data['users'] = $builder->paginate($perPage, 'default', $page); // table data
        $data['pager'] = $builder->pager; // pagination links

        /* ========= COUNT ========= */
        $data['count_all'] = $model->countAll();

        /* ========= PASS FILTER VALUES ========= */
        $data['perPage'] = $perPage;
        $data['search']  = $search;
        $data['status']  = $status;

        return view('admin/blogs', $data);
    }
    /* ======================================================
   CONTROLLER FUNCTION (FIXED)
====================================================== */
    public function AddNewBlogData()
    {
        helper(['form']);
        $commonModel = new CommonModel();
        $data = $this->request->getPost();
        /* ================= BLOG CODE ================= */
        $blogs_code = $commonModel->generateSerial('blogs', 'blogs_code', 'BLOGS');
        /* ================= IMAGE UPLOAD ================= */
        $photo = 'empty.png';

        $file = $this->request->getFile('blogs_image');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $path = FCPATH . 'uploads/blogs/';   // fixed folder name

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

            'blogs_code'         => $blogs_code,
            'slug'               => url_title($data['blogs_name'], '-', true),
            'blogs_name'         => $data['blogs_name'] ?? '',
            'blogs_image'        => $photo, // fixed
            'blogs_title'        => $data['blogs_title'] ?? '',
            'blogs_category'     => $data['blogs_category'] ?? '',
            'blogs_discription'  => $data['blogs_discription'] ?? '',
            'blogs_status'       => '1',
            'author_name'        => 'Admin',

            'seo_title'          => $data['blogs_title'] ?? '',
            'seo_discription'    => substr(strip_tags($data['blogs_discription']), 0, 150),
            'seo_keyword'        => $data['blogs_category'] ?? '',
        ];

        $insertId = $commonModel->insert_data('blogs', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to Add Blog'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Blog Added Successfully'
        ]);
    }


    public function delete_blogs($blogs_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM blogs  WHERE blogs_code='" . esc($blogs_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_blogs_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="blogs_code" value="' . esc($row->blogs_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;">Name: <span style="color:#05008D;">' . esc($row->blogs_name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;"> ' . esc($row->blogs_category) . '</p>
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

    public function delete_blogs_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $blogs_code = $this->request->getPost('blogs_code');
        if (!$blogs_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("blogs", ['blogs_code' => $blogs_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Blogs removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }


    public function edit_blogs($blogs_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM blogs  WHERE  blogs_code='" . esc($blogs_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
            <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
                      <form id="edit_blogs_action" method="post">
                    <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
                        <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Blogs Status</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <input type="hidden" name="blogs_code" value="' . $row->blogs_code . '">
                          <div class="row">
                        <div class="col-md 12 mb-3">
                            <label class="form-label">Blogs Status</label>
                              <select class="form-control" name="blogs_status"  required>';
        if ($row->blogs_status == 1) {
            $output .= '<option value="1" selected>Active</option>';
        } elseif ($row->blogs_status == 2) {
            $output .= '<option value="2" selected>Pending</option>';
        } else {
            $output .= '<option value="3" selected>Block</option>';
        }
        $output .= '
                                <option value="1">Active</option>
                                <option value="2">Pending</option>
                                <option value="3">Block</option>
                            </select>
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
    public function edit_blogs_action()
    {
        $blogs_code = $this->request->getPost('blogs_code');

        if (!$blogs_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'blogs_code missing'
            ]);
        }

        $data = [
            'blogs_status' => $this->request->getPost('blogs_status'),
        ];

        $model = new CommonModel();
        $update = $model->update_row(
            'blogs',
            $data,
            ['blogs_code' => $blogs_code]
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Blogs updated successfully!'
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

        $BlogModel = new BlogModel();
        $usersQuery = $BlogModel->builder();

        if (!empty($search)) {
            $usersQuery->groupStart()
                ->like('blogs_name', $search)
                ->orLike('blogs_category', $search)
                ->orLike('blogs_title', $search)
                ->groupEnd();
        }

        if (!empty($status)) {
            $usersQuery->where('blogs_status', $status);
        }

        $users = $usersQuery->get()->getResult();

        if (!$users) {
            echo '<div class="alert alert-warning text-center">No users found for the selected filters.</div>';
            return;
        }

        $output = '';
        $i = 1;
        foreach ($users as $row) {
            $output .= view('include/admin/card/blog_card', [
                'row' => $row,
                'i' => $i++
            ]);
        }

        echo $output;
    }
}

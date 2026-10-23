<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Broadcast extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model = new \App\Models\BroadcastModel();
        $commonModel = new CommonModel();

        $perPage = 10;
        $broadcast = $model

            ->orderBy('broadcast_id', 'DESC')
            ->paginate($perPage, 'default');

        $pager = $model->pager;
        $chapters = $commonModel->exe_query("
        SELECT chapter_code, chapter_name
        FROM chapters
        WHERE chapter_status='1'
    ")->getResultArray();
        $data = [
            'broadcast' => $broadcast,
            'chapters' => $chapters,
            'pager'       => $pager,
            'perPage'     => $perPage
        ];

        return view('admin/broadcast', $data);
    }


    public function delete_broadcast($broadcast_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM broadcast  WHERE broadcast_code='" . esc($broadcast_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_broadcast_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="broadcast_code" value="' . esc($row->broadcast_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->title) . '</span></p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this broadcast?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_broadcast_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $broadcast_code = $this->request->getPost('broadcast_code');
        if (!$broadcast_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("broadcast", ['broadcast_code' => $broadcast_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ broadcast removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }

    public function edit_broadcast($broadcast_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM broadcast  WHERE broadcast_code='" . esc($broadcast_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
            <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
                      <form id="edit_broadcast_status_action" method="post">
                    <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
                        <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Broadcast Status</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <input type="hidden" name="broadcast_code" value="' . $row->broadcast_code . '">
                          <div class="row">
                
                          <div class="col-md 12 mb-3">
                            <label class="form-label">Member Status</label>
                              <select class="form-control" name="status"  required>';
        if ($row->status == 1) {
            $output .= '<option value="1" selected>Active</option>';
        } elseif ($row->status == 2) {
            $output .= '<option value="2" selected>Hold</option>';
        } else {
            $output .= '<option value="3" selected>Block</option>';
        }
        $output .= '
                                <option value="1">Active</option>
                                <option value="2">Hold</option>
                                <option value="3">Block</option>
                            </select>
                        </div>

                              <div class="col-md-6">
                            <label class="fw-bold mb-1">Title</label>
                            <input type="text" name="title"
                                class="form-control" value="' . $row->title . '"
                                placeholder="Enter Broadcast Title" required>
                        </div>
               
                        <!-- Message -->
                        <div class="col-md-12">
                            <label class="fw-bold mb-1">Message</label>
                            <textarea name="message"
                                class="form-control summernote"
                                rows="5"
                                required>' . $row->message . '</textarea>
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

    public function edit_broadcast_status_action()
    {
        $broadcast_code = $this->request->getPost('broadcast_code');
        if (!$broadcast_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'broadcast code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'title' => $this->request->getPost('title'),
            'message' => $this->request->getPost('message'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'broadcast',
            $data,
            ['broadcast_code' => $broadcast_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ broadcast updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
    public function AddNewBroadcastData()
    {
        helper(['form']);
        $commonModel = new CommonModel();

        $uploadPath = FCPATH . 'uploads/broadcast/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $chapter_code = $this->request->getPost('chapter_code');
        $title        = $this->request->getPost('title');
        $message      = $this->request->getPost('message');

        $imageName = '';
        $videoName = '';

        /* ================= IMAGE UPLOAD ================= */
        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && $image->getName() != '' && !$image->hasMoved()) {

            $imageName = time() . '_' . $image->getRandomName();
            $image->move($uploadPath, $imageName);
        }

        /* ================= VIDEO UPLOAD ================= */
        $video = $this->request->getFile('video');

        if ($video && $video->isValid() && $video->getName() != '' && !$video->hasMoved()) {

            $videoName = time() . '_' . $video->getRandomName();
            $video->move($uploadPath, $videoName);
        }

        /* ================= INSERT ================= */
        $broadcast_code = $commonModel->get_serial3('broadcast', 'NT', 'broadcast_code');

        $insert = [
            'broadcast_code' => $broadcast_code,
            'chapter_code'   => $chapter_code,
            'title'          => $title,
            'message'        => $message,
            'image'          => $imageName,   // blank if not selected
            'video'          => $videoName,   // blank if not selected
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $insertId = $commonModel->insert_data('broadcast', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create Broadcast'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Broadcast Created Successfully'
        ]);
    }
}

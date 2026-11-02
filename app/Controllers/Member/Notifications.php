<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Notifications extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $user_code = session()->get('user_code');
        $commonModel = new CommonModel();

        $limit = 5;

        // ================= UNREAD PAGE =================
        $unreadPage = (int) ($this->request->getGet('unread_page') ?? 1);
        $unreadOffset = ($unreadPage - 1) * $limit;

        $unreadCountQry = "
        SELECT COUNT(*) as total
        FROM notifications
        WHERE user_code = '$user_code'
        AND status = 'unread'
    ";
        $unreadTotal = $commonModel->exe_query($unreadCountQry)->getRow()->total;
        $unreadTotalPages = ceil($unreadTotal / $limit);

        $unreadQry = "
        SELECT *
        FROM notifications
        WHERE user_code = '$user_code'
        AND status = 'unread'
        ORDER BY created_at DESC
        LIMIT $limit OFFSET $unreadOffset
    ";
        $unread_notifications = $commonModel->exe_query($unreadQry)->getResult();


        // ================= READ PAGE =================
        $readPage = (int) ($this->request->getGet('read_page') ?? 1);
        $readOffset = ($readPage - 1) * $limit;

        $readCountQry = "
        SELECT COUNT(*) as total
        FROM notifications
        WHERE user_code = '$user_code'
        AND status = 'read'
    ";
        $readTotal = $commonModel->exe_query($readCountQry)->getRow()->total;
        $readTotalPages = ceil($readTotal / $limit);

        $readQry = "
        SELECT *
        FROM notifications
        WHERE user_code = '$user_code'
        AND status = 'read'
        ORDER BY created_at DESC
        LIMIT $limit OFFSET $readOffset
    ";
        $read_notifications = $commonModel->exe_query($readQry)->getResult();

        $data = [
            'unread_notifications' => $unread_notifications,
            'read_notifications'   => $read_notifications,
            'unreadPage' => $unreadPage,
            'readPage' => $readPage,
            'unreadTotalPages' => $unreadTotalPages,
            'readTotalPages' => $readTotalPages,
        ];

        return view('member/notifications', $data);
    }

    public function edi_notf($n_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM notifications  WHERE n_code='" . esc($n_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
            <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
                      <form id="edit_not_status_action" method="post">
                    <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
                        <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Refferral Status</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <input type="hidden" name="n_code" value="' . $row->n_code . '">
                          <div class="row">
                
                        <div class="col-md-12">
                            <label class="fw-bold">Referral status</label>
                            <select name="status" class="form-control">
                                <option value="' . $row->status . '">' . $row->status . '</option>
                                   <option value="">Select Status</option>
                                        <option value="read">read</option>
                                        <option value="unread">unread</option>
                            </select>
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


    public function edit_not_status_action()
    {
        $n_code = $this->request->getPost('n_code');
        if (!$n_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Notifications code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'notifications',
            $data,
            ['n_code' => $n_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Notifications updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }

    public function edit_not_status_action2()
    {
        $n_code = $this->request->getPost('n_code');

        if (!$n_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Notifications code missing'
            ]);
        }

        $data = [
            'status' => "read",
        ];

        $model = new CommonModel();

        $update = $model->update_row(
            'notifications',
            $data,
            ['n_code' => $n_code]
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Notifications updated successfully!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
}

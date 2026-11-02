<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Notifications extends BaseController
{

    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $commonModel = new CommonModel();

        /*
    |--------------------------------------------------------------------------
    | Pagination Settings
    |--------------------------------------------------------------------------
    */

        $page = (int) $this->request->getGet('page');

        if ($page < 1) {
            $page = 1;
        }

        // Allowed page sizes
        $allowedLimits = [9, 18, 30, 50];

        $limit = (int) $this->request->getGet('limit');

        if (!in_array($limit, $allowedLimits, true)) {
            $limit = 9;
        }

        /*
    |--------------------------------------------------------------------------
    | Total Notifications
    |--------------------------------------------------------------------------
    */

        $totalRow = $commonModel->exe_query("
        SELECT COUNT(*) AS total
        FROM notifications
    ")->getRow();

        $total = (int) ($totalRow->total ?? 0);

        /*
    |--------------------------------------------------------------------------
    | Calculate Pagination
    |--------------------------------------------------------------------------
    */

        $totalPages = $total > 0
            ? (int) ceil($total / $limit)
            : 1;

        // Prevent invalid page number
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $limit;

        /*
    |--------------------------------------------------------------------------
    | Get Notifications
    |--------------------------------------------------------------------------
    */

        $qry = "
        SELECT *
        FROM notifications
        ORDER BY created_at DESC
        LIMIT {$limit} OFFSET {$offset}
    ";

        $notifications = $commonModel
            ->exe_query($qry)
            ->getResult();

        /*
    |--------------------------------------------------------------------------
    | Chapters
    |--------------------------------------------------------------------------
    */

        $chapters = $commonModel->exe_query("
        SELECT chapter_code, chapter_name
        FROM chapters
        WHERE chapter_status = '1'
        ORDER BY chapter_name ASC
    ")->getResultArray();

        /*
    |--------------------------------------------------------------------------
    | Pagination Range
    |--------------------------------------------------------------------------
    */

        $startRecord = $total > 0
            ? $offset + 1
            : 0;

        $endRecord = min(
            $offset + $limit,
            $total
        );

        /*
    |--------------------------------------------------------------------------
    | Send Data To View
    |--------------------------------------------------------------------------
    */

        return view('admin/notifications', [

            'chapters'      => $chapters,

            'notifications' => $notifications,

            'total'         => $total,

            'limit'         => $limit,

            'currentPage'   => $page,

            'totalPages'    => $totalPages,

            'startRecord'   => $startRecord,

            'endRecord'     => $endRecord
        ]);
    }


    public function get_members_by_chapter()
    {
        $db = \Config\Database::connect();

        $chapter_code = $this->request->getPost('chapter_code');

        if (!$chapter_code) {
            return $this->response->setJSON([]);
        }

        $data = $db->query("
        SELECT user_code, name 
        FROM chapter_members 
        WHERE chapter_code = ?
    ", [$chapter_code])->getResultArray();

        return $this->response->setJSON($data);
    }
    public function delete_notf($n_code)
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
    <form id="delete_notf_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="n_code" value="' . esc($row->n_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->message) . '</span></p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Meeting?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_notf_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $n_code = $this->request->getPost('n_code');
        if (!$n_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("notifications", ['n_code' => $n_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Notifications removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
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

    public function AddNewNotificatongData()
    {
        helper(['form']);
        $commonModel = new CommonModel();

        /* ================= VALIDATION ================= */
        $rules = [
            'chapter_code' => 'required',
            'title'        => 'required',
            'message'      => 'required',
            'user_code'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $this->validator->listErrors()
            ]);
        }

        $data = $this->request->getPost();

        $chapter_code = $data['chapter_code'];
        $user_codes   = $this->request->getPost('user_code'); // ARRAY
        $title        = $data['title'];
        $message      = $data['message'];

        /* ================= NOTIFICATION CODE ================= */
        $n_code = $commonModel->get_serial3('notifications', 'NT', 'n_code');

        $insert = [
            'n_code'       => $n_code,
            'chapter_code' => $chapter_code,
            'user_code'    => is_array($user_codes) ? implode(',', $user_codes) : $user_codes,
            'title'        => $title,
            'message'      => $message,
            'created_at'   => date('Y-m-d H:i:s')
        ];

        $insertId = $commonModel->insert_data('notifications', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to create notification'
            ]);
        }

        /* ================= EMAIL CONFIG ================= */
        $emailService = \Config\Services::email();

        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => 'smtp.hostinger.com',
            'SMTPUser'   => 'mail@eafconnect.com',
            'SMTPPass'   => 'mail@WHB26',
            'SMTPPort'   => 465,
            'SMTPCrypto' => 'ssl',
            'mailType'   => 'html',
            'charset'    => 'utf-8',
            'newline'    => "\r\n",
            'CRLF'       => "\r\n",
            'SMTPTimeout' => 30,
        ];

        $emailService->initialize($config);
        $emailService->setFrom('mail@eafconnect.com', 'eafconnect');

        /* ================= CHECK ALL OR SELECTED ================= */

        $members = [];

        if (in_array('all', $user_codes)) {

            // ALL MEMBERS
            $members = $commonModel->exe_query("
            SELECT email, name
            FROM chapter_members
            WHERE chapter_code = '$chapter_code'
            AND email IS NOT NULL
        ")->getResult();
        } else {

            // SELECTED MEMBERS
            $codes = implode("','", $user_codes);

            $members = $commonModel->exe_query("
            SELECT email, name
            FROM chapter_members
            WHERE user_code IN ('$codes')
            AND email IS NOT NULL
        ")->getResult();
        }

        /* ================= SEND EMAIL ================= */

        foreach ($members as $m) {

            if (empty($m->email)) continue;

            $emailService->clear(true);

            $emailService->setTo($m->email);
            $emailService->setSubject($title);

            $messageBody = view('mail/notification_template', [
                'name'    => $m->name,
                'title'   => $title,
                'message' => $message
            ]);

            $emailService->setMessage($messageBody);

            if (!$emailService->send()) {
                log_message('error', $emailService->printDebugger(['headers', 'subject', 'body']));
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Notification created & Emails sent successfully'
        ]);
    }
}

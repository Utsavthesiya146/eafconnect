<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\OneToOneModel;

class F2f extends BaseController
{
    public function index()
    {
        /*
    |--------------------------------------------------------------------------
    | LOGIN CHECK
    |--------------------------------------------------------------------------
    */
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        /*
    |--------------------------------------------------------------------------
    | MODELS
    |--------------------------------------------------------------------------
    */
        $model       = new OneToOneModel();
        $commonModel = new CommonModel();

        $user_code = session()->get('user_code');


        /*
    |--------------------------------------------------------------------------
    | REQUEST VALUES
    |--------------------------------------------------------------------------
    */
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');
        $tab     = $this->request->getGet('tab') ?? 'initiated_by_me';


        /*
    |--------------------------------------------------------------------------
    | MAIN F2F QUERY
    |--------------------------------------------------------------------------
    */
        $builder = $model;

        $builder->select("
        one_to_ones.*,

        from_user.name AS from_name,
        from_user.email AS from_email,
        COALESCE(from_user.profile_photo, 'empty.png') AS from_profile,

        to_user.name AS to_name,
        to_user.email AS to_email,
        COALESCE(to_user.profile_photo, 'empty.png') AS to_profile
    ");


        /*
    |--------------------------------------------------------------------------
    | JOIN FROM USER
    |--------------------------------------------------------------------------
    */
        $builder->join(
            'users AS from_user',
            'from_user.user_code = one_to_ones.from_user',
            'left'
        );


        /*
    |--------------------------------------------------------------------------
    | JOIN TO USER
    |--------------------------------------------------------------------------
    */
        $builder->join(
            'users AS to_user',
            'to_user.user_code = one_to_ones.to_user',
            'left'
        );


        /*
    |--------------------------------------------------------------------------
    | TAB FILTER
    |--------------------------------------------------------------------------
    */

        if ($tab === 'initiated_with_me') {

            /*
        | Other members created F2F with me
        */
            $builder->where(
                'one_to_ones.to_user',
                $user_code
            );
        } else {

            /*
        | F2F created by me
        */
            $builder->where(
                'one_to_ones.from_user',
                $user_code
            );
        }


        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if (!empty($search)) {

            $builder->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('one_to_ones.location', $search)
                ->groupEnd();
        }


        /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

        if (!empty($status)) {

            $builder->where(
                'one_to_ones.status',
                $status
            );
        }


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'one_to_ones.f2f_id',
            'DESC'
        );


        /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

        $data['connect'] = $builder->paginate(
            $perPage,
            'default',
            $page
        );

        $data['pager']   = $builder->pager;
        $data['perPage'] = $perPage;
        $data['tab']     = $tab;


        /*
    |--------------------------------------------------------------------------
    | DATABASE
    |--------------------------------------------------------------------------
    */

        $db = \Config\Database::connect();


        /*
    |--------------------------------------------------------------------------
    | CARD COMMON FILTER FUNCTION
    |--------------------------------------------------------------------------
    |
    | Applies the same filters to:
    |
    | 1. All
    | 2. This Week
    | 3. Current Month
    | 4. Last Month
    |
    */

        $applyCardFilters = function ($query) use (
            $user_code,
            $tab,
            $status,
            $search
        ) {

            /*
        |--------------------------------------------------------------
        | TAB FILTER
        |--------------------------------------------------------------
        */

            if ($tab === 'initiated_with_me') {

                $query->where(
                    'one_to_ones.to_user',
                    $user_code
                );
            } else {

                $query->where(
                    'one_to_ones.from_user',
                    $user_code
                );
            }


            /*
        |--------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------
        */

            if (!empty($status)) {

                $query->where(
                    'one_to_ones.status',
                    $status
                );
            }


            /*
        |--------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------
        */

            if (!empty($search)) {

                $query->groupStart()
                    ->like('one_to_ones.location', $search)
                    ->groupEnd();
            }
        };


        /*
    |--------------------------------------------------------------------------
    | ALL COUNT
    |--------------------------------------------------------------------------
    */

        $allBuilder = $db->table('one_to_ones');

        $applyCardFilters($allBuilder);

        $data['allCount'] =
            $allBuilder->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | THIS WEEK
    |--------------------------------------------------------------------------
    |
    | Monday 00:00:00
    | to
    | Sunday 23:59:59
    |
    */

        $startOfWeek = date(
            'Y-m-d 00:00:00',
            strtotime('monday this week')
        );

        $endOfWeek = date(
            'Y-m-d 23:59:59',
            strtotime('sunday this week')
        );


        $weekBuilder = $db->table('one_to_ones');

        $applyCardFilters($weekBuilder);

        $weekBuilder
            ->where(
                'one_to_ones.created_at >=',
                $startOfWeek
            )
            ->where(
                'one_to_ones.created_at <=',
                $endOfWeek
            );

        $data['weekCount'] =
            $weekBuilder->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | CURRENT MONTH
    |--------------------------------------------------------------------------
    */

        $startOfMonth = date(
            'Y-m-01 00:00:00'
        );

        $endOfMonth = date(
            'Y-m-t 23:59:59'
        );


        $monthBuilder = $db->table('one_to_ones');

        $applyCardFilters($monthBuilder);

        $monthBuilder
            ->where(
                'one_to_ones.created_at >=',
                $startOfMonth
            )
            ->where(
                'one_to_ones.created_at <=',
                $endOfMonth
            );

        $data['monthCount'] =
            $monthBuilder->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | LAST MONTH
    |--------------------------------------------------------------------------
    */

        $startOfLastMonth = date(
            'Y-m-01 00:00:00',
            strtotime('first day of last month')
        );

        $endOfLastMonth = date(
            'Y-m-t 23:59:59',
            strtotime('last day of last month')
        );


        $lastMonthBuilder = $db->table('one_to_ones');

        $applyCardFilters($lastMonthBuilder);

        $lastMonthBuilder
            ->where(
                'one_to_ones.created_at >=',
                $startOfLastMonth
            )
            ->where(
                'one_to_ones.created_at <=',
                $endOfLastMonth
            );

        $data['lastMonthCount'] =
            $lastMonthBuilder->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | MAKE SURE COUNTS ARE INTEGER
    |--------------------------------------------------------------------------
    */

        $data['allCount'] = (int) (
            $data['allCount'] ?? 0
        );

        $data['weekCount'] = (int) (
            $data['weekCount'] ?? 0
        );

        $data['monthCount'] = (int) (
            $data['monthCount'] ?? 0
        );

        $data['lastMonthCount'] = (int) (
            $data['lastMonthCount'] ?? 0
        );


        /*
    |--------------------------------------------------------------------------
    | MEMBER / CHAPTER DATA
    |--------------------------------------------------------------------------
    */

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


        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'member/f2f',
            $data
        );
    }


    public function addNewf2fData()
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

        // ✅ GENERATE CODE
        $f2f_code = $commonModel->generateSerial('one_to_ones', 'f2f_code', 'F2F');

        // ✅ FORMAT DATE (important)
        $date = !empty($data['date'])
            ? date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])))
            : null;

        // ✅ INSERT DATA
        $insert = [
            'f2f_code'   => $f2f_code,
            'from_user'  => (string)$user_code,
            'to_user'    => $data['to_user'] ?? null,
            'date'       => $date,
            'location'   => $data['location'] ?? null,
            'summary'    => $data['summary'] ?? null,
            'status'     => 'scheduled',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $insertId = $commonModel->insert_data('one_to_ones', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add 121'
            ]);
        }

        if (!empty($insert['to_user'])) {
            // Get Receiver Details
            $toUser = $commonModel->db->table('users')
                ->select('name,email')
                ->where('user_code', $insert['to_user'])
                ->get()
                ->getRow();

            // Get Sender Details
            $fromUser = $commonModel->db->table('users')
                ->select('name')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();

            // Notify Receiver
            create_system_notification(
                $insert['to_user'],
                'New 1-to-1 Scheduled',
                ($fromUser->name ?? 'A member') . ' scheduled a 1-to-1 meeting with you for ' . ($date ? date('d M Y', strtotime($date)) : 'an upcoming date') . '.'
            );

            // Notify Sender (Confirmation)
            create_system_notification(
                $user_code,
                '1-to-1 Meeting Scheduled',
                'You scheduled a 1-to-1 meeting with ' . ($toUser->name ?? 'member') . ' for ' . ($date ? date('d M Y', strtotime($date)) : 'an upcoming date') . '.'
            );
        }

        // ==============================
        // ✅ EMAIL NOTIFICATION
        // ==============================
        if (!empty($insert['to_user'])) {

            // 👉 RECEIVER
            $toUser = $commonModel->db->table('users')
                ->select('name,email')
                ->where('user_code', $insert['to_user'])
                ->get()
                ->getRow();

            // 👉 SENDER
            $fromUser = $commonModel->db->table('users')
                ->select('*')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();

            if ($toUser && !empty($toUser->email)) {

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
                    'SMTPTimeout' => 30,
                ];

                $emailService->initialize($config);
                $emailService->clear(true);

                $emailService->setFrom('mail@eafconnect.com', 'eafconnect');
                $emailService->setTo($toUser->email);



                $emailService->setSubject('New One-to-One Meeting Scheduled');

                // ✅ LOAD EMAIL VIEW
                $message = view('mail/f2f', [
                    'name'      => $toUser->name ?? 'User',
                    'from_name' => $fromUser->name ?? 'Member',
                    'contact'   => $fromUser->mobile ?? $fromUser->phone ?? 'N/A',
                    'date'      => $date,
                    'location'  => $insert['location'],
                    'summary'   => $insert['summary'],
                ]);

                $emailService->setMessage($message);

                if (!$emailService->send()) {
                    echo "<pre>";
                    print_r($emailService->printDebugger(['headers', 'subject', 'body']));
                    die;
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '121 Added Successfully & Email Sent'
        ]);
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
        $currentStatus = strtolower(trim((string)($row->status ?? '')));
        if ($currentStatus === '') {
            $currentStatus = 'scheduled';
        }
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
                <div class="col-md-12">
                    <label class="fw-bold mb-1">Status</label>
                    <select class="form-select" name="status">
                        <option value="scheduled"' . ($currentStatus === 'scheduled' ? ' selected' : '') . '>Scheduled</option>
                        <option value="completed"' . ($currentStatus === 'completed' ? ' selected' : '') . '>Completed</option>
                        <option value="re-scheduled"' . ($currentStatus === 're-scheduled' || $currentStatus === 'rescheduled' ? ' selected' : '') . '>Re-scheduled</option>
                        <option value="cancelled"' . ($currentStatus === 'cancelled' ? ' selected' : '') . '>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold mb-1">Summary / Discussion Notes</label>
                    <textarea name="summary" class="form-control" rows="3">' . esc($row->summary) . '</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white px-3" style="background-color:#1b4d8f;">Update Status</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function edit_f2f_action()
    {
        $f2f_code = $this->request->getPost('f2f_code');
        if (!$f2f_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'F2F meeting code missing'
            ]);
        }

        $db = \Config\Database::connect();
        $f2f = $db->table('one_to_ones')
            ->where('f2f_code', $f2f_code)
            ->get()
            ->getRow();

        if (!$f2f) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'F2F meeting not found'
            ]);
        }

        $newStatus = trim((string)$this->request->getPost('status'));
        $data = [
            'status'  => $newStatus,
            'summary' => $this->request->getPost('summary'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'one_to_ones',
            $data,
            ['f2f_code' => $f2f_code]
        );

        if ($update) {
            $currentUserId = session()->get('user_code');
            $currentUserName = session()->get('name') ?: 'A member';
            $fromUser = (string)($f2f->from_user ?? '');
            $toUser = (string)($f2f->to_user ?? '');
            $meetingDate = !empty($f2f->date) ? date('d M Y', strtotime($f2f->date)) : '';

            $partnerCode = ($currentUserId == $fromUser) ? $toUser : $fromUser;

            // Fetch partner name
            $partnerRow = $db->table('users')->select('name')->where('user_code', $partnerCode)->get()->getRow();
            $partnerName = $partnerRow->name ?? 'Member';

            // Notify partner
            if (!empty($partnerCode)) {
                create_system_notification(
                    $partnerCode,
                    '1-to-1 Status: ' . ucfirst($newStatus),
                    $currentUserName . ' updated the status of your 1-to-1 meeting' . ($meetingDate ? " ({$meetingDate})" : "") . ' to "' . ucfirst($newStatus) . '".'
                );
            }

            // Confirm to current user
            if (!empty($currentUserId)) {
                create_system_notification(
                    $currentUserId,
                    '1-to-1 Status Updated',
                    'You updated the 1-to-1 meeting status with ' . $partnerName . ($meetingDate ? " ({$meetingDate})" : "") . ' to "' . ucfirst($newStatus) . '".'
                );
            }

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
        $search  = trim((string)$request->getPost('search'));
        $date    = trim((string)$request->getPost('date'));
        $status  = trim((string)$request->getPost('status'));
        $to_user = trim((string)$request->getPost('to_user'));
        $tab     = trim((string)($request->getPost('tab') ?: 'initiated_by_me'));

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

        // Tab filter & Member With filter
        if ($tab === 'initiated_with_me') {
            $model->where('one_to_ones.to_user', $user_code);
            if (!empty($to_user)) {
                $model->where('one_to_ones.from_user', $to_user);
            }
        } else {
            $model->where('one_to_ones.from_user', $user_code);
            if (!empty($to_user)) {
                $model->where('one_to_ones.to_user', $to_user);
            }
        }

        // ✅ SEARCH
        if (!empty($search)) {
            $model->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('one_to_ones.location', $search)
                ->orLike('one_to_ones.topics', $search)
                ->orLike('one_to_ones.summary', $search)
                ->groupEnd();
        }

        // ✅ STATUS
        if (!empty($status)) {
            $st = strtolower($status);
            if ($st === 're-scheduled' || $st === 'rescheduled') {
                $model->groupStart()
                    ->where('LOWER(one_to_ones.status)', 're-scheduled')
                    ->orWhere('LOWER(one_to_ones.status)', 'rescheduled')
                    ->groupEnd();
            } else {
                $model->where('LOWER(one_to_ones.status)', $st);
            }
        }

        // ✅ DATE
        if (!empty($date)) {
            $model->where('one_to_ones.date', $date);
        }

        $model->orderBy('one_to_ones.f2f_id', 'DESC');

        $users = $model->findAll();

        if (empty($users)) {
            return '<tr>
                <td colspan="8" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center justify-content-center py-3">
                        <div style="width:54px; height:54px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:24px; margin-bottom:12px;">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size:15px;">No F2F Meetings Found</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">There are no One-to-One records matching your search or filter criteria.</p>
                    </div>
                </td>
            </tr>';
        }

        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/member/card/f2f_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }
}

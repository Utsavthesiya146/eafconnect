<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Chapter extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model = new \App\Models\ChapterModel();
        $commonModel = new CommonModel();

        $search  = $this->request->getGet('search');
        $perPage = $this->request->getGet('perPage') ?? 10;

        $model->select('chapters.*, users.name as leader_name')
            ->join('users', 'users.user_code = chapters.leader_code', 'left');

        if (!empty($search)) {
            $model->groupStart()
                ->like('chapters.chapter_name', $search)
                ->orLike('chapters.location', $search)
                ->groupEnd();
        }

        $model->orderBy("FIELD(chapter_status,1,2,3)", "", false)
            ->orderBy("chapters_id", "ASC");

        /* ==============================
       GET CHAPTERS
    ==============================*/
        $chapters = $model->paginate($perPage);

        /* ==============================
       SEPARATE MEMBER COUNT QUERY
    ==============================*/
        $counts = $commonModel->exe_query("
    SELECT 
        cm.chapter_code,
        COUNT(cm.member_id) AS total
    FROM chapter_members cm
    INNER JOIN users u
        ON u.user_code = cm.user_code
    WHERE cm.members_status = '1'
      AND u.status = '1'
    GROUP BY cm.chapter_code
")->getResultArray();

        // convert to key-value array
        $memberCounts = [];
        foreach ($counts as $c) {
            $memberCounts[$c['chapter_code']] = $c['total'];
        }

        // attach count to chapters
        foreach ($chapters as &$ch) {
            $code = $ch['chapter_code'];
            $ch['member_count'] = $memberCounts[$code] ?? 0;
        }

        /* ==============================
       MEMBER LIST
    ==============================*/
        $member = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();

        $data = [
            'chapters' => $chapters,
            'pager'    => $model->pager,
            'search'   => $search,
            'member'   => $member
        ];

        return view('admin/chapter', $data);
    }

    public function details($chapter_code)
    {
        $db = \Config\Database::connect();

        $commonModel = new \App\Models\CommonModel();
        $mitbModel   = new \App\Models\TyfcbModel();

        /* =====================================================
       1. CHAPTER DETAILS
    ===================================================== */

        $qry = "
        SELECT 
            c.*,

            (
                SELECT COUNT(*)
                FROM chapter_members cm
                INNER JOIN users u
                    ON u.user_code = cm.user_code
                WHERE cm.chapter_code = c.chapter_code
                  AND cm.members_status = '1'
                  AND u.status = '1'
            ) AS member_count

        FROM chapters c

        WHERE c.chapter_code = ?
    ";

        $chapter = $db
            ->query($qry, [$chapter_code])
            ->getResultArray();


        /* =====================================================
       2. CHAPTER MEMBERS
       Only ACTIVE chapter members + ACTIVE users
    ===================================================== */

        $qry2 = "
        SELECT 
            cm.*,

            cm.role AS member_role,
            cm.joined_date AS join_date,
            cm.members_status AS member_status,

            u.name,
            u.email,
            u.phone,
            u.profile_photo,
            u.city,
            u.status AS user_status,

            c.chapter_name

        FROM chapter_members cm

        INNER JOIN users u
            ON cm.user_code = u.user_code

        INNER JOIN chapters c
            ON cm.chapter_code = c.chapter_code

        WHERE cm.chapter_code = ?
          AND cm.members_status = '1'
          AND u.status = '1'

        ORDER BY cm.member_code ASC
    ";

        $users = $db
            ->query($qry2, [$chapter_code])
            ->getResultArray();


        /* =====================================================
       3. ACTIVE USER LIST FOR REFERRED BY
       Include chapter_name
    ===================================================== */

        $member = $commonModel->exe_query("
        SELECT DISTINCT
            u.user_code,
            u.name,
            c.chapter_name

        FROM users u

        INNER JOIN chapter_members cm
            ON cm.user_code = u.user_code

        INNER JOIN chapters c
            ON c.chapter_code = cm.chapter_code

        WHERE u.status = '1'
          AND cm.members_status = '1'

        ORDER BY u.name ASC
    ")->getResultArray();


        /* =====================================================
       4. GET MEMBER USER CODES
       ACTIVE MEMBERS OF THIS CHAPTER ONLY
    ===================================================== */

        $memberQry = "
        SELECT DISTINCT
            cm.user_code

        FROM chapter_members cm

        INNER JOIN users u
            ON u.user_code = cm.user_code

        WHERE cm.chapter_code = ?
          AND cm.members_status = '1'
          AND u.status = '1'
    ";

        $chapterMembers = $db
            ->query($memberQry, [$chapter_code])
            ->getResultArray();

        $chapterMemberCodes = array_column(
            $chapterMembers,
            'user_code'
        );


        /* =====================================================
       5. STATS CALCULATIONS
    ===================================================== */

        $meetings           = 0;
        $referralsGiven     = 0;
        $referralsReceived  = 0;
        $connects           = 0;
        $visitors           = 0;
        $notifications      = 0;
        $totalMITBReceived  = 0;
        $totalMITBGiven     = 0;


        if (!empty($chapterMemberCodes)) {

            /*
        |--------------------------------------------------------------------------
        | Escape member codes safely
        |--------------------------------------------------------------------------
        */

            $escapedCodes = array_map(
                fn($code) => $db->escape($code),
                $chapterMemberCodes
            );

            $memberList = implode(',', $escapedCodes);


            /* =================================================
           ONE TO ONE MEETINGS
        ================================================= */

            $meetings = $commonModel->exe_query("
            SELECT *
            FROM one_to_ones

            WHERE from_user IN ($memberList)

               OR to_user IN ($memberList)
        ")->getNumRows();


            /* =================================================
           REFERRALS GIVEN
        ================================================= */

            $referralsGiven = $commonModel->exe_query("
            SELECT *
            FROM referrals

            WHERE from_user IN ($memberList)
        ")->getNumRows();


            /* =================================================
           REFERRALS RECEIVED
        ================================================= */

            $referralsReceived = $commonModel->exe_query("
            SELECT *
            FROM referrals

            WHERE to_user IN ($memberList)
        ")->getNumRows();


            /* =================================================
           TOTAL CONNECTS
        ================================================= */

            $connects =
                $referralsGiven +
                $referralsReceived;


            /* =================================================
           VISITORS
        ================================================= */

            $visitors = $commonModel->exe_query("
            SELECT *
            FROM visitors

            WHERE user_code IN ($memberList)
        ")->getNumRows();


            /* =================================================
           NOTIFICATIONS
        ================================================= */

            $notifications = $commonModel->exe_query("
            SELECT *
            FROM notifications

            WHERE user_code IN ($memberList)
        ")->getNumRows();


            /* =================================================
           MITB RECEIVED
        ================================================= */

            $mitbReceived = $mitbModel
                ->selectSum('amount')
                ->whereIn(
                    'from_user',
                    $chapterMemberCodes
                )
                ->first();

            $totalMITBReceived = $mitbReceived['amount'] ?? 0;


            /* =================================================
           MITB GIVEN
        ================================================= */

            $mitbGiven = $mitbModel
                ->selectSum('amount')
                ->whereIn(
                    'to_user',
                    $chapterMemberCodes
                )
                ->first();

            $totalMITBGiven = $mitbGiven['amount'] ?? 0;
        }


        /* =====================================================
       6. TOTAL MITB
    ===================================================== */

        $totalMITB = $totalMITBGiven;


        /* =====================================================
       7. LOAD VIEW
    ===================================================== */

        return view('admin/chapter_details', [

            'chapters'       => $chapter,

            'users'          => $users,

            'member'         => $member,

            'meetings'       => $meetings,

            'connects'       => $connects,

            'visitors'       => $visitors,

            'notifications'  => $notifications,

            'totalMITB'      => $totalMITB,

            'businessDone'   => $totalMITBReceived
        ]);
    }

    public function AddNewChapterData()
    {
        helper(['form']);
        $commonModel = new \App\Models\CommonModel();
        // ✅ GET POST SAFELY
        $data = $this->request->getPost();
        // ✅ GENERATE CHAPTER CODE
        $chapter_code = $commonModel->get_serial3(
            'chapters',
            'CHP',
            'chapter_code' // ← MUST match DB column
        );
        $photo = 'empty.png';
        $file  = $this->request->getFile('chapter_profile');

        if ($file && $file->isValid() && !$file->hasMoved() && $file->getName() != '') {
            $uploadPath = FCPATH . 'uploads/profile/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $photo = $newName;
        }
        /* ================= INSERT DATA ================= */

        $insert = [
            'chapter_code'        => $chapter_code,
            'chapter_name'        => $data['chapter_name'] ?? '',
            'location'            => $data['location'] ?? '',
            'leader_code'         => $data['leader_code'] ?? null,
            'chapter_profile'     => $photo,
            'chapter_title'       => $data['chapter_title'] ?? '',
            'chapter_description' => $data['chapter_description'] ?? '',
            'chapter_status'      => 1,
        ];

        $commonModel->insert_data('chapters', $insert);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Chapter Created Successfully'
        ]);
    }


    public function delete_chapter($chapter_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM chapters  WHERE chapter_code='" . esc($chapter_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_chapter_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="chapter_code" value="' . esc($row->chapter_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;">Name: <span style="color:#05008D;">' . esc($row->chapter_name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->chapter_title) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->location) . '</p>
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

    public function delete_chapter_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $chapter_code = $this->request->getPost('chapter_code');
        if (!$chapter_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("chapters", ['chapter_code' => $chapter_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Chapter removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }








    public function edit_chapter_status($chapter_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM chapters  WHERE  chapter_code='" . esc($chapter_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
            <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
                      <form id="edit_chapter_status_action" method="post">
                    <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
                        <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Chapter Status</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <input type="hidden" name="chapter_code" value="' . $row->chapter_code . '">
                          <div class="row">
                        <div class="col-md 12 mb-2">
                            <label class="form-label">Member Status</label>
                              <select class="form-control" name="chapter_status"  required>';
        if ($row->chapter_status == 1) {
            $output .= '<option value="1" selected>Active</option>';
        } elseif ($row->chapter_status == 2) {
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
                      
                        <div class="col-md-12">
                      <div class="col-md-12">
                            <label class="fw-bold">Note</label>
                            <textarea name="admin_note" class="form-control">' . $row->admin_note . '</textarea>
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
    public function edit_chapter_status_action()
    {
        $chapter_code = $this->request->getPost('chapter_code');
        if (!$chapter_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'chapter_code missing'
            ]);
        }
        $data = [
            'chapter_status' => $this->request->getPost('chapter_status'),
            'admin_note' => $this->request->getPost('admin_note'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'chapters',
            $data,
            ['chapter_code' => $chapter_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Chapter updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }




    public function edit_chapter($chapter_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM chapters WHERE chapter_code='" . esc($chapter_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody(
                '<p class="text-center text-danger">User not found!</p>'
            );
        }
        $row = $user[0];
        $members = $commonModel
            ->exe_query("SELECT * FROM users WHERE status='1'")
            ->getResultArray();
        $leaderOptions = '';
        foreach ($members as $member) {
            $selected = ($member['user_code'] == $row->leader_code)
                ? 'selected'
                : '';
            $leaderOptions .= '
            <option value="' . esc($member['user_code']) . '" ' . $selected . '>
                ' . esc($member['name']) . ' (' . esc($member['user_code']) . ')
            </option>';
        }
        /* ===============================
       MODAL HTML
    ===============================*/
        $output = '

<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="edit_chapter_action" method="post">

        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#ffffff;">Edit Chapter</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-3">

            <input type="hidden" name="chapter_code" value="' . esc($row->chapter_code) . '">

            <div class="row g-2">

                <div class="col-md-12">
                    <label class="fw-bold">Chapter Name</label>
                    <input type="text" name="chapter_name" class="form-control"
                        value="' . esc($row->chapter_name) . '" required>
                </div>

                <div class="col-md-12">
                    <label class="fw-bold">Location</label>
                    <input type="text" name="location" class="form-control"
                        value="' . esc($row->location) . '">
                </div>

                <div class="col-md-12">
                    <label class="fw-bold">Chapter Leader</label>
                    <select name="leader_code" class="form-select">
                        ' . $leaderOptions . '
                    </select>
                </div>
    <div class="col-md-12">
    <label class="fw-bold">Meeting Day</label>
    <select name="meeting_day" class="form-control">
        <option value="">Select Meeting Day</option>

        <option value="Sunday" ' . (($row->meeting_day ?? '') === 'Sunday' ? 'selected' : '') . '>Sunday</option>
        <option value="Monday" ' . (($row->meeting_day ?? '') === 'Monday' ? 'selected' : '') . '>Monday</option>
        <option value="Tuesday" ' . (($row->meeting_day ?? '') === 'Tuesday' ? 'selected' : '') . '>Tuesday</option>
        <option value="Wednesday" ' . (($row->meeting_day ?? '') === 'Wednesday' ? 'selected' : '') . '>Wednesday</option>
        <option value="Thursday" ' . (($row->meeting_day ?? '') === 'Thursday' ? 'selected' : '') . '>Thursday</option>
        <option value="Friday" ' . (($row->meeting_day ?? '') === 'Friday' ? 'selected' : '') . '>Friday</option>
        <option value="Saturday" ' . (($row->meeting_day ?? '') === 'Saturday' ? 'selected' : '') . '>Saturday</option>
    </select>
</div>
                <div class="col-md-12">
                    <label class="fw-bold">Chapter Title</label>
                    <input type="text" name="chapter_title" class="form-control"
                        value="' . esc($row->chapter_title) . '">
                </div>

                <div class="col-md-12">
                    <label class="fw-bold">About Chapter</label>
                    <textarea name="chapter_description" class="form-control">' . esc($row->chapter_description) . '</textarea>
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
    public function edit_chapter_action()
    {
        $chapter_code = $this->request->getPost('chapter_code');
        if (!$chapter_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'chapter_code missing'
            ]);
        }
        $data = [
            'chapter_name' => $this->request->getPost('chapter_name'),
            'location' => $this->request->getPost('location'),
            'leader_code' => $this->request->getPost('leader_code'),
            'meeting_day' => $this->request->getPost('meeting_day'),
            'chapter_title' => $this->request->getPost('chapter_title'),
            'chapter_description' => $this->request->getPost('chapter_description'),

        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'chapters',
            $data,
            ['chapter_code' => $chapter_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Chapter updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }

    public function add_chapter_members($chapter_code)
    {
        $commonModel = new CommonModel();

        // GET CHAPTER
        $qry = "SELECT * FROM chapters WHERE chapter_code='" . esc($chapter_code) . "'";
        $chapter = $commonModel->exe_query($qry)->getRow();

        if (!$chapter) {
            return $this->response->setBody(
                '<p class="text-danger text-center">Chapter not found!</p>'
            );
        }

        /* ===============================
       GET EXISTING MEMBER USER CODES
    ===============================*/
        $existQry = "
        SELECT user_code
        FROM chapter_members
        WHERE chapter_code='" . esc($chapter_code) . "'
    ";

        $existing = $commonModel->exe_query($existQry)->getResultArray();

        $existingCodes = array_column($existing, 'user_code');

        $notIn = '';
        if (!empty($existingCodes)) {
            $notIn = " AND user_code NOT IN ('" .
                implode("','", $existingCodes) . "')";
        }

        /* ===============================
       GET AVAILABLE USERS
    ===============================*/
        /* ===============================
   GET AVAILABLE USERS (NOT IN ANY CHAPTER)
===============================*/
        $members = $commonModel->exe_query("
    SELECT u.*
    FROM users u
    WHERE u.status = '1'
    AND NOT EXISTS (
        SELECT 1 
        FROM chapter_members cm 
        WHERE cm.user_code = u.user_code
    )
")->getResultArray();

        /* ===============================
       BUILD DROPDOWN
    ===============================*/
        $memberOptions = '<option value="">-- Select Member --</option>';

        foreach ($members as $member) {
            $memberOptions .= '
        <option value="' . esc($member['user_code']) . '">
            ' . esc($member['name']) . ' (' . esc($member['user_code']) . ')
        </option>';
        }

        /* ===============================
       MODAL
    ===============================*/
        $output = '
<div class="modal-content">
<form id="add_chapter_members_action">

<div class="modal-header" style="background:#1b4d8f;color:#fff;">
<h6 class="modal-title" style="color:#ffffff;">Add Chapter Members</h6>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="chapter_code" value="' . esc($chapter_code) . '">

<label class="fw-bold">Select Member</label>
<select name="user_code" class="form-select" required>
' . $memberOptions . '
</select>

</div>

<div class="modal-footer border-0">
<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-sm text-white" style="background:#1b4d8f;">Add Member</button>
</div>

</form>
</div>';

        return $this->response->setBody($output);
    }

    public function add_chapter_members_action()
    {
        $commonModel = new \App\Models\CommonModel();

        $chapter_code = $this->request->getPost('chapter_code');
        $user_code    = $this->request->getPost('user_code');

        if (!$chapter_code || !$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Missing data'
            ]);
        }

        /* ===============================
       GET USER DETAILS (NEW MEMBER)
    ===============================*/
        $user = $commonModel
            ->exe_query("SELECT * FROM users WHERE user_code='" . esc($user_code) . "'")
            ->getRowArray();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        /* ===============================
       GET CHAPTER DETAILS
    ===============================*/
        $chapter = $commonModel
            ->exe_query("SELECT * FROM chapters WHERE chapter_code='" . esc($chapter_code) . "'")
            ->getRowArray();

        /* ===============================
       GENERATE MEMBER CODE
    ===============================*/
        $member_code = $commonModel->get_serial2(
            'chapter_members',
            'CHPM',
            'member_code'
        );

        /* ===============================
       INSERT DATA
    ===============================*/
        $insert = [
            'member_code'  => $member_code,
            'chapter_code' => $chapter_code,
            'user_code'    => $user['user_code'],
            'name'         => $user['name'],
            'phone'        => $user['phone'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'joined_date'  => date('Y-m-d'),
            'chapter_role' => 'Member'
        ];

        $insertId = $commonModel->insert_data('chapter_members', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Insert failed'
            ]);
        }

        // ==============================
        // ✅ EMAIL NOTIFICATION TO ALL CHAPTER MEMBERS
        // ==============================
        $members = $commonModel->exe_query("
        SELECT email, name 
        FROM chapter_members 
        WHERE chapter_code = '" . esc($chapter_code) . "'
        AND user_code != '" . esc($user_code) . "'
    ")->getResultArray();

        if (!empty($members)) {

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

            foreach ($members as $m) {

                if (empty($m['email'])) continue;

                $emailService->clear(true);

                $emailService->setFrom('mail@eafconnect.com', 'eafconnect');
                $emailService->setTo($m['email']);



                $emailService->setSubject('New Member Joined Your Chapter');

                // ✅ EMAIL VIEW
                $message = view('mail/chapter_new_member', [
                    'member_name'  => $user['name'],
                    'chapter_name' => $chapter['chapter_name'] ?? 'Chapter',
                    'receiver'     => $m['name']
                ]);

                $emailService->setMessage($message);

                $emailService->send(); // no die, continue loop
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Member added & notifications sent'
        ]);
    }
    public function get_filtered_data()
    {
        $request = \Config\Services::request();
        $search  = $request->getPost('search');
        $status  = $request->getPost('status');

        $chapterModel = new \App\Models\ChapterModel();
        $commonModel  = new CommonModel();

        $builder = $chapterModel->builder();

        /* =============================
       SEARCH FILTER
    ============================= */
        if (!empty($search)) {
            $builder->groupStart()
                ->like('chapter_name', $search)
                ->orLike('chapter_code', $search)
                ->orLike('chapter_description', $search) // ✅ FIXED
                ->orLike('location', $search)
                ->groupEnd();
        }

        /* =============================
       STATUS FILTER
    ============================= */
        if (!empty($status)) {
            $builder->where('chapter_status', $status);
        }

        $builder->orderBy("chapters_id", "DESC");

        $chapters = $builder->get()->getResultArray();

        if (empty($chapters)) {
            echo '<div class="alert alert-warning text-center">
                No chapters found for the selected filters.
              </div>';
            return;
        }

        /* =============================
       SEPARATE MEMBER COUNT QUERY
    ============================= */
        $counts = $commonModel->exe_query("
        SELECT chapter_code, COUNT(member_id) AS total
        FROM chapter_members
        WHERE members_status='1'
        GROUP BY chapter_code
    ")->getResultArray();

        $memberCounts = [];
        foreach ($counts as $c) {
            $memberCounts[$c['chapter_code']] = $c['total'];
        }

        /* =============================
       OUTPUT CARDS
    ============================= */
        $output = '';
        $i = 1;

        foreach ($chapters as $row) {

            // attach member count
            $row['member_count'] =
                $memberCounts[$row['chapter_code']] ?? 0;

            $output .= view('include/admin/card/chapter_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        echo $output;
    }


    public function get_filtered_data2()
    {
        $request = \Config\Services::request();
        $search  = $request->getPost('search');
        $status  = $request->getPost('status');

        $db = \Config\Database::connect();

        $builder = $db->table('chapter_members cm');

        $builder->select("
        cm.*,
        cm.role AS member_role,
        cm.joined_date AS join_date,
        cm.members_status AS status,

        u.user_code,
        u.name,
        u.email,
        u.phone,
        u.city,
        u.profile_photo,

        c.chapter_name
    ");

        $builder->join('users u', 'u.user_code = cm.user_code');
        $builder->join('chapters c', 'c.chapter_code = cm.chapter_code');

        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.name', $search)
                ->orLike('u.email', $search)
                ->orLike('u.phone', $search)
                ->orLike('u.city', $search)
                ->groupEnd();
        }

        /* ========= STATUS ========= */
        if (!empty($status)) {
            $builder->where('cm.members_status', $status);
        }

        $users = $builder->orderBy('cm.member_code', 'ASC')
            ->get()
            ->getResultArray();


        if (!$users) {
            echo '<div class="alert alert-warning text-center">No members found.</div>';
            return;
        }

        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/member_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        echo $output;
    }




    public function delete_chapter_member($user_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM chapter_members  WHERE user_code='" . esc($user_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_chapter_member_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="user_code" value="' . esc($row->user_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;">Name: <span style="color:#05008D;">' . esc($row->user_code) . '</span></p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Member Form Chapter?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_chapter_member_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $user_code = $this->request->getPost('user_code');
        if (!$user_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("chapter_members", ['user_code' => $user_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Chapter Member removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }
    public function chapter_report($chapter_code)
    {
        $db = \Config\Database::connect();
        $commonModel = new \App\Models\CommonModel();
        $mitbModel   = new \App\Models\TyfcbModel();

        /* ===============================
       1. CHAPTER INFO (SAFE)
    =============================== */
        $chapter_info = $db->query("
        SELECT 
            c.chapter_name,
            c.location,
            c.chapter_status,
            u.name as leader_name,
            u.profile_photo as leader_photo
        FROM chapters c
        LEFT JOIN users u ON u.user_code = c.leader_code
        WHERE c.chapter_code = ?
    ", [$chapter_code])->getRowArray();

        // fallback (IMPORTANT)
        if (!$chapter_info) {
            $chapter_info = [
                'chapter_name' => 'Chapter',
                'location' => '-',
                'chapter_status' => 1,
                'leader_name' => 'Not Assigned',
                'leader_photo' => 'empty.png'
            ];
        }

        /* ===============================
       2. MEMBERS
    =============================== */
        $members = $db->query("
        SELECT cm.*, u.name,u.status, u.phone
        FROM chapter_members cm
        JOIN users u ON u.user_code = cm.user_code
        WHERE cm.chapter_code = ? AND u.status='1'
    ", [$chapter_code])->getResultArray();

        $report = [];

        foreach ($members as $row) {

            $u = $row['user_code'];

            $f2f = $commonModel
                ->exe_query("SELECT COUNT(*) as total FROM one_to_ones WHERE from_user='$u' OR to_user='$u'")
                ->getRow()->total ?? 0;

            $refGiven = $commonModel
                ->exe_query("SELECT COUNT(*) as total FROM referrals WHERE from_user='$u'")
                ->getRow()->total ?? 0;

            $refReceived = $commonModel
                ->exe_query("SELECT COUNT(*) as total FROM referrals WHERE to_user='$u'")
                ->getRow()->total ?? 0;

            $visitors = $commonModel
                ->exe_query("SELECT COUNT(*) as total FROM visitors WHERE user_code='$u'")
                ->getRow()->total ?? 0;

            $invites = $commonModel
                ->exe_query("SELECT COUNT(*) as total FROM notifications WHERE user_code='$u'")
                ->getRow()->total ?? 0;

            $mitbGiven = $mitbModel
                ->selectSum('amount')
                ->where('to_user', $u)
                ->first()['amount'] ?? 0;

            $mitbReceived = $mitbModel
                ->selectSum('amount')
                ->where('from_user', $u)
                ->first()['amount'] ?? 0;

            $report[] = [
                'name' => $row['name'],
                'phone' => $row['phone'],
                'role' => $row['role'] ?? '-',

                'f2f' => $f2f,
                'ref_given' => $refGiven,
                'ref_received' => $refReceived,
                'connections' => $refGiven + $refReceived,
                'visitors' => $visitors,
                'invites' => $invites,
                'mitb_given' => $mitbGiven,
                'mitb_received' => $mitbReceived,
            ];
        }

        return view('member/chapter_report', [
            'report' => $report,
            'chapter_code' => $chapter_code,
            'chapter_info' => $chapter_info
        ]);
    }
    public function download_pdf($chapter_code)
    {
        $db = \Config\Database::connect();

        $commonModel = new \App\Models\CommonModel();
        $mitbModel   = new \App\Models\TyfcbModel();

        /*
    |--------------------------------------------------------------------------
    | LOAD DOMPDF
    |--------------------------------------------------------------------------
    */
        $options = new \Dompdf\Options();

        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new \Dompdf\Dompdf($options);


        /*
    |--------------------------------------------------------------------------
    | CHAPTER INFO
    |--------------------------------------------------------------------------
    */
        $chapter_info = $db->query("
        SELECT
            c.chapter_name,
            c.location,
            u.name AS leader_name
        FROM chapters c
        LEFT JOIN users u
            ON u.user_code = c.leader_code
        WHERE c.chapter_code = ?
        LIMIT 1
    ", [$chapter_code])->getRowArray();


        /*
    |--------------------------------------------------------------------------
    | CHECK CHAPTER
    |--------------------------------------------------------------------------
    */
        if (empty($chapter_info)) {

            return redirect()->back()->with(
                'msg',
                'Chapter not found'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | ACTIVE CHAPTER MEMBERS
    |--------------------------------------------------------------------------
    */
        $members = $db->query("
        SELECT
            cm.user_code,
            cm.role,
            u.name,
            u.status,
            u.phone
        FROM chapter_members cm
        INNER JOIN users u
            ON u.user_code = cm.user_code
        WHERE cm.chapter_code = ?
        AND u.status = '1'
        ORDER BY u.name ASC
    ", [$chapter_code])->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | REPORT
    |--------------------------------------------------------------------------
    */
        $report = [];


        foreach ($members as $row) {

            $u = trim((string) ($row['user_code'] ?? ''));

            if ($u === '') {
                continue;
            }


            /*
        |--------------------------------------------------------------------------
        | FACE TO FACE
        |--------------------------------------------------------------------------
        */
            $f2fResult = $db->query("
            SELECT COUNT(*) AS total
            FROM one_to_ones
            WHERE from_user = ?
               OR to_user = ?
        ", [$u, $u])->getRow();

            $f2f = (int) ($f2fResult->total ?? 0);


            /*
        |--------------------------------------------------------------------------
        | REFERRALS GIVEN
        |--------------------------------------------------------------------------
        */
            $refGivenResult = $db->query("
            SELECT COUNT(*) AS total
            FROM referrals
            WHERE from_user = ?
        ", [$u])->getRow();

            $refGiven = (int) ($refGivenResult->total ?? 0);


            /*
        |--------------------------------------------------------------------------
        | REFERRALS RECEIVED
        |--------------------------------------------------------------------------
        */
            $refReceivedResult = $db->query("
            SELECT COUNT(*) AS total
            FROM referrals
            WHERE to_user = ?
        ", [$u])->getRow();

            $refReceived = (int) ($refReceivedResult->total ?? 0);


            /*
        |--------------------------------------------------------------------------
        | VISITORS
        |--------------------------------------------------------------------------
        */
            $visitorsResult = $db->query("
            SELECT COUNT(*) AS total
            FROM visitors
            WHERE user_code = ?
        ", [$u])->getRow();

            $visitors = (int) ($visitorsResult->total ?? 0);


            /*
        |--------------------------------------------------------------------------
        | INVITES
        |--------------------------------------------------------------------------
        */
            $invitesResult = $db->query("
            SELECT COUNT(*) AS total
            FROM notifications
            WHERE user_code = ?
        ", [$u])->getRow();

            $invites = (int) ($invitesResult->total ?? 0);


            /*
        |--------------------------------------------------------------------------
        | MITB GIVEN
        |--------------------------------------------------------------------------
        |
        | Based on your existing logic:
        | to_user = member means MITB Given
        |
        */
            $mitbGiven = $mitbModel
                ->selectSum('amount')
                ->where('to_user', $u)
                ->first();

            $mitbGivenAmount = (float) (
                $mitbGiven['amount'] ?? 0
            );


            /*
        |--------------------------------------------------------------------------
        | MITB RECEIVED
        |--------------------------------------------------------------------------
        |
        | from_user = member means MITB Received
        |
        */
            $mitbReceived = $mitbModel
                ->selectSum('amount')
                ->where('from_user', $u)
                ->first();

            $mitbReceivedAmount = (float) (
                $mitbReceived['amount'] ?? 0
            );


            /*
        |--------------------------------------------------------------------------
        | REPORT ARRAY
        |--------------------------------------------------------------------------
        */
            $report[] = [

                'name'          => $row['name'] ?? '-',

                'phone'         => $row['phone'] ?? '-',

                'role'          => $row['role'] ?? '-',

                'f2f'           => $f2f,

                'ref_given'     => $refGiven,

                'ref_received'  => $refReceived,

                'connections'   => $refGiven + $refReceived,

                'visitors'      => $visitors,

                'invites'       => $invites,

                'mitb_given'    => $mitbGivenAmount,

                'mitb_received' => $mitbReceivedAmount,
            ];
        }


        /*
    |--------------------------------------------------------------------------
    | LOAD PDF VIEW
    |--------------------------------------------------------------------------
    */
        $html = view(
            'member/chapter_report_pdf',
            [
                'report'       => $report,
                'chapter_info' => $chapter_info
            ]
        );


        /*
    |--------------------------------------------------------------------------
    | GENERATE PDF
    |--------------------------------------------------------------------------
    */
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();


        /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
        return $dompdf->stream(
            'chapter_report_' . $chapter_code . '.pdf',
            [
                'Attachment' => true
            ]
        );
    }
}

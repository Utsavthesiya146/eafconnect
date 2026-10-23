<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\UserModel;

class Members extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }
        $model = new \App\Models\UserModel();
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
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        // Status Filter
        if (!empty($status)) {
            $builder->where('status', $status);
        }

        /* ========= ORDER ========= */
        $builder->orderBy("FIELD(status,1,2,3)", "", false);
        $builder->orderBy("user_id", "DESC");

        /* ========= PAGINATION ========= */
        $data['users'] = $builder->paginate($perPage, 'default', $page);
        $data['pager'] = $builder->pager;

        /* ========= MEMBER COUNTS (FIXED) ========= */
        $data['count_all']     = $model->builder()->countAllResults(false);           // Total members
        $data['count_active']  = (clone $model)->where('status', 1)->countAllResults(); // Active
        $data['count_hold']    = (clone $model)->where('status', 2)->countAllResults(); // Hold ✅
        $data['count_blocked'] = (clone $model)->where('status', 3)->countAllResults(); // Blocked

        /* ========= PASS FILTER VALUES ========= */
        $data['perPage'] = $perPage;
        $data['search']  = $search;
        $data['status']  = $status;
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
        return view('admin/members', $data);
    }
    public function AddNewMemeberData()
    {
        helper(['form']);

        $commonModel = new CommonModel();

        /* ================= VALIDATION ================= */
        $rules = [
            'name'     => 'required',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'gender'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $this->validator->listErrors()
            ]);
        }

        $data = $this->request->getPost();

        /* ================= USER CODE ================= */
        $user_code = $commonModel->get_serial('users', 'USR');

        /* ================= IMAGE UPLOAD ================= */
        /* ================= IMAGE UPLOAD ================= */
        $gender = strtolower(trim($data['gender'] ?? ''));

        // Default image based on gender
        if ($gender == 'male') {
            $photo = 'male.png';
        } elseif ($gender == 'female') {
            $photo = 'female.png';
        } else {
            $photo = 'empty.png';
        }

        $file = $this->request->getFile('profile_photo');

        if ($file && $file->isValid() && !$file->hasMoved() && $file->getName() != '') {

            $path = FCPATH . 'uploads/profile/';

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

            if (in_array($file->getMimeType(), $allowedTypes)) {
                $newName = $file->getRandomName();
                $file->move($path, $newName);
                $photo = $newName; // uploaded image overrides default gender image
            }
        }
        $referred_by = $this->request->getPost('referred_by');

        /* ================= MEMBER TYPE ================= */
        $member_type = ($data['member_type'] == 'Gold')
            ? 'gold_member'
            : 'member';

        $member = $commonModel->exe_query("
        SELECT
            u.user_code,
            u.name,
            c.chapter_name
        FROM users u
        INNER JOIN chapter_members cm
            ON cm.user_code = u.user_code
        INNER JOIN chapters c
            ON c.chapter_code = cm.chapter_code
        WHERE u.status='1' AND u.user_code='$referred_by'
        ORDER BY u.name ASC
    ")->getResultArray();
        $refferd_by_name = '';

        foreach ($member as $row) {
            $refferd_by_name = $row['name'] . ' - ' . $row['chapter_name'];
        }
        /* ================= INSERT DATA ================= */
        $insert = [
            'user_code'     => $user_code,
            'name'          => $data['name'] ?? '',
            'last_name'          => $data['last_name'] ?? '',
            'email'         => $data['email'] ?? '',
            'password'      => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'          => $data['role'] ?? '',
            'status'        => $data['status'] ?? '',
            'member_role'   => $data['member_role'] ?? '',
            'gender'        => $data['gender'] ?? '',
            'join_date'     => !empty($data['join_date']) ? $data['join_date'] : null,
            'referred_by'   => $data['referred_by'] ?? '',
            'member_type'   => $member_type,
            'profile_photo' => $photo,
            'created_at'    => date('Y-m-d H:i:s'),

            'business_name'   => $data['business_name'] ?? '',
            'industry'   => $data['industry'] ?? '',
            'address'   => $data['address'] ?? '',
            'start_year'   => $data['start_year'] ?? '',
            'gst_number'   => $data['gst_number'] ?? '',

            'refferd_by'   => $data['refferd_by'] ?? '',
            'refferd_by_name' => $refferd_by_name,
        ];

        $insertId = $commonModel->insert_data('users', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add member'
            ]);
        }

        // ==============================
        // ✅ EMAIL NOTIFICATION
        // ==============================
        if (!empty($data['email'])) {

            $emailService = \Config\Services::email();

            $config = [
                'protocol'   => 'smtp',
                'SMTPHost'   => 'smtp.hostinger.com',
                'SMTPUser'   => 'mail2@eafconnect.com',
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

            $emailService->setFrom('mail2@eafconnect.com', 'eafconnect');
            $emailService->setTo($data['email']);

            $emailService->setSubject('Welcome to eafconnect');

            // ✅ EMAIL TEMPLATE VIEW
            $message = view('mail/member_welcome', [
                'name'        => $data['name'],
                'email'       => $data['email'],
                'password'    => $data['password'], // optional (you can remove for security)
                'member_type' => ucfirst($member_type),
            ]);

            $emailService->setMessage($message);

            if (!$emailService->send()) {
                echo "<pre>";
                print_r($emailService->printDebugger(['headers', 'subject', 'body']));
                die;
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Member added successfully & Email Sent'
        ]);
    }


    public function delete_user($user_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM users  WHERE user_code='" . esc($user_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_application_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="user_code" value="' . esc($row->user_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;">Name: <span style="color:#05008D;">' . esc($row->name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">Email: ' . esc($row->email) . '</p>
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

    public function delete_application_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $user_code = $this->request->getPost('user_code');
        if (!$user_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("users", ['user_code' => $user_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Member removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }

    public function edit_status($user_code)
    {
        $commonModel = new CommonModel();

        /*
    |--------------------------------------------------------------------------
    | Validate User Code
    |--------------------------------------------------------------------------
    */
        $user_code = trim($user_code);

        if ($user_code === '') {
            return $this->response->setBody(
                '<p class="text-center text-danger">User code missing!</p>'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Get User
    |--------------------------------------------------------------------------
    */
        $userQuery = $commonModel->exe_query(
            "SELECT * 
         FROM users 
         WHERE user_code = " . $commonModel->db->escape($user_code) . "
         LIMIT 1"
        );

        $user = $userQuery->getRow();

        if (!$user) {
            return $this->response->setBody(
                '<p class="text-center text-danger">User not found!</p>'
            );
        }

        $userRow = $user;

        /*
    |--------------------------------------------------------------------------
    | Get Active Members For Referred By
    |--------------------------------------------------------------------------
    | Exclude current user to prevent self-referral.
    |--------------------------------------------------------------------------
    */
        $membersQuery = $commonModel->exe_query(
            "SELECT
            u.user_code,
            u.name,
            c.chapter_name
        FROM users u
        INNER JOIN chapter_members cm
            ON cm.user_code = u.user_code
        INNER JOIN chapters c
            ON c.chapter_code = cm.chapter_code
        WHERE u.status = '1'
          AND u.user_code != " . $commonModel->db->escape($user_code) . "
        ORDER BY u.name ASC"
        );

        $members = $membersQuery->getResultArray();

        /*
    |--------------------------------------------------------------------------
    | Current Referred By
    |--------------------------------------------------------------------------
    */
        $currentReferredBy = $userRow->refferd_by ?? '';

        /*
    |--------------------------------------------------------------------------
    | Modal HTML
    |--------------------------------------------------------------------------
    */
        $output = '
    <div class="modal-content"
        style="
            border-radius:12px;
            border:none;
            box-shadow:0 4px 12px rgba(0,0,0,.10);
            font-size:13px;
        ">

        <form id="edit_status_action" method="post">

            <div class="modal-header"
                style="
                    background:#1b4d8f;
                    color:#fff;
                    border-radius:12px 12px 0 0;
                ">

                <h6 class="modal-title text-white mb-0">
                    <i class="fa fa-user-pen me-2"></i>
                    Change Member Status
                </h6>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input
                    type="hidden"
                    name="user_code"
                    value="' . esc($userRow->user_code) . '">

                <div class="row g-3">

                    <!-- MEMBER STATUS -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold mb-1">
                            Member Status
                        </label>

                        <select
                            class="form-select"
                            name="status"
                            required>

                            <option value="1" '
            . ($userRow->status == '1' ? 'selected' : '') .
            '>
                                Active
                            </option>

                            <option value="2" '
            . ($userRow->status == '2' ? 'selected' : '') .
            '>
                                Pending
                            </option>

                            <option value="3" '
            . ($userRow->status == '3' ? 'selected' : '') .
            '>
                                Block
                            </option>

                        </select>

                    </div>

                    <!-- ACCOUNT TYPE -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold mb-1">
                            Account Type
                        </label>

                        <select
                            name="role"
                            class="form-select"
                            required>

                            <option
                                value="member"
                                ' . ($userRow->role == 'member' ? 'selected' : '') . '>
                                Member
                            </option>

                            <option
                                value="admin"
                                ' . ($userRow->role == 'admin' ? 'selected' : '') . '>
                                Admin
                            </option>

                        </select>

                    </div>

                    <!-- MEMBER ROLE -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold mb-1">
                            Member Role
                        </label>

                        <select
                            name="member_role"
                            class="form-select"
                            required>

                            <option
                                value="Member"
                                ' . ($userRow->member_role == 'Member' ? 'selected' : '') . '>
                                Member
                            </option>

                            <option
                                value="President"
                                ' . ($userRow->member_role == 'President' ? 'selected' : '') . '>
                                President
                            </option>

                            <option
                                value="Vice President"
                                ' . ($userRow->member_role == 'Vice President' ? 'selected' : '') . '>
                                Vice President
                            </option>

                            <option
                                value="Secretary Treasurer"
                                ' . ($userRow->member_role == 'Secretary Treasurer' ? 'selected' : '') . '>
                                Secretary Treasurer
                            </option>

                            <option
                                value="Chapter Growth"
                                ' . ($userRow->member_role == 'Chapter Growth' ? 'selected' : '') . '>
                                Chapter Growth
                            </option>

                        </select>

                    </div>

                    <!-- REFERRED BY -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold mb-1">
                            Referred By
                        </label>

                        <select
                            name="referred_by"
                            class="form-select">

                            <option value="">
                                -- Select Member --
                            </option>';

        /*
    |--------------------------------------------------------------------------
    | Members Dropdown
    |--------------------------------------------------------------------------
    */
        foreach ($members as $memberRow) {

            $memberCode = $memberRow['user_code'];

            $selected = (
                (string) $currentReferredBy === (string) $memberCode
            )
                ? 'selected'
                : '';

            $output .= '
            <option
                value="' . esc($memberCode) . '"
                ' . $selected . '>
                ' . esc($memberRow['name']) . '
                - ' . esc($memberRow['chapter_name']) . '
            </option>';
        }

        $output .= '
                        </select>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary btn-sm"
                    data-bs-dismiss="modal">

                    <i class="fa fa-xmark me-1"></i>
                    Cancel

                </button>

                <button
                    type="submit"
                    class="btn btn-primary btn-sm">

                    <i class="fa fa-check me-1"></i>
                    Update

                </button>

            </div>

        </form>

    </div>';

        return $this->response->setBody($output);
    }


    public function edit_status_action()
    {
        $commonModel = new CommonModel();
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | Get POST Data
    |--------------------------------------------------------------------------
    */
        $user_code   = trim((string) $this->request->getPost('user_code'));
        $status      = trim((string) $this->request->getPost('status'));
        $role        = trim((string) $this->request->getPost('role'));
        $member_role = trim((string) $this->request->getPost('member_role'));
        $referred_by = trim((string) $this->request->getPost('referred_by'));

        /*
    |--------------------------------------------------------------------------
    | Validate User Code
    |--------------------------------------------------------------------------
    */
        if ($user_code === '') {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'User code missing'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Validate Status
    |--------------------------------------------------------------------------
    */
        if (!in_array($status, ['1', '2', '3'], true)) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid member status'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Validate Role
    |--------------------------------------------------------------------------
    */
        if (!in_array($role, ['member', 'admin'], true)) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid account type'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Validate Member Role
    |--------------------------------------------------------------------------
    */
        $allowedMemberRoles = [
            'Member',
            'President',
            'Vice President',
            'Secretary Treasurer',
            'Chapter Growth'
        ];

        if (!in_array($member_role, $allowedMemberRoles, true)) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid member role'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Prevent Self Referral
    |--------------------------------------------------------------------------
    */
        if ($referred_by !== '' && $referred_by === $user_code) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'A member cannot refer themselves'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Get Referred Member Name
    |--------------------------------------------------------------------------
    */
        $refferd_by_name = '';

        if ($referred_by !== '') {

            $referredByQuery = $commonModel->exe_query(
                "SELECT
                u.user_code,
                u.name,
                c.chapter_name
            FROM users u
            INNER JOIN chapter_members cm
                ON cm.user_code = u.user_code
            INNER JOIN chapters c
                ON c.chapter_code = cm.chapter_code
            WHERE u.status = '1'
              AND u.user_code = " . $commonModel->db->escape($referred_by) . "
            LIMIT 1"
            );

            $referredMember = $referredByQuery->getRowArray();

            if ($referredMember) {

                $refferd_by_name =
                    $referredMember['name']
                    . ' - '
                    . $referredMember['chapter_name'];
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Start Transaction
    |--------------------------------------------------------------------------
    */
        $db->transStart();

        /*
    |--------------------------------------------------------------------------
    | Update Users Table
    |--------------------------------------------------------------------------
    */
        $userData = [
            'status'          => $status,
            'role'            => $role,
            'member_role'     => $member_role,
            'refferd_by'      => $referred_by,
            'refferd_by_name' => $refferd_by_name,
        ];

        $userUpdate = $commonModel->update_row(
            'users',
            $userData,
            [
                'user_code' => $user_code
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | Update Chapter Members Status
    |--------------------------------------------------------------------------
    | Same status will be applied to ALL chapter_members
    | records having this user_code.
    |--------------------------------------------------------------------------
    */
        $chapterMemberUpdate = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->update([
                'members_status' => $status
            ]);

        /*
    |--------------------------------------------------------------------------
    | Complete Transaction
    |--------------------------------------------------------------------------
    */
        $db->transComplete();

        /*
    |--------------------------------------------------------------------------
    | Check Transaction
    |--------------------------------------------------------------------------
    */
        if (
            $db->transStatus() === false ||
            !$userUpdate
        ) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Update failed. No changes were made.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Success Response
    |--------------------------------------------------------------------------
    */
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Member status updated successfully!'
        ]);
    }




    public function edit_m($user_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM users WHERE user_code='" . esc($user_code) . "'";
        $member = $commonModel->exe_query($qry)->getResult();
        if (empty($member)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Member not found");
        }
        $data = [
            'member' => $member,
        ];
        return view('admin/edit_user_profile', $data);
    }


    public function get_filtered_data()
    {
        try {

            $request = \Config\Services::request();

            $search = trim((string) $request->getPost('search'));
            $status = $request->getPost('status');

            $userModel = new UserModel();
            $usersQuery = $userModel->builder();

            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
            if ($search !== '') {

                $usersQuery->groupStart()
                    ->like('name', $search)
                    ->orLike('email', $search)
                    ->orLike('phone', $search)
                    ->orLike('city', $search)
                    ->orLike('state', $search)
                    ->orLike('country', $search)
                    ->groupEnd();
            }

            /*
        |--------------------------------------------------------------------------
        | STATUS
        | Don't use empty() because "0" is a valid value.
        |--------------------------------------------------------------------------
        */
            if ($status !== null && $status !== '') {
                $usersQuery->where('status', $status);
            }

            /*
        |--------------------------------------------------------------------------
        | GET USERS
        |--------------------------------------------------------------------------
        */
            $users = $usersQuery->get()->getResult();

            /*
        |--------------------------------------------------------------------------
        | NO RESULTS
        |--------------------------------------------------------------------------
        */
            if (empty($users)) {

                return $this->response
                    ->setStatusCode(200)
                    ->setBody('
                    <div class="alert alert-warning text-center mb-0">
                        <i class="fa fa-info-circle me-1"></i>
                        No users found for the selected filters.
                    </div>
                ');
            }

            /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
            $output = '';
            $i = 1;

            foreach ($users as $row) {

                $output .= view('include/admin/card/member_card2', [
                    'row' => $row,
                    'i'   => $i++
                ]);
            }

            return $this->response
                ->setStatusCode(200)
                ->setBody($output);
        } catch (\Throwable $e) {

            log_message(
                'error',
                'get_filtered_data Error: ' . $e->getMessage()
            );

            return $this->response
                ->setStatusCode(500)
                ->setBody(
                    '<div class="alert alert-danger">
                    <strong>Filter Error:</strong> '
                        . esc($e->getMessage()) .
                        '</div>'
                );
        }
    }
}

<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\ReferralModel;

class Connect extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            $isLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;
            if ($isLocal) {
                $db = \Config\Database::connect();
                $userRow = $db->table('users u')
                    ->select('u.*, cm.chapter_code, cm.chapter_role')
                    ->join('chapter_members cm', 'u.user_code = cm.user_code', 'left')
                    ->where('u.status', '1')
                    ->where('u.user_code', '5')
                    ->get()
                    ->getRow();
                if ($userRow) {
                    session()->set([
                        'user_id'       => $userRow->user_id,
                        'user_code'     => $userRow->user_code,
                        'name'          => $userRow->name,
                        'email'         => $userRow->email,
                        'phone'         => $userRow->phone,
                        'profile_photo' => $userRow->profile_photo,
                        'role'          => $userRow->role ?? 'member',
                        'member_role'   => $userRow->member_role ?? 'President',
                        'member_type'   => $userRow->member_type ?? 'Gold',
                        'status'        => $userRow->status,
                        'chapter_code'  => $userRow->chapter_code ?? 'CH00001',
                        'chapter_role'  => $userRow->chapter_role ?? 'President',
                        'logged_in'     => true,
                        'is_member'     => true
                    ]);
                }
            } else {
                return redirect()->to('/member_login');
            }
        }
        $commonModel = new CommonModel();
        $user_code = session()->get('user_code');
        $qry = "
        SELECT u.*, c.chapter_name, cm.chapter_role
        FROM users u
        LEFT JOIN chapter_members cm ON u.user_code = cm.user_code
        LEFT JOIN chapters c ON cm.chapter_code = c.chapter_code
        WHERE u.status='1'
        AND u.user_code = '" . $user_code . "'
    ";
        $user = $commonModel->exe_query($qry)->getResult();
        $qry = "
        SELECT 
            r.*,

            fu.name  AS from_name,
            fu.email AS from_email,
            fu.profile_photo AS from_profile,

            tu.name  AS to_name,
            tu.email AS to_email,
            tu.profile_photo AS to_profile,

            c.chapter_name

        FROM referrals r
        LEFT JOIN users fu ON fu.user_code = r.from_user
        LEFT JOIN users tu ON tu.user_code = r.to_user
        LEFT JOIN chapter_members cm ON cm.user_code = fu.user_code
        LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code

        WHERE tu.status='1'
        AND tu.user_code = '" . $user_code . "'

        ORDER BY r.created_at DESC
    ";
        $received = $commonModel->exe_query($qry)->getResultArray();
        $qry = "
        SELECT 
            r.*,

            fu.name  AS from_name,
            fu.email AS from_email,
            fu.profile_photo AS from_profile,

            tu.name  AS to_name,
            tu.email AS to_email,
            tu.profile_photo AS to_profile,

            c.chapter_name

        FROM referrals r
        LEFT JOIN users fu ON fu.user_code = r.from_user
        LEFT JOIN users tu ON tu.user_code = r.to_user
        LEFT JOIN chapter_members cm ON cm.user_code = tu.user_code
        LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code

        WHERE fu.status='1'
        AND fu.user_code = '" . $user_code . "'

        ORDER BY r.created_at DESC
    ";

        $given = $commonModel->exe_query($qry)->getResultArray();
        $receivedCount = count($received);
        $givenCount    = count($given);
        $totalCount    = $receivedCount + $givenCount;

        // Status counts
        $statusCounts = [
            'all'               => $receivedCount,
            'new'               => 0,
            'pending'           => 0,
            'invalid'           => 0,
            'mitb'              => 0,
            'contacted'         => 0,
            'closed business'   => 0,
            'not converted'     => 0,
            'completed'         => 0,
            'cancelled'         => 0,
            'MITB'     => 0,
            'open for discussion'     => 0,
        ];


        foreach ($received as $row) {
            $status = strtolower(trim($row['status'] ?? ''));
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }
        $member = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();
        $data = [
            'user'          => $user,
            'member'        => $member,
            'received'      => $received,
            'given'         => $given,
            'totalCount'    => $totalCount,
            'receivedCount' => $receivedCount,
            'givenCount'    => $givenCount,
            'statusCounts'  => $statusCounts
        ];

        return view('member/connects', $data);
    }

    public function details($referral_code = null)
    {
        $model       = new \App\Models\ReferralModel();
        $commonModel = new \App\Models\CommonModel();

        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = $this->request->getGet('search') ?? '';
        $status  = $this->request->getGet('status') ?? '';
        $model
            ->select("
            referrals.*,

            from_user.name  AS from_name,
            from_user.email AS from_email,
            from_user.phone AS from_phone,
            from_user.profile_photo AS from_profile,

            to_user.name  AS to_name,
            to_user.email AS to_email,
            to_user.phone AS to_phone,
            to_user.profile_photo AS to_profile
        ")
            // ✅ FIXED (use user_code)
            ->join('users AS from_user', 'from_user.user_code = referrals.from_user', 'left')
            ->join('users AS to_user', 'to_user.user_code = referrals.to_user', 'left');
        if (!empty($search)) {
            $model->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('referrals.business', $search)
                ->orLike('referrals.contact_number', $search)
                ->groupEnd();
        }
        if (!empty($status)) {
            $model->where('referrals.status', $status);
        }
        if (!empty($referral_code)) {
            $model->where('referrals.referral_code', $referral_code);
        }
        $model->orderBy('referrals.referral_id', 'DESC');
        $data['connect'] = $model->paginate($perPage);
        $data['pager']   = $model->pager;
        $data['perPage'] = $perPage;
        $member = $commonModel
            ->exe_query("SELECT user_code, name FROM users WHERE status='1'")
            ->getResultArray();
        $data['member'] = $member;
        return view('member/connects_details', $data);
    }
   public function getMembers()
{
    $db = \Config\Database::connect();

    $type = $this->request->getPost('type');
    $user_code = trim((string) session()->get('user_code'));

    // =========================================================
    // SAME CHAPTER MEMBERS
    // =========================================================
    if ($type == 'chapter') {

        $qry = "
            SELECT 
                u.user_code,
                u.name,
                u.phone,

                CASE 
                    WHEN u.user_code = ? THEN 1
                    ELSE 0
                END AS is_self

            FROM users u

            INNER JOIN chapter_members cm
                ON u.user_code = cm.user_code

            WHERE cm.chapter_code = (
                SELECT chapter_code
                FROM chapter_members
                WHERE user_code = ?
                  AND members_status = '1'
                LIMIT 1
            )

            AND cm.members_status = '1'
            AND u.status = '1'
            AND u.role = 'member'

            ORDER BY 
                CASE 
                    WHEN u.user_code = ? THEN 0
                    ELSE 1
                END ASC,
                u.name ASC
        ";

        $members = $db->query(
            $qry,
            [
                $user_code,
                $user_code,
                $user_code
            ]
        )->getResultArray();
    }


    // =========================================================
    // ALL MEMBERS
    // =========================================================
    else {

        $qry = "
            SELECT 
                user_code,
                name,
                phone,

                CASE 
                    WHEN user_code = ? THEN 1
                    ELSE 0
                END AS is_self

            FROM users

            WHERE status = '1'
              AND role = 'member'

            ORDER BY 
                CASE 
                    WHEN user_code = ? THEN 0
                    ELSE 1
                END ASC,
                name ASC
        ";

        $members = $db->query(
            $qry,
            [
                $user_code,
                $user_code
            ]
        )->getResultArray();
    }


    // =========================================================
    // RETURN JSON
    // =========================================================
    return $this->response->setJSON($members);
}
    public function AddNewReferralData()
    {
        helper(['form']);

        $session     = \Config\Services::session();
        $commonModel = new \App\Models\CommonModel();

        // ==============================
        // ✅ CHECK LOGIN SESSION
        // ==============================
        $user_code = $session->get('user_code');

        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired. Please login again.'
            ]);
        }

        // ==============================
        // ✅ GET POST DATA
        // ==============================
        $data = $this->request->getPost();

        // ==============================
        // ✅ GENERATE REFERRAL CODE
        // ==============================
        $referral_code = $commonModel->generateSerial(
            'referrals',
            'referral_code',
            'REFF'
        );

        // ==============================
        // ✅ PREPARE INSERT DATA
        // ==============================
        $insert = [
            'referral_code'  => $referral_code,
            'from_user'      => (string)$user_code,
            'to_user'        => $data['to_user'] ?? null,
            'business'       => $data['business'] ?? '',
            'contact_number' => $data['contact_number'] ?? '',
            'temperature'    => $data['temperature'] ?? '',
            'status'         => $data['status'] ?? 'pending',
            'description'    => $data['description'] ?? '',
            'created_at'     => date('Y-m-d H:i:s')
        ];

        // ==============================
        // ✅ INSERT DATA
        // ==============================
        $insertId = $commonModel->insert_data('referrals', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add referral'
            ]);
        }


        // ==============================
        // ✅ ADD NOTIFICATION
        // ==============================
        if (!empty($insert['to_user'])) {

            // Generate Notification Code
            $n_code = $commonModel->generateSerial(
                'notifications',
                'n_code',
                'NOTI'
            );

            // Get Sender Details
            $fromUser = $commonModel->db->table('users')
                ->select('name')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();

            // Notification Insert Data
            $notification = [
                'n_code'       => $n_code,
                'user_code'    => $data['to_user'],
                'title'        => 'New Referral Received',
                'message'      => ($fromUser->name ?? 'Someone') . ' sent you a new referral.',
                'status'       => 'unread',
            ];

            // Insert Notification
            $commonModel->insert_data('notifications', $notification);
        }
        // ==============================
        // ✅ SEND EMAIL NOTIFICATION
        // ==============================
        if (!empty($data['to_user'])) {

            $toUser = $commonModel->db->table('users')
                ->select('email, name')
                ->where('user_code', $data['to_user'])
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
                    'newline'    => "\r\n"
                ];

                // ✅ Initialize first
                $emailService->initialize($config);

                // ✅ Then clear
                $emailService->clear(true);

                $emailService->setFrom('mail@eafconnect.com', 'Web Hosting Baba');
                $emailService->setTo($toUser->email);
                $emailService->setSubject('New Referral Received');

                // ✅ IMPORTANT: correct view path
                $message = view('mail/referral', [
                    'name'        => $toUser->name ?? 'User',
                    'business'    => $insert['business'],
                    'contact'     => $insert['contact_number'],
                    'description' => $insert['description'],
                    'temperature' => $insert['temperature']
                ]);

                $emailService->setMessage($message);

                // ✅ Send mail
                if (!$emailService->send()) {
                    log_message('error', $emailService->printDebugger(['headers']));
                }
            }
        }

        // ==============================
        // ✅ SUCCESS RESPONSE
        // ==============================
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Referral Added Successfully'
        ]);
    }



    public function edit_refferral_status($referral_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM referrals  WHERE  referral_code='" . esc($referral_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
            <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
                      <form id="edit_refferral_status_action" method="post">
                    <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
                        <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Refferral Status</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <input type="hidden" name="referral_code" value="' . $row->referral_code . '">
                          <div class="row">
                
                        <div class="col-md-12">
                            <label class="fw-bold">Referral status</label>
                            <select name="status" class="form-control">
                                <option value="' . $row->status . '">' . $row->status . '</option>
                                   <option value="">Select Status</option>
                                       <option value="Open For Discussion">Open For Discussion</option>
                                        <option value="Contacted">Contacted</option>
                                        <option value="Need Sometime">Need Sometime</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="MITB">MITB</option>
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


    public function edit_refferral_status_action()
    {
        $referral_code = $this->request->getPost('referral_code');
        if (!$referral_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'referrals',
            $data,
            ['referral_code' => $referral_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Refferral updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }


    public function edit_refferral($referral_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM referrals  WHERE  referral_code='" . esc($referral_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects

        // Get active members
        $member = $commonModel->exe_query(
            "SELECT user_code, name FROM users WHERE status='1'"
        )->getResultArray();

        $row = $user[0]; // first row


        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="edit_refferral_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Refferral Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="referral_code" value="' . esc($row->referral_code) . '">
            <div class="row g-2">
           
                <div class="col-md-12">
                    <label class="fw-bold">Business / Opportunity Name</label>
                    <input type="text" name="business" class="form-control" value="' . esc($row->business) . '">
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Contact</label>
                    <input type="text" name="contact_number" class="form-control" value="' . esc($row->contact_number) . '">
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Temperature</label>
                    <select class="form-select" name="temperature">
                        <option value="Hot" ' . ($row->temperature == 'Hot' ? 'selected' : '') . '>Hot</option>
                        <option value="Warm" ' . ($row->temperature == 'Warm' ? 'selected' : '') . '>Warm</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Referral Status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">' . esc(ucfirst($row->status)) . '</option>
                        <option value="">Select Status</option>
                        <option value="pending">Pending</option>
                        <option value="contacted">Contacted</option>
                        <option value="closed business">Closed Business</option>
                        <option value="not converted">Not Converted</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="business_done">MITB</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Description / Details</label>
                    <textarea name="description" class="form-control">' . esc($row->description) . '</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 m-1 p-1" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white px-3 m-1 p-1" style="background-color:#1b4d8f;">Update</button>
        </div>
    </form>
</div>
';
        return $this->response->setBody($output);
    }
    public function edit_refferral_action()
    {
        $referral_code = $this->request->getPost('referral_code');
        if (!$referral_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Referral code missing'
            ]);
        }
        $data = [
            'business' => $this->request->getPost('business'),
            'contact_number' => $this->request->getPost('contact_number'),
            'temperature' => $this->request->getPost('temperature'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
        ];

        $model = new CommonModel();
        $update = $model->update_row(
            'referrals',
            $data,
            ['referral_code' => $referral_code]
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Referral updated successfully!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
}

<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\ReferralModel;

class Connect extends BaseController
{
    public static function normalizeStatus(?string $status): string
    {
        $clean = strtolower(trim(str_replace(['_', '-'], ' ', (string)$status)));
        return match ($clean) {
            'open for discussion' => 'Open For Discussion',
            'contacted'           => 'Contacted',
            'pending', 'new'      => 'Pending',
            'need sometime', 'not converted' => 'Need Sometime',
            'mitb'                => 'MITB',
            'completed', 'closed business', 'business done', 'bussiness done' => 'Completed',
            'cancelled', 'canceled', 'invalid' => 'Cancelled',
            default               => !empty($clean) ? ucwords($clean) : 'Pending',
        };
    }

    public static function getStatusSlug(?string $status): string
    {
        return strtolower(self::normalizeStatus($status));
    }

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
        SELECT DISTINCT
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

        // Canonical 7 status counts for Received
        $statusCountsReceived = [
            'open for discussion' => 0,
            'contacted'           => 0,
            'pending'             => 0,
            'need sometime'       => 0,
            'mitb'                => 0,
            'completed'           => 0,
            'cancelled'           => 0,
        ];
        foreach ($received as &$rRow) {
            $norm = self::normalizeStatus($rRow['status'] ?? '');
            $rRow['status'] = $norm;
            $slug = strtolower($norm);
            $rRow['status_slug'] = $slug;
            if (isset($statusCountsReceived[$slug])) {
                $statusCountsReceived[$slug]++;
            }
        }
        unset($rRow);

        // Canonical 7 status counts for Given
        $statusCountsGiven = [
            'open for discussion' => 0,
            'contacted'           => 0,
            'pending'             => 0,
            'need sometime'       => 0,
            'mitb'                => 0,
            'completed'           => 0,
            'cancelled'           => 0,
        ];
        foreach ($given as &$gRow) {
            $norm = self::normalizeStatus($gRow['status'] ?? '');
            $gRow['status'] = $norm;
            $slug = strtolower($norm);
            $gRow['status_slug'] = $slug;
            if (isset($statusCountsGiven[$slug])) {
                $statusCountsGiven[$slug]++;
            }
        }
        unset($gRow);

        $member = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();
        $data = [
            'user'                 => $user,
            'member'               => $member,
            'received'             => $received,
            'given'                => $given,
            'totalCount'           => $totalCount,
            'receivedCount'        => $receivedCount,
            'givenCount'           => $givenCount,
            'statusCounts'         => $statusCountsReceived, // backward compatibility
            'statusCountsReceived' => $statusCountsReceived,
            'statusCountsGiven'    => $statusCountsGiven,
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
            SELECT DISTINCT
                u.user_code,
                u.name,
                u.phone,
                u.email,
                u.profile_photo,
                u.business_name,
                u.industry,
                cm.chapter_code,
                c.chapter_name,

                CASE 
                    WHEN u.user_code = ? THEN 1
                    ELSE 0
                END AS is_self

            FROM users u

            INNER JOIN chapter_members cm
                ON u.user_code = cm.user_code

            LEFT JOIN chapters c
                ON c.chapter_code = cm.chapter_code

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
                u.user_code,
                u.name,
                u.phone,
                u.email,
                u.profile_photo,
                u.business_name,
                u.industry,
                chapter_data.chapter_code,
                chapter.chapter_name,

                CASE 
                    WHEN u.user_code = ? THEN 1
                    ELSE 0
                END AS is_self

            FROM users u

            LEFT JOIN (
                SELECT
                    cm.user_code,
                    MIN(cm.chapter_code) AS chapter_code
                FROM chapter_members cm
                WHERE cm.members_status = '1'
                GROUP BY cm.user_code
            ) chapter_data
                ON chapter_data.user_code = u.user_code
            LEFT JOIN chapters chapter
                ON chapter.chapter_code = chapter_data.chapter_code

            WHERE u.status = '1'
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
                    $user_code
                ]
            )->getResultArray();
        }


        // =========================================================
        // RETURN JSON
        // =========================================================
        return $this->response->setJSON($members);
    }
public function getMembers2()
{
    $db = \Config\Database::connect();

    try {
        $userCode = trim((string) session()->get('user_code'));

        if ($userCode === '') {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Session expired. Please login again.',
                    'csrfHash' => csrf_hash()
                ]);
        }

        // -----------------------------------------
        // FILTER INPUTS
        // -----------------------------------------
        $userName    = trim((string) $this->request->getPost('user_name'));
        $business    = trim((string) $this->request->getPost('business_name'));
        $category     = trim((string) $this->request->getPost('category'));
        $chapterCode  = trim((string) $this->request->getPost('chapter'));
        $type         = trim((string) $this->request->getPost('type'));

        $page = max(1, (int) $this->request->getPost('page'));

        $allowedPerPage = [12, 24, 36, 48];
        $perPage = (int) $this->request->getPost('per_page');

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 12;
        }

        $offset = ($page - 1) * $perPage;

        // -----------------------------------------
        // ACTIVE CHAPTER SUBQUERY
        // One active chapter per user
        // -----------------------------------------
        $chapterSubQuery = $db->table('chapter_members')
            ->select('user_code, MIN(chapter_code) AS chapter_code')
            ->where('members_status', '1')
            ->groupBy('user_code')
            ->getCompiledSelect();

        // -----------------------------------------
        // BASE QUERY
        // -----------------------------------------
        $builder = $db->table('users u');

        $builder->select([
            'u.user_code',
            'u.name',
            'u.phone',
            'u.email',
            'u.profile_photo',
            'u.business_name AS company_name',
            'u.industry',
            'cm.chapter_code',
            'c.chapter_name'
        ]);

        $builder->select(
            "CASE WHEN u.user_code = " . $db->escape($userCode) .
            " THEN 1 ELSE 0 END AS is_self",
            false
        );

        $builder->join(
            "($chapterSubQuery) cm",
            'cm.user_code = u.user_code',
            'left',
            false
        );

        $builder->join(
            'chapters c',
            'c.chapter_code = cm.chapter_code',
            'left'
        );

        $builder->where('u.status', '1');
        $builder->where('u.role', 'member');

        // -----------------------------------------
        // NAME FILTER
        // -----------------------------------------
        if ($userName !== '') {
            $builder->groupStart()
                ->like('u.name', $userName)
                ->orLike('u.user_code', $userName)
                ->groupEnd();
        }

        // -----------------------------------------
        // BUSINESS FILTER
        // -----------------------------------------
        if ($business !== '') {
            $builder->groupStart()
                ->like('u.business_name', $business)
                ->orLike('u.industry', $business)
                ->groupEnd();
        }

        // -----------------------------------------
        // CATEGORY / INDUSTRY FILTER
        // -----------------------------------------
        if ($category !== '') {
            $builder->where('u.industry', $category);
        }

        // -----------------------------------------
        // CHAPTER FILTER
        // -----------------------------------------
        if ($chapterCode !== '') {
            $builder->where('cm.chapter_code', $chapterCode);
        }

        // -----------------------------------------
        // CURRENT USER'S CHAPTER FILTER
        // -----------------------------------------
        if ($type === 'chapter') {
            $builder->whereIn(
                'u.user_code',
                function ($subBuilder) use ($userCode) {
                    $subBuilder->select('user_code')
                        ->from('chapter_members')
                        ->where(
                            'chapter_code',
                            function ($chapterBuilder) use ($userCode) {
                                $chapterBuilder->select('chapter_code')
                                    ->from('chapter_members')
                                    ->where('user_code', $userCode)
                                    ->where('members_status', '1')
                                    ->limit(1);
                            }
                        )
                        ->where('members_status', '1');
                }
            );
        }

        // -----------------------------------------
        // TOTAL RECORDS
        // -----------------------------------------
        $countBuilder = clone $builder;
        $totalRecords = $countBuilder->countAllResults();

        $totalPages = (int) ceil($totalRecords / $perPage);

        // Keep requested page within available pages
        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        // -----------------------------------------
        // FETCH MEMBERS
        // -----------------------------------------
        $builder->orderBy(
            "CASE WHEN u.user_code = " . $db->escape($userCode) .
            " THEN 0 ELSE 1 END",
            '',
            false
        );

        $builder->orderBy('u.name', 'ASC');
        $builder->limit($perPage, $offset);

        $members = $builder->get()->getResultArray();

        $selfContact = $db->table('users')
            ->select('name, business_name, phone, email')
            ->where('user_code', $userCode)
            ->get()
            ->getRowArray();

        // -----------------------------------------
        // RENDER MEMBER CARDS
        // -----------------------------------------
        $html = view(
            'include/member/card/referral_member_card',
            [
                'members'         => $members,
                'currentUserCode' => $userCode
            ]
        );

        return $this->response->setJSON([
            'status'      => true,
            'message'     => 'Members loaded successfully.',
            'html'        => $html,
            'total'       => $totalRecords,
            'count'       => $totalRecords,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => $totalPages,
            'self_contact' => $selfContact,
            'csrfHash'    => csrf_hash()
        ]);

    } catch (\Throwable $e) {

        log_message(
            'error',
            'getMembers2 Error: ' . $e->getMessage() .
            ' | File: ' . $e->getFile() .
            ' | Line: ' . $e->getLine()
        );

        return $this->response
            ->setStatusCode(500)
            ->setJSON([
                'status'  => false,
                'message' => ENVIRONMENT === 'development'
                    ? $e->getMessage()
                    : 'Unable to load members. Please try again.',
                'csrfHash' => csrf_hash()
            ]);
    }
}

   
public function AddNewReferralData()
{
    helper(['form']);

    $db = \Config\Database::connect();

    $session = \Config\Services::session();

    $commonModel = new \App\Models\CommonModel();

    // =========================================
    // JSON RESPONSE HELPER
    // =========================================

    $jsonResponse = function ($status, $message, $extra = []) {

        return $this->response->setJSON(array_merge([
            'status'   => $status,
            'message'  => $message,
            'csrfHash' => csrf_hash()
        ], $extra));

    };

    try {

        // =========================================
        // LOGGED-IN USER
        // =========================================

        $user_code = trim((string) $session->get('user_code'));

        if ($user_code === '') {

            return $jsonResponse(
                'error',
                'Session expired. Please login again.'
            );
        }

        // =========================================
        // FORM DATA
        // =========================================

        $data = $this->request->getPost();

        $to_user = trim((string) ($data['to_user'] ?? ''));

        $type = trim((string) ($data['referral_type'] ?? 'other'));

        // =========================================
        // VALIDATE RECIPIENT
        // =========================================

        if ($to_user === '') {

            return $jsonResponse(
                'error',
                'Please select a referral member.'
            );
        }

        if ($to_user === $user_code) {

            return $jsonResponse(
                'error',
                'You cannot refer yourself as the receiving member.'
            );
        }

        if (!in_array($type, ['self', 'other'], true)) {

            return $jsonResponse(
                'error',
                'Invalid referral type.'
            );
        }

        // =========================================
        // VERIFY RECIPIENT
        // =========================================

        $toUser = $db->table('users')
            ->select('user_code, name')
            ->where('user_code', $to_user)
            ->where('status', '1')
            ->where('role', 'member')
            ->get()
            ->getRow();

        if (!$toUser) {

            return $jsonResponse(
                'error',
                'Selected member is not available.'
            );
        }

        if ($type === 'self') {
            $selfContact = $db->table('users')
                ->select('name, business_name, phone, email')
                ->where('user_code', $user_code)
                ->get()
                ->getRowArray();

            if (!$selfContact) {
                return $jsonResponse(
                    'error',
                    'Your contact details could not be found.'
                );
            }

            $data['referral_name'] = $selfContact['name'] ?? '';
            $data['referral_company'] = $selfContact['business_name'] ?? '';
            $data['referral_mobile'] = $selfContact['phone'] ?? '';
            $data['referral_email'] = $selfContact['email'] ?? '';
        }

        // =========================================
        // REFERRAL PERSON DETAILS
        // =========================================

        $referralName = trim(
            (string) ($data['referral_name'] ?? '')
        );

        $referralCompany = trim(
            (string) ($data['referral_company'] ?? '')
        );

        $referralMobile = preg_replace(
            '/\D/',
            '',
            (string) ($data['referral_mobile'] ?? '')
        );

        if (strlen($referralMobile) === 12 && substr($referralMobile, 0, 2) === '91') {
            $referralMobile = substr($referralMobile, 2);
        } elseif (strlen($referralMobile) === 11 && $referralMobile[0] === '0') {
            $referralMobile = substr($referralMobile, 1);
        }

        $referralEmail = trim(
            (string) ($data['referral_email'] ?? '')
        );

        $remarks = trim(
            (string) ($data['remarks'] ?? '')
        );

        // =========================================
        // VALIDATE NAME
        // =========================================

        if ($referralName === '') {

            return $jsonResponse(
                'error',
                'Please enter referral person name.'
            );
        }

        // =========================================
        // VALIDATE BUSINESS
        // =========================================

        if ($referralCompany === '') {

            return $jsonResponse(
                'error',
                'Please enter business or company name.'
            );
        }

        // =========================================
        // VALIDATE MOBILE
        // =========================================

        if (!preg_match('/^[6-9][0-9]{9}$/', $referralMobile)) {

            return $jsonResponse(
                'error',
                'Please enter a valid 10-digit Indian mobile number.'
            );
        }

        // =========================================
        // VALIDATE EMAIL
        // =========================================

        if (
            $referralEmail !== '' &&
            !filter_var($referralEmail, FILTER_VALIDATE_EMAIL)
        ) {

            return $jsonResponse(
                'error',
                'Please enter a valid email address.'
            );
        }

        // =========================================
        // VALIDATE REMARKS
        // =========================================

        if ($remarks === '') {

            return $jsonResponse(
                'error',
                'Please enter referral remarks.'
            );
        }

        // =========================================
        // PRIORITY
        // =========================================

        $priority = (int) ($data['priority'] ?? 3);

        if ($priority < 1 || $priority > 5) {
            $priority = 3;
        }

        // =========================================
        // GET SENDER DETAILS
        // =========================================

        $fromUser = $db->table('users')
            ->select('user_code, name, phone, email, business_name')
            ->where('user_code', $user_code)
            ->get()
            ->getRow();

        if (!$fromUser) {

            return $jsonResponse(
                'error',
                'Your account details could not be found.'
            );
        }

        // =========================================
        // GENERATE REFERRAL CODE
        // =========================================

        $referralCode = $commonModel->generateSerial(
            'referrals',
            'referral_code',
            'REFF'
        );

        if (empty($referralCode)) {

            return $jsonResponse(
                'error',
                'Unable to generate referral code.'
            );
        }

        // =========================================
        // REFERRAL INSERT DATA
        // =========================================

        $insert = [

            'referral_code'  => $referralCode,

            'from_user'      => $user_code,

            'to_user'        => $to_user,

            'business'       => $referralCompany,

            'contact_number' => $referralMobile,

            'status'         => 'pending',

            'description'    => $remarks,

            'created_at'     => date('Y-m-d H:i:s')

        ];

        // =========================================
        // OPTIONAL DATABASE COLUMNS
        // =========================================

        $optionalColumns = [

            'referral_type'    => $type,

            'referral_name'    => $referralName,

            'referral_mobile'  => $referralMobile,

            'referral_email'   => $referralEmail,

            'referral_company' => $referralCompany,

            'priority'         => $priority,

            'temperature'      => $data['temperature'] ?? ''

        ];

        foreach ($optionalColumns as $column => $value) {

            if ($db->fieldExists($column, 'referrals')) {

                $insert[$column] = $value;
            }
        }

        // =========================================
        // DATABASE TRANSACTION
        // =========================================

        $db->transBegin();

        // =========================================
        // INSERT REFERRAL
        // =========================================

        $insertStatus = $db->table('referrals')->insert($insert);

        if (!$insertStatus) {

            $db->transRollback();

            log_message(
                'error',
                'Referral insert failed: ' .
                json_encode($db->error())
            );

            return $jsonResponse(
                'error',
                'Failed to save referral. Please try again.'
            );
        }

        $insertId = $db->insertID();

        // =========================================
        // GENERATE NOTIFICATION CODE
        // =========================================

        $notificationCode = $commonModel->generateSerial(
            'notifications',
            'n_code',
            'NOTI'
        );

        if (empty($notificationCode)) {

            $db->transRollback();

            return $jsonResponse(
                'error',
                'Unable to generate notification code.'
            );
        }

        // =========================================
        // NOTIFICATION DATA
        // =========================================

        $notification = [

            'n_code'    => $notificationCode,

            'user_code' => $to_user,

            'title'     => 'New Referral Received',

            'message'   => ($fromUser->name ?? 'Someone') .
                           ' sent you a new referral.',

            'status'    => 'unread'

        ];

        // =========================================
        // INSERT NOTIFICATION
        // =========================================

        $notificationStatus = $db->table('notifications')
            ->insert($notification);

        if (!$notificationStatus) {

            $db->transRollback();

            log_message(
                'error',
                'Referral notification insert failed: ' .
                json_encode($db->error())
            );

            return $jsonResponse(
                'error',
                'Referral notification could not be created.'
            );
        }

        // =========================================
        // COMMIT TRANSACTION
        // =========================================

        if ($db->transStatus() === false) {

            $db->transRollback();

            return $jsonResponse(
                'error',
                'Unable to complete referral transaction.'
            );
        }

        $db->transCommit();

        // =========================================
        // SUCCESS RESPONSE
        // =========================================

        return $jsonResponse(
            'success',
            'Referral Added Successfully',
            [
                'referral_code' => $referralCode,
                'insert_id'     => $insertId
            ]
        );

    } catch (\Throwable $e) {

        if ($db->transStatus() !== false) {
            $db->transRollback();
        }

        log_message(
            'error',
            'AddNewReferralData Error: ' .
            $e->getMessage() .
            ' | File: ' . $e->getFile() .
            ' | Line: ' . $e->getLine()
        );

        return $jsonResponse(
            'error',
            ENVIRONMENT === 'development'
                ? $e->getMessage()
                : 'Unable to submit referral. Please try again.'
        );
    }
}



    public function edit_refferral_status($referral_code)
    {
        $db = \Config\Database::connect();
        $referral_code = trim((string)$referral_code);
        $row = $db->table('referrals')
            ->groupStart()
            ->where('referral_code', $referral_code)
            ->orWhere('referral_id', $referral_code)
            ->groupEnd()
            ->get()
            ->getRow();

        if (empty($row)) {
            return $this->response->setBody('<p class="text-center text-danger p-3">Referral not found!</p>');
        }

        $currentStatus = self::normalizeStatus($row->status ?? 'Pending');
        $canonicalStatuses = [
            'Open For Discussion',
            'Contacted',
            'Pending',
            'Need Sometime',
            'MITB',
            'Completed',
            'Cancelled',
        ];

        $optionsHtml = '';
        foreach ($canonicalStatuses as $st) {
            $selected = (strcasecmp($st, $currentStatus) === 0) ? ' selected' : '';
            $optionsHtml .= '<option value="' . esc($st) . '"' . $selected . '>' . esc($st) . '</option>';
        }

        $output = '
<div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 8px 24px rgba(15,59,104,0.18); font-size:13px; overflow:hidden;">
    <form id="edit_refferral_status_action" method="post">
        <div class="modal-header" style="background:#0F3B68; color:#fff; border-bottom:1px solid rgba(255,255,255,0.1); padding:14px 18px;">
            <h6 class="modal-title mb-0" style="font-size:15px; font-weight:700; color:#ffffff;">Change Connect Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="referral_code" value="' . esc($row->referral_code ?? $referral_code) . '">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="fw-bold mb-1" style="color:#334155; font-size:13px;">Referral Status</label>
                    <select name="status" class="form-select" style="font-weight:600; font-size:13.5px; height:42px; border-radius:8px;">
                        ' . $optionsHtml . '
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0 pt-0 pb-3 px-3">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="status_c" class="btn btn-sm text-white px-4 rounded-pill fw-bold" style="background:#0F3B68; box-shadow:0 2px 6px rgba(15,59,104,0.25);">Update Status</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }


    public function edit_refferral_status_action()
    {
        $referral_code = trim((string)$this->request->getPost('referral_code'));
        if ($referral_code === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Referral code missing'
            ]);
        }
        $rawStatus = trim((string)$this->request->getPost('status'));
        $status = self::normalizeStatus($rawStatus);

        try {
            $db = \Config\Database::connect();
            $db->table('referrals')
                ->groupStart()
                ->where('referral_code', $referral_code)
                ->orWhere('referral_id', $referral_code)
                ->groupEnd()
                ->update(['status' => $status]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Status updated to ' . $status . ' successfully!'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage()
            ]);
        }
    }


    public function edit_refferral($referral_code)
    {
        $db = \Config\Database::connect();
        $referral_code = trim((string)$referral_code);
        $row = $db->table('referrals')
            ->groupStart()
            ->where('referral_code', $referral_code)
            ->orWhere('referral_id', $referral_code)
            ->groupEnd()
            ->get()
            ->getRow();

        if (empty($row)) {
            return $this->response->setBody('<p class="text-center text-danger p-3">Referral not found!</p>');
        }

        $currentStatus = self::normalizeStatus($row->status ?? 'Pending');
        $canonicalStatuses = [
            'Open For Discussion',
            'Contacted',
            'Pending',
            'Need Sometime',
            'MITB',
            'Completed',
            'Cancelled',
        ];

        $statusOptionsHtml = '';
        foreach ($canonicalStatuses as $st) {
            $selected = (strcasecmp($st, $currentStatus) === 0) ? ' selected' : '';
            $statusOptionsHtml .= '<option value="' . esc($st) . '"' . $selected . '>' . esc($st) . '</option>';
        }

        $output = '
<div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 8px 24px rgba(15,59,104,0.18); font-size:13px; overflow:hidden;">
    <form id="edit_refferral_action" method="post">
        <div class="modal-header" style="background:#0F3B68; color:#fff; border-bottom:1px solid rgba(255,255,255,0.1); padding:14px 18px;">
            <h6 class="modal-title mb-0" style="font-size:15px; font-weight:700; color:#ffffff;">Edit Referral</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="referral_code" value="' . esc($row->referral_code ?? $referral_code) . '">
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
                        ' . $statusOptionsHtml . '
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Description / Details</label>
                    <textarea name="description" class="form-control">' . esc($row->description) . '</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0 pt-0 pb-3 px-3">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white px-4 rounded-pill fw-bold" style="background:#0F3B68; box-shadow:0 2px 6px rgba(15,59,104,0.25);">Update</button>
        </div>
    </form>
</div>';
        return $this->response->setBody($output);
    }

    public function edit_refferral_action()
    {
        $referral_code = trim((string)$this->request->getPost('referral_code'));
        if ($referral_code === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Referral code missing'
            ]);
        }
        $rawStatus = trim((string)$this->request->getPost('status'));
        $data = [
            'business'       => $this->request->getPost('business'),
            'contact_number' => $this->request->getPost('contact_number'),
            'temperature'    => $this->request->getPost('temperature'),
            'status'         => self::normalizeStatus($rawStatus),
            'description'    => $this->request->getPost('description'),
        ];

        try {
            $db = \Config\Database::connect();
            $db->table('referrals')
                ->groupStart()
                ->where('referral_code', $referral_code)
                ->orWhere('referral_id', $referral_code)
                ->groupEnd()
                ->update($data);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => '✅ Referral updated successfully!'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => '❌ Update failed: ' . $e->getMessage()
            ]);
        }
    }
}

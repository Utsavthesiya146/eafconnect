<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\OneToOneModel;

class Visitor extends BaseController
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
    | DATABASE / MODELS
    |--------------------------------------------------------------------------
    */
        $userCode = (string) session()->get('user_code');

        $model       = new \App\Models\VisitorModel();
        $commonModel = new CommonModel();
        $db          = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | REQUEST
    |--------------------------------------------------------------------------
    */
        $request = service('request');

        /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
        $myPage = (int) ($request->getGet('my_page') ?? 1);

        $chapterPage = (int) (
            $request->getGet('chapter_page') ?? 1
        );

        $perPage = (int) (
            $request->getGet('perPage') ?? 10
        );

        $myPage      = max(1, $myPage);
        $chapterPage = max(1, $chapterPage);

        /*
    |--------------------------------------------------------------------------
    | ALLOWED PER PAGE
    |--------------------------------------------------------------------------
    */
        $allowedPerPage = [
            10,
            20,
            50,
            100
        ];

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */
        $search = trim(
            (string) ($request->getGet('search') ?? '')
        );

        $status = trim(
            (string) ($request->getGet('status') ?? '')
        );

        $date = trim(
            (string) ($request->getGet('date') ?? '')
        );

        /*
    |--------------------------------------------------------------------------
    | ALLOWED STATUS
    |--------------------------------------------------------------------------
    */
        $allowedStatuses = [
            'Invited',
            'Re-Invited',
            'Scheduled',
            'Pending',
            'Attended',
            'Cancelled',
            'visited',
            'pending',
            'cancelled'
        ];

        $meetingDay = $this->getMemberMeetingDay($userCode);

        $data['meetingDay'] = $meetingDay;

        /*
    |--------------------------------------------------------------------------
    | COMMON FILTER FUNCTION
    |--------------------------------------------------------------------------
    */
        $applyFilters = function ($builder) use (
            $search,
            $status,
            $date,
            $allowedStatuses
        ) {

            /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
            if (
                $status !== '' &&
                in_array(
                    $status,
                    $allowedStatuses,
                    true
                )
            ) {

                $builder->where(
                    'visitors.status',
                    $status
                );
            }

            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
            if ($search !== '') {

                $builder->groupStart();

                $builder->like(
                    'visitors.visitor_name',
                    $search
                );

                $builder->orLike(
                    'visitors.company_name',
                    $search
                );

                $builder->orLike(
                    'visitors.phone',
                    $search
                );

                $builder->orLike(
                    'u.name',
                    $search
                );

                $builder->orLike(
                    'u.email',
                    $search
                );

                $builder->orLike(
                    'c.chapter_name',
                    $search
                );

                $builder->groupEnd();
            }

            /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */
            if ($date !== '') {

                $builder->where(
                    'DATE(visitors.created_at)',
                    $date
                );
            }

            return $builder;
        };

        /*
    |--------------------------------------------------------------------------
    | INITIALIZE CHAPTER VARIABLES
    |--------------------------------------------------------------------------
    */
        $memberCodes  = [];
        $chapterCodes = [];

        /*
    |--------------------------------------------------------------------------
    | 1. MY VISITORS
    |--------------------------------------------------------------------------
    */
        $myBuilder = $db->table('visitors');

        $myBuilder->select("
        visitors.*,

        u.name AS member_name,
        u.email AS member_email,

        COALESCE(
            u.profile_photo,
            'empty.png'
        ) AS member_profile,

        c.chapter_name
    ");

        /*
    |--------------------------------------------------------------------------
    | JOIN USER
    |--------------------------------------------------------------------------
    */
        $myBuilder->join(
            'users AS u',
            'u.user_code = visitors.user_code',
            'left'
        );

        /*
    |--------------------------------------------------------------------------
    | JOIN CHAPTER
    |--------------------------------------------------------------------------
    */
        $myBuilder->join(
            'chapters AS c',
            'c.chapter_code = visitors.chapter_code',
            'left'
        );

        /*
    |--------------------------------------------------------------------------
    | ONLY MY VISITORS
    |--------------------------------------------------------------------------
    */
        $myBuilder->where(
            'visitors.user_code',
            $userCode
        );

        /*
    |--------------------------------------------------------------------------
    | APPLY FILTERS
    |--------------------------------------------------------------------------
    */
        $applyFilters($myBuilder);

        /*
    |--------------------------------------------------------------------------
    | COUNT MY VISITORS
    |--------------------------------------------------------------------------
    */
        $myCountBuilder = clone $myBuilder;

        $myTotal = (int)
        $myCountBuilder->countAllResults();

        /*
    |--------------------------------------------------------------------------
    | MY TOTAL PAGES
    |--------------------------------------------------------------------------
    */
        $myTotalPages = max(
            1,
            (int) ceil(
                $myTotal / $perPage
            )
        );

        /*
    |--------------------------------------------------------------------------
    | FIX MY PAGE
    |--------------------------------------------------------------------------
    */
        if (
            $myTotal > 0 &&
            $myPage > $myTotalPages
        ) {

            $myPage = $myTotalPages;
        }

        /*
    |--------------------------------------------------------------------------
    | MY OFFSET
    |--------------------------------------------------------------------------
    */
        $myOffset =
            ($myPage - 1) * $perPage;

        /*
    |--------------------------------------------------------------------------
    | GET MY VISITORS
    |--------------------------------------------------------------------------
    */
        $myBuilder
            ->orderBy(
                'visitors.visitors_id',
                'DESC'
            )
            ->limit(
                $perPage,
                $myOffset
            );

        $data['visitor'] =
            $myBuilder
            ->get()
            ->getResultArray();

        /*
    |--------------------------------------------------------------------------
    | 2. EVENTS
    |--------------------------------------------------------------------------
    */
        $eventResult = $commonModel->exe_query("
        SELECT *
        FROM events
        ORDER BY event_code DESC
    ");

        $data['events'] =
            $eventResult
            ? $eventResult->getResultArray()
            : [];

        /*
    |--------------------------------------------------------------------------
    | 3. GET LOGGED-IN USER
    |--------------------------------------------------------------------------
    */
        $rowData = $db->table('users')
            ->select(
                'user_code, member_role'
            )
            ->where(
                'user_code',
                $userCode
            )
            ->get()
            ->getRowArray();

        /*
    |--------------------------------------------------------------------------
    | CHECK CHAPTER LEADER
    |--------------------------------------------------------------------------
    */
        $memberRole = trim(
            (string) (
                $rowData['member_role'] ?? ''
            )
        );

        $isChapterLeader =
            !empty($rowData) &&
            ($rowData['user_code'] ?? '') === $userCode &&
            in_array(
                $memberRole,
                [
                    'President',
                    'Vice President'
                ],
                true
            );

        /*
    |--------------------------------------------------------------------------
    | DEFAULT CHAPTER DATA
    |--------------------------------------------------------------------------
    */
        $data['Visitors_chapter'] = [];

        $data['chapterPage'] =
            $chapterPage;

        $data['chapterTotal'] =
            0;

        $data['chapterTotalPages'] =
            1;

        /*
    |--------------------------------------------------------------------------
    | 4. CHAPTER VISITORS
    |--------------------------------------------------------------------------
    */
        if ($isChapterLeader) {

            /*
        |--------------------------------------------------------------------------
        | GET LEADER'S ACTIVE CHAPTERS
        |--------------------------------------------------------------------------
        */
            $chapterRows = $db->table('chapter_members')
                ->select('chapter_code')
                ->where(
                    'user_code',
                    $userCode
                )
                ->where(
                    'members_status',
                    '1'
                )
                ->get()
                ->getResultArray();

            /*
        |--------------------------------------------------------------------------
        | BUILD CHAPTER CODE ARRAY
        |--------------------------------------------------------------------------
        */
            foreach ($chapterRows as $chapter) {

                $chapterCode = trim(
                    (string) (
                        $chapter['chapter_code'] ?? ''
                    )
                );

                if ($chapterCode !== '') {

                    $chapterCodes[] =
                        $chapterCode;
                }
            }

            /*
        |--------------------------------------------------------------------------
        | REMOVE DUPLICATE CHAPTERS
        |--------------------------------------------------------------------------
        */
            $chapterCodes = array_values(
                array_unique($chapterCodes)
            );

            /*
        |--------------------------------------------------------------------------
        | GET ACTIVE MEMBERS FROM LEADER'S CHAPTERS
        |--------------------------------------------------------------------------
        */
            if (!empty($chapterCodes)) {

                $memberRows = $db->table('chapter_members')
                    ->select('user_code')
                    ->whereIn(
                        'chapter_code',
                        $chapterCodes
                    )
                    ->where(
                        'members_status',
                        '1'
                    )
                    ->get()
                    ->getResultArray();

                /*
            |--------------------------------------------------------------------------
            | BUILD MEMBER CODE ARRAY
            |--------------------------------------------------------------------------
            */
                foreach ($memberRows as $member) {

                    $memberCode = trim(
                        (string) (
                            $member['user_code'] ?? ''
                        )
                    );

                    if ($memberCode !== '') {

                        $memberCodes[] =
                            $memberCode;
                    }
                }

                /*
            |--------------------------------------------------------------------------
            | REMOVE DUPLICATE MEMBERS
            |--------------------------------------------------------------------------
            */
                $memberCodes = array_values(
                    array_unique($memberCodes)
                );
            }

            /*
        |--------------------------------------------------------------------------
        | GET CHAPTER VISITORS
        |--------------------------------------------------------------------------
        */
            if (!empty($memberCodes)) {

                $chapterBuilder =
                    $db->table('visitors');

                /*
            |--------------------------------------------------------------------------
            | SELECT
            |--------------------------------------------------------------------------
            */
                $chapterBuilder->select("
                visitors.*,

                u.name AS member_name,
                u.email AS member_email,

                COALESCE(
                    u.profile_photo,
                    'empty.png'
                ) AS member_profile,

                c.chapter_name
            ");

                /*
            |--------------------------------------------------------------------------
            | USER JOIN
            |--------------------------------------------------------------------------
            */
                $chapterBuilder->join(
                    'users AS u',
                    'u.user_code = visitors.user_code',
                    'left'
                );

                /*
            |--------------------------------------------------------------------------
            | CHAPTER JOIN
            |--------------------------------------------------------------------------
            */
                $chapterBuilder->join(
                    'chapters AS c',
                    'c.chapter_code = visitors.chapter_code',
                    'left'
                );

                /*
            |--------------------------------------------------------------------------
            | VISITORS ADDED BY CHAPTER MEMBERS
            |--------------------------------------------------------------------------
            */
                $chapterBuilder->whereIn(
                    'visitors.user_code',
                    $memberCodes
                );

                /*
            |--------------------------------------------------------------------------
            | DON'T SHOW MY OWN VISITORS
            |--------------------------------------------------------------------------
            */
                $chapterBuilder->where(
                    'visitors.user_code !=',
                    $userCode
                );

                /*
            |--------------------------------------------------------------------------
            | APPLY FILTERS
            |--------------------------------------------------------------------------
            */
                $applyFilters(
                    $chapterBuilder
                );

                /*
            |--------------------------------------------------------------------------
            | COUNT CHAPTER VISITORS
            |--------------------------------------------------------------------------
            */
                $chapterCountBuilder =
                    clone $chapterBuilder;

                $chapterTotal =
                    (int) $chapterCountBuilder
                        ->countAllResults();

                $data['chapterTotal'] =
                    $chapterTotal;

                /*
            |--------------------------------------------------------------------------
            | CHAPTER TOTAL PAGES
            |--------------------------------------------------------------------------
            */
                $chapterTotalPages = max(
                    1,
                    (int) ceil(
                        $chapterTotal / $perPage
                    )
                );

                $data['chapterTotalPages'] =
                    $chapterTotalPages;

                /*
            |--------------------------------------------------------------------------
            | FIX INVALID CHAPTER PAGE
            |--------------------------------------------------------------------------
            */
                if (
                    $chapterTotal > 0 &&
                    $chapterPage > $chapterTotalPages
                ) {

                    $chapterPage =
                        $chapterTotalPages;
                }

                $data['chapterPage'] =
                    $chapterPage;

                /*
            |--------------------------------------------------------------------------
            | CHAPTER OFFSET
            |--------------------------------------------------------------------------
            */
                $chapterOffset =
                    ($chapterPage - 1) *
                    $perPage;

                /*
            |--------------------------------------------------------------------------
            | GET CHAPTER VISITORS
            |--------------------------------------------------------------------------
            */
                $chapterBuilder
                    ->orderBy(
                        'visitors.visitors_id',
                        'DESC'
                    )
                    ->limit(
                        $perPage,
                        $chapterOffset
                    );

                $data['Visitors_chapter'] =
                    $chapterBuilder
                    ->get()
                    ->getResultArray();
            }
        }

        /*
    |--------------------------------------------------------------------------
    | FINAL PAGINATION / FILTER DATA
    |--------------------------------------------------------------------------
    */
        $data['myPage'] =
            $myPage;

        $data['myTotal'] =
            $myTotal;

        $data['myTotalPages'] =
            $myTotalPages;

        $data['chapterPage'] =
            $chapterPage;

        $data['chapterTotal'] =
            $data['chapterTotal'] ?? 0;

        $data['chapterTotalPages'] =
            $data['chapterTotalPages'] ?? 1;

        $data['perPage'] =
            $perPage;

        $data['search'] =
            $search;

        $data['status'] =
            $status;

        $data['date'] =
            $date;

        /*
    |--------------------------------------------------------------------------
    | CHAPTER MEETING DAY
    |--------------------------------------------------------------------------
    |
    | Used by Flatpickr in the visitor view.
    |
    */

        /*
    |--------------------------------------------------------------------------
    | LEADER STATUS
    |--------------------------------------------------------------------------
    */
        $data['isChapterLeader'] =
            $isChapterLeader;

        /*
    |--------------------------------------------------------------------------
    | STATUS COUNTS
    |--------------------------------------------------------------------------
    |
    | Search + Date are applied.
    | Status filter is intentionally NOT applied.
    |
    |--------------------------------------------------------------------------
    */
        $data['statusCounts'] = [
            'Invited'    => 0,
            'Re-Invited' => 0,
            'Scheduled'  => 0,
            'Pending'    => 0,
            'Attended'   => 0,
            'Cancelled'  => 0,
        ];

        /*
    |--------------------------------------------------------------------------
    | MY VISITOR STATUS COUNTS
    |--------------------------------------------------------------------------
    */
        $myStatusBuilder =
            $db->table('visitors');

        $myStatusBuilder->select("
        visitors.status,
        COUNT(*) AS total
    ");

        /*
    |--------------------------------------------------------------------------
    | USER JOIN
    |--------------------------------------------------------------------------
    */
        $myStatusBuilder->join(
            'users AS u',
            'u.user_code = visitors.user_code',
            'left'
        );

        /*
    |--------------------------------------------------------------------------
    | CHAPTER JOIN
    |--------------------------------------------------------------------------
    */
        $myStatusBuilder->join(
            'chapters AS c',
            'c.chapter_code = visitors.chapter_code',
            'left'
        );

        /*
    |--------------------------------------------------------------------------
    | ONLY MY VISITORS
    |--------------------------------------------------------------------------
    */
        $myStatusBuilder->where(
            'visitors.user_code',
            $userCode
        );

        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */
        if ($search !== '') {

            $myStatusBuilder->groupStart();

            $myStatusBuilder->like(
                'visitors.visitor_name',
                $search
            );

            $myStatusBuilder->orLike(
                'visitors.company_name',
                $search
            );

            $myStatusBuilder->orLike(
                'visitors.phone',
                $search
            );

            $myStatusBuilder->orLike(
                'u.name',
                $search
            );

            $myStatusBuilder->orLike(
                'u.email',
                $search
            );

            $myStatusBuilder->orLike(
                'c.chapter_name',
                $search
            );

            $myStatusBuilder->groupEnd();
        }

        /*
    |--------------------------------------------------------------------------
    | DATE
    |--------------------------------------------------------------------------
    */
        if ($date !== '') {

            $myStatusBuilder->where(
                'DATE(visitors.created_at)',
                $date
            );
        }

        /*
    |--------------------------------------------------------------------------
    | STATUS LIST
    |--------------------------------------------------------------------------
    */
        $myStatusBuilder
            ->whereIn(
                'visitors.status',
                [
                    'Invited',
                    'Re-Invited',
                    'Scheduled',
                    'Pending',
                    'Attended',
                    'Cancelled'
                ]
            )
            ->groupBy(
                'visitors.status'
            );

        /*
    |--------------------------------------------------------------------------
    | GET STATUS COUNTS
    |--------------------------------------------------------------------------
    */
        $myStatusRows =
            $myStatusBuilder
            ->get()
            ->getResultArray();

        foreach ($myStatusRows as $row) {

            $rowStatus = trim(
                (string) (
                    $row['status'] ?? ''
                )
            );

            if (
                isset(
                    $data['statusCounts'][$rowStatus]
                )
            ) {

                $data['statusCounts'][$rowStatus] =
                    (int) (
                        $row['total'] ?? 0
                    );
            }
        }

        /*
    |--------------------------------------------------------------------------
    | CHAPTER STATUS COUNTS
    |--------------------------------------------------------------------------
    */
        $data['chapterStatusCounts'] = [
            'Invited'    => 0,
            'Re-Invited' => 0,
            'Scheduled'  => 0,
            'Pending'    => 0,
            'Attended'   => 0,
            'Cancelled'  => 0,
        ];

        /*
    |--------------------------------------------------------------------------
    | ONLY CHAPTER LEADERS CAN SEE CHAPTER COUNTS
    |--------------------------------------------------------------------------
    */
        if (
            $isChapterLeader &&
            !empty($memberCodes)
        ) {

            $chapterStatusBuilder =
                $db->table('visitors');

            $chapterStatusBuilder->select("
            visitors.status,
            COUNT(*) AS total
        ");

            /*
        |--------------------------------------------------------------------------
        | USER JOIN
        |--------------------------------------------------------------------------
        */
            $chapterStatusBuilder->join(
                'users AS u',
                'u.user_code = visitors.user_code',
                'left'
            );

            /*
        |--------------------------------------------------------------------------
        | CHAPTER JOIN
        |--------------------------------------------------------------------------
        */
            $chapterStatusBuilder->join(
                'chapters AS c',
                'c.chapter_code = visitors.chapter_code',
                'left'
            );

            /*
        |--------------------------------------------------------------------------
        | CHAPTER MEMBERS' VISITORS
        |--------------------------------------------------------------------------
        */
            $chapterStatusBuilder->whereIn(
                'visitors.user_code',
                $memberCodes
            );

            /*
        |--------------------------------------------------------------------------
        | EXCLUDE LOGGED-IN USER
        |--------------------------------------------------------------------------
        */
            $chapterStatusBuilder->where(
                'visitors.user_code !=',
                $userCode
            );

            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
            if ($search !== '') {

                $chapterStatusBuilder->groupStart();

                $chapterStatusBuilder->like(
                    'visitors.visitor_name',
                    $search
                );

                $chapterStatusBuilder->orLike(
                    'visitors.company_name',
                    $search
                );

                $chapterStatusBuilder->orLike(
                    'visitors.phone',
                    $search
                );

                $chapterStatusBuilder->orLike(
                    'u.name',
                    $search
                );

                $chapterStatusBuilder->orLike(
                    'u.email',
                    $search
                );

                $chapterStatusBuilder->orLike(
                    'c.chapter_name',
                    $search
                );

                $chapterStatusBuilder->groupEnd();
            }

            /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */
            if ($date !== '') {

                $chapterStatusBuilder->where(
                    'DATE(visitors.created_at)',
                    $date
                );
            }

            /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
            $chapterStatusBuilder
                ->whereIn(
                    'visitors.status',
                    [
                        'Invited',
                        'Re-Invited',
                        'Scheduled',
                        'Pending',
                        'Attended',
                        'Cancelled'
                    ]
                )
                ->groupBy(
                    'visitors.status'
                );

            /*
        |--------------------------------------------------------------------------
        | GET CHAPTER STATUS COUNTS
        |--------------------------------------------------------------------------
        */
            $chapterStatusRows =
                $chapterStatusBuilder
                ->get()
                ->getResultArray();

            foreach (
                $chapterStatusRows as $row
            ) {

                $rowStatus = trim(
                    (string) (
                        $row['status'] ?? ''
                    )
                );

                if (
                    isset(
                        $data['chapterStatusCounts'][$rowStatus]
                    )
                ) {

                    $data['chapterStatusCounts'][$rowStatus] =
                        (int) (
                            $row['total'] ?? 0
                        );
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */
        return view(
            'member/visitor',
            $data
        );
    }
    public function AddNewvisitorData()
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
        $visitors_code = $commonModel->generateSerial('visitors', 'visitors_code', 'VC');

        // ✅ FORMAT DATE
        $dateVisited = !empty($data['date_visited'])
            ? date('Y-m-d', strtotime(str_replace('/', '-', $data['date_visited'])))
            : null;

        // ✅ INSERT DATA
        $insert = [
            'visitors_code' => $visitors_code,
            'user_code'     => (string)$user_code,
            'visitor_name'  => $data['visitor_name'] ?? null,
            'company'       => $data['company'] ?? null,
            'email'         => $data['email'] ?? null,
            'phone'         => $data['phone'] ?? null,
            'date_visited'  => $dateVisited,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $insertId = $commonModel->insert_data('visitors', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add Visitor'
            ]);
        }

        // ==============================
        // ✅ EMAIL NOTIFICATION
        // ==============================
        if (!empty($insert['email'])) {

            // 👉 MEMBER (who added visitor)
            $fromUser = $commonModel->db->table('users')
                ->select('*')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();

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
            $emailService->setTo($insert['email']);


            $emailService->setSubject('You Were Added as a Visitor');

            // ✅ EMAIL VIEW
            $message = view('mail/visitor', [
                'name'       => $insert['visitor_name'] ?? 'Guest',
                'member'     => $fromUser->name ?? 'Member',
                'contact'    => $fromUser->mobile ?? $fromUser->phone ?? 'N/A',
                'company'    => $insert['company'] ?? '',
                'phone'      => $insert['phone'] ?? '',
                'date'       => $dateVisited,
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
            'message' => 'Visitor Added Successfully & Email Sent'
        ]);
    }

    public function delete_v($visitors_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM visitors  WHERE visitors_code='" . esc($visitors_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_v_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="visitors_code" value="' . esc($row->visitors_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->visitor_name) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->company) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->phone) . '</p>
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

    public function delete_v_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $visitors_code = $this->request->getPost('visitors_code');
        if (!$visitors_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("visitors", ['visitors_code' => $visitors_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Visitor removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }



    public function edit_v($visitors_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM visitors  WHERE visitors_code='" . esc($visitors_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">F2F not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        // Safe query
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="edit_v_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Visitors Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="visitors_code" value="' . esc($row->visitors_code) . '">
            <div class="row g-2">
                   <div class="col-md-12">
                    <label class="fw-bold">status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">Current: ' . esc(ucfirst($row->status)) . '</option>
                        <option value="Attended">Attended</option>
                        <option value="Invited">Invited</option>
                        <option value="Re-Invited">Re-Invited</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                   <div class="col-md-12">
        <label class="fw-bold">Visited Date</label>
        <input type="date" name="date_visited" class="form-control" value="' . esc($row->date_visited) . '">
    </div>
                <div class="col-md-12">
                    <label class="fw-bold">summary</label>
                    <textarea name="note" class="form-control">' . esc($row->note) . '</textarea>
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

    public function edit_v_action()
    {
        $visitors_code = $this->request->getPost('visitors_code');
        if (!$visitors_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'visitors code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'note' => $this->request->getPost('note'),
            'date_visited' => $this->request->getPost('date_visited'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'visitors',
            $data,
            ['visitors_code' => $visitors_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ visitor updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }


    public function get_filtered_data()
    {
        $request  = \Config\Services::request();

        $user_code = session()->get('user_code');

        // Change this session key if your project uses another name
        $role = strtolower(trim(session()->get('role') ?? ''));

        $search = trim($request->getPost('search') ?? '');
        $date   = trim($request->getPost('date') ?? '');
        $status = trim($request->getPost('status') ?? '');

        $model = new \App\Models\VisitorModel();

        $model->select("
        visitors.*,
        u.name AS member_name,
        u.email AS member_email,
        COALESCE(u.profile_photo, 'empty.png') AS member_profile,
        c.chapter_name
    ");

        $model->join(
            'users AS u',
            'u.user_code = visitors.user_code',
            'left'
        );

        $model->join(
            'chapters AS c',
            'c.chapter_code = visitors.chapter_code',
            'left'
        );


        /* =====================================================
       SEARCH
       ===================================================== */

        if ($search !== '') {

            $model->groupStart()
                ->like('visitors.visitor_name', $search)
                ->orLike('visitors.company', $search)
                ->orLike('visitors.phone', $search)
                ->orLike('visitors.email', $search)
                ->orLike('visitors.note', $search)
                ->orLike('u.name', $search)
                ->orLike('u.email', $search)
                ->orLike('c.chapter_name', $search)
                ->groupEnd();
        }


        /* =====================================================
       STATUS
       ===================================================== */

        if ($status !== '') {

            $model->where(
                'visitors.status',
                $status
            );
        }


        /* =====================================================
       DATE
       ===================================================== */

        if ($date !== '') {

            /*
         * If date input is dd-mm-yyyy
         * convert it to yyyy-mm-dd
         */

            $dateParts = explode('-', $date);

            if (count($dateParts) === 3) {

                $formattedDate =
                    $dateParts[2] . '-' .
                    $dateParts[1] . '-' .
                    $dateParts[0];

                $model->where(
                    'visitors.date_visited',
                    $formattedDate
                );
            }
        }


        /* =====================================================
       ROLE BASED FILTER
       ===================================================== */

        if ($role === 'president') {

            /*
         * PRESIDENT
         *
         * Do NOT filter by user_code.
         *
         * President can see all visitor records.
         */
        } else {

            /*
         * MEMBER
         *
         * Only visitors added by the logged-in member.
         */

            $model->where(
                'visitors.user_code',
                $user_code
            );
        }


        /* =====================================================
       ORDER
       ===================================================== */

        $model->orderBy(
            'visitors.visitors_id',
            'DESC'
        );


        /* =====================================================
       GET DATA
       ===================================================== */

        $users = $model->findAll();


        /* =====================================================
       NO DATA
       ===================================================== */

        if (empty($users)) {

            return '
            <div class="alert alert-warning text-center">
                No Visitor Found
            </div>
        ';
        }


        /* =====================================================
       BUILD HTML
       ===================================================== */

        $output = '';

        $i = 1;

        foreach ($users as $row) {

            $output .= view(
                'include/member/card/visitor_card',
                [
                    'row' => $row,
                    'i'   => $i++
                ]
            );
        }


        return $output;
    }



    public function send_mail($visitors_code)
    {
        $commonModel = new CommonModel();

        $qry = "SELECT * FROM visitors WHERE visitors_code='" . esc($visitors_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();

        if (empty($user)) {
            return $this->response->setBody(
                '<p class="text-center text-danger">User not found!</p>'
            );
        }

        $row = $user[0];

        $output = '

<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="send_mail_action">

        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#fff;">Send Invitation</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body text-center">
            <input type="hidden" name="visitors_code" value="' . $row->visitors_code . '">
            <p>Are you sure you want to send this invitation mail?</p>
        </div>

        <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white" style="background:#1b4d8f;">Send</button>
        </div>

    </form>
</div>';

        return $this->response->setBody($output);
    }



    public function send_mail_action()
    {
        helper(['form']);

        $commonModel = new CommonModel();
        $session     = session();

        $user_code = $session->get('user_code');

        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired'
            ]);
        }

        // POST VALUE
        $visitors_code = $this->request->getPost('visitors_code');

        // VISITOR DATA
        $row = $commonModel->db->table('visitors')
            ->where('visitors_code', $visitors_code)
            ->get()
            ->getRow();

        if (!$row) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Visitor not found'
            ]);
        }

        // CHECK EMAIL
        if (empty($row->email)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Visitor email not found'
            ]);
        }

        // MEMBER DETAILS
        $fromUser = $commonModel->db->table('users')
            ->where('user_code', $user_code)
            ->get()
            ->getRow();

        // EMAIL CONFIG
        $email = \Config\Services::email();

        $config = [
            'protocol'    => 'smtp',
            'SMTPHost'    => 'smtp.hostinger.com',
            'SMTPUser'    => 'mail@eafconnect.com',
            'SMTPPass'    => 'mail@WHB26',
            'SMTPPort'    => 465,
            'SMTPCrypto'  => 'ssl',
            'mailType'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'SMTPTimeout' => 30,
        ];

        $email->initialize($config);
        $email->clear(true);

        $email->setFrom('mail@eafconnect.com', 'eafconnect');
        $email->setTo($row->email);
        $email->setSubject('You Were Added as a Visitor');

        $message = view('mail/visitor', [
            'name'    => $row->visitor_name ?? 'Guest',
            'member'  => $fromUser->name ?? 'Member',
            'contact' => $fromUser->mobile ?? $fromUser->phone ?? 'N/A',
            'company' => $row->company ?? '',
            'phone'   => $row->phone ?? '',
            'date'    => $row->created_at ?? date('Y-m-d'),
        ]);

        $email->setMessage($message);

        if (!$email->send()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $email->printDebugger(['headers'])
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Mail sent successfully'
        ]);
    }



    public function status_v_a($visitors_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM visitors  WHERE visitors_code='" . esc($visitors_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">F2F not found!</p>');
        }
        $row = $user[0];
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="status_v_a_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Visitors Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="visitors_code" value="' . esc($row->visitors_code) . '">
            <div class="row g-2">
                   <div class="col-md-12">
                    <label class="fw-bold">status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">Current: ' . esc(ucfirst($row->status)) . '</option>
                        <option value="Attended">Attended</option>
                        <option value="Invited">Invited</option>
                        <option value="Re-Invited">Re-Invited</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="fw-bold">Note</label>
                    <textarea name="note" class="form-control">' . esc($row->note) . '</textarea>
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

    public function status_v_a_action()
    {
        $visitors_code = $this->request->getPost('visitors_code');
        if (!$visitors_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'visitors code missing'
            ]);
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'note' => $this->request->getPost('note'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'visitors',
            $data,
            ['visitors_code' => $visitors_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ visitor updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
    public function getMemberMeetingDay(string $userCode): string
    {
        $db = \Config\Database::connect();

        $row = $db->table('chapter_members AS cm')
            ->select('c.meeting_day')
            ->join(
                'chapters AS c',
                'c.chapter_code = cm.chapter_code',
                'inner'
            )
            ->where(
                'cm.user_code',
                $userCode
            )
            ->where(
                'cm.members_status',
                '1'
            )
            ->where(
                'c.chapter_status',
                '1'
            )
            ->where(
                'c.meeting_day !=',
                ''
            )
            ->orderBy(
                'cm.member_id',
                'ASC'
            )
            ->get()
            ->getRow();

        if (!$row) {
            return '';
        }

        return trim((string) $row->meeting_day);
    }
}

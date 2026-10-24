<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\UserModel;
use Config\Database;
use App\Models\TyfcbModel;

class Chapter extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $model       = new \App\Models\ChapterModel();
        $commonModel = new CommonModel();
        $db          = Database::connect();
        $mitbModel   = new TyfcbModel();

        $search  = $this->request->getGet('search');
        $perPage = $this->request->getGet('perPage') ?? 10;

        /*
    |--------------------------------------------------------------------------
    | CHAPTERS + LEADER DETAILS
    |--------------------------------------------------------------------------
    */
        $model->select('
            chapters.*,
            users.name AS leader_name,
            users.profile_photo AS leader_photo
        ')
            ->join(
                'users',
                'users.user_code = chapters.leader_code',
                'left'
            );

        if (!empty($search)) {
            $model->groupStart()
                ->like('chapters.chapter_name', $search)
                ->orLike('chapters.location', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        $model->orderBy("FIELD(chapter_status,1,2,3)", "", false)
            ->orderBy("chapters_id", "ASC");

        /*
    |--------------------------------------------------------------------------
    | GET CHAPTERS
    |--------------------------------------------------------------------------
    */
        $chapters = $model->paginate($perPage);

        /*
    |--------------------------------------------------------------------------
    | MEMBER COUNT
    |--------------------------------------------------------------------------
    */
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

        $memberCounts = [];

        foreach ($counts as $c) {
            $memberCounts[$c['chapter_code']] = $c['total'];
        }

        /*
    |--------------------------------------------------------------------------
    | ATTACH MEMBER COUNT
    |--------------------------------------------------------------------------
    */
        foreach ($chapters as &$ch) {
            $code = $ch['chapter_code'];
            $ch['member_count'] = $memberCounts[$code] ?? 0;

            // Default leader photo
            if (
                empty($ch['leader_photo']) ||
                $ch['leader_photo'] === 'empty.png'
            ) {
                $ch['leader_photo'] = 'empty.png';
            }
        }

        /*
    |--------------------------------------------------------------------------
    | MEMBER LIST
    |--------------------------------------------------------------------------
    */
        $member = $commonModel
            ->exe_query("
            SELECT user_code, name
            FROM users
            WHERE status = '1'
        ")
            ->getResultArray();

        /*
    |--------------------------------------------------------------------------
    | DASHBOARD COUNTS
    |--------------------------------------------------------------------------
    */
        $meetingsCount_c_a = $db->query("
        SELECT COUNT(*) total 
        FROM events
    ")->getRow()->total ?? 0;

        $member_c_a = $db->query("
        SELECT COUNT(*) total 
        FROM users
    ")->getRow()->total ?? 0;

        $r_g_c_a = $db->query("
        SELECT COUNT(*) total 
        FROM referrals
    ")->getRow()->total ?? 0;

        $visitors_c_a = $db->query("
        SELECT COUNT(*) total 
        FROM visitors
    ")->getRow()->total ?? 0;

        $f2f__sc_a = $db->query("
        SELECT COUNT(*) total 
        FROM one_to_ones
    ")->getRow()->total ?? 0;

        $chapters_c_a = $db->query("
        SELECT COUNT(*) total 
        FROM chapters
    ")->getRow()->total ?? 0;

        /*
    |--------------------------------------------------------------------------
    | MITB TOTAL
    |--------------------------------------------------------------------------
    */
        $totalMITB_a = $db->query("
        SELECT COALESCE(SUM(amount),0) total
        FROM tyfcb
    ")->getRow()->total ?? 0;

        /*
    |--------------------------------------------------------------------------
    | VIEW DATA
    |--------------------------------------------------------------------------
    */
        $data = [
            'chapters' => $chapters,
            'pager'    => $model->pager,
            'search'   => $search,
            'member'   => $member,

            'meetingsCount_c_a' => $meetingsCount_c_a,
            'member_c_a'       => $member_c_a,
            'r_g_c_a'          => $r_g_c_a,
            'visitors_c_a'     => $visitors_c_a,
            'f2f__sc_a'        => $f2f__sc_a,
            'chapters_c_a'     => $chapters_c_a,
            'totalMITB_a'      => $totalMITB_a,
        ];

        return view('member/chapter', $data);
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

        /* CHAPTER LEADER */
        leader.name AS leader_name,
        leader.profile_photo AS leader_photo,
        leader.designation AS leader_designation,

        /* ACTIVE MEMBER COUNT */
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

    /* LEADER DETAILS */
    LEFT JOIN users leader
        ON leader.user_code = c.leader_code

    WHERE c.chapter_code = ?
";

        $chapter = $db
            ->query($qry, [$chapter_code])
            ->getResultArray();


        /* =====================================================
       2. CHAPTER MEMBERS
       Only active chapter members + active users
    ===================================================== */

        $qry2 = "
        SELECT 
            cm.*,

            cm.joined_date AS join_date,
            cm.members_status AS member_status,

            u.name,
            u.email,
            u.phone,
            u.profile_photo,
            u.city,
            u.industry,
            u.member_role,
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
       3. ACTIVE USER LIST
       Include chapter name for Referred By dropdown
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
       4. GET ACTIVE MEMBER USER CODES
       THIS CHAPTER ONLY
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
       5. DEFAULT STATS
    ===================================================== */

        $meetings          = 0;
        $referralsGiven    = 0;
        $referralsReceived = 0;
        $connects          = 0;
        $visitors          = 0;
        $notifications     = 0;

        $totalMITBReceived = 0;
        $totalMITBGiven    = 0;


        /* =====================================================
       6. STATS CALCULATIONS
    ===================================================== */

        if (!empty($chapterMemberCodes)) {

            /*
        |--------------------------------------------------------------------------
        | Safely escape member codes for IN()
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
       7. TOTAL MITB
    ===================================================== */

        $totalMITB = $totalMITBGiven;


        /* =====================================================
       8. LOAD VIEW
    ===================================================== */

        return view('member/chapter_details', [

            'chapters'      => $chapter,

            'users'         => $users,

            'member'        => $member,

            'meetings'      => $meetings,

            'connects'      => $connects,

            'visitors'      => $visitors,

            'notifications' => $notifications,

            'totalMITB'     => $totalMITB,

            'businessDone'  => $totalMITBReceived
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
        $members = $commonModel
            ->exe_query("SELECT * FROM users WHERE status='1' $notIn")
            ->getResultArray();

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
       GET USER DETAILS
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

        $commonModel->insert_data('chapter_members', $insert);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Member added successfully'
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

            $output .= view('include/member/card/chapter_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        echo $output;
    }
    public function get_filtered_data2()
    {
        $request = \Config\Services::request();

        $search = trim($request->getPost('search') ?? '');
        $status = trim($request->getPost('status') ?? '');

        $session   = session();
        $user_code = $session->get('user_code');

        if (!$user_code) {
            return $this->response
                ->setStatusCode(401)
                ->setBody('<div class="col-12 text-center p-5"><h5>Session expired.</h5></div>');
        }

        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | 1. GET ONLY MY CHAPTER CODES
    |    Leader OR Active Member
    |--------------------------------------------------------------------------
    */

        $chapterQry = "
        SELECT DISTINCT c.chapter_code

        FROM chapters c

        WHERE c.leader_code = ?

        OR EXISTS (
            SELECT 1
            FROM chapter_members cm
            INNER JOIN users u
                ON u.user_code = cm.user_code

            WHERE cm.chapter_code = c.chapter_code
              AND cm.user_code = ?
              AND cm.members_status = '1'
              AND u.status = '1'
        )
    ";

        $chapterRows = $db
            ->query($chapterQry, [$user_code, $user_code])
            ->getResultArray();

        $chapter_codes = array_column($chapterRows, 'chapter_code');


        /*
    |--------------------------------------------------------------------------
    | 2. NO CHAPTERS
    |--------------------------------------------------------------------------
    */

        if (empty($chapter_codes)) {

            return $this->response->setBody('
            <div class="col-12 text-center p-5">
                <h5>No Members Found</h5>
            </div>
        ');
        }


        /*
    |--------------------------------------------------------------------------
    | 3. CREATE SAFE PLACEHOLDERS
    |--------------------------------------------------------------------------
    */

        $placeholders = implode(
            ',',
            array_fill(0, count($chapter_codes), '?')
        );


        /*
    |--------------------------------------------------------------------------
    | 4. GET MEMBERS ONLY FROM MY CHAPTERS
    |--------------------------------------------------------------------------
    */

        $qry = "
        SELECT
            cm.member_code,
            cm.chapter_code,
            cm.user_code,
            cm.role AS member_role,
            cm.joined_date AS join_date,
            cm.members_status,

            u.name,
            u.email,
            u.phone,
            u.city,
            u.industry,
            u.profile_photo,
            u.status,

            c.chapter_name

        FROM chapter_members cm

        INNER JOIN users u
            ON u.user_code = cm.user_code

        INNER JOIN chapters c
            ON c.chapter_code = cm.chapter_code

        WHERE cm.chapter_code IN ($placeholders)

          AND cm.members_status = '1'

          AND u.status = '1'
    ";

        /*
    |--------------------------------------------------------------------------
    | 5. SEARCH
    |--------------------------------------------------------------------------
    */

        $params = $chapter_codes;

        if (!empty($search)) {

            $qry .= "
            AND (
                u.name LIKE ?
                OR u.email LIKE ?
                OR u.phone LIKE ?
                OR u.city LIKE ?
                OR u.last_name LIKE ?
                OR u.whatsapp LIKE ?
                OR u.address LIKE ?
                OR u.state LIKE ?
                OR u.designation LIKE ?
                OR u.skills LIKE ?
                OR u.services LIKE ?
                OR u.products LIKE ?
                OR u.bio LIKE ?
                OR u.business_name LIKE ?
                OR u.industry LIKE ?
                OR u.subcategory LIKE ?
                OR u.description LIKE ?
                OR u.office_address LIKE ?
                OR u.service_areas LIKE ?
            )
        ";

            $searchValue = '%' . $search . '%';

            for ($i = 0; $i < 19; $i++) {
                $params[] = $searchValue;
            }
        }


        /*
    |--------------------------------------------------------------------------
    | 6. STATUS FILTER
    |--------------------------------------------------------------------------
    */

        if (!empty($status)) {
            $qry .= " AND cm.members_status = ?";
            $params[] = $status;
        }


        /*
    |--------------------------------------------------------------------------
    | 7. ORDER
    |--------------------------------------------------------------------------
    */

        if (!empty($search)) {
            $searchEscaped = $db->escapeLikeString($search);
            $qry .= "
            ORDER BY 
                CASE 
                    WHEN u.name LIKE '{$searchEscaped}%' THEN 1
                    WHEN u.name LIKE '%{$searchEscaped}%' THEN 2
                    WHEN u.business_name LIKE '{$searchEscaped}%' THEN 3
                    WHEN u.business_name LIKE '%{$searchEscaped}%' THEN 4
                    WHEN u.industry LIKE '{$searchEscaped}%' THEN 5
                    ELSE 6
                END ASC,
                u.name ASC
            ";
        } else {
            $qry .= "
            ORDER BY cm.member_code ASC
            ";
        }


        /*
    |--------------------------------------------------------------------------
    | 8. EXECUTE
    |--------------------------------------------------------------------------
    */

        $users = $db
            ->query($qry, $params)
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | 9. BUILD HTML
    |--------------------------------------------------------------------------
    */

        $output = '';

        if (!empty($users)) {

            foreach ($users as $row) {

                $output .= '
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    ' . view(
                    'include/member/card/member_card',
                    ['row' => $row]
                ) . '
                </div>
            ';
            }
        } else {

            $output = '
            <div class="col-12 text-center p-5">
                <h5>No Members Found</h5>
            </div>
        ';
        }


        return $this->response->setBody($output);
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

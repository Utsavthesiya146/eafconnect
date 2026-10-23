<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use Dompdf\Dompdf;
use Config\Database;

class Reports extends BaseController
{
    public function weekly_slips()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $user_code = session()->get('user_code');
        $commonModel = new CommonModel();
        $model = new \App\Models\TyfcbModel();

        // =====================================
        // LAST 15 DAYS / LAST 2 WEEKS FILTER
        // =====================================
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-14 days'));
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');

        // ===== Connects Given =====
        $r_g = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE from_user = '$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $r_g_c = $r_g->getNumRows();

        // ===== Connects Received =====
        $r_r = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE to_user = '$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $r_r_c = $r_r->getNumRows();

        // ===== MITB GIVEN =====
        $totalMITBGivenRow = $model
            ->selectSum('amount')
            ->where('to_user', $user_code)
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->first();

        $totalMITBGiven = $totalMITBGivenRow['amount'] ?? 0;

        // ===== MITB RECEIVED =====
        $totalMITBReceivedRow = $model
            ->selectSum('amount')
            ->where('from_user', $user_code)
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->first();

        $totalMITBReceived = $totalMITBReceivedRow['amount'] ?? 0;

        // ===== F2F Meetings =====
        $f2f = $commonModel->exe_query("
        SELECT *
        FROM one_to_ones
        WHERE from_user = '$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $meetingsCount = $f2f->getNumRows();

        // ===== Visitors =====
        $visitors = $commonModel->exe_query("
        SELECT *
        FROM visitors
        WHERE user_code = '$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $visitors_c = $visitors->getNumRows();

        // ===== Total Amount =====
        $totalAmount = $totalMITBGiven + $totalMITBReceived;

        $data = [
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'r_g_c'             => $r_g_c,
            'r_r_c'             => $r_r_c,
            'totalMITBGiven'    => $totalMITBGiven,
            'totalMITBReceived' => $totalMITBReceived,
            'meetingsCount'     => $meetingsCount,
            'visitors_c'        => $visitors_c,
            'totalAmount'       => $totalAmount,
        ];

        return view('member/weekly_slips', $data);
    }



    public function download_weekly_report()
    {
        $user_code = session()->get('user_code');

        $commonModel = new \App\Models\CommonModel();
        $model = new \App\Models\TyfcbModel();

        // =====================================
        // LAST 15 DAYS / LAST 2 WEEKS
        // =====================================
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-14 days'));
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');

        // ===== Connects Given =====
        $r_g = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE from_user='$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $r_g_c = $r_g->getNumRows();

        // ===== Connects Received =====
        $r_r = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE to_user='$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $r_r_c = $r_r->getNumRows();

        // ===== MITB GIVEN =====
        $given = $model->selectSum('amount')
            ->where('to_user', $user_code)
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->first();

        $totalMITBGiven = $given['amount'] ?? 0;

        // ===== MITB RECEIVED =====
        $received = $model->selectSum('amount')
            ->where('from_user', $user_code)
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->first();

        $totalMITBReceived = $received['amount'] ?? 0;

        // ===== F2F Meetings =====
        $f2f = $commonModel->exe_query("
        SELECT *
        FROM one_to_ones
        WHERE from_user='$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $meetingsCount = $f2f->getNumRows();

        // ===== Visitors =====
        $visitors = $commonModel->exe_query("
        SELECT *
        FROM visitors
        WHERE user_code='$user_code'
        AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'
    ");
        $visitors_c = $visitors->getNumRows();

        $data = [
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'r_g_c'             => $r_g_c,
            'r_r_c'             => $r_r_c,
            'totalMITBGiven'    => $totalMITBGiven,
            'totalMITBReceived' => $totalMITBReceived,
            'meetingsCount'     => $meetingsCount,
            'visitors_c'        => $visitors_c,
            'totalAmount'       => $totalMITBGiven + $totalMITBReceived,
        ];

        $html = view('member/weekly_report_pdf', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Last_15_Days_Report.pdf", ["Attachment" => true]);
    }

    public function received_connects_report()
    {
        $commonModel = new CommonModel();
        $user_code = session()->get('user_code');

        /* ===============================
       USER DETAILS
    ===============================*/
        $user = $commonModel
            ->exe_query("
            SELECT u.*, c.chapter_name
            FROM users u
            LEFT JOIN chapter_members cm ON u.user_code = cm.user_code
            LEFT JOIN chapters c ON cm.chapter_code = c.chapter_code
            WHERE u.status='1' AND u.user_code='$user_code'
        ")->getResult();

        /* ===============================
       RECEIVED REFERRALS
    ===============================*/
        $received = $commonModel
            ->exe_query("
            SELECT r.*, 
                   fu.name AS from_name, fu.email AS from_email, fu.profile_photo AS from_profile,
                   tu.name AS to_name, tu.email AS to_email, tu.profile_photo AS to_profile,
                   c.chapter_name,
                   DATE(r.created_at) AS day
            FROM referrals r
            LEFT JOIN users fu ON fu.user_code = r.from_user
            LEFT JOIN users tu ON tu.user_code = r.to_user
            LEFT JOIN chapter_members cm ON cm.user_code = fu.user_code
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE tu.status='1' AND tu.user_code='$user_code'
            ORDER BY r.created_at DESC
        ")->getResultArray();

        /* ===============================
       GIVEN REFERRALS
    ===============================*/
        $given = $commonModel
            ->exe_query("
            SELECT r.*, 
                   fu.name AS from_name, fu.email AS from_email, fu.profile_photo AS from_profile,
                   tu.name AS to_name, tu.email AS to_email, tu.profile_photo AS to_profile,
                   c.chapter_name
            FROM referrals r
            LEFT JOIN users fu ON fu.user_code = r.from_user
            LEFT JOIN users tu ON tu.user_code = r.to_user
            LEFT JOIN chapter_members cm ON cm.user_code = tu.user_code
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE fu.status='1' AND fu.user_code='$user_code'
            ORDER BY r.created_at DESC
        ")->getResultArray();

        /* ===============================
       STATUS COUNTS
    ===============================*/
        $statusCounts = [
            'all' => 0,
            'open for discussion' => 0,
            'contacted' => 0,
            'not converted' => 0,
            'cancelled' => 0,
            'MITB' => 0,
        ];

        foreach ($received as $row) {
            $status = strtolower(trim($row['status'] ?? 'new'));
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
            $statusCounts['all']++;
        }

        /* ===============================
       MEMBER LIST
    ===============================*/
        $member = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();

        /* ===============================
       DAY-WISE CHART DATA (Received + Given)
    ===============================*/
        $days = [];
        $receivedData = [];
        $givenData = [];

        // Collect all dates
        foreach ($received as $row) $days[$row['day']] = true;
        foreach ($given as $row) {
            $day = date('Y-m-d', strtotime($row['created_at']));
            $days[$day] = true;
        }

        $days = array_keys($days);
        sort($days);

        // Initialize
        foreach ($days as $day) {
            $receivedData[$day] = 0;
            $givenData[$day] = 0;
        }

        // Count per day
        foreach ($received as $row) $receivedData[$row['day']]++;
        foreach ($given as $row) {
            $day = date('Y-m-d', strtotime($row['created_at']));
            $givenData[$day]++;
        }

        /* ===============================
       SEND DATA TO VIEW
    ===============================*/
        $data = [
            'user'          => $user,
            'member'        => $member,
            'received'      => $received,
            'given'         => $given,
            'receivedCount' => count($received),
            'statusCounts'  => $statusCounts,
            'chartLabels'   => $days,
            'chartReceived' => array_values($receivedData),
            'chartGiven'    => array_values($givenData),
        ];

        return view('member/received_connects_report', $data);
    }

    public function participation()
    {
        return view('member/participation_data');
    }



    public function my_participation_report()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $db = \Config\Database::connect();
        $model = new \App\Models\TyfcbModel();
        $commonModel = new \App\Models\CommonModel();
        $mitbModel = new \App\Models\TyfcbModel();
        $user_code = session()->get('user_code');

        /* ======================================================
        FILTER (TAB BASED)
    ====================================================== */

        $filter = $this->request->getGet('filter') ?? '180';

        $dateCondition = "";
        $dateParams = [];

        if ($filter != 'all') {
            $dateCondition = " AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $dateParams[] = (int)$filter;
        }

        /* ======================================================
        COUNTS (FILTERED)
    ====================================================== */
        list($dateCondition1, $dateParams1) = $this->getDateCondition('cm.members_created_at');

        $chapterMembers_c = $db->query("
    SELECT COUNT(DISTINCT cm.user_code) AS total
    FROM chapter_members cm
    INNER JOIN chapter_members mycm 
        ON mycm.chapter_code = cm.chapter_code
    WHERE mycm.user_code = ?
    AND cm.members_status = '1'
    $dateCondition1
", array_merge([$user_code], $dateParams1))
            ->getRow()->total ?? 0;

        $meetingsCount = $db->query("
        SELECT COUNT(*) total
        FROM one_to_ones
        WHERE from_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $r_g_c = $db->query("
        SELECT COUNT(*) total
        FROM referrals
        WHERE from_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $r_r_c = $db->query("
        SELECT COUNT(*) total
        FROM referrals
        WHERE to_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $visitors_c = $db->query("
        SELECT COUNT(*) total
        FROM visitors
        WHERE user_code=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $f2f__sc = $db->query("
        SELECT COUNT(*) total
        FROM one_to_ones
        WHERE from_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $f2f__rc = $db->query("
        SELECT COUNT(*) total
        FROM one_to_ones
        WHERE to_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $notifications_c = $db->query("
        SELECT COUNT(*) total
        FROM notifications
        WHERE user_code=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        /* ======================================================
        MITB TOTALS (FILTERED)
    ====================================================== */

        $totalMITBReceived = $db->query("
        SELECT COALESCE(SUM(amount),0) total
        FROM tyfcb
        WHERE from_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        $totalMITBGiven = $db->query("
        SELECT COALESCE(SUM(amount),0) total
        FROM tyfcb
        WHERE to_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        /* ======================================================
        CHART DATA
    ====================================================== */

        $givenQuery = $db->query("
        SELECT DATE(created_at) mitb_date,
               SUM(amount) total_given
        FROM tyfcb
        WHERE to_user=?
        $dateCondition
        GROUP BY DATE(created_at)
        ORDER BY mitb_date ASC
    ", array_merge([$user_code], $dateParams))->getResultArray();


        $receivedQuery = $db->query("
        SELECT DATE(created_at) mitb_date,
               SUM(amount) total_received
        FROM tyfcb
        WHERE from_user=?
        $dateCondition
        GROUP BY DATE(created_at)
        ORDER BY mitb_date ASC
    ", array_merge([$user_code], $dateParams))->getResultArray();


        $dates = [];
        $datesFormatted = [];
        $givenData = [];
        $receivedData = [];

        foreach ($givenQuery as $row)
            $dates[$row['mitb_date']] = true;

        foreach ($receivedQuery as $row)
            $dates[$row['mitb_date']] = true;

        ksort($dates);

        foreach (array_keys($dates) as $date) {

            $datesFormatted[] = date('d M', strtotime($date));

            $givenAmount = 0;
            foreach ($givenQuery as $g)
                if ($g['mitb_date'] == $date)
                    $givenAmount = (float)$g['total_given'];

            $receivedAmount = 0;
            foreach ($receivedQuery as $r)
                if ($r['mitb_date'] == $date)
                    $receivedAmount = (float)$r['total_received'];

            $givenData[] = $givenAmount;
            $receivedData[] = $receivedAmount;
        }




        // ================= PROFILE STRENGTH =================
        $user = $db->table('users')
            ->where('user_code', $user_code)
            ->get()
            ->getRowArray();

        $totalScore = 0;
        $maxScore = 100;

        $filled = fn($f) => !empty($user[$f]) && $user[$f] != '0';

        if ($filled('name')) $totalScore += 4;
        if ($filled('email')) $totalScore += 4;
        if ($filled('phone')) $totalScore += 4;
        if ($user['profile_photo'] != 'empty.png') $totalScore += 3;

        if ($filled('gender')) $totalScore += 3;
        if ($filled('dob')) $totalScore += 3;
        if ($filled('bio')) $totalScore += 4;

        if ($filled('address')) $totalScore += 2;
        if ($filled('city')) $totalScore += 2;
        if ($filled('state')) $totalScore += 2;
        if ($filled('country')) $totalScore += 2;
        if ($filled('pincode')) $totalScore += 2;

        if ($filled('designation')) $totalScore += 3;
        if ($filled('exp_years')) $totalScore += 3;
        if ($filled('skills')) $totalScore += 4;

        if ($filled('business_name')) $totalScore += 3;
        if ($filled('industry')) $totalScore += 3;
        if ($filled('description')) $totalScore += 4;

        if ($filled('website')) $totalScore += 5;
        if ($filled('linkedin')) $totalScore += 5;

        if ($filled('business_logo')) $totalScore += 5;
        if ($filled('business_card')) $totalScore += 5;

        if ($filled('services')) $totalScore += 4;
        if ($filled('products')) $totalScore += 3;
        if ($filled('languages')) $totalScore += 4;
        if ($filled('hobbies')) $totalScore += 4;

        if ($user['verified'] == '1') $totalScore += 10;

        $profileStrength = min(100, round(($totalScore / $maxScore) * 100));

        /* ======================================================
   ✅ THURSDAY → THURSDAY 10:30AM WEEK CALCULATION
====================================================== */

        date_default_timezone_set('Asia/Kolkata');

        $db = \Config\Database::connect();

        $now = new \DateTime();

        // Start from current date/time
        $weekStart = new \DateTime();

        // End date = next 15 days
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+15 days');

        // Convert to string for SQL
        $startDate = $weekStart->format('Y-m-d H:i:s');
        $endDate   = $weekEnd->format('Y-m-d H:i:s');


        /* ======================================================
   ✅ WEEKLY PERFORMANCE COUNTS
====================================================== */

        // Referral Given (Weekly)
        $weekly_ref_given = $db->query("
    SELECT COUNT(*) AS total
    FROM referrals
    WHERE from_user = ?
    AND created_at >= ?
    AND created_at < ?
", [$user_code, $startDate, $endDate])
            ->getRow()->total ?? 0;


        // MITB Received (Weekly)
        $weekly_mitb_received = $db->query("
    SELECT COALESCE(SUM(amount),0) AS total
    FROM tyfcb
    WHERE to_user = ?
    AND created_at >= ?
    AND created_at < ?
", [$user_code, $startDate, $endDate])
            ->getRow()->total ?? 0;


        // MITB Given (Weekly)
        $weekly_mitb_given = $db->query("
    SELECT COALESCE(SUM(amount),0) AS total
    FROM tyfcb
    WHERE from_user = ?
    AND created_at >= ?
    AND created_at < ?
", [$user_code, $startDate, $endDate])
            ->getRow()->total ?? 0;


        // F2F Send (Weekly)
        $weekly_f2f_send = $db->query("
    SELECT COUNT(*) AS total
    FROM one_to_ones
    WHERE from_user = ?
    AND created_at >= ?
    AND created_at < ?
", [$user_code, $startDate, $endDate])
            ->getRow()->total ?? 0;


        // F2F Received (Weekly)
        $weekly_f2f_receive = $db->query("
    SELECT COUNT(*) AS total
    FROM one_to_ones
    WHERE to_user = ?
    AND created_at >= ?
    AND created_at < ?
", [$user_code, $startDate, $endDate])
            ->getRow()->total ?? 0;


        // Visitors (Weekly)
        $weekly_visitors = $db->query("
    SELECT COUNT(*) AS total
    FROM visitors
    WHERE user_code = ?
    AND created_at >= ?
    AND created_at < ?
", [$user_code, $startDate, $endDate])
            ->getRow()->total ?? 0;
        /* ======================================================
   ✅ EVENTS (UNCHANGED - CLEAN)
====================================================== */

        $eventModel = new \App\Models\EventModel();
        $events = $eventModel
            ->select('events.*, chapters.chapter_name')
            ->join('chapters', 'chapters.chapter_code = events.chapter_code', 'left')
            ->where('events.status', '1')
            ->orderBy('events.date', 'DESC')
            ->orderBy('events.event_id', 'DESC')
            ->findAll();
        $db = Database::connect();
        /* =====================================================
           1. GET MY CHAPTERS (Leader OR Member)
        ===================================================== */
        $qry = "
            SELECT c.*,
            (
                SELECT COUNT(*)
                FROM chapter_members cm
                WHERE cm.chapter_code = c.chapter_code
            ) AS member_count
            FROM chapters c
            WHERE c.leader_code = ?
               OR EXISTS (
                    SELECT 1
                    FROM chapter_members cm
                    WHERE cm.chapter_code = c.chapter_code
                    AND cm.user_code = ?
               )
            ORDER BY c.chapters_id DESC
        ";

        $chapters = $db->query($qry, [$user_code, $user_code])->getResultArray();

        /* =====================================================
           2. GET CHAPTER MEMBERS
        ===================================================== */
        $chapter_codes = array_column($chapters, 'chapter_code');

        if (!empty($chapter_codes)) {

            $placeholders = implode(',', array_fill(0, count($chapter_codes), '?'));

            $qry2 = "
                SELECT cm.*, 
                       u.name, u.email, u.phone, u.profile_photo, u.city,
                       c.chapter_name, c.chapter_code,
                       cm.role AS member_role,
                       cm.joined_date AS join_date,
                       cm.members_status AS status
                FROM chapter_members cm
                INNER JOIN users u ON cm.user_code = u.user_code
                INNER JOIN chapters c ON cm.chapter_code = c.chapter_code
                WHERE cm.chapter_code IN ($placeholders)
                ORDER BY cm.member_code ASC
            ";

            $users = $db->query($qry2, $chapter_codes)->getResultArray();
        } else {
            $users = [];
        }

        /* =====================================================
           3. GET ALL MEMBER USER_CODES (MY CHAPTER ONLY)
        ===================================================== */
        $chapterMemberCodes = [];

        if (!empty($chapter_codes)) {

            $placeholders = implode(',', array_fill(0, count($chapter_codes), '?'));

            $memberQry = "
                SELECT DISTINCT user_code
                FROM chapter_members
                WHERE chapter_code IN ($placeholders)
            ";

            $chapterMembers = $db->query($memberQry, $chapter_codes)->getResultArray();

            $chapterMemberCodes = array_column($chapterMembers, 'user_code');
        }

        // Prevent empty IN()
        $memberList = !empty($chapterMemberCodes)
            ? "'" . implode("','", $chapterMemberCodes) . "'"
            : "''";

        // One to One Meetings
        $meetings = $commonModel->exe_query("
            SELECT * FROM one_to_ones
            WHERE from_user IN ($memberList)
               OR to_user IN ($memberList)
        ")->getNumRows();

        // Referrals
        $referralsGiven = $commonModel->exe_query("
            SELECT * FROM referrals
            WHERE from_user IN ($memberList)
        ")->getNumRows();

        $referralsReceived = $commonModel->exe_query("
            SELECT * FROM referrals
            WHERE to_user IN ($memberList)
        ")->getNumRows();

        $connects = $referralsGiven + $referralsReceived;

        // Visitors
        $visitors = $commonModel->exe_query("
            SELECT * FROM visitors
            WHERE user_code IN ($memberList)
        ")->getNumRows();

        // Notifications
        $notifications = $commonModel->exe_query("
            SELECT * FROM notifications
            WHERE user_code IN ($memberList)
        ")->getNumRows();




        return view('member/my_participation_report', [
            'events' => $events,
            'filter' => $filter,
            'meetingsCount' => $meetingsCount,
            'r_g_c' => $r_g_c,
            'r_r_c' => $r_r_c,
            'visitors_c' => $visitors_c,
            'f2f__sc' => $f2f__sc,
            'f2f__rc' => $f2f__rc,
            'notifications_c' => $notifications_c,
            'totalMITBReceived' => $totalMITBReceived,
            'totalMITBGiven' => $totalMITBGiven,
            'dates' => json_encode($datesFormatted),
            'givenData' => json_encode($givenData),
            'receivedData' => json_encode($receivedData),
            'profileStrength' => $profileStrength,
            'chapterMembers_c' => $chapterMembers_c,


        ]);
    }


    function getDateCondition($column)
    {
        $filter = $_GET['filter'] ?? '180';

        if ($filter != 'all') {
            return [
                " AND $column >= DATE_SUB(NOW(), INTERVAL ? DAY)",
                [(int)$filter]
            ];
        }

        return ["", []];
    }
}

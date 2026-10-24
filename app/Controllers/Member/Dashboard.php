<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\UserModel;
use Config\Database;
use App\Models\TyfcbModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }
        $this->checkAutoMeeting();
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

    INNER JOIN users u
        ON u.user_code = cm.user_code

    WHERE mycm.user_code = ?
      AND cm.members_status = '1'
      AND u.status = '1'
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

        // ======================
        // Total MITB Given
        // ======================
        $totalMITBGiven = $db->query("
    SELECT COALESCE(SUM(amount),0) AS total
    FROM tyfcb
    WHERE to_user = ?
    $dateCondition
", array_merge([$user_code], $dateParams))
            ->getRow()->total ?? 0;


        // ======================
        // Total MITB Received
        // ======================
        $totalMITBReceived = $db->query("
    SELECT COALESCE(SUM(amount),0) AS total
    FROM tyfcb
    WHERE from_user = ?
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
        SELECT DISTINCT cm.user_code
        FROM chapter_members cm
        INNER JOIN users u ON u.user_code = cm.user_code
        WHERE cm.chapter_code IN ($placeholders)
          AND u.status = '1'
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


        /* ============================================================
   CHAPTER WEEKLY PERFORMANCE
   ------------------------------------------------------------
   Cycle:
   Chapter meeting day @ 10:00 AM
   ->
   Next meeting day @ 10:00 AM
   ============================================================ */

        date_default_timezone_set('Asia/Kolkata');

        $weeklyPeriod = $this->getChapterWeeklyPeriod($user_code);

        $currentStartDate  = $weeklyPeriod['current_start'];
        $currentEndDate    = $weeklyPeriod['current_end'];

        $previousStartDate = $weeklyPeriod['previous_start'];
        $previousEndDate   = $weeklyPeriod['previous_end'];

        $meetingDay = $weeklyPeriod['meeting_day'];


        /* ============================================================
   CURRENT WEEK
   ============================================================ */

        /*
|--------------------------------------------------------------------------
| Referral Given
|--------------------------------------------------------------------------
*/
        $weekly_ref_given = $db->query("
    SELECT COUNT(*) AS total
    FROM referrals
    WHERE from_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $currentStartDate,
            $currentEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| MITB Received
|--------------------------------------------------------------------------
| IMPORTANT:
| to_user = receiver
|--------------------------------------------------------------------------
*/
        $weekly_mitb_received = $db->query("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM tyfcb
    WHERE to_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $currentStartDate,
            $currentEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| MITB Given
|--------------------------------------------------------------------------
| from_user = giver
|--------------------------------------------------------------------------
*/
        $weekly_mitb_given = $db->query("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM tyfcb
    WHERE from_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $currentStartDate,
            $currentEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| F2F Sent
|--------------------------------------------------------------------------
*/
        $weekly_f2f_send = $db->query("
    SELECT COUNT(*) AS total
    FROM one_to_ones
    WHERE from_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $currentStartDate,
            $currentEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| F2F Received
|--------------------------------------------------------------------------
*/
        $weekly_f2f_receive = $db->query("
    SELECT COUNT(*) AS total
    FROM one_to_ones
    WHERE to_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $currentStartDate,
            $currentEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| Visitors
|--------------------------------------------------------------------------
*/
        $weekly_visitors = $db->query("
    SELECT COUNT(*) AS total
    FROM visitors
    WHERE user_code = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $currentStartDate,
            $currentEndDate
        ])->getRow()->total ?? 0;


        /* ============================================================
   PREVIOUS WEEK
   ============================================================ */

        /*
|--------------------------------------------------------------------------
| Previous Referral Given
|--------------------------------------------------------------------------
*/
        $prev_ref_given = $db->query("
    SELECT COUNT(*) AS total
    FROM referrals
    WHERE from_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $previousStartDate,
            $previousEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| Previous MITB Received
|--------------------------------------------------------------------------
*/
        $prev_mitb_received = $db->query("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM tyfcb
    WHERE to_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $previousStartDate,
            $previousEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| Previous MITB Given
|--------------------------------------------------------------------------
*/
        $prev_mitb_given = $db->query("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM tyfcb
    WHERE from_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $previousStartDate,
            $previousEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| Previous F2F Sent
|--------------------------------------------------------------------------
*/
        $prev_f2f_send = $db->query("
    SELECT COUNT(*) AS total
    FROM one_to_ones
    WHERE from_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $previousStartDate,
            $previousEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| Previous F2F Received
|--------------------------------------------------------------------------
*/
        $prev_f2f_receive = $db->query("
    SELECT COUNT(*) AS total
    FROM one_to_ones
    WHERE to_user = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $previousStartDate,
            $previousEndDate
        ])->getRow()->total ?? 0;


        /*
|--------------------------------------------------------------------------
| Previous Visitors
|--------------------------------------------------------------------------
*/
        $prev_visitors = $db->query("
    SELECT COUNT(*) AS total
    FROM visitors
    WHERE user_code = ?
      AND created_at >= ?
      AND created_at < ?
", [
            $user_code,
            $previousStartDate,
            $previousEndDate
        ])->getRow()->total ?? 0;



        $globalCounts = $db->query("
            SELECT 
                (SELECT COUNT(*) FROM events) AS meetingsCount_c_a,
                (SELECT COUNT(*) FROM users) AS member_c_a,
                (SELECT COUNT(*) FROM referrals) AS r_g_c_a,
                (SELECT COUNT(*) FROM visitors) AS visitors_c_a,
                (SELECT COUNT(*) FROM one_to_ones) AS f2f__sc_a,
                (SELECT COUNT(*) FROM chapters) AS chapters_c_a,
                (SELECT COALESCE(SUM(amount),0) FROM tyfcb) AS totalMITB_a
        ")->getRow();

        $meetingsCount_c_a = $globalCounts->meetingsCount_c_a ?? 0;
        $member_c_a        = $globalCounts->member_c_a ?? 0;
        $r_g_c_a          = $globalCounts->r_g_c_a ?? 0;
        $visitors_c_a      = $globalCounts->visitors_c_a ?? 0;
        $f2f__sc_a         = $globalCounts->f2f__sc_a ?? 0;
        $chapters_c_a      = $globalCounts->chapters_c_a ?? 0;
        $totalMITB_a       = $globalCounts->totalMITB_a ?? 0;


        $inducation = $commonModel->exe_query("
        SELECT *
        FROM users
        WHERE refferd_by = '{$user_code}'
    ");

        $inducationCount = $inducation->getNumRows();




        return view('member/dashboard', [
            'events' => $events,
            'filter' => $filter,
            'meetingsCount' => $meetingsCount,
            'r_g_c' => $r_g_c,
            'r_r_c' => $r_r_c,
            'visitors_c' => $visitors_c,
            'f2f__sc' => $f2f__sc,
            'f2f__rc' => $f2f__rc,
            'inducationCount' => $inducationCount,

            'notifications_c' => $notifications_c,
            'totalMITBReceived' => $totalMITBReceived,
            'totalMITBGiven' => $totalMITBGiven,
            'dates' => json_encode($datesFormatted),
            'givenData' => json_encode($givenData),
            'receivedData' => json_encode($receivedData),
            'profileStrength' => $profileStrength,
            'chapterMembers_c' => $chapterMembers_c,
            'meetingDay' => $meetingDay,
            'currentStartDate' => $currentStartDate,
            'currentEndDate' => $currentEndDate,
            'previousStartDate' => $previousStartDate,
            'previousEndDate' => $previousEndDate,
            'weekly_ref_given' => $weekly_ref_given,
            'weekly_mitb_received' => $weekly_mitb_received,
            'weekly_mitb_given' => $weekly_mitb_given,
            'weekly_f2f_send' => $weekly_f2f_send,
            'weekly_f2f_receive' => $weekly_f2f_receive,
            'weekly_visitors' => $weekly_visitors,
            'prev_ref_given' => $prev_ref_given,
            'prev_mitb_received' => $prev_mitb_received,
            'prev_mitb_given' => $prev_mitb_given,
            'prev_f2f_send' => $prev_f2f_send,
            'prev_f2f_receive' => $prev_f2f_receive,
            'prev_visitors' => $prev_visitors,
            // mychapter 
            'totalMembers'  => count($chapterMemberCodes),
            'mitb'          => $totalMITBReceived + $totalMITBGiven,
            'connects'      => $connects,
            'meetings'      => $meetings,
            'visitors'      => $visitors,
            'notifications' => $notifications,



            // all 
            'meetingsCount_c_a'          => $meetingsCount_c_a,
            'member_c_a'      => $member_c_a,
            'r_g_c_a'      => $r_g_c_a,
            'visitors_c_a'      => $visitors_c_a,
            'f2f__sc_a' => $f2f__sc_a,
            'chapters_c_a' => $chapters_c_a,
            'totalMITB_a' => $totalMITB_a,
        ]);
    }







    /**
     * Get current and previous weekly cycle based on chapter meeting day at 10:00 AM.
     *
     * Example:
     * Meeting day = Thursday
     *
     * Current week:
     * Thursday 10:00 AM -> Next Thursday 10:00 AM
     *
     * Previous week:
     * Previous Thursday 10:00 AM -> Current Thursday 10:00 AM
     */
    private function getChapterWeeklyPeriod($user_code)
    {
        date_default_timezone_set('Asia/Kolkata');

        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | Get user's chapter
    |--------------------------------------------------------------------------
    | First try chapter_members.
    | If user is chapter leader, also check chapters.leader_code.
    */

        $chapter = $db->query("
        SELECT c.chapter_code, c.meeting_day
        FROM chapters c
        LEFT JOIN chapter_members cm
            ON cm.chapter_code = c.chapter_code
            AND cm.user_code = ?
        WHERE cm.user_code = ?
           OR c.leader_code = ?
        AND c.chapter_status = '1'
        ORDER BY
            CASE
                WHEN cm.user_code = ? THEN 0
                WHEN c.leader_code = ? THEN 1
                ELSE 2
            END,
            c.chapters_id DESC
        LIMIT 1
    ", [
            $user_code,
            $user_code,
            $user_code,
            $user_code,
            $user_code
        ])->getRowArray();

        /*
    |--------------------------------------------------------------------------
    | Default meeting day
    |--------------------------------------------------------------------------
    */

        $meetingDay = 'Thursday';

        if (!empty($chapter['meeting_day'])) {
            $meetingDay = trim($chapter['meeting_day']);
        }

        /*
    |--------------------------------------------------------------------------
    | Convert meeting_day to PHP day number
    |--------------------------------------------------------------------------
    |
    | PHP:
    | Sunday    = 0
    | Monday    = 1
    | Tuesday   = 2
    | Wednesday = 3
    | Thursday  = 4
    | Friday    = 5
    | Saturday  = 6
    |
    */

        $dayMap = [
            'sunday'    => 0,
            'monday'    => 1,
            'tuesday'   => 2,
            'wednesday' => 3,
            'thursday'  => 4,
            'friday'    => 5,
            'saturday'  => 6,

            // Numeric support
            '0' => 0,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 0
        ];

        $meetingDayKey = strtolower($meetingDay);

        $meetingDayNumber = $dayMap[$meetingDayKey] ?? 4;

        /*
    |--------------------------------------------------------------------------
    | Current date/time
    |--------------------------------------------------------------------------
    */

        $now = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));

        /*
    |--------------------------------------------------------------------------
    | Find this week's meeting day at 10:00 AM
    |--------------------------------------------------------------------------
    */

        $currentMeeting = clone $now;

        $currentDayNumber = (int) $currentMeeting->format('w');

        $daysDifference = $meetingDayNumber - $currentDayNumber;

        $currentMeeting->modify(
            ($daysDifference >= 0 ? '+' : '') . $daysDifference . ' days'
        );

        $currentMeeting->setTime(10, 0, 0);

        /*
    |--------------------------------------------------------------------------
    | If today's meeting time has already passed,
    | this is the current week's cycle.
    |
    | If today's meeting time has NOT arrived yet,
    | use previous week's meeting as current cycle start.
    |--------------------------------------------------------------------------
    */

        if ($now >= $currentMeeting) {

            $currentStart = clone $currentMeeting;
        } else {

            $currentStart = clone $currentMeeting;
            $currentStart->modify('-7 days');
        }

        /*
    |--------------------------------------------------------------------------
    | Current cycle end
    |--------------------------------------------------------------------------
    */

        $currentEnd = clone $currentStart;
        $currentEnd->modify('+7 days');

        /*
    |--------------------------------------------------------------------------
    | Previous cycle
    |--------------------------------------------------------------------------
    */

        $previousStart = clone $currentStart;
        $previousStart->modify('-7 days');

        $previousEnd = clone $currentStart;

        return [
            'meeting_day' => $meetingDay,

            'current_start' => $currentStart->format('Y-m-d H:i:s'),
            'current_end' => $currentEnd->format('Y-m-d H:i:s'),

            'previous_start' => $previousStart->format('Y-m-d H:i:s'),
            'previous_end' => $previousEnd->format('Y-m-d H:i:s')
        ];
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
    public function search()
    {
        $search = $this->request->getPost('search') ?? $this->request->getGet('search') ?? '';



        $builder = db_connect()->table('chapter_members cm');

        $builder->select("
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
    ");

        $builder->join('users u', 'u.user_code = cm.user_code', 'left');
        $builder->join('chapters c', 'c.chapter_code = cm.chapter_code', 'left');

        if ($search != '') {
            $builder->groupStart()
                ->like('u.name', $search)
                ->orLike('u.email', $search)
                ->orLike('u.phone', $search)
                ->orLike('u.city', $search)
                ->orLike('u.last_name', $search)
                ->orLike('u.whatsapp', $search)
                ->orLike('u.address', $search)
                ->orLike('u.state', $search)
                ->orLike('u.designation', $search)
                ->orLike('u.skills', $search)
                ->orLike('u.services', $search)
                ->orLike('u.products', $search)
                ->orLike('u.bio', $search)
                ->orLike('u.business_name', $search)
                ->orLike('u.industry', $search)
                ->orLike('u.subcategory', $search)
                ->orLike('u.description', $search)
                ->orLike('u.office_address', $search)
                ->orLike('u.service_areas', $search)
                ->groupEnd();
        }

        $builder->where('u.status', '1');

        if ($search != '') {
            $searchEscaped = $db->escapeLikeString($search);
            $builder->orderBy("
                CASE 
                    WHEN u.name LIKE '{$searchEscaped}%' THEN 1
                    WHEN u.name LIKE '%{$searchEscaped}%' THEN 2
                    WHEN u.business_name LIKE '{$searchEscaped}%' THEN 3
                    WHEN u.business_name LIKE '%{$searchEscaped}%' THEN 4
                    WHEN u.industry LIKE '{$searchEscaped}%' THEN 5
                    ELSE 6
                END
            ", 'ASC');
            $builder->orderBy('u.name', 'ASC');
        } else {
            $builder->orderBy('cm.member_code', 'ASC');
        }

        $data['users'] = $builder->get()->getResultArray();
        $data['search'] = $search;


        return view('member/search', $data);
    }

    public function checkAutoMeeting()
    {
        $lastRun = session()->get('auto_meeting_run');

        // Run once every 7 days
        if ($lastRun && strtotime($lastRun . ' +7 days') > time()) {
            return;
        }

        $this->autoGenerateMeetings('cron123');

        session()->set('auto_meeting_run', date('Y-m-d'));
    }


    public function autoGenerateMeetings($key = '')
    {
        if ($key != 'cron123') {
            exit('Unauthorized');
        }

        $user_code = session()->get('user_code');

        if (!$user_code) {
            exit('User not logged in');
        }

        $commonModel = new \App\Models\CommonModel();

        $chapters = $commonModel->exe_query("
        SELECT *
        FROM chapters
        WHERE chapter_status = '1'
    ")->getResultArray();

        $inserted = 0;

        foreach ($chapters as $chapter) {

            // Only chapter leader can generate the meeting
            if (
                strtolower(trim((string)($chapter['leader_code'] ?? ''))) !=
                strtolower(trim((string)($user_code ?? '')))
            ) {
                continue;
            }

            /*
        |--------------------------------------------------------------------------
        | NEXT WEEKLY MEETING
        |--------------------------------------------------------------------------
        | Meeting time = 7:30 AM
        | If today is Thursday, next meeting = next Thursday 7:30 AM
        |--------------------------------------------------------------------------
        */

            $now = new \DateTime();

            // Next Thursday
            $nextMeeting = new \DateTime('next Thursday');

            // Set meeting time to 7:30 AM
            $nextMeeting->setTime(7, 30, 0);

            /*
        |--------------------------------------------------------------------------
        | Check if next meeting already exists
        |--------------------------------------------------------------------------
        */

            $nextMeetingStart = $nextMeeting->format('Y-m-d H:i:s');

            $nextMeetingEnd = (clone $nextMeeting)
                ->modify('+1 minute')
                ->format('Y-m-d H:i:s');

            $exists = $commonModel->db->table('events')
                ->where('chapter_code', $chapter['chapter_code'])
                ->where('date >=', $nextMeetingStart)
                ->where('date <', $nextMeetingEnd)
                ->get()
                ->getRow();

            if ($exists) {
                continue;
            }

            /*
        |--------------------------------------------------------------------------
        | Create next meeting
        |--------------------------------------------------------------------------
        */

            $event_code = $commonModel->get_serial3(
                'events',
                'EVE',
                'event_code'
            );

            $insert = [
                'event_code'   => $event_code,
                'user_code'    => $user_code,
                'chapter_code' => $chapter['chapter_code'],
                'title'        => 'Weekly Meeting',
                'description'  => 'Auto Generated',
                'date'         => $nextMeetingStart,
                'time'         => '07:30 AM',
                'location'     => $chapter['location'] ?? '',
                'note'         => 'Auto Generated',
                'created_by'   => $user_code,
                'updated_on'   => date('Y-m-d H:i:s'),
            ];

            $commonModel->insert_data('events', $insert);

            $inserted++;
        }

        if ($inserted > 0) {
            session()->setFlashdata(
                'msg',
                "$inserted next weekly meeting(s) created successfully"
            );
        } else {
            session()->setFlashdata(
                'msg',
                'No Meeting Created'
            );
        }

        return redirect()->to('/member/dashboard');
    }
}

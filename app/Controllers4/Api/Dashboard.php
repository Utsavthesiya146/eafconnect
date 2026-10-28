<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Dashboard Controller
 *
 * GET /api/v1/dashboard
 *
 * Returns all data needed for the member dashboard:
 * - Performance counters (MITB, F2F, referrals, visitors)
 * - MITB chart data (given vs received by date)
 * - Weekly performance (current + previous week)
 * - Profile completion strength
 * - Chapter info
 */
class Dashboard extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $db     = \Config\Database::connect();
        $filter = $this->request->getGet('filter') ?? '180';

        // ── Date condition ──────────────────────────────────────
        $dateCondition = '';
        $dateParams    = [];

        if ($filter !== 'all') {
            $dateCondition = ' AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)';
            $dateParams[]  = (int) $filter;
        }

        // ── Counters ─────────────────────────────────────────────
        $meetingsCount = $db->query(
            "SELECT COUNT(*) total FROM one_to_ones WHERE from_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $r_g_c = $db->query(
            "SELECT COUNT(*) total FROM referrals WHERE from_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $r_r_c = $db->query(
            "SELECT COUNT(*) total FROM referrals WHERE to_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $visitors_c = $db->query(
            "SELECT COUNT(*) total FROM visitors WHERE user_code=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $f2f_sc = $db->query(
            "SELECT COUNT(*) total FROM one_to_ones WHERE from_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $f2f_rc = $db->query(
            "SELECT COUNT(*) total FROM one_to_ones WHERE to_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $notifications_c = $db->query(
            "SELECT COUNT(*) total FROM notifications WHERE user_code=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $totalMITBGiven = $db->query(
            "SELECT COALESCE(SUM(amount),0) AS total FROM tyfcb WHERE to_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        $totalMITBReceived = $db->query(
            "SELECT COALESCE(SUM(amount),0) AS total FROM tyfcb WHERE from_user=? $dateCondition",
            array_merge([$user_code], $dateParams)
        )->getRow()->total ?? 0;

        // ── MITB Chart Data ──────────────────────────────────────
        $givenQuery = $db->query(
            "SELECT DATE(created_at) mitb_date, SUM(amount) total_given
             FROM tyfcb WHERE to_user=? $dateCondition
             GROUP BY DATE(created_at) ORDER BY mitb_date ASC",
            array_merge([$user_code], $dateParams)
        )->getResultArray();

        $receivedQuery = $db->query(
            "SELECT DATE(created_at) mitb_date, SUM(amount) total_received
             FROM tyfcb WHERE from_user=? $dateCondition
             GROUP BY DATE(created_at) ORDER BY mitb_date ASC",
            array_merge([$user_code], $dateParams)
        )->getResultArray();

        $dates         = [];
        $datesFormatted = [];
        $givenData     = [];
        $receivedData  = [];

        foreach ($givenQuery as $row)    $dates[$row['mitb_date']] = true;
        foreach ($receivedQuery as $row) $dates[$row['mitb_date']] = true;
        ksort($dates);

        foreach (array_keys($dates) as $date) {
            $datesFormatted[] = date('d M', strtotime($date));
            $gAmt = 0;
            foreach ($givenQuery as $g)
                if ($g['mitb_date'] == $date) $gAmt = (float)$g['total_given'];
            $rAmt = 0;
            foreach ($receivedQuery as $r)
                if ($r['mitb_date'] == $date) $rAmt = (float)$r['total_received'];
            $givenData[]    = $gAmt;
            $receivedData[] = $rAmt;
        }

        // ── Profile Strength ─────────────────────────────────────
        $user = $db->table('users')
            ->where('user_code', $user_code)
            ->get()->getRowArray();

        $score    = 0;
        $filled   = fn($f) => !empty($user[$f]) && $user[$f] != '0';

        if ($filled('name'))          $score += 4;
        if ($filled('email'))         $score += 4;
        if ($filled('phone'))         $score += 4;
        if (($user['profile_photo'] ?? 'empty.png') !== 'empty.png') $score += 3;
        if ($filled('gender'))        $score += 3;
        if ($filled('dob'))           $score += 3;
        if ($filled('bio'))           $score += 4;
        if ($filled('address'))       $score += 2;
        if ($filled('city'))          $score += 2;
        if ($filled('state'))         $score += 2;
        if ($filled('country'))       $score += 2;
        if ($filled('pincode'))       $score += 2;
        if ($filled('designation'))   $score += 3;
        if ($filled('exp_years'))     $score += 3;
        if ($filled('skills'))        $score += 4;
        if ($filled('business_name')) $score += 3;
        if ($filled('industry'))      $score += 3;
        if ($filled('description'))   $score += 4;
        if ($filled('website'))       $score += 5;
        if ($filled('linkedin'))      $score += 5;
        if ($filled('business_logo')) $score += 5;
        if ($filled('business_card')) $score += 5;
        if ($filled('services'))      $score += 4;
        if ($filled('products'))      $score += 3;
        if ($filled('languages'))     $score += 4;
        if ($filled('hobbies'))       $score += 4;
        if (($user['verified'] ?? '0') == '1') $score += 10;

        $profileStrength = min(100, round($score));

        // ── Weekly Performance ───────────────────────────────────
        date_default_timezone_set('Asia/Kolkata');
        $now       = date('Y-m-d H:i:s');
        $weekStart = date('Y-m-d H:i:s');
        $weekEnd   = date('Y-m-d H:i:s', strtotime('+15 days'));

        $weekly = [
            'ref_given'     => $db->query("SELECT COUNT(*) AS total FROM referrals WHERE from_user=? AND created_at>=? AND created_at<?", [$user_code, $weekStart, $weekEnd])->getRow()->total ?? 0,
            'mitb_received' => $db->query("SELECT COALESCE(SUM(amount),0) AS total FROM tyfcb WHERE to_user=? AND created_at>=? AND created_at<?", [$user_code, $weekStart, $weekEnd])->getRow()->total ?? 0,
            'mitb_given'    => $db->query("SELECT COALESCE(SUM(amount),0) AS total FROM tyfcb WHERE from_user=? AND created_at>=? AND created_at<?", [$user_code, $weekStart, $weekEnd])->getRow()->total ?? 0,
            'f2f_send'      => $db->query("SELECT COUNT(*) AS total FROM one_to_ones WHERE from_user=? AND created_at>=? AND created_at<?", [$user_code, $weekStart, $weekEnd])->getRow()->total ?? 0,
            'f2f_receive'   => $db->query("SELECT COUNT(*) AS total FROM one_to_ones WHERE to_user=? AND created_at>=? AND created_at<?", [$user_code, $weekStart, $weekEnd])->getRow()->total ?? 0,
            'visitors'      => $db->query("SELECT COUNT(*) AS total FROM visitors WHERE user_code=? AND created_at>=? AND created_at<?", [$user_code, $weekStart, $weekEnd])->getRow()->total ?? 0,
        ];

        // ── Induction count ──────────────────────────────────────
        $inducationCount = $db->table('users')
            ->where('refferd_by', $user_code)
            ->countAllResults();

        // ── Chapter info ─────────────────────────────────────────
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()->getRow();

        $chapterInfo = null;
        if ($chapterMember) {
            $chapter = $db->table('chapters')
                ->where('chapter_code', $chapterMember->chapter_code)
                ->get()->getRow();

            $memberCount = $db->table('chapter_members')
                ->where('chapter_code', $chapterMember->chapter_code)
                ->where('members_status', '1')
                ->countAllResults();

            $chapterInfo = [
                'chapter_code' => $chapterMember->chapter_code,
                'chapter_name' => $chapter->chapter_name ?? '',
                'chapter_role' => $chapterMember->chapter_role ?? '',
                'member_count' => $memberCount,
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'filter'           => $filter,
                'meetings_count'   => (int) $meetingsCount,
                'referrals_given'  => (int) $r_g_c,
                'referrals_recv'   => (int) $r_r_c,
                'visitors'         => (int) $visitors_c,
                'f2f_sent'         => (int) $f2f_sc,
                'f2f_received'     => (int) $f2f_rc,
                'notifications'    => (int) $notifications_c,
                'mitb_given'       => (float) $totalMITBGiven,
                'mitb_received'    => (float) $totalMITBReceived,
                'induction_count'  => (int) $inducationCount,
                'profile_strength' => (int) $profileStrength,
                'chart'            => [
                    'dates'    => $datesFormatted,
                    'given'    => $givenData,
                    'received' => $receivedData,
                ],
                'weekly'   => $weekly,
                'chapter'  => $chapterInfo,
            ],
        ]);
    }
}

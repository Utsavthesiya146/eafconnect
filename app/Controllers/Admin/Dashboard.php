<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $db = \Config\Database::connect();

        /* ======================================================
       FILTER
    ====================================================== */
        $filter = $this->request->getGet('filter') ?? '180';

        $where = " WHERE 1=1 ";
        $params = [];

        if ($filter != 'all') {
            $where .= " AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $params[] = (int)$filter;
        }


        $member_c = $db->query("SELECT COUNT(*) AS total FROM users as u inner join chapter_members cm ON u.user_code=cm.user_code WHERE u.status='1'")
            ->getRow()->total ?? 0;
        /* ======================================================
       COUNTS
    ====================================================== */
        $meetingsCount = $db->query("SELECT COUNT(*) total FROM events $where", $params)->getRow()->total ?? 0;
        // $member_c      = $db->query("SELECT COUNT(*) total FROM users $where", $params)->getRow()->total ?? 0;
        $r_g_c         = $db->query("SELECT COUNT(*) total FROM referrals $where", $params)->getRow()->total ?? 0;
        $visitors_c    = $db->query("SELECT COUNT(*) total FROM visitors $where", $params)->getRow()->total ?? 0;
        $f2f__sc       = $db->query("SELECT COUNT(*) total FROM one_to_ones $where", $params)->getRow()->total ?? 0;
        $visitors_c    = $db->query("SELECT COUNT(*) total FROM visitors $where", $params)->getRow()->total ?? 0;
        $chapters_c    = $db->query("SELECT COUNT(*) total FROM chapters $where", $params)->getRow()->total ?? 0;


        $r_r_c = $db->query("SELECT COUNT(*) AS total FROM referrals $where", $params)->getRow()->total ?? 0;
        $f2f__rc = $db->query("SELECT COUNT(*) AS total FROM one_to_ones $where", $params)->getRow()->total ?? 0;
        $notifications_c = $db->query("SELECT COUNT(*) AS total FROM notifications $where", $params)->getRow()->total ?? 0;

        /* ======================================================
       MITB TOTAL
    ====================================================== */
        $totalMITB = $db->table('tyfcb')
            ->select('COALESCE(SUM(amount), 0) AS total', false)
            ->get()
            ->getRow()
            ->total ?? 0;

        $totalMITB = (float) $totalMITB;

        $averageMemberValue = $member_c > 0
            ? round($totalMITB / $member_c)
            : 0;


        /* ======================================================
       DAY-WISE QUERIES
    ====================================================== */
        $tables = [
            'visitors'     => 'visitors',
            'referrals'    => 'referrals',
            'meetings'     => 'events',
            'f2f'          => 'one_to_ones',
            'members'      => 'users',
            'mitb'         => 'tyfcb'
        ];

        $results = [];

        foreach ($tables as $key => $table) {

            $select = ($key == 'mitb')
                ? "SUM(amount) total"
                : "COUNT(*) total";

            $results[$key] = $db->query("
            SELECT DATE(created_at) chart_date, $select
            FROM $table
            $where
            GROUP BY DATE(created_at)
            ORDER BY chart_date ASC
        ", $params)->getResultArray();
        }

        /* ======================================================
       MERGE ALL DATES
    ====================================================== */
        $dates = [];

        foreach ($results as $dataset) {
            foreach ($dataset as $row) {
                $dates[$row['chart_date']] = true;
            }
        }

        ksort($dates);

        /* ======================================================
       BUILD FINAL ARRAYS
    ====================================================== */
        $labels = [];

        $visitorData = [];
        $referralData = [];
        $meetingData = [];
        $f2fData = [];
        $memberData = [];
        $mitbData = [];

        foreach (array_keys($dates) as $date) {

            $labels[] = date('d M', strtotime($date));

            $visitorData[] = $this->findValue($results['visitors'], $date);
            $referralData[] = $this->findValue($results['referrals'], $date);
            $meetingData[] = $this->findValue($results['meetings'], $date);
            $f2fData[] = $this->findValue($results['f2f'], $date);
            $memberData[] = $this->findValue($results['members'], $date);
            $mitbData[] = $this->findValue($results['mitb'], $date);
        }

        /* ======================================================
       CHAPTER DATA
    ====================================================== */


        $eventModel = new \App\Models\EventModel();

        $db = \Config\Database::connect();

        /* ===================================================
   EVENTS WITH CHAPTER MEMBER COUNT
=================================================== */
        $events = $eventModel
            ->select("
        events.*,
        chapters.chapter_name,
        COUNT(DISTINCT chapter_members.user_code) AS reg_count
    ")
            ->join('chapters', 'chapters.chapter_code = events.chapter_code', 'left')
            ->join(
                'chapter_members',
                "chapter_members.chapter_code = events.chapter_code 
         AND chapter_members.members_status = '1'",
                'left'
            )
            ->where('events.status', '1')
            ->groupBy('events.event_id')
            ->orderBy('events.date', 'DESC')
            ->orderBy('events.event_id', 'DESC')
            ->findAll();


        /* ===================================================
   CHAPTER SUMMARY
=================================================== */
        $chapters = $db->query("
    SELECT 
        c.chapter_name,
        COUNT(DISTINCT cm.user_code) members,
        COUNT(DISTINCT r.referral_id) referrals,
        COALESCE(SUM(t.amount),0) mitb_total
    FROM chapters c
    LEFT JOIN chapter_members cm 
        ON cm.chapter_code = c.chapter_code 
        AND cm.members_status='1'
    LEFT JOIN referrals r 
        ON r.from_user = cm.user_code
    LEFT JOIN tyfcb t 
        ON t.from_user = cm.user_code
    WHERE c.chapter_status='1'
    GROUP BY c.chapter_code
    ORDER BY c.chapter_name ASC
")->getResultArray();

        return view('admin/dashboard', [

            'filter' => $filter,
            'events' => $events,

            'meetingsCount' => $meetingsCount,
            'member_c'      => $member_c,
            'r_g_c'         => $r_g_c,
            'visitors_c'    => $visitors_c,
            'f2f__sc'       => $f2f__sc,
            'totalMITB'     => $totalMITB,
            'averageMemberValue'     => $averageMemberValue,


            'chapters_c'     => $chapters_c,
            'notifications_c'    => $notifications_c,
            'f2f__rc'       => $f2f__rc,
            'r_r_c'       => $r_r_c,
            /* Charts */
            'labels'        => json_encode($labels),
            'visitorData'   => json_encode($visitorData),
            'referralData'  => json_encode($referralData),
            'meetingData'   => json_encode($meetingData),
            'f2fData'       => json_encode($f2fData),
            'memberData'    => json_encode($memberData),
            'mitbData' => json_encode($mitbData),

            // old chart compatibility
            'dates'        => json_encode($labels),
            'givenData'    => json_encode($mitbData),
            'receivedData' => json_encode($mitbData),
            /* Chapter Chart */
            'chapterLabels'    => json_encode(array_column($chapters, 'chapter_name')),
            'chapterMembers'   => json_encode(array_column($chapters, 'members')),
            'chapterReferrals' => json_encode(array_column($chapters, 'referrals')),
            'chapterMITB'      => json_encode(array_column($chapters, 'mitb_total')),
        ]);
    }

    /* ======================================================
   HELPER
====================================================== */
    private function findValue($array, $date)
    {
        foreach ($array as $row) {
            if ($row['chart_date'] == $date) {
                return (float)$row['total'];
            }
        }
        return 0;
    }


    public function mergeUserData()
    {

        $commonModel = new CommonModel();
        $result = $commonModel->merge_user_profile_business_data();

        return $this->response->setJSON($result);
    }
}

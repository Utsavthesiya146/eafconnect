<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\CommonModel;
use App\Models\TyfcbModel;

class Mychapter extends BaseController
{

    public function index()
    {
        /*
    |--------------------------------------------------------------------------
    | CHECK LOGIN
    |--------------------------------------------------------------------------
    */
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $session  = session();
        $user_code = $session->get('user_code');

        if (!$user_code) {
            return redirect()->to('/login');
        }


        $db          = Database::connect();
        $commonModel = new CommonModel();
        $mitbModel   = new TyfcbModel();


        /* =====================================================
       1. GET MY CHAPTERS
       Leader OR Member
    ===================================================== */

        /* =====================================================
   1. GET MY CHAPTERS
   Leader OR Member
===================================================== */

        $qry = "
    SELECT
        c.*,

        /* CHAPTER LEADER DETAILS */
        leader.name AS leader_name,
        leader.profile_photo AS leader_photo,

        /* ACTIVE MEMBER COUNT */
        (
            SELECT COUNT(DISTINCT cm.user_code)
            FROM chapter_members cm
            INNER JOIN users u
                ON u.user_code = cm.user_code
            WHERE cm.chapter_code = c.chapter_code
              AND cm.members_status = '1'
              AND u.status = '1'
        ) AS member_count

    FROM chapters c

    /* GET LEADER FROM USERS TABLE */
    LEFT JOIN users leader
        ON leader.user_code = c.leader_code

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

    ORDER BY c.chapters_id DESC
";

        $chapters = $db
            ->query($qry, [$user_code, $user_code])
            ->getResultArray();


        /* =====================================================
       2. GET CHAPTER CODES
    ===================================================== */

        $chapter_codes = array_column(
            $chapters,
            'chapter_code'
        );


        /* =====================================================
       3. GET CHAPTER MEMBERS
       ONLY ACTIVE USERS + ACTIVE MEMBERS
    ===================================================== */

        if (!empty($chapter_codes)) {

            $placeholders = implode(
                ',',
                array_fill(
                    0,
                    count($chapter_codes),
                    '?'
                )
            );

            $qry2 = "
            SELECT

                cm.*,

                u.name,
                u.email,
                u.phone,
                u.profile_photo,
                u.city,
                u.industry,
                u.member_role,

                u.status AS user_status,

                c.chapter_name,
                c.chapter_code,

                cm.joined_date AS join_date,
                cm.members_status AS member_status

            FROM chapter_members cm

            INNER JOIN users u
                ON cm.user_code = u.user_code

            INNER JOIN chapters c
                ON cm.chapter_code = c.chapter_code

            WHERE cm.chapter_code IN ($placeholders)

              AND cm.members_status = '1'

              AND u.status = '1'

            ORDER BY cm.member_code ASC
        ";

            $users = $db
                ->query($qry2, $chapter_codes)
                ->getResultArray();
        } else {

            $users = [];
        }


        /* =====================================================
       4. GET ACTIVE MEMBER USER CODES
       MY CHAPTERS ONLY
    ===================================================== */

        $chapterMemberCodes = [];


        if (!empty($chapter_codes)) {

            $placeholders = implode(
                ',',
                array_fill(
                    0,
                    count($chapter_codes),
                    '?'
                )
            );

            $memberQry = "
            SELECT DISTINCT
                cm.user_code

            FROM chapter_members cm

            INNER JOIN users u
                ON u.user_code = cm.user_code

            WHERE cm.chapter_code IN ($placeholders)

              AND cm.members_status = '1'

              AND u.status = '1'
        ";

            $chapterMembers = $db
                ->query(
                    $memberQry,
                    $chapter_codes
                )
                ->getResultArray();

            $chapterMemberCodes = array_column(
                $chapterMembers,
                'user_code'
            );
        }


        /* =====================================================
       5. MEMBER LIST FOR IN()
    ===================================================== */

        if (!empty($chapterMemberCodes)) {

            $escapedCodes = array_map(
                fn($code) => $db->escape($code),
                $chapterMemberCodes
            );

            $memberList = implode(
                ',',
                $escapedCodes
            );
        } else {

            /*
        | Safe empty IN()
        */
            $memberList = "''";
        }


        /* =====================================================
       6. COUNTS
       ACTIVE CHAPTER MEMBERS ONLY
    ===================================================== */

        /*
    |--------------------------------------------------------------------------
    | ONE TO ONE MEETINGS
    |--------------------------------------------------------------------------
    */

        $meetings = $commonModel->exe_query("
        SELECT *
        FROM one_to_ones

        WHERE from_user IN ($memberList)

           OR to_user IN ($memberList)

    ")->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | REFERRALS GIVEN
    |--------------------------------------------------------------------------
    */

        $referralsGiven = $commonModel->exe_query("
        SELECT *
        FROM referrals

        WHERE from_user IN ($memberList)

    ")->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | REFERRALS RECEIVED
    |--------------------------------------------------------------------------
    */

        $referralsReceived = $commonModel->exe_query("
        SELECT *
        FROM referrals

        WHERE to_user IN ($memberList)

    ")->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | TOTAL CONNECTS
    |--------------------------------------------------------------------------
    */

        $connects =
            $referralsGiven +
            $referralsReceived;


        /*
    |--------------------------------------------------------------------------
    | VISITORS
    |--------------------------------------------------------------------------
    */

        $visitors = $commonModel->exe_query("
        SELECT *
        FROM visitors

        WHERE user_code IN ($memberList)

    ")->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

        $notifications = $commonModel->exe_query("
        SELECT *
        FROM notifications

        WHERE user_code IN ($memberList)

    ")->getNumRows();


        /* =====================================================
       7. MITB TOTAL
    ===================================================== */

        $totalMITBReceived = 0;
        $totalMITBGiven    = 0;


        if (!empty($chapterMemberCodes)) {

            /*
        |--------------------------------------------------------------------------
        | MITB RECEIVED
        |--------------------------------------------------------------------------
        */

            $mitbReceived = $mitbModel
                ->selectSum('amount')
                ->whereIn(
                    'from_user',
                    $chapterMemberCodes
                )
                ->first();

            $totalMITBReceived =
                $mitbReceived['amount'] ?? 0;


            /*
        |--------------------------------------------------------------------------
        | MITB GIVEN
        |--------------------------------------------------------------------------
        */

            $mitbGiven = $mitbModel
                ->selectSum('amount')
                ->whereIn(
                    'to_user',
                    $chapterMemberCodes
                )
                ->first();

            $totalMITBGiven =
                $mitbGiven['amount'] ?? 0;
        }


        /* =====================================================
       8. ACTIVE USERS DROPDOWN
    ===================================================== */

        $member = $commonModel->exe_query("
        SELECT
            u.user_code,
            u.name,
            u.email,
            u.phone,
            u.profile_photo,
            u.city,
            u.industry,
            u.member_role

        FROM users u

        WHERE u.status = '1'

        ORDER BY u.name ASC

    ")->getResultArray();


        /* =====================================================
       9. LOAD VIEW
    ===================================================== */

        return view('member/mychapter', [

            'chapters' => $chapters,

            'users' => $users,

            'member' => $member,

            'totalMembers' =>
            count($chapterMemberCodes),

            'mitb' =>
            $totalMITBGiven,

            'connects' =>
            $connects,

            'meetings' =>
            $meetings,

            'visitors' =>
            $visitors,

            'notifications' =>
            $notifications
        ]);
    }
}

<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\CommonModel;
use App\Models\TyfcbModel;

class Topperformer extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $user_code = session()->get('user_code');

        if (empty($user_code)) {
            return redirect()->to('/member_login');
        }

        $db = \Config\Database::connect();

        try {

            /* =========================================================
           GET CHAPTER CODE
        ========================================================= */

            $chapter_code = '';

            $chapterRow = $db->table('chapter_members')
                ->select('chapter_code')
                ->where('user_code', $user_code)
                ->where('members_status', '1')
                ->get()
                ->getRowArray();

            if (!empty($chapterRow)) {
                $chapter_code = $chapterRow['chapter_code'];
            }

            /* =========================================================
           GET CHAPTER MEMBER CODES
        ========================================================= */

            $chapterMemberCodes = [];

            if (!empty($chapter_code)) {

                $chapterMembers = $db->table('chapter_members')
                    ->select('user_code')
                    ->where('chapter_code', $chapter_code)
                    ->where('members_status', '1')
                    ->get()
                    ->getResultArray();

                $chapterMemberCodes = array_column($chapterMembers, 'user_code');
            }

            $memberList = "'0'";

            if (!empty($chapterMemberCodes)) {
                $memberList = "'" . implode("','", $chapterMemberCodes) . "'";
            }

            /* =========================================================
           OVERALL BUSINESS TOP 5
        ========================================================= */

            $overallBusinessQry = "
            SELECT
                u.user_code,
                u.name,
                u.profile_photo,
                u.industry,
                IFNULL(SUM(m.amount),0) AS business_amount
            FROM users u
            LEFT JOIN tyfcb m
                ON m.to_user = u.user_code
            WHERE u.status='1'
            GROUP BY u.user_code, u.name, u.profile_photo, u.industry
            ORDER BY business_amount DESC
            LIMIT 5
        ";

            $overallBusinessTop = $db->query($overallBusinessQry)->getResultArray();



  

            /* =========================================================
           OVERALL REFERRAL TOP 5
        ========================================================= */

            $overallReferralQry = "
            SELECT
                u.user_code,
                u.name,
                u.profile_photo,
                u.industry,
                COUNT(r.referral_id) AS referrals
            FROM users u
            LEFT JOIN referrals r
                ON r.from_user = u.user_code
            WHERE u.status='1'
            GROUP BY u.user_code, u.name, u.profile_photo, u.industry
            ORDER BY referrals DESC
            LIMIT 5
        ";

            $overallReferralTop = $db->query($overallReferralQry)->getResultArray();

            /* =========================================================
           CHAPTER BUSINESS TOP 5
        ========================================================= */

            $chapterBusinessTop = [];

            if (!empty($chapterMemberCodes)) {

                $chapterBusinessQry = "
                SELECT
                    u.user_code,
                    u.name,
                    u.profile_photo,
                    u.industry,
                    IFNULL(SUM(m.amount),0) AS business_amount
                FROM users u
                LEFT JOIN tyfcb m
                    ON m.to_user = u.user_code
                WHERE u.user_code IN ($memberList)
                AND u.status='1'
                GROUP BY u.user_code, u.name, u.profile_photo, u.industry
                ORDER BY business_amount DESC
                LIMIT 5
            ";

                $chapterBusinessTop = $db->query($chapterBusinessQry)->getResultArray();

             

                $businessTotal = array_sum(array_column($chapterBusinessTop, 'business_amount'));

                if ($businessTotal <= 0) {

                    $chapterBusinessTop = $db->table('users')
                        ->select('user_code,name,profile_photo,industry')
                        ->whereIn('user_code', $chapterMemberCodes)
                        ->where('status', '1')
                        ->orderBy('user_id', 'ASC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();

                    foreach ($chapterBusinessTop as &$row) {
                        $row['business_amount'] = 0;
                    }
                }
            }

            /* =========================================================
           CHAPTER REFERRAL TOP 5
        ========================================================= */

            $chapterReferralTop = [];

            if (!empty($chapterMemberCodes)) {

                $chapterReferralQry = "
                SELECT
                    u.user_code,
                    u.name,
                    u.profile_photo,
                    u.industry,
                    COUNT(r.referral_id) AS referrals
                FROM users u
                LEFT JOIN referrals r
                    ON r.from_user = u.user_code
                WHERE u.user_code IN ($memberList)
                AND u.status='1'
                GROUP BY u.user_code, u.name, u.profile_photo, u.industry
                ORDER BY referrals DESC
                LIMIT 5
            ";

                $chapterReferralTop = $db->query($chapterReferralQry)->getResultArray();

                $referralTotal = array_sum(array_column($chapterReferralTop, 'referrals'));

                if ($referralTotal <= 0) {

                    $chapterReferralTop = $db->table('users')
                        ->select('user_code,name,profile_photo,industry')
                        ->whereIn('user_code', $chapterMemberCodes)
                        ->where('status', '1')
                        ->orderBy('user_id', 'ASC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();

                    foreach ($chapterReferralTop as &$row) {
                        $row['referrals'] = 0;
                    }
                }
            }

            return view('member/topperformer', [
                'overallBusinessTop' => $overallBusinessTop,
                'overallReferralTop' => $overallReferralTop,
                'chapterBusinessTop' => $chapterBusinessTop,
                'chapterReferralTop' => $chapterReferralTop,
                'chapter_code' => $chapter_code,
                'chapterMemberCount' => count($chapterMemberCodes),
            ]);
        } catch (\Throwable $e) {

            echo '<pre>';
            echo 'ERROR: ' . $e->getMessage();
            echo "\n\nFILE: " . $e->getFile();
            echo "\nLINE: " . $e->getLine();
            exit;
        }
    }
}

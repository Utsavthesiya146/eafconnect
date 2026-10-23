<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Reports Controller
 *
 * GET /api/v1/reports/weekly        → weekly performance summary
 * GET /api/v1/reports/participation → chapter participation summary
 */
class Reports extends BaseController
{
    public function weekly()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        date_default_timezone_set('Asia/Kolkata');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd   = date('Y-m-d', strtotime('sunday this week'));

        $refGiven = $db->query(
            "SELECT COUNT(*) total FROM referrals WHERE from_user=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        $refReceived = $db->query(
            "SELECT COUNT(*) total FROM referrals WHERE to_user=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        $mitbGiven = $db->query(
            "SELECT COALESCE(SUM(amount),0) total FROM tyfcb WHERE from_user=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        $mitbReceived = $db->query(
            "SELECT COALESCE(SUM(amount),0) total FROM tyfcb WHERE to_user=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        $f2fSent = $db->query(
            "SELECT COUNT(*) total FROM one_to_ones WHERE from_user=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        $f2fReceived = $db->query(
            "SELECT COUNT(*) total FROM one_to_ones WHERE to_user=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        $visitors = $db->query(
            "SELECT COUNT(*) total FROM visitors WHERE user_code=? AND DATE(created_at) BETWEEN ? AND ?",
            [$user_code, $weekStart, $weekEnd]
        )->getRow()->total ?? 0;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'week_start'     => $weekStart,
                'week_end'       => $weekEnd,
                'ref_given'      => (int) $refGiven,
                'ref_received'   => (int) $refReceived,
                'mitb_given'     => (float) $mitbGiven,
                'mitb_received'  => (float) $mitbReceived,
                'f2f_sent'       => (int) $f2fSent,
                'f2f_received'   => (int) $f2fReceived,
                'visitors'       => (int) $visitors,
            ],
        ]);
    }

    public function participation()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        // Get user's chapter
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()
            ->getRow();

        if (!$chapterMember) {
            return $this->response->setJSON([
                'status'  => 'success',
                'data'    => null,
                'message' => 'Not a member of any chapter.',
            ]);
        }

        $chapter_code = $chapterMember->chapter_code;

        // Chapter-level aggregated stats
        $totalRefGiven = $db->query(
            "SELECT COUNT(*) total FROM referrals r
             INNER JOIN chapter_members cm ON cm.user_code = r.from_user
             WHERE cm.chapter_code = ? AND cm.members_status='1'",
            [$chapter_code]
        )->getRow()->total ?? 0;

        $totalMitb = $db->query(
            "SELECT COALESCE(SUM(t.amount),0) total FROM tyfcb t
             INNER JOIN chapter_members cm ON cm.user_code = t.from_user
             WHERE cm.chapter_code = ? AND cm.members_status='1'",
            [$chapter_code]
        )->getRow()->total ?? 0;

        $totalF2f = $db->query(
            "SELECT COUNT(*) total FROM one_to_ones o
             INNER JOIN chapter_members cm ON cm.user_code = o.from_user
             WHERE cm.chapter_code = ? AND cm.members_status='1'",
            [$chapter_code]
        )->getRow()->total ?? 0;

        $memberCount = $db->table('chapter_members')
            ->where('chapter_code', $chapter_code)
            ->where('members_status', '1')
            ->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'chapter_code'  => $chapter_code,
                'member_count'  => $memberCount,
                'total_refs'    => (int) $totalRefGiven,
                'total_mitb'    => (float) $totalMitb,
                'total_f2f'     => (int) $totalF2f,
            ],
        ]);
    }
}

<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Chapter Controller
 *
 * GET /api/v1/chapter          → my chapter info + members list
 * GET /api/v1/chapter/members  → full chapter member list
 */
class Chapter extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();
        $baseUrl   = rtrim(base_url(), '/');

        // Get user's chapter membership
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()->getRow();

        if (!$chapterMember) {
            return $this->response->setJSON([
                'status'  => 'success',
                'data'    => null,
                'message' => 'Not a member of any chapter.',
            ]);
        }

        $chapter_code = $chapterMember->chapter_code;

        // Get chapter details
        $chapter = $db->table('chapters')
            ->where('chapter_code', $chapter_code)
            ->get()->getRowArray();

        // Get all members
        $members = $db->query("
            SELECT cm.*, cm.chapter_role AS member_role,
                   u.name, u.email, u.phone, u.business_name, u.industry,
                   u.designation,
                   CASE WHEN u.profile_photo != 'empty.png' AND u.profile_photo != ''
                        THEN CONCAT(?, '/uploads/profile/', u.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS photo_url
            FROM chapter_members cm
            INNER JOIN users u ON u.user_code = cm.user_code
            WHERE cm.chapter_code = ?
              AND cm.members_status = '1'
              AND u.status = '1'
            ORDER BY u.name ASC
        ", [$baseUrl, $baseUrl, $chapter_code])->getResultArray();

        // Chapter stats
        $memberCount = count($members);
        $totalMitb   = $db->query(
            "SELECT COALESCE(SUM(amount),0) total FROM tyfcb WHERE from_user IN (SELECT user_code FROM chapter_members WHERE chapter_code=?) OR to_user IN (SELECT user_code FROM chapter_members WHERE chapter_code=?)",
            [$chapter_code, $chapter_code]
        )->getRow()->total ?? 0;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'chapter'          => $chapter,
                'my_role'          => $chapterMember->chapter_role ?? 'member',
                'members'          => $members,
                'member_count'     => $memberCount,
                'total_mitb'       => (float) $totalMitb,
            ],
        ]);
    }
}

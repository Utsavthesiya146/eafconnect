<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Members Controller
 *
 * GET /api/v1/members/search?q=... → search members across the platform
 */
class Members extends BaseController
{
    public function search()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $query     = trim((string) $this->request->getGet('q'));

        if (strlen($query) < 2) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => [],
            ]);
        }

        $db      = \Config\Database::connect();
        $baseUrl = rtrim(base_url(), '/');

        $members = $db->query("
            SELECT u.user_code, u.name, u.email, u.phone, u.business_name,
                   u.industry, u.designation,
                   CASE WHEN u.profile_photo != 'empty.png' AND u.profile_photo != ''
                        THEN CONCAT(?, '/uploads/profile/', u.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS photo_url,
                   c.chapter_name
            FROM users u
            LEFT JOIN chapter_members cm ON cm.user_code = u.user_code AND cm.members_status = '1'
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE u.status = '1'
              AND (u.name LIKE ? OR u.business_name LIKE ? OR u.email LIKE ? OR u.industry LIKE ?)
            ORDER BY u.name ASC
            LIMIT 50
        ", [$baseUrl, $baseUrl, "%$query%", "%$query%", "%$query%", "%$query%"])->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $members,
            'count'  => count($members),
        ]);
    }
}

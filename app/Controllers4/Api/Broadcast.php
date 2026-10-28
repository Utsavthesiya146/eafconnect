<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Broadcast Controller
 *
 * GET /api/v1/broadcasts → list chapter broadcasts
 */
class Broadcast extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()->getRow();

        if (!$chapterMember) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => [],
            ]);
        }

        $broadcasts = $db->query("
            SELECT b.*, c.chapter_name
            FROM broadcasts b
            LEFT JOIN chapters c ON c.chapter_code = b.chapter_code
            WHERE b.chapter_code = ? AND b.status = '1'
            ORDER BY b.created_at DESC
            LIMIT 50
        ", [$chapterMember->chapter_code])->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $broadcasts,
        ]);
    }
}

<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\Connect Controller (Referrals)
 *
 * GET  /api/v1/connect        → list referrals (given + received)
 * POST /api/v1/connect/add    → add new referral
 * GET  /api/v1/connect/details/(:id) → referral details
 */
class Connect extends BaseController
{
    /**
     * GET /api/v1/connect
     * Returns all referrals given and received by the logged-in user.
     */
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $baseUrl = rtrim(base_url(), '/');

        $referrals = $db->query("
            SELECT
                r.*,
                fu.name AS from_name,
                fu.email AS from_email,
                CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != ''
                     THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                     ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                tu.name AS to_name,
                tu.email AS to_email,
                CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != ''
                     THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                     ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo,
                c.chapter_name
            FROM referrals r
            LEFT JOIN users fu ON fu.user_code = r.from_user
            LEFT JOIN users tu ON tu.user_code = r.to_user
            LEFT JOIN chapter_members cm ON cm.user_code = tu.user_code
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE r.from_user = ? OR r.to_user = ?
            ORDER BY r.created_at DESC
        ", [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code, $user_code])->getResultArray();

        $given    = array_filter($referrals, fn($r) => $r['from_user'] === $user_code);
        $received = array_filter($referrals, fn($r) => $r['to_user'] === $user_code);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'given'          => array_values($given),
                'received'       => array_values($received),
                'given_count'    => count($given),
                'received_count' => count($received),
            ],
        ]);
    }

    /**
     * POST /api/v1/connect/add
     * Add a new referral.
     *
     * Body: from_user, to_user, business_type, description, amount, status
     */
    public function add()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $to_user       = trim((string) $this->request->getPost('to_user'));
        $business_type = trim((string) $this->request->getPost('business_type'));
        $description   = trim((string) $this->request->getPost('description'));
        $amount        = (float) ($this->request->getPost('amount') ?? 0);

        if (empty($to_user) || empty($business_type)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Recipient and business type are required.',
            ]);
        }

        $commonModel  = new CommonModel();
        $referral_code = $commonModel->generateSerial('referrals', 'referral_code', 'RF');

        $insert = [
            'referral_code' => $referral_code,
            'from_user'     => $user_code,
            'to_user'       => $to_user,
            'business_type' => $business_type,
            'description'   => $description,
            'amount'        => $amount,
            'status'        => '1',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $result = $commonModel->insert_data('referrals', $insert);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Referral added successfully.' : 'Failed to add referral.',
        ]);
    }

    /**
     * GET /api/v1/connect/members
     * Get all members in the same chapter (for adding referrals).
     */
    public function members()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        // Get user's chapter
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

        $baseUrl = rtrim(base_url(), '/');

        $members = $db->query("
            SELECT u.user_code, u.name, u.email, u.phone,
                   CASE WHEN u.profile_photo != 'empty.png' AND u.profile_photo != ''
                        THEN CONCAT(?, '/uploads/profile/', u.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS photo_url,
                   u.business_name, u.industry
            FROM chapter_members cm
            INNER JOIN users u ON u.user_code = cm.user_code
            WHERE cm.chapter_code = ?
              AND cm.members_status = '1'
              AND u.status = '1'
              AND u.user_code != ?
            ORDER BY u.name ASC
        ", [$baseUrl, $baseUrl, $chapterMember->chapter_code, $user_code])->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $members,
        ]);
    }
}

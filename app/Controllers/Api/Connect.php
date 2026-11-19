<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\Connect Controller (Referrals)
 *
 * GET  /api/v1/connect               → list referrals (given + received)
 * POST /api/v1/connect/add           → add new referral
 * GET  /api/v1/connect/details/(:id) → referral details
 * GET  /api/v1/connect/members       → list members in chapter
 */
class Connect extends BaseController
{
    /**
     * Normalize referral status strings to canonical format
     */
    public static function normalizeStatus(?string $status): string
    {
        $clean = strtolower(trim(str_replace(['_', '-'], ' ', (string)$status)));
        return match ($clean) {
            'open for discussion' => 'Open For Discussion',
            'contacted'           => 'Contacted',
            'pending', 'new'      => 'Pending',
            'need sometime', 'not converted' => 'Need Sometime',
            'mitb'                => 'MITB',
            'completed', 'closed business', 'business done', 'bussiness done' => 'Completed',
            'cancelled', 'canceled', 'invalid' => 'Cancelled',
            'thank you'           => 'Thank You',
            default               => !empty($clean) ? ucwords($clean) : 'Pending',
        };
    }

    /**
     * GET /api/v1/connect
     * Returns all referrals given and received by the logged-in user.
     * Matches the website queries in App\Controllers\Member\Connect 100%.
     */
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = (string) $authUser->user_code;
        $db        = \Config\Database::connect();

        $baseUrl = rtrim(base_url(), '/');

        // Query Received Referrals (where to_user = current user)
        $qryReceived = "
            SELECT DISTINCT
                r.*,
                COALESCE(r.business, '') AS business_type,
                fu.name  AS from_name,
                fu.email AS from_email,
                CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != '' AND fu.profile_photo IS NOT NULL
                     THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                     ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                tu.name  AS to_name,
                tu.email AS to_email,
                CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != '' AND tu.profile_photo IS NOT NULL
                     THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                     ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo,
                COALESCE(c.chapter_name, '') AS chapter_name
            FROM referrals r
            LEFT JOIN users fu ON fu.user_code = r.from_user
            LEFT JOIN users tu ON tu.user_code = r.to_user
            LEFT JOIN chapter_members cm ON cm.user_code = fu.user_code
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE tu.status = '1'
              AND tu.user_code = ?
            ORDER BY r.created_at DESC
        ";

        // Query Given Referrals (where from_user = current user)
        $qryGiven = "
            SELECT 
                r.*,
                COALESCE(r.business, '') AS business_type,
                fu.name  AS from_name,
                fu.email AS from_email,
                CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != '' AND fu.profile_photo IS NOT NULL
                     THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                     ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                tu.name  AS to_name,
                tu.email AS to_email,
                CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != '' AND tu.profile_photo IS NOT NULL
                     THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                     ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo,
                COALESCE(c.chapter_name, '') AS chapter_name
            FROM referrals r
            LEFT JOIN users fu ON fu.user_code = r.from_user
            LEFT JOIN users tu ON tu.user_code = r.to_user
            LEFT JOIN chapter_members cm ON cm.user_code = tu.user_code
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE fu.status = '1'
              AND fu.user_code = ?
            ORDER BY r.created_at DESC
        ";

        $received = $db->query($qryReceived, [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code])->getResultArray();
        $given    = $db->query($qryGiven,    [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code])->getResultArray();

        // Normalize status for each referral
        foreach ($received as &$rRow) {
            $rRow['status'] = self::normalizeStatus($rRow['status'] ?? '');
            if (empty($rRow['business_type']) && !empty($rRow['business'])) {
                $rRow['business_type'] = $rRow['business'];
            }
        }
        unset($rRow);

        foreach ($given as &$gRow) {
            $gRow['status'] = self::normalizeStatus($gRow['status'] ?? '');
            if (empty($gRow['business_type']) && !empty($gRow['business'])) {
                $gRow['business_type'] = $gRow['business'];
            }
        }
        unset($gRow);

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
     * Body: to_user, business_type / business, description, contact_number, amount
     */
    public function add()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = (string) $authUser->user_code;

        $to_user        = trim((string) $this->request->getPost('to_user'));
        $business_type  = trim((string) ($this->request->getPost('business_type') ?? $this->request->getPost('business') ?? ''));
        $description    = trim((string) $this->request->getPost('description'));
        $contact_number = trim((string) ($this->request->getPost('contact_number') ?? $this->request->getPost('phone') ?? ''));
        $amount         = (float) ($this->request->getPost('amount') ?? 0);

        if (empty($to_user) || empty($business_type)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Recipient and business type are required.',
            ]);
        }

        $commonModel   = new CommonModel();
        $referral_code = $commonModel->generateSerial('referrals', 'referral_code', 'RF');

        $insert = [
            'referral_code'  => $referral_code,
            'from_user'      => $user_code,
            'to_user'        => $to_user,
            'business'       => $business_type,
            'contact_number' => $contact_number,
            'description'    => $description,
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
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
        $user_code = (string) $authUser->user_code;
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
                   CASE WHEN u.profile_photo != 'empty.png' AND u.profile_photo != '' AND u.profile_photo IS NOT NULL
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

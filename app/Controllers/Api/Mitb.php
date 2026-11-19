<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\Mitb Controller (Money in the Bank / TYFCB)
 *
 * GET  /api/v1/mitb      → list MITB records
 * POST /api/v1/mitb/add  → add new MITB record
 */
class Mitb extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = (string) $authUser->user_code;
        $db        = \Config\Database::connect();

        $baseUrl = rtrim(base_url(), '/');

        $qryGiven = "
            SELECT t.*,
                   fu.name AS from_name, fu.email AS from_email,
                   CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != '' AND fu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                   tu.name AS to_name, tu.email AS to_email,
                   CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != '' AND tu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo
            FROM tyfcb t
            LEFT JOIN users fu ON fu.user_code = t.from_user
            LEFT JOIN users tu ON tu.user_code = t.to_user
            WHERE t.from_user = ?
            ORDER BY t.created_at DESC
        ";

        $qryReceived = "
            SELECT t.*,
                   fu.name AS from_name, fu.email AS from_email,
                   CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != '' AND fu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                   tu.name AS to_name, tu.email AS to_email,
                   CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != '' AND tu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo
            FROM tyfcb t
            LEFT JOIN users fu ON fu.user_code = t.from_user
            LEFT JOIN users tu ON tu.user_code = t.to_user
            WHERE t.to_user = ?
            ORDER BY t.created_at DESC
        ";

        $given    = $db->query($qryGiven,    [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code])->getResultArray();
        $received = $db->query($qryReceived, [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code])->getResultArray();

        $totalGiven    = array_sum(array_column(array_values($given), 'amount'));
        $totalReceived = array_sum(array_column(array_values($received), 'amount'));

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'given'          => array_values($given),
                'received'       => array_values($received),
                'total_given'    => (float) $totalGiven,
                'total_received' => (float) $totalReceived,
            ],
        ]);
    }

    public function add()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = (string) $authUser->user_code;

        $to_user       = trim((string) $this->request->getPost('to_user'));
        $amount        = (float) ($this->request->getPost('amount') ?? 0);
        $business_type = trim((string) ($this->request->getPost('business_type') ?? ''));
        $category      = trim((string) ($this->request->getPost('category') ?? ''));
        $comments      = trim((string) ($this->request->getPost('comments') ?? $this->request->getPost('description') ?? ''));

        if (empty($to_user) || $amount <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Recipient and valid amount are required.',
            ]);
        }

        $commonModel = new CommonModel();
        $mitb_code   = $commonModel->generateSerial('tyfcb', 'mitb_code', 'MB');

        $insert = [
            'mitb_code'     => $mitb_code,
            'from_user'     => $user_code,
            'to_user'       => $to_user,
            'amount'        => $amount,
            'business_type' => $business_type,
            'category'      => $category,
            'comments'      => $comments,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $result = $commonModel->insert_data('tyfcb', $insert);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'MITB record added successfully.' : 'Failed to add MITB record.',
        ]);
    }
}

<?php

namespace App\Controllers4\Api;

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
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $baseUrl = rtrim(base_url(), '/');

        $records = $db->query("
            SELECT t.*,
                   fu.name AS from_name, fu.email AS from_email,
                   CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != ''
                        THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                   tu.name AS to_name, tu.email AS to_email,
                   CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != ''
                        THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo
            FROM tyfcb t
            LEFT JOIN users fu ON fu.user_code = t.from_user
            LEFT JOIN users tu ON tu.user_code = t.to_user
            WHERE t.from_user = ? OR t.to_user = ?
            ORDER BY t.created_at DESC
        ", [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code, $user_code])->getResultArray();

        $given    = array_filter($records, fn($r) => $r['from_user'] === $user_code);
        $received = array_filter($records, fn($r) => $r['to_user'] === $user_code);

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
        $user_code = $authUser->user_code;

        $to_user       = trim((string) $this->request->getPost('to_user'));
        $amount        = (float) ($this->request->getPost('amount') ?? 0);
        $business_type = trim((string) ($this->request->getPost('business_type') ?? ''));
        $description   = trim((string) ($this->request->getPost('description') ?? ''));

        if (empty($to_user) || $amount <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Recipient and valid amount are required.',
            ]);
        }

        $commonModel = new CommonModel();
        $mitb_code   = $commonModel->generateSerial('tyfcb', 'mitb_code', 'MITB');

        $result = $commonModel->insert_data('tyfcb', [
            'mitb_code'     => $mitb_code,
            'from_user'     => $user_code,
            'to_user'       => $to_user,
            'amount'        => $amount,
            'business_type' => $business_type,
            'description'   => $description,
            'status'        => '1',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'MITB record added.' : 'Failed to add MITB record.',
        ]);
    }
}

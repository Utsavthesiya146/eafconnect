<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\F2f Controller (One-to-One Meetings)
 *
 * GET  /api/v1/f2f      → list F2F meetings
 * POST /api/v1/f2f/add  → add new F2F meeting
 */
class F2f extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = (string) $authUser->user_code;
        $db        = \Config\Database::connect();
        $baseUrl   = rtrim(base_url(), '/');

        $qrySent = "
            SELECT o.*,
                   fu.name AS from_name, fu.email AS from_email,
                   CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != '' AND fu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                   tu.name AS to_name, tu.email AS to_email,
                   CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != '' AND tu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo
            FROM one_to_ones o
            LEFT JOIN users fu ON fu.user_code = o.from_user
            LEFT JOIN users tu ON tu.user_code = o.to_user
            WHERE o.from_user = ?
            ORDER BY o.created_at DESC
        ";

        $qryReceived = "
            SELECT o.*,
                   fu.name AS from_name, fu.email AS from_email,
                   CASE WHEN fu.profile_photo != 'empty.png' AND fu.profile_photo != '' AND fu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', fu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS from_photo,
                   tu.name AS to_name, tu.email AS to_email,
                   CASE WHEN tu.profile_photo != 'empty.png' AND tu.profile_photo != '' AND tu.profile_photo IS NOT NULL
                        THEN CONCAT(?, '/uploads/profile/', tu.profile_photo)
                        ELSE CONCAT(?, '/assets/img/empty.png') END AS to_photo
            FROM one_to_ones o
            LEFT JOIN users fu ON fu.user_code = o.from_user
            LEFT JOIN users tu ON tu.user_code = o.to_user
            WHERE o.to_user = ?
            ORDER BY o.created_at DESC
        ";

        $sent     = $db->query($qrySent,     [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code])->getResultArray();
        $received = $db->query($qryReceived, [$baseUrl, $baseUrl, $baseUrl, $baseUrl, $user_code])->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'sent'           => array_values($sent),
                'received'       => array_values($received),
                'sent_count'     => count($sent),
                'received_count' => count($received),
            ],
        ]);
    }

    public function add()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = (string) $authUser->user_code;

        $to_user      = trim((string) $this->request->getPost('to_user'));
        $meeting_date = trim((string) ($this->request->getPost('meeting_date') ?? ''));
        $location     = trim((string) ($this->request->getPost('location') ?? ''));
        $topics       = trim((string) ($this->request->getPost('topics') ?? ''));

        if (empty($to_user) || empty($meeting_date)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Meeting partner and date are required.',
            ]);
        }

        $commonModel = new CommonModel();
        $f2f_code    = $commonModel->generateSerial('one_to_ones', 'f2f_code', 'F2F');

        $insert = [
            'f2f_code'     => $f2f_code,
            'from_user'    => $user_code,
            'to_user'      => $to_user,
            'meeting_date' => $meeting_date,
            'location'     => $location,
            'topics'       => $topics,
            'status'       => 'scheduled',
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        $result = $commonModel->insert_data('one_to_ones', $insert);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'F2F meeting scheduled successfully.' : 'Failed to schedule F2F.',
        ]);
    }
}

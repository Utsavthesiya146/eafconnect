<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\Visitors Controller
 *
 * GET  /api/v1/visitors      → list visitors
 * POST /api/v1/visitors/add  → add new visitor
 */
class Visitors extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $visitors = $db->query("
            SELECT v.*
            FROM visitors v
            WHERE v.user_code = ?
            ORDER BY v.created_at DESC
        ", [$user_code])->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'visitors' => $visitors,
                'total'    => count($visitors),
            ],
        ]);
    }

    public function add()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $name     = trim((string) $this->request->getPost('name'));
        $email    = trim((string) ($this->request->getPost('email') ?? ''));
        $phone    = trim((string) ($this->request->getPost('phone') ?? ''));
        $business = trim((string) ($this->request->getPost('business') ?? ''));
        $purpose  = trim((string) ($this->request->getPost('purpose') ?? ''));

        if (empty($name)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Visitor name is required.',
            ]);
        }

        $commonModel  = new CommonModel();
        $visitor_code = $commonModel->generateSerial('visitors', 'visitor_code', 'VS');

        $result = $commonModel->insert_data('visitors', [
            'visitor_code' => $visitor_code,
            'user_code'    => $user_code,
            'name'         => $name,
            'email'        => $email,
            'phone'        => $phone,
            'business'     => $business,
            'purpose'      => $purpose,
            'status'       => 'Pending',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Visitor added successfully.' : 'Failed to add visitor.',
        ]);
    }
}

<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\Invitations Controller
 *
 * GET  /api/v1/invitations      → list invitations
 * POST /api/v1/invitations/add  → add new invitation
 */
class Invitations extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $invitations = $db->query("
            SELECT i.*
            FROM invitations i
            WHERE i.user_code = ?
            ORDER BY i.created_at DESC
        ", [$user_code])->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $invitations,
        ]);
    }

    public function add()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $name  = trim((string) $this->request->getPost('name'));
        $email = trim((string) ($this->request->getPost('email') ?? ''));
        $phone = trim((string) ($this->request->getPost('phone') ?? ''));

        if (empty($name)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Name is required.',
            ]);
        }

        $commonModel     = new CommonModel();
        $invitation_code = $commonModel->generateSerial('invitations', 'invitation_code', 'INV');

        // Get chapter code from user's active membership
        $db = \Config\Database::connect();
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()
            ->getRow();

        $result = $commonModel->insert_data('invitations', [
            'invitation_code' => $invitation_code,
            'user_code'       => $user_code,
            'chapter_code'    => $chapterMember->chapter_code ?? '',
            'name'            => $name,
            'email'           => $email,
            'phone'           => $phone,
            'status'          => 'Pending',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Invitation sent successfully.' : 'Failed to send invitation.',
        ]);
    }
}

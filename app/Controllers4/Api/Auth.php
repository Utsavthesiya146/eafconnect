<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ApiTokenModel;

/**
 * Api\Auth Controller
 *
 * Handles mobile authentication:
 * - POST /api/v1/login   → returns token + user data
 * - POST /api/v1/logout  → revokes token
 *
 * Does NOT use PHP sessions — stateless token auth only.
 */
class Auth extends BaseController
{
    /**
     * POST /api/v1/login
     *
     * Body: { "email": "...", "password": "...", "device_info": "..." }
     */
    public function login()
    {
        $email      = trim((string) $this->request->getPost('email'));
        $password   = (string) $this->request->getPost('password');
        $deviceInfo = (string) ($this->request->getPost('device_info') ?? '');

        // Validation
        if (empty($email) || empty($password)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Email and password are required.',
            ]);
        }

        $userModel = new UserModel();

        // Find user by email
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Invalid email or password.',
            ]);
        }

        if (is_array($user)) {
            $user = (object) $user;
        }

        // Verify password
        if (!password_verify($password, $user->password)) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Invalid email or password.',
            ]);
        }

        // Check account is active
        if ($user->status != '1') {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Your account is not active. Please contact admin.',
            ]);
        }

        // Determine role-specific data
        $db          = \Config\Database::connect();
        $chapterCode = null;
        $chapterRole = null;
        $chapterName = null;

        if ($user->role === 'member') {
            // Check active chapter membership
            $chapterMember = $db->table('chapter_members')
                ->where('user_code', $user->user_code)
                ->where('members_status', '1')
                ->get()
                ->getRow();

            if (!$chapterMember) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status'  => 'error',
                    'message' => 'You are not an active chapter member. Please contact admin.',
                ]);
            }

            $chapterCode = $chapterMember->chapter_code;
            $chapterRole = $chapterMember->chapter_role ?? '';

            // Get chapter name
            $chapter = $db->table('chapters')
                ->where('chapter_code', $chapterCode)
                ->get()
                ->getRow();
            $chapterName = $chapter->chapter_name ?? '';
        }

        // Generate API token
        $tokenModel = new ApiTokenModel();
        $token      = $tokenModel->createToken($user->user_code, $deviceInfo);

        // Build profile photo URL
        $baseUrl = rtrim(base_url(), '/');
        $photoUrl = $user->profile_photo && $user->profile_photo !== 'empty.png'
            ? $baseUrl . '/uploads/profile/' . $user->profile_photo
            : $baseUrl . '/assets/img/empty.png';

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => [
                'user_id'       => $user->user_id,
                'user_code'     => $user->user_code,
                'name'          => $user->name,
                'last_name'     => $user->last_name ?? '',
                'email'         => $user->email,
                'phone'         => $user->phone,
                'role'          => $user->role,
                'member_role'   => $user->member_role ?? '',
                'member_type'   => $user->member_type ?? '',
                'status'        => $user->status,
                'profile_photo' => $photoUrl,
                'chapter_code'  => $chapterCode,
                'chapter_role'  => $chapterRole,
                'chapter_name'  => $chapterName,
            ],
        ]);
    }

    /**
     * POST /api/v1/logout
     *
     * Revokes the current device token.
     * Requires: Authorization: Bearer <token>
     */
    public function logout()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token      = trim(substr($authHeader, 7));
            $tokenModel = new ApiTokenModel();
            $tokenModel->revokeToken($token);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Logged out successfully.',
        ]);
    }
}

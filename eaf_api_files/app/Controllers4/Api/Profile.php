<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;

/**
 * Api\Profile Controller
 *
 * GET  /api/v1/profile          → get logged-in user profile
 * POST /api/v1/profile/update   → update basic profile info
 * POST /api/v1/profile/skills   → update professional skills
 * POST /api/v1/profile/business → update business info
 * POST /api/v1/profile/social   → update social media links
 * POST /api/v1/profile/photo    → upload profile photo (multipart)
 * POST /api/v1/profile/password → change password
 * GET  /api/v1/profile/details/(:user_code) → public profile view
 */
class Profile extends BaseController
{
    /**
     * GET /api/v1/profile
     * Returns the authenticated user's full profile.
     */
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $db   = \Config\Database::connect();
        $user = $db->table('users')
            ->where('user_code', $user_code)
            ->where('status', '1')
            ->get()->getRowArray();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User not found.',
            ]);
        }

        // Build URLs for uploaded files
        $baseUrl = rtrim(base_url(), '/');
        $user['profile_photo_url'] = !empty($user['profile_photo']) && $user['profile_photo'] !== 'empty.png'
            ? $baseUrl . '/uploads/profile/' . $user['profile_photo']
            : $baseUrl . '/assets/img/empty.png';

        $user['business_logo_url'] = !empty($user['business_logo'])
            ? $baseUrl . '/uploads/business_logo/' . $user['business_logo']
            : null;

        $user['business_card_url'] = !empty($user['business_card'])
            ? $baseUrl . '/uploads/business_cards/' . $user['business_card']
            : null;

        // Remove sensitive fields
        unset($user['password']);

        // Chapter info
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()->getRow();

        $chapter = null;
        if ($chapterMember) {
            $chapterRow = $db->table('chapters')
                ->where('chapter_code', $chapterMember->chapter_code)
                ->get()->getRow();
            $chapter = [
                'chapter_code' => $chapterMember->chapter_code,
                'chapter_name' => $chapterRow->chapter_name ?? '',
                'chapter_role' => $chapterMember->chapter_role ?? '',
            ];
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => [
                'user'    => $user,
                'chapter' => $chapter,
            ],
        ]);
    }

    /**
     * POST /api/v1/profile/update
     * Updates personal info (name, gender, dob, phone, address, etc.)
     */
    public function update()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $data = [
            'name'      => $this->request->getPost('name'),
            'gender'    => $this->request->getPost('gender'),
            'dob'       => $this->request->getPost('dob'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'alt_phone' => $this->request->getPost('alt_phone'),
            'whatsapp'  => $this->request->getPost('whatsapp'),
            'address'   => $this->request->getPost('address'),
            'city'      => $this->request->getPost('city'),
            'state'     => $this->request->getPost('state'),
            'pincode'   => $this->request->getPost('pincode'),
        ];

        // Remove null values
        $data = array_filter($data, fn($v) => $v !== null);

        $model  = new CommonModel();
        $result = $model->update_row('users', $data, ['user_code' => $user_code]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Profile updated successfully.' : 'Failed to update profile.',
        ]);
    }

    /**
     * POST /api/v1/profile/skills
     * Updates professional skills section.
     */
    public function updateSkills()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $data = [
            'designation' => $this->request->getPost('designation'),
            'exp_years'   => $this->request->getPost('exp_years'),
            'skills'      => $this->request->getPost('skills'),
            'services'    => $this->request->getPost('services'),
            'products'    => $this->request->getPost('products'),
            'bio'         => $this->request->getPost('bio'),
        ];

        $data   = array_filter($data, fn($v) => $v !== null);
        $model  = new CommonModel();
        $result = $model->update_row('users', $data, ['user_code' => $user_code]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Skills updated successfully.' : 'Update failed.',
        ]);
    }

    /**
     * POST /api/v1/profile/business
     * Updates business information.
     */
    public function updateBusiness()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $data = [
            'business_name' => $this->request->getPost('business_name'),
            'industry'      => $this->request->getPost('industry'),
            'subcategory'   => $this->request->getPost('subcategory'),
            'description'   => $this->request->getPost('description'),
            'reg_number'    => $this->request->getPost('reg_number'),
            'gst_number'    => $this->request->getPost('gst_number'),
            'start_year'    => $this->request->getPost('start_year'),
            'employees'     => $this->request->getPost('employees'),
        ];

        $data   = array_filter($data, fn($v) => $v !== null);
        $model  = new CommonModel();
        $result = $model->update_row('users', $data, ['user_code' => $user_code]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Business info updated.' : 'Update failed.',
        ]);
    }

    /**
     * POST /api/v1/profile/social
     * Updates social media links.
     */
    public function updateSocial()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $data = [
            'website'         => $this->request->getPost('website') ?? '',
            'linkedin'        => $this->request->getPost('linkedin') ?? '',
            'facebook'        => $this->request->getPost('facebook') ?? '',
            'instagram'       => $this->request->getPost('instagram') ?? '',
            'twitter'         => $this->request->getPost('twitter') ?? '',
            'youtube'         => $this->request->getPost('youtube') ?? '',
            'google_business' => $this->request->getPost('google_business') ?? '',
        ];

        $model  = new CommonModel();
        $result = $model->update_row('users', $data, ['user_code' => $user_code]);

        return $this->response->setJSON([
            'status'  => $result ? 'success' : 'error',
            'message' => $result ? 'Social links updated.' : 'Update failed.',
        ]);
    }

    /**
     * POST /api/v1/profile/photo
     * Upload profile photo (multipart/form-data).
     * Field name: profile_photo
     */
    public function uploadPhoto()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $file = $this->request->getFile('profile_photo');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'No valid file uploaded.',
            ]);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Only JPEG, PNG, WebP, GIF images are allowed.',
            ]);
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/profile/', $newName);

        $model = new CommonModel();
        $model->update_row('users', ['profile_photo' => $newName], ['user_code' => $user_code]);

        $baseUrl  = rtrim(base_url(), '/');
        $photoUrl = $baseUrl . '/uploads/profile/' . $newName;

        return $this->response->setJSON([
            'status'      => 'success',
            'message'     => 'Profile photo updated.',
            'photo_url'   => $photoUrl,
            'photo_name'  => $newName,
        ]);
    }

    /**
     * POST /api/v1/profile/password
     * Change password.
     */
    public function changePassword()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;

        $currentPass = $this->request->getPost('current_password');
        $newPass     = $this->request->getPost('new_password');
        $confirmPass = $this->request->getPost('confirm_password');

        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'All fields are required.',
            ]);
        }

        if ($newPass !== $confirmPass) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'New passwords do not match.',
            ]);
        }

        if (strlen($newPass) < 8) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Password must be at least 8 characters.',
            ]);
        }

        $db   = \Config\Database::connect();
        $user = $db->table('users')
            ->where('user_code', $user_code)
            ->get()->getRow();

        if (!$user || !password_verify($currentPass, $user->password)) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Current password is incorrect.',
            ]);
        }

        $model = new CommonModel();
        $model->update_row(
            'users',
            ['password' => password_hash($newPass, PASSWORD_DEFAULT)],
            ['user_code' => $user_code]
        );

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Password changed successfully.',
        ]);
    }

    /**
     * GET /api/v1/profile/details/(:user_code)
     * Public profile of any member (for chapter directory).
     */
    public function details(string $target_user_code)
    {
        $db       = \Config\Database::connect();
        $safe     = esc(trim($target_user_code));

        $user = $db->query("
            SELECT u.*, c.chapter_name
            FROM users u
            LEFT JOIN chapter_members cm ON cm.user_code = u.user_code
            LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
            WHERE u.status = '1' AND u.user_code = ?
        ", [$safe])->getRowArray();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Member not found.',
            ]);
        }

        $baseUrl = rtrim(base_url(), '/');
        $user['profile_photo_url'] = !empty($user['profile_photo']) && $user['profile_photo'] !== 'empty.png'
            ? $baseUrl . '/uploads/profile/' . $user['profile_photo']
            : $baseUrl . '/assets/img/empty.png';

        unset($user['password']);

        // Quick stats
        $referrals_given    = $db->query("SELECT COUNT(*) total FROM referrals WHERE from_user=?", [$safe])->getRow()->total ?? 0;
        $referrals_received = $db->query("SELECT COUNT(*) total FROM referrals WHERE to_user=?",   [$safe])->getRow()->total ?? 0;
        $f2f_count          = $db->query("SELECT COUNT(*) total FROM one_to_ones WHERE from_user=?", [$safe])->getRow()->total ?? 0;
        $mitb_total         = $db->query("SELECT COALESCE(SUM(amount),0) total FROM tyfcb WHERE from_user=? OR to_user=?", [$safe, $safe])->getRow()->total ?? 0;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'user'               => $user,
                'referrals_given'    => (int) $referrals_given,
                'referrals_received' => (int) $referrals_received,
                'f2f_count'          => (int) $f2f_count,
                'mitb_total'         => (float) $mitb_total,
            ],
        ]);
    }
}

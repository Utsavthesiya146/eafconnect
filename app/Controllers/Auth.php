<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->userModel = new UserModel();
        session();
    }

    public function loginCheck()
    {
        $isAjax   = $this->request->isAJAX();
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        // Validate input
        if (empty($email) || empty($password)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Email and Password are required.']);
            }
            return redirect()->back()->with('error', 'Email and Password are required');
        }

        // Get user
        $user = $this->userModel
            ->where('email', $email)
            ->first();

        // Check user exists
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email or password.']);
            }
            return redirect()->back()->with('error', 'Invalid Login');
        }

        if (is_array($user)) {
            $user = (object) $user;
        }

        // Check password
        if (!password_verify($password, $user->password)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email or password.']);
            }
            return redirect()->back()->with('error', 'Invalid Login');
        }

        // Check active status
        if ($user->status != '1') {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Your account is currently inactive. Please contact administration.']);
            }
            return redirect()->back()->with('error', 'Account not active');
        }

        /*
        ==========================================
        ADMIN USER HANDLING
        ==========================================
        */
        if ($user->role == 'admin') {
            $sessionData = [
                'user_id'       => $user->user_id,
                'user_code'     => $user->user_code,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'profile_photo' => $user->profile_photo,
                'role'          => $user->role,
                'member_role'   => $user->member_role ?? 'Administrator',
                'member_type'   => $user->member_type ?? 'admin',
                'status'        => $user->status,
                'logged_in'     => true,
                'is_admin'      => true,
            ];
            session()->set($sessionData);

            if ($isAjax) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => 'Sign in successful! Redirecting to Admin Dashboard...',
                    'redirect' => base_url('admin/dashboard')
                ]);
            }
            return redirect()->to('/admin/dashboard');
        }

        /*
        ==========================================
        MEMBER USER HANDLING
        ==========================================
        */
        $db = \Config\Database::connect();

        // Check chapter membership
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user->user_code)
            ->where('members_status', '1')
            ->get()
            ->getRow();

        if (!$chapterMember) {
            $msg = 'You are not an active chapter member. Please contact admin.';
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
            }
            return redirect()->back()->with('error', $msg);
        }

        if (empty($chapterMember->chapter_code)) {
            $msg = 'Chapter not assigned. Please contact admin.';
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
            }
            return redirect()->back()->with('error', $msg);
        }

        // Session set
        $sessionData = [
            'user_id'       => $user->user_id,
            'user_code'     => $user->user_code,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'profile_photo' => $user->profile_photo,
            'role'          => $user->role,
            'member_role'   => $user->member_role,
            'member_type'   => $user->member_type,
            'status'        => $user->status,
            'chapter_code'  => $chapterMember->chapter_code,
            'chapter_role'  => $chapterMember->chapter_role ?? '',
            'logged_in'     => true,
            'is_member'     => true
        ];

        session()->set($sessionData);

        if ($isAjax) {
            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'Sign in successful! Redirecting to Member Dashboard...',
                'redirect' => base_url('member/dashboard')
            ]);
        }

        return redirect()->to('/member/dashboard');
    }
    public function loginCheckAdmin()
    {
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        // Validate input
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email and Password are required');
        }

        // Get user
        $user = $this->userModel
            ->where('email', $email)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid Login');
        }

        if (is_array($user)) {
            $user = (object) $user;
        }

        // Check password
        if (!password_verify($password, $user->password)) {
            return redirect()->back()->with('error', 'Invalid Login');
        }

        // Check active
        if ($user->status != '1') {
            return redirect()->back()->with('error', 'Account not active');
        }

        /*
    =====================================
    ONLY ADMIN ALLOWED HERE
    =====================================
    */
        if ($user->role != 'admin') {
            return redirect()->to(base_url('/'))
                ->with('error', 'Please login from Member Portal');
        }

        /*
    =====================================
    ADMIN LOGIN SUCCESS
    =====================================
    */
        $sessionData = [
            'user_id'       => $user->user_id,
            'user_code'     => $user->user_code,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'profile_photo' => $user->profile_photo,
            'role'          => $user->role,
            'member_role'   => $user->member_role,
            'member_type'   => $user->member_type,
            'status'        => $user->status,
            'logged_in'     => true,
            'is_admin'      => true,
        ];

        session()->set($sessionData);

        return redirect()->to('/admin/dashboard');
    }

    public function login()
    {
        return redirect()->to(base_url('/'));
    }

    public function dashboard()
    {
        if (session()->get('logged_in')) {
            if (session()->get('is_admin')) {
                return redirect()->to(base_url('admin/dashboard'));
            }
            return redirect()->to(base_url('member/dashboard'));
        }
        return redirect()->to(base_url('/'));
    }

    /* ================= LOGOUT ================= */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/?auth=1'));
    }

    /* ================= 1-CLICK LIVE PREVIEW (SHARE WITH CLIENT / SIR) ================= */
    public function preview($target = '')
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('user_code', '5')->get()->getRow();
        if (!$user) {
            $user = $db->table('users')->where('role', 'member')->where('status', '1')->get()->getRow();
        }

        if ($user) {
            $chapterMember = $db->table('chapter_members')
                ->where('user_code', $user->user_code)
                ->where('members_status', '1')
                ->get()
                ->getRow();

            $sessionData = [
                'user_id'       => $user->user_id,
                'user_code'     => $user->user_code,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'profile_photo' => $user->profile_photo,
                'role'          => $user->role,
                'member_role'   => $user->member_role,
                'member_type'   => $user->member_type,
                'status'        => $user->status,
                'chapter_code'  => $chapterMember->chapter_code ?? 'all',
                'chapter_role'  => $chapterMember->chapter_role ?? '',
                'logged_in'     => true,
                'is_member'     => true,
            ];

            session()->set($sessionData);
        }

        $cleanTarget = strtolower(trim(str_replace(['.html', '.php'], '', (string)$target)));
        $cleanTarget = ltrim($cleanTarget, '/');

        // Route mapping for known aliases
        $routesMap = [
            'visitor'                 => 'member/visitor',
            'visitors'                => 'member/visitor',
            'meetings'                => 'member/meetings',
            'meeting'                 => 'member/meetings',
            'connect'                 => 'member/connect',
            'connects'                => 'member/connect',
            'profile'                 => 'member/profile',
            'mychapter'               => 'member/mychapter',
            'chapter'                 => 'member/chapter',
            'chapter_attendance'      => 'member/chapter_attendance',
            'f2f'                     => 'member/f2f',
            'mitb'                    => 'member/mitb',
            'topperformer'            => 'member/topperformer',
            'search'                  => 'member/search',
            'my_attendance'           => 'member/my_attendance',
            'notifications'           => 'member/notifications',
            'broadcast'               => 'member/broadcast',
            'invitations'             => 'member/invitations',
            'dashboard'               => 'member/dashboard',
            'my_participation_report' => 'member/reports/my_participation_report',
            'participation'           => 'member/reports/participation',
            'received_connects_report'=> 'member/reports/received_connects_report',
            'weekly_slips'            => 'member/reports/weekly_slips',
        ];

        if (!empty($cleanTarget) && isset($routesMap[$cleanTarget])) {
            return redirect()->to(base_url($routesMap[$cleanTarget]));
        }

        if (!empty($cleanTarget) && $cleanTarget !== 'index') {
            return redirect()->to(base_url('member/' . $cleanTarget));
        }

        return redirect()->to(base_url('member/dashboard'));
    }
}

<?php

namespace App\Controllers;

use App\Models\CommonModel;
use App\Models\ContactModel;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        // Default values
        $where  = '';
        $params = [];

        // Counts
        $user = $db->query("SELECT COUNT(*) AS total FROM users as u inner join chapter_members cm ON u.user_code=cm.user_code WHERE u.status='1'")
            ->getRow()->total ?? 0;

        $chapters = $db->query("SELECT COUNT(*) AS total FROM chapters")
            ->getRow()->total ?? 0;

        $tyfcb = $db->query("SELECT COUNT(*) AS total FROM tyfcb")
            ->getRow()->total ?? 0;

        $MITB = $db->query("
        SELECT COALESCE(SUM(amount),0) total 
        FROM tyfcb $where
    ", $params)->getRow()->total ?? 0;

        // Rounded Average Member Value
        $averageMemberValue = ($user > 0)
            ? round($MITB / $user)
            : 0;

        $activeChapters = $db->table('chapters')
            ->select('chapters.*, (SELECT COUNT(*) FROM chapter_members WHERE chapter_members.chapter_code = chapters.chapter_code AND members_status = "1") as member_count')
            ->where('chapter_status', '1')
            ->get()
            ->getResultArray();

        $upcomingMeetings = $db->table('events e')
            ->select('e.*, c.chapter_name')
            ->join('chapters c', 'c.chapter_code = e.chapter_code', 'left')
            ->where('e.status', '1')
            ->orderBy('e.date', 'DESC')
            ->limit(4)
            ->get()
            ->getResultArray();

        $data = [
            'user'               => $user,
            'chapters'           => $chapters,
            'tyfcb'              => $tyfcb,
            'MITB'               => round($MITB),
            'averageMemberValue' => $averageMemberValue,
            'activeChapters'     => $activeChapters,
            'upcomingMeetings'   => $upcomingMeetings,
        ];

        return view('home', $data);
    }

    public function contact(): string
    {
        return view('contact');
    }
    public function member_login()
    {
        // If already logged in
        if (session()->get('logged_in') == true) {

            // Admin redirect
            if (session()->get('is_admin') == true) {
                return redirect()->to('/admin/dashboard');
            }

            // Member redirect
            return redirect()->to('/member/dashboard');
        }
        $db = \Config\Database::connect();

        // Default values
        $where  = '';
        $params = [];

        // Counts
        $user = $db->query("SELECT COUNT(*) AS total FROM users as u inner join chapter_members cm ON u.user_code=cm.user_code WHERE u.status='1'")
            ->getRow()->total ?? 0;

        $chapters = $db->query("SELECT COUNT(*) AS total FROM chapters")
            ->getRow()->total ?? 0;

        $tyfcb = $db->query("SELECT COUNT(*) AS total FROM tyfcb")
            ->getRow()->total ?? 0;

        $MITB = $db->query("
        SELECT COALESCE(SUM(amount),0) total 
        FROM tyfcb $where
    ", $params)->getRow()->total ?? 0;

        // Rounded Average Member Value
        $averageMemberValue = ($user > 0)
            ? round($MITB / $user)
            : 0;

        $data = [
            'user'               => $user,
            'chapters'           => $chapters,
            'tyfcb'              => $tyfcb,
            'MITB'               => round($MITB),
            'averageMemberValue' => $averageMemberValue,
        ];
        return view('member_login', $data);
    }

    public function admin_login()
    {
        // If already logged in
        if (session()->get('logged_in') == true) {

            // Admin redirect
            if (session()->get('is_admin') == true) {
                return redirect()->to('/admin/dashboard');
            }

            // Member redirect
            return redirect()->to('/member/dashboard');
        }
        $db = \Config\Database::connect();

        // Default values
        $where  = '';
        $params = [];

        // Counts
        $user = $db->query("SELECT COUNT(*) AS total FROM users WHERE status='1'")
            ->getRow()->total ?? 0;

        $chapters = $db->query("SELECT COUNT(*) AS total FROM chapters")
            ->getRow()->total ?? 0;

        $tyfcb = $db->query("SELECT COUNT(*) AS total FROM tyfcb")
            ->getRow()->total ?? 0;

        $MITB = $db->query("
        SELECT COALESCE(SUM(amount),0) total 
        FROM tyfcb $where
    ", $params)->getRow()->total ?? 0;

        // Rounded Average Member Value
        $averageMemberValue = ($user > 0)
            ? round($MITB / $user)
            : 0;

        $data = [
            'user'               => $user,
            'chapters'           => $chapters,
            'tyfcb'              => $tyfcb,
            'MITB'               => round($MITB),
            'averageMemberValue' => $averageMemberValue,
        ];
        return view('admin_login', $data);
    }
    public function member_register()
    {
        return view('member_register');
    }
    public function addlead()
    {
        $commonModel = new CommonModel();
        $isAjax = $this->request->isAJAX();

        $data = $this->request->getPost();

        // Validation for required fields
        $name     = trim($data['name'] ?? '');
        $lastName = trim($data['last_name'] ?? '');
        $email    = trim($data['email'] ?? '');
        $phone    = trim($data['phone'] ?? '');

        if (empty($name) || empty($email) || empty($phone)) {
            $msg = 'Please fill in all required fields (First Name, Email, Phone).';
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
            }
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
        
        $lead_code = $commonModel->generateSerial(
            'leads',
            'lead_code',
            'LD'
        );

        $insert = [
            'lead_code'  => $lead_code,
            'name'       => $name,
            'last_name'  => $lastName,
            'email'      => $email,
            'phone'      => $phone,
            'business'   => trim($data['business'] ?? ''),
            'location'   => trim($data['location'] ?? ''),
            'gst'        => trim($data['gst'] ?? ''),
            'category'   => trim($data['category'] ?? ''),
            'since'      => $data['since'] ?? '',
            'status'     => 'new',
        ];

        $insertId = $commonModel->insert_data('leads', $insert);

        if ($insertId) {
            $successMsg = 'Membership application submitted successfully! Our Chapter Executive Committee will review your application for category exclusivity.';
            if ($isAjax) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => $successMsg
                ]);
            }

            session()->setFlashdata('success', $successMsg);
            return redirect()->to(base_url('/'));
        }

        $errMsg = 'Unable to save registration. Please try again.';
        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $errMsg]);
        }

        session()->setFlashdata('error', $errMsg);
        return redirect()->back()->withInput();
    }
    public function save()
    {
        helper(['form']);

        $model = new ContactModel();
        $contact_code = 'CNT' . time();
        $model->insert([
            'contact_code' => $contact_code,
            'name'         => trim((string)$this->request->getPost('name')),
            'email'        => trim((string)$this->request->getPost('email')),
            'phone'        => trim((string)$this->request->getPost('phone')),
            'subject'      => trim((string)$this->request->getPost('subject')) ?: 'General Inquiry',
            'message'      => trim((string)$this->request->getPost('message')),
            'user_code'    => '',
            'status'       => '1',
            'note'         => ''
        ]);

        return redirect()->to(base_url('contact'))
            ->with('success', 'Message sent successfully.');
    }
}

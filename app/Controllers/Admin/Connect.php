<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\ReferralModel;

class Connect extends BaseController
{
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
            default               => !empty($clean) ? ucwords($clean) : 'Pending',
        };
    }

    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model       = new \App\Models\ReferralModel();
        $commonModel = new CommonModel();

        /* ========= REQUEST VALUES ========= */
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $search  = trim($this->request->getGet('search') ?? '');
        $status  = trim($this->request->getGet('status') ?? '');
        $builder = $model
            ->select("
        referrals.*, 

        from_user.name  as from_name,
        from_user.email as from_email,
        from_user.phone as from_phone,

        to_user.name  as to_name,
        to_user.email as to_email,
        to_user.phone as to_phone,

        COALESCE(from_user.profile_photo,'empty.png') as from_profile,
        COALESCE(to_user.profile_photo,'empty.png') as to_profile
    ")
            ->join('users as from_user', 'from_user.user_code = referrals.from_user', 'left')
            ->join('users as to_user', 'to_user.user_code = referrals.to_user', 'left');
        /* ========= SEARCH FILTER ========= */
        if ($search != '') {
            $builder->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('referrals.business', $search)
                ->orLike('referrals.contact_number', $search)
                ->groupEnd();
        }

        /* ========= STATUS FILTER ========= */
        if ($status != '') {
            $builder->where('referrals.status', $status);
        }

        /* ========= ORDER ========= */
        $builder->orderBy('referrals.referral_id', 'DESC');

        /* ========= MEMBER LIST ========= */
        $data['member'] = $commonModel
            ->exe_query("SELECT user_code,name FROM users WHERE status='1'")
            ->getResultArray();

        /* ========= PAGINATION ========= */
        $data['connect'] = $builder->paginate($perPage);
        $data['pager']   = $model->pager;
        $data['perPage'] = $perPage;

        return view('admin/connects', $data);
    }
    public function details($referral_code)
    {
        $model = new \App\Models\ReferralModel();
        $commonModel = new \App\Models\CommonModel();

        /* ========= REQUEST VALUES ========= */
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = $this->request->getGet('search') ?? '';
        $status  = $this->request->getGet('status') ?? '';

        /* ========= BUILD QUERY ========= */
        $builder = $model; // Using model directly for pagination

        // Base select and joins
        $builder = $builder
            ->select("referrals.*, 
            from_user.name as from_name, from_user.email as from_email, from_user.phone as from_phone,
            to_user.name as to_name, to_user.email as to_email, to_user.phone as to_phone")
            ->join('users from_user', 'from_user.user_id = referrals.from_user', 'left')
            ->join('users to_user', 'to_user.user_id = referrals.to_user', 'left');

        // Search filter
        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('from_user.name', $search)
                ->orLike('to_user.name', $search)
                ->orLike('referrals.business', $search)
                ->orLike('referrals.contact_number', $search)
                ->groupEnd();
        }

        // Status filter
        if (!empty($status)) {
            $builder = $builder->where('referrals.status', $status);
        }

        // Filter by referral code (optional if needed)
        if (!empty($referral_code)) {
            $builder = $builder->where('referrals.referral_code', $referral_code);
        }

        // Order
        $builder = $builder->orderBy('referrals.referral_id', 'DESC');

        /* ========= PAGINATION ========= */
        $data['connect'] = $builder->paginate($perPage, 'default', $page);
        $data['pager']   = $builder->pager;
        $data['perPage'] = $perPage;

        /* ========= MEMBER LIST ========= */
        $member = $commonModel->exe_query("SELECT user_code, name FROM users WHERE status='1'");
        if ($member instanceof \CodeIgniter\Database\BaseResult) {
            $member = $member->getResultArray();
        }
        $data['member'] = $member;

        /* ========= RENDER VIEW ========= */
        return view('admin/connects_details', $data);
    }

    public function AddNewReferralData()
    {
        helper(['form']);
        $commonModel = new \App\Models\CommonModel();
        // ✅ GET POST SAFELY
        $data = $this->request->getPost();
        // ✅ GENERATE CHAPTER CODE
        $referral_code = $commonModel->get_serial3(
            'referrals',
            'REFF',
            'referral_id' // ← MUST match DB column
        );
        $insert = [
            'referral_code'        => $referral_code,
            'from_user'        => $data['from_user'] ?? '',
            'to_user'            => $data['to_user'] ?? '',
            'business'         => $data['business'] ?? null,
            'contact_number'       => $data['contact_number'] ?? '',
            'temperature' => $data['temperature'] ?? '',
            'status' => $data['status'] ?? '',
            'description' => $data['description'] ?? '',
        ];

        $commonModel->insert_data('referrals', $insert);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Referral Added Successfully'
        ]);
    }

    public function delete_refferral($referral_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM referrals  WHERE referral_code='" . esc($referral_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_referral_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="referral_code" value="' . esc($row->referral_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;">Name: <span style="color:#05008D;">' . esc($row->business) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">Email: ' . esc($row->status) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Refferral?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_referral_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $referral_code = $this->request->getPost('referral_code');
        if (!$referral_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("referrals", ['referral_code' => $referral_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Referral removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }


    public function edit_refferral_status($referral_code)
    {
        $db = \Config\Database::connect();
        $referral_code = trim((string)$referral_code);
        $row = $db->table('referrals')
            ->groupStart()
                ->where('referral_code', $referral_code)
                ->orWhere('referral_id', $referral_code)
            ->groupEnd()
            ->get()
            ->getRow();

        if (empty($row)) {
            return $this->response->setBody('<p class="text-center text-danger p-3">Referral not found!</p>');
        }

        $currentStatus = self::normalizeStatus($row->status ?? 'Pending');
        $canonicalStatuses = [
            'Open For Discussion',
            'Contacted',
            'Pending',
            'Need Sometime',
            'MITB',
            'Completed',
            'Cancelled',
        ];

        $optionsHtml = '';
        foreach ($canonicalStatuses as $st) {
            $selected = (strcasecmp($st, $currentStatus) === 0) ? ' selected' : '';
            $optionsHtml .= '<option value="' . esc($st) . '"' . $selected . '>' . esc($st) . '</option>';
        }

        $output = '
<div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 12px 32px rgba(15,59,104,0.22); font-size:14px; overflow:hidden;">
    <form id="edit_refferral_status_action" method="post">
        <div class="modal-header" style="background:#0F3B68; color:#fff; border-bottom:1px solid rgba(255,255,255,0.12); padding:16px 20px;">
            <h6 class="modal-title mb-0" style="font-size:16px; font-weight:700; color:#ffffff; line-height:1.4;">Change Referral Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity:0.85;"></button>
        </div>
        <div class="modal-body" style="padding:20px;">
            <input type="hidden" name="referral_code" value="' . esc($row->referral_code ?? $referral_code) . '">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="fw-bold mb-2 d-block" style="color:#1E293B; font-size:13.5px; line-height:1.4;">Referral Status</label>
                    <select name="status" class="form-select" style="font-weight:600; font-size:14px; line-height:1.5; min-height:48px; height:auto; padding:10px 14px; border-radius:10px; border:1.5px solid #CBD5E1; color:#0F172A; background-color:#FFFFFF;">
                        ' . $optionsHtml . '
                    </select>
                </div>
                <div class="col-12">
                    <label class="fw-bold mb-2 d-block" style="color:#1E293B; font-size:13.5px; line-height:1.4;">Admin Note</label>
                    <textarea name="admin_note" class="form-control" rows="3" style="font-size:13.5px; line-height:1.4; border-radius:10px; border:1.5px solid #CBD5E1;">' . esc($row->admin_note ?? '') . '</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0 pt-0 pb-4 px-4 gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-pill" data-bs-dismiss="modal" style="font-size:13px; font-weight:600; min-height:38px;">Cancel</button>
            <button type="submit" name="status_c" class="btn btn-sm text-white px-4 py-2 rounded-pill fw-bold" style="background:#0F3B68; font-size:13px; font-weight:700; min-height:38px; box-shadow:0 3px 8px rgba(15,59,104,0.3);">Update Status</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }


    public function edit_refferral_status_action()
    {
        $referral_code = trim((string)$this->request->getPost('referral_code'));

        if ($referral_code === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Referral code missing'
            ]);
        }

        $rawStatus = trim((string)$this->request->getPost('status'));
        $data = [
            'status'     => self::normalizeStatus($rawStatus),
            'admin_note' => $this->request->getPost('admin_note'),
        ];

        try {
            $db = \Config\Database::connect();
            $db->table('referrals')
                ->groupStart()
                    ->where('referral_code', $referral_code)
                    ->orWhere('referral_id', $referral_code)
                ->groupEnd()
                ->update($data);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => '✅ Referral status updated successfully!'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => '❌ Update failed: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_refferral($referral_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM referrals  WHERE  referral_code='" . esc($referral_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        // Safe query


        // Get active members
        $member = $commonModel->exe_query(
            "SELECT user_code, name FROM users WHERE status='1'"
        )->getResultArray();

        $row = $user[0]; // first row

        $fromOptions = '';
        $toOptions = '';
        foreach ($member as $m) {
            $selectedFrom = ($m['user_code'] == $row->from_user) ? 'selected' : '';
            $selectedTo = ($m['user_code'] == $row->to_user) ? 'selected' : '';
            $fromOptions .= '<option value="' . esc($m['user_code']) . '" ' . $selectedFrom . '>'
                . esc($m['name']) . ' (' . esc($m['user_code']) . ')</option>';
            $toOptions .= '<option value="' . esc($m['user_code']) . '" ' . $selectedTo . '>'
                . esc($m['name']) . ' (' . esc($m['user_code']) . ')</option>';
        }

        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="edit_refferral_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Refferral Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="referral_code" value="' . esc($row->referral_code) . '">
            
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="fw-bold">Referring Member</label>
                    <select name="from_user" class="form-select">
                        <option value="">-- Select Member --</option>
                        ' . $fromOptions . '
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Send Referral To</label>
                    <select name="to_user" class="form-select">
                        <option value="">-- Select Member --</option>
                        ' . $toOptions . '
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Business / Opportunity Name</label>
                    <input type="text" name="business" class="form-control" value="' . esc($row->business) . '">
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Contact</label>
                    <input type="text" name="contact_number" class="form-control" value="' . esc($row->contact_number) . '">
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Temperature</label>
                    <select class="form-select" name="temperature">
                        <option value="Hot" ' . ($row->temperature == 'Hot' ? 'selected' : '') . '>Hot</option>
                        <option value="Warm" ' . ($row->temperature == 'Warm' ? 'selected' : '') . '>Warm</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Referral Status</label>
                    <select class="form-select" name="status">
                        <option value="' . esc($row->status) . '">' . esc(ucfirst($row->status)) . '</option>
                        <option value="">Select Status</option>
                        <option value="pending">Pending</option>
                        <option value="contacted">Contacted</option>
                        <option value="closed business">Closed Business</option>
                        <option value="not converted">Not Converted</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="business_done">MITB</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Description / Details</label>
                    <textarea name="description" class="form-control">' . esc($row->description) . '</textarea>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Admin Note</label>
                    <textarea name="admin_note" class="form-control">' . esc($row->admin_note) . '</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-end border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 m-1 p-1" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white px-3 m-1 p-1" style="background-color:#1b4d8f;">Update</button>
        </div>
    </form>
</div>
';

        return $this->response->setBody($output);
    }

    public function edit_refferral_action()
    {
        $referral_code = $this->request->getPost('referral_code');

        if (!$referral_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Referral code missing'
            ]);
        }

        $data = [
            'from_user' => $this->request->getPost('from_user'),
            'to_user' => $this->request->getPost('to_user'),
            'business' => $this->request->getPost('business'),
            'contact_number' => $this->request->getPost('contact_number'),
            'temperature' => $this->request->getPost('temperature'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'admin_note' => $this->request->getPost('admin_note'),
        ];

        $model = new CommonModel();
        $update = $model->update_row(
            'referrals',
            $data,
            ['referral_code' => $referral_code]
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Referral updated successfully!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
    public function get_filtered_data()
    {
        $request = \Config\Services::request();

        $search = trim($request->getPost('search') ?? '');
        $status = trim($request->getPost('status') ?? '');

        $referralModel = new \App\Models\ReferralModel();

        $builder = $referralModel
            ->select("
            referrals.*,

            fu.name  AS from_name,
            fu.email AS from_email,
            fu.phone AS from_phone,
            fu.profile_photo AS from_profile,

            tu.name  AS to_name,
            tu.email AS to_email,
            tu.phone AS to_phone,
            tu.profile_photo AS to_profile
        ")
            ->join('users fu', 'fu.user_code = referrals.from_user', 'left')
            ->join('users tu', 'tu.user_code = referrals.to_user', 'left');

        /* =============================
       SEARCH FILTER
    ============================= */

        if ($search != '') {

            $builder->groupStart()
                ->like('referrals.from_user', $search)
                ->orLike('fu.name', $search)
                ->orLike('fu.email', $search)

                ->orLike('referrals.to_user', $search)
                ->orLike('tu.name', $search)
                ->orLike('tu.email', $search)

                ->orLike('referrals.business', $search)
                ->orLike('referrals.contact_number', $search)
                ->orLike('referrals.temperature', $search)
                ->orLike('referrals.description', $search)
                ->orLike('referrals.status', $search)
                ->groupEnd();
        }

        /* =============================
       STATUS FILTER
    ============================= */

        if ($status != '') {
            $builder->where('referrals.status', $status);
        }

        $builder->orderBy('referrals.referral_id', 'DESC');

        try {

            $chapters = $builder->findAll();
        } catch (\Exception $e) {

            echo '<div class="alert alert-danger text-center">'
                . esc($e->getMessage()) .
                '</div>';

            return;
        }

        if (empty($chapters)) {

            echo '<div class="alert alert-warning text-center">
                No referrals found.
              </div>';

            return;
        }

        $output = '';
        $i = 1;

        foreach ($chapters as $row) {

            $output .= view('include/admin/card/connect_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $this->response->setBody($output);
    }
}

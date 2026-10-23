<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\TyfcbModel;

class Mitb extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model       = new \App\Models\TyfcbModel();
        $commonModel = new \App\Models\CommonModel();
        $db          = \Config\Database::connect();

        /* ========= REQUEST VALUES ========= */

        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $search  = trim($this->request->getGet('search') ?? '');
        $status  = trim($this->request->getGet('status') ?? '');

        /* ========= QUERY BUILDER ========= */

        $builder = $model
            ->select("
            tyfcb.*, 

            from_user.name  as from_name,
            from_user.email as from_email,
            from_user.phone as from_phone,
            from_user.profile_photo as from_profile,

            to_user.name  as to_name,
            to_user.email as to_email,
            to_user.phone as to_phone,
            to_user.profile_photo as to_profile
        ")
            ->join('users as from_user', 'from_user.user_code = tyfcb.from_user', 'left')
            ->join('users as to_user', 'to_user.user_code = tyfcb.to_user', 'left');

        /* ========= SEARCH FILTER ========= */

        if ($search != '') {

            $builder->groupStart()
                ->like('from_user.name', $search)
                ->orLike('from_user.email', $search)

                ->orLike('to_user.name', $search)
                ->orLike('to_user.email', $search)

                ->orLike('tyfcb.mitb_code', $search)
                ->orLike('tyfcb.referral_code', $search)
                ->orLike('tyfcb.amount', $search)
                ->orLike('tyfcb.business_type', $search)
                ->groupEnd();
        }

        /* ========= STATUS FILTER ========= */

        if ($status != '') {
            $builder->where('tyfcb.status', $status);
        }

        /* ========= ORDER ========= */

        $builder->orderBy('tyfcb.mitb_id', 'DESC');

        /* ========= PAGINATION ========= */

        $data['connect'] = $builder->paginate($perPage);

        $data['pager']   = $model->pager;
        $data['perPage'] = $perPage;

        /* ========= MEMBERS ========= */

        $data['member'] = $commonModel
            ->exe_query("
            SELECT user_code,name 
            FROM users 
            WHERE status='1'
        ")
            ->getResultArray();

        /* ========= CHAPTERS ========= */

        $data['chapters'] = $commonModel
            ->exe_query("
            SELECT chapter_code,chapter_name 
            FROM chapters 
            WHERE chapter_status='1'
        ")
            ->getResultArray();

        /* ========= TOTAL REVENUE ========= */

        /* =========================================================
   TOTAL MITB / REVENUE
========================================================= */

        $totalRevenueRow = $db->table('tyfcb')
            ->select('COALESCE(SUM(amount), 0) AS total_amount', false)
            ->get()
            ->getRowArray();

        $totalRevenue = (float) ($totalRevenueRow['total_amount'] ?? 0);

        $data['totalRevenue'] = $totalRevenue;


        /* =========================================================
   ACTIVE MEMBER COUNT
   Only active chapter members
========================================================= */

        $memberCountRow = $db->table('chapter_members')
            ->select('COUNT(DISTINCT user_code) AS total_members', false)
            ->where('members_status', '1')
            ->get()
            ->getRowArray();

        $member_c = (int) ($memberCountRow['total_members'] ?? 0);

        $data['member_c'] = $member_c;


        /* =========================================================
   AVERAGE MEMBER VALUE
========================================================= */

        $data['avgMemberValue'] = ($member_c > 0)
            ? round($totalRevenue / $member_c)
            : 0;

        /* ========= DAILY REVENUE ========= */

        $data['dailyRevenue'] = $db->query("
        SELECT DATE(created_at) as day,
               SUM(amount) as total
        FROM tyfcb
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY day ASC
    ")->getResultArray();

        /* ========= MONTHLY REVENUE ========= */

        $data['monthlyRevenue'] = $db->query("
        SELECT DATE_FORMAT(created_at,'%Y-%m') as month,
               SUM(amount) as total
        FROM tyfcb
        GROUP BY month
        ORDER BY month ASC
    ")->getResultArray();

        /* ========= TOTAL RECORDS ========= */

        $data['totalRecords'] = $db->query("
        SELECT COUNT(*) as total
        FROM tyfcb
    ")->getRow()->total ?? 0;

        return view('admin/mitb', $data);
    }
    public function get_filtered_data()
    {
        try {

            $request = service('request');
            $db      = \Config\Database::connect();

            /* =====================================================
           FILTER VALUES
        ===================================================== */

            $search = trim((string) $request->getPost('search'));

            $category = trim(
                (string) $request->getPost('category')
            );

            $businessType = trim(
                (string) $request->getPost('business_type')
            );

            $chapter = trim(
                (string) $request->getPost('chapter_code')
            );

            $monthFilter = trim(
                (string) $request->getPost('month_filter')
            );

            $fromDate = trim(
                (string) $request->getPost('from_date')
            );

            $toDate = trim(
                (string) $request->getPost('to_date')
            );


            /* =====================================================
           BASE QUERY
        ===================================================== */

            $builder = $db->table('tyfcb t');


            /* =====================================================
           SELECT
        ===================================================== */

            $builder->select("
            t.*,

            fu.name AS from_name,
            fu.email AS from_email,
            fu.profile_photo AS from_profile,

            tu.name AS to_name,
            tu.email AS to_email,
            tu.profile_photo AS to_profile
        ");


            /* =====================================================
           USERS JOIN
        ===================================================== */

            $builder->join(
                'users fu',
                'fu.user_code = t.from_user',
                'left'
            );

            $builder->join(
                'users tu',
                'tu.user_code = t.to_user',
                'left'
            );


            /* =====================================================
           SEARCH
        ===================================================== */

            if ($search !== '') {

                $builder->groupStart()

                    ->like('fu.name', $search)

                    ->orLike('tu.name', $search)

                    ->orLike('fu.email', $search)

                    ->orLike('tu.email', $search)

                    ->orLike('t.category', $search)

                    ->orLike('t.business_type', $search)

                    ->orLike('t.description', $search)

                    ->orLike('CAST(t.amount AS CHAR)', $search)

                    ->orLike('t.from_user', $search)

                    ->orLike('t.to_user', $search)

                    ->groupEnd();
            }


            /* =====================================================
           CATEGORY
        ===================================================== */

            if ($category !== '') {

                $builder->where(
                    't.category',
                    $category
                );
            }


            /* =====================================================
           BUSINESS TYPE
        ===================================================== */

            if ($businessType !== '') {

                $builder->where(
                    't.business_type',
                    $businessType
                );
            }


            /* =====================================================
           CHAPTER FILTER
           
           IMPORTANT:
           TRIM handles accidental spaces in DB values.
        ===================================================== */

            if ($chapter !== '') {

                $builder->where(
                    'TRIM(t.chapter_code) =',
                    $chapter
                );
            }


            /* =====================================================
           DATE FILTER
           
           PRIORITY:
           
           1. From/To date
           2. Month filter
        ===================================================== */

            if ($fromDate !== '' || $toDate !== '') {

                /* -------------------------------------------------
               FROM DATE
            ------------------------------------------------- */

                if ($fromDate !== '') {

                    $builder->where(
                        't.created_at >=',
                        $fromDate . ' 00:00:00'
                    );
                }


                /* -------------------------------------------------
               TO DATE
            ------------------------------------------------- */

                if ($toDate !== '') {

                    $builder->where(
                        't.created_at <=',
                        $toDate . ' 23:59:59'
                    );
                }
            }

            /* =====================================================
           MONTH FILTER
        ===================================================== */ elseif ($monthFilter !== '') {

                $days = (int) $monthFilter;

                if ($days > 0) {

                    $startDate = date(
                        'Y-m-d H:i:s',
                        strtotime("-{$days} days")
                    );

                    $builder->where(
                        't.created_at >=',
                        $startDate
                    );
                }
            }


            /* =====================================================
           ORDER
        ===================================================== */

            $builder->orderBy(
                't.mitb_id',
                'DESC'
            );


            /* =====================================================
           EXECUTE QUERY
        ===================================================== */

            $query = $builder->get();

            $rows = $query->getResultArray();


            /* =====================================================
           NO RECORDS
        ===================================================== */

            if (empty($rows)) {

                echo '
                <tr>
                    <td colspan="8"
                        class="text-center text-muted py-4">

                        <i class="fa fa-info-circle me-1"></i>
                        No Records Found

                    </td>
                </tr>
            ';

                return;
            }


            /* =====================================================
           OUTPUT
        ===================================================== */

            $i = 1;

            foreach ($rows as $row) {

                echo view(
                    'include/admin/card/mitb_card',
                    [
                        'row' => $row,
                        'i'   => $i++
                    ]
                );
            }
        } catch (\Throwable $e) {

            log_message(
                'error',
                'MITB FILTER ERROR: ' . $e->getMessage()
            );


            echo '
            <tr>
                <td colspan="8"
                    class="text-danger text-center py-4">

                    <strong>
                        Error loading records
                    </strong>

                    <br>

                    <small>
                        ' . esc($e->getMessage()) . '
                    </small>

                </td>
            </tr>
        ';
        }
    }

    private function filteredQuery()
    {
        $request = service('request');
        $db = \Config\Database::connect();

        $builder = $db->table('tyfcb t');

        $builder->select("
        t.*,
        fu.name AS from_name,
        tu.name AS to_name
    ");

        $builder->join('users fu', 'fu.user_code=t.from_user', 'left');
        $builder->join('users tu', 'tu.user_code=t.to_user', 'left');

        $search       = $request->getGet('search');
        $category     = $request->getGet('category');
        $businessType = $request->getGet('business_type');
        $chapter      = $request->getGet('chapter_code');
        $fromDate     = $request->getGet('from_date');
        $toDate       = $request->getGet('to_date');
        $monthFilter  = $request->getGet('month_filter');

        if ($search) {
            $builder->groupStart()
                ->like('fu.name', $search)
                ->orLike('tu.name', $search)
                ->orLike('t.amount', $search)
                ->groupEnd();
        }

        if ($category) $builder->where('t.category', $category);
        if ($businessType) $builder->where('t.business_type', $businessType);
        if ($chapter) $builder->where('t.chapter_code', $chapter);
        if ($fromDate) $builder->where('DATE(t.created_at)>=', $fromDate);
        if ($toDate) $builder->where('DATE(t.created_at)<=', $toDate);

        if ($monthFilter) {
            $builder->where(
                't.created_at >=',
                date('Y-m-d', strtotime("-{$monthFilter} days"))
            );
        }

        return $builder->orderBy('t.mitb_id', 'DESC')->get()->getResultArray();
    }

    public function export_excel()
    {
        $rows = $this->filteredQuery();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=TYFCB_Report.xls");

        echo "<table border='1'>";
        echo "<tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Giver</th>
            <th>Receiver</th>
            <th>Category</th>
            <th>Business Type</th>
          </tr>";

        foreach ($rows as $r) {
            echo "<tr>
                <td>{$r['created_at']}</td>
                <td>{$r['amount']}</td>
                <td>{$r['from_name']}</td>
                <td>{$r['to_name']}</td>
                <td>{$r['category']}</td>
                <td>{$r['business_type']}</td>
              </tr>";
        }

        echo "</table>";
        exit;
    }
    public function export_pdf()
    {
        $rows = $this->filteredQuery();

        $html = view('pagers/pdf_export', ['rows' => $rows]);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("TYFCB_Report.pdf", ["Attachment" => 1]);
    }




    public function delete_mitb($mitb_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM tyfcb  WHERE mitb_code='" . esc($mitb_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_mitb_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="mitb_code" value="' . esc($row->mitb_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->amount) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->category) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->business_type) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Business ?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_mitb_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $mitb_code = $this->request->getPost('mitb_code');
        if (!$mitb_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("tyfcb", ['mitb_code' => $mitb_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Business removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }



    public function edit_mitb($mitb_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM tyfcb  WHERE  mitb_code='" . esc($mitb_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">Business not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        // Safe query
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="edit_mitb_action" method="post">
        <div class="modal-header" style="background-color:#1b4d8f; color:#fff; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0" style="font-size:15px;color:#ffffff;">Change Business Status</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="mitb_code" value="' . esc($row->mitb_code) . '">
            <div class="row g-2">
                <div class="col-md-12">
                    <label class="fw-bold">Amount</label>
                    <input type="text" name="amount" class="form-control" value="' . esc($row->amount) . '">
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Category</label>
                    <select class="form-select" name="category">
                        <option value="' . esc($row->category) . '">' . esc(ucfirst($row->category)) . '</option>
                        <option value="New">New</option>
                        <option value="Visitor">Visitor</option>
                    </select>
                </div>
                  <div class="col-md-12">
                    <label class="fw-bold">Business Type</label>
                    <select class="form-select" name="business_type">
                        <option value="' . esc($row->business_type) . '">' . esc(ucfirst($row->business_type)) . '</option>
                        <option value="New">New</option>
                        <option value="Repeat">Repeat</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Description</label>
                    <textarea name="description" class="form-control">' . esc($row->description) . '</textarea>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold">Admin Note</label>
                    <textarea name="note" class="form-control">' . esc($row->note) . '</textarea>
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

    public function edit_mitb_action()
    {
        $mitb_code = $this->request->getPost('mitb_code');
        if (!$mitb_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Referral code missing'
            ]);
        }
        $data = [
            'amount' => $this->request->getPost('amount'),
            'category' => $this->request->getPost('category'),
            'business_type' => $this->request->getPost('business_type'),
            'description' => $this->request->getPost('description'),
            'note' => $this->request->getPost('note'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'tyfcb',
            $data,
            ['mitb_code' => $mitb_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Business  updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
}

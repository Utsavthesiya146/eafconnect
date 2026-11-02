<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\TyfcbModel;

class Mitb extends BaseController
{

    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $model = new \App\Models\TyfcbModel();
        $commonModel = new CommonModel();

        $user_code = session()->get('user_code');

        // =========================================
        // FILTER VALUES
        // =========================================

        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $search       = trim((string) $this->request->getGet('search'));
        $category     = trim((string) $this->request->getGet('category'));
        $businessType = trim((string) $this->request->getGet('business_type'));
        $chapterCode  = trim((string) $this->request->getGet('chapter_code'));
        $duration     = (int) $this->request->getGet('month_filter');

        $fromDate = $this->request->getGet('from_date');
        $toDate   = $this->request->getGet('to_date');

        // =========================================
        // DATABASE COLUMN CONFIGURATION
        // Change these if your actual table uses
        // different column names.
        // =========================================

        $dateColumn = 'tyfcb.created_at';

        // =========================================
        // BASE QUERY
        // =========================================

        $baseQuery = function () use (
            $model,
            $search,
            $category,
            $businessType,
            $chapterCode,
            $duration,
            $fromDate,
            $toDate,
            $dateColumn
        ) {

            $builder = $model->select("
            tyfcb.*,

            from_user.name AS from_name,
            from_user.email AS from_email,
            from_user.profile_photo AS from_profile,

            to_user.name AS to_name,
            to_user.email AS to_email,
            to_user.profile_photo AS to_profile

        ")
                ->join(
                    'users AS from_user',
                    'from_user.user_code = tyfcb.from_user',
                    'left'
                )
                ->join(
                    'users AS to_user',
                    'to_user.user_code = tyfcb.to_user',
                    'left'
                );

            // SEARCH
            if ($search !== '') {

                $builder->groupStart()
                    ->like('from_user.name', $search)
                    ->orLike('to_user.name', $search)
                    ->orLike('tyfcb.mitb_code', $search)
                    ->orLike('tyfcb.business_type', $search)
                    ->groupEnd();
            }

            // CATEGORY
            if ($category !== '') {
                $builder->where('tyfcb.category', $category);
            }

            // BUSINESS TYPE
            if ($businessType !== '') {
                $builder->where('tyfcb.business_type', $businessType);
            }

            // CHAPTER
            // Includes records where either participant
            // belongs to the selected chapter.

            if ($chapterCode !== '') {

                $db = \Config\Database::connect();

                $escapedChapter = $db->escape($chapterCode);

                $chapterCondition = "
                (
                    tyfcb.from_user IN (
                        SELECT user_code
                        FROM chapter_members
                        WHERE chapter_code = {$escapedChapter}
                    )
                    OR
                    tyfcb.to_user IN (
                        SELECT user_code
                        FROM chapter_members
                        WHERE chapter_code = {$escapedChapter}
                    )
                )
            ";

                $builder->where($chapterCondition, null, false);
            }

            // DURATION
            if (in_array($duration, [7, 30, 180, 365])) {

                $startDate = date(
                    'Y-m-d',
                    strtotime("-{$duration} days")
                );

                $builder->where(
                    $dateColumn . ' >=',
                    $startDate . ' 00:00:00'
                );
            }

            // FROM DATE
            if (!empty($fromDate)) {

                $builder->where(
                    $dateColumn . ' >=',
                    $fromDate . ' 00:00:00'
                );
            }

            // TO DATE
            if (!empty($toDate)) {

                $builder->where(
                    $dateColumn . ' <=',
                    $toDate . ' 23:59:59'
                );
            }

            return $builder;
        };


        // =========================================
        // MITB GIVEN
        // from_user = logged-in member
        // =========================================

        $givenBuilder = $baseQuery();

        $givenBuilder
            ->where('tyfcb.from_user', $user_code)
            ->orderBy('tyfcb.mitb_id', 'DESC');

        $data['mitb_given'] = $givenBuilder->paginate(
            $perPage,
            'given'
        );

        $data['pager_given'] = $givenBuilder->pager;


        // =========================================
        // MITB RECEIVED
        // to_user = logged-in member
        // =========================================

        $receivedBuilder = $baseQuery();

        $receivedBuilder
            ->where('tyfcb.to_user', $user_code)
            ->orderBy('tyfcb.mitb_id', 'DESC');

        $data['mitb_received'] = $receivedBuilder->paginate(
            $perPage,
            'received'
        );

        $data['pager_received'] = $receivedBuilder->pager;


        // =========================================
        // TOTAL COUNTS
        // Overall counts, independent of filters
        // =========================================

        $data['givenCount'] = $model
            ->where('from_user', $user_code)
            ->countAllResults();

        $data['receivedCount'] = $model
            ->where('to_user', $user_code)
            ->countAllResults();


        // =========================================
        // BUSINESS STATISTICS
        // =========================================

        // Total business volume involving this member

        $revenueResult = $model
            ->selectSum('amount', 'total_amount')
            ->groupStart()
            ->where('from_user', $user_code)
            ->orWhere('to_user', $user_code)
            ->groupEnd()
            ->first();

        $data['totalRevenue'] = (float) (
            $revenueResult['total_amount'] ?? 0
        );


        // Total records involving this member

        $data['totalRecords'] = $model
            ->groupStart()
            ->where('from_user', $user_code)
            ->orWhere('to_user', $user_code)
            ->groupEnd()
            ->countAllResults();


        // Average member value

        $data['avgMemberValue'] = $data['totalRecords'] > 0
            ? $data['totalRevenue'] / $data['totalRecords']
            : 0;


        // =========================================
        // ADDITIONAL STATISTICS
        // =========================================

        $givenAmount = $model
            ->selectSum('amount', 'total_amount')
            ->where('from_user', $user_code)
            ->first();

        $receivedAmount = $model
            ->selectSum('amount', 'total_amount')
            ->where('to_user', $user_code)
            ->first();

        $data['totalGiven'] = (float) (
            $givenAmount['total_amount'] ?? 0
        );

        $data['totalReceived'] = (float) (
            $receivedAmount['total_amount'] ?? 0
        );


        // =========================================
        // CHAPTER LIST
        // =========================================

        $data['chapters'] = $commonModel
            ->exe_query("
            SELECT DISTINCT
                c.chapter_code,
                c.chapter_name
            FROM chapters AS c
            INNER JOIN chapter_members AS cm
                ON cm.chapter_code = c.chapter_code
            WHERE cm.user_code = " . $model->db->escape($user_code) . "
            ORDER BY c.chapter_name ASC
        ")
            ->getResultArray();







// =========================================
// MONTHLY BUSINESS AMOUNT + RECORD COUNTS
// =========================================

$chartYear = (int) ($this->request->getGet('chart_year') ?? date('Y'));

if ($chartYear < 2000 || $chartYear > (int) date('Y') + 1) {
    $chartYear = (int) date('Y');
}

$data['chartYear'] = $chartYear;

$db = \Config\Database::connect();

$monthlyRows = $db->query("
    SELECT
        MONTH(created_at) AS month_number,

        -- BUSINESS GIVEN AMOUNT
        SUM(
            CASE
                WHEN from_user = ? THEN COALESCE(amount, 0)
                ELSE 0
            END
        ) AS given_amount,

        -- BUSINESS RECEIVED AMOUNT
        SUM(
            CASE
                WHEN to_user = ? THEN COALESCE(amount, 0)
                ELSE 0
            END
        ) AS received_amount,

        -- BUSINESS GIVEN COUNT
        SUM(
            CASE
                WHEN from_user = ? THEN 1
                ELSE 0
            END
        ) AS given_count,

        -- BUSINESS RECEIVED COUNT
        SUM(
            CASE
                WHEN to_user = ? THEN 1
                ELSE 0
            END
        ) AS received_count

    FROM tyfcb

    WHERE
        (from_user = ? OR to_user = ?)
        AND created_at >= ?
        AND created_at < ?

    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at) ASC
", [
    $user_code,
    $user_code,
    $user_code,
    $user_code,
    $user_code,
    $user_code,
    $chartYear . '-01-01 00:00:00',
    ($chartYear + 1) . '-01-01 00:00:00'
])->getResultArray();

// MONTH LABELS
$monthLabels = [
    'Jan', 'Feb', 'Mar', 'Apr',
    'May', 'Jun', 'Jul', 'Aug',
    'Sep', 'Oct', 'Nov', 'Dec'
];

// INITIALIZE 12 MONTHS
$monthlyGiven = array_fill(0, 12, 0);
$monthlyReceived = array_fill(0, 12, 0);

$monthlyGivenCount = array_fill(0, 12, 0);
$monthlyReceivedCount = array_fill(0, 12, 0);

// FILL MONTHLY DATA
foreach ($monthlyRows as $row) {

    $monthIndex = (int) $row['month_number'] - 1;

    if ($monthIndex >= 0 && $monthIndex < 12) {

        $monthlyGiven[$monthIndex] = (float) $row['given_amount'];

        $monthlyReceived[$monthIndex] = (float) $row['received_amount'];

        $monthlyGivenCount[$monthIndex] = (int) $row['given_count'];

        $monthlyReceivedCount[$monthIndex] = (int) $row['received_count'];
    }
}

// CHART DATA
$data['monthlyChart'] = [
    'labels' => $monthLabels,

    'given' => $monthlyGiven,

    'received' => $monthlyReceived,

    'given_count' => $monthlyGivenCount,

    'received_count' => $monthlyReceivedCount
];

// CHART YEAR DROPDOWN
$data['chartYears'] = range(
    (int) date('Y') - 4,
    (int) date('Y')
);








            
        // =========================================
        // PRESERVE FILTER VALUES
        // =========================================

        $data['filters'] = [
            'search'        => $search,
            'category'      => $category,
            'business_type' => $businessType,
            'chapter_code'  => $chapterCode,
            'month_filter'  => $duration,
            'from_date'     => $fromDate,
            'to_date'       => $toDate,
            'perPage'       => $perPage
        ];


        return view('member/mitb', $data);
    }

    public function addNewmitbData()
    {
        helper(['form']);

        $session     = \Config\Services::session();
        $commonModel = new CommonModel();
        $user_code   = $session->get('user_code');
        $chapter_code   = $session->get('chapter_code');
        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired. Please login again.'
            ]);
        }

        $data = $this->request->getPost();

        // ✅ GENERATE CODE
        $mitb_code = $commonModel->generateSerial('tyfcb', 'mitb_code', 'MITB');

        // ✅ INSERT DATA
        $insert = [
            'mitb_code'     => $mitb_code,
            'from_user'     => (string)$user_code,
            'to_user'       => $data['to_user'] ?? null,
            'category'      => $data['category'] ?? null,
            'amount'        => $data['amount'] ?? null,
            'business_type' => $data['business_type'] ?? null,
            'description'   => $data['description'] ?? null,
            'status'        => 'new',
            'created_at'    => date('Y-m-d H:i:s'),
            'chapter_code'     => (string)$chapter_code,
        ];

        $insertId = $commonModel->insert_data('tyfcb', $insert);

        if (!$insertId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add MITB'
            ]);
        }

        if (!empty($insert['to_user'])) {
            // Generate Notification Code
            $n_code = $commonModel->generateSerial(
                'notifications',
                'n_code',
                'NOTI'
            );
            // Get Sender Details
            $fromUser = $commonModel->db->table('users')
                ->select('name')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();
            // Notification Insert Data
            $notification = [
                'n_code'       => $n_code,
                'user_code'    => $data['to_user'],
                'title'        => 'MITB Received',
                'message'      => ($fromUser->name ?? 'Someone') . ' sent you a new MITB.',
                'status'       => 'unread',
            ];
            // Insert Notification
            $commonModel->insert_data('notifications', $notification);
        }

        // ==============================
        // ✅ EMAIL SECTION
        // ==============================
        if (!empty($insert['to_user'])) {

            // 👉 RECEIVER
            $toUser = $commonModel->db->table('users')
                ->select('name,email')
                ->where('user_code', $insert['to_user'])
                ->get()
                ->getRow();

            // 👉 SENDER (SAFE - NO MOBILE ERROR)
            $fromUser = $commonModel->db->table('users')
                ->select('*')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();

            if ($toUser && !empty($toUser->email)) {

                $emailService = \Config\Services::email();

                $config = [
                    'protocol'   => 'smtp',
                    'SMTPHost'   => 'smtp.hostinger.com',
                    'SMTPUser'   => 'mail@eafconnect.com',
                    'SMTPPass'   => 'mail@WHB26',
                    'SMTPPort'   => 465,
                    'SMTPCrypto' => 'ssl',
                    'mailType'   => 'html',
                    'charset'    => 'utf-8',
                    'newline'    => "\r\n",
                    'SMTPTimeout' => 30,
                ];

                // ✅ INIT
                $emailService->initialize($config);
                $emailService->clear(true);

                // ✅ EMAIL HEADERS
                $emailService->setFrom('mail@eafconnect.com', 'eafconnect');
                $emailService->setTo($toUser->email);



                $emailService->setSubject('New MITB Request Received');

                // ✅ SAFE DATA PASS
                $message = view('mail/mitb', [
                    'name'        => $toUser->name ?? 'User',
                    'from_name'   => $fromUser->name ?? 'Member',
                    'contact'     => $fromUser->mobile ?? $fromUser->phone ?? 'N/A',
                    'business'    => $insert['business_type'] ?? '',
                    'amount'      => $insert['amount'] ?? '',
                    'description' => $insert['description'] ?? '',
                    'temperature' => $insert['category'] ?? ''
                ]);

                $emailService->setMessage($message);

                // ✅ SEND
                if (!$emailService->send()) {

                    // 🔥 DEBUG (remove after testing)
                    echo "<pre>";
                    print_r($emailService->printDebugger(['headers', 'subject', 'body']));
                    die;
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'MITB Added Successfully & Email Sent'
        ]);
    }
public function get_filtered_data()
{
    try {

        $request = service('request');
        $db = \Config\Database::connect();

        /* =========================================
           FILTER VALUES
        ========================================= */

        $search       = trim((string) $request->getPost('search'));
        $category     = $request->getPost('category');
        $businessType = $request->getPost('business_type');
        $chapter      = $request->getPost('chapter_code');
        $fromDate     = $request->getPost('from_date');
        $toDate       = $request->getPost('to_date');
        $monthFilter  = $request->getPost('month_filter');

        /* =========================================
           PAGINATION
        ========================================= */

        $perPage = (int) ($request->getPost('per_page') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $page = max(1, (int) ($request->getPost('page') ?? 1));

        /* =========================================
           BASE QUERY
        ========================================= */

        $builder = $db->table('tyfcb t');

        $builder->select("
            t.*,
            fu.name AS from_name,
            fu.email AS from_email,
            fu.profile_photo AS from_profile,
            tu.name AS to_name,
            tu.email AS to_email,
            tu.profile_photo AS to_profile
        ");

        $builder->join('users fu', 'fu.user_code = t.from_user', 'left');
        $builder->join('users tu', 'tu.user_code = t.to_user', 'left');

        /* =========================================
           SEARCH FILTER
        ========================================= */

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
                ->groupEnd();

        }

        /* =========================================
           DROPDOWN FILTERS
        ========================================= */

        if (!empty($category)) {
            $builder->where('t.category', $category);
        }

        if (!empty($businessType)) {
            $builder->where('t.business_type', $businessType);
        }

        if (!empty($chapter)) {
            $builder->where('t.chapter_code', $chapter);
        }

        /* =========================================
           DATE FILTER
           Custom date > Month filter
        ========================================= */

        if (!empty($fromDate) || !empty($toDate)) {

            if (!empty($fromDate)) {
                $builder->where(
                    't.created_at >=',
                    $fromDate . ' 00:00:00'
                );
            }

            if (!empty($toDate)) {
                $builder->where(
                    't.created_at <=',
                    $toDate . ' 23:59:59'
                );
            }

        } elseif (!empty($monthFilter)) {

            $days = (int) $monthFilter;

            if ($days > 0) {

                $builder->where(
                    't.created_at >=',
                    date('Y-m-d H:i:s', strtotime("-{$days} days"))
                );

            }
        }

        /* =========================================
           ORDER
        ========================================= */

        $builder->orderBy('t.mitb_id', 'DESC');

        /* =========================================
           TOTAL RECORD COUNT
        ========================================= */

        $countBuilder = clone $builder;

        $totalRows = $countBuilder->countAllResults();

        $totalPages = (int) ceil($totalRows / $perPage);

        /* =========================================
           VALIDATE CURRENT PAGE
        ========================================= */

        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        /* =========================================
           FETCH PAGINATED RECORDS
        ========================================= */

        $rows = $builder
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        /* =========================================
           NO RECORDS
        ========================================= */

        if (empty($rows)) {

            echo '
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2"></i>
                            <div>No Records Found</div>
                        </div>
                    </td>
                </tr>
            ';

            return;
        }

        /* =========================================
           DISPLAY RECORDS
        ========================================= */

        $i = $offset + 1;

        foreach ($rows as $row) {

            echo view('include/member/card/mitb_card', [
                'row' => $row,
                'i'   => $i++
            ]);

        }

        /* =========================================
           PAGINATION
        ========================================= */

        if ($totalPages > 1) {

            $startPage = max(1, $page - 2);
            $endPage   = min($totalPages, $page + 2);

            echo '<tr class="mitb-pagination-row">';
            echo '<td colspan="8">';
            echo '<div class="mitb-pagination-wrapper">';

            // Record information
            $startRecord = $offset + 1;
            $endRecord   = min($offset + $perPage, $totalRows);

            echo '<div class="mitb-pagination-info">';
            echo 'Showing <strong>' . $startRecord . '</strong> - ';
            echo '<strong>' . $endRecord . '</strong> of ';
            echo '<strong>' . $totalRows . '</strong> records';
            echo '</div>';

            echo '<ul class="mitb-pagination">';

            // Previous
            $prevPage = max(1, $page - 1);

            echo '<li>';
            echo '<a href="#" data-page="' . $prevPage . '" class="mitb-pagination-link ' . ($page <= 1 ? 'disabled' : '') . '">';
            echo '<i class="fas fa-chevron-left"></i>';
            echo '</a>';
            echo '</li>';

            // First page
            if ($startPage > 1) {

                echo '<li>';
                echo '<a href="#" data-page="1" class="mitb-pagination-link">1</a>';
                echo '</li>';

                if ($startPage > 2) {
                    echo '<li class="mitb-pagination-dots">...</li>';
                }

            }

            // Page numbers
            for ($p = $startPage; $p <= $endPage; $p++) {

                $activeClass = ($p == $page) ? 'active' : '';

                echo '<li>';
                echo '<a href="#" data-page="' . $p . '" class="mitb-pagination-link ' . $activeClass . '">';
                echo $p;
                echo '</a>';
                echo '</li>';

            }

            // Last page
            if ($endPage < $totalPages) {

                if ($endPage < $totalPages - 1) {
                    echo '<li class="mitb-pagination-dots">...</li>';
                }

                echo '<li>';
                echo '<a href="#" data-page="' . $totalPages . '" class="mitb-pagination-link">';
                echo $totalPages;
                echo '</a>';
                echo '</li>';

            }

            // Next
            $nextPage = min($totalPages, $page + 1);

            echo '<li>';
            echo '<a href="#" data-page="' . $nextPage . '" class="mitb-pagination-link ' . ($page >= $totalPages ? 'disabled' : '') . '">';
            echo '<i class="fas fa-chevron-right"></i>';
            echo '</a>';
            echo '</li>';

            echo '</ul>';

            echo '</div>';
            echo '</td>';
            echo '</tr>';

        }

    } catch (\Throwable $e) {

        log_message('error', 'MITB Filter Error: ' . $e->getMessage());

        echo '
            <tr>
                <td colspan="8" class="text-danger text-center py-3">
                    Unable to load records. Please try again.
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
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this MITB?</p>
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
                "message" => "✅ Business  removed successfully!"
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


    public function getMembers()
    {
        $db = \Config\Database::connect();

        $type      = $this->request->getPost('type');
        $user_code = session()->get('user_code');

        if ($type == 'chapter') {

            $qry = "
            SELECT u.user_code,u.name
            FROM users u
            INNER JOIN chapter_members cm
                ON cm.user_code = u.user_code
            WHERE cm.chapter_code = (
                SELECT chapter_code
                FROM chapter_members
                WHERE user_code = ?
                LIMIT 1
            )
            AND u.status='1'
            ORDER BY u.name ASC
        ";

            $members = $db->query($qry, [$user_code])->getResultArray();
        } else {

            $qry = "
            SELECT user_code,name
            FROM users
            WHERE status='1'
            ORDER BY name ASC
        ";

            $members = $db->query($qry)->getResultArray();
        }

        return $this->response->setJSON($members);
    }
}

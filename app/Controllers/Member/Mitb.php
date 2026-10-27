<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\TyfcbModel;

class Mitb extends BaseControlle
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }
        $model = new \App\Models\TyfcbModel();
        $commonModel = new CommonModel();
        $db = \Config\Database::connect();
        $user_code = session()->get('user_code');

        /* ========= REQUEST VALUES ========= */
        $perPage = (int)($this->request->getGet('perPage') ?? 10);
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');

        /* ========= CHAPTERS FOR DROPDOWN ========= */
        $data['chapters'] = $commonModel
            ->exe_query("SELECT chapter_code, chapter_name FROM chapters WHERE chapter_status='1' ORDER BY chapter_name ASC")
            ->getResultArray();

        /* ========= COUNTS & STATS ========= */
        $totalGivenRow = $model->selectSum('amount')->where('from_user', $user_code)->first();
        $totalGiven = (float)($totalGivenRow['amount'] ?? 0);

        $totalReceivedRow = $model->selectSum('amount')->where('to_user', $user_code)->first();
        $totalReceived = (float)($totalReceivedRow['amount'] ?? 0);

        $totalRevenue = $totalGiven + $totalReceived;

        $givenCount = $model->where('from_user', $user_code)->countAllResults();
        $receivedCount = $model->where('to_user', $user_code)->countAllResults();
        $totalRecords = $model
            ->groupStart()
                ->where('from_user', $user_code)
                ->orWhere('to_user', $user_code)
            ->groupEnd()
            ->countAllResults();

        $avgMemberValue = ($totalRecords > 0) ? ($totalRevenue / $totalRecords) : 0;

        $data['totalRevenue']    = $totalRevenue;
        $data['totalGiven']      = $totalGiven;
        $data['totalReceived']   = $totalReceived;
        $data['givenCount']      = $givenCount;
        $data['receivedCount']   = $receivedCount;
        $data['totalRecords']    = $totalRecords;
        $data['avgMemberValue']  = $avgMemberValue;

        /* ========= THIS MONTH VS LAST MONTH (MoM) ========= */
        $thisMonthStart = date('Y-m-01 00:00:00');
        $thisMonthEnd   = date('Y-m-t 23:59:59');
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $lastMonthEnd   = date('Y-m-t 23:59:59', strtotime('last day of last month'));

        $thisMonthRow = $db->query("
            SELECT 
                COALESCE(SUM(amount), 0) AS total,
                COALESCE(SUM(CASE WHEN from_user=? THEN amount ELSE 0 END), 0) AS given,
                COALESCE(SUM(CASE WHEN to_user=? THEN amount ELSE 0 END), 0) AS received,
                COUNT(*) as count
            FROM tyfcb 
            WHERE (from_user=? OR to_user=?) AND created_at BETWEEN ? AND ?
        ", [$user_code, $user_code, $user_code, $user_code, $thisMonthStart, $thisMonthEnd])->getRowArray();

        $lastMonthRow = $db->query("
            SELECT COALESCE(SUM(amount), 0) AS total 
            FROM tyfcb 
            WHERE (from_user=? OR to_user=?) AND created_at BETWEEN ? AND ?
        ", [$user_code, $user_code, $lastMonthStart, $lastMonthEnd])->getRowArray();

        $thisMonthRevenue = (float)($thisMonthRow['total'] ?? 0);
        $lastMonthRevenue = (float)($lastMonthRow['total'] ?? 0);
        $growthPercent    = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($thisMonthRevenue > 0 ? 100 : 0);

        $data['thisMonthRevenue'] = $thisMonthRevenue;
        $data['lastMonthRevenue'] = $lastMonthRevenue;
        $data['growthPercent']    = $growthPercent;
        $data['thisMonthCount']   = (int)($thisMonthRow['count'] ?? 0);

        /* ========= MONTH-BY-MONTH CONTRIBUTIONS BREAKDOWN ========= */
        $monthlyContributions = $db->query("
            SELECT 
                DATE_FORMAT(t.created_at, '%Y-%m') AS month_key,
                DATE_FORMAT(t.created_at, '%b %Y') AS month_label,
                DATE_FORMAT(t.created_at, '%M %Y') AS month_full_label,
                COALESCE(SUM(CASE WHEN t.from_user = ? THEN t.amount ELSE 0 END), 0) AS given_amount,
                COALESCE(SUM(CASE WHEN t.to_user = ? THEN t.amount ELSE 0 END), 0) AS received_amount,
                COALESCE(SUM(t.amount), 0) AS total_amount,
                COUNT(*) AS total_count,
                COALESCE(SUM(CASE WHEN t.from_user = ? THEN 1 ELSE 0 END), 0) AS given_count,
                COALESCE(SUM(CASE WHEN t.to_user = ? THEN 1 ELSE 0 END), 0) AS received_count
            FROM tyfcb t
            WHERE (t.from_user = ? OR t.to_user = ?)
            GROUP BY DATE_FORMAT(t.created_at, '%Y-%m')
            ORDER BY month_key DESC
        ", [$user_code, $user_code, $user_code, $user_code, $user_code, $user_code])->getResultArray();

        $data['monthlyContributions'] = $monthlyContributions;

        /* ========= CHART DATA (LAST 6 MONTHS CHRONOLOGICAL) ========= */
        $chartMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $mk = date('Y-m', strtotime("-$i months"));
            $chartMonths[$mk] = [
                'label'    => date('M Y', strtotime("-$i months")),
                'given'    => 0,
                'received' => 0,
                'total'    => 0
            ];
        }
        foreach ($monthlyContributions as $m) {
            if (isset($chartMonths[$m['month_key']])) {
                $chartMonths[$m['month_key']]['given']    = (float)$m['given_amount'];
                $chartMonths[$m['month_key']]['received'] = (float)$m['received_amount'];
                $chartMonths[$m['month_key']]['total']    = (float)$m['total_amount'];
            }
        }

        $data['chartCategories']     = array_values(array_column($chartMonths, 'label'));
        $data['chartGivenSeries']    = array_values(array_column($chartMonths, 'given'));
        $data['chartReceivedSeries'] = array_values(array_column($chartMonths, 'received'));
        $data['chartTotalSeries']    = array_values(array_column($chartMonths, 'total'));

        /* ========= UNIFIED ALL TRANSACTIONS (PAGINATED / INITIAL 50) ========= */
        $baseBuilder = $db->table('tyfcb t')
            ->select("
                t.*,
                fu.name AS from_name,
                fu.email AS from_email,
                fu.profile_photo AS from_profile,
                fu.user_code AS from_user_code,
                tu.name AS to_name,
                tu.email AS to_email,
                tu.profile_photo AS to_profile,
                tu.user_code AS to_user_code,
                c.chapter_name
            ")
            ->join('users fu', 'fu.user_code = t.from_user', 'left')
            ->join('users tu', 'tu.user_code = t.to_user', 'left')
            ->join('chapters c', 'c.chapter_code = t.chapter_code', 'left')
            ->groupStart()
                ->where('t.from_user', $user_code)
                ->orWhere('t.to_user', $user_code)
            ->groupEnd()
            ->orderBy('t.mitb_id', 'DESC');

        $data['all_records'] = $baseBuilder->get()->getResultArray();

        /* ========= SUB-TAB LISTS (GIVEN & RECEIVED) ========= */
        $givenBuilder = function () use ($model, $user_code) {
            return $model->select("
                tyfcb.*,
                from_user.name AS from_name,
                from_user.email AS from_email,
                from_user.profile_photo AS from_profile,
                from_user.user_code AS from_user_code,
                to_user.name AS to_name,
                to_user.email AS to_email,
                to_user.profile_photo AS to_profile,
                to_user.user_code AS to_user_code
            ")
            ->join('users AS from_user', 'from_user.user_code = tyfcb.from_user', 'left')
            ->join('users AS to_user', 'to_user.user_code = tyfcb.to_user', 'left')
            ->where('tyfcb.from_user', $user_code)
            ->orderBy('tyfcb.mitb_id', 'DESC');
        };

        $receivedBuilder = function () use ($model, $user_code) {
            return $model->select("
                tyfcb.*,
                from_user.name AS from_name,
                from_user.email AS from_email,
                from_user.profile_photo AS from_profile,
                from_user.user_code AS from_user_code,
                to_user.name AS to_name,
                to_user.email AS to_email,
                to_user.profile_photo AS to_profile,
                to_user.user_code AS to_user_code
            ")
            ->join('users AS from_user', 'from_user.user_code = tyfcb.from_user', 'left')
            ->join('users AS to_user', 'to_user.user_code = tyfcb.to_user', 'left')
            ->where('tyfcb.to_user', $user_code)
            ->orderBy('tyfcb.mitb_id', 'DESC');
        };

        $gQ = $givenBuilder();
        $data['mitb_given']  = $gQ->paginate($perPage, 'given');
        $data['pager_given'] = $gQ->pager;

        $rQ = $receivedBuilder();
        $data['mitb_received']  = $rQ->paginate($perPage, 'received');
        $data['pager_received'] = $rQ->pager;

        $data['member'] = $commonModel
            ->exe_query("
                SELECT 
                    u.user_code,
                    u.name,
                    u.email,
                    cm.chapter_code AS member_chapter_code,
                    c.chapter_name,
                    c.chapter_code
                FROM users AS u
                INNER JOIN chapter_members AS cm
                    ON cm.user_code = u.user_code
                INNER JOIN chapters AS c
                    ON c.chapter_code = cm.chapter_code
                WHERE u.status = '1'
            ")
            ->getResultArray();

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
            $fromUser = $commonModel->db->table('users')
                ->select('name')
                ->where('user_code', $user_code)
                ->get()
                ->getRow();

            $toUserObj = $commonModel->db->table('users')
                ->select('name')
                ->where('user_code', $insert['to_user'])
                ->get()
                ->getRow();

            $amtStr = !empty($insert['amount']) ? '₹' . number_format((float)$insert['amount']) : 'business';

            // Notify Receiver
            create_system_notification(
                $insert['to_user'],
                'MITB Received (' . $amtStr . ')',
                ($fromUser->name ?? 'A member') . ' recorded a Thank You Note (MITB) of ' . $amtStr . ' for you.'
            );

            // Notify Sender (Confirmation)
            create_system_notification(
                $user_code,
                'MITB Submitted',
                'You recorded a Thank You Note (MITB) of ' . $amtStr . ' for ' . ($toUserObj->name ?? 'member') . '.'
            );
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
            $session = \Config\Services::session();
            $user_code = $session->get('user_code');
            if (empty($user_code)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Session expired. Please login again.']);
            }

            $request = service('request');
            $db = \Config\Database::connect();

            /* ================= FILTER VALUES ================= */
            $search        = trim($request->getPost('search') ?? '');
            $category      = $request->getPost('category');
            $businessType  = $request->getPost('business_type');
            $chapter       = $request->getPost('chapter_code');
            $fromDate      = $request->getPost('from_date');
            $toDate        = $request->getPost('to_date');
            $monthFilter   = $request->getPost('month_filter');
            $specificMonth = $request->getPost('specific_month');
            $tab           = $request->getPost('tab') ?? 'all';
            $scope         = $request->getPost('scope') ?? 'my';

            /* ================= BASE QUERY ================= */
            $builder = $db->table('tyfcb t');

            $builder->select("
                t.*,
                fu.name AS from_name,
                fu.email AS from_email,
                fu.profile_photo AS from_profile,
                fu.user_code AS from_user_code,
                tu.name AS to_name,
                tu.email AS to_email,
                tu.profile_photo AS to_profile,
                tu.user_code AS to_user_code,
                c.chapter_name
            ");

            $builder->join('users fu', 'fu.user_code = t.from_user', 'left');
            $builder->join('users tu', 'tu.user_code = t.to_user', 'left');
            $builder->join('chapters c', 'c.chapter_code = t.chapter_code', 'left');

            /* ================= SCOPE & TAB FILTER ================= */
            if ($scope === 'chapter') {
                $userChapter = $session->get('chapter_code');
                if (!empty($userChapter)) {
                    $builder->where('t.chapter_code', $userChapter);
                }
            } else {
                if ($tab === 'received') {
                    $builder->where('t.to_user', $user_code);
                } elseif ($tab === 'given') {
                    $builder->where('t.from_user', $user_code);
                } else {
                    $builder->groupStart()
                        ->where('t.from_user', $user_code)
                        ->orWhere('t.to_user', $user_code)
                    ->groupEnd();
                }
            }

            /* ================= SEARCH ================= */
            if ($search !== '') {
                $builder->groupStart()
                    ->like('fu.name', $search)
                    ->orLike('tu.name', $search)
                    ->orLike('fu.email', $search)
                    ->orLike('tu.email', $search)
                    ->orLike('t.category', $search)
                    ->orLike('t.business_type', $search)
                    ->orLike('t.description', $search)
                    ->orLike('t.mitb_code', $search)
                    ->orLike('CAST(t.amount AS CHAR)', $search)
                    ->groupEnd();
            }

            /* ================= DROPDOWN FILTERS ================= */
            if (!empty($category)) {
                $builder->where('t.category', $category);
            }

            if (!empty($businessType)) {
                $builder->where('t.business_type', $businessType);
            }

            if (!empty($chapter)) {
                $builder->where('t.chapter_code', $chapter);
            }

            /* ================= DATE FILTERS ================= */
            if (!empty($specificMonth)) {
                $builder->where("DATE_FORMAT(t.created_at, '%Y-%m')", $specificMonth);
            } elseif (!empty($fromDate) || !empty($toDate)) {
                if (!empty($fromDate)) {
                    $builder->where('t.created_at >=', $fromDate . ' 00:00:00');
                }
                if (!empty($toDate)) {
                    $builder->where('t.created_at <=', $toDate . ' 23:59:59');
                }
            } elseif (!empty($monthFilter)) {
                $days = (int)$monthFilter;
                $builder->where(
                    't.created_at >=',
                    date('Y-m-d H:i:s', strtotime("-{$days} days"))
                );
            }

            /* ================= ORDER & FETCH ================= */
            $builder->orderBy('t.mitb_id', 'DESC');
            $rows = $builder->get()->getResultArray();

            /* ================= STATS CALCULATION ================= */
            $totalRevenue  = 0;
            $totalGiven    = 0;
            $totalReceived = 0;
            $givenCount    = 0;
            $receivedCount = 0;
            $totalRecords  = count($rows);

            foreach ($rows as $r) {
                $amt = (float)$r['amount'];
                $totalRevenue += $amt;
                if ($r['from_user'] == $user_code) {
                    $totalGiven += $amt;
                    $givenCount++;
                }
                if ($r['to_user'] == $user_code) {
                    $totalReceived += $amt;
                    $receivedCount++;
                }
            }

            $avgMemberValue = ($totalRecords > 0) ? round($totalRevenue / $totalRecords) : 0;

            /* ================= TABLE ROWS HTML ================= */
            $html = '';
            if (empty($rows)) {
                $html = '<tr><td colspan="9" class="text-center py-5">
                    <div class="empty-state-wrap text-center p-4">
                        <i class="ri-folder-open-line" style="font-size:42px; color:#94A3B8;"></i>
                        <h6 class="mt-2 text-muted fw-bold">No MITB Records Found</h6>
                        <p class="text-secondary small mb-0">Try clearing or adjusting your search & filters.</p>
                    </div>
                </td></tr>';
            } else {
                $i = 1;
                foreach ($rows as $row) {
                    $html .= view('include/member/card/mitb_card', [
                        'row' => $row,
                        'i'   => $i++,
                        'currentUserCode' => $user_code
                    ]);
                }
            }

            /* ================= MONTHLY SUMMARY MATRIX (TAB 4) ================= */
            $matrixQuery = $db->query("
                SELECT 
                    DATE_FORMAT(t.created_at, '%Y-%m') AS month_key,
                    DATE_FORMAT(t.created_at, '%b %Y') AS month_label,
                    DATE_FORMAT(t.created_at, '%M %Y') AS month_full_label,
                    COALESCE(SUM(CASE WHEN t.from_user = ? THEN t.amount ELSE 0 END), 0) AS given_amount,
                    COALESCE(SUM(CASE WHEN t.to_user = ? THEN t.amount ELSE 0 END), 0) AS received_amount,
                    COALESCE(SUM(t.amount), 0) AS total_amount,
                    COUNT(*) AS total_count,
                    COALESCE(SUM(CASE WHEN t.from_user = ? THEN 1 ELSE 0 END), 0) AS given_count,
                    COALESCE(SUM(CASE WHEN t.to_user = ? THEN 1 ELSE 0 END), 0) AS received_count
                FROM tyfcb t
                WHERE (t.from_user = ? OR t.to_user = ?)
                GROUP BY DATE_FORMAT(t.created_at, '%Y-%m')
                ORDER BY month_key DESC
            ", [$user_code, $user_code, $user_code, $user_code, $user_code, $user_code])->getResultArray();

            $matrixHtml = '';
            $monthlySummary = [];
            if (!empty($matrixQuery)) {
                $mIndex = 1;
                foreach ($matrixQuery as $mq) {
                    $monthlySummary[] = [
                        'month_key'      => $mq['month_key'],
                        'month_label'    => $mq['month_label'],
                        'given'          => (float)$mq['given_amount'],
                        'received'       => (float)$mq['received_amount'],
                        'total'          => (float)$mq['total_amount'],
                        'count'          => (int)$mq['total_count'],
                        'given_count'    => (int)$mq['given_count'],
                        'received_count' => (int)$mq['received_count']
                    ];

                    $matrixHtml .= '
                    <tr class="monthly-matrix-row" onclick="filterByMonth(\'' . $mq['month_key'] . '\')">
                        <td class="text-center fw-semibold text-muted">' . $mIndex++ . '</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="month-calendar-icon"><i class="ri-calendar-event-fill"></i></span>
                                <div>
                                    <span class="fw-bold text-dark d-block">' . esc($mq['month_full_label']) . '</span>
                                    <small class="text-muted">' . $mq['month_key'] . '</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-end fw-bold text-success" style="font-size:14px;">₹ ' . number_format($mq['received_amount'], 2) . '</td>
                        <td class="text-end fw-bold" style="color:#C6A667; font-size:14px;">₹ ' . number_format($mq['given_amount'], 2) . '</td>
                        <td class="text-end fw-bold" style="color:#101B35; font-size:15px;">₹ ' . number_format($mq['total_amount'], 2) . '</td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-light text-dark border px-2 py-1">
                                <i class="ri-file-list-3-line me-1 text-primary"></i>' . $mq['total_count'] . ' Slips
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill" onclick="event.stopPropagation(); filterByMonth(\'' . $mq['month_key'] . '\');">
                                <i class="ri-filter-3-line me-1"></i> View Transactions
                            </button>
                        </td>
                    </tr>';
                }
            } else {
                $matrixHtml = '<tr><td colspan="7" class="text-center py-4 text-muted">No monthly contribution records available</td></tr>';
            }

            // Chronological Chart Data for ApexCharts
            $chartMonths = [];
            for ($i = 5; $i >= 0; $i--) {
                $mk = date('Y-m', strtotime("-$i months"));
                $chartMonths[$mk] = [
                    'label'    => date('M Y', strtotime("-$i months")),
                    'given'    => 0,
                    'received' => 0,
                    'total'    => 0
                ];
            }
            foreach ($matrixQuery as $m) {
                if (isset($chartMonths[$m['month_key']])) {
                    $chartMonths[$m['month_key']]['given']    = (float)$m['given_amount'];
                    $chartMonths[$m['month_key']]['received'] = (float)$m['received_amount'];
                    $chartMonths[$m['month_key']]['total']    = (float)$m['total_amount'];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'stats' => [
                    'totalRevenue'            => $totalRevenue,
                    'totalRevenueFormatted'   => '₹ ' . number_format($totalRevenue, 2),
                    'totalGiven'              => $totalGiven,
                    'totalGivenFormatted'     => '₹ ' . number_format($totalGiven, 2),
                    'totalReceived'           => $totalReceived,
                    'totalReceivedFormatted'  => '₹ ' . number_format($totalReceived, 2),
                    'totalRecords'            => $totalRecords,
                    'givenCount'              => $givenCount,
                    'receivedCount'           => $receivedCount,
                    'avgMemberValueFormatted' => '₹ ' . number_format($avgMemberValue, 2)
                ],
                'chart' => [
                    'categories' => array_values(array_column($chartMonths, 'label')),
                    'given'      => array_values(array_column($chartMonths, 'given')),
                    'received'   => array_values(array_column($chartMonths, 'received')),
                    'total'      => array_values(array_column($chartMonths, 'total'))
                ],
                'monthlySummary' => $monthlySummary,
                'html'           => $html,
                'matrixHtml'     => $matrixHtml,
                'totalRecords'   => $totalRecords
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'html'    => '<tr><td colspan="9" class="text-danger text-center">Error: ' . esc($e->getMessage()) . '</td></tr>'
            ]);
        }
    }

    private function filteredQuery()
    {
        $session = \Config\Services::session();
        $user_code = $session->get('user_code');
        $request = service('request');
        $db = \Config\Database::connect();

        $builder = $db->table('tyfcb t');

        $builder->select("
            t.*,
            fu.name AS from_name,
            fu.email AS from_email,
            tu.name AS to_name,
            tu.email AS to_email,
            c.chapter_name
        ");

        $builder->join('users fu', 'fu.user_code=t.from_user', 'left');
        $builder->join('users tu', 'tu.user_code=t.to_user', 'left');
        $builder->join('chapters c', 'c.chapter_code=t.chapter_code', 'left');

        $builder->groupStart()
            ->where('t.from_user', $user_code)
            ->orWhere('t.to_user', $user_code)
        ->groupEnd();

        $search        = $request->getGet('search');
        $category      = $request->getGet('category');
        $businessType  = $request->getGet('business_type');
        $chapter       = $request->getGet('chapter_code');
        $fromDate      = $request->getGet('from_date');
        $toDate        = $request->getGet('to_date');
        $monthFilter   = $request->getGet('month_filter');
        $specificMonth = $request->getGet('specific_month');

        if ($search) {
            $builder->groupStart()
                ->like('fu.name', $search)
                ->orLike('tu.name', $search)
                ->orLike('t.amount', $search)
                ->orLike('t.mitb_code', $search)
            ->groupEnd();
        }

        if ($category) $builder->where('t.category', $category);
        if ($businessType) $builder->where('t.business_type', $businessType);
        if ($chapter) $builder->where('t.chapter_code', $chapter);
        if ($specificMonth) $builder->where("DATE_FORMAT(t.created_at, '%Y-%m')", $specificMonth);
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
        $db = \Config\Database::connect();
        $mitb = $db->table('tyfcb')->where('mitb_code', $mitb_code)->get()->getRow();

        $model = new CommonModel();
        $update = $model->update_row(
            'tyfcb',
            $data,
            ['mitb_code' => $mitb_code]
        );
        if ($update) {
            if ($mitb) {
                $currentUserId = session()->get('user_code');
                $currentUserName = session()->get('name') ?: 'A member';
                $amtStr = !empty($data['amount']) ? '₹' . number_format((float)$data['amount']) : 'business';
                $partnerCode = ($currentUserId == $mitb->from_user) ? $mitb->to_user : $mitb->from_user;
                if (!empty($partnerCode)) {
                    create_system_notification(
                        $partnerCode,
                        'MITB Details Updated',
                        $currentUserName . ' updated the Thank You Note details (' . $amtStr . ').'
                    );
                }
            }
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Business updated successfully!'
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

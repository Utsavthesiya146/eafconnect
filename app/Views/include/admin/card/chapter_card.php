<div class="col-md-3 d-flex">

    <?php

    $db = \Config\Database::connect();
    $commonModel = new \App\Models\CommonModel();
    $mitbModel   = new \App\Models\TyfcbModel();

    $currentChapter = $row['chapter_code'];

    /* =========================================
   GET MEMBER CODES OF THIS CHAPTER
========================================= */

    $memberCodes = $db->table('chapter_members')
        ->select('user_code')
        ->where('chapter_code', $currentChapter)
        ->get()
        ->getResultArray();

    $memberCodes = array_column($memberCodes, 'user_code');


    /* =========================================
   DEFAULT VALUES
========================================= */

    $f2f = 0;
    $connects = 0;
    $visitors = 0;
    $mitb = 0;


    /* =========================================
   CALCULATE ONLY IF MEMBERS EXIST
========================================= */

    if (!empty($memberCodes)) {

        /* ---------- F2F ---------- */
        $f2f = $db->table('one_to_ones')
            ->groupStart()
            ->whereIn('from_user', $memberCodes)
            ->orWhereIn('to_user', $memberCodes)
            ->groupEnd()
            ->countAllResults();


        /* ---------- CONNECTS ---------- */
        $connects = $db->table('referrals')
            ->groupStart()
            ->whereIn('from_user', $memberCodes)
            ->orWhereIn('to_user', $memberCodes)
            ->groupEnd()
            ->countAllResults();


        /* ---------- VISITORS ---------- */
        $visitors = $db->table('visitors')
            ->whereIn('user_code', $memberCodes)
            ->countAllResults();


        /* ---------- MITB ---------- */
        $mitbReceived = $mitbModel
            ->selectSum('amount')
            ->whereIn('from_user', $memberCodes)
            ->first()['amount'] ?? 0;

        $mitbGiven = $mitbModel
            ->selectSum('amount')
            ->whereIn('to_user', $memberCodes)
            ->first()['amount'] ?? 0;

        $mitb = $mitbGiven;
    }

    ?>
    <div class="chapter-card-pro">
        <!-- HEADER -->
        <div class="chapter-top">
            <div>
                <h4><?= esc($row['chapter_name']) ?></h4>

                <span class="location-badge">
                    <i class="bi bi-geo-alt-fill"></i>
                    <?= esc($row['location']) ?>
                </span>
            </div>
            <div class="card-menu">
                <?php
                $statusClass = '';
                $statusText  = '';
                $statusIcon  = '';
                if ($row['chapter_status'] == '1') {
                    $statusClass = 'status-active';
                    $statusText  = 'Active';
                    $statusIcon  = 'bi-check-circle-fill';
                } elseif ($row['chapter_status'] == '2') {
                    $statusClass = 'status-pending';
                    $statusText  = 'Hold';
                    $statusIcon  = 'bi-clock-fill';
                } else {
                    $statusClass = 'status-blocked';
                    $statusText  = 'Blocked';
                    $statusIcon  = 'bi-x-circle-fill';
                }
                ?>
                <span onclick="edit_chapter_status('<?= $row['chapter_code'] ?>');"
                    class="chapter-status <?= $statusClass ?>">
                    <i class="bi <?= $statusIcon ?>"></i>
                    <?= $statusText ?>
                </span>
                <span onclick="add_chapter_members('<?= $row['chapter_code'] ?>');"
                    class="action-btn view-btn" title="add">
                    <i class="fa fa-plus"></i>
                </span>
                <a href="<?= site_url('admin/chapter/details/' . $row['chapter_code']) ?>"
                    class="action-btn view-btn" title="details">
                    <i class="fa fa-eye"></i>
                </a>
                <span onclick="edit_chapter('<?= $row['chapter_code'] ?>');"
                    class="action-btn edit-btn" title="Edit">
                    <i class="fa fa-pen"></i>
                </span>
                <a href="javascript:void(0)"
                    onclick="delete_chapter('<?= $row['chapter_code'] ?>');"
                    class="action-btn delete-btn" title="Delete">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <!-- LEADER -->
        <div class="leader-section">
            <p class="section-label">CHAPTER LEADER</p>

            <div class="leader-box">
                <div class="leader-avatar">
                    <img src="<?= get_member_avatar($row['leader_photo'] ?? '', '', $row['leader_code'] ?? '') ?>"
                        class="leader-img"
                        style="object-fit:cover;object-position:top center;">
                </div>

                <div>
                    <strong>
                        <?= !empty($row['leader_name'])
                            ? esc($row['leader_name'])
                            : 'Not Assigned' ?>
                    </strong>
                    <small>Chapter Leader</small>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="chapter-stats">
            <div class="stat large">
                <span>Members</span>
                <h3><?= esc($row['member_count']) ?></h3>
            </div>

            <div class="stat large">
                <span>MITB</span>
                <h3>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($mitb) ?></h3>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-md-4 col-6">
                <div class="stat small">
                    <span>Ref </span>
                    <h5><?= $connects ?></h5>
                </div>
            </div>

            <div class="col-md-4 col-6">
                <div class="stat small">
                    <span>Vis</span>
                    <h5><?= $visitors ?></h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat small">
                    <span>F2F</span>
                    <h5><?= $f2f ?></h5>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm p-1 mb-2 mt-2" style="background-color: #0a3d61;">
            <div class="d-flex gap-1">
                <!-- ACTION -->
                <a href="<?= site_url('admin/chapter/details/' . $row['chapter_code']) ?>"
                    class="dashboard-btn" style="font-size:12px;">
                    <i class="bi bi-graph-up-arrow"></i>
                    View Performance
                </a>
                <a href="<?= base_url('admin/chapter/report/' . $row['chapter_code']) ?>"
                    class="btn btn-outline-light text-light btn-sm d-flex align-items-center gap-1 px-2 py-1" style="font-size:12px;">
                    <i class="bi bi-bar-chart-line"></i>
                    Report
                </a>

                <a href="<?= base_url('admin/chapter/download_pdf/' . $row['chapter_code']) ?>"
                    class="btn btn-danger btn-sm d-flex align-items-center gap-1 px-2 py-1" style="font-size:12px;">
                    <i class="bi bi-file-earmark-pdf"></i>
                    PDF
                </a>
            </div>
        </div>
    </div>
</div>
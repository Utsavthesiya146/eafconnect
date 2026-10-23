<?php
/* ======================================================
   LOAD MODELS
====================================================== */
$commonModel = new \App\Models\CommonModel();
$mitbModel   = new \App\Models\TyfcbModel();

$u = $row['user_code'];

/* ======================================================
   F2F COUNT (One to One Meetings)
====================================================== */
$f2fCount = $commonModel
    ->exe_query("SELECT f2f_id FROM one_to_ones WHERE from_user='$u'")
    ->getNumRows();

/* ======================================================
   CONNECTION COUNT (Referrals Given)
====================================================== */
$conCount = $commonModel
    ->exe_query("SELECT referral_id FROM referrals WHERE from_user='$u'")
    ->getNumRows();

/* ======================================================
   VISITORS COUNT
====================================================== */
$visCount = $commonModel
    ->exe_query("SELECT visitors_id  FROM visitors WHERE user_code='$u'")
    ->getNumRows();

/* ======================================================
   MITB TOTAL (Given + Received)
====================================================== */
$mitb = $mitbModel
    ->selectSum('amount')
    ->groupStart()
    // ->where('from_user', $u)
    ->Where('to_user', $u)
    ->groupEnd()
    ->first();

$mitbAmount = $mitb['amount'] ?? 0;
?>

<!-- =====================================================
     MEMBER CARD START
===================================================== -->

<div class="member-card">

    <a href="<?= base_url(); ?>admin/profile/details/<?= esc($row['user_code']) ?>">

        <!-- PROFILE -->
        <div class="member-top text-center position-relative">
            <?php 
            $avatarData = get_member_avatar($row['profile_photo'] ?? '', $row['gender'] ?? '', $row['user_code'] ?? '', true);
            ?>
            <img
                style="object-fit: cover; object-position: top center;"
                src="<?= esc($avatarData['url']) ?>"
                class="member-avatar">

            <?php if ($avatarData['is_ai']): ?>
                <div class="mt-1">
                    <span class="badge" style="background:rgba(198,166,103,0.18); color:#C6A667; font-size:10px; border:1px solid rgba(198,166,103,0.4); border-radius:50px; padding:2px 8px;">
                        <i class="ri-sparkling-fill me-1"></i>AI Avatar
                    </span>
                </div>
            <?php endif; ?>

            <h5 class="member-name mt-2"><?= esc($row['name']) ?></h5>

            <div class="chapter-badge">
                <i class="fa fa-building"></i>
                <?= esc($row['chapter_name']) ?>
            </div>

        </div>

        <!-- ROLE -->
        <div class="member-role-box">
            Role: <?= esc($row['member_role']) ?>
        </div>

        <!-- ================= STATS ================= -->
        <div class="member-stats">

            <!-- CONNECTION -->
            <div class="stat-box">
                <span>Ref</span>
                <strong><?= $conCount ?></strong>
            </div>

            <!-- MITB -->
            <div class="stat-box">
                <span>MITB</span>
                <strong>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($mitbAmount) ?></strong>
            </div>

            <!-- F2F -->
            <div class="stat-box">
                <span>F2F</span>
                <strong><?= $f2fCount ?></strong>
            </div>

            <!-- VISITORS -->
            <div class="stat-box">
                <span>VIS</span>
                <strong><?= $visCount ?></strong>
            </div>

        </div>
        <!-- CONTACT -->
        <div class="member-contact">
            <?php if (!empty($row['phone'])): ?>
                <i class="fa fa-phone"></i>
                <?= esc($row['phone']) ?>
            <?php else: ?>
                <i class="fa fa-phone"></i>
                Contact Not Provided
            <?php endif; ?>
        </div>
    </a>
    <div>
        <center>
            <a class="btn btn-info btn-sm me-1" style="background-color: #184b8d;"
                href="<?= base_url('admin/profile/details/' . $row['user_code']) ?>">
                <i class="fa fa-eye"></i>
            </a>
            <a class="btn btn-warning btn-sm me-1"
                href="<?= base_url('admin/members/edit_m/' . $row['user_code']) ?>" title="Edit">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger btn-sm" onclick="delete_chapter_member('<?= $row['user_code'] ?>');" title="Delete">
                <i class="fa fa-trash"></i>
            </a>
        </center>
    </div>
</div>
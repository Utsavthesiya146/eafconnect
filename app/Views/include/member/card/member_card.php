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
    ->orWhere('to_user', $u)
    ->groupEnd()
    ->first();

$mitbAmount = $mitb['amount'] ?? 0;
?>

<!-- =====================================================
     MEMBER CARD START
===================================================== -->

<div class="member-card">
    <a href="<?= base_url(); ?>member/profile/details/<?= esc($row['user_code']) ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%; justify-content: space-between;">
        <!-- PROFILE -->
        <div class="member-top text-center position-relative">
            <?php 
            $avatarData = get_member_avatar($row['profile_photo'] ?? '', $row['gender'] ?? '', $row['user_code'] ?? '', true);
            ?>
            <img
                style="object-fit: cover; object-position: top center;"
                src="<?= esc($avatarData['url']) ?>"
                class="member-avatar"
                onerror="this.onerror=null; this.src='<?= base_url('images/empty.png'); ?>';">

            <?php if ($avatarData['is_ai']): ?>
                <div class="mt-1">
                    <span class="badge" style="background:rgba(198,166,103,0.18); color:#C6A667; font-size:10px; border:1px solid rgba(198,166,103,0.4); border-radius:50px; padding:2px 8px;">
                        <i class="ri-sparkling-fill me-1"></i>AI Avatar
                    </span>
                </div>
            <?php endif; ?>

            <h5 class="member-name mt-2" title="<?= esc($row['name']) ?>"><?= esc($row['name']) ?></h5>
            
            <div class="chapter-badge">
                <i class="ri-building-line me-1"></i>
                <?= esc(!empty($row['chapter_name']) ? $row['chapter_name'] : (!empty($row['company_name']) ? $row['company_name'] : 'EAF Member')) ?>
            </div>
        </div>

        <!-- ROLE & INDUSTRY -->
        <div class="member-role-box text-center">
            <div style="font-weight: 700; color: #101B35; font-size: 12px; margin-bottom: 2px;">
                <?= esc($row['industry'] ?? 'Business Professional') ?>
            </div>
            <div style="font-size: 11px; color: #64748B;">
                Role: <span style="font-weight: 600; color: #172B50;"><?= esc($row['member_role'] ?? 'Member') ?></span>
            </div>
        </div>

        <!-- ================= STATS ================= -->
        <div class="member-stats">
            <!-- CONNECTION -->
            <div class="stat-box">
                <span>CON</span>
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
                <i class="ri-phone-line"></i>
                <span><?= esc($row['phone']) ?></span>
            <?php else: ?>
                <i class="ri-phone-line" style="opacity: 0.5;"></i>
                <span style="opacity: 0.7;">Contact Not Provided</span>
            <?php endif; ?>
        </div>
    </a>
</div>
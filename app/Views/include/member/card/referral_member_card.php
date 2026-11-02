<?php
$members = $members ?? [];
$currentUserCode = (string) ($currentUserCode ?? '');
?>

<?php if (!empty($members)): ?>

    <?php foreach ($members as $row): ?>

        <?php
        $memberCode = (string) ($row['user_code'] ?? '');
        $memberName = trim((string) ($row['name'] ?? 'Member'));
        $isSelf = ($memberCode === $currentUserCode);

        $photo = trim((string) ($row['profile_photo'] ?? ''));

        if ($photo === '') {
            $photo = 'empty.png';
        }

        $photoUrl = base_url('uploads/profile/' . $photo);
        ?>

        <article class="referral-member-card">

            <div class="referral-member-top">

                <img
                    src="<?= esc($photoUrl) ?>"
                    class="referral-member-avatar"
                    alt="<?= esc($memberName) ?>"
                    onerror="this.onerror=null;this.src='<?= esc(base_url('uploads/profile/empty.png')) ?>';"
                >

                <div class="referral-member-details">

                    <div class="referral-member-name">
                        <?= esc($memberName) ?>

                        <?php if ($isSelf): ?>
                            <span class="referral-self-badge">You</span>
                        <?php endif; ?>
                    </div>

                    <div class="referral-member-company">
                        <i class="bi bi-briefcase"></i>
                        <?= esc(trim((string) ($row['company_name'] ?? '')) ?: 'Business details not available') ?>
                    </div>

                    <div class="referral-member-industry">
                        <i class="bi bi-grid"></i>
                        <?= esc(trim((string) ($row['industry'] ?? '')) ?: 'Industry not specified') ?>
                    </div>

                </div>

            </div>

            <div class="referral-member-meta">

                <?php if (!empty($row['chapter_name'])): ?>
                    <div class="referral-meta-item">
                        <i class="bi bi-people"></i>
                        <span><?= esc($row['chapter_name']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($row['phone'])): ?>
                    <div class="referral-meta-item">
                        <i class="bi bi-telephone"></i>
                        <span><?= esc($row['phone']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($row['email'])): ?>
                    <div class="referral-meta-item referral-email">
                        <i class="bi bi-envelope"></i>
                        <span><?= esc($row['email']) ?></span>
                    </div>
                <?php endif; ?>

            </div>

            <div class="referral-member-footer">

                <?php if ($isSelf): ?>

                    <button type="button" class="referral-selected-btn" disabled>
                        <i class="bi bi-person-check"></i>
                        Your profile
                    </button>

                <?php else: ?>

                    <button
                        type="button"
                        class="referral-select-member"
                        data-user-code="<?= esc($memberCode) ?>"
                        data-name="<?= esc($memberName) ?>"
                    >
                        <span>Select this member</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                <?php endif; ?>

            </div>

        </article>

    <?php endforeach; ?>

<?php else: ?>

    <div class="referral-empty-state">
        <i class="bi bi-people"></i>
        <h6>No Members Found</h6>
        <p>Try changing your search or filter options.</p>
    </div>

<?php endif; ?>
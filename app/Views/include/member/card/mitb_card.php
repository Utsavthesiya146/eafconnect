
<?php
$myUserCode = $currentUserCode ?? (session()->get('user_code') ?? '');

$isReceived = ((string)($row['to_user'] ?? '') === (string)$myUserCode);
$isGiver    = ((string)($row['from_user'] ?? '') === (string)$myUserCode);

$createdAt = !empty($row['created_at'])
    ? strtotime($row['created_at'])
    : time();

$amountFormatted = (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
    ->format((float)($row['amount'] ?? 0), 2);

$bType = strtolower($row['business_type'] ?? 'new');
$isRepeat = str_contains($bType, 'repeat');

$fromName = $row['from_name'] ?? 'N/A';
$toName   = $row['to_name'] ?? 'N/A';

$description = $row['description'] ?? '';
$mitbCode = $row['mitb_code'] ?? '';
?>

<tr class="mitb-table-row">

    <!-- SERIAL NUMBER -->
    <td class="text-center align-middle">
        <span class="mitb-idx-badge">
            <?= esc($i) ?>
        </span>
    </td>

    <!-- DATE -->
    <td class="align-middle">
        <div class="mitb-date-cell">

            <div class="mitb-date-icon">
                <i class="ri-calendar-event-line"></i>
            </div>

            <div class="mitb-date-content">
                <span class="mitb-date-val">
                    <?= date('d M Y', $createdAt) ?>
                </span>

                <small class="mitb-time-val">
                    <i class="ri-time-line"></i>
                    <?= date('h:i A', $createdAt) ?>
                </small>
            </div>

        </div>
    </td>

    <!-- AMOUNT -->
    <td class="align-middle">

        <div class="mitb-amount-cell">

            <span class="mitb-amount-number <?= $isReceived ? 'amount-received' : 'amount-given' ?>">
                <small>₹</small><?= $amountFormatted ?>
            </span>

            <span class="mitb-direction-badge <?= $isReceived ? 'badge-inbound' : 'badge-outbound' ?>">

                <i class="<?= $isReceived ? 'ri-arrow-down-line' : 'ri-arrow-up-line' ?>"></i>

                <?= $isReceived ? 'Received' : 'Given' ?>

            </span>

        </div>

    </td>

    <!-- GIVEN BY -->
    <td class="align-middle">

        <div class="mitb-member-cell">

            <div class="mitb-avatar-wrap">

                <img
                    src="<?= esc(get_member_avatar(
                        $row['from_profile'] ?? '',
                        '',
                        $row['from_user_code'] ?? ($row['from_user'] ?? '')
                    )) ?>"
                    class="mitb-table-avatar avatar-from"
                    alt="<?= esc($fromName) ?>">

                <?php if ($isGiver): ?>
                    <span class="mitb-you-tag">YOU</span>
                <?php endif; ?>

            </div>

            <div class="mitb-member-info">

                <span class="mitb-member-name">
                    <?= esc($fromName) ?>
                </span>

                <small class="mitb-member-email">
                    <?= esc($row['from_email'] ?? 'No email') ?>
                </small>

            </div>

        </div>

    </td>

    <!-- FLOW -->
    <td class="text-center align-middle">

        <div class="mitb-flow-indicator">
            <i class="ri-arrow-right-line"></i>
        </div>

    </td>

    <!-- RECEIVED BY -->
    <td class="align-middle">

        <div class="mitb-member-cell">

            <div class="mitb-avatar-wrap">

                <img
                    src="<?= esc(get_member_avatar(
                        $row['to_profile'] ?? '',
                        '',
                        $row['to_user_code'] ?? ($row['to_user'] ?? '')
                    )) ?>"
                    class="mitb-table-avatar avatar-to"
                    alt="<?= esc($toName) ?>">

                <?php if ($isReceived): ?>
                    <span class="mitb-you-tag tag-gold">YOU</span>
                <?php endif; ?>

            </div>

            <div class="mitb-member-info">

                <span class="mitb-member-name">
                    <?= esc($toName) ?>
                </span>

                <small class="mitb-member-email">
                    <?= esc($row['to_email'] ?? 'No email') ?>
                </small>

            </div>

        </div>

    </td>

    <!-- CATEGORY -->
    <td class="text-center align-middle">

        <span class="mitb-chip chip-category">
            <i class="ri-price-tag-3-line"></i>
            <?= esc($row['category'] ?? 'General') ?>
        </span>

    </td>

    <!-- BUSINESS TYPE -->
    <td class="text-center align-middle">

        <span class="mitb-chip <?= $isRepeat ? 'chip-repeat' : 'chip-new' ?>">

            <i class="<?= $isRepeat ? 'ri-repeat-2-line' : 'ri-sparkling-fill' ?>"></i>

            <?= esc(ucfirst($row['business_type'] ?? 'New')) ?>

        </span>

    </td>

    <!-- ACTIONS -->
    <td class="text-center align-middle">

        <div class="mitb-action-group">

            <?php if (!empty($description)): ?>

                <button
                    type="button"
                    class="mitb-action-icon btn-view"
                    title="View Description"
                    onclick='showMitbDesc(
                        <?= json_encode($mitbCode) ?>,
                        <?= json_encode($description) ?>,
                        <?= json_encode($amountFormatted) ?>,
                        <?= json_encode($fromName) ?>,
                        <?= json_encode($toName) ?>
                    )'>

                    <i class="ri-eye-line"></i>

                </button>

            <?php endif; ?>

            <?php if ($isGiver): ?>

                <button
                    type="button"
                    class="mitb-action-icon btn-edit"
                    title="Edit MITB"
                    onclick='edit_mitb(<?= json_encode($mitbCode) ?>)'>

                    <i class="ri-pencil-line"></i>

                </button>

                <button
                    type="button"
                    class="mitb-action-icon btn-del"
                    title="Delete MITB"
                    onclick='delete_mitb(<?= json_encode($mitbCode) ?>)'>

                    <i class="ri-delete-bin-line"></i>

                </button>

            <?php else: ?>

                <span class="mitb-verified" title="Verified Received">
                    <i class="ri-shield-check-fill"></i>
                </span>

            <?php endif; ?>

        </div>

    </td>

</tr>
<style>
/* ==========================================
   MITB MODERN TABLE
========================================== */

.modern-table-wrapper {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 14px;
}

.modern-table {
    width: 100%;
    min-width: 1150px;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
}

.modern-table thead th {
    background: #f5f8fc;
    color: #64748b;
    padding: 14px 13px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .55px;
    white-space: nowrap;
    border-bottom: 1px solid #e8edf4;
}

.modern-table tbody td {
    padding: 13px 12px;
    border-bottom: 1px solid #edf1f6;
    background: #fff;
    vertical-align: middle;
}

.modern-table tbody tr:last-child td {
    border-bottom: none;
}

.mitb-table-row {
    transition: background .2s ease;
}

.mitb-table-row:hover td {
    background: #f8fbff;
}

/* SERIAL */

.mitb-idx-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border-radius: 9px;
    background: #f0f4fa;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
}

/* DATE */

.mitb-date-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 125px;
}

.mitb-date-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 10px;
    background: #edf4ff;
    color: #3674cf;
    font-size: 17px;
}

.mitb-date-content {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.mitb-date-val {
    color: #26344d;
    font-size: 12px;
    font-weight: 750;
    white-space: nowrap;
}

.mitb-time-val {
    color: #94a3b8;
    font-size: 10px;
    font-weight: 600;
}

.mitb-time-val i {
    margin-right: 3px;
}

/* AMOUNT */

.mitb-amount-cell {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
    min-width: 115px;
}

.mitb-amount-number {
    font-size: 15px;
    font-weight: 850;
    white-space: nowrap;
    letter-spacing: -.3px;
}

.mitb-amount-number small {
    font-size: 12px;
    margin-right: 2px;
}

.amount-received {
    color: #087f5b;
}

.amount-given {
    color: #dc4771;
}

/* DIRECTION BADGE */

.mitb-direction-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.badge-inbound {
    background: #e9f8f1;
    color: #087f5b;
}

.badge-outbound {
    background: #fff0f3;
    color: #dc4771;
}

/* MEMBER */

.mitb-member-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 160px;
}

.mitb-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}

.mitb-table-avatar {
    width: 39px;
    height: 39px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 7px rgba(15, 23, 42, .10);
}

.avatar-from {
    background: #eaf2ff;
}

.avatar-to {
    background: #fff3e6;
}

.mitb-member-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
    gap: 4px;
}

.mitb-member-name {
    color: #26344d;
    font-size: 12px;
    font-weight: 750;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.mitb-member-email {
    color: #98a2b3;
    font-size: 10px;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* YOU TAG */

.mitb-you-tag {
    position: absolute;
    bottom: -5px;
    right: -8px;
    padding: 2px 5px;
    border-radius: 5px;
    background: #087f5b;
    color: #fff;
    font-size: 8px;
    font-weight: 850;
    border: 1px solid #fff;
}

.mitb-you-tag.tag-gold {
    background: #e5a900;
    color: #fff;
}

/* FLOW */

.mitb-flow-indicator {
    width: 29px;
    height: 29px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f1f5f9;
    color: #8b9bb0;
    font-size: 16px;
    transition: .2s ease;
}

.mitb-table-row:hover .mitb-flow-indicator {
    background: #e5efff;
    color: #3674cf;
}

/* CATEGORY & TYPE */

.mitb-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.chip-category {
    background: #f1f4f9;
    color: #52647c;
}

.chip-new {
    background: #eaf2ff;
    color: #3475cb;
}

.chip-repeat {
    background: #fff4df;
    color: #c18412;
}

/* ACTIONS */

.mitb-action-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.mitb-action-icon {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    border-radius: 9px;
    cursor: pointer;
    font-size: 15px;
    transition: all .2s ease;
}

.btn-view {
    background: #edf4ff;
    color: #3475cb;
}

.btn-edit {
    background: #eaf8f1;
    color: #087f5b;
}

.btn-del {
    background: #fff0f0;
    color: #e34e5b;
}

.btn-view:hover {
    background: #3475cb;
    color: #fff;
}

.btn-edit:hover {
    background: #087f5b;
    color: #fff;
}

.btn-del:hover {
    background: #e34e5b;
    color: #fff;
}

.mitb-verified {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: #e9f8f1;
    color: #087f5b;
    font-size: 17px;
}

/* MOBILE */

@media (max-width: 768px) {

    .modern-table {
        min-width: 1100px;
    }

    .modern-table tbody td {
        padding: 11px 9px;
    }

    .mitb-table-avatar {
        width: 35px;
        height: 35px;
    }

}
</style>
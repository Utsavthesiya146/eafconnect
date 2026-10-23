<?php
$myUserCode = $currentUserCode ?? (session()->get('user_code') ?? '');
$isReceived = ((string)($row['to_user'] ?? '') === (string)$myUserCode);
$isGiver    = ((string)($row['from_user'] ?? '') === (string)$myUserCode);
$createdAt  = !empty($row['created_at']) ? strtotime($row['created_at']) : time();
$amountFormatted = (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['amount'] ?? 0, 2);
?>
<tr class="mitb-table-row">
    <!-- INDEX -->
    <td class="text-center align-middle">
        <span class="mitb-idx-badge"><?= $i ?></span>
    </td>

    <!-- DATE & TIME -->
    <td class="align-middle">
        <div class="mitb-date-cell">
            <span class="mitb-date-val">
                <i class="ri-calendar-line text-primary me-1"></i><?= date('d M Y', $createdAt) ?>
            </span>
            <small class="mitb-time-val text-muted">
                <i class="ri-time-line me-1"></i><?= date('h:i A', $createdAt) ?>
            </small>
        </div>
    </td>

    <!-- AMOUNT WITH INBOUND / OUTBOUND STATUS -->
    <td class="align-middle">
        <div class="mitb-amount-cell">
            <span class="mitb-amount-number <?= $isReceived ? 'amount-received' : 'amount-given' ?>">
                ₹ <?= $amountFormatted ?>
            </span>
            <?php if ($isReceived) : ?>
                <span class="mitb-direction-badge badge-inbound" title="Inbound Business Received">
                    <i class="ri-arrow-down-line"></i> Received
                </span>
            <?php else : ?>
                <span class="mitb-direction-badge badge-outbound" title="Outbound Business Given">
                    <i class="ri-arrow-up-line"></i> Given
                </span>
            <?php endif; ?>
        </div>
    </td>

    <!-- GIVEN BY (FROM MEMBER) -->
    <td class="align-middle">
        <div class="mitb-member-cell">
            <div class="mitb-avatar-wrap">
                <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? ($row['from_user'] ?? '')) ?>" 
                     class="mitb-table-avatar avatar-from" 
                     alt="<?= esc($row['from_name'] ?? 'Member') ?>">
                <?php if ($isGiver) : ?>
                    <span class="mitb-you-tag">You</span>
                <?php endif; ?>
            </div>
            <div class="mitb-member-info">
                <span class="mitb-member-name"><?= esc($row['from_name'] ?? 'N/A') ?></span>
                <small class="mitb-member-email text-muted"><?= esc($row['from_email'] ?? '') ?></small>
            </div>
        </div>
    </td>

    <!-- RELATIONSHIP FLOW ARROW -->
    <td class="text-center align-middle">
        <div class="mitb-flow-indicator" title="Closed business flow">
            <i class="ri-arrow-right-line"></i>
        </div>
    </td>

    <!-- RECEIVED BY (TO MEMBER) -->
    <td class="align-middle">
        <div class="mitb-member-cell">
            <div class="mitb-avatar-wrap">
                <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? ($row['to_user'] ?? '')) ?>" 
                     class="mitb-table-avatar avatar-to" 
                     alt="<?= esc($row['to_name'] ?? 'Member') ?>">
                <?php if ($isReceived) : ?>
                    <span class="mitb-you-tag tag-gold">You</span>
                <?php endif; ?>
            </div>
            <div class="mitb-member-info">
                <span class="mitb-member-name"><?= esc($row['to_name'] ?? 'N/A') ?></span>
                <small class="mitb-member-email text-muted"><?= esc($row['to_email'] ?? '') ?></small>
            </div>
        </div>
    </td>

    <!-- CATEGORY -->
    <td class="text-center align-middle">
        <span class="mitb-chip chip-category">
            <i class="ri-price-tag-3-line me-1"></i><?= esc($row['category'] ?? 'General') ?>
        </span>
    </td>

    <!-- BUSINESS TYPE -->
    <td class="text-center align-middle">
        <?php $bType = strtolower($row['business_type'] ?? 'new'); ?>
        <span class="mitb-chip <?= str_contains($bType, 'repeat') ? 'chip-repeat' : 'chip-new' ?>">
            <i class="<?= str_contains($bType, 'repeat') ? 'ri-repeat-2-line' : 'ri-sparkling-fill' ?> me-1"></i>
            <?= esc(ucfirst($row['business_type'] ?? 'New')) ?>
        </span>
    </td>

    <!-- ACTIONS -->
    <td class="text-center align-middle">
        <div class="mitb-action-group">
            <?php if (!empty($row['description'])) : ?>
                <button type="button" 
                        class="mitb-action-icon btn-view" 
                        title="View Description"
                        onclick="showMitbDesc('<?= esc($row['mitb_code']) ?>', '<?= esc(addslashes($row['description'])) ?>', '<?= esc($amountFormatted) ?>', '<?= esc($row['from_name']) ?>', '<?= esc($row['to_name']) ?>');">
                    <i class="ri-information-line"></i>
                </button>
            <?php endif; ?>

            <?php if ($isGiver) : ?>
                <button type="button" 
                        class="mitb-action-icon btn-edit" 
                        title="Edit Slip" 
                        onclick="edit_mitb('<?= esc($row['mitb_code']) ?>');">
                    <i class="ri-pencil-line"></i>
                </button>
                <button type="button" 
                        class="mitb-action-icon btn-del" 
                        title="Delete Slip" 
                        onclick="delete_mitb('<?= esc($row['mitb_code']) ?>');">
                    <i class="ri-delete-bin-line"></i>
                </button>
            <?php else : ?>
                <span class="text-muted small"><i class="ri-shield-check-line text-success" title="Verified Received"></i></span>
            <?php endif; ?>
        </div>
    </td>
</tr>
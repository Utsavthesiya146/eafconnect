<?php
$statusLower = strtolower(trim(str_replace(['_', '-'], ' ', (string)($row['status'] ?? ''))));
$badgeStyle = match ($statusLower) {
    'open for discussion' => 'background: rgba(15, 59, 104, 0.12); color: #0F3B68; border: 1px solid rgba(15, 59, 104, 0.25);',
    'contacted'           => 'background: rgba(2, 132, 199, 0.12); color: #0284C7; border: 1px solid rgba(2, 132, 199, 0.25);',
    'pending', 'new'      => 'background: rgba(245, 158, 11, 0.14); color: #D97706; border: 1px solid rgba(245, 158, 11, 0.3);',
    'need sometime', 'not converted' => 'background: rgba(147, 51, 234, 0.12); color: #7C3AED; border: 1px solid rgba(147, 51, 234, 0.25);',
    'mitb'                => 'background: rgba(13, 148, 136, 0.14); color: #0D9488; border: 1px solid rgba(13, 148, 136, 0.3);',
    'completed', 'closed business', 'business done', 'bussiness done' => 'background: rgba(16, 185, 129, 0.14); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);',
    'cancelled', 'canceled' => 'background: rgba(239, 68, 68, 0.12); color: #DC2626; border: 1px solid rgba(239, 68, 68, 0.25);',
    default               => 'background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0;'
};

$displayStatus = match ($statusLower) {
    'open for discussion' => 'Open For Discussion',
    'contacted'           => 'Contacted',
    'pending', 'new'      => 'Pending',
    'need sometime', 'not converted' => 'Need Sometime',
    'mitb'                => 'MITB',
    'completed', 'closed business', 'business done', 'bussiness done' => 'Completed',
    'cancelled', 'canceled' => 'Cancelled',
    default               => !empty($row['status']) ? ucwords($statusLower) : 'Pending',
};

$phoneClean = preg_replace('/[^0-9+]/', '', $row['contact_number'] ?? '');
$waClean = preg_replace('/[^0-9]/', '', $row['contact_number'] ?? '');
if (strlen($waClean) === 10) {
    $waClean = '91' . $waClean;
}
?>
<div class="col-12 mb-3 p-0 ref-card-col">
    <!-- =========================================================================
         1. DESKTOP VIEW (>= 992px) - WIDE GRID ROW
         ========================================================================= -->
    <div class="referral-card shadow-sm ref-grid-row d-none d-lg-grid">
        <!-- 1. FROM SENDER (220px) -->
        <div style="display: flex; align-items: center; gap: 10px; width: 220px; overflow: hidden;">
            <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? ($row['user_code'] ?? '')) ?>" 
                 style="width: 38px; height: 38px; min-width: 38px; border-radius: 50%; object-fit: cover; object-position: top center; border: 2px solid #0F3B68; flex-shrink: 0;"
                 alt="<?= esc($row['from_name']) ?>"
                 onerror="this.onerror=null; this.src='<?= base_url('images/empty.png') ?>'">
            <div style="overflow: hidden; line-height: 1.25;">
                <div style="font-weight: 700; font-size: 13px; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($row['from_name']) ?>">
                    <?= esc($row['from_name']) ?>
                </div>
                <div style="font-size: 11px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($row['from_email']) ?>">
                    <?= esc($row['from_email']) ?>
                </div>
            </div>
        </div>

        <!-- 2. FLOW ARROW (32px) -->
        <div style="display: flex; align-items: center; justify-content: center; width: 32px;">
            <span style="width: 26px; height: 26px; border-radius: 50%; background: rgba(15, 59, 104, 0.06); color: #0F3B68; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">
                <i class="fa fa-arrow-right"></i>
            </span>
        </div>

        <!-- 3. TO RECEIVER (200px) -->
        <div style="display: flex; align-items: center; gap: 10px; width: 200px; overflow: hidden;">
            <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? '') ?>" 
                 style="width: 38px; height: 38px; min-width: 38px; border-radius: 50%; object-fit: cover; object-position: top center; border: 2px solid #D97706; flex-shrink: 0;"
                 alt="<?= esc($row['to_name']) ?>"
                 onerror="this.onerror=null; this.src='<?= base_url('images/empty.png') ?>'">
            <div style="overflow: hidden; line-height: 1.25;">
                <div style="font-weight: 700; font-size: 13px; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($row['to_name']) ?>">
                    <?= esc($row['to_name']) ?>
                </div>
                <div style="font-size: 11px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($row['to_email']) ?>">
                    <?= esc($row['to_email']) ?>
                </div>
            </div>
        </div>

        <!-- 4. BUSINESS (230px) -->
        <div style="width: 230px; overflow: hidden; line-height: 1.25;">
            <div style="font-size: 9.5px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Business Opportunity</div>
            <div style="font-weight: 700; font-size: 12.5px; color: #0F3B68; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($row['business']) ?>">
                <?= esc($row['business']) ?>
            </div>
        </div>

        <!-- 5. STATUS BADGE (120px) -->
        <div style="width: 120px; text-align: center;">
            <span onclick="edit_refferral_status('<?= esc($row['referral_code']) ?>');"
                  style="display: inline-block; padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px; cursor: pointer; user-select: none; transition: transform 0.15s ease; <?= $badgeStyle ?>"
                  onmouseover="this.style.transform='scale(1.05)';"
                  onmouseout="this.style.transform='scale(1)';"
                  title="Click to change status">
                <?= esc($displayStatus) ?>
            </span>
        </div>

        <!-- 6. DATE & TIME (160px) -->
        <div style="width: 160px; text-align: center; font-size: 11.5px; color: #64748B; font-weight: 500; white-space: nowrap;" title="Created on <?= (new DateTime($row['created_at']))->format('d M Y, h:i A') ?>">
            <i class="ri-time-line me-1 text-muted"></i><?= (new DateTime($row['created_at']))->format('d M Y • h:i A') ?>
        </div>

        <!-- 7. ACTIONS (110px) -->
        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; width: 110px; padding-right: 4px;">
            <?php if (!empty($phoneClean)): ?>
                <a href="tel:<?= esc($phoneClean); ?>" 
                   style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: #0284C7; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; text-decoration: none; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);" 
                   title="Call Member">
                    <i class="ri-phone-fill"></i>
                </a>
                <a href="https://wa.me/<?= esc($waClean); ?>?text=<?= urlencode('Hello, connecting via EAF Connect regarding referral: ' . $row['business']) ?>" 
                   target="_blank" 
                   style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: #25D366; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; box-shadow: 0 2px 6px rgba(37, 211, 102, 0.25);" 
                   title="WhatsApp">
                    <i class="ri-whatsapp-fill"></i>
                </a>
            <?php endif; ?>
            <a href="<?= site_url('member/connect/details/' . $row['referral_code']) ?>" 
               style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: #0F3B68; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; text-decoration: none; box-shadow: 0 2px 6px rgba(15, 59, 104, 0.2);" 
               title="View Details">
                <i class="ri-eye-line"></i>
            </a>
        </div>
    </div>

    <!-- =========================================================================
         2. MOBILE VIEW (< 992px) - 100% WIDTH NATIVE CARD (NO SIDE SCROLLING!)
         ========================================================================= -->
    <div class="ref-mobile-card shadow-sm d-flex d-lg-none">
        <!-- Top Row: Status Badge on Left, Date & Time on Right -->
        <div class="ref-mob-header">
            <span class="ref-mob-status-pill" onclick="edit_refferral_status('<?= esc($row['referral_code']) ?>');" style="<?= $badgeStyle ?>" title="Tap to update status">
                <i class="ri-record-circle-line me-1"></i><?= esc($displayStatus) ?>
            </span>
            <span class="ref-mob-date">
                <i class="ri-calendar-line me-1"></i><?= (new DateTime($row['created_at']))->format('d M Y, h:i A') ?>
            </span>
        </div>

        <!-- Middle Row: Connected Members (From -> To) -->
        <div class="ref-mob-flow-card">
            <div class="ref-mob-member-item">
                <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? ($row['user_code'] ?? '')) ?>" 
                     class="ref-mob-avatar from-avatar" alt="<?= esc($row['from_name']) ?>"
                     onerror="this.onerror=null; this.src='<?= base_url('images/empty.png') ?>'">
                <div class="ref-mob-member-text">
                    <span class="ref-mob-tag tag-from">GIVEN BY</span>
                    <strong class="ref-mob-member-name"><?= esc($row['from_name']) ?></strong>
                    <small class="ref-mob-member-sub"><?= esc($row['from_email']) ?></small>
                </div>
            </div>

            <div class="ref-mob-flow-divider">
                <i class="ri-arrow-right-line"></i>
            </div>

            <div class="ref-mob-member-item">
                <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? '') ?>" 
                     class="ref-mob-avatar to-avatar" alt="<?= esc($row['to_name']) ?>"
                     onerror="this.onerror=null; this.src='<?= base_url('images/empty.png') ?>'">
                <div class="ref-mob-member-text">
                    <span class="ref-mob-tag tag-to">RECEIVED BY</span>
                    <strong class="ref-mob-member-name"><?= esc($row['to_name']) ?></strong>
                    <small class="ref-mob-member-sub"><?= esc($row['to_email']) ?></small>
                </div>
            </div>
        </div>

        <!-- Business Opportunity Box -->
        <div class="ref-mob-business-box">
            <div class="ref-mob-business-label"><i class="ri-briefcase-4-line text-primary me-1"></i>BUSINESS OPPORTUNITY</div>
            <div class="ref-mob-business-desc"><?= esc($row['business'] ?: 'General Referral Opportunity') ?></div>
        </div>

        <!-- Action Buttons Footer (100% VISIBLE & ACCESSIBLE AT A GLANCE!) -->
        <div class="ref-mob-action-footer">
            <?php if (!empty($phoneClean)): ?>
                <a href="tel:<?= esc($phoneClean); ?>" class="ref-mob-btn btn-call" title="Call Member">
                    <i class="ri-phone-fill"></i>
                    <span>Call</span>
                </a>
                <a href="https://wa.me/<?= esc($waClean); ?>?text=<?= urlencode('Hello, connecting via EAF Connect regarding referral: ' . $row['business']) ?>" 
                   target="_blank" class="ref-mob-btn btn-wa" title="WhatsApp Member">
                    <i class="ri-whatsapp-fill"></i>
                    <span>WhatsApp</span>
                </a>
            <?php endif; ?>
            <a href="<?= site_url('member/connect/details/' . $row['referral_code']) ?>" class="ref-mob-btn btn-details" title="View Details">
                <i class="ri-eye-line"></i>
                <span>Details</span>
            </a>
            <button type="button" class="ref-mob-btn btn-status" onclick="edit_refferral_status('<?= esc($row['referral_code']) ?>');" title="Change Status">
                <i class="ri-edit-2-line"></i>
                <span>Status</span>
            </button>
        </div>
    </div>
</div>
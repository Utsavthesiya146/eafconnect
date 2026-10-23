<?php
$statusLower = strtolower(trim($row['status'] ?? ''));
$badgeStyle = match ($statusLower) {
    'cancelled' => 'background: rgba(239, 68, 68, 0.12); color: #DC2626; border: 1px solid rgba(239, 68, 68, 0.25);',
    'completed', 'closed business', 'business_done', 'mitb' => 'background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25);',
    'pending' => 'background: rgba(245, 158, 11, 0.12); color: #D97706; border: 1px solid rgba(245, 158, 11, 0.25);',
    'contacted' => 'background: rgba(14, 165, 233, 0.12); color: #0284C7; border: 1px solid rgba(14, 165, 233, 0.25);',
    'new' => 'background: rgba(15, 59, 104, 0.1); color: #0F3B68; border: 1px solid rgba(15, 59, 104, 0.2);',
    default => 'background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0;'
};

$phoneClean = preg_replace('/[^0-9+]/', '', $row['contact_number'] ?? '');
$waClean = preg_replace('/[^0-9]/', '', $row['contact_number'] ?? '');
if (strlen($waClean) === 10) {
    $waClean = '91' . $waClean;
}
?>
<div class="col-12 mb-2 p-0" style="min-width: 1200px; width: 1200px;">
    <div class="referral-card shadow-sm" style="display: grid !important; grid-template-columns: 220px 32px 200px 230px 120px 160px 110px !important; align-items: center !important; gap: 12px !important; background: #FFFFFF !important; border: 1px solid #E2E8F0 !important; border-radius: 12px !important; padding: 0 16px !important; height: 64px !important; min-height: 64px !important; max-height: 64px !important; white-space: nowrap !important; width: 1200px !important; min-width: 1200px !important; box-shadow: 0 2px 8px rgba(15, 59, 104, 0.04) !important; transition: all 0.2s ease !important; box-sizing: border-box !important; overflow: visible !important;">
        
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
            <span onclick="edit_refferral_status('<?= $row['referral_code'] ?>');"
                  style="display: inline-block; padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px; cursor: pointer; user-select: none; <?= $badgeStyle ?>"
                  title="Click to update status">
                <?= esc(ucwords(str_replace('_', ' ', $row['status']))) ?>
            </span>
        </div>

        <!-- 6. DATE & TIME (160px) -->
        <div style="width: 160px; text-align: center; font-size: 11.5px; color: #64748B; font-weight: 500; white-space: nowrap;" title="Created on <?= (new DateTime($row['created_at']))->format('d M Y, h:i A') ?>">
            <i class="ri-time-line me-1 text-muted"></i><?= (new DateTime($row['created_at']))->format('d M Y • h:i A') ?>
        </div>

        <!-- 7. ACTIONS (110px - fully visible, no clipping) -->
        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; width: 110px; padding-right: 4px;">
            <?php if (!empty($phoneClean)): ?>
                <a href="tel:<?= esc($phoneClean); ?>" 
                   style="width: 30px; height: 30px; min-width: 30px; border-radius: 8px; background: #0284C7; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; text-decoration: none; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);" 
                   title="Call Member">
                    <i class="fa fa-phone"></i>
                </a>
                <a href="https://wa.me/<?= esc($waClean); ?>?text=<?= urlencode('Hello, connecting via EAF Connect regarding referral: ' . $row['business']) ?>" 
                   target="_blank" 
                   style="width: 30px; height: 30px; min-width: 30px; border-radius: 8px; background: #25D366; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; text-decoration: none; box-shadow: 0 2px 6px rgba(37, 211, 102, 0.25);" 
                   title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
            <?php endif; ?>
            <a href="<?= site_url('member/connect/details/' . $row['referral_code']) ?>" 
               style="width: 30px; height: 30px; min-width: 30px; border-radius: 8px; background: #0F3B68; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; text-decoration: none; box-shadow: 0 2px 6px rgba(15, 59, 104, 0.2);" 
               title="View Details">
                <i class="fa fa-eye"></i>
            </a>
        </div>

    </div>
</div>
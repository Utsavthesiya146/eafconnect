<?php
$isUnread = ($row->status === 'unread');
$meta = get_notification_icon($row->title ?? '', $row->message ?? '');
$timeAgo = notification_time_ago($row->created_at);
?>

<div class="notification-card <?= $isUnread ? 'unread' : 'is-read' ?>" id="notif-card-<?= esc($row->n_code) ?>">
    <!-- Icon & Category -->
    <div class="notif-icon-col" onclick="viewNotificationDetails('<?= esc($row->n_code) ?>')">
        <div class="notif-category-icon" style="background: <?= $meta['bg'] ?>; color: <?= $meta['color'] ?>; border: 1px solid <?= $meta['border'] ?>;">
            <i class="<?= esc($meta['icon']) ?>"></i>
        </div>
    </div>

    <!-- Date Pill -->
    <div class="notif-date" onclick="viewNotificationDetails('<?= esc($row->n_code) ?>')">
        <span class="day"><?= date('d', strtotime($row->created_at)) ?></span>
        <span class="month"><?= date('M', strtotime($row->created_at)) ?></span>
        <small class="year"><?= date('Y', strtotime($row->created_at)) ?></small>
        <small class="time"><?= date('h:i A', strtotime($row->created_at)) ?></small>
    </div>

    <!-- Main Content -->
    <div class="notif-content" onclick="viewNotificationDetails('<?= esc($row->n_code) ?>')">
        <div class="d-flex align-items-center justify-content-between mb-1 gap-2 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <h6 class="notif-title mb-0"><?= esc($row->title) ?></h6>
                <span class="notif-type-tag" style="background: <?= $meta['bg'] ?>; color: <?= $meta['color'] ?>;">
                    <?= esc($meta['badge']) ?>
                </span>
            </div>
            <span class="notif-rel-time">
                <i class="ri-time-line"></i> <?= esc($timeAgo) ?>
            </span>
        </div>

        <p class="notif-message-text mb-0">
            <?= esc(strip_tags($row->message)) ?>
        </p>
    </div>

    <!-- Footer / Quick Actions -->
    <div class="notif-action-group">
        <!-- Status Toggle -->
        <?php if ($isUnread): ?>
            <button type="button" 
                    class="btn-status-toggle unread" 
                    onclick="toggleNotificationStatus('<?= esc($row->n_code) ?>', 'read', event)"
                    title="Click to mark as read">
                <i class="ri-checkbox-circle-line"></i> Mark Read
            </button>
        <?php else: ?>
            <button type="button" 
                    class="btn-status-toggle is-read" 
                    onclick="toggleNotificationStatus('<?= esc($row->n_code) ?>', 'unread', event)"
                    title="Click to mark as unread">
                <i class="ri-mail-line"></i> Mark Unread
            </button>
        <?php endif; ?>

        <!-- Delete Action -->
        <button type="button" 
                class="btn-delete-notif" 
                onclick="confirmDeleteNotification('<?= esc($row->n_code) ?>', event)" 
                title="Delete notification">
            <i class="ri-delete-bin-line"></i>
        </button>
    </div>
</div>
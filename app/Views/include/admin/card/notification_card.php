<div class="notification-card <?= $row->status == 'unread' ? 'unread' : '' ?>">

    <div class="notif-date">
        <div class="day"><?= date('d', strtotime($row->created_at)) ?></div>
        <div class="month"><?= date('M Y', strtotime($row->created_at)) ?></div>
        <small><?= date('h:i A', strtotime($row->created_at)) ?></small>
    </div>

    <div class="notif-content">
        <div class="notif-message">
            <h5> <?= strip_tags($row->title, '<p><b><br><strong><em>') ?></h5>
            <?= strip_tags($row->message, '<p><b><br><strong><em>') ?>
        </div>

        <div class="notif-footer">
            <?php if ($row->status == 'unread'): ?>
                <span class="status-badge unread">Unread</span>
            <?php else: ?>
                <span class="status-badge read">Read</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="notif-action">
        <button class="btn-delete"
            onclick="edi_notf('<?= esc($row->n_code) ?>')">
            <i class="fa fa-pen"></i>
        </button>
        <button class="btn-delete"
            onclick="delete_notf('<?= esc($row->n_code) ?>')">
            <i class="fa fa-trash"></i>
        </button>
    </div>

</div>
<?php
$mode = strtolower($row['mode'] ?? '');

$modeClass = match ($mode) {
    'online'  => 'crm-mode online',
    'offline' => 'crm-mode offline',
    'hybrid'  => 'crm-mode hybrid',
    default   => 'crm-mode'
};

$modeIcon = match ($mode) {
    'online'  => 'bi-camera-video-fill',
    'offline' => 'bi-geo-alt-fill',
    'hybrid'  => 'bi-shuffle',
    default   => 'bi-calendar-event'
};
?>

<div class="crm-meeting-item">

    <!-- Number -->
    <div class="crm-meeting-number">
        <?= sprintf('%02d', $i) ?>
    </div>


    <!-- Date -->
    <div class="crm-meeting-date">

        <div class="crm-date-day">
            <?= date('d', strtotime($row['date'])) ?>
        </div>

        <div class="crm-date-info">
            <strong>
                <?= date('M Y', strtotime($row['date'])) ?>
            </strong>

            <small>
                <i class="bi bi-clock"></i>
                <?= date('h:i A', strtotime($row['date'])) ?>
            </small>
        </div>

    </div>


    <!-- Meeting Details -->
    <div class="crm-meeting-info">

        <div class="crm-meeting-name">
            <?= esc($row['title']) ?>
        </div>

        <?php if (!empty($row['description'])): ?>
            <div class="crm-meeting-description">
                <?= esc($row['description']) ?>
            </div>
        <?php endif; ?>

        <div class="crm-meeting-chapter">
            <i class="bi bi-diagram-3"></i>
            <?= esc($row['chapter_name'] ?? 'N/A') ?>
        </div>

    </div>


    <!-- Location -->
    <div class="crm-meeting-location">

        <div>
            <i class="bi bi-geo-alt"></i>
            <span>
                <?= esc($row['location'] ?? 'N/A') ?>
            </span>
        </div>

        <?php if (!empty($row['venue'])): ?>
            <small>
                <?= esc($row['venue']) ?>
            </small>
        <?php endif; ?>

    </div>


    <!-- Mode -->
    <div class="crm-meeting-mode">

        <span class="<?= $modeClass ?>">
            <i class="bi <?= $modeIcon ?>"></i>
            <?= ucfirst($mode ?: 'N/A') ?>
        </span>

    </div>


    <!-- Registration -->
    <div class="crm-meeting-registration">

        <div class="crm-registration-icon">
            <i class="bi bi-person-check-fill"></i>
        </div>

        <div>
            <strong>
                <?= $row['reg_count'] ?? 0 ?>
            </strong>

            <small>Registered</small>
        </div>

    </div>


    <!-- Join -->
    <div class="crm-meeting-action">

        <?php if ($mode !== 'offline' && !empty($row['meeting_link'])): ?>

            <a href="<?= esc($row['meeting_link']) ?>"
                target="_blank"
                class="crm-join-btn">

                <i class="bi bi-box-arrow-up-right"></i>
                Join

            </a>

        <?php else: ?>

            <span class="crm-no-link">
                —
            </span>

        <?php endif; ?>

    </div>


    <!-- Actions -->
    <div class="crm-meeting-actions">

        <button type="button"
            class="crm-action-btn edit"
            onclick="edit_meeting('<?= esc($row['event_code']) ?>')"
            title="Edit Meeting">

            <i class="fa fa-edit"></i>

        </button>

        <button type="button"
            class="crm-action-btn delete"
            onclick="delete_meeting('<?= esc($row['event_code']) ?>')"
            title="Delete Meeting">

            <i class="fa fa-trash"></i>

        </button>

    </div>

</div>
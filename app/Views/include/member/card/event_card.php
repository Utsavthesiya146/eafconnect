<?php

/* ============================
   EVENT DATE
============================ */

$eventDate = !empty($row['date'])
    ? strtotime($row['date'])
    : time();

$month = strtoupper(date('M', $eventDate));
$day   = date('d', $eventDate);
$year  = date('Y', $eventDate);
$time  = date('h:i A', $eventDate);


/* ============================
   MODE
============================ */

$mode = strtolower((string)($row['mode'] ?? 'offline'));

switch ($mode) {

    case 'online':

        $modeData = [
            'class' => 'event-mode-online',
            'icon'  => 'fa-video-camera',
            'text'  => 'Online'
        ];

        break;

    case 'hybrid':

        $modeData = [
            'class' => 'event-mode-hybrid',
            'icon'  => 'fa-random',
            'text'  => 'Hybrid'
        ];

        break;

    default:

        $modeData = [
            'class' => 'event-mode-offline',
            'icon'  => 'fa-building',
            'text'  => 'Offline'
        ];

        break;
}


/* ============================
   STATUS
============================ */

/*
 * IMPORTANT:
 * events.status is ENUM('1','2','3')
 * So keep the value as STRING.
 */

$statusValue = (string)($row['status'] ?? '1');

switch ($statusValue) {

    case '2':

        $statusData = [
            'class' => 'event-status-ongoing',
            'icon'  => 'fa-clock-o',
            'text'  => 'Ongoing'
        ];

        break;

    case '3':

        $statusData = [
            'class' => 'event-status-completed',
            'icon'  => 'fa-check-circle',
            'text'  => 'Completed'
        ];

        break;

    case '1':
    default:

        $statusData = [
            'class' => 'event-status-scheduled',
            'icon'  => 'fa-calendar-check-o',
            'text'  => 'Scheduled'
        ];

        break;
}


/* ============================
   ACCESS
============================ */

$db = \Config\Database::connect();

$rowData = $db->table('events e')
    ->select('e.event_code, c.leader_code')
    ->join(
        'chapters c',
        'c.chapter_code = e.chapter_code',
        'left'
    )
    ->where('e.event_code', $row['event_code'])
    ->get()
    ->getRowArray();


/* ============================
   USER
============================ */

$userCode = trim((string)session()->get('user_code'));

$adminRole = trim((string)(session()->get('admin_role') ?? ''));


/* ============================
   ALLOWED ROLES
============================ */

$allowedRoles = [
    'President',
    'Vice President'
];


/* ============================
   CAN MANAGE
============================ */

$leaderCode = trim((string)($rowData['leader_code'] ?? ''));

$canManageMeeting =
    ($leaderCode !== '' && $leaderCode === $userCode) ||
    in_array($adminRole, $allowedRoles, true);


/* ============================
   EVENT CODE
============================ */

$eventCode = esc((string)($row['event_code'] ?? ''));

?>

<div class="meeting-card">

    <!-- =================================
         DATE
    ================================== -->

    <div class="meeting-date-box">

        <span class="meeting-month">
            <?= esc($month) ?>
        </span>

        <strong class="meeting-day">
            <?= esc($day) ?>
        </strong>

        <span class="meeting-year">
            <?= esc($year) ?>
        </span>

    </div>


    <!-- =================================
         EVENT TITLE
    ================================== -->

    <h3 class="meeting-title">
        <?= esc($row['title'] ?? 'Untitled Meeting') ?>
    </h3>


    <!-- =================================
         CHAPTER
    ================================== -->

    <div class="meeting-chapter">

        <i class="fa fa-users"></i>

        <span>
            <?= esc($row['chapter_name'] ?? 'N/A') ?>
        </span>

    </div>


    <!-- =================================
         TIME
    ================================== -->

    <div class="meeting-time">

        <span class="meeting-time-icon">
            <i class="fa fa-clock-o"></i>
        </span>

        <strong>
            <?= esc($time) ?>
        </strong>

    </div>


    <!-- =================================
         DESCRIPTION
    ================================== -->

    <div class="meeting-description">

        <i class="fa fa-comment-o"></i>

        <div>

            <?php if (!empty($row['description'])): ?>

                <?= nl2br(esc($row['description'])) ?>

            <?php else: ?>

                No description available.

            <?php endif; ?>

        </div>

    </div>


    <!-- =================================
         LOCATION
    ================================== -->

    <div class="meeting-location">

        <div class="meeting-location-item">

            <i class="fa fa-map-marker"></i>

            <div>

                <small>Location</small>

                <strong>
                    <?= esc($row['location'] ?? 'N/A') ?>
                </strong>

            </div>

        </div>


        <div class="meeting-location-item">

            <i class="fa fa-building"></i>

            <div>

                <small>Venue</small>

                <strong>
                    <?= esc($row['venue'] ?? 'N/A') ?>
                </strong>

            </div>

        </div>

    </div>


    <!-- =================================
         MODE + STATUS
    ================================== -->

    <div class="meeting-tags">

        <!-- MODE -->

        <span class="<?= esc($modeData['class']) ?>">

            <i class="fa <?= esc($modeData['icon']) ?>"></i>

            <?= esc($modeData['text']) ?>

        </span>


        <!-- STATUS -->

        <?php if ($canManageMeeting): ?>

            <span
                class="<?= esc($statusData['class']) ?>"
                style="cursor:pointer;"
                onclick="edit_meeting('<?= $eventCode ?>')"
                title="Edit Meeting">

                <i class="fa <?= esc($statusData['icon']) ?>"></i>

                <?= esc($statusData['text']) ?>

            </span>

        <?php else: ?>

            <span class="<?= esc($statusData['class']) ?>">

                <i class="fa <?= esc($statusData['icon']) ?>"></i>

                <?= esc($statusData['text']) ?>

            </span>

        <?php endif; ?>

    </div>


    <!-- =================================
         JOIN / ATTENDANCE
    ================================== -->

    <a href="<?= base_url('member/meetings/attendance/' . $eventCode) ?>"
        class="meeting-join-btn attendance-btn">

        <i class="fa fa-check-circle"></i>

        Mark Attendance

    </a>


    <!-- =================================
         ACTIONS
    ================================== -->

    <?php if ($canManageMeeting): ?>

        <div class="meeting-actions">


            <!-- SEND MAIL -->

            <button type="button"
                onclick="send_mail('<?= $eventCode ?>')"
                class="meeting-action-mail"
                title="Send Mail">

                <i class="fa fa-envelope"></i>

                <span>Mail</span>

            </button>


            <!-- JOIN MEETING -->

            <?php if (
                $mode !== 'offline' &&
                !empty($row['meeting_link'])
            ): ?>

                <a href="<?= esc($row['meeting_link']) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="meeting-action-mail"
                    title="Meeting Link">

                    <i class="fa fa-link"></i>

                    <span>Join Meeting</span>

                </a>

            <?php endif; ?>


            <!-- EDIT -->

            <button type="button"
                onclick="edit_meeting('<?= $eventCode ?>')"
                class="meeting-action-edit"
                title="Edit">

                <i class="fa fa-edit"></i>

            </button>


            <!-- DELETE -->

            <button type="button"
                onclick="delete_meeting('<?= $eventCode ?>')"
                class="meeting-action-delete"
                title="Delete">

                <i class="fa fa-trash"></i>

            </button>


            <!-- ATTENDANCE -->

            <a href="<?= base_url('member/meetings/attendance/' . $eventCode) ?>"
                class="meeting-action-attendance"
                title="Attendance">

                <i class="fa fa-user"></i>

            </a>


        </div>

    <?php endif; ?>
</div>
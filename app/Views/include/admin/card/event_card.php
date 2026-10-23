<tr>

    <td data-label="No">
        <?= $i ?>
    </td>

    <td data-label="Date">
        <b><?= date('d M Y', strtotime($row['date'])) ?></b><br>
        <small class="text-muted">
            <?= date('h:i A', strtotime($row['date'])) ?>
        </small>
    </td>

    <td data-label="Title">
        <strong><?= esc($row['title']) ?></strong>
        <p class="mb-0"><?= esc($row['description']) ?></p>
    </td>

    <td data-label="Chapter">
        <?= esc($row['chapter_name'] ?? 'N/A') ?>
    </td>

    <td data-label="Location">
        <div><?= esc($row['location']) ?></div>
        <small><?= esc($row['venue'] ?? 'N/A') ?></small>
    </td>

    <?php
    $modeClass = match ($row['mode']) {
        'online'  => 'status online',
        'offline' => 'status offline',
        'hybrid'  => 'status hybrid',
        default   => 'status offline'
    };

    $statusValue = $row['status'] ?? '1';

    $statusText = match ($statusValue) {
        '1' => 'Scheduled',
        '2' => 'Ongoing',
        '3' => 'Completed',
        default => 'Scheduled'
    };

    $statusClass = match ($statusValue) {
        '1' => 'status-scheduled',
        '2' => 'status-ongoing',
        '3' => 'status-completed',
        default => 'status-default'
    };
    ?>

    <td data-label="Mode">
        <span class="<?= $modeClass ?>">
            <?= ucfirst($row['mode']) ?>
        </span>
    </td>

    <td data-label="Status">
        <span class="<?= $statusClass ?>"
              onclick="edit_meeting('<?= $row['event_code'] ?>')">
            <?= $statusText ?>
        </span>
    </td>

    <td data-label="Join">
        <?php if ($row['mode'] != 'offline' && !empty($row['meeting_link'])) : ?>

            <a href="<?= esc($row['meeting_link']) ?>"
               target="_blank"
               class="btn btn-success btn-sm">
                Join
            </a>

        <?php else : ?>

            -

        <?php endif; ?>
    </td>

    <td data-label="Actions">

        <button type="button"
                class="btn btn-warning btn-sm"
                onclick="send_mail('<?= $row['event_code'] ?>')">
            <i class="fa fa-envelope"></i>
        </button>

        <button type="button"
                class="btn btn-primary btn-sm"
                onclick="edit_meeting('<?= $row['event_code'] ?>')">
            <i class="fa fa-edit"></i>
        </button>

        <button type="button"
                class="btn btn-danger btn-sm"
                onclick="delete_meeting('<?= $row['event_code'] ?>')">
            <i class="fa fa-trash"></i>
        </button>

        <a href="<?= base_url('admin/meetings/attendance/'.$row['event_code']) ?>"
           class="btn btn-info btn-sm">
            <i class="fa fa-user"></i>
        </a>

    </td>

</tr>
<tr>
    <td> <?= esc($serialNo) ?></td>
    <td style="font-weight: 600; color:#222;">
        <?= esc($a->name) ?>
    </td>
    <td style="font-weight: 600; color:#222;">
        <?= esc($a->event_title) ?>
    </td>

    <td style="color:#555;">
        <?= esc($a->chapter_name) ?>
    </td>

    <td style="color:#777;">
        <?= date('d M Y', strtotime($a->event_date)) ?>
    </td>

    <td>
        <?php
        $statusColor = match ($a->status) {
            'present' => '#28a745',
            'absent'  => '#dc3545',
            'medical' => '#ffc107',
            'late'    => '#fd7e14',
            default   => '#6c757d',
        };
        ?>

        <span class="badge"
            style="background: <?= $statusColor ?>; color:#fff; padding:6px 12px; border-radius:20px;">
            <?= ucfirst($a->status) ?>
        </span>
    </td>

</tr>
<tr>

    <td><?= $i++ ?></td>

    <!-- FROM -->
    <td>
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user'] ?? ($row['from_user_code'] ?? '')) ?>" class="avatar" style="object-fit:cover; object-position:top center; border: 1.5px solid #101B35;">
            <div>
                <div class="name"><?= esc($row['from_name']) ?></div>
                <small><?= esc($row['from_email']) ?></small>
            </div>
        </div>
    </td>

    <!-- TO -->
    <td>
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user'] ?? ($row['to_user_code'] ?? '')) ?>" class="avatar" style="object-fit:cover; object-position:top center; border: 1.5px solid #C6A667;">
            <div>
                <div class="name"><?= esc($row['to_name']) ?></div>
                <small><?= esc($row['to_email']) ?></small>
            </div>
        </div>
    </td>

    <td><?= esc($row['location']) ?></td>

    <td><?= date('d M Y', strtotime($row['date'])) ?></td>

    <td>
        <?php
        $rawStatus = strtolower(trim((string)($row['status'] ?? '')));
        if ($rawStatus === '') {
            $rawStatus = 'scheduled';
        }
        $statusClass = match ($rawStatus) {
            'completed'                   => 'status completed',
            'cancelled'                   => 'status cancelled',
            're-scheduled', 'rescheduled' => 'status pending',
            default                       => 'status scheduled'
        };
        $statusLabel = match ($rawStatus) {
            'completed'                   => 'Completed',
            'cancelled'                   => 'Cancelled',
            're-scheduled', 'rescheduled' => 'Re-scheduled',
            default                       => 'Scheduled'
        };
        ?>
        <span class="<?= $statusClass ?>">
            <?= $statusLabel ?>
        </span>
    </td>

    <td>
        <?= (new DateTime($row['created_at']))->format('d M Y, H:i') ?>
    </td>

    <td>
        <?php if ($row['from_user'] == session()->get('user_code')) { ?>
            <a class="btn btn-warning btn-sm"
               onclick="edit_f2f('<?= $row['f2f_code'] ?>');">
                <i class="fa fa-edit"></i>
            </a>
        <?php } ?>
    </td>

</tr>
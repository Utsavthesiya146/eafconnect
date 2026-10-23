<tr>
    <td data-label="ID"><?= $i ?></td>

    <td class="member-info" data-label="From User">
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user'] ?? ($row['from_user_code'] ?? '')) ?>" class="avatar" style="object-fit:cover;object-position:top center; border: 1.5px solid #101B35;">
            <div>
                <div class="name"><?= esc($row['from_name']) ?></div>
                <small><?= esc($row['from_email']) ?></small>
            </div>
        </div>
    </td>

    <td class="member-info" data-label="To User">
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user'] ?? ($row['to_user_code'] ?? '')) ?>" class="avatar" style="object-fit:cover;object-position:top center; border: 1.5px solid #C6A667;">
            <div>
                <div class="name"><?= esc($row['to_name']) ?></div>
                <small><?= esc($row['to_email']) ?></small>
            </div>
        </div>
    </td>

    <td data-label="Location">
        <?= esc($row['location']) ?>
    </td>

    <td data-label="Meeting Date">
        <?= date('d M Y', strtotime($row['date'])) ?>
    </td>

    <td data-label="Status">
        <?php
        $statusClass = match ($row['status']) {
            'scheduled' => 'status scheduled',
            'completed' => 'status completed',
            'cancelled' => 'status cancelled',
            default => 'status pending'
        };
        ?>
        <span class="<?= $statusClass ?>"
            onclick="edit_f2f('<?= $row['f2f_code'] ?>');">
            <?= ucfirst($row['status']) ?>
        </span>
    </td>

    <td data-label="Created">
        <?= (new DateTime($row['created_at']))->format('d M Y, H:i') ?>
    </td>

    <td class="actions" data-label="Actions">
        <a class="btn btn-warning btn-sm"
            onclick="edit_f2f('<?= $row['f2f_code'] ?>');">
            <i class="fa fa-edit"></i>
        </a>

        <a class="btn btn-danger btn-sm"
            onclick="delete_f2f('<?= $row['f2f_code'] ?>');">
            <i class="fa fa-trash"></i>
        </a>
    </td>
</tr>
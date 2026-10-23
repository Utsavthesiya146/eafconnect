<tr>

    <td data-label="ID"><?= $i ?></td>

    <td class="visitor-info" data-label="Visitor">
        <div>
            <div class="name"><?= esc($row['visitor_name']) ?></div>
            <small><?= esc($row['email']) ?></small>
        </div>
    </td>

    <td data-label="Company">
        <?= esc($row['company']) ?>
    </td>

    <td data-label="Phone">
        <?= esc($row['phone']) ?>
    </td>

    <td data-label="Visited Date">
        <?= date('d M Y', strtotime($row['date_visited'])) ?>
    </td>

    <td class="member-info" data-label="Added By">
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['member_profile'] ?? '', '', $row['user_code'] ?? ($row['member_code'] ?? '')) ?>" style="object-fit:cover;object-position:top center; border: 1.5px solid #101B35;"
                class="avatar">

            <div>
                <div class="name"><?= esc($row['member_name']) ?></div>
                <small><?= esc($row['member_email']) ?></small>
            </div>
        </div>
    </td>

    <td data-label="Status">
        <?php
        $statusClass = match ($row['status']) {
            'visited'   => 'status completed',
            'pending'   => 'status pending',
            'cancelled' => 'status cancelled',
            default     => 'status pending'
        };
        ?>

        <span class="<?= $statusClass ?>"
            onclick="edit_v('<?= $row['visitors_code'] ?>');">
            <?= ucfirst($row['status']) ?>
        </span>
    </td>

    <td class="actions" data-label="Action">
        <a class="btn btn-danger btn-sm"
            onclick="delete_v('<?= $row['visitors_code'] ?>');">
            <i class="fa fa-trash"></i>
        </a>
    </td>

</tr>
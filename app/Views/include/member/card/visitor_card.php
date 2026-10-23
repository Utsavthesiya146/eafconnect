<tr>

    <td><?= $i ?></td>

    <td>
        <strong><?= esc($row['visitor_name']) ?></strong><br>
        <small><?= esc($row['email']) ?></small>
    </td>

    <td><?= esc($row['company']) ?></td>

    <td><?= esc($row['phone']) ?></td>

    <td><?= date('d M Y', strtotime($row['date_visited'])) ?></td>

    <td>
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['member_profile'] ?? '', '', $row['user_code'] ?? ($row['member_code'] ?? '')) ?>" class="avatar" style="object-fit: cover; object-position: top center; border: 1.5px solid #101B35;">
            <div>
                <div class="name"><?= esc($row['member_name']) ?></div>
                <small><?= esc($row['member_email']) ?></small>
            </div>
        </div>
    </td>

    <td>
        <?php
        $status = strtolower(trim($row['status'] ?? ''));

        $statusClass2 = match ($status) {
            'invited'   => 'btn btn-sm btn-danger',
            'attended'  => 'btn btn-sm btn-success',
            'visited'   => 'btn btn-sm btn-success',
            'pending'   => 'btn btn-sm btn-warning',
            'cancelled' => 'btn btn-sm btn-danger',
            default     => 'btn btn-sm btn-warning'
        };

        $db = \Config\Database::connect();

        $rowData = $db->table('users u')
            ->select('u.user_code, u.member_role')
            ->where('u.user_code', session()->get('user_code'))
            ->get()
            ->getRowArray();

        $isPresidentOrVP =
            ($rowData['user_code'] ?? '') === session()->get('user_code') &&
            in_array(
                $rowData['member_role'] ?? '',
                ['President', 'Vice President'],
                true
            );
        ?>

        <span
            class="<?= $statusClass2 ?>"
            style="cursor: pointer;"
            onclick="<?= $isPresidentOrVP
                            ? "status_v_a('" . esc($row['visitors_code']) . "')"
                            : "edit_v('" . esc($row['visitors_code']) . "')"
                        ?>">
            <?= ucfirst($status) ?>
            <i class="fa fa-edit"></i>
        </span>

        <a class="btn btn-warning btn-sm"
            onclick="edit_v('<?= $row['visitors_code'] ?>')">
            <i class="fa fa-edit"></i>
        </a>
    </td>

    <td>
        <a class="btn btn-warning btn-sm"
            onclick="send_mail('<?= $row['visitors_code'] ?>')">
            <i class="fa fa-envelope"></i>
        </a>

        <a class="btn btn-warning btn-sm"
            onclick="delete_v('<?= $row['visitors_code'] ?>');">
            <i class="fa fa-trash"></i>
        </a>
    </td>

</tr>
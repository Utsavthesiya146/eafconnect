<tr>
    <td data-label="ID"><?= $i ?></td>

    <td data-label="Created">
        <?= (new DateTime($row['created_at']))->format('d M Y, H:i') ?>
    </td>

    <td data-label="Amount">
        <strong class="amount">₹<?= esc((new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['amount'], 2)) ?></strong>
    </td>
    <td class="member-info" data-label="To Member">
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? '') ?>"
                class="avatar" style="object-fit:cover;object-position:top center; border: 1.5px solid #C6A667;">
            <div>
                <div class="name"><?= esc($row['to_name']) ?></div>
                <small><?= esc($row['to_email']) ?></small>
            </div>
        </div>
    </td>
    <td class="member-info" data-label="From Member">
        <div class="member-cell">
            <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? ($row['user_code'] ?? '')) ?>"
                class="avatar" style="object-fit:cover;object-position:top center; border: 1.5px solid #101B35;">
            <div>
                <div class="name"><?= esc($row['from_name']) ?></div>
                <small><?= esc($row['from_email']) ?></small>
            </div>
        </div>
    </td>

    <td data-label="Category">
        <span onclick="edit_mitb('<?= $row['mitb_code'] ?>');">
            <?= esc($row['category']) ?>
        </span>
    </td>

    <td data-label="Business Type">
        <span onclick="edit_mitb('<?= $row['mitb_code'] ?>');">
            <?= esc($row['business_type']) ?>
        </span>
    </td>

    <td class="actions" data-label="Actions">
        <a class="btn btn-warning btn-sm"
            onclick="edit_mitb('<?= $row['mitb_code'] ?>');">
            <i class="fa fa-edit"></i>
        </a>

        <a class="btn btn-danger btn-sm"
            onclick="delete_mitb('<?= $row['mitb_code'] ?>');">
            <i class="fa fa-trash"></i>
        </a>
    </td>
</tr>
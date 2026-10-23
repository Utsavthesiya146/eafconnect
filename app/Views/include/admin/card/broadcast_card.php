<tr>

    <td><?= $i; ?></td>
    <!-- IMAGE -->
    <td>
        <?php if (!empty($row['image'])) { ?>
            <img src="<?= base_url('uploads/broadcast/' . $row['image']) ?>"
                style="width:60px;height:60px;border-radius:10px;object-fit:cover;">
        <?php } else { ?>
            <img src="<?= base_url('uploads/no-image.png') ?>"
                style="width:60px;height:60px;border-radius:10px;">
        <?php } ?>
    </td>
    <!-- TITLE -->
    <td><?= esc($row['title']) ?></td>
    <!-- MESSAGE -->
    <td><?= esc(strip_tags($row['message'])) ?></td>

    <!-- STATUS -->
    <td>
        <?php if ($row['status'] == 1) { ?>
            <span class="badge bg-success" onclick="edit_broadcast('<?= $row['broadcast_code'] ?>')">Active</span>

        <?php } elseif ($row['status'] == 2) { ?>
            <span class="badge bg-warning" onclick="edit_broadcast('<?= $row['broadcast_code'] ?>')">Pending</span>

        <?php } else { ?>
            <span class="badge bg-danger" onclick="edit_broadcast('<?= $row['broadcast_code'] ?>')">Blocked</span>
        <?php } ?>
    </td>

    <!-- ACTION -->
    <td>
        <a onclick="edit_broadcast('<?= $row['broadcast_code'] ?>')"
            class="btn btn-danger btn-sm">
            <i class="fa fa-edit"></i>
        </a>

        <a onclick="delete_broadcast('<?= $row['broadcast_code'] ?>')"
            class="btn btn-danger btn-sm">
            <i class="fa fa-trash"></i>
        </a>
    </td>

</tr>
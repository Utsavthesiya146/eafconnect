  <tr>
      <td><?= $i ?></td>
      <td><?= esc($row['name']) ?></td>
      <td><?= esc($row['email']) ?></td>
      <td><?= esc($row['phone'] ?? '-') ?></td>
      <td><?= esc($row['subject'] ?? '-') ?></td>
      <td><?= esc($row['message'] ?? '-') ?></td>
      <td>
          <?php
            $statusClass = match ($row['status']) {
                '1'      => 'status pending',
                '2'    => 'status contacted',
                '3'  => 'status completed',
                default     => 'status pending'
            };
            ?>
          <?php if ($row['status'] == 1) { ?>
              <span class="badge bg-success" onclick="edit_contact('<?= $row['contact_code'] ?>')">Active</span>

          <?php } elseif ($row['status'] == 2) { ?>
              <span class="badge bg-warning" onclick="edit_contact('<?= $row['contact_code'] ?>')">Pending</span>

          <?php } else { ?>
              <span class="badge bg-danger" onclick="edit_contact('<?= $row['contact_code'] ?>')">Blocked</span>
          <?php } ?>
      </td>
      <td>
          <?= date('d M Y h:i A', strtotime($row['created_on'])) ?>
      </td>
      <td>
          <a class="btn btn-warning btn-sm" onclick="edit_contact('<?= $row['contact_code'] ?>')">
              <i class="fa fa-edit"></i>
          </a>
          <a class="btn btn-danger btn-sm" onclick="delete_contact('<?= $row['contact_code'] ?>')">
              <i class="fa fa-trash"></i>
          </a>
      </td>
  </tr>
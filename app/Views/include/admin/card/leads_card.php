  <tr>
      <td><?= $i ?></td>
      <td><?= esc($row['name']) ?></td>
      <td><?= esc($row['email']) ?></td>
      <td><?= esc($row['phone'] ?? '-') ?></td>
      <td><?= esc($row['company'] ?? '-') ?></td>
      <td><?= esc($row['message'] ?? '-') ?></td>
      <td>
          <?php
            $statusClass = match ($row['status']) {
                'new'      => 'status pending',
                'viewed'    => 'status contacted',
                'accepted'  => 'status completed',
                'declined'  => 'status cancelled',
                default     => 'status pending'
            };
            ?>
          <span class="<?= $statusClass ?>" onclick="edit_leads('<?= $row['lead_code'] ?>')">
              <?= ucfirst($row['status']) ?>
          </span>
      </td>
      <td>
          <?= date('d M Y h:i A', strtotime($row['created_at'])) ?>
      </td>
      <td>
          <a class="btn btn-primary btn-sm" onclick="add_member('<?= $row['lead_code'] ?>')">
              <i class="fa fa-plus"></i>
          </a>
          <a class="btn btn-warning btn-sm" onclick="edit_leads('<?= $row['lead_code'] ?>')">
              <i class="fa fa-edit"></i>
          </a>
          <a class="btn btn-danger btn-sm" onclick="delete_leads('<?= $row['lead_code'] ?>')">
              <i class="fa fa-trash"></i>
          </a>
      </td>
  </tr>
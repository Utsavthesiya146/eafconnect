  <tr>
      <td><?= $i ?></td>
      <td><?= esc($row['guest_name']) ?></td>
      <td><?= esc($row['guest_email']) ?></td>
      <td><?= esc($row['guest_phone'] ?? '-') ?></td>
      <td><?= esc($row['guest_company'] ?? '-') ?></td>
      <td><?= esc($row['message'] ?? '-') ?></td>
      <td>
          <?php
            $statusClass = match ($row['status']) {
                'sent'      => 'status sent',
                'viewed'    => 'status viewed',
                'accepted'  => 'status accepted',
                'declined'  => 'status declined',
                default     => 'status pending'
            };
            ?>
          <span class="<?= $statusClass ?>">
              <?= ucfirst($row['status']) ?>
          </span>
      </td>
      <td>
          <?= date('d M Y h:i A', strtotime($row['created_at'])) ?>
      </td>
      <td>
          <a class="btn btn-warning btn-sm" onclick="send_mail('<?= $row['invitations_code'] ?>')">
              <i class="fa fa-envelope"></i>
          </a>
          <a class="btn btn-warning btn-sm" onclick="edit_invitation('<?= $row['invitations_code'] ?>')">
              <i class="fa fa-edit"></i>
          </a>
          <a class="btn btn-danger btn-sm" onclick="delete_invitation('<?= $row['invitations_code'] ?>')">
              <i class="fa fa-trash"></i>
          </a>
      </td>
  </tr>
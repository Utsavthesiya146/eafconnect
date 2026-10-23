 <tr>
     <td data-label="S.No"><?= $i ?></td>

     <td class="member-info">
         <div class="member-cell">
             <?php $mAvatar2 = get_member_avatar($row->profile_photo ?? '', $row->gender ?? '', $row->user_code ?? '', true); ?>
             <div class="position-relative d-inline-block">
                 <img src="<?= $mAvatar2['url'] ?>"
                     class="avatar"
                     style="object-fit:cover;object-position:top center; border: 1.5px solid <?= $mAvatar2['is_ai'] ? '#C6A667' : '#101B35' ?>;">
                 <?php if ($mAvatar2['is_ai']): ?>
                     <span title="AI Avatar" style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; background: #C6A667; border: 1.5px solid #fff; border-radius: 50%; display: inline-block;"></span>
                 <?php endif; ?>
             </div>

             <div>

                 <?php
                    $commonModel = new \App\Models\CommonModel();

                    $refCount = $commonModel->exe_query("
    SELECT COUNT(*) AS total
    FROM users
    WHERE refferd_by = '" . $row->user_code . "'
")->getResultArray();

                    $totalRef = !empty($refCount) ? $refCount[0]['total'] : 0;
                    ?>
                 <div class="name"><?= esc($row->name) ?></div>
                 <small><?= esc($row->email) ?></small>
             </div>
         </div>
     </td>

     <td data-label="Business" class="business">
         <?= esc($row->business_name) ?> (<?= $totalRef; ?>)<br>
         <p><small class="text-dark"> <span style="color:#d11b2d;">Referred By :</span><?= esc($row->refferd_by_name) ?></small></p>
     </td>

     <td data-label="Phone">
         <?= esc($row->phone) ?>
     </td>

     <td data-label="Location">
         📍 <?= esc($row->city) ?>
     </td>

     <td data-label="Role">
         <span class="role-badge"
             onclick="edit_status('<?= $row->user_code ?>');">
             <?= esc($row->member_role) ?>
         </span>
     </td>

     <td data-label="Status">
         <?php if ($row->status == '1'): ?>
             <span onclick="edit_status('<?= $row->user_code ?>');"
                 class="status active">Active</span>
         <?php elseif ($row->status == '2'): ?>
             <span onclick="edit_status('<?= $row->user_code ?>');"
                 class="status pending">Pending</span>
         <?php else: ?>
             <span onclick="edit_status('<?= $row->user_code ?>');"
                 class="status blocked">Blocked</span>
         <?php endif; ?>
     </td>

     <td data-label="Join Date">
         <?= esc($row->join_date) ?>
     </td>

     <td class="actions">
         <a class="btn btn-info btn-sm me-1"
             style="background:#184b8d;"
             href="<?= base_url('admin/profile/details/' . $row->user_code) ?>">
             <i class="fa fa-eye"></i>
         </a>

         <a class="btn btn-warning btn-sm me-1"
             href="<?= base_url('admin/members/edit_m/' . $row->user_code) ?>">
             <i class="fa fa-edit"></i>
         </a>

         <a class="btn btn-danger btn-sm"
             onclick="delete_user('<?= $row->user_code ?>');">
             <i class="fa fa-trash"></i>
         </a>
     </td>
 </tr>
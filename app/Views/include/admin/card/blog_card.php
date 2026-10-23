 <tr>
     <td><?= $i++; ?></td>

     <!-- IMAGE -->
     <td>
         <div class="member-cell">
             <img src="<?= base_url('uploads/blogs/' . $row->blogs_image) ?>"
                 class="avatar"
                 style="width:60px;height:60px;border-radius:10px;object-fit:cover;object-position:center;">
         </div>
     </td>

     <!-- BLOG NAME -->
     <td class="business">
         <?= esc($row->blogs_name) ?>
     </td>

     <!-- CATEGORY -->
     <td>
         <?= esc($row->blogs_category) ?>
     </td>

     <!-- STATUS -->
     <td>
         <?php if ($row->blogs_status == '1'): ?>
             <span onclick="edit_blogs('<?= $row->blogs_code ?>');"
                 class="status active">Active</span>

         <?php elseif ($row->blogs_status == '2'): ?>
             <span onclick="edit_blogs('<?= $row->blogs_code ?>');"
                 class="status pending">Pending</span>

         <?php else: ?>
             <span onclick="edit_blogs('<?= $row->blogs_code ?>');"
                 class="status blocked">Blocked</span>
         <?php endif; ?>
     </td>

     <!-- ACTION -->
     <td>
         <a class="btn btn-danger btn-sm"
             onclick="delete_blogs('<?= $row->blogs_code ?>');"
             title="Delete">
             <i class="fa fa-trash"></i>
         </a>
     </td>
 </tr>
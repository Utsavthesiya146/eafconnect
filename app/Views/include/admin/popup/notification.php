<!-- edit start-->
<div class="modal fade" id="meeting_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="meeting_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function Add_new_notification() {
        $('#Add_new_notification').modal('show');
    }
</script>
<div class="modal fade" id="Add_new_notification" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">

            <form id="AddNewNotificatongData">

                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0 text-white">Send New Notification</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="row g-2">

                        <!-- Chapter -->
                        <div class="col-md-6">
                            <label class="fw-bold">Select Chapter</label>
                            <select name="chapter_code" id="chapter_code" class="form-select" required>
                                <option value="">-- Select Chapter --</option>
                                <?php foreach ($chapters as $row): ?>
                                    <option value="<?= esc($row['chapter_code']) ?>">
                                        <?= esc($row['chapter_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Members -->
                        <div class="col-md-6">
                            <label class="fw-bold">Members</label>

                            <select name="user_code[]" id="user_code" class="form-select" multiple required>
                                <option value="all">All Chapter Members</option>
                            </select>

                            <small class="text-muted">Hold CTRL to select multiple</small>
                        </div>

                        <!-- Title -->
                        <div class="col-md-12">
                            <label class="fw-bold">Subject</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- Message -->
                        <div class="col-md-12">
                            <label class="fw-bold">Message</label>
                            <textarea name="message" class="form-control summernote" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">
                        Send Notification
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
    $('#chapter_code').on('change', function() {

        let chapter_code = $(this).val();

        if (chapter_code == '') {
            $('#user_code').html('<option value="">-- Select Member --</option><option value="all">All Chapter Members</option>');
            return;
        }

        $.ajax({
            url: "<?= base_url('admin/notifications/get_members_by_chapter') ?>",
            type: "POST",
            data: {
                chapter_code: chapter_code
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">-- Select Member --</option>';
                html += '<option value="all">All Chapter Members</option>';

                $.each(res, function(i, row) {
                    html += `<option value="${row.user_code}">
                            ${row.name} (${row.user_code})
                         </option>`;
                });

                $('#user_code').html(html);
            },
            error: function(xhr) {
                console.log("AJAX Error:", xhr.responseText);
            }
        });

    });
</script>
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">

<!-- jQuery (required) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150,
            placeholder: 'Write your message here...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
<script>
    $(document).on("submit", "#AddNewNotificatongData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/notifications/AddNewNotificatongData'); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            beforeSend: function() {
                Swal.fire({
                    title: "Saving...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },

            success: function(res) {

                if (res.status === "success") {

                    Swal.fire({
                        icon: "success",
                        title: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $("#Add_new_notification").modal("hide");
                        location.reload();
                    });

                } else {

                    Swal.fire({
                        icon: "error",
                        title: res.message
                    });
                }
            },

            error: function(xhr) {
                Swal.fire("Server Error", xhr.responseText, "error");
            }
        });
    });
</script>




<script>
    function delete_notf(n_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/notifications/delete_notf') ?>/" + n_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_notf_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/notifications/delete_notf_action') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: "Deleting...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted",
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        $("#meeting_edit").modal("hide");
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong. Check console."
                });
                console.error(xhr.responseText);
            }
        });
    });
</script>

<script>
    function edi_notf(chapter_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/notifications/edi_notf') ?>/" + chapter_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_not_status_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/notifications/edit_not_status_action') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: "Deleting...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted",
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        $("#meeting_edit").modal("hide");
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong. Check console."
                });
                console.error(xhr.responseText);
            }
        });
    });
</script>
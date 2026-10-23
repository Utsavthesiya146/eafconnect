<!-- edit start-->
<div class="modal fade" id="meeting_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="meeting_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function Add_new_broadcast() {
        $('#Add_new_broadcast').modal('show');
    }
</script>
<div class="modal fade" id="Add_new_broadcast" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:650px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="AddNewBroadcastData" enctype="multipart/form-data">
                <div class="modal-header text-white" style="background:#1b4e8f;">
                    <h5 class="mb-0 text-white">📢 Send New Broadcast</h5>
                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Chapter -->
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">Select Chapter</label>
                            <select name="chapter_code" class="form-select" required>
                                <option value="">-- Select Chapter --</option>
                                <?php foreach ($chapters as $row): ?>
                                    <option value="<?= $row['chapter_code']; ?>">
                                        <?= $row['chapter_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">Title</label>
                            <input type="text" name="title"
                                class="form-control"
                                placeholder="Enter Broadcast Title" required>
                        </div>
                        <!-- Image -->
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">Upload Image</label>
                            <input type="file" name="image"
                                class="form-control"
                                accept="image/*">
                        </div>
                        <!-- Video -->
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">Upload Video</label>
                            <input type="file" name="video"
                                class="form-control"
                                accept="video/*">
                        </div>
                        <!-- Message -->
                        <div class="col-md-12">
                            <label class="fw-bold mb-1">Message</label>
                            <textarea name="message"
                                class="form-control summernote"
                                rows="5"
                                required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                        class="btn text-white px-4"
                        style="background:#1b4e8f;">
                        Send Broadcast
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
    $(document).on("submit", "#AddNewBroadcastData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/broadcast/AddNewBroadcastData'); ?>",
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
    function delete_broadcast(n_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/broadcast/delete_broadcast') ?>/" + n_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_broadcast_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/broadcast/delete_broadcast_action') ?>",
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
    function edit_broadcast(chapter_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/broadcast/edit_broadcast') ?>/" + chapter_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_broadcast_status_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/broadcast/edit_broadcast_status_action') ?>",
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
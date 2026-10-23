<div class="modal fade" id="f2f_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="f2f_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_f2f() {
        $('#add_new_f2f').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_f2f" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">
            <form id="addNewf2fData" enctype="multipart/form-data">
                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;"> Record a F2F Meeting </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="fw-bold">Met With👤</label>
                            <select name="to_user" class="form-select">
                                <option value="">-- Select Member --</option>
                                <?php foreach ($member as $row): ?>
                                    <option value="<?= esc($row['user_code']) ?>">
                                        <?= esc($row['name']) ?> (<?= esc($row['chapter_name']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Date </label>
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Location</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Topics Discussed</label>
                            <textarea name="summary" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">Add F2F</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("submit", "#addNewf2fData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('member/f2f/addNewf2fData'); ?>",
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
                Swal.fire({
                    icon: res.status,
                    title: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $("#add_new_f2f").modal("hide");
                    location.reload();
                });
            },

            error: function(xhr) {
                Swal.fire("Server Error", xhr.responseText, "error");
            }
        });
    });
</script>
<script>
    function delete_f2f(mitb_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('member/f2f/delete_f2f') ?>/" + mitb_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_f2f_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/f2f/delete_f2f_action') ?>",
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
                        $("#f2f_edit").modal("hide");
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
    function edit_f2f(mitb_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('member/f2f/edit_f2f') ?>/" + mitb_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_f2f_action", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('member/f2f/edit_f2f_action') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: "Edit...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Updated",
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        $("#f2f_edit").modal("hide");
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
<div class="modal fade" id="f2f_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="f2f_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_vt() {
        $('#add_new_vt').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_vt" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">
            <form id="AddNewvisitorData" enctype="multipart/form-data">
                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;"> Send Meeting Invitation </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <p >Fill out the details below to invite a visitor to an upcoming chapter meeting.</p>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Visitor Name </label>
                            <input type="text" name="visitor_name" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Company </label>
                            <input type="text" name="company" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Email </label>
                            <input type="text" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Phone </label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Met With👤</label>
                            <select name="date_visited" class="form-select">
                                <option value="">-- Select Member --</option>
                                <?php foreach ($events as $row): ?>
                                    <option value="<?= esc($row['date']) ?>">
                                        <?= esc($row['title']) ?> (<?= esc($row['date']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <p> You can only invite visitors to upcoming chapter meetings.</p>
                        </div>

                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">Send Invitation</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("submit", "#AddNewvisitorData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/visitor/AddNewvisitorData'); ?>",
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
                    $("#add_new_mitb").modal("hide");
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
    function delete_v(visitors_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/visitor/delete_v') ?>/" + visitors_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_v_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/visitor/delete_v_action') ?>",
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
    function edit_v(visitors_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/visitor/edit_v') ?>/" + visitors_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_v_action", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/visitor/edit_v_action') ?>",
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
                        title: "Edit",
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
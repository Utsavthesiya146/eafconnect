<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="chapter_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="chapter_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_new_connects() {
        $('#add_new_connects').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_connects" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">
            <form id="AddNewReferralData" enctype="multipart/form-data">
                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;">New connects Record</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold">Referring Member</label>
                            <select name="from_user" class="form-select">
                                <option value="">-- Select Member --</option>
                                <?php foreach ($member as $row): ?>
                                    <option value="<?= esc($row['user_code']) ?>">
                                        <?= esc($row['name']) ?> (<?= esc($row['user_code']) ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Send Referral To</label>
                            <select name="to_user" class="form-select">
                                <option value="">-- Select Member --</option>
                                <?php foreach ($member as $row): ?>
                                    <option value="<?= esc($row['user_code']) ?>">
                                        <?= esc($row['name']) ?> (<?= esc($row['user_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Business / Opportunity Name</label>
                            <input type="text" name="business" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Contact</label>
                            <input type="text" name="contact_number" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Temperature</label>
                            <select class="form-control" name="temperature" id="">
                                <option value="Hot">Hot</option>
                                <option value="Warm">Warm</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Current Status</label>
                            <select class="form-control" name="status" id="">
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Description / Details</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">Add Referral</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("submit", "#AddNewReferralData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/connect/AddNewReferralData'); ?>",
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
                    icon: res.status === 'success' ? 'success' : 'error',
                    title: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $("#add_new_connects").modal("hide");
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
    function delete_refferral(referral_code) {
        $('#chapter_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/connect/delete_refferral') ?>/" + referral_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_referral_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/connect/delete_referral_action') ?>",
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
                        $("#user_edit").modal("hide");
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
    function edit_refferral_status(referral_code) {
        $('#chapter_edit').modal('show');

        $.ajax({
            url: "<?= site_url('admin/connect/edit_refferral_status') ?>/" + referral_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#edit_refferral_status_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/connect/edit_refferral_status_action') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            beforeSend: function() {
                Swal.fire({
                    title: "Updating...",
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
                        $("#chapter_edit").modal("hide"); // ✅ FIXED
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
                console.error(xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong"
                });
            }
        });
    });
</script>


<script>
    function edit_refferral(referral_code) {
        $('#chapter_edit').modal('show');

        $.ajax({
            url: "<?= site_url('admin/connect/edit_refferral') ?>/" + referral_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#edit_refferral_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/connect/edit_refferral_action') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            beforeSend: function() {
                Swal.fire({
                    title: "Updating...",
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
                        $("#chapter_edit").modal("hide"); // ✅ FIXED
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
                console.error(xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong"
                });
            }
        });
    });
</script>




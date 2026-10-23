<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="user_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="user_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add() {
        $('#add_new_members').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_members" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">

            <form id="AddNewMemeberData" enctype="multipart/form-data">

                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;">Add New Members</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">

                        <div class="col-md-6">
                            <label class="fw-bold">First Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Last Name</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>


                        <div class="col-md-6">
                            <label class="fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">👁</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Account Type</label>
                            <select name="role" class="form-select">
                                <option value="member">Member</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="2">Pending</option>
                                <option value="3">Blocked</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Member Role</label>
                            <select name="member_role" class="form-select">
                                <option value="Member">Member</option>
                                <option>President</option>
                                <option>Vice President</option>
                                <option>Secretary Treasurer</option>
                                <option>Chapter Growth</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Join Date</label>
                            <input type="date" name="join_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Member Type</label>
                            <select name="member_type" class="form-select">
                                <option value="Normal">Normal</option>
                                <option value="Gold">Gold Member 👑</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Refferd By</label>
                            <select name="refferd_by" class="form-select">
                                <option value="">-- Select Member --</option>
                                <?php
                                foreach ($member as $row): ?>
                                    <option value="<?= esc($row['name']) ?> (<?= esc($row['chapter_name']) ?>">
                                        <?= esc($row['name']) ?> (<?= esc($row['chapter_name']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Profile Photo</label>
                            <div class="d-flex gap-3 align-items-center">
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*">
                                <img id="imagePreview"
                                    style="width:60px;height:60px;display:none;object-fit:cover"
                                    class="rounded-circle border">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label class="fw-bold">Business Name</label>
                            <input type="text" name="business_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Business Category</label>
                            <input type="text" name="industry" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Location</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Since</label>
                            <input type="date" name="start_year" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">GST</label>
                            <input type="text" name="gst_number" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">Add Member</button>
                </div>

            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("submit", "#AddNewMemeberData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/members/AddNewMemeberData'); ?>",
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
                        $("#add_new_members").modal("hide");
                        location.reload();
                    });

                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },

            error: function(xhr) {
                Swal.fire("Server Error", xhr.responseText, "error");
            }
        });
    });
</script>

<script>
    function delete_user(user_code) {
        $('#user_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/members/delete_user') ?>/" + user_code,
            success: function(response) {
                $("#user_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_application_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/members/delete_application_action') ?>",
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

    /*
    |--------------------------------------------------------------------------
    | OPEN EDIT STATUS MODAL
    |--------------------------------------------------------------------------
    */
    function edit_status(user_code) {

        if (!user_code) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid User',
                text: 'User code is missing.'
            });
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Show Modal
        |--------------------------------------------------------------------------
        */
        $('#user_edit').modal('show');

        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        $('#user_edit_data').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3"
                     role="status">
                </div>

                <div class="text-muted small">
                    Loading member details...
                </div>
            </div>
        `);

        /*
        |--------------------------------------------------------------------------
        | Load Member Data
        |--------------------------------------------------------------------------
        */
        $.ajax({

            url: "<?= site_url('admin/members/edit_status') ?>/" +
                 encodeURIComponent(user_code),

            type: "GET",

            dataType: "html",

            cache: false,

            success: function(response) {

                $('#user_edit_data').html(response);
            },

            error: function(xhr, status, error) {

                console.error(
                    'Edit Status Error:',
                    xhr.responseText
                );

                $('#user_edit_data').html(`
                    <div class="alert alert-danger text-center m-3">
                        <i class="fa fa-exclamation-triangle me-1"></i>
                        Unable to load member details.
                    </div>
                `);
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT EDIT STATUS FORM
    |--------------------------------------------------------------------------
    | Delegated event is required because the form is loaded by AJAX.
    |--------------------------------------------------------------------------
    */
    $(document).on(
        "submit",
        "#edit_status_action",
        function(e) {

            e.preventDefault();

            const form = this;
            const $button = $(form).find(
                'button[type="submit"]'
            );

            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submit
            |--------------------------------------------------------------------------
            */
            if ($button.prop('disabled')) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Disable Button
            |--------------------------------------------------------------------------
            */
            $button
                .prop('disabled', true)
                .html(`
                    <span class="spinner-border spinner-border-sm me-1"
                          role="status"></span>
                    Updating...
                `);

            /*
            |--------------------------------------------------------------------------
            | AJAX Update
            |--------------------------------------------------------------------------
            */
            $.ajax({

                url: "<?= site_url('admin/members/edit_status_action') ?>",

                type: "POST",

                data: $(form).serialize(),

                dataType: "json",

                beforeSend: function() {

                    Swal.fire({
                        title: "Updating Member...",
                        text: "Please wait",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: function() {
                            Swal.showLoading();
                        }
                    });

                },

                success: function(res) {

                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS
                    |--------------------------------------------------------------------------
                    */
                    if (res.status === "success") {

                        Swal.fire({
                            icon: "success",
                            title: "Updated Successfully",
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        }).then(function() {

                            $('#user_edit').modal('hide');

                            /*
                            | Reload page so member card/table
                            | reflects the new status.
                            */
                            location.reload();

                        });

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | APPLICATION ERROR
                    |--------------------------------------------------------------------------
                    */
                    else {

                        $button
                            .prop('disabled', false)
                            .html(`
                                <i class="fa fa-check me-1"></i>
                                Update
                            `);

                        Swal.fire({
                            icon: "error",
                            title: "Update Failed",
                            text: res.message ||
                                  "Unable to update member."
                        });
                    }
                },

                /*
                |--------------------------------------------------------------------------
                | SERVER / AJAX ERROR
                |--------------------------------------------------------------------------
                */
                error: function(xhr, status, error) {

                    console.error(
                        "AJAX Error:",
                        xhr.responseText
                    );

                    $button
                        .prop('disabled', false)
                        .html(`
                            <i class="fa fa-check me-1"></i>
                            Update
                        `);

                    let message =
                        "Something went wrong. Please try again.";

                    /*
                    |--------------------------------------------------------------------------
                    | Try to Read JSON Error
                    |--------------------------------------------------------------------------
                    */
                    if (xhr.responseText) {

                        try {

                            const response =
                                JSON.parse(xhr.responseText);

                            if (response.message) {
                                message = response.message;
                            }

                        } catch (e) {

                            console.error(
                                "Invalid JSON response:",
                                xhr.responseText
                            );
                        }
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: message
                    });
                }

            });
        }
    );

</script>


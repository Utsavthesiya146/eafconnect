<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="meeting_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="meeting_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_new_invitations() {
        $('#add_new_invitations').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<!-- ADD MEETING MODAL -->
<div class="modal fade" id="add_new_invitations" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">
            <form id="AddNewInvitationsData">
                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0 text-white">Send Invitation</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="fw-bold">Visitor Name</label>
                            <input type="text" name="guest_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Visitor Email</label>
                            <input type="email" name="guest_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Visitor Phone </label>
                            <input type="text" name="guest_phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Visitor Company</label>
                            <input type="text" name="guest_company" class="form-control">
                        </div>
                        <!-- Title -->
                        <div class="col-md-12">
                            <label class="fw-bold">Visitor Message</label>
                            <textarea name="message" class="form-control"></textarea>
                        </div>

                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">
                        Send Invitation Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on("submit", "#AddNewInvitationsData", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/Invitations/AddNewInvitationsData'); ?>",
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
                        $("#add_new_invitations").modal("hide");
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
    function delete_invitation(invitations_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/invitations/delete_invitation') ?>/" + invitations_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_invitation_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/invitations/delete_invitation_action') ?>",
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
    function edit_invitation(invitations_code) {
        $('#meeting_edit').modal('show');

        $.ajax({
            url: "<?= site_url('admin/invitations/edit_invitation') ?>/" + invitations_code,
            type: "GET",
            success: function(response) {
                $("#meeting_edit_data").html(response);
            },
            error: function() {
                alert("Failed to load meeting.");
            }
        });
    }


    /* ===============================
       UPDATE FORM SUBMIT
    ================================*/
    $(document).on("submit", "#edit_invitation_action", function(e) {

        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/invitations/edit_invitation_action') ?>",
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
                        title: "Success",
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
                console.log(xhr.responseText);
                Swal.fire("Server Error", "Something went wrong", "error");
            }
        });

    });
</script>
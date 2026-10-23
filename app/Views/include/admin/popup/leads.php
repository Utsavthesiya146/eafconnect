<!-- edit start-->
<div class="modal fade" id="meeting_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="meeting_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function delete_leads(lead_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/leads/delete_leads') ?>/" + lead_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_leads_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/leads/delete_leads_action') ?>",
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
    function edit_leads(lead_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/leads/edit_leads') ?>/" + lead_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    /* ===============================
       UPDATE FORM SUBMIT
    ================================ */
    $(document).on("submit", "#edit_leads_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/leads/edit_leads_action') ?>",
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
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {

                        $("#meeting_edit").modal("hide");
                        location.reload();

                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },

            error: function(xhr) {
                console.log(xhr.responseText);

                Swal.fire(
                    "Server Error",
                    "Something went wrong.",
                    "error"
                );
            }
        });
    });
</script>


<script>
    function add_member(lead_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/leads/add_member') ?>/" + lead_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    /* ===============================
       UPDATE FORM SUBMIT
    ================================ */
    $(document).on("submit", "#add_member_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/leads/add_member_action') ?>",
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
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {

                        $("#meeting_edit").modal("hide");
                        location.reload();

                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },

            error: function(xhr) {
                console.log(xhr.responseText);

                Swal.fire(
                    "Server Error",
                    "Something went wrong.",
                    "error"
                );
            }
        });
    });
</script>
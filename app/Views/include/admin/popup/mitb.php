<div class="modal fade" id="mitb_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="mitb_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>

<script>
    function delete_mitb(mitb_code) {
        $('#mitb_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/mitb/delete_mitb') ?>/" + mitb_code,
            success: function(response) {
                $("#mitb_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_mitb_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/mitb/delete_mitb_action') ?>",
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
                        $("#mitb_edit").modal("hide");
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
    function edit_mitb(mitb_code) {
        $('#mitb_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/mitb/edit_mitb') ?>/" + mitb_code,
            success: function(response) {
                $("#mitb_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_mitb_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/mitb/edit_mitb_action') ?>",
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
                        $("#mitb_edit").modal("hide");
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
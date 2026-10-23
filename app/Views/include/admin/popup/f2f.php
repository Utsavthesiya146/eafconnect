<div class="modal fade" id="f2f_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="f2f_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>

<script>
    function delete_f2f(mitb_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/f2f/delete_f2f') ?>/" + mitb_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_f2f_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/f2f/delete_f2f_action') ?>",
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
            url: "<?= site_url('admin/f2f/edit_f2f') ?>/" + mitb_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_f2f_action", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/f2f/edit_f2f_action') ?>",
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
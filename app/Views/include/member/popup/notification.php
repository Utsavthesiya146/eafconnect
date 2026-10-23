<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="meeting_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="meeting_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>


<script>
    function edi_notf(chapter_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('member/notifications/edi_notf') ?>/" + chapter_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_not_status_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/notifications/edit_not_status_action') ?>",
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
    function readNotification(n_code, btn) {

        $.ajax({
            url: "<?= base_url('member/notifications/edit_not_status_action') ?>",
            type: "POST",
            data: {
                n_code: n_code,
                status: 'read'
            },
            dataType: "json",

            success: function(response) {

                if (response.status == 'success') {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Marked as read',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    // Reload Page
                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                } else {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Failed to update',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },

            error: function() {

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Server error',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });

    }
</script>
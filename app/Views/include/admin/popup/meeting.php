<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="meeting_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="meeting_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_new_meeting() {
        $('#add_new_meeting').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<!-- ADD MEETING MODAL -->
<div class="modal fade" id="add_new_meeting" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">

            <form id="AddNewMeetingData">

                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0 text-white">Global Meeting Details</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">

                        <!-- Chapter -->
                        <div class="col-md-12">
                            <label class="fw-bold">Select Chapter</label>

                            <select name="chapter_code" class="form-select" required>
                                <option value="">-- Select Chapter --</option>

                                <?php
                                $commonModel = new \App\Models\CommonModel();
                                $chapters = $commonModel
                                    ->exe_query("
                SELECT chapter_code, chapter_name
                FROM chapters
                WHERE chapter_status='1'
            ")
                                    ->getResultArray();

                                foreach ($chapters as $row): ?>
                                    <option value="<?= esc($row['chapter_code']) ?>">
                                        <?= esc($row['chapter_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label class="fw-bold">Meeting Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <!-- Time -->
                        <div class="col-md-6">
                            <label class="fw-bold">Meeting Time</label>
                            <input type="time" name="time" class="form-control">
                        </div>

                        <!-- Title -->
                        <div class="col-md-12">
                            <label class="fw-bold">Meeting Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Meeting Mode</label>
                            <input type="text" name="mode" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Meeting Link</label>
                            <input type="text" name="meeting_link" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Location</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Venue</label>
                            <input type="text" name="venue" class="form-control">
                        </div>
                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="fw-bold">Agenda / Notes</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">
                        Create Meeting
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).on("submit", "#AddNewMeetingData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/Meetings/AddNewMeetingData'); ?>",
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
                    icon: "success",
                    title: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $("#add_new_meeting").modal("hide");
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
    function delete_meeting(chapter_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/meetings/delete_meeting') ?>/" + chapter_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_meeting_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/meetings/delete_meeting_action') ?>",
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
 

    function edit_meeting(event_code) {
        $('#meeting_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/meetings/edit_meeting') ?>/" + event_code,
            success: function(response) {
                $("#meeting_edit_data").html(response);
            }
        });
    }
    $(document).on("submit", "#edit_meeting_action", function(e) {

        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/meetings/edit_meeting_action') ?>",
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
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
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

            error: function(xhr, status, error) {
                console.log("Status:", status);
                console.log("Error:", error);
                console.log(xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Check console for details."
                });
            }

        });

    });
</script>
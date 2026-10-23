<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="chapter_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="chapter_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_new_chapter() {
        $('#add_new_chapter').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_chapter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">
            <form id="AddNewChapterData" enctype="multipart/form-data">
                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;">Add New Chapter</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="fw-bold">Chapter Name</label>
                            <input type="text" name="chapter_name" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Location</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Chapter Leader</label>
                            <select name="leader_code" class="form-select">
                                <option value="">-- Select Leader --</option>
                                <?php foreach ($member as $row): ?>
                                    <option value="<?= esc($row['user_code']) ?>">
                                        <?= esc($row['name']) ?> (<?= esc($row['user_code']) ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Chapter Profile</label>
                            <input type="file" name="chapter_profile" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Chapter Title</label>
                            <input type="text" name="chapter_title" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">About Chapter</label>
                            <textarea name="chapter_description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">Create Chapter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("submit", "#AddNewChapterData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/Chapter/AddNewChapterData'); ?>",
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
                    $("#add_new_chapter").modal("hide");
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
    function delete_chapter(chapter_code) {
        $('#chapter_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/chapter/delete_chapter') ?>/" + chapter_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_chapter_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/Chapter/delete_chapter_action') ?>",
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
    function edit_chapter_status(chapter_code) {
        $('#chapter_edit').modal('show');

        $.ajax({
            url: "<?= site_url('admin/chapter/edit_chapter_status') ?>/" + chapter_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#edit_chapter_status_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/chapter/edit_chapter_status_action') ?>",
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
    function edit_chapter(chapter_code) {
        $('#chapter_edit').modal('show');

        $.ajax({
            url: "<?= site_url('admin/chapter/edit_chapter') ?>/" + chapter_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#edit_chapter_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/chapter/edit_chapter_action') ?>",
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
    function add_chapter_members(chapter_code) {
        $('#chapter_edit').modal('show');

        $.ajax({
            url: "<?= site_url('admin/chapter/add_chapter_members') ?>/" + chapter_code,
            success: function(response) {
                $("#chapter_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#add_chapter_members_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/chapter/add_chapter_members_action') ?>",
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
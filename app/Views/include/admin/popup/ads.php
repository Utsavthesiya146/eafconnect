<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="user_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="user_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_ads() {
        $('#add_ads').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_ads" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">

            <form id="AddNewAdsData" enctype="multipart/form-data">

                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;">Add New Ads</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Name</label>
                        <input type="text" name="ads_name" class="form-control form-control-sm" placeholder="Ads Name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Thumbnail (JPG, JPEG, PNG)</label>
                        <input type="file" name="ads_image" class="form-control form-control-sm" required>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="fw-bold">Chapters</label>
                            <select name="chapter_code" class="form-select">
                                <option value="">-- Select Chapters --</option>
                                <?php foreach ($chapters as $row): ?>
                                    <option value="<?= esc($row['chapter_code']) ?>">
                                        <?= esc($row['chapter_name']) ?> (<?= esc($row['chapter_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                                <option value="all">All Chapters</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">From</label>
                                <input type="date" name="from_date" class="form-control form-control-sm" placeholder="Ads Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">To</label>
                                <input type="date" name="to_date" class="form-control form-control-sm" placeholder="Ads Title" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Category</label>
                        <input type="text" name="ads_category" class="form-control form-control-sm" placeholder="Ads Category" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Ads Description</label>
                        <textarea class="form-control form-control-sm" id="summernote" name="ads_discription" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-end border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 m-1 p-1" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" class="btn btn-sm text-white px-3 m-1 p-1" style="background-color:#0a8260;">Post</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Summernote Includes -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Write blog content here...',
            tabsize: 2,
            height: 150
        });
    });
</script>
<script>
    $(document).on("submit", "#AddNewAdsData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/ads/AddNewAdsData'); ?>",
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
    function delete_ads(user_code) {
        $('#user_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/ads/delete_ads') ?>/" + user_code,
            success: function(response) {
                $("#user_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_ads_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/ads/delete_ads_action') ?>",
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
    function edit_ads(user_code) {
        $('#user_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/ads/edit_ads') ?>/" + user_code,
            success: function(response) {
                $("#user_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_ads_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/ads/edit_ads_action') ?>",
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
                        title: "Updated",
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
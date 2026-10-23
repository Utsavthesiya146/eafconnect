<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="user_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="user_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_blog() {
        $('#add_blog').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_blog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">

            <form id="AddNewBlogData" enctype="multipart/form-data">

                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;">Add New Blogs</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Blog Name</label>
                        <input type="text" name="blogs_name" class="form-control form-control-sm" placeholder="Blog Name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Blog Thumbnail (JPG, JPEG, PNG)</label>
                        <input type="file" name="blogs_image" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Blog Title</label>
                        <input type="text" name="blogs_title" class="form-control form-control-sm" placeholder="Blog Title" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Blog Category</label>
                        <input type="text" name="blogs_category" class="form-control form-control-sm" placeholder="Blog Category" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Blog Description</label>
                        <textarea class="form-control form-control-sm" id="summernote" name="blogs_discription" rows="4"></textarea>
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
    $(document).on("submit", "#AddNewBlogData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('admin/blogs/AddNewBlogData'); ?>",
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
    function delete_blogs(user_code) {
        $('#user_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/blogs/delete_blogs') ?>/" + user_code,
            success: function(response) {
                $("#user_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_blogs_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/blogs/delete_blogs_action') ?>",
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
    function edit_blogs(user_code) {
        $('#user_edit').modal('show');
        $.ajax({
            url: "<?= site_url('admin/blogs/edit_blogs') ?>/" + user_code,
            success: function(response) {
                $("#user_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_blogs_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('admin/blogs/edit_blogs_action') ?>",
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
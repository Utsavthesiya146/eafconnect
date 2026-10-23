<div class="modal fade" id="mitb_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="mitb_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_mitb() {
        $('#add_new_mitb').modal('show');
    }
</script>
<!-- edit end -->
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_mitb" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,.1);font-size:13px;">
            <form id="addNewmitbData" enctype="multipart/form-data">
                <!-- HEADER -->
                <div class="modal-header" style="background:#1b4e8f;color:#fff;">
                    <h6 class="mb-0" style="color:#ffffff;">Reported Closed MITB 💸 </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold">Who gave you this business? 🤝</label>
                            <select class="form-control" name="category" id="business_source">
                                <option value="">-- Select Source --</option>
                                <option value="My Chapter Member">My Chapter Member</option>
                                <option value="Across Chapter Member">Across Chapter Member</option>
                                <option value="Visitor">Visitor</option>
                                <option value="New">New</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Select Member Who Gave Business 👤</label>
                            <select name="to_user" id="member_list" class="form-select">
                                <option value="">-- Select Member --</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Business Closed Amount (INR) 💰</label>
                            <input type="text" name="amount" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Business Type 📈</label>
                            <select class="form-control" name="business_type" id="">
                                <option value="New">New Business</option>
                                <option value="Repeat">Repeat Business</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Date of Business </label>
                            <input type="date" name="created_at" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Description of Business ✨</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#1b4e8f;">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#business_source').change(function() {

            let source = $(this).val();

            if (source == 'My Chapter Member') {
                loadMembers('chapter');
            } else if (source == 'Across Chapter Member') {
                loadMembers('all');
            } else {
                $('#member_list').html('<option value="">-- Not Required --</option>');
            }

        });

    });

    function loadMembers(type) {
        $.ajax({
            url: "<?= base_url('member/connect/getMembers') ?>",
            type: "POST",
            data: {
                type: type
            },
            dataType: "json",
            success: function(res) {
                let html = '<option value="">-- Select Member --</option>';

                $.each(res, function(i, m) {
                    html += `<option value="${m.user_code}">
                            ${m.name}
                        </option>`;
                });

                $('#member_list').html(html);
            }
        });
    }
</script>
<script>
    $(document).on("submit", "#addNewmitbData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('member/mitb/addNewmitbData'); ?>",
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
                    icon: res.status,
                    title: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $("#add_new_mitb").modal("hide");
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
    function delete_mitb(mitb_code) {
        $('#mitb_edit').modal('show');
        $.ajax({
            url: "<?= site_url('member/mitb/delete_mitb') ?>/" + mitb_code,
            success: function(response) {
                $("#mitb_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_mitb_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/mitb/delete_mitb_action') ?>",
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
            url: "<?= site_url('member/mitb/edit_mitb') ?>/" + mitb_code,
            success: function(response) {
                $("#mitb_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_mitb_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/mitb/edit_mitb_action') ?>",
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
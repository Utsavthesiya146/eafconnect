<style>
    /* ===== MODERN SWEET ALERT UI ===== */
    .swal2-popup {
        border-radius: 16px !important;
        padding: 28px 26px !important;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 25px 70px rgba(0, 0, 0, .18) !important;
        animation: popupFade .25s ease;
    }

    /* smooth popup animation */
    @keyframes popupFade {
        from {
            transform: translateY(20px) scale(.96);
            opacity: 0;
        }

        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
    /* title */
    .swal2-title {
        font-size: 20px !important;
        font-weight: 600 !important;
        color: #2b2b2b;
    }

    /* text */
    .swal2-html-container {
        font-size: 14px;
        color: #6c757d;
    }

    /* backdrop blur */
    .swal2-container.swal2-backdrop-show {
        backdrop-filter: blur(5px);
        background: rgba(0, 0, 0, .35) !important;
    }

    /* loader size */
    .swal2-loader {
        border-width: 3px !important;
    }

    /* buttons */
    .swal2-confirm {
        border-radius: 8px !important;
        padding: 8px 18px !important;
        font-weight: 500;
    }

    /* success icon smoother */
    .swal2-success-ring {
        border-width: 3px !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on("submit", "#updateMemberForm", function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find("button[type='submit']");
        $.ajax({
            url: "<?= site_url('admin/profile/update_profile_data_action'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
               `);
                Swal.fire({
                    title: "Updating Profile",
                    html: `
                    <div style="font-size:13px">
                        Syncing your latest information securely...
                    </div>
                `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                Swal.close();
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1600);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message,
                        confirmButtonColor: "#dc3545"
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect. Please try again.",
                    confirmButtonColor: "#dc3545"
                });
                console.log(xhr.responseText);
            },
            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>


<script>
    $(document).on("submit", "#updateProfessionalSkillsForm", function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find("button[type='submit']");
        $.ajax({
            url: "<?= site_url('admin/profile/updateProfessionalSkillsForm'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
               `);
                Swal.fire({
                    title: "Updating Profile",
                    html: `
                    <div style="font-size:13px">
                        Syncing your latest information securely...
                    </div>
                `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                Swal.close();
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1600);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message,
                        confirmButtonColor: "#dc3545"
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect. Please try again.",
                    confirmButtonColor: "#dc3545"
                });
                console.log(xhr.responseText);
            },
            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>



<script>
    $(document).on("submit", "#UpdateBusinessInformationForm", function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find("button[type='submit']");
        $.ajax({
            url: "<?= site_url('admin/profile/UpdateBusinessInformationForm'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
               `);
                Swal.fire({
                    title: "Updating Profile",
                    html: `
                    <div style="font-size:13px">
                        Syncing your latest information securely...
                    </div>
                `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                Swal.close();
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1600);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message,
                        confirmButtonColor: "#dc3545"
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect. Please try again.",
                    confirmButtonColor: "#dc3545"
                });
                console.log(xhr.responseText);
            },
            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>



<script>
    $(document).on("submit", "#UpdateBusinessLocationForm", function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find("button[type='submit']");
        $.ajax({
            url: "<?= site_url('admin/profile/UpdateBusinessLocationForm'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
               `);
                Swal.fire({
                    title: "Updating Profile",
                    html: `
                    <div style="font-size:13px">
                        Syncing your latest information securely...
                    </div>
                `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                Swal.close();
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1600);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message,
                        confirmButtonColor: "#dc3545"
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect. Please try again.",
                    confirmButtonColor: "#dc3545"
                });
                console.log(xhr.responseText);
            },
            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>

<script>
    $(document).on("submit", "#updateSocialMedia", function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find("button[type='submit']");
        $.ajax({
            url: "<?= site_url('admin/profile/updateSocialMedia'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
               `);
                Swal.fire({
                    title: "Updating Profile",
                    html: `
                    <div style="font-size:13px">
                        Syncing your latest information securely...
                    </div>
                `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                Swal.close();
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1600);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message,
                        confirmButtonColor: "#dc3545"
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect. Please try again.",
                    confirmButtonColor: "#dc3545"
                });
                console.log(xhr.responseText);
            },
            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>


<script>
    $(document).on("submit", "#updateAwardsData", function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find("button[type='submit']");
        $.ajax({
            url: "<?= site_url('admin/profile/updateAwardsData'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
               `);
                Swal.fire({
                    title: "Updating Profile",
                    html: `
                    <div style="font-size:13px">
                        Syncing your latest information securely...
                    </div>
                `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                Swal.close();
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1600);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message,
                        confirmButtonColor: "#dc3545"
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect. Please try again.",
                    confirmButtonColor: "#dc3545"
                });
                console.log(xhr.responseText);
            },
            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>

<script>
    $(document).on("submit", "#updateMediaDocumentData", function(e) {
        e.preventDefault();

        const form = this;
        const btn = $(form).find("button[type='submit']");

        // ✅ Use FormData for file upload
        const formData = new FormData(form);

        $.ajax({
            url: "<?= site_url('admin/profile/updateMediaDocumentData'); ?>",
            type: "POST",
            data: formData,
            processData: false, // ✅ REQUIRED
            contentType: false, // ✅ REQUIRED
            dataType: "json",

            beforeSend: function() {
                btn.prop("disabled", true)
                    .addClass("opacity-75")
                    .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Saving Changes...
                `);

                Swal.fire({
                    title: "Updating Profile",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },

            success: function(res) {
                Swal.close();

                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated Successfully",
                        text: res.message,
                        timer: 1700,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 1600);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: res.message
                    });
                }
            },

            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to connect."
                });

                console.log(xhr.responseText);
            },

            complete: function() {
                btn.prop("disabled", false)
                    .removeClass("opacity-75")
                    .html(`<i class="fas fa-save me-1"></i> Update Profile`);
            }
        });
    });
</script>

<script>
    $(document).on("submit", "#updateChangePasswordtData", function(e) {
        e.preventDefault();

        let form = $(this);
        let btn = form.find("button[type='submit']");

        $.ajax({
            url: "<?= site_url('admin/profile/updateChangePasswordtData'); ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",

            beforeSend: function() {
                btn.prop("disabled", true);

                Swal.fire({
                    title: "Updating Password...",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },

            success: function(res) {

                // ✅ safely replace loading popup
                if (res.status === "success") {

                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    form[0].reset();

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

                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong"
                });
            },

            complete: function() {
                btn.prop("disabled", false);
            }
        });
    });
</script>
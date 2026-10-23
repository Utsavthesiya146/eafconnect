<script>
$(document).on('submit', '#contact_form', function(e) {

    e.preventDefault();
    e.stopPropagation();

    const form = this;
    const $form = $(form);
    const $button = $('#submitLeadBtn');

    console.log('FORM SUBMIT INTERCEPTED');
    console.log('AJAX URL:', '<?= site_url('addlead') ?>');

    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }

    $.ajax({
        url: '<?= site_url('addlead') ?>',
        type: 'POST',
        data: $form.serialize(),
        dataType: 'json',

        beforeSend: function() {

            $button.prop('disabled', true);

            Swal.fire({
                title: 'Submitting...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        },

        success: function(res) {

            console.log('Response:', res);

            Swal.close();

            if (res.status === 'success') {

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {

                    $form[0].reset();

                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message || 'Unable to submit.'
                });
            }
        },

        error: function(xhr) {

            Swal.close();

            console.error('HTTP:', xhr.status);
            console.error('Response:', xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Unable to submit the form.'
            });
        },

        complete: function() {
            $button.prop('disabled', false);
        }
    });

    return false;
});
</script><?php if (session()->getFlashdata('success')): ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: <?= json_encode(session()->getFlashdata('success')) ?>,
        confirmButtonText: 'OK',
        confirmButtonColor: '#5db812'
    });

});
</script>

<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: <?= json_encode(session()->getFlashdata('error')) ?>,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
    });

});
</script>

<?php endif; ?><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- ========================= JOIN COMMUNITY MODAL ========================= -->
<div class="modal fade"
    id="joinCommunityModal"
    tabindex="-1"
    aria-labelledby="joinCommunityLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content crm-modal">

            <!-- ================= HEADER ================= -->
            <div class="crm-modal-header">

                <div class="d-flex align-items-center gap-2">

                    <div class="crm-header-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>

                    <div>
                        <h5 class="crm-modal-title" id="joinCommunityLabel">
                            Join EAF Connect
                        </h5>

                        <div class="crm-modal-subtitle">
                            Business Network Registration
                        </div>
                    </div>

                </div>

                <button type="button"
                    class="crm-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>

            </div>


            <!-- ================= BODY ================= -->
            <div class="modal-body crm-modal-body">

                <!-- Intro -->
                <div class="crm-intro">

                    <div class="crm-intro-icon">
                        <i class="fas fa-handshake"></i>
                    </div>

                    <div>
                        <h6>Build meaningful business connections</h6>
                        <p>
                            Complete your business details to join the EAF Connect network.
                        </p>
                    </div>

                </div>


                <!-- Form -->
                <form  action="<?= base_url(); ?>addlead" method="post">

                    <!-- ================= PERSONAL DETAILS ================= -->
                    <div class="crm-section">

                        <div class="crm-section-header">
                            <div class="crm-section-icon">
                                <i class="fas fa-user"></i>
                            </div>

                            <div>
                                <h6>Personal Details</h6>
                                <small>Tell us about yourself</small>
                            </div>
                        </div>

                        <div class="row g-3">

                            <!-- First Name -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    First Name <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-user"></i>

                                    <input type="text"
                                        name="name"
                                        class="form-control crm-input"
                                        placeholder="Enter first name"
                                        autocomplete="given-name"
                                        required>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Last Name <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-user"></i>

                                    <input type="text"
                                        name="last_name"
                                        class="form-control crm-input"
                                        placeholder="Enter last name"
                                        autocomplete="family-name"
                                        required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Email Address <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-envelope"></i>

                                    <input type="email"
                                        name="email"
                                        class="form-control crm-input"
                                        placeholder="name@example.com"
                                        autocomplete="email"
                                        required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Phone Number <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-phone"></i>

                                    <input type="tel"
                                        name="phone"
                                        class="form-control crm-input"
                                        placeholder="Enter phone number"
                                        autocomplete="tel"
                                        required>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- ================= BUSINESS DETAILS ================= -->
                    <div class="crm-section">

                        <div class="crm-section-header">

                            <div class="crm-section-icon business">
                                <i class="fas fa-building"></i>
                            </div>

                            <div>
                                <h6>Business Information</h6>
                                <small>Tell us about your business</small>
                            </div>

                        </div>

                        <div class="row g-3">

                            <!-- Business Name -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Business Name <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-building"></i>

                                    <input type="text"
                                        name="business"
                                        class="form-control crm-input"
                                        placeholder="Enter business name"
                                        required>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Business Category <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-layer-group"></i>

                                    <input type="text"
                                        name="category"
                                        class="form-control crm-input"
                                        placeholder="e.g. IT, Finance, Retail"
                                        required>
                                </div>
                            </div>

                            <!-- Since -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Business Since <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-calendar-alt"></i>

                                    <input type="date"
                                        name="since"
                                        class="form-control crm-input"
                                        required>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="col-md-6">
                                <label class="crm-label">
                                    Business Location <span>*</span>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-map-marker-alt"></i>

                                    <input type="text"
                                        name="location"
                                        class="form-control crm-input"
                                        placeholder="City / Location"
                                        required>
                                </div>
                            </div>

                            <!-- GST -->
                            <div class="col-12">
                                <label class="crm-label">
                                    GST Number
                                    <small>(Optional)</small>
                                </label>

                                <div class="crm-input-group">
                                    <i class="fas fa-file-invoice"></i>

                                    <input type="text"
                                        name="gst"
                                        class="form-control crm-input"
                                        placeholder="Enter GST number if applicable">
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- ================= SUBMIT ================= -->
                    <div class="crm-submit-area">

                        <div class="crm-security">
                            <i class="fas fa-shield-alt"></i>

                            <span>
                                Your information is secure and confidential.
                            </span>
                        </div>

                        <button type="submit" id="submitLeadBtn" class="crm-submit-btn">
                            <span>
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Registration
                            </span>
                            <i class="fas fa-arrow-right crm-submit-arrow"></i>
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>


<!-- ========================= CRM MODAL CSS ========================= -->
<style>
    /* =========================================================
   CRM MODAL
========================================================= */

    #joinCommunityModal .modal-dialog {
        max-width: 820px;
    }

    #joinCommunityModal .crm-modal {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        background: #f7f9fc;
        box-shadow: 0 25px 70px rgba(0, 0, 0, .22);
    }


    /* =========================================================
   HEADER
========================================================= */

    #joinCommunityModal .crm-modal-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        background: linear-gradient(135deg,
                #08426b 0%,
                #0a507f 55%,
                #b91f16 150%);
        color: #fff;
    }

    #joinCommunityModal .crm-header-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .16);
        font-size: 17px;
    }

    #joinCommunityModal .crm-modal-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: -.2px;
    }

    #joinCommunityModal .crm-modal-subtitle {
        margin-top: 2px;
        color: rgba(255, 255, 255, .72);
        font-size: 12px;
    }

    #joinCommunityModal .crm-close {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 8px;
        background: rgba(255, 255, 255, .10);
        color: #fff;
        font-size: 13px;
        transition: .25s ease;
    }

    #joinCommunityModal .crm-close:hover {
        background: rgba(255, 255, 255, .20);
        transform: rotate(90deg);
    }


    /* =========================================================
   BODY
========================================================= */

    #joinCommunityModal .crm-modal-body {
        padding: 22px;
        background: #f7f9fc;
    }


    /* =========================================================
   INTRO
========================================================= */

    #joinCommunityModal .crm-intro {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px 15px;
        margin-bottom: 18px;
        border: 1px solid #e5eaf0;
        border-radius: 11px;
        background: #fff;
    }

    #joinCommunityModal .crm-intro-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #eef5fa;
        color: #08426b;
    }

    #joinCommunityModal .crm-intro h6 {
        margin: 0 0 2px;
        color: #1d2939;
        font-size: 13px;
        font-weight: 700;
    }

    #joinCommunityModal .crm-intro p {
        margin: 0;
        color: #7a8699;
        font-size: 12px;
    }


    /* =========================================================
   SECTIONS
========================================================= */

    #joinCommunityModal .crm-section {
        padding: 18px;
        margin-bottom: 15px;
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 13px;
    }

    #joinCommunityModal .crm-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf0f4;
    }

    #joinCommunityModal .crm-section-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eef5fa;
        color: #08426b;
        font-size: 13px;
    }

    #joinCommunityModal .crm-section-icon.business {
        background: #fff1ef;
        color: #b91f16;
    }

    #joinCommunityModal .crm-section-header h6 {
        margin: 0;
        color: #1d2939;
        font-size: 13px;
        font-weight: 700;
    }

    #joinCommunityModal .crm-section-header small {
        display: block;
        margin-top: 2px;
        color: #98a2b3;
        font-size: 11px;
    }


    /* =========================================================
   LABELS
========================================================= */

    #joinCommunityModal .crm-label {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
    }

    #joinCommunityModal .crm-label span {
        color: #d92d20;
    }

    #joinCommunityModal .crm-label small {
        color: #98a2b3;
        font-weight: 400;
    }


    /* =========================================================
   INPUTS
========================================================= */

    #joinCommunityModal .crm-input-group {
        position: relative;
    }

    #joinCommunityModal .crm-input-group>i {
        position: absolute;
        left: 13px;
        top: 50%;
        z-index: 2;
        transform: translateY(-50%);
        color: #98a2b3;
        font-size: 13px;
        pointer-events: none;
        transition: .2s ease;
    }

    #joinCommunityModal .crm-input {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px 10px 38px;
        border: 1px solid #dfe4ea;
        border-radius: 9px;
        background: #fff;
        color: #344054;
        font-size: 13px;
        outline: none;
        box-shadow: none;
        transition: all .2s ease;
    }

    #joinCommunityModal .crm-input::placeholder {
        color: #a6afbb;
        font-size: 12px;
    }

    #joinCommunityModal .crm-input:hover {
        border-color: #c7ced8;
    }

    #joinCommunityModal .crm-input:focus {
        border-color: #08426b;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(8, 66, 107, .08);
    }

    #joinCommunityModal .crm-input:focus+* {
        color: #08426b;
    }


    /* Date input */
    #joinCommunityModal input[type="date"] {
        color: #667085;
    }


    /* =========================================================
   SUBMIT AREA
========================================================= */

    #joinCommunityModal .crm-submit-area {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 3px 2px 0;
    }

    #joinCommunityModal .crm-security {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #98a2b3;
        font-size: 11px;
    }

    #joinCommunityModal .crm-security i {
        color: #12b76a;
        font-size: 12px;
    }


    /* =========================================================
   SUBMIT BUTTON
========================================================= */

    #joinCommunityModal .crm-submit-btn {
        min-width: 225px;
        min-height: 46px;
        padding: 0 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        border: 0;
        border-radius: 9px;
        background: linear-gradient(135deg,
                #08426b,
                #0b527f);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 5px 14px rgba(8, 66, 107, .18);
        transition: all .25s ease;
    }

    #joinCommunityModal .crm-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(8, 66, 107, .25);
    }

    #joinCommunityModal .crm-submit-arrow {
        transition: .25s ease;
        font-size: 12px;
    }

    #joinCommunityModal .crm-submit-btn:hover .crm-submit-arrow {
        transform: translateX(4px);
    }


    /* =========================================================
   MOBILE
========================================================= */

    @media (max-width: 767.98px) {

        #joinCommunityModal .modal-dialog {
            margin: 8px;
        }

        #joinCommunityModal .crm-modal {
            border-radius: 13px;
        }

        #joinCommunityModal .crm-modal-header {
            padding: 15px;
        }

        #joinCommunityModal .crm-modal-body {
            padding: 12px;
        }

        #joinCommunityModal .crm-header-icon {
            width: 40px;
            height: 40px;
            font-size: 15px;
        }

        #joinCommunityModal .crm-modal-title {
            font-size: 16px;
        }

        #joinCommunityModal .crm-modal-subtitle {
            font-size: 11px;
        }

        #joinCommunityModal .crm-section {
            padding: 14px;
            margin-bottom: 12px;
        }

        #joinCommunityModal .crm-section-header {
            margin-bottom: 14px;
        }

        #joinCommunityModal .crm-intro {
            padding: 11px;
            margin-bottom: 12px;
        }

        #joinCommunityModal .crm-input {
            min-height: 42px;
            font-size: 12px;
        }

        #joinCommunityModal .crm-submit-area {
            flex-direction: column;
            align-items: stretch;
        }

        #joinCommunityModal .crm-security {
            justify-content: center;
            order: 2;
        }

        #joinCommunityModal .crm-submit-btn {
            width: 100%;
            order: 1;
        }

    }


    /* =========================================================
   SMALL MOBILE
========================================================= */

    @media (max-width: 480px) {

        #joinCommunityModal .crm-modal-header {
            padding: 13px;
        }

        #joinCommunityModal .crm-modal-body {
            padding: 9px;
        }

        #joinCommunityModal .crm-section {
            padding: 12px;
        }

        #joinCommunityModal .crm-label {
            font-size: 11px;
        }

        #joinCommunityModal .crm-input {
            padding-left: 35px;
        }

        #joinCommunityModal .crm-input::placeholder {
            font-size: 11px;
        }

    }
</style>
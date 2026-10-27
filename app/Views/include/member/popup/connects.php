<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="connect_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="connect_edit_data" style="max-width:440px; width:92%; margin:1.5rem auto;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_new_connects() {
        $('#add_new_connects').modal('show');
    }
</script>
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_connects" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">

        <div class="modal-content"
            style="border-radius:12px;
                    border:none;
                    box-shadow:0 4px 12px rgba(0,0,0,.1);
                    font-size:13px;">

            <form id="AddNewReferralData" enctype="multipart/form-data">

                <!-- HEADER -->
                <div class="modal-header"
                    style="background:#1b4e8f;color:#fff;">

                    <h6 class="mb-0" style="color:#ffffff;">
                        New Connects Opportunity
                    </h6>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <!-- BODY -->
                <div class="modal-body connect-modal">

                    <!-- MEMBER SOURCE -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Select Member From
                        </label>

                        <div class="member-source">

                            <label class="radio-card active">

                                <input type="radio"
                                    name="member_form"
                                    value="chapter"
                                    checked>

                                <span>My Chapter</span>

                            </label>


                            <label class="radio-card">

                                <input type="radio"
                                    name="member_form"
                                    value="all">

                                <span>Across All Chapters</span>

                            </label>

                        </div>

                    </div>


                    <!-- MEMBER SELECT -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Send Connect To
                        </label>

                        <select name="to_user"
                            id="member_list"
                            class="form-select"
                            required>

                            <option value="">
                                Loading members...
                            </option>

                        </select>

                        <small class="text-muted">
                            Search member name and select a member.
                        </small>

                    </div>


                    <!-- CONNECT NAME -->
                    <!-- CONNECT NAME -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Connects Name
                        </label>

                        <input type="text"
                            name="business"
                            id="connects_name"
                            class="form-control"
                            required>
                    </div>


                    <!-- CONNECT CONTACT -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Connects Contact
                        </label>

                        <input type="text"
                            name="contact_number"
                            id="connects_contact"
                            class="form-control"
                            required>
                    </div>


                    <!-- DETAILS -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Details
                        </label>

                        <textarea name="description"
                            rows="3"
                            class="form-control"></textarea>

                    </div>


                    <!-- TEMPERATURE + STATUS -->
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Temperature
                            </label>

                            <select class="form-select"
                                name="temperature">

                                <option value="Hot">
                                    🔥 Hot
                                </option>

                                <option value="Warm">
                                    🌤 Warm
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select class="form-select" name="status">
                                <option value="Open For Discussion" selected>Open For Discussion</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Pending">Pending</option>
                                <option value="Need Sometime">Need Sometime</option>
                                <option value="MITB">MITB</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>

                        </div>

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="modal-footer border-0">

                    <button type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button type="submit"
                        class="btn btn-sm text-white"
                        style="background:#1b4e8f;">

                        Send Connects

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function ($) {

    /*
    |--------------------------------------------------------------------------
    | INITIALIZE SELECT2
    |--------------------------------------------------------------------------
    */
    function initMemberSelect() {

        if ($('#member_list').hasClass('select2-hidden-accessible')) {
            $('#member_list').select2('destroy');
        }

        $('#member_list').select2({
            placeholder: '-- Search & Select Member --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#add_new_connects'),
            minimumResultsForSearch: 0,

            language: {
                noResults: function () {
                    return 'No member found';
                },

                searching: function () {
                    return 'Searching...';
                }
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | HTML ESCAPE
    |--------------------------------------------------------------------------
    */
    function escapeHtml(text) {

        if (text === null || text === undefined) {
            return '';
        }

        return $('<div>')
            .text(String(text))
            .html();
    }


    /*
    |--------------------------------------------------------------------------
    | FILL CONNECT DETAILS
    |--------------------------------------------------------------------------
    */
    function fillConnectDetails() {

        let $selected = $('#member_list option:selected');

        if (!$selected.length) {
            $('#connects_name').val('');
            $('#connects_contact').val('');
            return;
        }

        let isMySelf = $selected.attr('data-myself');

        /*
        |--------------------------------------------------------------------------
        | MY SELF
        |--------------------------------------------------------------------------
        */
        if (isMySelf === '1') {

            let name = $selected.attr('data-name') || '';
            let phone = $selected.attr('data-phone') || '';

            console.log('--------------------------------');
            console.log('MY SELF SELECTED');
            console.log('Name:', name);
            console.log('Phone:', phone);
            console.log('--------------------------------');

            $('#connects_name').val(name);
            $('#connects_contact').val(phone);

        } else {

            /*
            |--------------------------------------------------------------------------
            | OTHER MEMBER
            |--------------------------------------------------------------------------
            */
            $('#connects_name').val('');
            $('#connects_contact').val('');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD MEMBERS
    |--------------------------------------------------------------------------
    */
    function loadMembers(type = 'chapter') {

        let $memberList = $('#member_list');

        /*
        |--------------------------------------------------------------------------
        | Clear Name & Contact
        |--------------------------------------------------------------------------
        */
        $('#connects_name').val('');
        $('#connects_contact').val('');


        /*
        |--------------------------------------------------------------------------
        | Destroy Select2 before changing options
        |--------------------------------------------------------------------------
        */
        if ($memberList.hasClass('select2-hidden-accessible')) {
            $memberList.select2('destroy');
        }


        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        $memberList.html(`
            <option value="">
                Loading members...
            </option>
        `);


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */
        $.ajax({

            url: "<?= base_url('member/connect/getMembers') ?>",

            type: "POST",

            dataType: "json",

            data: {
                type: type
            },


            beforeSend: function () {

                $memberList.html(`
                    <option value="">
                        Loading members...
                    </option>
                `);
            },


            success: function (res) {

                console.log('Members Response:', res);

                let html = '';

                /*
                |--------------------------------------------------------------------------
                | Default Option
                |--------------------------------------------------------------------------
                */
                html += `
                    <option value="">
                        -- Search & Select Member --
                    </option>
                `;


                /*
                |--------------------------------------------------------------------------
                | Separate My Self
                |--------------------------------------------------------------------------
                */
                let mySelf = null;
                let otherMembers = [];


                if (Array.isArray(res)) {

                    res.forEach(function (m) {

                        if (
                            m.is_self == 1 ||
                            m.is_self === true ||
                            m.is_self === "1"
                        ) {

                            mySelf = m;

                        } else {

                            otherMembers.push(m);
                        }

                    });
                }


                /*
                |--------------------------------------------------------------------------
                | MY SELF FIRST
                |--------------------------------------------------------------------------
                */
                if (mySelf) {

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | Your database field is "phone"
                    |--------------------------------------------------------------------------
                    */
                    let selfName = mySelf.name || '';
                    let selfPhone = mySelf.phone || '';

                    console.log('My Self Object:', mySelf);
                    console.log('My Self Name:', selfName);
                    console.log('My Self Phone:', selfPhone);


                    html += `
                        <option
                            value="${escapeHtml(mySelf.user_code)}"
                            data-myself="1"
                            data-name="${escapeHtml(selfName)}"
                            data-phone="${escapeHtml(selfPhone)}">

                            ⭐ Self - ${escapeHtml(selfName)}

                        </option>
                    `;
                }


                /*
                |--------------------------------------------------------------------------
                | OTHER MEMBERS
                |--------------------------------------------------------------------------
                */
                otherMembers.forEach(function (m) {

                    html += `
                        <option
                            value="${escapeHtml(m.user_code)}">

                            ${escapeHtml(m.name || '')}

                        </option>
                    `;
                });


                /*
                |--------------------------------------------------------------------------
                | Update Select
                |--------------------------------------------------------------------------
                */
                $memberList.html(html);


                /*
                |--------------------------------------------------------------------------
                | Initialize Select2
                |--------------------------------------------------------------------------
                */
                initMemberSelect();


                /*
                |--------------------------------------------------------------------------
                | AUTOMATICALLY SELECT MY SELF
                |--------------------------------------------------------------------------
                */
                if (mySelf) {

                    $memberList.val(mySelf.user_code);

                    /*
                    |--------------------------------------------------------------------------
                    | Tell Select2 about the selected value
                    |--------------------------------------------------------------------------
                    */
                    $memberList.trigger('change.select2');

                    /*
                    |--------------------------------------------------------------------------
                    | Fill Name + Phone
                    |--------------------------------------------------------------------------
                    */
                    fillConnectDetails();
                }

            },


            error: function (xhr, status, error) {

                console.log('Member AJAX Error:', xhr.responseText);

                $memberList.html(`
                    <option value="">
                        Unable to load members
                    </option>
                `);

                initMemberSelect();

                $('#connects_name').val('');
                $('#connects_contact').val('');
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | MEMBER SELECT CHANGE
    |--------------------------------------------------------------------------
    */
    $(document).on('change', '#member_list', function () {

        fillConnectDetails();

    });


    /*
    |--------------------------------------------------------------------------
    | RADIO SWITCH
    |--------------------------------------------------------------------------
    */
    $(document).on(
        'change',
        'input[name="member_form"]',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Active Radio Card
            |--------------------------------------------------------------------------
            */
            $('.radio-card').removeClass('active');

            $(this)
                .closest('.radio-card')
                .addClass('active');


            /*
            |--------------------------------------------------------------------------
            | Get Type
            |--------------------------------------------------------------------------
            */
            let type = $(this).val();


            /*
            |--------------------------------------------------------------------------
            | Load Members
            |--------------------------------------------------------------------------
            */
            loadMembers(type);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | MODAL OPEN
    |--------------------------------------------------------------------------
    */
    $('#add_new_connects').on(
        'shown.bs.modal',
        function () {

            let selectedType =
                $('input[name="member_form"]:checked').val() ||
                'chapter';

            loadMembers(selectedType);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | MODAL CLOSE
    |--------------------------------------------------------------------------
    */
    $('#add_new_connects').on(
        'hidden.bs.modal',
        function () {

            /*
            | Clear fields when modal closes
            */
            $('#connects_name').val('');
            $('#connects_contact').val('');

            /*
            | Reset member select
            */
            if ($('#member_list').hasClass('select2-hidden-accessible')) {
                $('#member_list').val(null).trigger('change');
            } else {
                $('#member_list').val('');
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT READY
    |--------------------------------------------------------------------------
    */
    $(document).ready(function () {

        /*
        | Nothing to load here.
        | Members are loaded when modal opens.
        */

    });


})(jQuery);
</script>
<script>
    $(document).on("submit", "#AddNewReferralData", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('member/connect/AddNewReferralData'); ?>",
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
                    icon: res.status === 'success' ? 'success' : 'error',
                    title: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $("#add_new_connects").modal("hide");
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
    function edit_refferral_status(referral_code) {
        $('#connect_edit').modal('show');

        $.ajax({
            url: "<?= site_url('member/connect/edit_refferral_status') ?>/" + referral_code,
            success: function(response) {
                $("#connect_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#edit_refferral_status_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/connect/edit_refferral_status_action') ?>",
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
                        $("#connect_edit").modal("hide");
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
    function edit_refferral(referral_code) {
        $('#connect_edit').modal('show');

        $.ajax({
            url: "<?= site_url('member/connect/edit_refferral') ?>/" + referral_code,
            success: function(response) {
                $("#connect_edit_data").html(response);
            }
        });
    }


    $(document).on("submit", "#edit_refferral_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/connect/edit_refferral_action') ?>",
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    #add_new_connects .select2-container {
        width: 100% !important;
    }


    #add_new_connects .select2-container .select2-selection--single {

        height: 38px !important;

        border: 1px solid #dee2e6 !important;

        border-radius: 6px !important;

        display: flex !important;

        align-items: center !important;

        padding-left: 8px !important;

        font-size: 13px !important;

        background: #fff !important;

    }


    /*
     * SELECT2 TEXT
     */
    #add_new_connects .select2-selection__rendered {

        line-height: 36px !important;

        color: #333 !important;

        padding-left: 4px !important;

        font-size: 13px !important;

    }


    /*
     * ARROW
     */
    #add_new_connects .select2-selection__arrow {

        height: 36px !important;

        right: 7px !important;

    }


    /*
     * SEARCH BOX
     */
    #add_new_connects .select2-search--dropdown {

        padding: 8px !important;

        background: #fff;

    }


    #add_new_connects .select2-search__field {

        height: 36px !important;

        border: 1px solid #ced4da !important;

        border-radius: 6px !important;

        padding: 6px 10px !important;

        font-size: 13px !important;

        outline: none !important;

    }


    /*
     * DROPDOWN
     */
    #add_new_connects .select2-dropdown {

        border: 1px solid #dee2e6 !important;

        border-radius: 7px !important;

        box-shadow: 0 5px 18px rgba(0, 0, 0, .12) !important;

        overflow: hidden;

    }


    /*
     * OPTIONS
     */
    #add_new_connects .select2-results__option {

        padding: 9px 12px !important;

        font-size: 13px !important;

    }


    /*
     * HOVER
     */
    #add_new_connects .select2-results__option--highlighted {

        background: #1b4e8f !important;

        color: #fff !important;

    }


    /*
     * MY SELF OPTION
     */
    #add_new_connects .my-self-option {

        font-weight: 600;

        color: #1b4e8f;

    }


    /*
     * RADIO CARDS
     */
    .member-source {

        display: flex;

        gap: 10px;

    }


    .radio-card {

        flex: 1;

        border: 1px solid #dee2e6;

        border-radius: 8px;

        padding: 10px 12px;

        cursor: pointer;

        background: #fff;

        transition: all .2s ease;

        text-align: center;

    }


    .radio-card input {

        display: none;

    }


    .radio-card span {

        font-weight: 600;

        font-size: 13px;

    }


    .radio-card.active {

        background: #1b4e8f;

        border-color: #1b4e8f;

        color: #fff;

    }


    .radio-card:hover {

        border-color: #1b4e8f;

    }
</style>
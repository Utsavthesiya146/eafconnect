<!-- edit start-->
<!-- edit start-->
<div class="modal fade" id="connect_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="connect_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_new_connects() {
        $('#add_new_connects').modal('show');
    }
</script>

<!-- ================================================
     SINGLE CREATE REFERRAL MODAL
================================================ -->



<div class="referral-modal" id="add_new_connects">

    <div class="referral-main-dialog">

        <!-- HEADER -->
        <div class="referral-modal-header">
            <div>
                <h5>Give New Referral</h5>
                <small>Select a member and share a business referral</small>
            </div>

            <button type="button"
                class="referral-close"
                onclick="closeReferralModal()">
                &times;
            </button>
        </div>

        <div class="referral-modal-body">

            <div id="referralMemberPicker">
                <!-- MEMBER SEARCH -->
                <div class="referral-section-title">
                    <i class="fas fa-users"></i>
                    Find Referral Member
                </div>

                <div class="row g-2 mb-3">

                <div class="col-md-6">
                    <input type="text"
                        id="ref_user_name"
                        class="form-control"
                        placeholder="Search member, phone or email">
                </div>

                <div class="col-md-6">
                    <input type="text"
                        id="ref_business_name"
                        class="form-control"
                        placeholder="Search business or company">
                </div>

                <div class="col-md-6">
                    <input type="text"
                        id="ref_category"
                        class="form-control"
                        placeholder="Search industry">
                </div>

                <div class="col-md-6">
                    <select id="ref_chapter" class="form-select">
                        <option value="">All Chapters</option>

                        <?php if (!empty($chapters)): ?>
                            <?php foreach ($chapters as $chapter): ?>
                                <option value="<?= esc($chapter['chapter_code']) ?>">
                                    <?= esc($chapter['chapter_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </select>
                </div>

                </div>

                <!-- MEMBER COUNT -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="referral-subtitle">Available Members</strong>

                    <span id="referralMemberCount" class="referral-count">
                        0 Members
                    </span>
                </div>

                <!-- MEMBER RESULTS -->
                <div id="referralMemberResults">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <p class="mt-2 text-muted">Loading members...</p>
                    </div>
                </div>

                <!-- PAGINATION -->
                <div id="referralPagination"></div>
            </div>

            <hr class="my-4">

            <!-- REFERRAL FORM -->
            <div class="referral-section-title">
                <i class="fas fa-handshake"></i>
                Referral Information
            </div>

            <form id="submitReferralForm">

                <?= csrf_field() ?>

                <input type="hidden"
                    name="to_user"
                    id="ref_to_user">

                <div class="referral-selected-member mb-3" id="referralSelectedMember" hidden>
                    <span class="referral-selected-icon"><i class="fas fa-user-check"></i></span>
                    <div>
                        <span class="referral-selected-label">Sending referral to</span>
                        <strong id="ref_selected_member"></strong>
                    </div>
                    <button type="button" class="referral-change-member" id="referralChangeMember">
                        <i class="fas fa-exchange-alt"></i>
                        Change member
                    </button>
                </div>

                <section id="referralContactOptions" class="referral-contact-options" hidden>
                    <div class="referral-contact-heading">
                        <div>
                            <h6>Who is the referral for?</h6>
                            <p>Use your own details or enter a new contact.</p>
                        </div>
                    </div>

                    <div class="referral-contact-type">
                        <label class="referral-type-card" for="referral_type_self">
                            <input type="radio" name="referral_type" id="referral_type_self" value="self">
                            <span class="referral-type-icon"><i class="fas fa-user"></i></span>
                            <span class="referral-type-copy">
                                <strong>Self</strong>
                                <small>Use my saved contact details</small>
                            </span>
                            <i class="fas fa-check-circle referral-type-check"></i>
                        </label>

                        <label class="referral-type-card" for="referral_type_other">
                            <input type="radio" name="referral_type" id="referral_type_other" value="other" checked>
                            <span class="referral-type-icon"><i class="fas fa-user-plus"></i></span>
                            <span class="referral-type-copy">
                                <strong>Other</strong>
                                <small>Enter a new contact's details</small>
                            </span>
                            <i class="fas fa-check-circle referral-type-check"></i>
                        </label>
                    </div>

                    <div class="referral-contact-details" id="referralContactDetails">
                        <div class="referral-contact-details-heading">
                            <strong>Contact details</strong>
                            <span id="referralContactHint">Enter the person you are referring.</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Referral Person Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="referral_name"
                                    id="referral_name"
                                    class="form-control"
                                    required
                                    disabled
                                    maxlength="150"
                                    placeholder="Enter contact person's name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Business / Company <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="referral_company"
                                    id="referral_company"
                                    class="form-control"
                                    required
                                    disabled
                                    maxlength="255"
                                    placeholder="Enter business or company name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Contact Number <span class="text-danger">*</span>
                                </label>

                                <input type="tel"
                                    name="referral_mobile"
                                    id="referral_mobile"
                                    class="form-control"
                                    required
                                    disabled
                                    maxlength="10"
                                    pattern="[6-9][0-9]{9}"
                                    placeholder="10-digit mobile number">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>

                                <input type="email"
                                    name="referral_email"
                                    id="referral_email"
                                    class="form-control"
                                    disabled
                                    maxlength="150"
                                    placeholder="Enter email address (optional)">
                            </div>

                            <div class="col-md-6">
                                <fieldset class="referral-priority-fieldset">
                                    <legend class="form-label">Priority</legend>
                                    <div class="referral-priority-options">
                                        <label class="referral-priority-option" for="referral_priority_1">
                                            <input type="radio" name="priority" id="referral_priority_1" value="1" disabled>
                                            <span class="referral-priority-number">1</span>
                                            <span class="referral-priority-label">Very low</span>
                                        </label>
                                        <label class="referral-priority-option" for="referral_priority_2">
                                            <input type="radio" name="priority" id="referral_priority_2" value="2" disabled>
                                            <span class="referral-priority-number">2</span>
                                            <span class="referral-priority-label">Low</span>
                                        </label>
                                        <label class="referral-priority-option selected" for="referral_priority_3">
                                            <input type="radio" name="priority" id="referral_priority_3" value="3" checked disabled>
                                            <span class="referral-priority-number">3</span>
                                            <span class="referral-priority-label">Medium</span>
                                        </label>
                                        <label class="referral-priority-option" for="referral_priority_4">
                                            <input type="radio" name="priority" id="referral_priority_4" value="4" disabled>
                                            <span class="referral-priority-number">4</span>
                                            <span class="referral-priority-label">High</span>
                                        </label>
                                        <label class="referral-priority-option" for="referral_priority_5">
                                            <input type="radio" name="priority" id="referral_priority_5" value="5" disabled>
                                            <span class="referral-priority-number">5</span>
                                            <span class="referral-priority-label">Very high</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Remarks <span class="text-danger">*</span>
                                </label>

                                <textarea name="remarks"
                                    id="ref_remarks"
                                    class="form-control"
                                    rows="3"
                                    required
                                    disabled
                                    placeholder="Enter referral details, requirements and additional information"></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ACTION -->
                <div class="referral-form-footer">

                    <button type="button"
                        class="btn btn-light"
                        onclick="closeReferralModal()">
                        Cancel
                    </button>

                    <button type="submit"
                        id="submitReferralBtn"
                        class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>
                        Submit Referral
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


<!-- =====================================================
     REFERRAL CSS
===================================================== -->

<style>
    .referral-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(9, 25, 56, .65);
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .referral-modal.active {
        display: flex;
    }

    body.referral-modal-open {
        overflow: hidden;
    }

    .referral-main-dialog {
        width: 100%;
        max-width: 850px;
        max-height: 92vh;
        overflow-y: auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        animation: referralModalIn .25s ease;
    }

    @keyframes referralModalIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .referral-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        background: #091938;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .referral-modal-header h5 {
        font-size: 17px;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .referral-modal-header small {
        color: #cbd5e1;
        font-size: 11px;
    }

    .referral-close {
        border: 0;
        background: transparent;
        color: #fff;
        font-size: 29px;
        cursor: pointer;
        line-height: 1;
    }

    .referral-modal-body {
        padding: 22px;
    }

    .referral-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #091938;
        margin-bottom: 14px;
    }

    .referral-section-title i {
        color: #1a56b8;
        margin-right: 7px;
    }

    .referral-subtitle {
        font-size: 13px;
        color: #091938;
    }

    .referral-count {
        background: #edf3ff;
        color: #1a56b8;
        font-size: 11px;
        font-weight: 700;
        border-radius: 20px;
        padding: 5px 11px;
    }

    .referral-modal .form-control,
    .referral-modal .form-select {
        min-height: 42px;
        border: 1px solid #dce3ed;
        border-radius: 8px;
        font-size: 13px;
    }

    .referral-modal .form-control:focus,
    .referral-modal .form-select:focus {
        border-color: #1a56b8;
        box-shadow: 0 0 0 3px rgba(26, 86, 184, .10);
    }

    .referral-modal .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .referral-member-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 12px;
        margin-bottom: 9px;
        border: 1px solid #e8edf4;
        border-radius: 11px;
        background: #fff;
        transition: .2s ease;
    }

    .referral-member-card:hover {
        border-color: #1a56b8;
        box-shadow: 0 4px 15px rgba(9, 25, 56, .07);
    }

    .referral-member-avatar img {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        object-position: top center;
        flex-shrink: 0;
    }

    .referral-member-info {
        flex: 1;
        min-width: 0;
    }

    .referral-member-info h6 {
        margin: 0 0 4px;
        font-size: 13px;
        font-weight: 700;
        color: #091938;
    }

    .referral-member-info p {
        margin: 2px 0;
        font-size: 11px;
        color: #64748b;
        overflow-wrap: anywhere;
    }

    .referral-member-industry i {
        flex: 0 0 auto;
        font-size: 10px;
    }

    .referral-member-info small {
        font-size: 11px;
        color: #1a56b8;
    }

    .referral-select-member {
        white-space: nowrap;
        font-size: 11px;
        padding: 7px 12px;
        border-radius: 7px;
    }

    .referral-empty-state {
        text-align: center;
        padding: 35px 15px;
        color: #64748b;
    }

    .referral-empty-state i {
        font-size: 32px;
        color: #94a3b8;
    }

    .referral-empty-state h6 {
        color: #091938;
        font-weight: 700;
        margin-top: 10px;
    }

    .referral-pagination {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
        margin: 15px 0;
    }

    .referral-page-btn {
        min-width: 34px;
        height: 34px;
        border: 1px solid #dbe3ed;
        background: #fff;
        color: #091938;
        border-radius: 7px;
        font-size: 12px;
    }

    .referral-page-btn.active {
        background: #1a56b8;
        color: #fff;
        border-color: #1a56b8;
    }

    .referral-form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #edf0f5;
    }

    .referral-form-footer .btn {
        min-height: 40px;
        padding: 8px 18px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
    }

    .referral-selected-member {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 15px;
        background: #f0f7ff;
        border: 1px solid #cfe2ff;
        border-radius: 11px;
    }

    .referral-selected-icon,
    .referral-type-icon {
        display: grid;
        place-items: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        color: #1557a8;
        background: #e4efff;
        border-radius: 10px;
    }

    .referral-selected-member > div {
        display: flex;
        flex-direction: column;
        min-width: 0;
        gap: 2px;
    }

    .referral-selected-member[hidden],
    #referralMemberPicker[hidden] {
        display: none !important;
    }

    .referral-change-member {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
        padding: 7px 10px;
        color: #1557a8;
        background: #fff;
        border: 1px solid #cfe0f8;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .referral-change-member:hover {
        color: #fff;
        background: #1557a8;
        border-color: #1557a8;
    }

    .referral-selected-label {
        color: #65748b;
        font-size: 11px;
    }

    .referral-selected-member strong {
        color: #10233f;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .referral-contact-options {
        margin-bottom: 18px;
    }

    .referral-contact-heading h6 {
        margin: 0 0 4px;
        color: #10233f;
        font-size: 15px;
        font-weight: 700;
    }

    .referral-contact-heading p {
        margin: 0 0 12px;
        color: #69778d;
        font-size: 12px;
    }

    .referral-contact-type {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .referral-type-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 70px;
        padding: 12px;
        border: 1px solid #dfe6ef;
        border-radius: 11px;
        background: #fff;
        cursor: pointer;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .referral-type-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .referral-type-card.selected {
        border-color: #1a56b8;
        background: #f5f9ff;
        box-shadow: 0 0 0 2px rgba(26, 86, 184, .08);
    }

    .referral-type-card:focus-within {
        outline: 3px solid rgba(26, 86, 184, .16);
        outline-offset: 2px;
    }

    .referral-type-copy {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .referral-type-copy strong {
        color: #10233f;
        font-size: 13px;
    }

    .referral-type-copy small {
        color: #69778d;
        font-size: 11px;
    }

    .referral-type-check {
        color: #1a56b8;
        opacity: 0;
    }

    .referral-type-card.selected .referral-type-check {
        opacity: 1;
    }

    .referral-contact-details {
        padding: 16px;
        background: #f8faff;
        border: 1px solid #e6ecf4;
        border-radius: 11px;
    }

    .referral-contact-details-heading {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 5px 12px;
        margin-bottom: 14px;
    }

    .referral-contact-details-heading strong {
        color: #10233f;
        font-size: 13px;
    }

    .referral-contact-details-heading span {
        color: #69778d;
        font-size: 11px;
    }

    #referralContactDetails[hidden],
    #referralContactOptions[hidden],
    #referralSelectedMember[hidden] {
        display: none !important;
    }

    @media(max-width:575px) {

        .referral-modal {
            padding: 7px;
        }

        .referral-main-dialog {
            max-height: 96vh;
            border-radius: 12px;
        }

        .referral-modal-header {
            padding: 15px;
        }

        .referral-modal-body {
            padding: 14px;
        }

        .referral-member-card {
            gap: 9px;
            padding: 9px;
        }

        .referral-member-avatar img {
            width: 42px;
            height: 42px;
        }

        .referral-member-info h6 {
            font-size: 12px;
        }

        .referral-select-member {
            padding: 6px 8px;
            font-size: 10px;
        }

        .referral-form-footer {
            flex-direction: column-reverse;
        }

        .referral-form-footer .btn {
            width: 100%;
        }

        .referral-contact-type {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .referral-contact-details {
            padding: 12px;
        }
    }
</style>

<script>
    $(document).ready(function() {

        /* =========================================
           CONFIGURATION
        ========================================= */

        const searchUrl = "<?= site_url('member/connect/getMembers2') ?>";
        const saveUrl = "<?= site_url('member/connect/AddNewReferralData') ?>";

        let csrfName = "<?= csrf_token() ?>";
        let csrfHash = "<?= csrf_hash() ?>";

        let currentPage = 1;
        let totalPages = 1;
        let request = null;
        let searchTimer = null;
        let selfContact = null;

        const perPage = 12;

        /* =========================================
           CSRF UPDATE
        ========================================= */

        function updateCsrf(hash) {
            if (!hash) return;

            csrfHash = hash;

            $('input[name="' + csrfName + '"]').val(csrfHash);
        }

        /* =========================================
           OPEN / CLOSE MODAL
        ========================================= */

        window.add_new_connects = function() {
            resetReferralForm();
            $('#add_new_connects').addClass('active');
            $('body').addClass('referral-modal-open');

            loadReferralMembers(1);
        };

        window.closeReferralModal = function() {
            $('#add_new_connects').removeClass('active');
            $('body').removeClass('referral-modal-open');
        };

        // Close by clicking outside modal
        $('#add_new_connects').on('click', function(e) {
            if (e.target === this) {
                closeReferralModal();
            }
        });

        // Close by Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReferralModal();
            }
        });

        /* =========================================
           PAGINATION HTML
        ========================================= */

        function renderPagination(page, pages) {

            let html = '';

            if (pages <= 1) {
                $('#referralPagination').html('');
                return;
            }

            html += `
            <button
                type="button"
                class="referral-page-btn"
                data-page="${page - 1}"
                ${page <= 1 ? 'disabled' : ''}
            >
                <i class="bi bi-chevron-left"></i>
            </button>
        `;

            let start = Math.max(1, page - 2);
            let end = Math.min(pages, page + 2);

            if (start > 1) {
                html += `
                <button type="button"
                    class="referral-page-btn"
                    data-page="1">1</button>
            `;

                if (start > 2) {
                    html += `<span class="px-1">...</span>`;
                }
            }

            for (let i = start; i <= end; i++) {

                html += `
                <button
                    type="button"
                    class="referral-page-btn ${i === page ? 'active' : ''}"
                    data-page="${i}"
                >
                    ${i}
                </button>
            `;
            }

            if (end < pages) {

                if (end < pages - 1) {
                    html += `<span class="px-1">...</span>`;
                }

                html += `
                <button type="button"
                    class="referral-page-btn"
                    data-page="${pages}">${pages}</button>
            `;
            }

            html += `
            <button
                type="button"
                class="referral-page-btn"
                data-page="${page + 1}"
                ${page >= pages ? 'disabled' : ''}
            >
                <i class="bi bi-chevron-right"></i>
            </button>
        `;

            $('#referralPagination').html(html);
        }

        /* =========================================
           LOAD MEMBERS
        ========================================= */

        function loadReferralMembers(page = 1) {

            currentPage = page;

            if (request && request.readyState !== 4) {
                request.abort();
            }

            $('#referralMemberResults').html(`
            <div class="referral-loading">
                <span class="spinner-border spinner-border-sm"></span>
                Loading members...
            </div>
        `);

            $('#referralLoadingText').text('Loading...');

            $('#referralPagination').empty();

            let postData = {
                user_name: $('#ref_user_name').val(),
                business_name: $('#ref_business_name').val(),
                category: $('#ref_category').val(),
                chapter: $('#ref_chapter').val(),
                type: 'all',
                page: page,
                per_page: perPage
            };

            postData[csrfName] = csrfHash;

            request = $.ajax({
                url: searchUrl,
                type: 'POST',
                dataType: 'json',
                data: postData,

                success: function(response) {

                    updateCsrf(response.csrfHash);

                    if (
                        response.status === true ||
                        response.status === 'success' ||
                        response.status === 1 ||
                        response.status === '1'
                    ) {

                        selfContact = response.self_contact || null;

                        $('#referralMemberResults').html(
                            response.html || `
                            <div class="referral-empty-state">
                                <i class="bi bi-people"></i>
                                <h6>No Members Found</h6>
                                <p>Try another search.</p>
                            </div>
                        `
                        );

                        let total = parseInt(response.total ?? response.count ?? 0, 10);
                        let responsePage = parseInt(response.page ?? page, 10);
                        let pages = parseInt(
                            response.total_pages ?? Math.ceil(total / perPage),
                            10
                        );

                        totalPages = Math.max(1, pages);

                        $('#referralMemberCount').text(
                            total + (total === 1 ? ' member' : ' members')
                        );

                        $('#referralLoadingText').text(
                            total > 0 ?
                            'Page ' + responsePage + ' of ' + totalPages :
                            ''
                        );

                        renderPagination(responsePage, totalPages);

                    } else {

                        $('#referralMemberResults').html(`
                        <div class="referral-empty-state">
                            <i class="bi bi-exclamation-circle"></i>
                            <h6>Unable to Load Members</h6>
                            <p>${escapeHtml(response.message || 'Please try again.')}</p>
                        </div>
                    `);

                        $('#referralMemberCount').text('0 members');
                    }
                },

                error: function(xhr, status) {

                    if (status === 'abort') {
                        return;
                    }

                    console.error('Referral member AJAX error:', xhr.status);
                    console.error('Response:', xhr.responseText);

                    let message = 'Unable to load members. Please try again.';

                    if (xhr.status === 401) {
                        message = 'Your session has expired. Please login again.';
                    } else if (xhr.status === 403) {
                        message = 'Access denied or CSRF validation failed.';
                    } else if (xhr.status === 404) {
                        message = 'Member search route not found. Check your route.';
                    } else if (xhr.status === 500) {
                        message = 'Server error. Check the CodeIgniter log for details.';
                    }

                    $('#referralMemberResults').html(`
                    <div class="referral-empty-state">
                        <i class="bi bi-exclamation-triangle"></i>
                        <h6>Unable to Load Members</h6>
                        <p>${escapeHtml(message)}</p>
                    </div>
                `);

                    $('#referralMemberCount').text('0 members');
                    $('#referralLoadingText').text('');
                },

                complete: function() {
                    request = null;
                }
            });
        }

        /* =========================================
           BASIC HTML ESCAPING
        ========================================= */

        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }

        /* =========================================
           SEARCH FILTERS
        ========================================= */

        $('#ref_user_name, #ref_business_name').on('input', function() {

            clearTimeout(searchTimer);

            searchTimer = setTimeout(function() {
                loadReferralMembers(1);
            }, 350);
        });

        $('#ref_category, #ref_chapter').on('change', function() {
            loadReferralMembers(1);
        });

        function setReferralContactType(type) {
            const isSelf = type === 'self';
            const fields = {
                name: $('#referral_name'),
                company: $('#referral_company'),
                mobile: $('#referral_mobile'),
                email: $('#referral_email')
            };

            if (isSelf && !selfContact) {
                Swal.fire({
                    icon: 'error',
                    title: 'Contact details unavailable',
                    text: 'Your saved profile details could not be loaded. Please try again or choose Other.'
                });
                $('#referral_type_other').prop('checked', true);
                setReferralContactType('other');
                return;
            }

            let selfMobile = isSelf
                ? String(selfContact.phone || '').replace(/\D/g, '')
                : '';

            if (selfMobile.length === 12 && selfMobile.substring(0, 2) === '91') {
                selfMobile = selfMobile.substring(2);
            } else if (selfMobile.length === 11 && selfMobile.charAt(0) === '0') {
                selfMobile = selfMobile.substring(1);
            }

            const values = isSelf ? {
                name: selfContact.name || '',
                company: selfContact.business_name || '',
                mobile: selfMobile,
                email: selfContact.email || ''
            } : {
                name: '',
                company: '',
                mobile: '',
                email: ''
            };

            Object.keys(fields).forEach(function(key) {
                fields[key]
                    .val(values[key])
                    .prop('readonly', isSelf && values[key] !== '');
            });

            $('#referralContactHint').text(
                isSelf
                    ? 'Your saved profile details are filled in automatically.'
                    : 'Enter the person you are referring.'
            );

            $('.referral-type-card').removeClass('selected');
            $('input[name="referral_type"]:checked')
                .closest('.referral-type-card')
                .addClass('selected');
        }

        $('input[name="referral_type"]').on('change', function() {
            setReferralContactType(this.value);
        });

        $('input[name="priority"]').on('change', function() {
            $('.referral-priority-option').removeClass('selected');
            $(this).closest('.referral-priority-option').addClass('selected');
        });

        function resetReferralForm() {
            $('#submitReferralForm')[0].reset();
            $('#ref_to_user').val('');
            $('#ref_selected_member').text('');
            $('#referralSelectedMember').prop('hidden', true);
            $('#referralMemberPicker').prop('hidden', false);
            $('#referralContactOptions').prop('hidden', true);
            $('#referralContactDetails').find(':input').prop('disabled', true);
            $('.referral-type-card').removeClass('selected');
            $('.referral-priority-option').removeClass('selected');
            $('#referral_priority_3').closest('.referral-priority-option').addClass('selected');
            setReferralContactType('other');
        }

        /* =========================================
           PAGINATION CLICK
        ========================================= */

        $(document).on('click', '.referral-page-btn', function() {

            let page = parseInt($(this).data('page'), 10);

            if (
                !page ||
                page < 1 ||
                page > totalPages ||
                page === currentPage
            ) {
                return;
            }

            loadReferralMembers(page);
        });

        /* =========================================
           SELECT MEMBER
        ========================================= */

        $(document).on('click', '.referral-select-member', function() {

            let userCode = $(this).data('user-code');
            let memberName = $(this).data('name');

            if (!userCode) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Member',
                    text: 'Unable to select this member.'
                });

                return;
            }

            $('#ref_to_user').val(userCode);
            $('#ref_selected_member').text(memberName);
            $('#referralSelectedMember').prop('hidden', false);
            $('#referralMemberPicker').prop('hidden', true);
            $('#referralContactOptions').prop('hidden', false);
            $('#referralContactDetails').find(':input').prop('disabled', false);

            setReferralContactType(
                $('input[name="referral_type"]:checked').val() || 'other'
            );

            $('.referral-select-member')
                .prop('disabled', false)
                .html('<span>Select this member</span><i class="bi bi-arrow-right"></i>');

            $(this)
                .prop('disabled', true)
                .html('<span>Selected</span><i class="bi bi-check2-circle"></i>');

        });

        $('#referralChangeMember').on('click', function() {
            $('#referralMemberPicker').prop('hidden', false);
            $('#ref_user_name').trigger('focus');
        });

        /* =========================================
           SUBMIT REFERRAL
        ========================================= */

        $('#submitReferralForm').on('submit', function(e) {

            e.preventDefault();

            let selectedMember = $('#ref_to_user').val();
            let mobile = $.trim($('#referral_mobile').val());

            if (!selectedMember) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select a Member',
                    text: 'Please select a member before submitting the referral.'
                });

                return;
            }

            if (!/^[6-9][0-9]{9}$/.test(mobile)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Mobile Number',
                    text: 'Please enter a valid 10-digit Indian mobile number.'
                });

                $('#referral_mobile').focus();
                return;
            }

            let $button = $('#submitReferralBtn');

            $button.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm"></span>
            Submitting...
        `);

            let formData = $(this).serializeArray();

            // Ensure latest CSRF token is submitted
            formData = formData.filter(function(item) {
                return item.name !== csrfName;
            });

            formData.push({
                name: csrfName,
                value: csrfHash
            });

            $.ajax({
                url: saveUrl,
                type: 'POST',
                dataType: 'json',
                data: $.param(formData),

                success: function(response) {

                    updateCsrf(response.csrfHash);

                    if (
                        response.status === true ||
                        response.status === 'success' ||
                        response.status === 1 ||
                        response.status === '1'
                    ) {

                        resetReferralForm();
                        closeReferralModal();

                        Swal.fire({
                            icon: 'success',
                            title: 'Referral submitted',
                            text: response.message || 'Referral submitted successfully.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });

                        if (typeof window.loadReferrals === 'function') {
                            window.loadReferrals();
                        }

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Unable to Submit',
                            text: response.message || 'Please try again.'
                        });
                    }
                },

                error: function(xhr) {

                    console.error('Referral submit error:', xhr.status);
                    console.error('Response:', xhr.responseText);

                    let message = 'Unable to submit referral. Please try again.';

                    if (xhr.status === 403) {
                        message = 'CSRF validation failed. Refresh the page and try again.';
                    } else if (xhr.status === 404) {
                        message = 'Referral submission route not found.';
                    } else if (xhr.status === 500) {
                        message = 'Server error. Check the controller and database query.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: message
                    });
                },

                complete: function() {
                    $button.prop('disabled', false).html(`
                    <i class="bi bi-send"></i>
                    Submit Referral
                `);
                }
            });

        });

    });
</script>
<style>
    /* =========================================
   REFERRAL MODAL
========================================= */

    .referral-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(9, 25, 56, 0.55);
    }

    .referral-modal.active {
        display: flex;
    }

    .referral-main-dialog {
        width: 100%;
        max-width: 1050px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        animation: referralModalIn 0.25s ease;
    }

    @keyframes referralModalIn {
        from {
            opacity: 0;
            transform: translateY(15px) scale(0.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .referral-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 22px;
        color: #fff;
        background: #091938;
    }

    .referral-modal-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .referral-modal-header p {
        margin: 5px 0 0;
        color: #c8d3e5;
        font-size: 12px;
    }

    .referral-close-btn {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        border: 0;
        border-radius: 9px;
        cursor: pointer;
    }

    .referral-close-btn:hover {
        background: #de7c26;
    }

    .referral-modal-body {
        padding: 20px;
        overflow-y: auto;
    }

    /* SELECTED MEMBER */

    .referral-selected-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        margin-bottom: 18px;
        color: #091938;
        background: #f0f4fa;
        border: 1px solid #dce5f1;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .referral-selected-info i {
        color: #de7c26;
        font-size: 18px;
    }

    /* FILTERS */

    .referral-filter-section {
        padding: 16px;
        margin-bottom: 20px;
        background: #f8faff;
        border: 1px solid #e5ebf4;
        border-radius: 12px;
    }

    .referral-filter-heading,
    .referral-results-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
    }

    .referral-filter-heading h6,
    .referral-results-heading h6 {
        margin: 0;
        color: #091938;
        font-size: 14px;
        font-weight: 700;
    }

    .referral-filter-heading span {
        color: #de7c26;
        font-size: 12px;
        font-weight: 700;
    }

    .referral-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .referral-filter-item label,
    .referral-mobile-section label {
        display: block;
        margin-bottom: 6px;
        color: #34435c;
        font-size: 12px;
        font-weight: 600;
    }

    .referral-filter-item input,
    .referral-filter-item select,
    .referral-mobile-section input {
        width: 100%;
        height: 40px;
        padding: 0 11px;
        color: #26344d;
        background: #fff;
        border: 1px solid #dce3ed;
        border-radius: 8px;
        outline: none;
        font-size: 12px;
    }

    .referral-filter-item input:focus,
    .referral-filter-item select:focus,
    .referral-mobile-section input:focus {
        border-color: #de7c26;
        box-shadow: 0 0 0 3px rgba(222, 124, 38, 0.1);
    }

    /* MEMBER GRID */

    .referral-member-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 13px;
        min-height: 100px;
    }

    #referralMemberResults {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 9px;
    }

    .referral-member-card {
        display: grid;
        grid-template-columns: minmax(220px, 1.15fr) minmax(0, 1.5fr) 155px;
        align-items: center;
        gap: 18px;
        min-width: 0;
        margin-bottom: 0;
        padding: 12px 15px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 35, 65, .035);
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .referral-member-card:hover {
        border-color: #9dbbe8;
        box-shadow: 0 9px 22px rgba(20, 54, 100, .10);
        transform: translateY(-2px);
    }

    .referral-member-top {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .referral-member-avatar {
        width: 54px;
        height: 54px;
        flex-shrink: 0;
        object-fit: cover;
        object-position: center top;
        border-radius: 50%;
        border: 3px solid #f0f5fc;
        box-shadow: 0 0 0 1px #dce6f3;
    }

    .referral-member-details {
        flex: 1;
        min-width: 0;
    }

    .referral-member-name {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        color: #091938;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    .referral-self-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 7px;
        color: #087c5b;
        background: #e7f7f0;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .referral-member-company {
        margin-top: 5px;
        color: #34445e;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .referral-member-company i {
        margin-right: 4px;
        color: #6484b2;
        font-size: 11px;
    }

    .referral-member-industry {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        margin-top: 6px;
        padding: 3px 8px;
        color: #50627d;
        background: #f1f5fa;
        border-radius: 20px;
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .referral-member-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 7px 14px;
        min-width: 0;
        padding: 0 0 0 15px;
        margin: 0;
        border-top: 0;
        border-left: 1px solid #edf1f6;
    }

    .referral-member-meta div {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        color: #63728a;
        font-size: 11px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .referral-member-meta i {
        flex: 0 0 14px;
        width: 14px;
        margin-top: 1px;
        color: #6484b2;
        text-align: center;
    }

    .referral-member-meta .referral-email span {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .referral-member-footer {
        min-width: 0;
        margin: 0;
        padding: 0;
    }

    .referral-select-member,
    .referral-selected-btn {
        width: 100%;
        min-height: 38px;
        padding: 8px 12px;
        color: #174b93;
        background: #f3f7fd;
        border: 1px solid #d5e2f4;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .18s ease;
    }

    .referral-select-member:hover,
    .referral-select-member:focus-visible {
        color: #fff;
        background: #174b93;
        border-color: #174b93;
        outline: none;
    }

    .referral-select-member {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        text-align: left;
    }

    .referral-select-member i {
        font-size: 14px;
        transition: transform .18s ease;
    }

    .referral-select-member:hover i {
        transform: translateX(2px);
    }

    .referral-selected-btn {
        color: #087c5b;
        background: #e7f7f0;
        border-color: #c9eadc;
        cursor: not-allowed;
    }

    .referral-priority-fieldset {
        min-width: 0;
        padding: 0;
        margin: 0;
        border: 0;
    }

    .referral-priority-fieldset legend {
        float: none;
        width: auto;
        margin-bottom: 7px;
    }

    .referral-priority-options {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 6px;
    }

    .referral-priority-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-width: 0;
        min-height: 62px;
        padding: 6px 3px;
        color: #596a83;
        background: #fff;
        border: 1px solid #dce4ef;
        border-radius: 9px;
        cursor: pointer;
        transition: .18s ease;
    }

    .referral-priority-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
    }

    .referral-priority-number {
        display: grid;
        place-items: center;
        width: 24px;
        height: 24px;
        color: #52647e;
        background: #edf2f8;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 700;
    }

    .referral-priority-label {
        font-size: 9px;
        font-weight: 600;
        line-height: 1.1;
        text-align: center;
    }

    .referral-priority-option.selected {
        color: #174b93;
        background: #f2f7ff;
        border-color: #8fb1e2;
        box-shadow: 0 0 0 2px rgba(26, 86, 184, .07);
    }

    .referral-priority-option.selected .referral-priority-number {
        color: #fff;
        background: #1a56b8;
    }

    .referral-priority-option:focus-within {
        outline: 3px solid rgba(26, 86, 184, .16);
        outline-offset: 2px;
    }

    /* EMPTY AND LOADING */

    .referral-empty-state,
    .referral-loading {
        grid-column: 1 / -1;
        padding: 35px 15px;
        color: #758198;
        text-align: center;
        font-size: 13px;
    }

    .referral-empty-state i {
        display: block;
        margin-bottom: 8px;
        color: #de7c26;
        font-size: 30px;
    }

    .referral-empty-state h6 {
        margin-bottom: 5px;
        color: #091938;
        font-weight: 700;
    }

    /* PAGINATION */

    .referral-pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin-top: 18px;
    }

    .referral-page-btn {
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        color: #091938;
        background: #fff;
        border: 1px solid #dce3ed;
        border-radius: 7px;
        font-size: 12px;
        cursor: pointer;
    }

    .referral-page-btn:hover,
    .referral-page-btn.active {
        color: #fff;
        background: #091938;
        border-color: #091938;
    }

    .referral-page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* MOBILE NUMBER AND SUBMIT */

    .referral-mobile-section {
        max-width: 350px;
        margin-top: 22px;
    }

    .referral-submit-section {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
        margin-top: 20px;
        border-top: 1px solid #edf0f5;
    }

    .referral-cancel-btn,
    .referral-submit-btn {
        min-height: 40px;
        padding: 0 18px;
        border: 0;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .referral-cancel-btn {
        color: #34435c;
        background: #edf1f6;
    }

    .referral-submit-btn {
        color: #fff;
        background: #de7c26;
    }

    .referral-submit-btn:hover {
        background: #c66a1c;
    }

    .referral-submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* RESPONSIVE */

    @media (max-width: 900px) {
        .referral-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .referral-member-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        #referralMemberResults {
            grid-template-columns: minmax(0, 1fr);
        }

        .referral-member-card {
            grid-template-columns: minmax(180px, 1fr) minmax(0, 1.2fr) 140px;
            gap: 12px;
        }
    }

    @media (max-width: 576px) {
        .referral-modal {
            padding: 8px;
        }

        .referral-main-dialog {
            max-height: 96vh;
            border-radius: 13px;
        }

        .referral-modal-header {
            padding: 14px;
        }

        .referral-modal-body {
            padding: 13px;
        }

        .referral-filter-grid,
        .referral-member-grid,
        #referralMemberResults {
            grid-template-columns: 1fr;
        }

        .referral-member-card {
            grid-template-columns: minmax(0, 1fr);
            gap: 10px;
            padding: 12px;
        }

        .referral-member-meta {
            padding: 10px 0 0;
            border-top: 1px solid #edf1f6;
            border-left: 0;
        }

        .referral-member-footer {
            padding-top: 2px;
        }

        .referral-submit-section {
            flex-direction: column-reverse;
        }

        .referral-cancel-btn,
        .referral-submit-btn {
            width: 100%;
        }
    }
</style>
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
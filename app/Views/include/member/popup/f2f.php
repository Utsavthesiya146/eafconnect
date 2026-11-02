<div class="modal fade" id="f2f_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="f2f_edit_data" style="max-width:600px;">
        <!-- Content will be loaded here via AJAX -->
    </div>
</div>
<script>
    function add_f2f() {
        $('#add_new_f2f').modal('show');
    }
</script>
<!-- ADD MEMBER MODAL -->
<div class="modal fade" id="add_new_f2f" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered f2f-dialog">
        <div class="modal-content f2f-modal-content">
            <form id="addNewf2fData" enctype="multipart/form-data">
                <!-- HEADER -->
                <div class="modal-header f2f-modal-header">
                    <div>
                        <h5 class="mb-1">Record a F2F meeting</h5>
                        <small>Capture the meeting details and who shared the business.</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body f2f-modal-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="f2f-label" for="business_source">Who gave you this business?</label>
                            <select class="form-select" name="category" id="business_source" required>
                                <option value="">-- Select Source --</option>
                                <option value="My Chapter Member">My Chapter Member</option>
                                <option value="Across Chapter Member">Across Chapter Member</option>
                                <option value="Visitor">Visitor</option>
                                <option value="New">New</option>
                            </select>
                        </div>

                        <input type="hidden" name="to_user" id="f2f_to_user" value="0">

                        <div class="col-12" id="f2fMemberPicker" hidden>
                            <div class="f2f-member-heading">
                                <label class="f2f-label mb-0">Find and select a member</label>
                                <div class="f2f-member-heading-actions">
                                    <span id="f2fMemberCount" class="f2f-member-count"></span>
                                    <button type="button" id="f2fClearFilters" class="f2f-clear-filters">Clear filters</button>
                                </div>
                            </div>

                            <div class="f2f-member-filters">
                                <input type="search"
                                    id="f2f_filter_name"
                                    class="form-control"
                                    placeholder="Search member name, phone or email"
                                    autocomplete="off">
                                <input type="search"
                                    id="f2f_filter_business"
                                    class="form-control"
                                    placeholder="Search business or company"
                                    autocomplete="off">
                                <input type="search"
                                    id="f2f_filter_industry"
                                    class="form-control"
                                    placeholder="Search industry"
                                    autocomplete="off">
                                <select id="f2f_filter_chapter" class="form-select">
                                    <option value="">All Chapters</option>
                                </select>
                            </div>

                            <div id="f2fMemberResults" class="f2f-member-results" aria-live="polite">
                                <div class="f2f-member-message">Choose a source to find members.</div>
                            </div>
                        </div>

                        <div class="col-12" id="f2fSelectedMember" hidden>
                            <div class="f2f-selected-member">
                                <span class="f2f-selected-avatar"><i class="bi bi-person-check"></i></span>
                                <div class="f2f-selected-copy">
                                    <small>Business shared by</small>
                                    <strong id="f2fSelectedMemberName"></strong>
                                </div>
                                <button type="button" id="f2fChangeMember" class="f2f-change-member">
                                    Change member
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="f2f-label" for="f2f_date">Meeting date</label>
                            <input type="date" name="date" id="f2f_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="f2f-label" for="f2f_location">Location</label>
                            <input type="text" name="location" id="f2f_location" class="form-control" required maxlength="255" placeholder="Where did you meet?">
                        </div>
                        <div class="col-12">
                            <label class="f2f-label" for="f2f_summary">Topics discussed <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="summary" id="f2f_summary" class="form-control" rows="3" placeholder="Add a short summary or key discussion points"></textarea>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer f2f-modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="f2fSubmitButton" class="btn btn-primary">Add meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .f2f-dialog {
        max-width: 760px;
    }

    .f2f-modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 24px 70px rgba(8, 24, 48, .24);
    }

    .f2f-modal-header {
        align-items: flex-start;
        padding: 20px 22px;
        color: #fff;
        background: linear-gradient(120deg, #12396a, #1b5799);
        border: 0;
    }

    .f2f-modal-header h5 {
        color: #fff;
        font-size: 17px;
        font-weight: 700;
    }

    .f2f-modal-header small {
        color: #d7e5f7;
        font-size: 12px;
    }

    .f2f-modal-body {
        padding: 22px;
    }

    .f2f-label {
        display: block;
        margin-bottom: 7px;
        color: #263a57;
        font-size: 12px;
        font-weight: 700;
    }

    #add_new_f2f .form-control,
    #add_new_f2f .form-select {
        min-height: 42px;
        border-color: #d9e2ed;
        border-radius: 9px;
        font-size: 13px;
    }

    #add_new_f2f textarea.form-control {
        min-height: 90px;
    }

    #add_new_f2f .form-control:focus,
    #add_new_f2f .form-select:focus {
        border-color: #5385c3;
        box-shadow: 0 0 0 3px rgba(27, 78, 143, .12);
    }

    .f2f-member-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin: 8px 0;
    }

    .f2f-member-count {
        color: #6a7b92;
        font-size: 11px;
    }

    .f2f-member-filters {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 10px;
    }

    .f2f-member-heading-actions {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .f2f-clear-filters {
        padding: 3px 0;
        color: #1b5799;
        background: transparent;
        border: 0;
        font-size: 11px;
        font-weight: 600;
    }

    .f2f-clear-filters:hover {
        color: #12396a;
        text-decoration: underline;
    }

    .f2f-member-results {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 250px;
        overflow-y: auto;
        padding: 1px 3px 1px 1px;
    }

    .f2f-member-option {
        display: flex;
        align-items: center;
        gap: 11px;
        width: 100%;
        padding: 10px 12px;
        color: #253954;
        text-align: left;
        background: #fff;
        border: 1px solid #e1e8f1;
        border-radius: 11px;
        transition: .18s ease;
    }

    .f2f-member-option:hover,
    .f2f-member-option:focus-visible {
        background: #f5f9ff;
        border-color: #9ab9e2;
        box-shadow: 0 4px 12px rgba(24, 63, 110, .08);
        outline: none;
    }

    .f2f-member-avatar {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        object-fit: cover;
        border: 2px solid #eef3f9;
        border-radius: 50%;
    }

    .f2f-member-copy {
        display: flex;
        flex: 1;
        flex-direction: column;
        min-width: 0;
        gap: 3px;
    }

    .f2f-member-copy strong {
        color: #142a47;
        font-size: 13px;
        line-height: 1.3;
    }

    .f2f-member-copy span {
        color: #65758d;
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .f2f-member-copy small {
        color: #7b8aa0;
        font-size: 10px;
        overflow-wrap: anywhere;
    }

    .f2f-member-option > i {
        color: #5379aa;
    }

    .f2f-member-message {
        padding: 22px 12px;
        color: #708098;
        text-align: center;
        background: #f7f9fc;
        border: 1px dashed #dbe3ee;
        border-radius: 11px;
        font-size: 12px;
    }

    .f2f-selected-member {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px;
        background: #f1f7ff;
        border: 1px solid #d5e5f9;
        border-radius: 11px;
    }

    .f2f-selected-avatar {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        color: #1b4e8f;
        background: #deebfc;
        border-radius: 50%;
    }

    .f2f-selected-copy {
        display: flex;
        flex: 1;
        flex-direction: column;
        min-width: 0;
        gap: 3px;
    }

    .f2f-selected-copy small {
        color: #667891;
        font-size: 10px;
    }

    .f2f-selected-copy strong {
        color: #162d4c;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .f2f-change-member {
        padding: 7px 10px;
        color: #1b4e8f;
        background: #fff;
        border: 1px solid #cbdcf1;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
    }

    .f2f-modal-footer {
        gap: 9px;
        padding: 14px 22px 20px;
        border: 0;
    }

    .f2f-modal-footer .btn {
        min-height: 39px;
        padding: 8px 16px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
    }

    .f2f-modal-footer .btn-primary {
        background: #1b4e8f;
        border-color: #1b4e8f;
    }

    #f2fMemberPicker[hidden],
    #f2fSelectedMember[hidden] {
        display: none !important;
    }

    @media (max-width: 575px) {
        .f2f-modal-body {
            padding: 16px;
        }

        .f2f-member-filters {
            grid-template-columns: 1fr;
        }

        .f2f-modal-footer {
            padding: 12px 16px 16px;
        }

        .f2f-modal-footer .btn {
            flex: 1;
        }
    }
</style>
<script>
    $(document).ready(function() {
        const membersUrl = "<?= site_url('member/connect/getMembers') ?>";
        const defaultPhoto = <?= json_encode(base_url('uploads/profile/empty.png')) ?>;
        const csrfName = "<?= csrf_token() ?>";
        let membersRequest = null;
        let members = [];

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : String(value)).html();
        }

        function renderMembers() {
            const nameSearch = $.trim($('#f2f_filter_name').val() || '').toLowerCase();
            const businessSearch = $.trim($('#f2f_filter_business').val() || '').toLowerCase();
            const industrySearch = $.trim($('#f2f_filter_industry').val() || '').toLowerCase();
            const chapterFilter = $('#f2f_filter_chapter').val() || '';
            const filtered = members.filter(function(member) {
                const memberMatches = [
                    member.name,
                    member.email,
                    member.phone
                ].join(' ').toLowerCase().includes(nameSearch);
                const businessMatches = String(member.business_name || '')
                    .toLowerCase().includes(businessSearch);
                const industryMatches = String(member.industry || '')
                    .toLowerCase().includes(industrySearch);
                const chapterMatches = !chapterFilter ||
                    String(member.chapter_code || '') === chapterFilter;

                return memberMatches && businessMatches && industryMatches && chapterMatches;
            });

            $('#f2fMemberCount').text(
                filtered.length + (filtered.length === 1 ? ' member' : ' members')
            );

            if (!filtered.length) {
                $('#f2fMemberResults').html(
                    '<div class="f2f-member-message">No matching members found. Try a different search.</div>'
                );
                return;
            }

            const html = filtered.map(function(member) {
                const name = member.name || 'Member';
                const business = member.business_name || member.industry || 'Business details not available';
                const photo = member.profile_photo
                    ? "<?= base_url('uploads/profile/') ?>" + encodeURIComponent(member.profile_photo)
                    : defaultPhoto;

                return `
                    <button type="button"
                        class="f2f-member-option"
                        data-user-code="${escapeHtml(member.user_code)}"
                        data-name="${escapeHtml(name)}">
                        <img class="f2f-member-avatar"
                            src="${escapeHtml(photo)}"
                            alt="${escapeHtml(name)}"
                            onerror="this.onerror=null;this.src='${escapeHtml(defaultPhoto)}';">
                        <span class="f2f-member-copy">
                            <strong>${escapeHtml(name)}</strong>
                            <span>${escapeHtml(business)}</span>
                            <small>${escapeHtml([
                                member.industry,
                                member.chapter_name
                            ].filter(Boolean).join(' · ') || 'Member')}</small>
                        </span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                `;
            }).join('');

            $('#f2fMemberResults').html(html);
        }

        function populateChapterFilter() {
            const chapters = new Map();
            members.forEach(function(member) {
                if (member.chapter_code && member.chapter_name) {
                    chapters.set(String(member.chapter_code), String(member.chapter_name));
                }
            });

            const options = ['<option value="">All Chapters</option>'];
            Array.from(chapters.entries())
                .sort(function(a, b) {
                    return a[1].localeCompare(b[1]);
                })
                .forEach(function(chapter) {
                    options.push(
                        '<option value="' + escapeHtml(chapter[0]) + '">' +
                        escapeHtml(chapter[1]) +
                        '</option>'
                    );
                });

            $('#f2f_filter_chapter').html(options.join(''));
        }

        function loadMembers(type) {
            if (membersRequest && membersRequest.readyState !== 4) {
                membersRequest.abort();
            }

            $('#f2f_filter_name, #f2f_filter_business, #f2f_filter_industry').val('');
            $('#f2f_filter_chapter').html('<option value="">All Chapters</option>');
            $('#f2fMemberPicker').prop('hidden', false);
            $('#f2fSelectedMember').prop('hidden', true);
            $('#f2f_to_user').val('');
            $('#f2fMemberCount').text('');
            $('#f2fMemberResults').html(
                '<div class="f2f-member-message"><span class="spinner-border spinner-border-sm me-2"></span>Loading members...</div>'
            );

            const data = { type: type };
            const token = $('input[name="' + csrfName + '"]').val();
            if (token) {
                data[csrfName] = token;
            }

            membersRequest = $.ajax({
                url: membersUrl,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (!Array.isArray(response)) {
                        $('#f2fMemberResults').html(
                            '<div class="f2f-member-message">Unable to load members. Please try again.</div>'
                        );
                        return;
                    }

                    members = response;
                    populateChapterFilter();
                    renderMembers();
                },
                error: function(xhr, status) {
                    if (status === 'abort') {
                        return;
                    }
                    console.error('F2F member search failed:', xhr.status, xhr.responseText);
                    $('#f2fMemberResults').html(
                        '<div class="f2f-member-message">Unable to load members. Please try again.</div>'
                    );
                },
                complete: function() {
                    membersRequest = null;
                }
            });
        }

        $('#business_source').on('change', function() {
            const source = $(this).val();

            $('#f2f_to_user').val('0');
            $('#f2fSelectedMember').prop('hidden', true);

            if (source === 'My Chapter Member') {
                loadMembers('chapter');
            } else if (source === 'Across Chapter Member') {
                loadMembers('all');
            } else {
                if (membersRequest && membersRequest.readyState !== 4) {
                    membersRequest.abort();
                }
                members = [];
                $('#f2fMemberPicker').prop('hidden', true);
                $('#f2fMemberResults').empty();
                $('#f2fMemberCount').text('');
            }
        });

        $('#f2f_filter_name, #f2f_filter_business, #f2f_filter_industry')
            .on('input', renderMembers);
        $('#f2f_filter_chapter').on('change', renderMembers);
        $('#f2fClearFilters').on('click', function() {
            $('#f2f_filter_name, #f2f_filter_business, #f2f_filter_industry').val('');
            $('#f2f_filter_chapter').val('');
            renderMembers();
        });

        $(document).on('click', '.f2f-member-option', function() {
            const userCode = $(this).data('user-code');
            const memberName = $(this).data('name');

            if (!userCode) {
                return;
            }

            $('#f2f_to_user').val(userCode);
            $('#f2fSelectedMemberName').text(memberName);
            $('#f2fSelectedMember').prop('hidden', false);
            $('#f2fMemberPicker').prop('hidden', true);
        });

        $('#f2fChangeMember').on('click', function() {
            $('#f2fMemberPicker').prop('hidden', false);
            $('#f2f_filter_name').trigger('focus');
        });
    });
</script>
<script>
    $(document).on("submit", "#addNewf2fData", function(e) {
        e.preventDefault();

        const source = $('#business_source').val();
        const selectedUser = $('#f2f_to_user').val();
        if (
            (source === 'My Chapter Member' || source === 'Across Chapter Member') &&
            (!selectedUser || selectedUser === '0')
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Select a member',
                text: 'Choose the member who gave you this business.'
            });
            return;
        }

        let formData = new FormData(this);
        const $submitButton = $('#f2fSubmitButton');

        $.ajax({
            url: "<?= site_url('member/f2f/addNewf2fData'); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            beforeSend: function() {
                $submitButton.prop('disabled', true).text('Saving...');
                Swal.fire({
                    title: "Saving...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            },

            success: function(res) {
                if (res.status !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Unable to save meeting',
                        text: res.message || 'Please try again.'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Meeting recorded',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $("#add_new_f2f").modal("hide");
                    location.reload();
                });
            },

            error: function(xhr) {
                console.error('F2F submission failed:', xhr.status, xhr.responseText);
                Swal.fire(
                    "Server Error",
                    "Unable to save the meeting. Please try again.",
                    "error"
                );
            },
            complete: function() {
                $submitButton.prop('disabled', false).text('Add meeting');
            }
        });
    });
</script>
<script>
    function delete_f2f(mitb_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('member/f2f/delete_f2f') ?>/" + mitb_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#delete_f2f_action", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('member/f2f/delete_f2f_action') ?>",
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
                        $("#f2f_edit").modal("hide");
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
    function edit_f2f(mitb_code) {
        $('#f2f_edit').modal('show');
        $.ajax({
            url: "<?= site_url('member/f2f/edit_f2f') ?>/" + mitb_code,
            success: function(response) {
                $("#f2f_edit_data").html(response);
            }
        });
    }

    $(document).on("submit", "#edit_f2f_action", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('member/f2f/edit_f2f_action') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: "Edit...",
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
                        $("#f2f_edit").modal("hide");
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
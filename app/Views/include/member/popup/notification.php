<!-- Notification View Modal -->
<div class="modal fade" id="notification_view_modal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="notification_modal_content" style="max-width:540px;">
        <!-- AJAX content loaded here -->
    </div>
</div>

<script>
    /**
     * View full notification details in modal (and auto-marks as read)
     */
    function viewNotificationDetails(n_code) {
        if (!n_code) return;

        // Open modal with loading state
        $('#notification_modal_content').html(`
            <div class="modal-content border-0 p-5 text-center shadow-lg" style="border-radius:16px;">
                <div class="spinner-border text-primary mx-auto mb-3" role="status"></div>
                <p class="text-muted mb-0 small">Loading notification...</p>
            </div>
        `);
        $('#notification_view_modal').modal('show');

        $.ajax({
            url: "<?= base_url('member/notifications/view_details') ?>/" + encodeURIComponent(n_code),
            type: "GET",
            success: function(response) {
                $('#notification_modal_content').html(response);
                // Mark card as read visually if on notifications page
                const card = $('#notif-card-' + n_code);
                if (card.length && card.hasClass('unread')) {
                    card.removeClass('unread').addClass('is-read');
                    const btn = card.find('.btn-status-toggle');
                    btn.removeClass('unread').addClass('is-read')
                       .html('<i class="ri-mail-line"></i> Mark Unread')
                       .attr('onclick', `toggleNotificationStatus('${n_code}', 'unread', event)`);
                }
                // Refresh header unread count if available
                updateHeaderBadge();
            },
            error: function() {
                $('#notification_modal_content').html(`
                    <div class="modal-content border-0 p-4 text-center shadow-lg" style="border-radius:16px;">
                        <i class="ri-error-warning-line text-danger" style="font-size:36px;"></i>
                        <p class="text-danger mt-2 mb-3">Failed to load notification.</p>
                        <button type="button" class="btn btn-sm btn-secondary mx-auto px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                    </div>
                `);
            }
        });
    }

    /**
     * Toggle status between read and unread
     */
    function toggleNotificationStatus(n_code, targetStatus, e) {
        if (e) e.stopPropagation();

        const card = $('#notif-card-' + n_code);
        const toggleBtn = card.find('.btn-status-toggle');
        const origHtml = toggleBtn.html();
        toggleBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: "<?= base_url('member/notifications/edit_not_status_action') ?>",
            type: "POST",
            data: {
                n_code: n_code,
                status: targetStatus
            },
            dataType: "json",
            success: function(res) {
                toggleBtn.prop('disabled', false);
                if (res.status === 'success') {
                    if (targetStatus === 'read') {
                        card.removeClass('unread').addClass('is-read');
                        toggleBtn.removeClass('unread').addClass('is-read')
                                 .html('<i class="ri-mail-line"></i> Mark Unread')
                                 .attr('onclick', `toggleNotificationStatus('${n_code}', 'unread', event)`);
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Marked as read',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        card.removeClass('is-read').addClass('unread');
                        toggleBtn.removeClass('is-read').addClass('unread')
                                 .html('<i class="ri-checkbox-circle-line"></i> Mark Read')
                                 .attr('onclick', `toggleNotificationStatus('${n_code}', 'read', event)`);

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Marked as unread',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    updateHeaderBadge(res.unread_count);
                } else {
                    toggleBtn.html(origHtml);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message || 'Failed to update status',
                        timer: 2000
                    });
                }
            },
            error: function() {
                toggleBtn.prop('disabled', false).html(origHtml);
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Unable to reach server. Please try again.',
                    timer: 2000
                });
            }
        });
    }

    /**
     * Delete confirmation & execution
     */
    function confirmDeleteNotification(n_code, e) {
        if (e) e.stopPropagation();

        Swal.fire({
            title: 'Delete Notification?',
            text: 'This notification will be permanently removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0F3B68',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                executeDeleteNotification(n_code);
            }
        });
    }

    /**
     * Delete from inside modal
     */
    function deleteNotificationDirect(n_code) {
        $('#notification_view_modal').modal('hide');
        executeDeleteNotification(n_code);
    }

    /**
     * Execute delete request
     */
    function executeDeleteNotification(n_code) {
        const card = $('#notif-card-' + n_code);

        $.ajax({
            url: "<?= base_url('member/notifications/delete_notification') ?>",
            type: "POST",
            data: { n_code: n_code },
            dataType: "json",
            beforeSend: function() {
                if (card.length) {
                    card.css('opacity', '0.4');
                }
            },
            success: function(res) {
                if (res.status === 'success') {
                    if (card.length) {
                        card.slideUp(250, function() {
                            $(this).remove();
                            checkEmptyContainers();
                        });
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Deleted successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    updateHeaderBadge(res.unread_count);
                } else {
                    if (card.length) card.css('opacity', '1');
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: res.message || 'Could not delete notification.'
                    });
                }
            },
            error: function() {
                if (card.length) card.css('opacity', '1');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Server communication error.'
                });
            }
        });
    }

    /**
     * Mark all unread notifications as read
     */
    function markAllNotificationsRead() {
        Swal.fire({
            title: 'Mark all as read?',
            text: 'All unread notifications will be marked as read.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0F3B68',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, Mark All Read',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('member/notifications/mark_all_read') ?>",
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: 'All notifications marked as read.',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    }

    /**
     * Clear all read notifications
     */
    function clearAllReadNotifications() {
        Swal.fire({
            title: 'Clear Read Notifications?',
            text: 'All read notifications will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, Clear All',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('member/notifications/clear_all_read') ?>",
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cleared',
                                text: 'Read notifications have been removed.',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    }

    /**
     * Check if containers became empty after deletion
     */
    function checkEmptyContainers() {
        if ($('.unread-container .notification-card').length === 0) {
            $('.unread-container').html('<div class="empty-mini"><i class="ri-checkbox-circle-line me-1"></i> No unread notifications</div>');
        }
        if ($('.read-container .notification-card').length === 0) {
            $('.read-container').html('<div class="empty-mini"><i class="ri-inbox-line me-1"></i> No read notifications</div>');
        }
    }

    /**
     * Live update of topbar notification badge
     */
    function updateHeaderBadge(count) {
        if (typeof count !== 'undefined') {
            applyBadgeCount(count);
        } else {
            $.ajax({
                url: "<?= base_url('member/notifications/get_header_notifications') ?>",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (res && typeof res.unread_count !== 'undefined') {
                        applyBadgeCount(res.unread_count);
                    }
                }
            });
        }
    }

    function applyBadgeCount(count) {
        const badge = $('#header-noti-badge, .kvv-noti-badge');
        const countInt = parseInt(count, 10);
        if (countInt > 0) {
            const displayStr = countInt > 9 ? '9+' : countInt.toString();
            if (badge.length) {
                badge.text(displayStr).show();
            } else {
                $('#page-header-notifications-dropdown, .noti-icon').append(`
                    <span class="badge rounded-pill bg-danger position-absolute kvv-noti-badge" id="header-noti-badge">
                        ${displayStr}
                    </span>
                `);
            }
        } else {
            badge.remove();
        }
    }
</script>
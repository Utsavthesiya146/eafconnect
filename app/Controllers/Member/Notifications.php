<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Notifications extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $user_code = (string)session()->get('user_code');
        $db = \Config\Database::connect();

        $limit = 8;

        // ================= UNREAD PAGE =================
        $unreadPage = max(1, (int)($this->request->getGet('unread_page') ?? 1));
        $unreadOffset = ($unreadPage - 1) * $limit;

        $unreadTotal = $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'unread')
            ->countAllResults();

        $unreadTotalPages = max(1, (int)ceil($unreadTotal / $limit));
        if ($unreadPage > $unreadTotalPages && $unreadTotal > 0) {
            $unreadPage = $unreadTotalPages;
            $unreadOffset = ($unreadPage - 1) * $limit;
        }

        $unread_notifications = $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'unread')
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $unreadOffset)
            ->get()
            ->getResult();

        // ================= READ PAGE =================
        $readPage = max(1, (int)($this->request->getGet('read_page') ?? 1));
        $readOffset = ($readPage - 1) * $limit;

        $readTotal = $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'read')
            ->countAllResults();

        $readTotalPages = max(1, (int)ceil($readTotal / $limit));
        if ($readPage > $readTotalPages && $readTotal > 0) {
            $readPage = $readTotalPages;
            $readOffset = ($readPage - 1) * $limit;
        }

        $read_notifications = $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'read')
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $readOffset)
            ->get()
            ->getResult();

        $data = [
            'unread_notifications' => $unread_notifications,
            'read_notifications'   => $read_notifications,
            'unreadTotal'          => $unreadTotal,
            'readTotal'            => $readTotal,
            'unreadPage'           => $unreadPage,
            'readPage'             => $readPage,
            'unreadTotalPages'     => $unreadTotalPages,
            'readTotalPages'       => $readTotalPages,
        ];

        return view('member/notifications', $data);
    }

    /**
     * Update a single notification status (read / unread)
     */
    public function edit_not_status_action()
    {
        $user_code = (string)session()->get('user_code');
        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Please log in to perform this action.'
            ]);
        }

        $n_code = trim((string)$this->request->getPost('n_code'));
        if (!$n_code) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Notification code missing'
            ]);
        }

        $status = strtolower(trim((string)$this->request->getPost('status')));
        if (!in_array($status, ['read', 'unread'], true)) {
            $status = 'read';
        }

        $db = \Config\Database::connect();
        $updated = $db->table('notifications')
            ->where('n_code', $n_code)
            ->where('user_code', $user_code)
            ->update(['status' => $status]);

        if ($updated) {
            $unreadCount = $db->table('notifications')
                ->where('user_code', $user_code)
                ->where('status', 'unread')
                ->countAllResults();

            return $this->response->setJSON([
                'status'       => 'success',
                'new_status'   => $status,
                'unread_count' => $unreadCount,
                'message'      => $status === 'read' ? 'Marked as read' : 'Marked as unread'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Could not update notification'
        ]);
    }

    /**
     * Mark all unread notifications as read
     */
    public function mark_all_read()
    {
        $user_code = (string)session()->get('user_code');
        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired'
            ]);
        }

        $db = \Config\Database::connect();
        $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return $this->response->setJSON([
            'status'       => 'success',
            'unread_count' => 0,
            'message'      => 'All notifications marked as read!'
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete_notification()
    {
        $user_code = (string)session()->get('user_code');
        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired'
            ]);
        }

        $n_code = trim((string)$this->request->getPost('n_code'));
        if (!$n_code) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Notification code missing'
            ]);
        }

        $db = \Config\Database::connect();
        $deleted = $db->table('notifications')
            ->where('n_code', $n_code)
            ->where('user_code', $user_code)
            ->delete();

        if ($deleted) {
            $unreadCount = $db->table('notifications')
                ->where('user_code', $user_code)
                ->where('status', 'unread')
                ->countAllResults();

            return $this->response->setJSON([
                'status'       => 'success',
                'unread_count' => $unreadCount,
                'message'      => 'Notification deleted successfully!'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Failed to delete notification'
        ]);
    }

    /**
     * Clear all read notifications
     */
    public function clear_all_read()
    {
        $user_code = (string)session()->get('user_code');
        if (empty($user_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Session expired'
            ]);
        }

        $db = \Config\Database::connect();
        $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'read')
            ->delete();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'All read notifications cleared!'
        ]);
    }

    /**
     * Get live notifications for topbar dropdown
     */
    public function get_header_notifications()
    {
        $user_code = (string)session()->get('user_code');
        if (empty($user_code)) {
            return $this->response->setJSON([
                'unread_count'  => 0,
                'notifications' => []
            ]);
        }

        $db = \Config\Database::connect();
        $unreadCount = $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'unread')
            ->countAllResults();

        $rows = $db->table('notifications')
            ->where('user_code', $user_code)
            ->orderBy('status', 'ASC') // 'read' > 'unread', so 'unread' comes first
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get()
            ->getResult();

        $list = [];
        foreach ($rows as $r) {
            $meta = get_notification_icon($r->title ?? '', $r->message ?? '');
            $list[] = [
                'n_code'     => $r->n_code,
                'title'      => esc($r->title),
                'message'    => esc(strip_tags($r->message)),
                'status'     => $r->status,
                'icon'       => $meta['icon'],
                'color'      => $meta['color'],
                'bg'         => $meta['bg'],
                'badge'      => $meta['badge'],
                'time_ago'   => notification_time_ago($r->created_at),
                'created_at' => date('d M Y, h:i A', strtotime($r->created_at)),
            ];
        }

        return $this->response->setJSON([
            'unread_count'  => $unreadCount,
            'notifications' => $list
        ]);
    }

    /**
     * View full notification details in modal & auto-mark as read
     */
    public function view_details($n_code = '')
    {
        $user_code = (string)session()->get('user_code');
        if (empty($user_code) || empty($n_code)) {
            return $this->response->setBody('<p class="text-danger text-center p-3">Invalid notification.</p>');
        }

        $db = \Config\Database::connect();
        $row = $db->table('notifications')
            ->where('n_code', $n_code)
            ->where('user_code', $user_code)
            ->get()
            ->getRow();

        if (!$row) {
            return $this->response->setBody('<p class="text-danger text-center p-3">Notification not found.</p>');
        }

        // Auto mark read if unread
        if ($row->status === 'unread') {
            $db->table('notifications')
                ->where('n_id', $row->n_id)
                ->update(['status' => 'read']);
        }

        $meta = get_notification_icon($row->title ?? '', $row->message ?? '');
        $timeAgo = notification_time_ago($row->created_at);
        $fullDate = date('l, d F Y - h:i A', strtotime($row->created_at));

        $output = '
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header px-4 py-3 border-0" style="background: linear-gradient(135deg, #0F3B68, #172B50); color:#fff;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; color:#FFD700; font-size:18px;">
                        <i class="' . esc($meta['icon']) . '"></i>
                    </div>
                    <div>
                        <span class="badge rounded-pill mb-1" style="background:#C6A667; color:#101B35; font-size:10px; font-weight:700;">' . esc($meta['badge']) . '</span>
                        <h6 class="modal-title mb-0 fw-bold text-white" style="font-size:16px;">' . esc($row->title) . '</h6>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <span class="text-muted small"><i class="ri-time-line me-1"></i>' . esc($timeAgo) . '</span>
                    <span class="text-muted small">' . esc($fullDate) . '</span>
                </div>
                <div class="notification-full-message" style="font-size:14px; line-height:1.7; color:#334155;">
                    ' . nl2br(esc($row->message)) . '
                </div>
            </div>
            <div class="modal-footer px-4 py-3 bg-light border-0 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="deleteNotificationDirect(\'' . esc($row->n_code) . '\')">
                    <i class="ri-delete-bin-line me-1"></i> Delete
                </button>
                <button type="button" class="btn btn-sm text-white rounded-pill px-4 fw-semibold" style="background:#0F3B68;" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>';

        return $this->response->setBody($output);
    }
}

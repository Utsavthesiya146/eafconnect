<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Notifications Controller
 *
 * GET  /api/v1/notifications          → list notifications
 * POST /api/v1/notifications/read     → mark notification as read
 * POST /api/v1/notifications/read_all → mark all as read
 */
class Notifications extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $notifications = $db->query("
            SELECT n.*, c.chapter_name
            FROM notifications n
            LEFT JOIN chapters c ON c.chapter_code = n.chapter_code
            WHERE n.user_code = ?
            ORDER BY n.created_at DESC
            LIMIT 100
        ", [$user_code])->getResultArray();

        $unread = count(array_filter($notifications, fn($n) => ($n['status'] ?? '1') === '1'));

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'notifications' => $notifications,
                'unread_count'  => $unread,
                'total'         => count($notifications),
            ],
        ]);
    }

    public function markRead()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $notif_id  = $this->request->getPost('notification_id');
        $db        = \Config\Database::connect();

        if ($notif_id) {
            $db->table('notifications')
                ->where('notification_id', $notif_id)
                ->where('user_code', $user_code)
                ->update(['status' => '2']);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Notification marked as read.',
        ]);
    }

    public function markAllRead()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        $db->table('notifications')
            ->where('user_code', $user_code)
            ->update(['status' => '2']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'All notifications marked as read.',
        ]);
    }
}

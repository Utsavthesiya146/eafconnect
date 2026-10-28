<?php

namespace App\Controllers4\Api;

use App\Controllers\BaseController;

/**
 * Api\Meetings Controller
 *
 * GET /api/v1/meetings  → list chapter meetings/events
 */
class Meetings extends BaseController
{
    public function index()
    {
        $authUser  = $this->request->getGlobal('auth_user');
        $user_code = $authUser->user_code;
        $db        = \Config\Database::connect();

        // Get user's chapter
        $chapterMember = $db->table('chapter_members')
            ->where('user_code', $user_code)
            ->where('members_status', '1')
            ->get()->getRow();

        if (!$chapterMember) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => ['meetings' => [], 'total' => 0],
            ]);
        }

        $chapter_code = $chapterMember->chapter_code;

        // Get meetings for this chapter
        $meetings = $db->query("
            SELECT e.*, c.chapter_name
            FROM events e
            LEFT JOIN chapters c ON c.chapter_code = e.chapter_code
            WHERE e.chapter_code = ? AND e.status = '1'
            ORDER BY e.date DESC
        ", [$chapter_code])->getResultArray();

        // For each meeting, get attendance status
        foreach ($meetings as &$meeting) {
            $attendance = $db->query("
                SELECT status FROM event_attendance
                WHERE event_id = ? AND user_code = ?
            ", [$meeting['event_id'], $user_code])->getRow();

            $meeting['my_attendance'] = $attendance->status ?? 'absent';
        }
        unset($meeting);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'meetings'     => $meetings,
                'total'        => count($meetings),
                'chapter_code' => $chapter_code,
            ],
        ]);
    }
}

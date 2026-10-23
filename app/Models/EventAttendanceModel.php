<?php

namespace App\Models;

use CodeIgniter\Model;

class EventAttendanceModel extends Model
{
    protected $table      = 'event_attendance';
    protected $primaryKey = 'event_a_id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'event_a_code',
        'event_code',
        'user_code',
        'chapter_code',
        'status',
        'note',
        'created_at',
        'updated_on'
    ];

    protected $useTimestamps = false;

    // Create attendance for all chapter members when event is created
    public function createAttendanceForEvent($event_code, $chapter_code)
    {
        $db = \Config\Database::connect();
        $members = $db->table('chapter_members')
                      ->select('user_code, chapter_code')
                      ->where('chapter_code', $chapter_code)
                      ->get()
                      ->getResult();

        $data = [];
        foreach ($members as $m) {
            $data[] = [
                'event_a_code' => 'EAT' . uniqid(),
                'event_code'   => $event_code,
                'user_code'    => $m->user_code,
                'chapter_code' => $m->chapter_code,
                'status'       => 'registered',
                'note'         => 'Auto registered',
                'updated_on'   => date('Y-m-d H:i:s')
            ];
        }

        if (!empty($data)) {
            $this->insertBatch($data);
        }
    }

    // Get members for attendance marking
    public function getEventAttendanceMembers($event_code)
    {
        $db = \Config\Database::connect();
        return $db->table('event_attendance ea')
                  ->select('ea.*, u.name, u.email')
                  ->join('users u', 'u.user_code = ea.user_code', 'left')
                  ->where('ea.event_code', $event_code)
                  ->get()
                  ->getResult();
    }

    // Update attendance
    public function markAttendance($event_a_id, $status, $note)
    {
        $this->where('event_a_id', $event_a_id)
             ->set([
                 'status' => $status,
                 'note' => $note,
                 'updated_on' => date('Y-m-d H:i:s')
             ])
             ->update();
    }
}
<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\EventModel;
use App\Models\EventAttendanceModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class Meetings extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        $model = new \App\Models\EventModel();
        $commonModel = new CommonModel();

        $search  = $this->request->getGet('search');
        $perPage = (int)($this->request->getGet('perPage') ?? 10);

        $model->select("
        events.*,
        chapters.chapter_name
    ")
            ->join('chapters', 'chapters.chapter_code = events.chapter_code', 'left');

        if (!empty($search)) {
            $model->groupStart()
                ->like('events.title', $search)
                ->orLike('events.location', $search)
                ->orLike('chapters.chapter_name', $search)
                ->groupEnd();
        }

        $model->orderBy('events.date', 'DESC')
            ->orderBy('events.event_id', 'DESC');

        $events = $model->paginate($perPage);

        $chapters = $commonModel
            ->exe_query("
            SELECT chapter_code, chapter_name
            FROM chapters
            WHERE chapter_status='1'
            ORDER BY chapter_name ASC
        ")
            ->getResultArray();

        return view('admin/meetings', [
            'events'   => $events,
            'pager'    => $model->pager,
            'search'   => $search,
            'perPage'  => $perPage,
            'chapters' => $chapters,
        ]);
    }


    public function AddNewMeetingData()
    {
        helper(['form']);

        $commonModel = new \App\Models\CommonModel();
        $data = $this->request->getPost();

        /* ===============================
       GENERATE EVENT CODE
    ===============================*/
        $event_code = $commonModel->get_serial3(
            'events',
            'EVE',
            'event_code'
        );

        /* ===============================
       COMBINE DATE + TIME
    ===============================*/
        $date = $data['date'] ?? '';
        $time = $data['time'] ?? '00:00';

        $datetime = date('Y-m-d H:i:s', strtotime("$date $time"));

        /* ===============================
       INSERT DATA
    ===============================*/
        $insert = [
            'event_code'   => $event_code,
            'chapter_code' => $data['chapter_code'] ?? '',
            'title'        => $data['title'] ?? '',
            'description'  => $data['description'] ?? '',
            'date'         => $datetime,


            'mode'  => $data['mode'] ?? '',
            'meeting_link'  => $data['meeting_link'] ?? '',
            'description'  => $data['location'] ?? '',
            'venue'  => $data['venue'] ?? '',


            // REQUIRED FIELDS
            'location'     => 'Global Meeting',
            'note'         => 'Created via Admin',
            'updated_on'   => date('Y-m-d H:i:s'),
        ];

        $commonModel->insert_data('events', $insert);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => '✅ Meeting Created Successfully'
        ]);
    }


    public function delete_meeting($event_code)
    {
        $commonModel = new CommonModel();
        $qry = "SELECT * FROM events  WHERE event_code='" . esc($event_code) . "'";
        $user = $commonModel->exe_query($qry)->getResult();
        if (empty($user)) {
            return $this->response->setBody('<p class="text-center text-danger">User not found!</p>');
        }
        $row = $user[0]; // CI4 returns array of objects
        $output = '
<div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-size:13px;">
    <form id="delete_meeting_action" method="post">
        <div class="modal-header" style="background-color:#1b4e8f; color:#fff; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
            <h6 class="modal-title mb-0 text-white">Confirm Deletion</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-4">
            <input type="hidden" name="event_code" value="' . esc($row->event_code) . '">
            <p class="fw-bold text-dark mb-1" style="font-size:14px;"><span style="color:#05008D;">' . esc($row->title) . '</span></p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->date) . '</p>
            <p class="text-muted mb-2" style="font-size:13px;">' . esc($row->time) . '</p>
            <p class="mb-3 text-muted" style="font-size:13px;">This action cannot be undone. Are you sure you want to delete this Meeting?</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-3 p-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm m-1 px-3 text-white p-2" style="background-color:#1b4e8f;">Yes, Delete</button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function delete_meeting_action()
    {
        $commonModel = new CommonModel(); // ensure this is initialized
        $event_code = $this->request->getPost('event_code');
        if (!$event_code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Invalid request!"
            ]);
        }
        $res = $commonModel->deleteRow("events", ['event_code' => $event_code]);
        if ($res) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "✅ Meeting removed successfully!"
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "❌ Deletion failed. Please try again!"
            ]);
        }
    }
    public function edit_meeting($event_code = '')
    {
        if (empty($event_code)) {
            return $this->response->setBody(
                '<div class="alert alert-danger">Invalid Event Code!</div>'
            );
        }

        $db = \Config\Database::connect();

        $event = $db->table('events')
            ->where('event_code', $event_code)
            ->get()
            ->getRow();

        if (!$event) {
            return $this->response->setBody(
                '<div class="alert alert-danger">Meeting not found!</div>'
            );
        }

        $statusList = [
            1 => 'Scheduled',
            2 => 'Ongoing',
            3 => 'Completed'
        ];

        $statusOptions = '';

        foreach ($statusList as $key => $value) {
            $selected = ($event->status == $key) ? 'selected' : '';
            $statusOptions .= "<option value='{$key}' {$selected}>{$value}</option>";
        }

        $html = '
        <div class="modal-content">

            <form id="edit_meeting_action">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Meeting</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="event_code" value="' . esc($event->event_code) . '">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Status</label>

                            <select class="form-select" name="status">
                                ' . $statusOptions . '
                            </select>

                        </div>

                        <div class="col-md-12 mb-3">

                            <label>Meeting Title</label>

                            <input
                                type="text"
                                class="form-control"
                                name="title"
                                value="' . esc($event->title) . '">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Meeting Mode</label>

                            <input
                                type="text"
                                class="form-control"
                                name="mode"
                                value="' . esc($event->mode) . '">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Meeting Link</label>

                            <input
                                type="text"
                                class="form-control"
                                name="meeting_link"
                                value="' . esc($event->meeting_link) . '">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Location</label>

                            <input
                                type="text"
                                class="form-control"
                                name="location"
                                value="' . esc($event->location) . '">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Venue</label>

                            <input
                                type="text"
                                class="form-control"
                                name="venue"
                                value="' . esc($event->venue) . '">

                        </div>

                        <div class="col-md-12">

                            <label>Description</label>

                            <textarea
                                class="form-control"
                                rows="4"
                                name="description">' . esc($event->description) . '</textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>';

        return $this->response->setBody($html);
    }



    public function edit_meeting_action()
    {
        $commonModel = new CommonModel();

        $event_code = $this->request->getPost('event_code');

        if (empty($event_code)) {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Event code missing.'
            ]);
        }

        $data = [

            'status' => $this->request->getPost('status'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),

            'mode' => $this->request->getPost('mode'),
            'meeting_link' => $this->request->getPost('meeting_link'),
            'location' => $this->request->getPost('location'),
            'venue' => $this->request->getPost('venue')

        ];

        $update = $commonModel->update_row(
            'events',
            $data,
            ['event_code' => $event_code]
        );

        if ($update) {

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Meeting updated successfully.'
            ]);
        } else {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Update failed.'
            ]);
        }
    }

    public function attendance($event_code)
    {
        $db = \Config\Database::connect();
        $commonModel = new CommonModel();

        /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

        $event = $db->table('events')
            ->where('event_code', $event_code)
            ->get()
            ->getRow();

        if (!$event) {
            return redirect()->back()
                ->with('error', 'Event not found');
        }


        /*
    |--------------------------------------------------------------------------
    | CHAPTER
    |--------------------------------------------------------------------------
    */

        $chapter_code = $event->chapter_code;

        $chapter = $db->table('chapters')
            ->where('chapter_code', $chapter_code)
            ->get()
            ->getRow();


        /*
    |--------------------------------------------------------------------------
    | ALLOW ATTENDANCE MARKING
    |--------------------------------------------------------------------------
    */

        $today     = date('Y-m-d');
        $eventDate = date('Y-m-d', strtotime($event->date));

        $allowMark = ($today === $eventDate);


        /*
    |--------------------------------------------------------------------------
    | CHAPTER LEADER
    |--------------------------------------------------------------------------
    */

        $leader = $db->table('chapter_members cm')
            ->select('u.name AS leader_name')
            ->join(
                'users u',
                'u.user_code = cm.user_code',
                'inner'
            )

            ->where(
                'cm.role',
                'leader'
            )
            ->get()
            ->getRow();

        $leader_name = $leader->leader_name ?? 'N/A';


        /*
    |--------------------------------------------------------------------------
    | MEMBERS + CURRENT EVENT ATTENDANCE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Attendance is joined using BOTH:
    |
    | user_code
    | event_code
    |
    | This ensures an old attendance record from another event
    | is never displayed for the current event.
    |
    */


        $members = $db->table('chapter_members cm')
            ->select('
        u.user_code,
        u.name,
        u.email,
        u.profile_photo,
        u.status AS user_status,
        ea.event_a_id,
        ea.event_a_code,
        ea.status AS attendance_status,
        ea.note AS attendance_note,
        ea.updated_on AS attendance_updated_on
    ')
            ->join(
                'users u',
                'u.user_code = cm.user_code',
                'inner'
            )
            ->join(
                'event_attendance ea',
                'ea.user_code = u.user_code
         AND ea.event_code = ' . $db->escape($event_code),
                'left'
            )
            ->where('cm.chapter_code', $chapter_code)
            ->where('u.user_code !=', '')
            ->where('u.user_code !=', '0')
            ->where('u.status', '1')
            ->orderBy('u.name', 'ASC')
            ->get()
            ->getResult();
        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */
        $attendance = $db->table('event_attendance ea')
            ->select('
        ea.*,
        u.name,
        u.email,
        u.profile_photo,
        u.status AS user_status
    ')
            ->join(
                'users u',
                'u.user_code = ea.user_code',
                'left'
            )
            ->where('ea.event_code', $event_code)
            ->where('u.status', '1')
            ->orderBy('u.name', 'ASC')
            ->get()
            ->getResult();
        /*
    |--------------------------------------------------------------------------
    | VISITORS
    |--------------------------------------------------------------------------
    */

        $visitorQuery = $db->table('visitors');

        $visitorQuery->select("
        visitors.*,

        u.name AS member_name,
        u.email AS member_email,

        COALESCE(
            u.profile_photo,
            'empty.png'
        ) AS member_profile,

        c.chapter_name
    ");

        $visitorQuery->join(
            'users AS u',
            'u.user_code = visitors.user_code',
            'left'
        );

        $visitorQuery->join(
            'chapters AS c',
            'c.chapter_code = visitors.chapter_code',
            'left'
        );

        $visitorQuery->where(
            'visitors.date_visited',
            $today
        );

        $visitorQuery->orderBy(
            'visitors.visitors_id',
            'DESC'
        );

        $visitor = $visitorQuery
            ->get()
            ->getResult();


        /*
    |--------------------------------------------------------------------------
    | ACTIVE EVENTS
    |--------------------------------------------------------------------------
    */

        $events = $commonModel
            ->exe_query("
            SELECT *
            FROM events
            WHERE status = '1'
        ")
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | VIEW DATA
    |--------------------------------------------------------------------------
    */

        $data = [

            'event' => $event,

            'chapter' => $chapter,

            'members' => $members,

            'attendance' => $attendance,

            'event_code' => $event_code,

            'allowMark' => $allowMark,

            'event_title' => $event->title,

            'event_date' => $event->date,

            'leader_name' => $leader_name,

            'member_count' => count($members),

            'visitor' => $visitor,

            'events' => $events,

        ];


        /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'member/attendance',
            $data
        );
    }
    // FIX save_attendance()





    public function ajax_mark_all_present()
    {
        helper(['form']);

        $db = \Config\Database::connect();

        $event_code   = trim($this->request->getPost('event_code'));
        $chapter_code = trim($this->request->getPost('chapter_code'));

        if (empty($event_code) || empty($chapter_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Required fields missing.'
            ]);
        }

        try {

            // Get all users for the chapter
            $members = $db->table('users')
                ->select('user_code')
                ->where('chapter_code', $chapter_code)
                ->get()
                ->getResult();

            $commonModel = new \App\Models\CommonModel();

            foreach ($members as $member) {

                $exists = $db->table('event_attendance')
                    ->where('event_code', $event_code)
                    ->where('user_code', $member->user_code)
                    ->get()
                    ->getRow();

                if ($exists) {

                    // Update existing attendance
                    $db->table('event_attendance')
                        ->where('event_code', $event_code)
                        ->where('user_code', $member->user_code)
                        ->update([
                            'status'     => 'Present',
                            'updated_on' => date('Y-m-d H:i:s')
                        ]);
                } else {

                    // Insert new attendance
                    $event_a_code = $commonModel->get_serial3(
                        'event_attendance',
                        'EAT',
                        'event_a_code'
                    );

                    $db->table('event_attendance')->insert([
                        'event_a_code' => $event_a_code,
                        'event_code'   => $event_code,
                        'chapter_code' => $chapter_code,
                        'user_code'    => $member->user_code,
                        'status'       => 'Present',
                        'note'         => '',
                        'updated_on'   => date('Y-m-d H:i:s')
                    ]);
                }
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'All members marked as Present.'
            ]);
        } catch (\Throwable $e) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function get_filtered_data()
    {
        $request = \Config\Services::request();


        $search  = trim($request->getPost('search'));
        $date    = $request->getPost('date');
        $status  = $request->getPost('status');

        $model = new \App\Models\EventModel();

        /* ========= SEARCH ========= */
        if (!empty($search)) {
            $model->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('mode', $search)
                ->orLike('chapter_code', $search)
                ->orLike('location', $search)
                ->orLike('note', $search)
                ->groupEnd();
        }

        /* ========= STATUS ========= */
        if (!empty($status)) {
            $model->where('status', $status);
        }

        /* ========= DATE ========= */
        if (!empty($date)) {
            $model->where('DATE(date)', $date);
        }


        $model->orderBy('event_id', 'DESC');

        $users = $model->findAll();

        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No Event Found</div>';
        }

        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/admin/card/event_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
    }



    public function attance_status($event_a_code = null)
    {
        $commonModel = new CommonModel();
        if (empty($event_a_code)) {
            return $this->response->setBody(
                '<p class="text-center text-danger">Attendance  Event Code!</p>'
            );
        }
        // Get event details
        $qry = "SELECT * FROM event_attendance WHERE event_a_code='" . esc($event_a_code) . "'";
        $event = $commonModel->exe_query($qry)->getRow();
        if (!$event) {
            return $this->response->setBody(
                '<p class="text-center text-danger">Attendance  not found!</p>'
            );
        }
        $output = '
    <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
        <form id="edit_attendance_action">
            <div class="modal-header" style="background:#1b4d8f;color:#fff;">
                <h6 class="modal-title text-white">Edit Attendance </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="event_a_code" value="' . esc($event->event_a_code) . '">
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="fw-bold mb-1">Attendance  Status</label>
                        <select name="status" class="form-control">
                            <option value="' . esc($event->status) . '">' . esc($event->status) . '</option>
                                  <option value="present">present</option>
                                   <option value="absent">absent</option>
                                    <option value="medical">medical</option>
                                     <option value="substitute">substitute</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="fw-bold mb-1">Notes</label>
                        <textarea name="note" class="form-control" rows="4">' . esc($event->note) . '</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm text-white" style="background:#1b4d8f;">Update</button>
            </div>
        </form>
    </div>';

        return $this->response->setBody($output);
    }


    public function edit_attendance_action()
    {
        $commonModel = new CommonModel();
        $event_a_code = $this->request->getPost('event_a_code');
        if (empty($event_a_code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event code missing'
            ]);
        }
        $data = [
            'status'      => $this->request->getPost('status'),
            'note'       => $this->request->getPost('note'),
        ];
        $update = $commonModel->update_row(
            'event_attendance',
            $data,
            ['event_a_code' => $event_a_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => '✅ Attendance updated successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => '❌ Update failed!'
            ]);
        }
    }


    public function my_attendance()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */

        $user_code = trim(
            (string) session()->get('user_code')
        );

        if (empty($user_code)) {
            return redirect()->to('/member_login');
        }


        /*
    |--------------------------------------------------------------------------
    | GET ONLY LOGGED-IN USER ATTENDANCE
    |--------------------------------------------------------------------------
    */

        $attendance = $db->table('event_attendance ea')
            ->select('
            ea.event_a_id,
            ea.event_code,
            ea.chapter_code,
            ea.user_code,
            ea.status,
            ea.note,
            ea.updated_on,

            e.title AS event_title,
            e.date AS event_date,
            e.status AS event_status,

            c.chapter_name
        ')

            /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */

            ->join(
                'events e',
                'ea.event_code = e.event_code',
                'left'
            )

            /*
        |--------------------------------------------------------------------------
        | CHAPTER
        |--------------------------------------------------------------------------
        */

            ->join(
                'chapters c',
                'ea.chapter_code = c.chapter_code',
                'left'
            )

            /*
        |--------------------------------------------------------------------------
        | ONLY MY ATTENDANCE
        |--------------------------------------------------------------------------
        */


            /*
        |--------------------------------------------------------------------------
        | ONLY COMPLETED / ACTIVE EVENTS
        |--------------------------------------------------------------------------
        */

            ->where(
                'e.status',
                '3'
            )

            /*
        |--------------------------------------------------------------------------
        | LATEST EVENT FIRST
        |--------------------------------------------------------------------------
        */

            ->orderBy(
                'e.date',
                'DESC'
            )

            ->get()
            ->getResult();


        /*
    |--------------------------------------------------------------------------
    | SEND DATA TO VIEW
    |--------------------------------------------------------------------------
    */

        $data = [
            'attendance' => $attendance
        ];


        /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'admin/my_attendance',
            $data
        );
    }
    public function chapter_attendance()
    {
        $db = \Config\Database::connect();
        $perPage = 20;

        $page = (int) ($this->request->getGet('page') ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;
        $builder = $db->table('event_attendance ea');

        $builder->select('
        ea.event_a_id,
        ea.event_code,
        ea.chapter_code,
        ea.user_code,
        ea.status,
        ea.note,
        ea.updated_on,

        e.title AS event_title,
        e.date AS event_date,
        e.status AS event_status,

        c.chapter_name,
        c.chapter_code AS event_chapter_code,

        u.name,
        u.email,
        u.phone,
        u.profile_photo,

        cm.role AS member_role
    ');

        $builder->join(
            'events e',
            'ea.event_code = e.event_code',
            'left'
        );


        $builder->join(
            'chapters c',
            'ea.chapter_code = c.chapter_code',
            'left'
        );

        $builder->join(
            'users u',
            'ea.user_code = u.user_code',
            'inner'
        );

        $builder->join(
            'chapter_members cm',
            'cm.user_code = ea.user_code
         AND cm.chapter_code = ea.chapter_code',
            'inner'
        );


        $builder->where(
            'e.status',
            '3'
        );


        $builder->where(
            'u.status',
            '1'
        );


        $builder->where(
            'cm.members_status',
            '1'
        );

        $countBuilder = clone $builder;

        $totalRecords = $countBuilder->countAllResults();

        $builder->orderBy(
            'e.date',
            'DESC'
        );

        $builder->orderBy(
            'c.chapter_name',
            'ASC'
        );

        $builder->orderBy(
            'u.name',
            'ASC'
        );


        $attendance = $builder
            ->limit(
                $perPage,
                $offset
            )
            ->get()
            ->getResult();


        $totalPages = $totalRecords > 0
            ? (int) ceil($totalRecords / $perPage)
            : 1;

        $memberBuilder = $db->table('users u');

        $memberBuilder->select('
        u.user_code,
        u.name,
        u.email,
        u.phone,
        u.profile_photo,

        cm.chapter_code AS member_chapter_code,

        c.chapter_name,
        c.chapter_code
    ');

        $memberBuilder->join(
            'chapter_members cm',
            'cm.user_code = u.user_code',
            'inner'
        );


        $memberBuilder->join(
            'chapters c',
            'c.chapter_code = cm.chapter_code',
            'inner'
        );

        $memberBuilder->where(
            'u.status',
            '1'
        );

        $memberBuilder->where(
            'cm.members_status',
            '1'
        );

        $memberBuilder->orderBy(
            'c.chapter_name',
            'ASC'
        );

        $memberBuilder->orderBy(
            'u.name',
            'ASC'
        );

        $member = $memberBuilder
            ->get()
            ->getResultArray();

        $chapters = $db->table('chapters')
            ->select('chapter_code, chapter_name')
            ->orderBy('chapter_name', 'ASC')
            ->get()
            ->getResultArray();


        $data = [
            'member'       => $member,
            'attendance'   => $attendance,
            'chapters'     => $chapters,

            'totalRecords' => $totalRecords,
            'totalPages'   => $totalPages,
            'currentPage'  => $page,
            'perPage'      => $perPage,
        ];

        return view(
            'admin/chapter_attendance',
            $data
        );
    }

    public function ajax_save_attendance()
    {
        $commonModel = new \App\Models\CommonModel();
        $db = \Config\Database::connect();

        $event_code   = trim($this->request->getPost('event_code'));
        $chapter_code = trim($this->request->getPost('chapter_code'));
        $user_code    = trim($this->request->getPost('user_code'));
        $status       = trim($this->request->getPost('status'));
        $note         = trim($this->request->getPost('note'));

        /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

        if (
            $event_code === '' ||
            $chapter_code === '' ||
            $user_code === '' ||
            $status === ''
        ) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Required fields missing'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | VALID STATUS
    |--------------------------------------------------------------------------
    */

        $allowed_status = [
            'present',
            'absent',
            'medical',
            'substitute'
        ];

        if (!in_array($status, $allowed_status, true)) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid attendance status'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | CHECK EXISTING RECORD
    |--------------------------------------------------------------------------
    */

        $exists = $db->table('event_attendance')
            ->select('event_a_id')
            ->where('event_code', $event_code)
            ->where('user_code', $user_code)
            ->limit(1)
            ->get()
            ->getRow();


        /*
    |--------------------------------------------------------------------------
    | UPDATE EXISTING
    |--------------------------------------------------------------------------
    */

        if (!empty($exists)) {

            $updated = $db->table('event_attendance')
                ->where('event_code', $event_code)
                ->where('user_code', $user_code)
                ->update([
                    'status'     => $status,
                    'note'       => $note,
                    'updated_on' => date('Y-m-d H:i:s')
                ]);


            if (!$updated) {

                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Unable to update attendance'
                ]);
            }


            return $this->response->setJSON([
                'status'  => 'success',
                'action'  => 'update',
                'message' => 'Attendance updated successfully'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | INSERT NEW
    |--------------------------------------------------------------------------
    */

        $event_a_code = $commonModel->get_serial3(
            'event_attendance',
            'EAT',
            'event_a_code'
        );


        $insert = [
            'event_a_code' => $event_a_code,
            'event_code'   => $event_code,
            'chapter_code' => $chapter_code,
            'user_code'    => $user_code,
            'status'       => $status,
            'note'         => $note,
            'updated_on'   => date('Y-m-d H:i:s')
        ];


        $inserted = $db->table('event_attendance')
            ->insert($insert);


        if (!$inserted) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unable to save attendance'
            ]);
        }


        return $this->response->setJSON([
            'status'  => 'success',
            'action'  => 'insert',
            'message' => 'Attendance saved successfully'
        ]);
    }






    public function get_filtered_data3()
    {
        try {

            $request = \Config\Services::request();
            $db      = \Config\Database::connect();


            /*
        |--------------------------------------------------------------------------
        | FILTER VALUES
        |--------------------------------------------------------------------------
        */

            $search = trim(
                (string) $request->getPost('search')
            );

            $memberCode = trim(
                (string) $request->getPost('to_user')
            );

            $status = trim(
                (string) $request->getPost('status')
            );

            $chapterCode = trim(
                (string) $request->getPost('chapter_code')
            );

            $fromDate = trim(
                (string) $request->getPost('from_date')
            );

            $toDate = trim(
                (string) $request->getPost('to_date')
            );

            $month = trim(
                (string) $request->getPost('month')
            );


            /*
        |--------------------------------------------------------------------------
        | ATTENDANCE QUERY
        |--------------------------------------------------------------------------
        */

            $builder = $db->table('event_attendance ea');


            /*
        |--------------------------------------------------------------------------
        | SELECT
        |--------------------------------------------------------------------------
        */

            $builder->select('
            ea.event_a_id,
            ea.event_code,
            ea.chapter_code,
            ea.user_code,
            ea.status,
            ea.note,
            ea.updated_on,

            e.title AS event_title,
            e.date AS event_date,
            e.status AS event_status,

            c.chapter_name,
            c.chapter_code AS event_chapter_code,

            u.name,
            u.email,
            u.phone,
            u.profile_photo,

            cm.role AS member_role
        ');


            /*
        |--------------------------------------------------------------------------
        | EVENT JOIN
        |--------------------------------------------------------------------------
        */

            $builder->join(
                'events e',
                'ea.event_code = e.event_code',
                'left'
            );


            /*
        |--------------------------------------------------------------------------
        | CHAPTER JOIN
        |--------------------------------------------------------------------------
        */

            $builder->join(
                'chapters c',
                'ea.chapter_code = c.chapter_code',
                'left'
            );


            /*
        |--------------------------------------------------------------------------
        | USER JOIN
        |--------------------------------------------------------------------------
        */

            $builder->join(
                'users u',
                'ea.user_code = u.user_code',
                'inner'
            );


            /*
        |--------------------------------------------------------------------------
        | CHAPTER MEMBER JOIN
        |--------------------------------------------------------------------------
        */

            $builder->join(
                'chapter_members cm',
                'cm.user_code = ea.user_code
             AND cm.chapter_code = ea.chapter_code',
                'inner'
            );


            /*
        |--------------------------------------------------------------------------
        | ONLY COMPLETED / VALID EVENTS
        |--------------------------------------------------------------------------
        */

            $builder->where(
                'e.status',
                '3'
            );


            /*
        |--------------------------------------------------------------------------
        | ONLY ACTIVE USERS
        |--------------------------------------------------------------------------
        */

            $builder->where(
                'u.status',
                '1'
            );


            /*
        |--------------------------------------------------------------------------
        | ONLY ACTIVE CHAPTER MEMBERS
        |--------------------------------------------------------------------------
        */

            $builder->where(
                'cm.members_status',
                '1'
            );


            /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | NO LOGGED-IN USER FILTER
        |
        | Admin can view attendance from ALL chapters and ALL members.
        |
        |--------------------------------------------------------------------------
        */


            /*
        |--------------------------------------------------------------------------
        | CHAPTER FILTER
        |--------------------------------------------------------------------------
        */

            if ($chapterCode !== '') {

                $builder->where(
                    'TRIM(ea.chapter_code) =',
                    $chapterCode
                );
            }


            /*
        |--------------------------------------------------------------------------
        | MEMBER FILTER
        |--------------------------------------------------------------------------
        */

            if ($memberCode !== '') {

                $builder->where(
                    'ea.user_code',
                    $memberCode
                );
            }


            /*
        |--------------------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------------------
        */

            if ($search !== '') {

                $builder->groupStart()

                    ->like(
                        'u.name',
                        $search
                    )

                    ->orLike(
                        'u.email',
                        $search
                    )

                    ->orLike(
                        'u.phone',
                        $search
                    )

                    ->orLike(
                        'ea.user_code',
                        $search
                    )

                    ->orLike(
                        'e.title',
                        $search
                    )

                    ->orLike(
                        'c.chapter_name',
                        $search
                    )

                    ->orLike(
                        'ea.chapter_code',
                        $search
                    )

                    ->orLike(
                        'ea.status',
                        $search
                    )

                    ->orLike(
                        'ea.note',
                        $search
                    )

                    ->groupEnd();
            }


            /*
        |--------------------------------------------------------------------------
        | ATTENDANCE STATUS FILTER
        |--------------------------------------------------------------------------
        */

            if ($status !== '') {

                $builder->where(
                    'ea.status',
                    $status
                );
            }


            /*
        |--------------------------------------------------------------------------
        | DATE RANGE FILTER
        |--------------------------------------------------------------------------
        |
        | From + To date takes priority over month.
        |
        |--------------------------------------------------------------------------
        */

            $validFromDate = false;
            $validToDate   = false;


            /*
        |--------------------------------------------------------------------------
        | FROM DATE
        |--------------------------------------------------------------------------
        */

            if (
                $fromDate !== '' &&
                preg_match(
                    '/^\d{4}-\d{2}-\d{2}$/',
                    $fromDate
                )
            ) {

                $validFromDate = true;
            }


            /*
        |--------------------------------------------------------------------------
        | TO DATE
        |--------------------------------------------------------------------------
        */

            if (
                $toDate !== '' &&
                preg_match(
                    '/^\d{4}-\d{2}-\d{2}$/',
                    $toDate
                )
            ) {

                $validToDate = true;
            }


            /*
        |--------------------------------------------------------------------------
        | APPLY CUSTOM DATE RANGE
        |--------------------------------------------------------------------------
        */

            if ($validFromDate || $validToDate) {

                if ($validFromDate) {

                    $builder->where(
                        'e.date >=',
                        $fromDate . ' 00:00:00'
                    );
                }


                if ($validToDate) {

                    $builder->where(
                        'e.date <=',
                        $toDate . ' 23:59:59'
                    );
                }
            }


            /*
        |--------------------------------------------------------------------------
        | MONTH FILTER
        |--------------------------------------------------------------------------
        |
        | Only apply month when no custom date filter is selected.
        |
        |--------------------------------------------------------------------------
        */ elseif ($month !== '') {

                if (
                    preg_match(
                        '/^\d{4}-\d{2}$/',
                        $month
                    )
                ) {

                    $monthStart = $month . '-01';

                    $monthEnd = date(
                        'Y-m-t',
                        strtotime($monthStart)
                    );


                    /*
                |--------------------------------------------------------------
                | START OF MONTH
                |--------------------------------------------------------------
                */

                    $builder->where(
                        'e.date >=',
                        $monthStart . ' 00:00:00'
                    );


                    /*
                |--------------------------------------------------------------
                | END OF MONTH
                |--------------------------------------------------------------
                */

                    $builder->where(
                        'e.date <=',
                        $monthEnd . ' 23:59:59'
                    );
                }
            }


            /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

            $builder->orderBy(
                'e.date',
                'DESC'
            );

            $builder->orderBy(
                'c.chapter_name',
                'ASC'
            );

            $builder->orderBy(
                'u.name',
                'ASC'
            );


            /*
        |--------------------------------------------------------------------------
        | EXECUTE QUERY
        |--------------------------------------------------------------------------
        */

            $attendance = $builder
                ->get()
                ->getResult();


            /*
        |--------------------------------------------------------------------------
        | NO DATA
        |--------------------------------------------------------------------------
        */

            if (empty($attendance)) {

                return '
                <tr>
                    <td colspan="5" class="text-center">

                        <div class="empty-state">

                            <div class="empty-icon">
                                <i class="fa fa-calendar-xmark"></i>
                            </div>

                            <h6>
                                No Attendance Records
                            </h6>

                            <p>
                                No attendance matches the selected filters.
                            </p>

                        </div>

                    </td>
                </tr>
            ';
            }


            /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */

            $output   = '';
            $serialNo = 1;


            foreach ($attendance as $a) {

                $output .= view(
                    'include/admin/card/chapter_attendance_card',
                    [
                        'a'        => $a,
                        'serialNo' => $serialNo
                    ]
                );

                $serialNo++;
            }


            /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

            return $output;
        } catch (\Throwable $e) {

            log_message(
                'error',
                'GET FILTERED CHAPTER ATTENDANCE ERROR: ' .
                    $e->getMessage()
            );


            return '
            <tr>
                <td colspan="5" class="text-center">

                    <div class="alert alert-danger mb-0">

                        <strong>
                            Error loading attendance
                        </strong>

                        <br>

                        <small>
                            ' .
                esc($e->getMessage())
                . '
                        </small>

                    </div>

                </td>
            </tr>
        ';
        }
    }
    public function attendance_pdf()
    {
        $request = \Config\Services::request();
        $db      = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | ADMIN LOGIN
    |--------------------------------------------------------------------------
    |
    | Change admin_code to your actual admin session key if different.
    |
    */



        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        $search = trim(
            (string) $request->getGet('search')
        );

        $memberCode = trim(
            (string) $request->getGet('to_user')
        );

        $chapterCode = trim(
            (string) $request->getGet('chapter_code')
        );

        $status = trim(
            (string) $request->getGet('status')
        );

        $fromDate = trim(
            (string) $request->getGet('from_date')
        );

        $toDate = trim(
            (string) $request->getGet('to_date')
        );

        $month = trim(
            (string) $request->getGet('month')
        );


        /*
    |--------------------------------------------------------------------------
    | VALIDATE FROM DATE
    |--------------------------------------------------------------------------
    */

        if (
            $fromDate !== '' &&
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $fromDate
            )
        ) {
            $fromDate = '';
        }


        /*
    |--------------------------------------------------------------------------
    | VALIDATE TO DATE
    |--------------------------------------------------------------------------
    */

        if (
            $toDate !== '' &&
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $toDate
            )
        ) {
            $toDate = '';
        }


        /*
    |--------------------------------------------------------------------------
    | VALIDATE MONTH
    |--------------------------------------------------------------------------
    */

        if (
            $month !== '' &&
            !preg_match(
                '/^\d{4}-\d{2}$/',
                $month
            )
        ) {
            $month = '';
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('event_attendance ea');


        /*
    |--------------------------------------------------------------------------
    | SELECT
    |--------------------------------------------------------------------------
    */

        $builder->select('
        ea.event_a_id,
        ea.event_code,
        ea.chapter_code,
        ea.user_code,
        ea.status,
        ea.note,
        ea.updated_on,

        e.title AS event_title,
        e.date AS event_date,
        e.status AS event_status,

        c.chapter_name,
        c.chapter_code AS event_chapter_code,

        u.name,
        u.email,
        u.phone,
        u.profile_photo,

        cm.role AS member_role
    ');


        /*
    |--------------------------------------------------------------------------
    | EVENT JOIN
    |--------------------------------------------------------------------------
    |
    | ONLY EVENTS WHERE e.status = 3
    |
    */

        $builder->join(
            'events e',
            "ea.event_code = e.event_code AND e.status = '3'",
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER JOIN
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'chapters c',
            'ea.chapter_code = c.chapter_code',
            'left'
        );


        /*
    |--------------------------------------------------------------------------
    | USER JOIN
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'users u',
            'ea.user_code = u.user_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER MEMBER JOIN
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'chapter_members cm',
            'cm.user_code = ea.user_code
         AND cm.chapter_code = ea.chapter_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | IMPORTANT - ADMIN ACCESS
    |--------------------------------------------------------------------------
    |
    | NO logged-in user restriction.
    |
    | NO whereIn() based on user's chapters.
    |
    | Admin can see ALL chapters.
    |
    |--------------------------------------------------------------------------
    */


        /*
    |--------------------------------------------------------------------------
    | ACTIVE USER
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'u.status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | ACTIVE CHAPTER MEMBER
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'cm.members_status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER FILTER
    |--------------------------------------------------------------------------
    */

        if ($chapterCode !== '') {

            $builder->where(
                'TRIM(ea.chapter_code) =',
                $chapterCode
            );
        }


        /*
    |--------------------------------------------------------------------------
    | MEMBER FILTER
    |--------------------------------------------------------------------------
    */

        if ($memberCode !== '') {

            $builder->where(
                'ea.user_code',
                $memberCode
            );
        }


        /*
    |--------------------------------------------------------------------------
    | SEARCH FILTER
    |--------------------------------------------------------------------------
    */

        if ($search !== '') {

            $builder->groupStart();

            $builder->like(
                'u.name',
                $search
            );

            $builder->orLike(
                'u.email',
                $search
            );

            $builder->orLike(
                'u.phone',
                $search
            );

            $builder->orLike(
                'ea.user_code',
                $search
            );

            $builder->orLike(
                'e.title',
                $search
            );

            $builder->orLike(
                'c.chapter_name',
                $search
            );

            $builder->orLike(
                'ea.chapter_code',
                $search
            );

            $builder->orLike(
                'ea.status',
                $search
            );

            $builder->orLike(
                'ea.note',
                $search
            );

            $builder->groupEnd();
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE STATUS FILTER
    |--------------------------------------------------------------------------
    */

        if ($status !== '') {

            $builder->where(
                'ea.status',
                $status
            );
        }


        /*
    |--------------------------------------------------------------------------
    | DATE FILTER
    |--------------------------------------------------------------------------
    |
    | Custom From/To date has priority over Month.
    |
    |--------------------------------------------------------------------------
    */

        if ($fromDate !== '') {

            $builder->where(
                'e.date >=',
                $fromDate . ' 00:00:00'
            );
        }


        if ($toDate !== '') {

            $builder->where(
                'e.date <=',
                $toDate . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | MONTH FILTER
    |--------------------------------------------------------------------------
    |
    | Apply only when From/To dates are not selected.
    |
    |--------------------------------------------------------------------------
    */

        if (
            $month !== '' &&
            $fromDate === '' &&
            $toDate === ''
        ) {

            $monthStart = $month . '-01';

            $monthEnd = date(
                'Y-m-t',
                strtotime($monthStart)
            );


            $builder->where(
                'e.date >=',
                $monthStart . ' 00:00:00'
            );


            $builder->where(
                'e.date <=',
                $monthEnd . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'e.date',
            'DESC'
        );

        $builder->orderBy(
            'c.chapter_name',
            'ASC'
        );

        $builder->orderBy(
            'u.name',
            'ASC'
        );


        /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

        $attendance = $builder
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | REPORT DATE DISPLAY
    |--------------------------------------------------------------------------
    */

        if (
            $fromDate !== '' &&
            $toDate !== ''
        ) {

            $dateDisplay =
                date(
                    'd-m-Y',
                    strtotime($fromDate)
                )
                . ' to '
                .
                date(
                    'd-m-Y',
                    strtotime($toDate)
                );
        } elseif ($fromDate !== '') {

            $dateDisplay =
                'From '
                .
                date(
                    'd-m-Y',
                    strtotime($fromDate)
                );
        } elseif ($toDate !== '') {

            $dateDisplay =
                'Up to '
                .
                date(
                    'd-m-Y',
                    strtotime($toDate)
                );
        } elseif ($month !== '') {

            $dateDisplay =
                date(
                    'F Y',
                    strtotime($month . '-01')
                );
        } else {

            $dateDisplay = 'All Dates';
        }


        /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

        $totalRecords = count($attendance);

        $presentCount    = 0;
        $absentCount     = 0;
        $medicalCount    = 0;
        $lateCount       = 0;
        $substituteCount = 0;
        $otherCount      = 0;


        foreach ($attendance as $row) {

            $attendanceStatus = strtolower(
                trim(
                    (string) ($row['status'] ?? '')
                )
            );


            switch ($attendanceStatus) {

                case 'present':

                    $presentCount++;

                    break;


                case 'absent':

                    $absentCount++;

                    break;


                case 'medical':

                    $medicalCount++;

                    break;


                case 'late':

                    $lateCount++;

                    break;


                case 'substitute':

                    $substituteCount++;

                    break;


                default:

                    $otherCount++;

                    break;
            }
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE PERCENTAGE
    |--------------------------------------------------------------------------
    */

        $attendancePercentage = 0;

        if ($totalRecords > 0) {

            $attendancePercentage = round(
                (
                    $presentCount /
                    $totalRecords
                ) * 100,
                1
            );
        }


        /*
    |--------------------------------------------------------------------------
    | REPORT TITLE
    |--------------------------------------------------------------------------
    */

        $reportTitle = 'Admin Attendance Report';


        /*
    |--------------------------------------------------------------------------
    | ESCAPE HELPER
    |--------------------------------------------------------------------------
    */

        $e = static function ($value) {

            return htmlspecialchars(
                (string) $value,
                ENT_QUOTES,
                'UTF-8'
            );
        };


        /*
    |--------------------------------------------------------------------------
    | HTML START
    |--------------------------------------------------------------------------
    */

        $html = '<!DOCTYPE html>';

        $html .= '<html>';

        $html .= '<head>';

        $html .= '<meta charset="UTF-8">';


        /*
    |--------------------------------------------------------------------------
    | CSS
    |--------------------------------------------------------------------------
    */

        $html .= '
<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 25px;
    background: #f4f6f9;
    color: #1f2937;
    font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
    font-size: 12px;
}

.report {
    width: 100%;
    background: #ffffff;
    border-radius: 12px;
    padding: 22px;
}

.report-header {
    display: table;
    width: 100%;
    padding-bottom: 18px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 18px;
}

.report-header-left {
    display: table-cell;
    vertical-align: middle;
}

.report-header-right {
    display: table-cell;
    vertical-align: middle;
    text-align: right;
}

.report-icon {
    display: inline-block;
    width: 42px;
    height: 42px;
    line-height: 42px;
    text-align: center;
    background: #eef4ff;
    color: #2563eb;
    border-radius: 9px;
    font-size: 18px;
    margin-right: 10px;
    vertical-align: middle;
}

.report-title-wrap {
    display: inline-block;
    vertical-align: middle;
}

.report-title {
    margin: 0;
    font-size: 21px;
    font-weight: bold;
    color: #111827;
}

.report-subtitle {
    margin-top: 4px;
    font-size: 10px;
    color: #6b7280;
}

.report-period {
    margin-top: 5px;
    font-size: 11px;
    font-weight: bold;
    color: #2563eb;
}

.filter-summary {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 18px;
}

.filter-title {
    font-weight: bold;
    color: #374151;
    margin-right: 7px;
}

.filter-tag {
    display: inline-block;
    padding: 4px 8px;
    margin-right: 5px;
    margin-bottom: 3px;
    border-radius: 12px;
    background: #eef2ff;
    color: #3730a3;
    font-size: 9px;
    font-weight: bold;
}

.summary-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 8px;
    margin: 0 -8px 18px -8px;
}

.summary-table td {
    width: 16.66%;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px;
    vertical-align: top;
}

.kpi-label {
    display: block;
    color: #6b7280;
    font-size: 9px;
    text-transform: uppercase;
    font-weight: bold;
}

.kpi-value {
    display: block;
    margin-top: 5px;
    font-size: 20px;
    font-weight: bold;
    color: #111827;
}

.kpi-blue {
    border-top: 3px solid #2563eb !important;
}

.kpi-green {
    border-top: 3px solid #16a34a !important;
}

.kpi-red {
    border-top: 3px solid #dc2626 !important;
}

.kpi-orange {
    border-top: 3px solid #f59e0b !important;
}

.kpi-purple {
    border-top: 3px solid #7c3aed !important;
}

.kpi-cyan {
    border-top: 3px solid #0891b2 !important;
}

.table-wrapper {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

table.attendance-table {
    width: 100%;
    border-collapse: collapse;
}

.attendance-table thead th {
    background: #f8fafc;
    color: #6b7280;
    font-size: 9px;
    text-transform: uppercase;
    font-weight: bold;
    padding: 9px 7px;
    border-bottom: 1px solid #e5e7eb;
    text-align: center;
}

.attendance-table thead th.left {
    text-align: left;
}

.attendance-table tbody td {
    padding: 8px 7px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    text-align: center;
}

.attendance-table tbody td.left {
    text-align: left;
}

.attendance-table tbody tr:last-child td {
    border-bottom: 0;
}

.member-name {
    font-weight: bold;
    color: #111827;
}

.member-email {
    margin-top: 2px;
    font-size: 8px;
    color: #9ca3af;
}

.status-badge {
    display: inline-block;
    min-width: 52px;
    padding: 4px 7px;
    border-radius: 5px;
    font-size: 8px;
    font-weight: bold;
}

.status-present {
    background: #dcfce7;
    color: #15803d;
}

.status-absent {
    background: #fee2e2;
    color: #b91c1c;
}

.status-late {
    background: #fef3c7;
    color: #b45309;
}

.status-medical {
    background: #e0f2fe;
    color: #0369a1;
}

.status-substitute {
    background: #ede9fe;
    color: #6d28d9;
}

.status-other {
    background: #f3f4f6;
    color: #374151;
}

.report-footer {
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
    display: table;
    width: 100%;
    font-size: 8px;
    color: #9ca3af;
}

.footer-left {
    display: table-cell;
    text-align: left;
}

.footer-right {
    display: table-cell;
    text-align: right;
}

@page {
    margin: 20px;
}

</style>
';

        $html .= '</head>';

        $html .= '<body>';

        $html .= '<div class="report">';


        /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

        $html .= '
<div class="report-header">

    <div class="report-header-left">

        <span class="report-icon">
            &#128202;
        </span>

        <span class="report-title-wrap">

            <div class="report-title">
                ' . $e($reportTitle) . '
            </div>

            <div class="report-subtitle">
                Complete chapter-wise member attendance overview
            </div>

            <div class="report-period">
                ' . $e($dateDisplay) . '
            </div>

        </span>

    </div>

    <div class="report-header-right">

        <strong>
            Generated:
        </strong>

        ' . date('d M Y h:i A') . '

    </div>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | ACTIVE FILTERS
    |--------------------------------------------------------------------------
    */

        $activeFilters = [];


        if ($search !== '') {

            $activeFilters[] =
                'Search: ' .
                $e($search);
        }


        if ($chapterCode !== '') {

            $activeFilters[] =
                'Chapter: ' .
                $e($chapterCode);
        }


        if ($memberCode !== '') {

            $activeFilters[] =
                'Member: ' .
                $e($memberCode);
        }


        if ($status !== '') {

            $activeFilters[] =
                'Status: ' .
                ucfirst(
                    $e($status)
                );
        }


        if ($fromDate !== '') {

            $activeFilters[] =
                'From: ' .
                date(
                    'd M Y',
                    strtotime($fromDate)
                );
        }


        if ($toDate !== '') {

            $activeFilters[] =
                'To: ' .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        }


        if (
            $month !== '' &&
            $fromDate === '' &&
            $toDate === ''
        ) {

            $activeFilters[] =
                'Month: ' .
                date(
                    'F Y',
                    strtotime(
                        $month . '-01'
                    )
                );
        }


        if (!empty($activeFilters)) {

            $html .= '
<div class="filter-summary">

    <span class="filter-title">
        Active Filters:
    </span>
';

            foreach ($activeFilters as $filter) {

                $html .= '
    <span class="filter-tag">
        ' . $filter . '
    </span>
';
            }

            $html .= '
</div>
';
        }


        /*
    |--------------------------------------------------------------------------
    | SUMMARY CARDS
    |--------------------------------------------------------------------------
    */

        $html .= '
<table class="summary-table">

<tr>

    <td class="kpi-blue">

        <span class="kpi-label">
            Total Records
        </span>

        <span class="kpi-value">
            ' . $totalRecords . '
        </span>

    </td>


    <td class="kpi-green">

        <span class="kpi-label">
            Present
        </span>

        <span class="kpi-value">
            ' . $presentCount . '
        </span>

    </td>


    <td class="kpi-red">

        <span class="kpi-label">
            Absent
        </span>

        <span class="kpi-value">
            ' . $absentCount . '
        </span>

    </td>


    <td class="kpi-orange">

        <span class="kpi-label">
            Late
        </span>

        <span class="kpi-value">
            ' . $lateCount . '
        </span>

    </td>


    <td class="kpi-purple">

        <span class="kpi-label">
            Substitute
        </span>

        <span class="kpi-value">
            ' . $substituteCount . '
        </span>

    </td>


    <td class="kpi-cyan">

        <span class="kpi-label">
            Attendance
        </span>

        <span class="kpi-value">
            ' . $attendancePercentage . '%
        </span>

    </td>

</tr>

</table>
';


        /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

        $html .= '
<div class="table-wrapper">

<table class="attendance-table">

<thead>

<tr>

    <th style="width:35px;">
        #
    </th>

    <th class="left">
        Member
    </th>

    <th>
        Chapter
    </th>

    <th>
        Meeting
    </th>

    <th>
        Date
    </th>

    <th>
        Status
    </th>

    <th>
        Note
    </th>

</tr>

</thead>

<tbody>
';


        /*
    |--------------------------------------------------------------------------
    | DATA ROWS
    |--------------------------------------------------------------------------
    */

        if (!empty($attendance)) {

            $i = 1;

            foreach ($attendance as $row) {

                $rowStatus = strtolower(
                    trim(
                        (string) ($row['status'] ?? '')
                    )
                );


                /*
            |------------------------------------------------------------------
            | STATUS CLASS
            |------------------------------------------------------------------
            */

                switch ($rowStatus) {

                    case 'present':

                        $statusClass = 'status-present';

                        break;

                    case 'absent':

                        $statusClass = 'status-absent';

                        break;

                    case 'late':

                        $statusClass = 'status-late';

                        break;

                    case 'medical':

                        $statusClass = 'status-medical';

                        break;

                    case 'substitute':

                        $statusClass = 'status-substitute';

                        break;

                    default:

                        $statusClass = 'status-other';

                        break;
                }


                /*
            |------------------------------------------------------------------
            | MEMBER
            |------------------------------------------------------------------
            */

                $memberName = trim(
                    (string) ($row['name'] ?? '')
                );

                $memberEmail = trim(
                    (string) ($row['email'] ?? '')
                );


                /*
            |------------------------------------------------------------------
            | CHAPTER
            |------------------------------------------------------------------
            */

                $chapterName = trim(
                    (string) ($row['chapter_name'] ?? '')
                );


                /*
            |------------------------------------------------------------------
            | EVENT
            |------------------------------------------------------------------
            */

                $eventTitle = trim(
                    (string) ($row['event_title'] ?? '')
                );


                /*
            |------------------------------------------------------------------
            | EVENT DATE
            |------------------------------------------------------------------
            */

                $eventDate = $row['event_date'] ?? '';

                $formattedDate = '';

                if (!empty($eventDate)) {

                    $timestamp = strtotime(
                        $eventDate
                    );

                    if ($timestamp !== false) {

                        $formattedDate =
                            date(
                                'd M Y',
                                $timestamp
                            );
                    }
                }


                /*
            |------------------------------------------------------------------
            | NOTE
            |------------------------------------------------------------------
            */

                $note = trim(
                    (string) ($row['note'] ?? '')
                );


                /*
            |------------------------------------------------------------------
            | HTML ROW
            |------------------------------------------------------------------
            */

                $html .= '
<tr>

    <td>
        ' . $i++ . '
    </td>


    <td class="left">

        <div class="member-name">
            ' . $e($memberName) . '
        </div>

        <div class="member-email">
            ' . $e($memberEmail) . '
        </div>

    </td>


    <td>
        ' . $e($chapterName) . '
    </td>


    <td>
        ' . $e($eventTitle) . '
    </td>


    <td>
        ' . $e($formattedDate) . '
    </td>


    <td>

        <span class="status-badge ' .
                    $statusClass .
                    '">

            ' .
                    strtoupper(
                        $e($rowStatus)
                    )
                    . '

        </span>

    </td>


    <td class="left">
        ' . $e($note) . '
    </td>

</tr>
';
            }
        } else {

            $html .= '
<tr>

    <td
        colspan="7"
        style="
            padding:40px;
            text-align:center;
            color:#9ca3af;
        "
    >

        No attendance records found
        for the selected filters.

    </td>

</tr>
';
        }


        /*
    |--------------------------------------------------------------------------
    | TABLE END
    |--------------------------------------------------------------------------
    */

        $html .= '
</tbody>

</table>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

        $html .= '
<div class="report-footer">

    <div class="footer-left">
        Attendance Management System
    </div>

    <div class="footer-right">

        Total Records:
        ' . $totalRecords . '

        &nbsp;&nbsp;|&nbsp;&nbsp;

        ' . $e($dateDisplay) . '

    </div>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | HTML END
    |--------------------------------------------------------------------------
    */

        $html .= '</div>';

        $html .= '</body>';

        $html .= '</html>';


        /*
    |--------------------------------------------------------------------------
    | DOMPDF
    |--------------------------------------------------------------------------
    */

        $dompdf = new \Dompdf\Dompdf();

        $dompdf->set_option(
            'isHtml5ParserEnabled',
            true
        );

        $dompdf->set_option(
            'isRemoteEnabled',
            true
        );

        $dompdf->loadHtml($html);

        $dompdf->setPaper(
            'A4',
            'landscape'
        );

        $dompdf->render();


        /*
    |--------------------------------------------------------------------------
    | PDF FILE NAME
    |--------------------------------------------------------------------------
    */

        $filename =
            'admin_attendance_report_' .
            date('Y-m-d_H-i-s') .
            '.pdf';


        /*
    |--------------------------------------------------------------------------
    | STREAM PDF
    |--------------------------------------------------------------------------
    */

        $dompdf->stream(
            $filename,
            [
                'Attachment' => false
            ]
        );

        exit;
    }
    public function attendance_excel()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        $search = trim(
            (string) $this->request->getGet('search')
        );

        $memberCode = trim(
            (string) $this->request->getGet('to_user')
        );

        $chapterCode = trim(
            (string) $this->request->getGet('chapter_code')
        );

        $status = trim(
            (string) $this->request->getGet('status')
        );

        $fromDate = trim(
            (string) $this->request->getGet('from_date')
        );

        $toDate = trim(
            (string) $this->request->getGet('to_date')
        );

        $month = trim(
            (string) $this->request->getGet('month')
        );


        /*
    |--------------------------------------------------------------------------
    | VALIDATE DATE FILTERS
    |--------------------------------------------------------------------------
    */

        if (
            $fromDate !== '' &&
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)
        ) {
            $fromDate = '';
        }

        if (
            $toDate !== '' &&
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)
        ) {
            $toDate = '';
        }

        if (
            $month !== '' &&
            !preg_match('/^\d{4}-\d{2}$/', $month)
        ) {
            $month = '';
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('event_attendance ea');


        /*
    |--------------------------------------------------------------------------
    | SELECT
    |--------------------------------------------------------------------------
    */

        $builder->select('
        ea.event_a_id,
        ea.event_code,
        ea.chapter_code,
        ea.user_code,
        ea.status,
        ea.note,
        ea.updated_on,

        e.title AS event_title,
        e.date AS event_date,
        e.status AS event_status,

        c.chapter_name,

        u.name,
        u.email,
        u.phone,

        cm.role AS member_role
    ');


        /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    | Only completed/approved events where status = 3
    */

        $builder->join(
            'events e',
            "ea.event_code = e.event_code AND e.status = '3'",
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'chapters c',
            'ea.chapter_code = c.chapter_code',
            'left'
        );


        /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'users u',
            'ea.user_code = u.user_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER MEMBER
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'chapter_members cm',
            'cm.user_code = ea.user_code
         AND cm.chapter_code = ea.chapter_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | ACTIVE USERS ONLY
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'u.status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | ACTIVE CHAPTER MEMBERS ONLY
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'cm.members_status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER FILTER
    |--------------------------------------------------------------------------
    | Admin can filter any chapter.
    */

        if ($chapterCode !== '') {

            $builder->where(
                'ea.chapter_code',
                $chapterCode
            );
        }


        /*
    |--------------------------------------------------------------------------
    | MEMBER FILTER
    |--------------------------------------------------------------------------
    */

        if ($memberCode !== '') {

            $builder->where(
                'ea.user_code',
                $memberCode
            );
        }


        /*
    |--------------------------------------------------------------------------
    | SEARCH FILTER
    |--------------------------------------------------------------------------
    */

        if ($search !== '') {

            $builder->groupStart();

            $builder->like(
                'u.name',
                $search
            );

            $builder->orLike(
                'u.email',
                $search
            );

            $builder->orLike(
                'e.title',
                $search
            );

            $builder->orLike(
                'c.chapter_name',
                $search
            );

            $builder->orLike(
                'ea.status',
                $search
            );

            $builder->orLike(
                'ea.note',
                $search
            );

            $builder->orLike(
                'ea.user_code',
                $search
            );

            $builder->groupEnd();
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE STATUS FILTER
    |--------------------------------------------------------------------------
    */

        if ($status !== '') {

            $builder->where(
                'ea.status',
                $status
            );
        }


        /*
    |--------------------------------------------------------------------------
    | FROM DATE FILTER
    |--------------------------------------------------------------------------
    */

        if ($fromDate !== '') {

            $builder->where(
                'e.date >=',
                $fromDate . ' 00:00:00'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | TO DATE FILTER
    |--------------------------------------------------------------------------
    */

        if ($toDate !== '') {

            $builder->where(
                'e.date <=',
                $toDate . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | MONTH FILTER
    |--------------------------------------------------------------------------
    |
    | Month is applied only when From / To dates are not selected.
    |
    */

        if (
            $month !== '' &&
            $fromDate === '' &&
            $toDate === ''
        ) {

            $monthStart = $month . '-01';

            $monthEnd = date(
                'Y-m-t',
                strtotime($monthStart)
            );


            $builder->where(
                'e.date >=',
                $monthStart . ' 00:00:00'
            );


            $builder->where(
                'e.date <=',
                $monthEnd . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'e.date',
            'DESC'
        );

        $builder->orderBy(
            'c.chapter_name',
            'ASC'
        );

        $builder->orderBy(
            'u.name',
            'ASC'
        );


        /*
    |--------------------------------------------------------------------------
    | GET ATTENDANCE
    |--------------------------------------------------------------------------
    */

        $attendance = $builder
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | EXCEL CONTENT
    |--------------------------------------------------------------------------
    */

        $output = '';


        /*
    |--------------------------------------------------------------------------
    | UTF-8 BOM
    |--------------------------------------------------------------------------
    */

        $output .= "\xEF\xBB\xBF";


        /*
    |--------------------------------------------------------------------------
    | TABLE START
    |--------------------------------------------------------------------------
    */

        $output .= '<table border="1">';


        /*
    |--------------------------------------------------------------------------
    | REPORT TITLE
    |--------------------------------------------------------------------------
    */

        $output .= '<tr>';

        $output .= '<th colspan="8">';
        $output .= 'Attendance Report';
        $output .= '</th>';

        $output .= '</tr>';


        /*
    |--------------------------------------------------------------------------
    | FILTER INFORMATION
    |--------------------------------------------------------------------------
    */

        $filterText = 'All Dates';


        if (
            $fromDate !== '' &&
            $toDate !== ''
        ) {

            $filterText =
                date(
                    'd M Y',
                    strtotime($fromDate)
                )
                . ' to '
                .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        } elseif ($fromDate !== '') {

            $filterText =
                'From ' .
                date(
                    'd M Y',
                    strtotime($fromDate)
                );
        } elseif ($toDate !== '') {

            $filterText =
                'Up to ' .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        } elseif ($month !== '') {

            $filterText =
                date(
                    'F Y',
                    strtotime(
                        $month . '-01'
                    )
                );
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER SUMMARY
    |--------------------------------------------------------------------------
    */

        $output .= '<tr>';

        $output .= '<td colspan="8">';

        $output .= '<strong>Date Filter:</strong> ';

        $output .= htmlspecialchars(
            $filterText,
            ENT_QUOTES,
            'UTF-8'
        );


        /*
    |----------------------------------------------------------------------
    | CHAPTER FILTER DISPLAY
    |----------------------------------------------------------------------
    */

        if ($chapterCode !== '') {

            $output .= ' &nbsp; | &nbsp; ';

            $output .= '<strong>Chapter:</strong> ';

            $output .= htmlspecialchars(
                $chapterCode,
                ENT_QUOTES,
                'UTF-8'
            );
        }


        /*
    |----------------------------------------------------------------------
    | MEMBER FILTER DISPLAY
    |----------------------------------------------------------------------
    */

        if ($memberCode !== '') {

            $output .= ' &nbsp; | &nbsp; ';

            $output .= '<strong>Member:</strong> ';

            $output .= htmlspecialchars(
                $memberCode,
                ENT_QUOTES,
                'UTF-8'
            );
        }


        /*
    |----------------------------------------------------------------------
    | STATUS FILTER DISPLAY
    |----------------------------------------------------------------------
    */

        if ($status !== '') {

            $output .= ' &nbsp; | &nbsp; ';

            $output .= '<strong>Status:</strong> ';

            $output .= htmlspecialchars(
                ucfirst($status),
                ENT_QUOTES,
                'UTF-8'
            );
        }


        $output .= '</td>';

        $output .= '</tr>';


        /*
    |--------------------------------------------------------------------------
    | COLUMN HEADINGS
    |--------------------------------------------------------------------------
    */

        $output .= '<tr>';

        $output .= '<th>S.No</th>';

        $output .= '<th>Member Name</th>';

        $output .= '<th>Email</th>';

        $output .= '<th>Event</th>';

        $output .= '<th>Chapter</th>';

        $output .= '<th>Date</th>';

        $output .= '<th>Status</th>';

        $output .= '<th>Note</th>';

        $output .= '</tr>';


        /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

        $serialNo = 1;


        foreach ($attendance as $row) {

            $output .= '<tr>';


            /*
        |----------------------------------------------------------------------
        | S.NO
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $output .= $serialNo;

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | MEMBER NAME
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $output .= htmlspecialchars(
                (string) ($row['name'] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            );

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | EMAIL
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $output .= htmlspecialchars(
                (string) ($row['email'] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            );

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | EVENT
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $output .= htmlspecialchars(
                (string) ($row['event_title'] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            );

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | CHAPTER
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $output .= htmlspecialchars(
                (string) ($row['chapter_name'] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            );

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | DATE
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            if (!empty($row['event_date'])) {

                $timestamp = strtotime(
                    $row['event_date']
                );

                if ($timestamp !== false) {

                    $output .= date(
                        'd M Y',
                        $timestamp
                    );
                }
            }

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | STATUS
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $attendanceStatus = trim(
                (string) ($row['status'] ?? '')
            );

            $output .= htmlspecialchars(
                ucfirst($attendanceStatus),
                ENT_QUOTES,
                'UTF-8'
            );

            $output .= '</td>';


            /*
        |----------------------------------------------------------------------
        | NOTE
        |----------------------------------------------------------------------
        */

            $output .= '<td>';

            $output .= htmlspecialchars(
                (string) ($row['note'] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            );

            $output .= '</td>';


            $output .= '</tr>';


            $serialNo++;
        }


        /*
    |--------------------------------------------------------------------------
    | NO RECORDS
    |--------------------------------------------------------------------------
    */

        if (empty($attendance)) {

            $output .= '<tr>';

            $output .= '<td colspan="8" style="text-align:center;">';

            $output .=
                'No attendance records found for the selected filters.';

            $output .= '</td>';

            $output .= '</tr>';
        }


        /*
    |--------------------------------------------------------------------------
    | CLOSE TABLE
    |--------------------------------------------------------------------------
    */

        $output .= '</table>';


        /*
    |--------------------------------------------------------------------------
    | FILE NAME
    |--------------------------------------------------------------------------
    */

        $filename =
            'attendance_report_' .
            date('Y-m-d_H-i-s') .
            '.xls';


        /*
    |--------------------------------------------------------------------------
    | DOWNLOAD EXCEL
    |--------------------------------------------------------------------------
    */

        return $this->response
            ->setHeader(
                'Content-Type',
                'application/vnd.ms-excel; charset=UTF-8'
            )
            ->setHeader(
                'Content-Disposition',
                'attachment; filename="' .
                    $filename .
                    '"'
            )
            ->setHeader(
                'Cache-Control',
                'max-age=0'
            )
            ->setBody(
                $output
            );
    }



    public function attendance_report()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | ADMIN LOGIN
    |--------------------------------------------------------------------------
    */

        $userCode = trim(
            (string) session()->get('user_code')
        );

        if (empty($userCode)) {
            return redirect()->to('/admin/login');
        }


        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        $search = trim(
            (string) $this->request->getGet('search')
        );

        $memberCode = trim(
            (string) $this->request->getGet('to_user')
        );

        $status = trim(
            (string) $this->request->getGet('status')
        );

        $fromDate = trim(
            (string) $this->request->getGet('from_date')
        );

        $toDate = trim(
            (string) $this->request->getGet('to_date')
        );

        $month = trim(
            (string) $this->request->getGet('month')
        );


        /*
    |--------------------------------------------------------------------------
    | VALIDATE DATES
    |--------------------------------------------------------------------------
    */

        if (
            $fromDate !== '' &&
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)
        ) {
            $fromDate = '';
        }

        if (
            $toDate !== '' &&
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)
        ) {
            $toDate = '';
        }

        if (
            $month !== '' &&
            !preg_match('/^\d{4}-\d{2}$/', $month)
        ) {
            $month = '';
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    |
    | ADMIN:
    | ALL CHAPTERS
    |
    */

        $builder = $db->table('event_attendance ea');


        /*
    |--------------------------------------------------------------------------
    | SELECT
    |--------------------------------------------------------------------------
    */

        $builder->select('
        ea.user_code,

        ea.chapter_code,

        u.name,

        u.email,

        COUNT(DISTINCT ea.event_code) AS total_meetings,

        SUM(
            CASE
                WHEN ea.status = "present"
                THEN 1
                ELSE 0
            END
        ) AS present_count,

        SUM(
            CASE
                WHEN ea.status = "absent"
                THEN 1
                ELSE 0
            END
        ) AS absent_count,

        SUM(
            CASE
                WHEN ea.status = "late"
                THEN 1
                ELSE 0
            END
        ) AS late_count,

        SUM(
            CASE
                WHEN ea.status = "substitute"
                THEN 1
                ELSE 0
            END
        ) AS substitute_count,

        SUM(
            CASE
                WHEN ea.status = "medical"
                THEN 1
                ELSE 0
            END
        ) AS medical_count
    ');


        /*
    |--------------------------------------------------------------------------
    | EVENT JOIN
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'events e',
            "ea.event_code = e.event_code AND e.status = '3'",
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | USER JOIN
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'users u',
            'ea.user_code = u.user_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER JOIN
    |--------------------------------------------------------------------------
    |
    | Change "chapters" to your actual chapter table name.
    |
    */

        $builder->join(
            'chapters c',
            'c.chapter_code = ea.chapter_code',
            'left'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER NAME
    |--------------------------------------------------------------------------
    */

        $builder->select('
        c.chapter_name
    ');


        /*
    |--------------------------------------------------------------------------
    | ACTIVE USER
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'u.status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | MEMBER FILTER
    |--------------------------------------------------------------------------
    */

        if ($memberCode !== '') {

            $builder->where(
                'ea.user_code',
                $memberCode
            );
        }


        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if ($search !== '') {

            $builder->groupStart();

            $builder->like(
                'u.name',
                $search
            );

            $builder->orLike(
                'u.email',
                $search
            );

            $builder->orLike(
                'ea.user_code',
                $search
            );

            $builder->orLike(
                'e.title',
                $search
            );

            $builder->orLike(
                'ea.chapter_code',
                $search
            );

            $builder->orLike(
                'c.chapter_name',
                $search
            );

            $builder->groupEnd();
        }


        /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

        if ($status !== '') {

            $builder->where(
                'ea.status',
                $status
            );
        }


        /*
    |--------------------------------------------------------------------------
    | FROM DATE
    |--------------------------------------------------------------------------
    */

        if ($fromDate !== '') {

            $builder->where(
                'e.date >=',
                $fromDate . ' 00:00:00'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | TO DATE
    |--------------------------------------------------------------------------
    */

        if ($toDate !== '') {

            $builder->where(
                'e.date <=',
                $toDate . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | MONTH
    |--------------------------------------------------------------------------
    */

        if (
            $month !== '' &&
            $fromDate === '' &&
            $toDate === ''
        ) {

            $monthStart = $month . '-01';

            $monthEnd = date(
                'Y-m-t',
                strtotime($monthStart)
            );

            $builder->where(
                'e.date >=',
                $monthStart . ' 00:00:00'
            );

            $builder->where(
                'e.date <=',
                $monthEnd . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | GROUP BY
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | User + Chapter
    |
    */

        $builder->groupBy([
            'ea.user_code',
            'ea.chapter_code',
            'c.chapter_name',
            'u.name',
            'u.email'
        ]);


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'c.chapter_name',
            'ASC'
        );

        $builder->orderBy(
            'u.name',
            'ASC'
        );


        /*
    |--------------------------------------------------------------------------
    | GET REPORT
    |--------------------------------------------------------------------------
    */

        $report = $builder
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

        return $this->attendanceReportHtml(
            $report,
            $search,
            $memberCode,
            $status,
            $fromDate,
            $toDate,
            $month
        );
    }
    private function attendanceReportHtml(
        array $report,
        string $search = '',
        string $memberCode = '',
        string $status = '',
        string $fromDate = '',
        string $toDate = '',
        string $month = ''
    ) {
        /*
    |--------------------------------------------------------------------------
    | REPORT PERIOD
    |--------------------------------------------------------------------------
    */

        if (!empty($fromDate) && !empty($toDate)) {

            $reportPeriod =
                date('d M Y', strtotime($fromDate))
                . ' - ' .
                date('d M Y', strtotime($toDate));
        } elseif (!empty($fromDate)) {

            $reportPeriod =
                'From ' . date('d M Y', strtotime($fromDate));
        } elseif (!empty($toDate)) {

            $reportPeriod =
                'Up to ' . date('d M Y', strtotime($toDate));
        } elseif (!empty($month)) {

            $reportPeriod =
                date('F Y', strtotime($month . '-01'));
        } else {

            $reportPeriod = 'Overall Attendance';
        }


        /*
    |--------------------------------------------------------------------------
    | TOTALS
    |--------------------------------------------------------------------------
    */

        $totalMembers    = count($report);
        $totalMeetings   = 0;
        $totalPresent    = 0;
        $totalAbsent     = 0;
        $totalLate       = 0;
        $totalSubstitute = 0;
        $totalMedical    = 0;


        foreach ($report as $row) {

            $totalMeetings +=
                (int) ($row['total_meetings'] ?? 0);

            $totalPresent +=
                (int) ($row['present_count'] ?? 0);

            $totalAbsent +=
                (int) ($row['absent_count'] ?? 0);

            $totalLate +=
                (int) ($row['late_count'] ?? 0);

            $totalSubstitute +=
                (int) ($row['substitute_count'] ?? 0);

            $totalMedical +=
                (int) ($row['medical_count'] ?? 0);
        }


        /*
    |--------------------------------------------------------------------------
    | OVERALL ATTENDANCE %
    |--------------------------------------------------------------------------
    */

        $overallPercentage = 0;

        if ($totalMeetings > 0) {

            $overallPercentage = round(
                ($totalPresent / $totalMeetings) * 100,
                1
            );
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER SUMMARY
    |--------------------------------------------------------------------------
    */

        $activeFilters = [];


        if (!empty($search)) {

            $activeFilters[] =
                'Search: ' . esc($search);
        }


        if (!empty($memberCode)) {

            $activeFilters[] =
                'Member Filter Applied';
        }


        if (!empty($status)) {

            $activeFilters[] =
                'Status: ' . ucfirst(esc($status));
        }


        if (!empty($fromDate)) {

            $activeFilters[] =
                'From: ' . date(
                    'd M Y',
                    strtotime($fromDate)
                );
        }


        if (!empty($toDate)) {

            $activeFilters[] =
                'To: ' . date(
                    'd M Y',
                    strtotime($toDate)
                );
        }


        /*
    |--------------------------------------------------------------------------
    | MONTH FILTER
    |--------------------------------------------------------------------------
    */

        if (
            !empty($month) &&
            empty($fromDate) &&
            empty($toDate)
        ) {

            $activeFilters[] =
                'Month: ' .
                date(
                    'F Y',
                    strtotime($month . '-01')
                );
        }


        /*
    |--------------------------------------------------------------------------
    | HTML START
    |--------------------------------------------------------------------------
    */

        $html = '<!DOCTYPE html>';

        $html .= '<html>';

        $html .= '<head>';

        $html .= '<meta charset="UTF-8">';

        $html .= '
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>';

        $html .= '<title>Admin Attendance Report</title>';


        /*
    |--------------------------------------------------------------------------
    | ADMIN REPORT STYLE
    |--------------------------------------------------------------------------
    */

        $html .= '

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    padding: 30px;

    background: #f4f6f9;

    color: #1f2937;

    font-family:
        Inter,
        Arial,
        Helvetica,
        sans-serif;

    font-size: 14px;
}


/* ==================================================
   REPORT CONTAINER
================================================== */

.report {

    max-width: 1500px;

    margin: 0 auto;

    background: #ffffff;

    border-radius: 14px;

    padding: 28px;

    box-shadow:
        0 4px 20px rgba(0,0,0,.06);
}


/* ==================================================
   HEADER
================================================== */

.report-header {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 20px;

    padding-bottom: 22px;

    border-bottom: 1px solid #e5e7eb;

    margin-bottom: 22px;
}

.report-title {

    display: flex;

    align-items: flex-start;

    gap: 14px;
}

.report-icon {

    width: 48px;

    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #eef4ff;

    color: #2563eb;

    border-radius: 12px;

    font-size: 20px;

    flex-shrink: 0;
}

.report-title h1 {

    margin: 0;

    font-size: 24px;

    font-weight: 700;

    color: #111827;
}

.report-title p {

    margin: 5px 0 0;

    color: #6b7280;

    font-size: 13px;
}

.report-period {

    margin-top: 6px !important;

    color: #2563eb !important;

    font-size: 12px !important;

    font-weight: 600;
}


/* ==================================================
   ADMIN BADGE
================================================== */

.admin-badge {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    margin-top: 7px;

    padding: 4px 9px;

    background: #eff6ff;

    color: #1d4ed8;

    border: 1px solid #bfdbfe;

    border-radius: 20px;

    font-size: 10px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .5px;
}


/* ==================================================
   PRINT BUTTON
================================================== */

.print-btn {

    border: 0;

    background: #111827;

    color: #fff;

    padding: 10px 18px;

    border-radius: 8px;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;
}

.print-btn:hover {

    background: #1f2937;
}


/* ==================================================
   FILTER BAR
================================================== */

.filter-summary {

    background: #f8fafc;

    border: 1px solid #e5e7eb;

    border-radius: 10px;

    padding: 12px 15px;

    margin-bottom: 22px;

    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 8px;
}

.filter-label {

    font-weight: 600;

    color: #4b5563;

    margin-right: 5px;
}

.filter-tag {

    display: inline-flex;

    align-items: center;

    padding: 5px 10px;

    border-radius: 20px;

    background: #eef2ff;

    color: #3730a3;

    font-size: 12px;

    font-weight: 600;
}


/* ==================================================
   KPI CARDS
================================================== */

.kpi-grid {

    display: grid;

    grid-template-columns:
        repeat(6, 1fr);

    gap: 14px;

    margin-bottom: 25px;
}

.kpi-card {

    position: relative;

    background: #fff;

    border: 1px solid #e5e7eb;

    border-radius: 12px;

    padding: 17px;

    min-height: 105px;

    overflow: hidden;
}

.kpi-card::after {

    content: "";

    position: absolute;

    width: 55px;

    height: 55px;

    right: -20px;

    bottom: -20px;

    background: #f3f4f6;

    border-radius: 50%;
}

.kpi-label {

    font-size: 12px;

    color: #6b7280;

    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: .3px;
}

.kpi-value {

    display: block;

    margin-top: 8px;

    font-size: 24px;

    font-weight: 700;

    color: #111827;
}

.kpi-sub {

    margin-top: 3px;

    font-size: 11px;

    color: #9ca3af;
}


/* ==================================================
   KPI COLORS
================================================== */

.kpi-primary {

    border-top: 3px solid #2563eb;
}

.kpi-success {

    border-top: 3px solid #16a34a;
}

.kpi-danger {

    border-top: 3px solid #dc2626;
}

.kpi-warning {

    border-top: 3px solid #f59e0b;
}

.kpi-purple {

    border-top: 3px solid #7c3aed;
}

.kpi-info {

    border-top: 3px solid #0891b2;
}


/* ==================================================
   SECTION HEADER
================================================== */

.section-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 12px;
}

.section-title {

    margin: 0;

    font-size: 16px;

    font-weight: 700;

    color: #111827;
}

.section-count {

    font-size: 12px;

    color: #6b7280;
}


/* ==================================================
   TABLE
================================================== */

.table-card {

    border: 1px solid #e5e7eb;

    border-radius: 12px;

    overflow: hidden;

    background: #fff;
}

table {

    width: 100%;

    border-collapse: collapse;
}

thead th {

    background: #f8fafc;

    color: #6b7280;

    font-size: 11px;

    text-transform: uppercase;

    letter-spacing: .4px;

    font-weight: 700;

    padding: 13px 12px;

    border-bottom: 1px solid #e5e7eb;

    white-space: nowrap;

    text-align: center;
}

thead th.member-col {

    text-align: left;
}

tbody td {

    padding: 13px 12px;

    border-bottom: 1px solid #f1f5f9;

    text-align: center;

    vertical-align: middle;
}

tbody tr:last-child td {

    border-bottom: 0;
}

tbody tr:hover {

    background: #fafafa;
}


/* ==================================================
   MEMBER
================================================== */

.member-info {

    display: flex;

    align-items: center;

    gap: 10px;

    text-align: left;
}

.member-avatar {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #eef2ff;

    color: #4f46e5;

    font-weight: 700;

    font-size: 13px;

    flex-shrink: 0;
}

.member-name {

    font-weight: 600;

    color: #111827;
}

.member-number {

    font-size: 11px;

    color: #9ca3af;

    margin-top: 2px;
}


/* ==================================================
   NUMBER
================================================== */

.number {

    font-weight: 700;

    color: #374151;
}


/* ==================================================
   BADGES
================================================== */

.badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 35px;

    padding: 5px 9px;

    border-radius: 6px;

    font-size: 12px;

    font-weight: 700;
}

.badge-present {

    background: #dcfce7;

    color: #15803d;
}

.badge-absent {

    background: #fee2e2;

    color: #b91c1c;
}

.badge-late {

    background: #fef3c7;

    color: #b45309;
}

.badge-substitute {

    background: #ede9fe;

    color: #6d28d9;
}

.badge-medical {

    background: #e0f2fe;

    color: #0369a1;
}


/* ==================================================
   ATTENDANCE RATE
================================================== */

.attendance-rate {

    min-width: 100px;
}

.rate-text {

    font-weight: 700;

    font-size: 12px;

    margin-bottom: 5px;
}

.progress {

    width: 100%;

    height: 6px;

    background: #e5e7eb;

    border-radius: 10px;

    overflow: hidden;
}

.progress-bar {

    height: 100%;

    border-radius: 10px;

    background: #16a34a;
}

.progress-low {

    background: #dc2626;
}

.progress-medium {

    background: #f59e0b;
}


/* ==================================================
   TOTAL ROW
================================================== */

.total-row td {

    background: #f8fafc;

    border-top: 2px solid #e5e7eb;

    font-weight: 700;
}


/* ==================================================
   EMPTY
================================================== */

.no-data {

    padding: 45px 20px !important;

    text-align: center;

    color: #9ca3af;
}


/* ==================================================
   FOOTER
================================================== */

.report-footer {

    margin-top: 18px;

    padding-top: 15px;

    border-top: 1px solid #e5e7eb;

    display: flex;

    justify-content: space-between;

    color: #9ca3af;

    font-size: 11px;
}


/* ==================================================
   RESPONSIVE
================================================== */

@media(max-width:1100px) {

    .kpi-grid {

        grid-template-columns:
            repeat(3, 1fr);
    }

    .table-card {

        overflow-x: auto;
    }

    table {

        min-width: 950px;
    }
}


@media(max-width:650px) {

    body {

        padding: 10px;
    }

    .report {

        padding: 15px;

        border-radius: 8px;
    }

    .report-header {

        flex-direction: column;
    }

    .kpi-grid {

        grid-template-columns:
            repeat(2, 1fr);
    }
}


/* ==================================================
   PRINT
================================================== */

@media print {

    body {

        padding: 0;

        background: #fff;
    }

    .report {

        max-width: none;

        padding: 10px;

        box-shadow: none;

        border-radius: 0;
    }

    .print-btn {

        display: none;
    }

    .filter-summary {

        background: #fff;
    }

    tbody tr:hover {

        background: transparent;
    }

}

</style>

';

        $html .= '</head>';

        $html .= '<body>';

        $html .= '<div class="report">';


        /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="report-header">

    <div class="report-title">

        <div class="report-icon">
            📊
        </div>

        <div>

            <h1>
                Admin Attendance Report
            </h1>

            <p>
                Organization-wide member attendance overview and meeting statistics
            </p>

            <span class="admin-badge">
                🔐 Admin Report
            </span>

            <p class="report-period">
                Report Period:
                ' . esc($reportPeriod) . '
            </p>

        </div>

    </div>


    <button
        type="button"
        class="print-btn"
        onclick="window.print()">

        🖨 Print Report

    </button>

</div>

';


        /*
    |--------------------------------------------------------------------------
    | FILTER SUMMARY
    |--------------------------------------------------------------------------
    */

        if (!empty($activeFilters)) {

            $html .= '

<div class="filter-summary">

    <span class="filter-label">
        Active Filters:
    </span>

';

            foreach ($activeFilters as $filter) {

                $html .= '

    <span class="filter-tag">
        ' . $filter . '
    </span>

';
            }

            $html .= '</div>';
        }


        /*
    |--------------------------------------------------------------------------
    | KPI CARDS
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="kpi-grid">

    <div class="kpi-card kpi-primary">

        <span class="kpi-label">
            Members
        </span>

        <strong class="kpi-value">
            ' . $totalMembers . '
        </strong>

        <div class="kpi-sub">
            Total members
        </div>

    </div>


    <div class="kpi-card kpi-info">

        <span class="kpi-label">
            Meetings
        </span>

        <strong class="kpi-value">
            ' . $totalMeetings . '
        </strong>

        <div class="kpi-sub">
            Total attendance records
        </div>

    </div>


    <div class="kpi-card kpi-success">

        <span class="kpi-label">
            Present
        </span>

        <strong class="kpi-value">
            ' . $totalPresent . '
        </strong>

        <div class="kpi-sub">
            Present records
        </div>

    </div>


    <div class="kpi-card kpi-danger">

        <span class="kpi-label">
            Absent
        </span>

        <strong class="kpi-value">
            ' . $totalAbsent . '
        </strong>

        <div class="kpi-sub">
            Absent records
        </div>

    </div>


    <div class="kpi-card kpi-warning">

        <span class="kpi-label">
            Late
        </span>

        <strong class="kpi-value">
            ' . $totalLate . '
        </strong>

        <div class="kpi-sub">
            Late attendance
        </div>

    </div>


    <div class="kpi-card kpi-purple">

        <span class="kpi-label">
            Attendance
        </span>

        <strong class="kpi-value">
            ' . $overallPercentage . '%
        </strong>

        <div class="kpi-sub">
            Overall present rate
        </div>

    </div>

</div>

';


        /*
    |--------------------------------------------------------------------------
    | TABLE SECTION
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="section-header">

    <h2 class="section-title">
        Member Attendance Summary
    </h2>

    <span class="section-count">
        ' . $totalMembers . ' Members
    </span>

</div>


<div class="table-card">

<table>

<thead>

<tr>

    <th>#</th>

    <th class="member-col">
        Member
    </th>

    <th>
        Meetings
    </th>

    <th>
        Present
    </th>

    <th>
        Absent
    </th>

    <th>
        Late
    </th>

    <th>
        Substitute
    </th>

    <th>
        Medical
    </th>

    <th>
        Attendance
    </th>

</tr>

</thead>

<tbody>

';


        /*
    |--------------------------------------------------------------------------
    | DATA ROWS
    |--------------------------------------------------------------------------
    */

        if (!empty($report)) {

            $i = 1;

            foreach ($report as $row) {

                $meetings =
                    (int) ($row['total_meetings'] ?? 0);

                $present =
                    (int) ($row['present_count'] ?? 0);

                $absent =
                    (int) ($row['absent_count'] ?? 0);

                $late =
                    (int) ($row['late_count'] ?? 0);

                $substitute =
                    (int) ($row['substitute_count'] ?? 0);

                $medical =
                    (int) ($row['medical_count'] ?? 0);


                /*
            |--------------------------------------------------------------------------
            | ATTENDANCE %
            |--------------------------------------------------------------------------
            */

                $percentage = 0;

                if ($meetings > 0) {

                    $percentage = round(
                        ($present / $meetings) * 100,
                        1
                    );
                }


                /*
            |--------------------------------------------------------------------------
            | PROGRESS CLASS
            |--------------------------------------------------------------------------
            */

                if ($percentage >= 75) {

                    $progressClass = '';
                } elseif ($percentage >= 50) {

                    $progressClass = 'progress-medium';
                } else {

                    $progressClass = 'progress-low';
                }


                /*
            |--------------------------------------------------------------------------
            | MEMBER NAME
            |--------------------------------------------------------------------------
            */

                $name = trim(
                    (string) (
                        $row['name'] ?? ''
                    )
                );


                /*
            |--------------------------------------------------------------------------
            | MEMBER INITIAL
            |--------------------------------------------------------------------------
            */

                $initial = '?';

                if ($name !== '') {

                    $initial = strtoupper(
                        substr($name, 0, 1)
                    );
                }


                /*
            |--------------------------------------------------------------------------
            | TABLE ROW
            |--------------------------------------------------------------------------
            */

                $html .= '

<tr>

    <td class="number">
        ' . $i . '
    </td>


    <td>

        <div class="member-info">

            <div class="member-avatar">
                ' . esc($initial) . '
            </div>

            <div>

                <div class="member-name">
                    ' . esc($name) . '
                </div>

                <div class="member-number">
                    Attendance Summary
                </div>

            </div>

        </div>

    </td>


    <td class="number">
        ' . $meetings . '
    </td>


    <td>

        <span class="badge badge-present">
            ' . $present . '
        </span>

    </td>


    <td>

        <span class="badge badge-absent">
            ' . $absent . '
        </span>

    </td>


    <td>

        <span class="badge badge-late">
            ' . $late . '
        </span>

    </td>


    <td>

        <span class="badge badge-substitute">
            ' . $substitute . '
        </span>

    </td>


    <td>

        <span class="badge badge-medical">
            ' . $medical . '
        </span>

    </td>


    <td>

        <div class="attendance-rate">

            <div class="rate-text">
                ' . $percentage . '%
            </div>

            <div class="progress">

                <div
                    class="progress-bar ' . $progressClass . '"
                    style="width:' . min(100, max(0, $percentage)) . '%">
                </div>

            </div>

        </div>

    </td>

</tr>

';

                $i++;
            }


            /*
        |--------------------------------------------------------------------------
        | TOTAL ROW
        |--------------------------------------------------------------------------
        */

            $html .= '

<tr class="total-row">

    <td colspan="2">
        TOTAL
    </td>

    <td>
        ' . $totalMeetings . '
    </td>

    <td>

        <span class="badge badge-present">
            ' . $totalPresent . '
        </span>

    </td>

    <td>

        <span class="badge badge-absent">
            ' . $totalAbsent . '
        </span>

    </td>

    <td>

        <span class="badge badge-late">
            ' . $totalLate . '
        </span>

    </td>

    <td>

        <span class="badge badge-substitute">
            ' . $totalSubstitute . '
        </span>

    </td>

    <td>

        <span class="badge badge-medical">
            ' . $totalMedical . '
        </span>

    </td>

    <td>
        ' . $overallPercentage . '%
    </td>

</tr>

';
        } else {

            /*
        |--------------------------------------------------------------------------
        | NO DATA
        |--------------------------------------------------------------------------
        */

            $html .= '

<tr>

    <td
        colspan="9"
        class="no-data">

        📅 No attendance records found
        for the selected filters.

    </td>

</tr>

';
        }


        $html .= '

</tbody>

</table>

</div>

';


        /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="report-footer">

    <div>
        Admin Attendance Management
    </div>

    <div>
        Generated on:
        ' . date('d M Y h:i A') . '
    </div>

</div>

';


        /*
    |--------------------------------------------------------------------------
    | CLOSE HTML
    |--------------------------------------------------------------------------
    */

        $html .= '</div>';

        $html .= '</body>';

        $html .= '</html>';


        /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

        return $html;
    }

    public function attendance_view()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | ADMIN ACCESS
    |--------------------------------------------------------------------------
    |
    | No logged-in member/chapter restriction.
    | Add your admin-role/session validation here if required.
    |
    */

        /*
    |--------------------------------------------------------------------------
    | GET FILTERS
    |--------------------------------------------------------------------------
    */

        $search = trim(
            (string) $this->request->getGet('search')
        );

        $memberCode = trim(
            (string) $this->request->getGet('to_user')
        );

        $status = trim(
            (string) $this->request->getGet('status')
        );

        /*
    |--------------------------------------------------------------------------
    | FROM / TO DATE
    |--------------------------------------------------------------------------
    */

        $fromDate = trim(
            (string) $this->request->getGet('from_date')
        );

        $toDate = trim(
            (string) $this->request->getGet('to_date')
        );

        /*
    |--------------------------------------------------------------------------
    | MONTH FILTER
    |--------------------------------------------------------------------------
    */

        $month = trim(
            (string) $this->request->getGet('month')
        );

        /*
    |--------------------------------------------------------------------------
    | VALIDATE DATE FILTERS
    |--------------------------------------------------------------------------
    */

        if (
            $fromDate !== '' &&
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)
        ) {
            $fromDate = '';
        }

        if (
            $toDate !== '' &&
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)
        ) {
            $toDate = '';
        }

        if (
            $month !== '' &&
            !preg_match('/^\d{4}-\d{2}$/', $month)
        ) {
            $month = '';
        }

        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('event_attendance ea');

        $builder->select('
        ea.event_a_id,
        ea.event_code,
        ea.chapter_code,
        ea.user_code,
        ea.status,
        ea.note,
        ea.updated_on,

        e.title AS event_title,
        e.date AS event_date,

        c.chapter_name,

        u.name,
        u.email,
        u.phone,
        u.profile_photo,

        cm.role AS member_role
    ');

        /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    |
    | Only completed/required events where status = 3
    |
    */

        $builder->join(
            'events e',
            "ea.event_code = e.event_code AND e.status = '3'",
            'inner'
        );

        /*
    |--------------------------------------------------------------------------
    | CHAPTER
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'chapters c',
            'ea.chapter_code = c.chapter_code',
            'left'
        );

        /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'users u',
            'ea.user_code = u.user_code',
            'inner'
        );

        /*
    |--------------------------------------------------------------------------
    | CHAPTER MEMBER
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'chapter_members cm',
            'cm.user_code = ea.user_code
         AND cm.chapter_code = ea.chapter_code',
            'inner'
        );

        /*
    |--------------------------------------------------------------------------
    | ONLY ACTIVE USERS
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'u.status',
            '1'
        );

        /*
    |--------------------------------------------------------------------------
    | ONLY ACTIVE CHAPTER MEMBERS
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'cm.members_status',
            '1'
        );

        /*
    |--------------------------------------------------------------------------
    | MEMBER FILTER
    |--------------------------------------------------------------------------
    */

        if ($memberCode !== '') {

            $builder->where(
                'ea.user_code',
                $memberCode
            );
        }

        /*
    |--------------------------------------------------------------------------
    | SEARCH FILTER
    |--------------------------------------------------------------------------
    */

        if ($search !== '') {

            $builder->groupStart();

            $builder->like(
                'u.name',
                $search
            );

            $builder->orLike(
                'u.email',
                $search
            );

            $builder->orLike(
                'e.title',
                $search
            );

            $builder->orLike(
                'c.chapter_name',
                $search
            );

            $builder->orLike(
                'ea.status',
                $search
            );

            $builder->orLike(
                'ea.note',
                $search
            );

            $builder->groupEnd();
        }

        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE STATUS FILTER
    |--------------------------------------------------------------------------
    */

        if ($status !== '') {

            $builder->where(
                'ea.status',
                $status
            );
        }

        /*
    |--------------------------------------------------------------------------
    | FROM DATE
    |--------------------------------------------------------------------------
    */

        if ($fromDate !== '') {

            $builder->where(
                'e.date >=',
                $fromDate . ' 00:00:00'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | TO DATE
    |--------------------------------------------------------------------------
    */

        if ($toDate !== '') {

            $builder->where(
                'e.date <=',
                $toDate . ' 23:59:59'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | MONTH FILTER
    |--------------------------------------------------------------------------
    |
    | Apply month only when From / To dates are empty.
    |
    */

        if (
            $month !== '' &&
            $fromDate === '' &&
            $toDate === ''
        ) {

            $monthStart = $month . '-01';

            $monthEnd = date(
                'Y-m-t',
                strtotime($monthStart)
            );

            $builder->where(
                'e.date >=',
                $monthStart . ' 00:00:00'
            );

            $builder->where(
                'e.date <=',
                $monthEnd . ' 23:59:59'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'e.date',
            'DESC'
        );

        $builder->orderBy(
            'u.name',
            'ASC'
        );

        /*
    |--------------------------------------------------------------------------
    | GET ATTENDANCE
    |--------------------------------------------------------------------------
    */

        $attendance = $builder
            ->get()
            ->getResult();

        /*
    |--------------------------------------------------------------------------
    | FILTER INFORMATION
    |--------------------------------------------------------------------------
    */

        $filters = [

            'search'    => $search,

            'to_user'   => $memberCode,

            'status'    => $status,

            'from_date' => $fromDate,

            'to_date'   => $toDate,

            'month'     => $month

        ];

        /*
    |--------------------------------------------------------------------------
    | RETURN ADMIN VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'admin/attendance_view',
            [
                'attendance' => $attendance,

                'filters' => $filters
            ]
        );
    }
    public function get_filtered_data2()
    {
        $request = \Config\Services::request();
        $db      = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        $search = trim((string) $request->getPost('search'));
        $date   = trim((string) $request->getPost('date'));
        $status = trim((string) $request->getPost('status'));


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('event_attendance ea');

        $builder->select('
        ea.*,

        e.title AS event_title,
        e.date AS event_date,
        e.status AS event_status,

        c.chapter_name,

        u.name,
        u.email,
        u.phone,
        u.profile_photo
    ');


        /*
    |--------------------------------------------------------------------------
    | JOINS
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'events e',
            'ea.event_code = e.event_code',
            'left'
        );

        $builder->join(
            'chapters c',
            'ea.chapter_code = c.chapter_code',
            'left'
        );

        $builder->join(
            'users u',
            'ea.user_code = u.user_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY COMPLETED / ACTIVE EVENTS
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'e.status',
            '3'
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY ACTIVE USERS
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'u.status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if ($search !== '') {

            $builder->groupStart()

                ->like('u.name', $search)
                ->orLike('u.email', $search)
                ->orLike('e.title', $search)
                ->orLike('c.chapter_name', $search)
                ->orLike('ea.status', $search)
                ->orLike('ea.note', $search)

                ->groupEnd();
        }


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE STATUS
    |--------------------------------------------------------------------------
    */

        if ($status !== '') {

            $builder->where(
                'ea.status',
                $status
            );
        }


        /*
    |--------------------------------------------------------------------------
    | DATE
    |--------------------------------------------------------------------------
    */

        if ($date !== '') {

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {

                $builder->where(
                    'e.date >=',
                    $date . ' 00:00:00'
                );

                $builder->where(
                    'e.date <=',
                    $date . ' 23:59:59'
                );
            }
        }


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'e.date',
            'DESC'
        );

        $builder->orderBy(
            'u.name',
            'ASC'
        );


        /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

        $attendance = $builder
            ->get()
            ->getResult();


        /*
    |--------------------------------------------------------------------------
    | NO DATA
    |--------------------------------------------------------------------------
    */

        if (empty($attendance)) {

            return '
            <div class="alert alert-warning text-center">
                No Attendance Found
            </div>
        ';
        }


        /*
    |--------------------------------------------------------------------------
    | OUTPUT
    |--------------------------------------------------------------------------
    */

        $output = '';

        foreach ($attendance as $a) {

            $output .= view(
                'include/admin/card/my_attendance_card',
                [
                    'a' => $a
                ]
            );
        }

        return $output;
    }


    /**
     * =========================================================
     * EXPORT ATTENDANCE EXCEL
     * Present first, Absent second
     * Updated column shows date only
     * =========================================================
     */
    public function exportAttendanceExcel($event_code)
    {
        $db = \Config\Database::connect();

        // =========================================================
        // EVENT DETAILS
        // =========================================================
        $event = $db->table('events e')
            ->select('e.*, c.chapter_name')
            ->join(
                'chapters c',
                'c.chapter_code = e.chapter_code',
                'left'
            )
            ->where('e.event_code', $event_code)
            ->get()
            ->getRow();

        if (!$event) {
            exit('Event not found');
        }

        // =========================================================
        // ALL ACTIVE CHAPTER MEMBERS + ATTENDANCE
        // PRESENT FIRST, ABSENT SECOND
        // =========================================================
        $attendance = $db->table('chapter_members cm')
            ->select('
            u.user_code,
            u.name,
            u.status as user_status,
            ea.event_a_id,
            ea.status as attendance_status,
            ea.note,
            ea.updated_on
        ')
            ->join(
                'users u',
                'u.user_code = cm.user_code',
                'inner'
            )
            ->join(
                'event_attendance ea',
                "ea.user_code = cm.user_code
             AND ea.event_code = " . $db->escape($event_code),
                'left'
            )
            ->where('cm.chapter_code', $event->chapter_code)
            ->where('cm.members_status', '1')
            ->where('u.status', '1')

            // =====================================================
            // PRESENT FIRST
            // ABSENT SECOND
            // OTHER / NOT MARKED AFTER
            // =====================================================
            ->orderBy("
            CASE
                WHEN LOWER(TRIM(ea.status)) = 'present' THEN 1
                WHEN LOWER(TRIM(ea.status)) = 'absent' THEN 2
                WHEN ea.status IS NULL OR TRIM(ea.status) = '' THEN 4
                ELSE 3
            END
        ", '', false)

            ->orderBy('u.name', 'ASC')
            ->get()
            ->getResultArray();

        // =========================================================
        // FILE NAME
        // =========================================================
        $filename = 'Attendance_' . $event_code . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";

        // =========================================================
        // TITLE
        // =========================================================
        echo "
    <tr>
        <th colspan='4' style='font-size:18px;background:#f2f2f2;'>
            Attendance Report
        </th>
    </tr>";

        // =========================================================
        // EVENT INFO
        // =========================================================
        echo "
    <tr>
        <td><strong>Event</strong></td>
        <td>" . htmlspecialchars($event->title ?? '') . "</td>
        <td><strong>Chapter</strong></td>
        <td>" . htmlspecialchars($event->chapter_name ?? '') . "</td>
    </tr>";

        echo "
    <tr>
        <td><strong>Date</strong></td>
        <td colspan='3'>" .
            (
                !empty($event->date)
                ? date('d M Y', strtotime($event->date))
                : ''
            ) .
            "</td>
    </tr>";

        echo "<tr><td colspan='4'></td></tr>";

        // =========================================================
        // TABLE HEADER
        // =========================================================
        echo "
    <tr style='background:#d9d9d9;'>
        <th>Member</th>
        <th>Status</th>
        <th>Note</th>
        <th>Updated</th>
    </tr>";

        // =========================================================
        // DATA
        // =========================================================
        if (!empty($attendance)) {

            foreach ($attendance as $a) {

                // Attendance status
                $attendanceStatus = !empty($a['attendance_status'])
                    ? ucfirst(strtolower(trim($a['attendance_status'])))
                    : 'Not Marked';

                // Note
                $note = !empty($a['note'])
                    ? htmlspecialchars($a['note'])
                    : '';

                // =================================================
                // DATE ONLY
                // =================================================
                $updated = !empty($a['updated_on'])
                    ? date('d M Y', strtotime($a['updated_on']))
                    : '';

                echo "
            <tr>
                <td>" . htmlspecialchars($a['name'] ?? '') . "</td>
                <td>" . htmlspecialchars($attendanceStatus) . "</td>
                <td>{$note}</td>
                <td>{$updated}</td>
            </tr>";
            }
        } else {

            echo "
        <tr>
            <td colspan='4' align='center'>
                No Active Members Found
            </td>
        </tr>";
        }

        echo "</table>";

        exit;
    }


    /**
     * =========================================================
     * EXPORT ATTENDANCE PDF
     * Present first, Absent second
     * Updated column shows date only
     * =========================================================
     */
    public function exportAttendancePdf($event_code)
    {
        ob_clean();
        ob_start();

        $db = \Config\Database::connect();

        // =========================================================
        // EVENT DETAILS
        // =========================================================
        $event = $db->table('events e')
            ->select('e.*, c.chapter_name')
            ->join(
                'chapters c',
                'c.chapter_code = e.chapter_code',
                'left'
            )
            ->where('e.event_code', $event_code)
            ->get()
            ->getRow();

        if (!$event) {
            exit('Event not found');
        }

        // =========================================================
        // ALL ACTIVE CHAPTER MEMBERS + ATTENDANCE
        // PRESENT FIRST
        // ABSENT SECOND
        // =========================================================
        $attendance = $db->table('chapter_members cm')
            ->select('
            u.user_code,
            u.name,
            u.status as user_status,
            ea.event_a_id,
            ea.status as attendance_status,
            ea.note,
            ea.updated_on
        ')
            ->join(
                'users u',
                'u.user_code = cm.user_code',
                'inner'
            )
            ->join(
                'event_attendance ea',
                "ea.user_code = cm.user_code
             AND ea.event_code = " . $db->escape($event_code),
                'left'
            )
            ->where('cm.chapter_code', $event->chapter_code)
            ->where('cm.members_status', '1')
            ->where('u.status', '1')

            // =====================================================
            // ATTENDANCE ORDER
            // PRESENT = 1
            // ABSENT  = 2
            // OTHER   = 3
            // NOT MARKED = 4
            // =====================================================
            ->orderBy("
            CASE
                WHEN LOWER(TRIM(ea.status)) = 'present' THEN 1
                WHEN LOWER(TRIM(ea.status)) = 'absent' THEN 2
                WHEN ea.status IS NULL OR TRIM(ea.status) = '' THEN 4
                ELSE 3
            END
        ", '', false)

            ->orderBy('u.name', 'ASC')
            ->get()
            ->getResult();

        // =========================================================
        // HTML
        // =========================================================
        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">

        <style>

            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 11px;
            }

            h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            .event-info {
                margin-bottom: 15px;
                line-height: 1.6;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                background: #198754;
                color: #ffffff;
                font-weight: bold;
                text-align: left;
            }

            th,
            td {
                border: 1px solid #999999;
                padding: 7px;
            }

            .not-marked {
                color: #dc3545;
            }

        </style>
    </head>

    <body>

        <h2>Attendance Report</h2>

        <div class="event-info">

            <strong>Event:</strong>
            ' . htmlspecialchars($event->title ?? '') . '
            <br>

            <strong>Chapter:</strong>
            ' . htmlspecialchars($event->chapter_name ?? '') . '
            <br>

            <strong>Date:</strong>
            ' .
            (
                !empty($event->date)
                ? date('d M Y', strtotime($event->date))
                : ''
            )
            . '

        </div>

        <table>

            <thead>
                <tr>
                    <th width="40%">Member</th>
                    <th width="20%">Status</th>
                    <th width="25%">Note</th>
                    <th width="15%">Updated</th>
                </tr>
            </thead>

            <tbody>';

        // =========================================================
        // DATA
        // =========================================================
        if (!empty($attendance)) {

            foreach ($attendance as $a) {

                $attendanceStatus = !empty($a->attendance_status)
                    ? ucfirst(strtolower(trim($a->attendance_status)))
                    : 'Not Marked';

                $statusClass = empty($a->attendance_status)
                    ? 'not-marked'
                    : '';

                $note = !empty($a->note)
                    ? htmlspecialchars($a->note)
                    : '';

                // =================================================
                // DATE ONLY
                // =================================================
                $updated = !empty($a->updated_on)
                    ? date('d M Y', strtotime($a->updated_on))
                    : '';

                $html .= '
                <tr>

                    <td>
                        ' . htmlspecialchars($a->name ?? '') . '
                    </td>

                    <td class="' . $statusClass . '">
                        ' . htmlspecialchars($attendanceStatus) . '
                    </td>

                    <td>
                        ' . $note . '
                    </td>

                    <td>
                        ' . $updated . '
                    </td>

                </tr>';
            }
        } else {

            $html .= '
            <tr>
                <td colspan="4" align="center">
                    No Active Members Found
                </td>
            </tr>';
        }

        $html .= '
            </tbody>

        </table>

    </body>
    </html>';

        // =========================================================
        // DOMPDF
        // =========================================================
        $dompdf = new \Dompdf\Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // =========================================================
        // PDF HEADERS
        // =========================================================
        header('Content-Type: application/pdf');

        header(
            'Content-Disposition: inline; filename="Attendance_' .
                $event_code .
                '.pdf"'
        );

        echo $dompdf->output();

        exit;
    }
}

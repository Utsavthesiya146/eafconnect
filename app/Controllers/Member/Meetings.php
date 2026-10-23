<?php

namespace App\Controllers\Member;

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
        // ==========================================
        // MEMBER LOGIN CHECK
        // ==========================================
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }

        $user_code = trim((string) session()->get('user_code'));

        if ($user_code === '') {
            return redirect()->to('/member_login');
        }

        $model       = new \App\Models\EventModel();
        $commonModel = new CommonModel();

        // ==========================================
        // SEARCH
        // ==========================================
        $search = trim((string) $this->request->getGet('search'));

        // ==========================================
        // PER PAGE
        // ==========================================
        $perPage = $this->request->getGet('perPage');

        if ($perPage === null || $perPage === '') {
            $perPage = 10;
        }

        $perPage = (int) $perPage;

        // Allowed pagination values
        $allowedPerPage = [10, 20, 50, 100];

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        // ==========================================
        // EVENTS QUERY
        // ==========================================
        $model->select('
        events.*,
        chapters.chapter_name
    ');

        $model->join(
            'chapters',
            'chapters.chapter_code = events.chapter_code',
            'left'
        );

        // ==========================================
        // ONLY LOGGED-IN MEMBER CHAPTERS
        // ==========================================
        $model->whereIn(
            'events.chapter_code',
            function ($builder) use ($user_code) {

                $builder->select('cm.chapter_code')
                    ->from('chapter_members cm')
                    ->join(
                        'chapters c',
                        'c.chapter_code = cm.chapter_code',
                        'inner'
                    )
                    ->where('cm.user_code', $user_code)
                    ->where('cm.members_status', '1')
                    ->where('c.chapter_status', '1');
            }
        );

        // ==========================================
        // SEARCH FILTER
        // ==========================================
        if ($search !== '') {

            $model->groupStart();

            $model->like('events.title', $search);
            $model->orLike('events.location', $search);
            $model->orLike('events.venue', $search);
            $model->orLike('events.description', $search);
            $model->orLike('chapters.chapter_name', $search);

            $model->groupEnd();
        }

        // ==========================================
        // ORDERING
        // ==========================================
        $model->orderBy('events.date', 'DESC');
        $model->orderBy('events.event_id', 'DESC');

        // ==========================================
        // PAGINATION
        // ==========================================
        $events = $model->paginate($perPage);

        // ==========================================
        // CHAPTER DROPDOWN
        // ==========================================
        $chapterBuilder = $commonModel->db
            ->table('chapters c');

        $chapterBuilder->select('
        c.chapter_code,
        c.chapter_name
    ');

        $chapterBuilder->join(
            'chapter_members cm',
            'cm.chapter_code = c.chapter_code',
            'inner'
        );

        $chapterBuilder->where(
            'c.chapter_status',
            '1'
        );

        $chapterBuilder->where(
            'cm.user_code',
            $user_code
        );

        $chapterBuilder->where(
            'cm.members_status',
            '1'
        );

        $chapterBuilder->orderBy(
            'c.chapter_name',
            'ASC'
        );

        $chapters = $chapterBuilder
            ->get()
            ->getResultArray();

        $user_code = session()->get('user_code');
        $meetingDay = $this->getMemberMeetingDay($user_code);

        $data = [
            'events'   => $events,
            'pager'    => $model->pager,
            'search'   => $search,
            'perPage'  => $perPage,
            'chapters' => $chapters,
            'meetingDay' => $meetingDay,
        ];

        return view(
            'member/meetings',
            $data
        );
    }

    public function updateMeetingStatus()
    {
        $db = \Config\Database::connect();

        // Logged user
        $user_code = session()->get('user_code');

        if (!$user_code) {
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | GET ONLY CHAPTERS WHERE USER IS LEADER
    |--------------------------------------------------------------------------
    */
        $chapters = $db->table('chapters')
            ->where('leader_code', $user_code)
            ->where('chapter_status', '1')
            ->get()
            ->getResultArray();

        if (empty($chapters)) {
            return;
        }

        foreach ($chapters as $chapter) {

            /*
        |--------------------------------------------------------------------------
        | GET EVENTS OF THIS CHAPTER
        |--------------------------------------------------------------------------
        */
            $events = $db->table('events')
                ->where('chapter_code', $chapter['chapter_code'])
                ->get()
                ->getResultArray();

            foreach ($events as $event) {

                // Current time
                $now = strtotime(date('Y-m-d H:i:s'));

                /*
            |--------------------------------------------------------------------------
            | MEETING DATE + TIME
            |--------------------------------------------------------------------------
            */

                $meetingDate = date(
                    'Y-m-d',
                    strtotime($event['date'])
                );

                $meetingDateTime = strtotime(
                    $meetingDate . ' ' . $event['time']
                );

                // End time (+2 hours)
                $meetingEndTime = strtotime(
                    '+2 hours',
                    $meetingDateTime
                );

                /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            |
            | 1 = Scheduled
            | 2 = Ongoing
            | 3 = Completed
            |
            */

                // Before start
                if ($now < $meetingDateTime) {

                    $status = '1';
                }

                // Ongoing
                elseif (
                    $now >= $meetingDateTime &&
                    $now <= $meetingEndTime
                ) {

                    $status = '2';
                }

                // Completed
                else {

                    $status = '3';
                }

                /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

                if ($event['status'] != $status) {

                    $db->table('events')
                        ->where('event_code', $event['event_code'])
                        ->update([

                            'status'     => $status,
                            'updated_on' => date('Y-m-d H:i:s')

                        ]);
                }
            }
        }
    }

    public function AddNewMeetingData()
    {
        helper(['form']);

        $commonModel = new \App\Models\CommonModel();
        $data = $this->request->getPost();
        $user_code = session()->get('user_code');

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
            'user_code'   => $user_code,
            'chapter_code' => $data['chapter_code'] ?? '',
            'title'        => $data['title'] ?? '',
            'description'  => $data['description'] ?? '',
            'date'         => $datetime,

            'mode'  => $data['mode'] ?? '',
            'meeting_link'  => $data['meeting_link'] ?? '',
            'description'  => $data['location'] ?? '',
            'venue'  => $data['venue'] ?? '',

            // REQUIRED FIELDS
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
    public function edit_meeting($event_code)
    {
        $commonModel = new CommonModel();

        $event_code = trim((string) $event_code);

        if ($event_code === '') {
            return $this->response->setBody(
                '<p class="text-center text-danger">Event code is missing!</p>'
            );
        }

        // Safe query
        $qry = "SELECT * FROM events WHERE event_code = "
            . $commonModel->db->escape($event_code);

        $result = $commonModel->exe_query($qry)->getResult();

        if (empty($result)) {
            return $this->response->setBody(
                '<p class="text-center text-danger">Meeting not found!</p>'
            );
        }

        $row = $result[0];

        /*
     * IMPORTANT:
     * Database status is ENUM('1','2','3')
     * Therefore keep it as STRING.
     */
        $currentStatus = (string) ($row->status ?? '1');

        $statusOptions = '
        <option value="1" ' . ($currentStatus === '1' ? 'selected' : '') . '>
            Scheduled
        </option>

        <option value="2" ' . ($currentStatus === '2' ? 'selected' : '') . '>
            Ongoing
        </option>

        <option value="3" ' . ($currentStatus === '3' ? 'selected' : '') . '>
            Completed
        </option>
    ';

        /*
     * Mode ENUM:
     * offline / online / hybrid
     */
        $currentMode = strtolower((string) ($row->mode ?? 'offline'));

        $modeOptions = '
        <option value="offline" ' . ($currentMode === 'offline' ? 'selected' : '') . '>
            Offline
        </option>

        <option value="online" ' . ($currentMode === 'online' ? 'selected' : '') . '>
            Online
        </option>

        <option value="hybrid" ' . ($currentMode === 'hybrid' ? 'selected' : '') . '>
            Hybrid
        </option>
    ';

        $output = '
<div class="modal-content"
    style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">

    <form id="edit_meeting_action" method="post">

        <div class="modal-header"
            style="background:#1b4d8f;color:#fff;">

            <h6 class="modal-title">
                Edit Meeting
            </h6>

            <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal">
            </button>

        </div>

        <div class="modal-body p-3">

            <input type="hidden"
                name="event_code"
                value="' . esc($row->event_code ?? '') . '">

            <div class="row g-2">

                <!-- STATUS -->
                <div class="col-md-12">

                    <label class="fw-bold">
                        Meeting Status
                    </label>

                    <select name="status"
                        id="meeting_status"
                        class="form-control"
                        required>

                        ' . $statusOptions . '

                    </select>

                </div>

                <!-- TITLE -->
                <div class="col-md-12">

                    <label class="fw-bold">
                        Meeting Title
                    </label>

                    <input type="text"
                        name="title"
                        class="form-control"
                        value="' . esc($row->title ?? '') . '"
                        required>

                </div>

                <!-- MODE -->
                <div class="col-md-12">

                    <label class="fw-bold">
                        Meeting Mode
                    </label>

                    <select name="mode"
                        class="form-control"
                        required>

                        ' . $modeOptions . '

                    </select>

                </div>

                <!-- MEETING LINK -->
                <div class="col-md-12">

                    <label class="fw-bold">
                        Meeting Link
                    </label>

                    <input type="text"
                        name="meeting_link"
                        class="form-control"
                        value="' . esc($row->meeting_link ?? '') . '">

                </div>

                <!-- LOCATION -->
                <div class="col-md-6">

                    <label class="fw-bold">
                        Location
                    </label>

                    <input type="text"
                        name="location"
                        class="form-control"
                        value="' . esc($row->location ?? '') . '">

                </div>

                <!-- VENUE -->
                <div class="col-md-6">

                    <label class="fw-bold">
                        Venue
                    </label>

                    <input type="text"
                        name="venue"
                        class="form-control"
                        value="' . esc($row->venue ?? '') . '">

                </div>

                <!-- DESCRIPTION -->
                <div class="col-md-12">

                    <label class="fw-bold">
                        Agenda / Notes
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4">' . esc($row->description ?? '') . '</textarea>

                </div>

            </div>

        </div>

        <div class="modal-footer border-0">

            <button type="button"
                class="btn btn-outline-secondary btn-sm"
                data-bs-dismiss="modal">
                Cancel
            </button>

            <button type="submit"
                class="btn btn-sm text-white"
                style="background:#1b4d8f;">

                <i class="fa fa-save"></i>
                Update

            </button>

        </div>

    </form>

</div>';

        return $this->response->setBody($output);
    }

    public function edit_meeting_action()
    {
        $event_code = trim((string) $this->request->getPost('event_code'));
        $status     = trim((string) $this->request->getPost('status'));

        if ($event_code === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event code is missing.'
            ]);
        }

        /*
     * IMPORTANT:
     * events.status is ENUM('1','2','3')
     */
        if (!in_array($status, ['1', '2', '3'], true)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid meeting status.'
            ]);
        }

        $title = trim((string) $this->request->getPost('title'));

        if ($title === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Meeting title is required.'
            ]);
        }

        $mode = trim((string) $this->request->getPost('mode'));

        /*
     * Validate mode because database has ENUM
     */
        if (!in_array($mode, ['offline', 'online', 'hybrid'], true)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid meeting mode.'
            ]);
        }

        $data = [
            'status'       => $status,
            'title'        => $title,
            'description'  => trim((string) $this->request->getPost('description')),
            'mode'         => $mode,
            'meeting_link' => trim((string) $this->request->getPost('meeting_link')),
            'location'     => trim((string) $this->request->getPost('location')),
            'venue'        => trim((string) $this->request->getPost('venue')),
        ];

        $db = \Config\Database::connect();

        /*
     * Check meeting exists
     */
        $existing = $db->table('events')
            ->select('event_id,event_code,status')
            ->where('event_code', $event_code)
            ->get()
            ->getRow();

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Meeting not found.'
            ]);
        }

        /*
     * Update
     */
        $builder = $db->table('events');

        $builder->where('event_code', $event_code);

        $builder->update($data);

        /*
     * Get database error if any
     */
        $dbError = $db->error();

        if (!empty($dbError['code'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Database error: ' . $dbError['message']
            ]);
        }

        /*
     * IMPORTANT:
     * Verify the status directly from database.
     */
        $check = $db->table('events')
            ->select('status')
            ->where('event_code', $event_code)
            ->get()
            ->getRow();

        if (!$check) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unable to verify meeting.'
            ]);
        }

        $savedStatus = (string) $check->status;

        if ($savedStatus !== $status) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Status was not saved.',
                'debug' => [
                    'sent_status'  => $status,
                    'saved_status' => $savedStatus
                ]
            ]);
        }

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Meeting updated successfully!',
            'saved_status' => $savedStatus
        ]);
    }
    public function send_mail($event_code)
    {
        $commonModel = new \App\Models\CommonModel();

        // ✅ Event + Chapter Join
        $event = $commonModel->db->table('events e')
            ->select('e.*, c.chapter_name')
            ->join('chapters c', 'c.chapter_code = e.chapter_code', 'left')
            ->where('e.event_code', $event_code)
            ->get()
            ->getRow();

        if (!$event) {
            return $this->response->setBody(
                '<p class="text-center text-danger">Event not found!</p>'
            );
        }

        // ✅ Chapter Members (emails)
        $members = $commonModel->db->table('chapter_members')
            ->where('chapter_code', $event->chapter_code)
            ->where('members_status', '1')
            ->get()
            ->getResult();

        $emails = array_column($members, 'email');

        $output = '
<div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 4px 12px rgba(0,0,0,0.1);font-size:13px;">
    <form id="send_mail_action" method="post">
        <div class="modal-header" style="background:#1b4d8f;color:#fff;">
            <h6 class="modal-title" style="color:#ffffff;">Send Meeting Mail</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-3">
            <input type="hidden" name="event_code" value="' . esc($event->event_code) . '">
            <input type="hidden" name="emails" value="' . esc(implode(',', $emails)) . '">
            <!-- SUBJECT -->
            <div class="mb-2">
                <label class="fw-bold">Subject</label>
                <input type="text" name="subject" class="form-control"
                    value="Meeting: ' . esc($event->title) . ' - ' . esc($event->chapter_name) . '">
            </div>
            <!-- MESSAGE -->
            <div class="mb-2">
                <label class="fw-bold">Message</label>
                <textarea name="message" class="form-control" rows="4">Dear Member,
You are invited for a chapter meeting.
Chapter: ' . esc($event->chapter_name) . '
Title: ' . esc($event->title) . '
Date: ' . esc($event->date) . '
Time: ' . esc($event->time) . '
Location: ' . esc($event->location) . '
Venue: ' . esc($event->venue) . '
Mode: ' . esc($event->mode) . '
Meeting Link: ' . esc($event->meeting_link) . '

Regards,
Team</textarea>
            </div>
            <!-- EVENT PREVIEW -->
            <div class="border rounded p-2 bg-light">
                <small>
                    <b>Chapter:</b> ' . esc($event->chapter_name) . '<br>
                    <b>Date:</b> ' . esc($event->date) . '<br>
                    <b>Time:</b> ' . esc($event->time) . '<br>
                    <b>Location:</b> ' . esc($event->location) . '<br>
                    <b>Venue:</b> ' . esc($event->venue) . '
                </small>
            </div>
        </div>

        <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm text-white" style="background:#1b4d8f;">
                Send Mail
            </button>
        </div>
    </form>
</div>';

        return $this->response->setBody($output);
    }

    public function send_mail_action()
    {
        $emails  = explode(',', $this->request->getPost('emails'));
        $subject = $this->request->getPost('subject');
        $event_code = $this->request->getPost('event_code');
        $commonModel = new \App\Models\CommonModel();
        $event = $commonModel->db->table('events e')
            ->select('e.*, c.chapter_name')
            ->join('chapters c', 'c.chapter_code = e.chapter_code', 'left')
            ->where('e.event_code', $event_code)
            ->get()
            ->getRow();

        if (!$event) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Event not found'
            ]);
        }

        $emailService = \Config\Services::email();

        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => 'smtp.hostinger.com',
            'SMTPUser'   => 'mail@eafconnect.com',
            'SMTPPass'   => 'mail@WHB26',
            'SMTPPort'   => 465,
            'SMTPCrypto' => 'ssl', // ✅ FIXED
            'mailType'   => 'html',
            'charset'    => 'utf-8',
            'newline'    => "\r\n"
        ];

        $emailService->initialize($config);

        $success = 0;
        $failed  = 0;

        foreach ($emails as $email) {

            $email = trim($email);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failed++;
                continue;
            }

            $emailService->clear(true);

            $emailService->setFrom('mail@eafconnect.com', 'Web Hosting Baba'); // ✅ IMPORTANT
            $emailService->setTo($email);
            $emailService->setSubject($subject);

            $message = view('mail/notification', [
                'name'  => $email,
                'event' => $event
            ]);

            $emailService->setMessage($message);

            if ($emailService->send()) {
                $success++;
            } else {
                $failed++;
                log_message('error', $emailService->printDebugger(['headers']));
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => "Mail sent: $success, Failed: $failed",
            'success' => $success,
            'failed'  => $failed
        ]);
    }

    public function get_filtered_data()
    {
        $request = \Config\Services::request();
        $user_code = session()->get('user_code');

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
        /* ========= USER FILTER ========= */
        $model->where('user_code', $user_code);

        $model->orderBy('event_id', 'DESC');

        $users = $model->findAll();

        if (empty($users)) {
            return '<div class="alert alert-warning text-center">No Event Found</div>';
        }

        $output = '';
        $i = 1;

        foreach ($users as $row) {
            $output .= view('include/member/card/event_card', [
                'row' => $row,
                'i'   => $i++
            ]);
        }

        return $output;
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
    | EVENT STATUS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | status = 3 means meeting/event is completed/closed.
    | Attendance MUST NOT be editable.
    |
    */

        $eventStatus = (string) ($event->status ?? '');

        $isMeetingClosed = ($eventStatus === '3');

        /*
    |--------------------------------------------------------------------------
    | EVENT DATE
    |--------------------------------------------------------------------------
    */

        $today = date('Y-m-d');

        $eventDate = !empty($event->date)
            ? date('Y-m-d', strtotime($event->date))
            : '';

        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE FROZEN
    |--------------------------------------------------------------------------
    |
    | Attendance is frozen when:
    |
    | 1. Meeting status = 3
    |
    | OR
    |
    | 2. Your separate freeze field is enabled, if you have one.
    |
    */

        $isAttendanceFrozen = $isMeetingClosed;

        /*
    |--------------------------------------------------------------------------
    | ALLOW ATTENDANCE MARKING
    |--------------------------------------------------------------------------
    |
    | Attendance can ONLY be edited:
    |
    | - On event date
    | - Meeting status is NOT 3
    | - Attendance is NOT frozen
    |
    */

        $allowMark = (
            $today === $eventDate &&
            !$isMeetingClosed &&
            !$isAttendanceFrozen
        );

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
                'cm.chapter_code',
                $chapter_code
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
            ->where(
                'cm.chapter_code',
                $chapter_code
            )
            ->where(
                'u.user_code !=',
                ''
            )
            ->where(
                'u.user_code !=',
                '0'
            )
            ->where(
                'u.status',
                '1'
            )
            ->orderBy(
                'u.name',
                'ASC'
            )
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
            ->where(
                'ea.event_code',
                $event_code
            )
            ->where(
                'u.status',
                '1'
            )
            ->orderBy(
                'u.name',
                'ASC'
            )
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

            'isAttendanceFrozen' => $isAttendanceFrozen,

            'isMeetingClosed' => $isMeetingClosed,

            'eventStatus' => $eventStatus,

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

            ->where(
                'ea.user_code',
                $user_code
            )

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
        $user_code = session()->get('user_code');
        $meetingDay = $this->getMemberMeetingDay($user_code);
        $data = [
            'attendance' => $attendance,
            'meetingDay' => $meetingDay,
        ];


        /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'member/my_attendance',
            $data
        );
    }
    public function chapter_attendance()
    {
        $db = \Config\Database::connect();
        $userCode = session()->get('user_code');

        if (empty($userCode)) {
            return redirect()->to('/member_login');
        }
        $perPage = 20;

        $page = (int) ($this->request->getGet('page') ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;

        $chapterMembers = $db->table('chapter_members cm')
            ->select('cm.chapter_code')
            ->where('cm.user_code', $userCode)
            ->where('cm.members_status', '1')
            ->get()
            ->getResultArray();

        if (empty($chapterMembers)) {

            return view('member/chapter_attendance', [
                'attendance'   => [],
                'member'       => [],
                'totalRecords' => 0,
                'totalPages'   => 1,
                'currentPage'  => $page,
                'perPage'      => $perPage
            ]);
        }


        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
        );


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

        u.name,
        u.email,
        u.phone,
        u.profile_photo,

        cm.role AS member_role
    ');


        /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

        $builder->join(
            'events e',
            'ea.event_code = e.event_code',
            'left'
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
    | ONLY LOGGED-IN USER'S CHAPTER(S)
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY EVENT STATUS = 3
    |--------------------------------------------------------------------------
    |
    | Same condition as my_attendance()
    |
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
    | TOTAL RECORDS
    |--------------------------------------------------------------------------
    */

        $countBuilder = clone $builder;

        $totalRecords = $countBuilder->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | GET PAGINATED ATTENDANCE
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

        $attendance = $builder
            ->limit(
                $perPage,
                $offset
            )
            ->get()
            ->getResult();


        /*
    |--------------------------------------------------------------------------
    | TOTAL PAGES
    |--------------------------------------------------------------------------
    */

        $totalPages = $totalRecords > 0
            ? (int) ceil($totalRecords / $perPage)
            : 1;


        /*
    |--------------------------------------------------------------------------
    | GET CHAPTER MEMBERS
    |--------------------------------------------------------------------------
    */

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

        $memberBuilder->whereIn(
            'cm.chapter_code',
            $chapterCodes
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
            'u.name',
            'ASC'
        );

        $member = $memberBuilder
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | VIEW DATA
    |--------------------------------------------------------------------------
    */

        $data = [
            'member'       => $member,
            'attendance'   => $attendance,
            'totalRecords' => $totalRecords,
            'totalPages'   => $totalPages,
            'currentPage'  => $page,
            'perPage'      => $perPage,
        ];


        /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'member/chapter_attendance',
            $data
        );
    }
    public function ajax_save_attendance()
    {
        $commonModel = new \App\Models\CommonModel();
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | GET POST DATA
    |--------------------------------------------------------------------------
    */

        $event_code = trim(
            (string)$this->request->getPost('event_code')
        );

        $chapter_code = trim(
            (string)$this->request->getPost('chapter_code')
        );

        $user_code = trim(
            (string)$this->request->getPost('user_code')
        );

        $status = strtolower(
            trim(
                (string)$this->request->getPost('status')
            )
        );

        $note = trim(
            (string)$this->request->getPost('note')
        );


        /*
    |--------------------------------------------------------------------------
    | VALIDATE REQUIRED FIELDS
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
    | GET EVENT
    |--------------------------------------------------------------------------
    */

        $event = $db->table('events')
            ->select('
            event_code,
            chapter_code,
            date,
            status
        ')
            ->where(
                'event_code',
                $event_code
            )
            ->limit(1)
            ->get()
            ->getRow();


        if (!$event) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event not found'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | VERIFY CHAPTER
    |--------------------------------------------------------------------------
    */

        if (
            (string)$event->chapter_code !==
            (string)$chapter_code
        ) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid chapter for this event'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | STATUS 3 = COMPLETED / FROZEN
    |--------------------------------------------------------------------------
    */

        if (
            (string)$event->status === '3'
        ) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attendance is frozen. It cannot be edited.'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | CHECK EVENT DATE
    |--------------------------------------------------------------------------
    */

        $today = date('Y-m-d');

        $eventDate = date(
            'Y-m-d',
            strtotime($event->date)
        );


        if (
            $today !== $eventDate
        ) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attendance can only be marked on the event date.'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | VALID ATTENDANCE STATUS
    |--------------------------------------------------------------------------
    */

        $allowed_status = [
            'present',
            'absent',
            'medical',
            'substitute'
        ];


        if (
            !in_array(
                $status,
                $allowed_status,
                true
            )
        ) {

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
            ->where(
                'event_code',
                $event_code
            )
            ->where(
                'user_code',
                $user_code
            )
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
                ->where(
                    'event_code',
                    $event_code
                )
                ->where(
                    'user_code',
                    $user_code
                )
                ->update([

                    'status' => $status,

                    'note' => $note,

                    'updated_on' => date(
                        'Y-m-d H:i:s'
                    )

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
    | GENERATE ATTENDANCE CODE
    |--------------------------------------------------------------------------
    */

        $event_a_code = $commonModel->get_serial3(
            'event_attendance',
            'EAT',
            'event_a_code'
        );


        /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

        $insert = [

            'event_a_code' => $event_a_code,

            'event_code' => $event_code,

            'chapter_code' => $chapter_code,

            'user_code' => $user_code,

            'status' => $status,

            'note' => $note,

            'updated_on' => date(
                'Y-m-d H:i:s'
            )

        ];


        $inserted = $db->table(
            'event_attendance'
        )->insert($insert);


        if (!$inserted) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unable to save attendance'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

        return $this->response->setJSON([
            'status'  => 'success',
            'action'  => 'insert',
            'message' => 'Attendance saved successfully'
        ]);
    }



    public function get_filtered_data2()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $user_code = session()->get('user_code');

        $search = $request->getPost('search');
        $date   = $request->getPost('date');
        $status = $request->getPost('status');

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
        c.chapter_name
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


        /*
    |--------------------------------------------------------------------------
    | ONLY EVENTS WITH STATUS = 3
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'e.status',
            '3'
        );


        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if (!empty($search)) {

            $builder->groupStart()
                ->like('e.title', $search)
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

        if (!empty($status)) {

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

        if (!empty($date)) {

            $builder->where(
                'e.date >=',
                $date . ' 00:00:00'
            );

            $builder->where(
                'e.date <=',
                $date . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | LOGGED-IN USER
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'ea.user_code',
            $user_code
        );


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'e.date',
            'DESC'
        );


        /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

        $users = $builder
            ->get()
            ->getResult();


        /*
    |--------------------------------------------------------------------------
    | NO DATA
    |--------------------------------------------------------------------------
    */

        if (empty($users)) {

            return '<div class="alert alert-warning text-center">
                    No Attendance Found
                </div>';
        }


        /*
    |--------------------------------------------------------------------------
    | OUTPUT
    |--------------------------------------------------------------------------
    */

        $output = '';

        foreach ($users as $a) {

            $output .= view(
                'include/member/card/my_attendance_card',
                [
                    'a' => $a
                ]
            );
        }


        return $output;
    }

    public function get_filtered_data3()
    {
        $request = \Config\Services::request();
        $db      = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | LOGGED-IN USER
    |--------------------------------------------------------------------------
    */

        $userCode = trim((string) session()->get('user_code'));

        if (empty($userCode)) {

            return '
            <tr>
                <td colspan="5" class="text-center">
                    <div class="alert alert-danger mb-0">
                        Please login again.
                    </div>
                </td>
            </tr>
        ';
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER VALUES
    |--------------------------------------------------------------------------
    */

        $search     = trim((string) $request->getPost('search'));
        $memberCode = trim((string) $request->getPost('to_user'));
        $status     = trim((string) $request->getPost('status'));

        $fromDate   = trim((string) $request->getPost('from_date'));
        $toDate     = trim((string) $request->getPost('to_date'));

        $month      = trim((string) $request->getPost('month'));


        /*
    |--------------------------------------------------------------------------
    | GET LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $chapterMembers = $db
            ->table('chapter_members cm')
            ->select('cm.chapter_code')
            ->where('cm.user_code', $userCode)
            ->where('cm.members_status', '1')
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | NO CHAPTER
    |--------------------------------------------------------------------------
    */

        if (empty($chapterMembers)) {

            return '
            <tr>
                <td colspan="5" class="text-center">
                    <div class="alert alert-warning mb-0">
                        No chapter found.
                    </div>
                </td>
            </tr>
        ';
        }


        /*
    |--------------------------------------------------------------------------
    | CHAPTER CODES
    |--------------------------------------------------------------------------
    */

        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
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

        u.name,
        u.email,
        u.phone,
        u.profile_photo,

        cm.role AS member_role
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

        $builder->join(
            'chapter_members cm',
            'cm.user_code = ea.user_code
         AND cm.chapter_code = ea.chapter_code',
            'inner'
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY SAME CHAPTERS
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY EVENTS WITH STATUS = 3
    |--------------------------------------------------------------------------
    |
    | Same condition as my_attendance()
    |
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
                    'e.title',
                    $search
                )

                ->orLike(
                    'c.chapter_name',
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
    | FROM DATE FILTER
    |--------------------------------------------------------------------------
    */

        if ($fromDate !== '') {

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {

                $builder->where(
                    'e.date >=',
                    $fromDate . ' 00:00:00'
                );
            }
        }


        /*
    |--------------------------------------------------------------------------
    | TO DATE FILTER
    |--------------------------------------------------------------------------
    */

        if ($toDate !== '') {

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {

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
    */

        if ($month !== '') {

            if (preg_match('/^\d{4}-\d{2}$/', $month)) {

                $monthStart = $month . '-01';

                $monthEnd = date(
                    'Y-m-t',
                    strtotime($monthStart)
                );


                /*
            |------------------------------------------------------------------
            | START OF MONTH
            |------------------------------------------------------------------
            */

                $builder->where(
                    'e.date >=',
                    $monthStart . ' 00:00:00'
                );


                /*
            |------------------------------------------------------------------
            | END OF MONTH
            |------------------------------------------------------------------
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
            'u.name',
            'ASC'
        );


        /*
    |--------------------------------------------------------------------------
    | EXECUTE
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
                'include/member/card/chapter_attendance_card',
                [
                    'a'        => $a,
                    'serialNo' => $serialNo
                ]
            );

            $serialNo++;
        }


        return $output;
    }
    public function attendance_pdf()
    {
        $request = \Config\Services::request();
        $db      = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | LOGGED-IN USER
    |--------------------------------------------------------------------------
    */

        $userCode = trim((string) session()->get('user_code'));

        if (empty($userCode)) {
            return redirect()->to('/member_login');
        }


        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        $search     = trim((string) $request->getGet('search'));
        $memberCode = trim((string) $request->getGet('to_user'));
        $status     = trim((string) $request->getGet('status'));

        $fromDate   = trim((string) $request->getGet('from_date'));
        $toDate     = trim((string) $request->getGet('to_date'));

        $month      = trim((string) $request->getGet('month'));


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
    | GET LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $chapterMembers = $db
            ->table('chapter_members cm')
            ->select('cm.chapter_code')
            ->where('cm.user_code', $userCode)
            ->where('cm.members_status', '1')
            ->get()
            ->getResultArray();


        if (empty($chapterMembers)) {

            return redirect()
                ->back()
                ->with('error', 'No chapter found.');
        }


        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
        );


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

        cm.role AS member_role
    ');


        /*
    |--------------------------------------------------------------------------
    | JOINS
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


        /*
    |--------------------------------------------------------------------------
    | CHAPTER FILTER
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
        );


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
    | Month is applied only when From / To Date are not selected.
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

        if ($fromDate !== '' && $toDate !== '') {

            $dateDisplay =
                date('d-m-Y', strtotime($fromDate))
                . ' to '
                . date('d-m-Y', strtotime($toDate));
        } elseif ($fromDate !== '') {

            $dateDisplay =
                'From '
                . date('d-m-Y', strtotime($fromDate));
        } elseif ($toDate !== '') {

            $dateDisplay =
                'Up to '
                . date('d-m-Y', strtotime($toDate));
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
                ($presentCount / $totalRecords) * 100,
                1
            );
        }


        /*
    |--------------------------------------------------------------------------
    | REPORT TITLE
    |--------------------------------------------------------------------------
    */

        $reportTitle = 'Attendance Report';


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


/* --------------------------------------------------
   REPORT
-------------------------------------------------- */

.report {
    width: 100%;
    background: #ffffff;
    border-radius: 12px;
    padding: 22px;
}


/* --------------------------------------------------
   HEADER
-------------------------------------------------- */

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


/* --------------------------------------------------
   FILTER SUMMARY
-------------------------------------------------- */

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


/* --------------------------------------------------
   SUMMARY CARDS
-------------------------------------------------- */

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


/* --------------------------------------------------
   TABLE
-------------------------------------------------- */

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


/* --------------------------------------------------
   MEMBER
-------------------------------------------------- */

.member-name {
    font-weight: bold;
    color: #111827;
}

.member-email {
    margin-top: 2px;
    font-size: 8px;
    color: #9ca3af;
}


/* --------------------------------------------------
   STATUS
-------------------------------------------------- */

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


/* --------------------------------------------------
   FOOTER
-------------------------------------------------- */

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


/* --------------------------------------------------
   PRINT / PDF
-------------------------------------------------- */

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
                Member attendance overview and meeting statistics
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
                'Search: ' . $e($search);
        }

        if ($memberCode !== '') {

            $activeFilters[] =
                'Member: ' . $e($memberCode);
        }

        if ($status !== '') {

            $activeFilters[] =
                'Status: ' . ucfirst($e($status));
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
                    strtotime($month . '-01')
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
            |--------------------------------------------------------------
            | STATUS CLASS
            |--------------------------------------------------------------
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
            |--------------------------------------------------------------
            | MEMBER DATA
            |--------------------------------------------------------------
            */

                $memberName = trim(
                    (string) ($row['name'] ?? '')
                );

                $memberEmail = trim(
                    (string) ($row['email'] ?? '')
                );

                $chapterName = trim(
                    (string) ($row['chapter_name'] ?? '')
                );

                $eventTitle = trim(
                    (string) ($row['event_title'] ?? '')
                );

                $eventDate = $row['event_date'] ?? '';

                $note = trim(
                    (string) ($row['note'] ?? '')
                );


                /*
            |--------------------------------------------------------------
            | DATE FORMAT
            |--------------------------------------------------------------
            */

                $formattedDate = '';

                if (!empty($eventDate)) {

                    $timestamp = strtotime($eventDate);

                    if ($timestamp !== false) {

                        $formattedDate =
                            date(
                                'd M Y',
                                $timestamp
                            );
                    }
                }


                /*
            |--------------------------------------------------------------
            | HTML ROW
            |--------------------------------------------------------------
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

        <span class="status-badge ' . $statusClass . '">

            ' . strtoupper(
                    $e($rowStatus)
                ) . '

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
            'attendance_report_' .
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
    | LOGIN
    |--------------------------------------------------------------------------
    */

        $userCode = trim(
            (string) session()->get('user_code')
        );

        if (empty($userCode)) {
            return redirect()->to('/member_login');
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
    | GET LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $chapterMembers = $db
            ->table('chapter_members cm')
            ->select('cm.chapter_code')
            ->where(
                'cm.user_code',
                $userCode
            )
            ->where(
                'cm.members_status',
                '1'
            )
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | NO ACTIVE CHAPTER
    |--------------------------------------------------------------------------
    */

        if (empty($chapterMembers)) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'No active chapter found.'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | CHAPTER CODES
    |--------------------------------------------------------------------------
    */

        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
        );


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table(
            'event_attendance ea'
        );


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

        cm.role AS member_role
    ');


        /*
    |--------------------------------------------------------------------------
    | JOINS
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


        /*
    |--------------------------------------------------------------------------
    | SAME CHAPTER ONLY
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
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
    | ACTIVE MEMBERS ONLY
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
        $output .= 'Chapter Attendance Report';
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


        $output .= '<tr>';

        $output .= '<td colspan="8">';

        $output .= '<strong>Date Filter:</strong> ';

        $output .= htmlspecialchars(
            $filterText,
            ENT_QUOTES,
            'UTF-8'
        );

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
            'chapter_attendance_' .
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

    public function attendance_view()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */

        $userCode = trim(
            (string) session()->get('user_code')
        );

        if (empty($userCode)) {

            return redirect()->to('/member_login');
        }


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
    | FROM / TO DATE FILTER
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
    | GET LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $chapterMembers = $db
            ->table('chapter_members cm')
            ->select('cm.chapter_code')
            ->where(
                'cm.user_code',
                $userCode
            )
            ->where(
                'cm.members_status',
                '1'
            )
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | NO CHAPTER
    |--------------------------------------------------------------------------
    */

        if (empty($chapterMembers)) {

            return view(
                'member/attendance_view',
                [
                    'attendance' => [],

                    'filters' => [

                        'search'     => $search,

                        'to_user'    => $memberCode,

                        'status'     => $status,

                        'from_date'  => $fromDate,

                        'to_date'    => $toDate,

                        'month'      => $month
                    ]
                ]
            );
        }


        /*
    |--------------------------------------------------------------------------
    | CHAPTER CODES
    |--------------------------------------------------------------------------
    */

        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
        );


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table(
            'event_attendance ea'
        );


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
    | ONLY LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY ACTIVE USER
    |--------------------------------------------------------------------------
    */

        $builder->where(
            'u.status',
            '1'
        );


        /*
    |--------------------------------------------------------------------------
    | ONLY ACTIVE CHAPTER MEMBER
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
    | Month is applied only when From / To dates are empty.
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


            /*
        |----------------------------------------------------------------------
        | MONTH START
        |----------------------------------------------------------------------
        */

            $builder->where(
                'e.date >=',
                $monthStart . ' 00:00:00'
            );


            /*
        |----------------------------------------------------------------------
        | MONTH END
        |----------------------------------------------------------------------
        */

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
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'member/attendance_view',
            [
                'attendance' => $attendance,

                'filters' => $filters
            ]
        );
    }

    public function attendance_report()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

        $userCode = trim(
            (string) session()->get('user_code')
        );

        if (empty($userCode)) {
            return redirect()->to('/member_login');
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


        /*
    |--------------------------------------------------------------------------
    | FROM DATE
    |--------------------------------------------------------------------------
    */

        $fromDate = trim(
            (string) $this->request->getGet('from_date')
        );


        /*
    |--------------------------------------------------------------------------
    | TO DATE
    |--------------------------------------------------------------------------
    */

        $toDate = trim(
            (string) $this->request->getGet('to_date')
        );


        /*
    |--------------------------------------------------------------------------
    | MONTH
    |--------------------------------------------------------------------------
    */

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
    | GET LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $chapterMembers = $db
            ->table('chapter_members cm')
            ->select('cm.chapter_code')
            ->where(
                'cm.user_code',
                $userCode
            )
            ->where(
                'cm.members_status',
                '1'
            )
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | NO CHAPTER
    |--------------------------------------------------------------------------
    */

        if (empty($chapterMembers)) {

            return $this->attendanceReportHtml(
                [],
                $search,
                $memberCode,
                $status,
                $fromDate,
                $toDate,
                $month
            );
        }


        /*
    |--------------------------------------------------------------------------
    | CHAPTER CODES
    |--------------------------------------------------------------------------
    */

        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
        );


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table(
            'event_attendance ea'
        );


        $builder->select('
        ea.user_code,
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
    |
    | IMPORTANT:
    | Only events where e.status = '3'
    |
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
    | SAME CHAPTER ONLY
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
        );


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
    | Apply Month only when From / To are not selected.
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


            /*
        |----------------------------------------------------------------------
        | MONTH START
        |----------------------------------------------------------------------
        */

            $builder->where(
                'e.date >=',
                $monthStart . ' 00:00:00'
            );

            if ($status !== '') {
                $builder->where('ea.status', $status);
            }
            /*
        |----------------------------------------------------------------------
        | MONTH END
        |----------------------------------------------------------------------
        */

            $builder->where(
                'e.date <=',
                $monthEnd . ' 23:59:59'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | GROUP BY MEMBER
    |--------------------------------------------------------------------------
    */

        $builder->groupBy([
            'ea.user_code',
            'u.name',
            'u.email'
        ]);


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

        $builder->orderBy(
            'u.name',
            'ASC'
        );


        /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

        $report = $builder
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | RETURN HTML
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

        if (
            !empty($fromDate) &&
            !empty($toDate)
        ) {

            $reportPeriod =
                date(
                    'd M Y',
                    strtotime($fromDate)
                )
                .
                ' - '
                .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        } elseif (!empty($fromDate)) {

            $reportPeriod =
                'From ' .
                date(
                    'd M Y',
                    strtotime($fromDate)
                );
        } elseif (!empty($toDate)) {

            $reportPeriod =
                'Up to ' .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        } elseif (!empty($month)) {

            $reportPeriod = date(
                'F Y',
                strtotime($month . '-01')
            );
        } else {

            $reportPeriod = 'Overall Attendance';
        }


        /*
    |--------------------------------------------------------------------------
    | TOTALS
    |--------------------------------------------------------------------------
    */

        $totalMembers     = count($report);
        $totalMeetings    = 0;
        $totalPresent     = 0;
        $totalAbsent      = 0;
        $totalLate        = 0;
        $totalSubstitute  = 0;
        $totalMedical     = 0;


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

            $overallPercentage =
                round(
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
                'Status: ' .
                ucfirst(
                    esc($status)
                );
        }


        /*
    |--------------------------------------------------------------------------
    | FROM DATE
    |--------------------------------------------------------------------------
    */

        if (!empty($fromDate)) {

            $activeFilters[] =
                'From: ' .
                date(
                    'd M Y',
                    strtotime($fromDate)
                );
        }


        /*
    |--------------------------------------------------------------------------
    | TO DATE
    |--------------------------------------------------------------------------
    */

        if (!empty($toDate)) {

            $activeFilters[] =
                'To: ' .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        }


        /*
    |--------------------------------------------------------------------------
    | MONTH
    |--------------------------------------------------------------------------
    |
    | Only display month when From/To are not selected.
    |
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
                    strtotime(
                        $month . '-01'
                    )
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

        $html .= '<title>Attendance Report</title>';


        /*
    |--------------------------------------------------------------------------
    | CRM STYLE
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


/* --------------------------------------------------
   REPORT CONTAINER
-------------------------------------------------- */

.report {

    max-width: 1500px;

    margin: 0 auto;

    background: #ffffff;

    border-radius: 14px;

    padding: 28px;

    box-shadow:
        0 4px 20px rgba(0,0,0,.06);
}


/* --------------------------------------------------
   HEADER
-------------------------------------------------- */

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

    align-items: center;

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


/* --------------------------------------------------
   PRINT BUTTON
-------------------------------------------------- */

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


/* --------------------------------------------------
   FILTER BAR
-------------------------------------------------- */

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


/* --------------------------------------------------
   KPI CARDS
-------------------------------------------------- */

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


/* KPI variations */

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


/* --------------------------------------------------
   SECTION HEADER
-------------------------------------------------- */

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


/* --------------------------------------------------
   TABLE CARD
-------------------------------------------------- */

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


/* --------------------------------------------------
   MEMBER
-------------------------------------------------- */

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


/* --------------------------------------------------
   NUMBER
-------------------------------------------------- */

.number {

    font-weight: 700;

    color: #374151;
}


/* --------------------------------------------------
   BADGES
-------------------------------------------------- */

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


/* --------------------------------------------------
   ATTENDANCE PERCENTAGE
-------------------------------------------------- */

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


/* --------------------------------------------------
   TOTAL ROW
-------------------------------------------------- */

.total-row td {

    background: #f8fafc;

    border-top: 2px solid #e5e7eb;

    font-weight: 700;
}


/* --------------------------------------------------
   EMPTY
-------------------------------------------------- */

.no-data {

    padding: 45px 20px !important;

    text-align: center;

    color: #9ca3af;
}


/* --------------------------------------------------
   FOOTER
-------------------------------------------------- */

.report-footer {

    margin-top: 18px;

    padding-top: 15px;

    border-top: 1px solid #e5e7eb;

    display: flex;

    justify-content: space-between;

    color: #9ca3af;

    font-size: 11px;
}


/* --------------------------------------------------
   RESPONSIVE
-------------------------------------------------- */

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


/* --------------------------------------------------
   PRINT
-------------------------------------------------- */

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
            <span>📊</span>
        </div>

        <div>

            <h1>
                Attendance Report
            </h1>

            <p>
                Member attendance overview and meeting statistics
            </p>

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
            Active members
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
            Attendance records
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

                    $percentage =
                        round(
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

                    $progressClass =
                        'progress-medium';
                } else {

                    $progressClass =
                        'progress-low';
                }


                /*
            |--------------------------------------------------------------------------
            | MEMBER NAME
            |--------------------------------------------------------------------------
            */

                $name =
                    trim(
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

                    $initial =
                        strtoupper(
                            substr(
                                $name,
                                0,
                                1
                            )
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
                    style="width:' . $percentage . '%">
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
        Attendance Management System
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
    public function freeze_attendance()
    {
        $db = \Config\Database::connect();

        $event_code = trim((string) $this->request->getPost('event_code'));

        if ($event_code === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event code is required'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | GET EVENT
    |--------------------------------------------------------------------------
    */

        $event = $db->table('events e')
            ->select('
            e.*,
            c.chapter_name
        ')
            ->join(
                'chapters c',
                'c.chapter_code = e.chapter_code',
                'left'
            )
            ->where('e.event_code', $event_code)
            ->limit(1)
            ->get()
            ->getRow();

        if (!$event) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event not found'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | ALREADY FROZEN / COMPLETED
    |--------------------------------------------------------------------------
    */

        if ((string) $event->status === '3') {
            return $this->response->setJSON([
                'status'       => 'error',
                'event_status' => '3',
                'message'      => 'This meeting is already frozen.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | OPTIONAL: ONLY ALLOW FREEZE ON EVENT DATE
    |--------------------------------------------------------------------------
    */

        $today     = date('Y-m-d');
        $eventDate = date('Y-m-d', strtotime($event->date));

        if ($today !== $eventDate) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attendance can be frozen only on the event date.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | GET ABSENT MEMBERS BEFORE FREEZING
    |--------------------------------------------------------------------------
    */

        $absentMembers = $db->table('event_attendance ea')
            ->select('
            ea.user_code,
            ea.status,
            ea.note,
            u.name,
            u.email
        ')
            ->join(
                'users u',
                'u.user_code = ea.user_code',
                'inner'
            )
            ->where('ea.event_code', $event_code)
            ->where('ea.status', 'absent')
            ->where('u.status', '1')
            ->where('u.email !=', '')
            ->get()
            ->getResult();

        /*
    |--------------------------------------------------------------------------
    | FREEZE EVENT
    |
    | 3 = Completed / Frozen
    |--------------------------------------------------------------------------
    */

        $updated = $db->table('events')
            ->where('event_code', $event_code)
            ->where('status !=', '3')
            ->update([
                'status'     => '3',
                'updated_on' => date('Y-m-d H:i:s')
            ]);

        if (!$updated) {

            // Check if it became frozen already
            $checkEvent = $db->table('events')
                ->select('status')
                ->where('event_code', $event_code)
                ->get()
                ->getRow();

            if (!$checkEvent || (string) $checkEvent->status !== '3') {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Unable to freeze attendance.'
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | SEND EMAIL TO ABSENT MEMBERS
    |--------------------------------------------------------------------------
    */

        $mailSuccess = 0;
        $mailFailed  = 0;

        if (!empty($absentMembers)) {

            $emailService = \Config\Services::email();

            /*
        |--------------------------------------------------------------------------
        | EMAIL CONFIG
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Change SMTP password to the actual mailbox password.
        | Better: keep these values in .env.
        |
        */

            $config = [
                'protocol'   => 'smtp',
                'SMTPHost'   => 'smtp.hostinger.com',
                'SMTPUser'   => 'mail@eafconnect.com',
                'SMTPPass'   => 'mail@WHB26',
                'SMTPPort'   => 465,
                'SMTPCrypto' => 'ssl',
                'mailType'   => 'html',
                'charset'    => 'utf-8',
                'wordWrap'   => true,
                'newline'    => "\r\n",
                'CRLF'       => "\r\n",
                'SMTPTimeout' => 30
            ];

            $emailService->initialize($config);

            foreach ($absentMembers as $member) {

                $email = trim((string) ($member->email ?? ''));

                /*
            |--------------------------------------------------------------------------
            | VALID EMAIL
            |--------------------------------------------------------------------------
            */

                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mailFailed++;
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | CLEAR PREVIOUS EMAIL
            |--------------------------------------------------------------------------
            */

                $emailService->clear(true);

                /*
            |--------------------------------------------------------------------------
            | FROM
            |--------------------------------------------------------------------------
            */

                $emailService->setFrom(
                    'mail@eafconnect.com',
                    'EAF Connect'
                );

                /*
            |--------------------------------------------------------------------------
            | TO
            |--------------------------------------------------------------------------
            */

                $emailService->setTo($email);

                /*
            |--------------------------------------------------------------------------
            | SUBJECT
            |--------------------------------------------------------------------------
            */

                $emailService->setSubject(
                    'Meeting Attendance Notification - ' .
                        ($event->title ?? 'Meeting')
                );

                /*
            |--------------------------------------------------------------------------
            | SAFE DATA
            |--------------------------------------------------------------------------
            */

                $memberName = esc($member->name ?? 'Member');
                $chapterName = esc($event->chapter_name ?? 'N/A');
                $meetingTitle = esc($event->title ?? 'Meeting');
                $meetingDate = esc(
                    date(
                        'd M Y',
                        strtotime($event->date)
                    )
                );

                /*
            |--------------------------------------------------------------------------
            | EMAIL HTML
            |--------------------------------------------------------------------------
            */

                $message = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Meeting Attendance Notification</title>
</head>

<body style="
    margin:0;
    padding:20px;
    background:#f5f7fa;
    font-family:Arial,Helvetica,sans-serif;
">

<div style="
    max-width:600px;
    margin:0 auto;
    background:#ffffff;
    border:1px solid #e0e0e0;
    border-radius:10px;
    overflow:hidden;
">

    <!-- HEADER -->

    <div style="
        background:#1b4d8f;
        color:#ffffff;
        padding:22px;
    ">

        <h2 style="
            margin:0;
            color:#ffffff;
            font-size:22px;
        ">
            Meeting Attendance Notification
        </h2>

    </div>

    <!-- CONTENT -->

    <div style="padding:25px;">

        <p style="font-size:15px;">
            Dear <strong>' . $memberName . '</strong>,
        </p>

        <p style="font-size:15px;line-height:1.6;">
            The attendance for the following meeting has now
            been finalized.
        </p>

        <table style="
            width:100%;
            border-collapse:collapse;
            margin:20px 0;
            font-size:14px;
        ">

            <tr>
                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                    background:#f8f9fa;
                    width:35%;
                ">
                    <strong>Chapter</strong>
                </td>

                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                ">
                    ' . $chapterName . '
                </td>
            </tr>

            <tr>
                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                    background:#f8f9fa;
                ">
                    <strong>Meeting</strong>
                </td>

                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                ">
                    ' . $meetingTitle . '
                </td>
            </tr>

            <tr>
                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                    background:#f8f9fa;
                ">
                    <strong>Date</strong>
                </td>

                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                ">
                    ' . $meetingDate . '
                </td>
            </tr>

            <tr>
                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                    background:#f8f9fa;
                ">
                    <strong>Status</strong>
                </td>

                <td style="
                    padding:10px;
                    border:1px solid #ddd;
                    color:#dc3545;
                    font-weight:bold;
                ">
                    Absent
                </td>
            </tr>

        </table>

        <div style="
            background:#fff3cd;
            border:1px solid #ffeeba;
            padding:14px;
            border-radius:6px;
            margin:20px 0;
            color:#856404;
        ">
            Our records show that you were marked
            <strong>Absent</strong> for this meeting.
        </div>

        <p style="
            font-size:14px;
            line-height:1.6;
        ">
            Attendance has been finalized and can no longer
            be changed.
        </p>

        <p style="
            margin-top:30px;
            font-size:14px;
        ">
            Regards,<br>
            <strong>EAF Connect Team</strong>
        </p>

    </div>

</div>

</body>
</html>';

                $emailService->setMessage($message);

                /*
            |--------------------------------------------------------------------------
            | SEND
            |--------------------------------------------------------------------------
            */

                if ($emailService->send()) {

                    $mailSuccess++;
                } else {

                    $mailFailed++;

                    log_message(
                        'error',
                        'Absent attendance email failed for ' .
                            $email .
                            ': ' .
                            $emailService->printDebugger([
                                'headers',
                                'subject'
                            ])
                    );
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | FINAL RESPONSE
    |--------------------------------------------------------------------------
    */

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Attendance frozen successfully.',
            'event_status' => '3',
            'absent_count' => count($absentMembers),
            'mail_success' => $mailSuccess,
            'mail_failed'  => $mailFailed
        ]);
    }

    public function getMemberMeetingDay(string $userCode): string
    {
        $db = \Config\Database::connect();

        $row = $db->table('chapter_members AS cm')
            ->select('c.meeting_day')
            ->join(
                'chapters AS c',
                'c.chapter_code = cm.chapter_code',
                'inner'
            )
            ->where(
                'cm.user_code',
                $userCode
            )
            ->where(
                'cm.members_status',
                '1'
            )
            ->where(
                'c.chapter_status',
                '1'
            )
            ->where(
                'c.meeting_day !=',
                ''
            )
            ->orderBy(
                'cm.member_id',
                'ASC'
            )
            ->get()
            ->getRow();

        if (!$row) {
            return '';
        }

        return trim((string) $row->meeting_day);
    }
    public function unfreeze_attendance()
    {
        $db = \Config\Database::connect();

        $event_code = trim((string) $this->request->getPost('event_code'));

        if ($event_code === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event code is required.'
            ]);
        }

        // Get this particular event
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
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Event not found.'
            ]);
        }

        // Only this event date
        $today = date('Y-m-d');

        $eventDate = date(
            'Y-m-d',
            strtotime($event->date)
        );

        if ($today !== $eventDate) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attendance can be unfrozen only on the event date.'
            ]);
        }

        // Must currently be frozen
        if ((string) $event->status !== '3') {
            return $this->response->setJSON([
                'status'       => 'error',
                'event_status' => (string) $event->status,
                'message'      => 'This meeting is not frozen.'
            ]);
        }

        // Unfreeze ONLY this event
        $db->table('events')
            ->where('event_code', $event_code)
            ->where('status', '3')
            ->update([
                'status'     => '1',
                'updated_on' => date('Y-m-d H:i:s')
            ]);

        // Verify
        $check = $db->table('events')
            ->select('status')
            ->where('event_code', $event_code)
            ->get()
            ->getRow();

        if (!$check || (string) $check->status !== '1') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unable to unfreeze attendance.'
            ]);
        }

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Attendance unfrozen successfully.',
            'event_status' => '1',
            'event_code'   => $event_code
        ]);
    }

    public function attendance_report2()
    {
        $db = \Config\Database::connect();

        /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

        $userCode = trim(
            (string) session()->get('user_code')
        );

        if ($userCode === '') {
            return redirect()->to('/member_login');
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
    | RUNNING USER
    |--------------------------------------------------------------------------
    */

        $runningUser = $db
            ->table('users')
            ->select('name')
            ->where('user_code', $userCode)
            ->get()
            ->getRowArray();

        $runningUserName = trim(
            (string) ($runningUser['name'] ?? '')
        );

        if ($runningUserName === '') {
            $runningUserName = 'Member';
        }


        /*
    |--------------------------------------------------------------------------
    | GET LOGGED-IN USER CHAPTERS
    |--------------------------------------------------------------------------
    */

        $chapterMembers = $db
            ->table('chapter_members cm')
            ->select('cm.chapter_code, c.chapter_name')
            ->join(
                'chapters c',
                'c.chapter_code = cm.chapter_code',
                'left'
            )
            ->where(
                'cm.user_code',
                $userCode
            )
            ->where(
                'cm.members_status',
                '1'
            )
            ->groupBy([
                'cm.chapter_code',
                'c.chapter_name'
            ])
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | NO CHAPTER
    |--------------------------------------------------------------------------
    */

        if (empty($chapterMembers)) {

            return $this->attendanceReportHtml(
                [],
                $runningUserName,
                [],
                $search,
                $memberCode,
                $status,
                $fromDate,
                $toDate,
                $month
            );
        }


        /*
    |--------------------------------------------------------------------------
    | CHAPTER CODES
    |--------------------------------------------------------------------------
    */

        $chapterCodes = array_column(
            $chapterMembers,
            'chapter_code'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER NAMES
    |--------------------------------------------------------------------------
    */

        $chapterNames = [];

        foreach ($chapterMembers as $chapter) {

            if (!empty($chapter['chapter_name'])) {

                $chapterNames[] =
                    trim($chapter['chapter_name']);
            }
        }

        $chapterNames = array_values(
            array_unique($chapterNames)
        );


        /*
    |--------------------------------------------------------------------------
    | ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

        $builder = $db->table(
            'event_attendance ea'
        );


        /*
    |--------------------------------------------------------------------------
    | SELECT MEMBER ATTENDANCE COUNTS
    |--------------------------------------------------------------------------
    */

        $builder->select('
        ea.user_code,
        u.name,
        u.email,

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
                WHEN ea.status = "medical"
                THEN 1
                ELSE 0
            END
        ) AS medical_count,

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

        COUNT(DISTINCT ea.event_code) AS total_meetings
    ');


        /*
    |--------------------------------------------------------------------------
    | EVENT JOIN
    |--------------------------------------------------------------------------
    |
    | Only completed/valid events where status = 3
    |
    */

        $builder->join(
            'events e',
            "ea.event_code = e.event_code
         AND e.status = '3'",
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
    | ONLY MY CHAPTERS
    |--------------------------------------------------------------------------
    */

        $builder->whereIn(
            'ea.chapter_code',
            $chapterCodes
        );


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
                'e.title',
                $search
            );

            $builder->groupEnd();
        }


        /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | If status filter is applied, the report will only count that status.
    |
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
    |
    | Month works only when From/To are not selected.
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
    | GROUP
    |--------------------------------------------------------------------------
    */

        $builder->groupBy([
            'ea.user_code',
            'u.name',
            'u.email'
        ]);


        /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

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
    | RETURN REPORT HTML
    |--------------------------------------------------------------------------
    */

        return $this->attendanceReportHtml2(
            $report,
            $runningUserName,
            $chapterNames,
            $search,
            $memberCode,
            $status,
            $fromDate,
            $toDate,
            $month
        );
    }


    /*
|--------------------------------------------------------------------------
| ATTENDANCE REPORT HTML
|--------------------------------------------------------------------------
*/

    private function attendanceReportHtml2(
        array $report,
        string $runningUserName = '',
        array $chapterNames = [],
        string $search = '',
        string $memberCode = '',
        string $status = '',
        string $fromDate = '',
        string $toDate = '',
        string $month = ''
    ) {
        /*
    |--------------------------------------------------------------------------
    | REPORT DATE
    |--------------------------------------------------------------------------
    */

        $runAt = date(
            'M d, Y h:i A'
        );


        /*
    |--------------------------------------------------------------------------
    | CHAPTER DISPLAY
    |--------------------------------------------------------------------------
    */

        if (!empty($chapterNames)) {

            $chapterDisplay =
                implode(
                    ', ',
                    $chapterNames
                );
        } else {

            $chapterDisplay =
                'No Chapter';
        }


        /*
    |--------------------------------------------------------------------------
    | START DATE DISPLAY
    |--------------------------------------------------------------------------
    */

        if ($fromDate !== '') {

            $displayStartDate =
                date(
                    'n/j/y',
                    strtotime($fromDate)
                );
        } elseif ($month !== '') {

            $displayStartDate =
                date(
                    'n/j/y',
                    strtotime($month . '-01')
                );
        } else {

            $displayStartDate =
                'All';
        }


        /*
    |--------------------------------------------------------------------------
    | END DATE DISPLAY
    |--------------------------------------------------------------------------
    */

        if ($toDate !== '') {

            $displayEndDate =
                date(
                    'n/j/y',
                    strtotime($toDate)
                );
        } elseif ($month !== '') {

            $monthEnd =
                date(
                    'Y-m-t',
                    strtotime($month . '-01')
                );

            $displayEndDate =
                date(
                    'n/j/y',
                    strtotime($monthEnd)
                );
        } else {

            $displayEndDate =
                'All';
        }


        /*
    |--------------------------------------------------------------------------
    | REPORT PERIOD
    |--------------------------------------------------------------------------
    */

        if (
            $fromDate !== '' &&
            $toDate !== ''
        ) {

            $reportPeriod =
                date(
                    'd M Y',
                    strtotime($fromDate)
                )
                .
                ' - '
                .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        } elseif ($fromDate !== '') {

            $reportPeriod =
                'From '
                .
                date(
                    'd M Y',
                    strtotime($fromDate)
                );
        } elseif ($toDate !== '') {

            $reportPeriod =
                'Up to '
                .
                date(
                    'd M Y',
                    strtotime($toDate)
                );
        } elseif ($month !== '') {

            $reportPeriod =
                date(
                    'F Y',
                    strtotime($month . '-01')
                );
        } else {

            $reportPeriod =
                'Overall Attendance';
        }


        /*
    |--------------------------------------------------------------------------
    | TOTAL COUNTS
    |--------------------------------------------------------------------------
    */

        $totalMembers = count($report);

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalMedical = 0;
        $totalLate = 0;
        $totalSubstitute = 0;
        $totalMeetings = 0;


        foreach ($report as $row) {

            $totalPresent +=
                (int) ($row['present_count'] ?? 0);

            $totalAbsent +=
                (int) ($row['absent_count'] ?? 0);

            $totalMedical +=
                (int) ($row['medical_count'] ?? 0);

            $totalLate +=
                (int) ($row['late_count'] ?? 0);

            $totalSubstitute +=
                (int) ($row['substitute_count'] ?? 0);

            $totalMeetings +=
                (int) ($row['total_meetings'] ?? 0);
        }


        /*
    |--------------------------------------------------------------------------
    | HTML
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

        $html .= '
<title>
    Attendance Report
</title>
';


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

html,
body {
    margin: 0;
    padding: 0;
}

body {

    background: #ffffff;

    color: #222222;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    font-size: 12px;
}


/* --------------------------------------------------
   REPORT WRAPPER
-------------------------------------------------- */

.report-wrapper {

    width: 100%;

    padding: 18px 28px 30px 28px;
}


/* --------------------------------------------------
   TOP HEADER
-------------------------------------------------- */

.top-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    background: #ffffff;

    border-bottom: 1px solid #dddddd;

    padding: 5px 0 10px 0;

    margin-bottom: 8px;
}

.report-heading {

    font-size: 24px;

    font-weight: 700;

    color: #c91d2b;

    margin: 0;
}

.close-text {

    font-size: 26px;

    color: #777777;

    font-weight: 300;
}


/* --------------------------------------------------
   RUNNING USER BAR
-------------------------------------------------- */

.user-bar {

    display: grid;

    grid-template-columns:
        1.2fr
        1.4fr
        1fr
        1.4fr
        2fr;

    background: #e7e8ed;

    min-height: 38px;

    border-radius: 3px;

    overflow: hidden;

    margin-bottom: 9px;
}

.user-item {

    padding: 6px 12px;

    border-right: 1px solid #ffffff;

    font-size: 10px;
}

.user-item:last-child {

    border-right: 0;
}

.user-label {

    display: block;

    font-weight: 700;

    color: #333333;

    margin-bottom: 2px;
}

.user-value {

    display: block;

    font-weight: 700;

    color: #111111;
}


/* --------------------------------------------------
   PARAMETERS
-------------------------------------------------- */

.parameters {

    background: #eef3f6;

    border-radius: 4px;

    padding: 6px 10px 7px 10px;

    margin-bottom: 18px;
}

.parameters-title {

    font-weight: 700;

    font-size: 10px;

    margin-bottom: 4px;

    color: #333333;
}

.parameter-row {

    display: grid;

    grid-template-columns: 160px 1fr;

    border-bottom: 1px solid #ffffff;

    min-height: 18px;

    align-items: center;
}

.parameter-row:last-child {

    border-bottom: 0;
}

.parameter-label {

    font-weight: 700;

    padding: 2px 4px;

    font-size: 10px;
}

.parameter-value {

    padding: 2px 4px;

    font-weight: 600;

    font-size: 10px;
}


/* --------------------------------------------------
   REPORT INFO
-------------------------------------------------- */

.report-info {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 8px;

    padding: 0 2px;
}

.report-info-title {

    font-size: 13px;

    font-weight: 700;

    color: #333333;
}

.report-info-period {

    font-size: 10px;

    color: #666666;

    font-weight: 600;
}


/* --------------------------------------------------
   TABLE
-------------------------------------------------- */

.report-table-wrapper {

    width: 100%;

    overflow-x: auto;
}

.report-table {

    width: 100%;

    border-collapse: collapse;

    table-layout: fixed;

    border: 1px solid #bbbbbb;
}

.report-table th {

    background: #cf1e2d;

    color: #ffffff;

    border: 1px solid #ffffff;

    font-size: 11px;

    font-weight: 700;

    height: 20px;

    padding: 3px 5px;

    text-align: center;

    vertical-align: middle;
}

.report-table th.member-header {

    width: auto;
}

.report-table th.count-header {

    width: 58px;
}

.report-table td {

    border: 1px solid #cccccc;

    height: 20px;

    padding: 3px 7px;

    font-size: 10px;

    vertical-align: middle;
}

.report-table td.member-cell {

    text-align: left;

    font-weight: 500;

    color: #222222;

    white-space: nowrap;
}

.report-table td.count-cell {

    text-align: right;

    padding-right: 10px;

    font-weight: 600;
}


/* --------------------------------------------------
   ALTERNATE ROW
-------------------------------------------------- */

.report-table tbody tr:nth-child(even) {

    background: #f1f2f3;
}

.report-table tbody tr:nth-child(odd) {

    background: #ffffff;
}

.report-table tbody tr:hover {

    background: #e9eaec;
}


/* --------------------------------------------------
   STATUS COLORS
-------------------------------------------------- */

.absent-count {

    color: #222222;

    font-weight: 700;
}

.medical-count {

    color: #222222;

    font-weight: 700;
}

.late-count {

    color: #222222;

    font-weight: 700;
}

.substitute-count {

    color: #222222;

    font-weight: 700;
}


/* --------------------------------------------------
   TOTAL
-------------------------------------------------- */

.total-row td {

    background: #e7e8ed !important;

    font-weight: 700;

    border-top: 2px solid #999999;
}

.total-label {

    text-align: right !important;

    padding-right: 10px !important;
}


/* --------------------------------------------------
   SUMMARY
-------------------------------------------------- */

.summary-box {

    display: flex;

    gap: 8px;

    margin-top: 15px;

    flex-wrap: wrap;
}

.summary-item {

    border: 1px solid #d1d1d1;

    background: #f5f5f5;

    padding: 7px 14px;

    min-width: 100px;

    text-align: center;

    border-radius: 3px;
}

.summary-label {

    display: block;

    font-size: 9px;

    color: #666666;

    font-weight: 700;

    text-transform: uppercase;
}

.summary-value {

    display: block;

    font-size: 15px;

    font-weight: 700;

    margin-top: 2px;
}


/* --------------------------------------------------
   FOOTER
-------------------------------------------------- */

.footer {

    margin-top: 15px;

    padding-top: 7px;

    border-top: 1px solid #dddddd;

    display: flex;

    justify-content: space-between;

    font-size: 9px;

    color: #888888;
}


/* --------------------------------------------------
   EMPTY
-------------------------------------------------- */

.empty-row {

    text-align: center !important;

    padding: 30px !important;

    color: #777777;

    font-weight: 600;
}


/* --------------------------------------------------
   PRINT
-------------------------------------------------- */

@media print {

    body {

        background: #ffffff;

        margin: 0;

        padding: 0;
    }

    .report-wrapper {

        padding: 10px 15px;
    }

    .close-text {

        display: none;
    }

    .report-table {

        page-break-inside: auto;
    }

    .report-table tr {

        page-break-inside: avoid;

        page-break-after: auto;
    }

    .report-table thead {

        display: table-header-group;
    }
}


/* --------------------------------------------------
   MOBILE
-------------------------------------------------- */

@media(max-width:700px) {

    .report-wrapper {

        padding: 10px;
    }

    .user-bar {

        grid-template-columns: 1fr 1fr;
    }

    .parameter-row {

        grid-template-columns: 100px 1fr;
    }

    .report-table {

        min-width: 500px;
    }
}

</style>

';


        $html .= '</head>';

        $html .= '<body>';

        $html .= '<div class="report-wrapper">';


        /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="top-header">

    <h1 class="report-heading">
        Attendance Report
    </h1>

    <div class="close-text">
        ×
    </div>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | USER BAR
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="user-bar">

    <div class="user-item">

        <span class="user-label">
            Running User
        </span>

        <span class="user-value">
            ' . esc($runningUserName) . '
        </span>

    </div>


    <div class="user-item">

        <span class="user-label">
            Run At
        </span>

        <span class="user-value">
            ' . esc($runAt) . '
        </span>

    </div>


    <div class="user-item">

        <span class="user-label">
            Country
        </span>

        <span class="user-value">
            India
        </span>

    </div>


    <div class="user-item">

        <span class="user-label">
            Report Period
        </span>

        <span class="user-value">
            ' . esc($reportPeriod) . '
        </span>

    </div>


    <div class="user-item">

        <span class="user-label">
            Chapter
        </span>

        <span class="user-value">
            ' . esc($chapterDisplay) . '
        </span>

    </div>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | PARAMETERS
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="parameters">

    <div class="parameters-title">
        Parameters
    </div>


    <div class="parameter-row">

        <div class="parameter-label">
            Chapter:
        </div>

        <div class="parameter-value">
            ' . esc($chapterDisplay) . '
        </div>

    </div>


    <div class="parameter-row">

        <div class="parameter-label">
            Start Date:
        </div>

        <div class="parameter-value">
            ' . esc($displayStartDate) . '
        </div>

    </div>


    <div class="parameter-row">

        <div class="parameter-label">
            End Date:
        </div>

        <div class="parameter-value">
            ' . esc($displayEndDate) . '
        </div>

    </div>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | TABLE TITLE
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="report-info">

    <div class="report-info-title">
        Member Attendance
    </div>

    <div class="report-info-period">
        ' . esc($reportPeriod) . '
    </div>

</div>
';


        /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="report-table-wrapper">

<table class="report-table">

<thead>

<tr>

    <th class="member-header">
        Member Name
    </th>

    <th class="count-header">
        A
    </th>

    <th class="count-header">
        M
    </th>

    <th class="count-header">
        L
    </th>

    <th class="count-header">
        S
    </th>

</tr>

</thead>

<tbody>

';


        /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

        if (!empty($report)) {

            foreach ($report as $row) {

                $name = trim(
                    (string) (
                        $row['name'] ?? ''
                    )
                );

                $absent = (int) (
                    $row['absent_count'] ?? 0
                );

                $medical = (int) (
                    $row['medical_count'] ?? 0
                );

                $late = (int) (
                    $row['late_count'] ?? 0
                );

                $substitute = (int) (
                    $row['substitute_count'] ?? 0
                );


                $html .= '

<tr>

    <td class="member-cell">
        ' . esc($name) . '
    </td>

    <td class="count-cell absent-count">
        ' . $absent . '
    </td>

    <td class="count-cell medical-count">
        ' . $medical . '
    </td>

    <td class="count-cell late-count">
        ' . $late . '
    </td>

    <td class="count-cell substitute-count">
        ' . $substitute . '
    </td>

</tr>

';
            }


            /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

            $html .= '

<tr class="total-row">

    <td class="total-label">
        TOTAL
    </td>

    <td class="count-cell">
        ' . $totalAbsent . '
    </td>

    <td class="count-cell">
        ' . $totalMedical . '
    </td>

    <td class="count-cell">
        ' . $totalLate . '
    </td>

    <td class="count-cell">
        ' . $totalSubstitute . '
    </td>

</tr>

';
        } else {

            $html .= '

<tr>

    <td
        colspan="5"
        class="empty-row">

        No attendance records found
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
    | SUMMARY
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="summary-box">

    <div class="summary-item">

        <span class="summary-label">
            Members
        </span>

        <span class="summary-value">
            ' . $totalMembers . '
        </span>

    </div>


    <div class="summary-item">

        <span class="summary-label">
            Meetings
        </span>

        <span class="summary-value">
            ' . $totalMeetings . '
        </span>

    </div>


    <div class="summary-item">

        <span class="summary-label">
            Present
        </span>

        <span class="summary-value">
            ' . $totalPresent . '
        </span>

    </div>


    <div class="summary-item">

        <span class="summary-label">
            Absent
        </span>

        <span class="summary-value">
            ' . $totalAbsent . '
        </span>

    </div>


    <div class="summary-item">

        <span class="summary-label">
            Medical
        </span>

        <span class="summary-value">
            ' . $totalMedical . '
        </span>

    </div>


    <div class="summary-item">

        <span class="summary-label">
            Late
        </span>

        <span class="summary-value">
            ' . $totalLate . '
        </span>

    </div>


    <div class="summary-item">

        <span class="summary-label">
            Substitute
        </span>

        <span class="summary-value">
            ' . $totalSubstitute . '
        </span>

    </div>

</div>

';


        /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

        $html .= '

<div class="footer">

    <div>
        Attendance Management System
    </div>

    <div>
        Generated:
        ' . esc($runAt) . '
    </div>

</div>

';


        /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

        $html .= '</div>';

        $html .= '</body>';

        $html .= '</html>';


        return $html;
    }
}

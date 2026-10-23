<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class CronController extends BaseController
{
    public function autoGenerateMeetings($key = '')
    {
        if ($key != 'cron123') {
            exit('Unauthorized');
        }

        $commonModel = new CommonModel();
        $today = strtolower(date('l'));

        $chapters = $commonModel
            ->exe_query("SELECT * FROM chapters WHERE chapter_status='1'")
            ->getResultArray();

        foreach ($chapters as $chapter) {

            if (strtolower($chapter['meeting_day']) != $today) {
                continue;
            }

            $exists = $commonModel->exe_query("
                SELECT * FROM events 
                WHERE chapter_code='" . esc($chapter['chapter_code']) . "' 
                AND YEARWEEK(date,1)=YEARWEEK(CURDATE(),1)
            ")->getRow();

            if ($exists) {
                continue;
            }

            $event_code = $commonModel->get_serial3('events', 'EVE', 'event_code');

            $commonModel->insert_data('events', [
                'event_code'   => $event_code,
                'user_code'    => $chapter['leader_code'],
                'chapter_code' => $chapter['chapter_code'],
                'title'        => $chapter['chapter_name'] . ' Weekly Meeting',
                'description'  => 'Auto Generated Weekly Meeting',
                'date'         => date('Y-m-d 10:00:00'),
                'time'         => '10:00 AM',
                'location'     => $chapter['location'],
                'note'         => 'Auto Generated',
                'created_by'   => 1,
                'updated_on'   => date('Y-m-d H:i:s'),
            ]);
        }

        echo "Auto Meeting Done";
    }
}

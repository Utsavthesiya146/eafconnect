<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('get_member_avatar')) {
    /**
     * Get Member Profile Avatar with AI Fallback
     *
     * @param string|null $photo Filename from users.profile_photo
     * @param string|null $gender Male/Female/Other or empty
     * @param string|null $userCode User code if gender lookup needed
     * @param bool $returnMeta If true, returns array ['url' => ..., 'is_ai' => bool, 'type' => string]
     * @return string|array
     */
    function get_member_avatar($photo = null, $gender = null, $userCode = null, $returnMeta = false)
    {
        $photo = trim((string)$photo);
        $cleanGender = ucfirst(strtolower(trim((string)$gender)));

        // 1. FAST PATH: Check for real photo first without any DB hit
        $hasRealPhoto = false;
        if (!empty($photo) && $photo !== 'empty.png' && $photo !== 'empty2.png') {
            if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $photo)) {
                $hasRealPhoto = true;
            }
        }

        if ($hasRealPhoto) {
            $avatarUrl = base_url('uploads/profile/' . $photo);
            if ($returnMeta) {
                return ['url' => $avatarUrl, 'is_ai' => false, 'type' => 'real'];
            }
            return $avatarUrl;
        }

        // 2. Default clean profile avatar fallback based on provided gender
        if ($cleanGender === 'Female') {
            $avatarUrl = base_url('images/female.png');
            $type = 'default_female';
        } elseif ($cleanGender === 'Male') {
            $avatarUrl = base_url('images/male.png');
            $type = 'default_male';
        } else {
            $avatarUrl = base_url('images/empty.png');
            $type = 'default_neutral';
        }

        if ($returnMeta) {
            return ['url' => $avatarUrl, 'is_ai' => false, 'type' => $type];
        }

        return $avatarUrl;
    }
}

if (!function_exists('create_system_notification')) {
    /**
     * Dispatch one or more in-app notifications
     *
     * @param string|int|array $userCodes Target user_code or array of user_codes
     * @param string $title Notification headline
     * @param string $message Detailed notification message
     * @param string|null $chapterCode Optional chapter code
     * @param string|null $note Optional metadata / context
     * @return int Number of notifications created
     */
    function create_system_notification($userCodes, string $title, string $message, ?string $chapterCode = null, ?string $note = null): int
    {
        try {
            $db = \Config\Database::connect();
            $targets = [];

            if (is_array($userCodes)) {
                foreach ($userCodes as $code) {
                    $c = trim((string)$code);
                    if ($c !== '' && !in_array($c, $targets, true)) {
                        $targets[] = $c;
                    }
                }
            } else {
                $c = trim((string)$userCodes);
                if ($c !== '') {
                    $targets[] = $c;
                }
            }

            // Handle 'all' or chapter-wide distribution
            if (in_array('all', $targets, true)) {
                $targets = array_diff($targets, ['all']);
                $builder = $db->table('chapter_members')
                    ->select('user_code')
                    ->where('members_status', '1');
                if (!empty($chapterCode)) {
                    $builder->where('chapter_code', $chapterCode);
                }
                $cm = $builder->get()->getResultArray();
                foreach ($cm as $member) {
                    $mc = trim((string)$member['user_code']);
                    if ($mc !== '' && !in_array($mc, $targets, true)) {
                        $targets[] = $mc;
                    }
                }
            }

            if (empty($targets)) {
                return 0;
            }

            // Query max numeric part of n_code for unique serial generation
            $row = $db->query("SELECT MAX(CAST(SUBSTRING(n_code, 5) AS UNSIGNED)) AS max_no FROM notifications WHERE n_code LIKE 'NOTI%'")->getRow();
            $maxNo = (!empty($row->max_no)) ? (int)$row->max_no : 0;

            $now = date('Y-m-d H:i:s');
            $rowsToInsert = [];

            foreach ($targets as $userCode) {
                $maxNo++;
                $nCode = 'NOTI' . str_pad((string)$maxNo, 5, '0', STR_PAD_LEFT);
                $rowsToInsert[] = [
                    'n_code'       => $nCode,
                    'user_code'    => (string)$userCode,
                    'chapter_code' => $chapterCode ?: null,
                    'title'        => $title,
                    'message'      => $message,
                    'note'         => $note ?: null,
                    'status'       => 'unread',
                    'created_at'   => $now,
                ];
            }

            if (!empty($rowsToInsert)) {
                $inserted = $db->table('notifications')->insertBatch($rowsToInsert);
                return $inserted ? count($rowsToInsert) : 0;
            }

            return 0;
        } catch (\Throwable $e) {
            log_message('error', 'create_system_notification failed: ' . $e->getMessage());
            return 0;
        }
    }
}

if (!function_exists('get_notification_icon')) {
    /**
     * Get visual icon, color and badge label for a notification
     */
    function get_notification_icon(string $title = '', string $message = ''): array
    {
        $text = strtolower($title . ' ' . $message);

        if (strpos($text, '121') !== false || strpos($text, 'one-to-one') !== false || strpos($text, 'f2f') !== false) {
            return [
                'icon'  => 'ri-user-voice-line',
                'color' => '#2563EB',
                'bg'    => '#EFF6FF',
                'border'=> '#BFDBFE',
                'badge' => '1-to-1'
            ];
        }

        if (strpos($text, 'referral') !== false || strpos($text, 'connect') !== false) {
            return [
                'icon'  => 'ri-shake-hands-line',
                'color' => '#16A34A',
                'bg'    => '#F0FDF4',
                'border'=> '#BBF7D0',
                'badge' => 'Connect'
            ];
        }

        if (strpos($text, 'meeting') !== false || strpos($text, 'event') !== false) {
            return [
                'icon'  => 'ri-calendar-event-line',
                'color' => '#D97706',
                'bg'    => '#FFFBEB',
                'border'=> '#FDE68A',
                'badge' => 'Meeting'
            ];
        }

        if (strpos($text, 'mitb') !== false || strpos($text, 'thank you') !== false || strpos($text, 'business') !== false) {
            return [
                'icon'  => 'ri-money-rupee-circle-line',
                'color' => '#9333EA',
                'bg'    => '#FAF5FF',
                'border'=> '#E9D5FF',
                'badge' => 'Business'
            ];
        }

        return [
            'icon'  => 'ri-notification-3-line',
            'color' => '#0F3B68',
            'bg'    => '#F8FAFC',
            'border'=> '#E2E8F0',
            'badge' => 'Notice'
        ];
    }
}

if (!function_exists('notification_time_ago')) {
    /**
     * Format timestamp to friendly time ago string
     */
    function notification_time_ago($datetime): string
    {
        if (empty($datetime)) {
            return '';
        }

        $time = is_numeric($datetime) ? (int)$datetime : strtotime($datetime);
        if (!$time) {
            return '';
        }

        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Just now';
        }
        if ($diff < 3600) {
            $mins = max(1, (int)floor($diff / 60));
            return $mins . 'm ago';
        }
        if ($diff < 86400) {
            $hours = (int)floor($diff / 3600);
            return $hours . 'h ago';
        }
        if ($diff < 172800) {
            return 'Yesterday ' . date('h:i A', $time);
        }

        return date('d M Y, h:i A', $time);
    }
}


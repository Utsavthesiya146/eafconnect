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

        // If gender not provided but userCode is, check database
        if (empty($cleanGender) && !empty($userCode)) {
            static $genderCache = [];
            if (!isset($genderCache[$userCode])) {
                if (class_exists('\Config\Database')) {
                    try {
                        $db = \Config\Database::connect();
                        $u = $db->table('users')->select('gender')->where('user_code', $userCode)->get()->getRow();
                        $genderCache[$userCode] = $u ? ucfirst(strtolower(trim($u->gender ?? ''))) : '';
                    } catch (\Throwable $e) {
                        $genderCache[$userCode] = '';
                    }
                } else {
                    $genderCache[$userCode] = '';
                }
            }
            $cleanGender = $genderCache[$userCode];
        }

        $uploadDir = rtrim(FCPATH, '/\\') . '/uploads/profile/';
        $hasRealPhoto = false;

        if (!empty($photo) && $photo !== 'empty.png' && $photo !== 'empty2.png') {
            if (file_exists($uploadDir . $photo) || file_exists(FCPATH . 'uploads/profile/' . $photo)) {
                $hasRealPhoto = true;
            } elseif (preg_match('/\.(jpg|jpeg|png|webp)$/i', $photo)) {
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

        // Default clean, simple profile avatars
        if ($cleanGender === 'Male') {
            $avatarUrl = base_url('images/male.png');
            $type = 'default_male';
        } elseif ($cleanGender === 'Female') {
            $avatarUrl = base_url('images/female.png');
            $type = 'default_female';
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

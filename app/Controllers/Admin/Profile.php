<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Profile extends BaseController
{
    public function index()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin');
        }
        $commonModel = new CommonModel();
        $user_code = session()->get('user_code');
        $db = \Config\Database::connect();
        $qry = "SELECT * FROM users 
                WHERE status='1'
                AND user_code='" . $user_code . "'";
        $member = $commonModel->exe_query($qry)->getResult();
        $data = [
            'member' => $member
        ];
        return view('admin/profile', $data);
    }

    public function details($user_code)
    {
        $db = \Config\Database::connect();
        $model = new \App\Models\TyfcbModel();
        $commonModel = new \App\Models\CommonModel();

        /* ===============================
       MEMBER DETAILS
    =============================== */
        $qry = "
        SELECT u.*, c.chapter_name
        FROM users u
        LEFT JOIN chapter_members cm ON cm.user_code = u.user_code
        LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
        WHERE u.user_code = ?
    ";
        $member = $db->query($qry, [$user_code])->getResult();

        /* ===============================
       REFERRALS
    =============================== */
        $referrals = $db->query("SELECT * FROM referrals WHERE from_user=?", [$user_code]);
        $referrals_c = $referrals->getNumRows();

        /* ===============================
       GIVEN REFERRALS
    =============================== */
        $qry = "
        SELECT r.*,
               fu.name  AS from_name,
               fu.email AS from_email,
               fu.profile_photo AS from_profile,
               tu.name  AS to_name,
               tu.email AS to_email,
               tu.profile_photo AS to_profile,
               c.chapter_name
        FROM referrals r
        LEFT JOIN users fu ON fu.user_code = r.from_user
        LEFT JOIN users tu ON tu.user_code = r.to_user
        LEFT JOIN chapter_members cm ON cm.user_code = tu.user_code
        LEFT JOIN chapters c ON c.chapter_code = cm.chapter_code
        WHERE fu.status='1'
        AND fu.user_code=?
        ORDER BY r.created_at DESC
    ";

        $given = $db->query($qry, [$user_code])->getResultArray();

        /* ===============================
       PAGINATION FILTER
    =============================== */
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');

        $baseQuery = function () use ($model, $search, $status) {

            $builder = $model->select("
                tyfcb.*,
                from_user.name AS from_name,
                to_user.name AS to_name
            ")
                ->join('users AS from_user', 'from_user.user_code=tyfcb.from_user', 'left')
                ->join('users AS to_user', 'to_user.user_code=tyfcb.to_user', 'left');

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('from_user.name', $search)
                    ->orLike('to_user.name', $search)
                    ->orLike('tyfcb.business_type', $search)
                    ->groupEnd();
            }

            if (!empty($status)) {
                $builder->where('tyfcb.status', $status);
            }

            return $builder;
        };

        $receivedBuilder = $baseQuery();
        $receivedBuilder->where('tyfcb.from_user', $user_code);
        $receivedBuilder->orderBy('mitb_id', 'DESC');
        $mitb_received = $receivedBuilder->paginate($perPage, 'received');

        $givenBuilder = $baseQuery();
        $givenBuilder->where('tyfcb.to_user', $user_code);
        $givenBuilder->orderBy('mitb_id', 'DESC');
        $mitb_given = $givenBuilder->paginate($perPage, 'given');

        /* ===============================
       COUNTS
    =============================== */
        $givenCount = $model->where('from_user', $user_code)->countAllResults();
        $receivedCount = $model->where('to_user', $user_code)->countAllResults();

        $meetingsCount = $db->query("SELECT * FROM one_to_ones WHERE from_user=?", [$user_code])->getNumRows();
        $r_g_c = $db->query("SELECT * FROM referrals WHERE from_user=?", [$user_code])->getNumRows();
        $r_r_c = $db->query("SELECT * FROM referrals WHERE to_user=?", [$user_code])->getNumRows();
        $visitors_c = $db->query("SELECT * FROM visitors WHERE user_code=?", [$user_code])->getNumRows();

        /* ===============================
       FILTER DATE
    =============================== */
        $filter = $this->request->getGet('filter') ?? '180';

        $dateCondition = "";
        $dateParams = [];

        if ($filter != 'all') {
            $dateCondition = " AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $dateParams[] = (int)$filter;
        }

        /* ===============================
       TOTALS
    =============================== */
        $totalMITBReceived = $db->query("
        SELECT COALESCE(SUM(amount),0) total
        FROM tyfcb
        WHERE from_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))->getRow()->total;

        $totalMITBGiven = $db->query("
        SELECT COALESCE(SUM(amount),0) total
        FROM tyfcb
        WHERE to_user=?
        $dateCondition
    ", array_merge([$user_code], $dateParams))->getRow()->total;

        $totalRevenue = $totalMITBReceived + $totalMITBGiven;
        $totalRecords = $givenCount + $receivedCount;
        $avgMemberValue = ($totalRecords > 0) ? ($totalRevenue / $totalRecords) : 0;

        /* ===============================
       CHART DATA
    =============================== */
        $givenQuery = $db->query("
        SELECT DATE(created_at) mitb_date,
               SUM(amount) total_given
        FROM tyfcb
        WHERE to_user=?
        $dateCondition
        GROUP BY DATE(created_at)
        ORDER BY mitb_date ASC
    ", array_merge([$user_code], $dateParams))->getResultArray();

        $receivedQuery = $db->query("
        SELECT DATE(created_at) mitb_date,
               SUM(amount) total_received
        FROM tyfcb
        WHERE from_user=?
        $dateCondition
        GROUP BY DATE(created_at)
        ORDER BY mitb_date ASC
    ", array_merge([$user_code], $dateParams))->getResultArray();

        $dates = [];
        $givenData = [];
        $receivedData = [];

        foreach ($givenQuery as $row) {
            $dates[$row['mitb_date']] = date('d M', strtotime($row['mitb_date']));
            $givenData[$row['mitb_date']] = $row['total_given'];
        }

        foreach ($receivedQuery as $row) {
            $dates[$row['mitb_date']] = date('d M', strtotime($row['mitb_date']));
            $receivedData[$row['mitb_date']] = $row['total_received'];
        }

        ksort($dates);

        $labels = [];
        $finalGiven = [];
        $finalReceived = [];

        foreach ($dates as $dateKey => $label) {
            $labels[] = $label;
            $finalGiven[] = $givenData[$dateKey] ?? 0;
            $finalReceived[] = $receivedData[$dateKey] ?? 0;
        }

        /* ===============================
       FINAL DATA
    =============================== */
        $data = [
            'member' => $member,
            'given' => $given,
            'referrals_c' => $referrals_c,
            'mitb_received' => $mitb_received,
            'mitb_given' => $mitb_given,
            'givenCount' => $givenCount,
            'receivedCount' => $receivedCount,
            'totalRevenue' => $totalRevenue,
            'totalRecords' => $totalRecords,
            'avgMemberValue' => $avgMemberValue,
            'meetingsCount' => $meetingsCount,
            'r_g_c' => $r_g_c,
            'r_r_c' => $r_r_c,
            'visitors_c' => $visitors_c,
            'totalMITBReceived' => $totalMITBReceived,
            'totalMITBGiven' => $totalMITBGiven,
            'dates' => json_encode($labels),
            'givenData' => json_encode($finalGiven),
            'receivedData' => json_encode($finalReceived),
            'filter' => $filter,


        ];

        return view('admin/profile_details', $data);
    }



    public function update_profile_data_action()
    {
        $request = service('request');
        // GET USER CODE
        $user_code = $request->getPost('user_code');

        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }
        // FORM DATA
        $data = [
            'name'      => $request->getPost('name'),
            'gender'    => $request->getPost('gender'),
            'dob'       => $request->getPost('dob'),
            'email'     => $request->getPost('email'),
            'phone'     => $request->getPost('phone'),
            'alt_phone' => $request->getPost('alt_phone'),
            'whatsapp'  => $request->getPost('whatsapp'),
            'address'   => $request->getPost('address'),
            'city'      => $request->getPost('city'),
            'state'     => $request->getPost('state'),
            'pincode'   => $request->getPost('pincode'),
        ];

        $model = new CommonModel();

        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code] // ✅ FIXED
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Profile updated successfully!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
    public function updateProfessionalSkillsForm()
    {
        $request = service('request');
        // GET USER CODE
        $user_code = $request->getPost('user_code');
        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }
        // FORM DATA
        $data = [
            'designation'      => $request->getPost('designation'),
            'exp_years'    => $request->getPost('exp_years'),
            'skills'       => $request->getPost('skills'),
            'services'     => $request->getPost('services'),
            'products'     => $request->getPost('products'),
            'bio' => $request->getPost('bio'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code] // ✅ FIXED
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Profile updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
    public function UpdateBusinessInformationForm()
    {
        $request = service('request');
        // GET USER CODE
        $user_code = $request->getPost('user_code');
        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }
        // FORM DATA
        $data = [
            'business_name'      => $request->getPost('business_name'),
            'industry'    => $request->getPost('industry'),
            'subcategory'       => $request->getPost('subcategory'),
            'description'     => $request->getPost('description'),
            'reg_number'     => $request->getPost('reg_number'),
            'gst_number' => $request->getPost('gst_number'),
            'start_year' => $request->getPost('start_year'),
            'employees' => $request->getPost('employees'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code] // ✅ FIXED
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Profile updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }
    public function UpdateBusinessLocationForm()
    {
        $request = service('request');
        // GET USER CODE
        $user_code = $request->getPost('user_code');
        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }
        // FORM DATA
        $data = [
            'office_address'      => $request->getPost('office_address'),
            'working_hours'    => $request->getPost('working_hours'),
            'service_areas'       => $request->getPost('service_areas'),
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code] // ✅ FIXED
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Profile updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => '❌ Update failed'
        ]);
    }


    public function updateSocialMedia()
    {
        $request = service('request');

        $user_code = $request->getPost('user_code');

        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }

        $data = [
            'website'         => $request->getPost('website') ?? '',
            'linkedin'        => $request->getPost('linkedin') ?? '',
            'facebook'        => $request->getPost('facebook') ?? '',
            'instagram'       => $request->getPost('instagram') ?? '',
            'twitter'         => $request->getPost('twitter') ?? '',
            'youtube'         => $request->getPost('youtube') ?? '',
            'google_business' => $request->getPost('google_business') ?? '',
        ];

        $model = new CommonModel();

        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code]
        );

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Profile updated successfully!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Update failed'
        ]);
    }


    public function updateAwardsData()
    {
        $request = service('request');
        $user_code = $request->getPost('user_code');
        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }
        $data = [
            'awards'         => $request->getPost('awards') ?? '',
            'certifications'        => $request->getPost('certifications') ?? '',
            'tags'        => $request->getPost('tags') ?? '',
        ];
        $model = new CommonModel();
        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code]
        );
        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Profile updated successfully!'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Update failed'
        ]);
    }


    public function updateMediaDocumentData()
    {
        $request = service('request');

        $user_code = $request->getPost('user_code');

        if (!$user_code) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User code missing'
            ]);
        }

        $data = [];

        /*
    |--------------------------------------------------------------------------
    | PROFILE PHOTO
    |--------------------------------------------------------------------------
    */
        $profile = $request->getFile('profile_photo');

        if ($profile && $profile->isValid() && !$profile->hasMoved()) {

            $newName = $profile->getRandomName();
            $profile->move(FCPATH . 'uploads/profile/', $newName);

            $data['profile_photo'] = $newName;
        }

        /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGO
    |--------------------------------------------------------------------------
    */
        $logo = $request->getFile('business_logo');

        if ($logo && $logo->isValid() && !$logo->hasMoved()) {

            $newName = $logo->getRandomName();
            $logo->move(FCPATH . 'uploads/business_logo/', $newName);

            $data['business_logo'] = $newName;
        }

        /*
    |--------------------------------------------------------------------------
    | BUSINESS CARD
    |--------------------------------------------------------------------------
    */
        $card = $request->getFile('business_card');

        if ($card && $card->isValid() && !$card->hasMoved()) {

            $newName = $card->getRandomName();
            $card->move(FCPATH . 'uploads/business_cards/', $newName);

            $data['business_card'] = $newName;
        }

        /*
    |--------------------------------------------------------------------------
    | BROCHURE
    |--------------------------------------------------------------------------
    */
        $brochure = $request->getFile('brochure');

        if ($brochure && $brochure->isValid() && !$brochure->hasMoved()) {

            $newName = $brochure->getRandomName();
            $brochure->move(FCPATH . 'uploads/brochures/', $newName);

            $data['brochure'] = $newName;
        }

        /*
    |--------------------------------------------------------------------------
    | PORTFOLIO
    |--------------------------------------------------------------------------
    */
        $portfolio = $request->getFile('portfolio');

        if ($portfolio && $portfolio->isValid() && !$portfolio->hasMoved()) {

            $newName = $portfolio->getRandomName();
            $portfolio->move(FCPATH . 'uploads/portfolios/', $newName);

            $data['portfolio'] = $newName;
        }

        $model = new CommonModel();

        $update = $model->update_row(
            'users',
            $data,
            ['user_code' => $user_code]
        );

        return $this->response->setJSON([
            'status' => $update ? 'success' : 'error',
            'message' => $update
                ? 'Media & documents updated successfully!'
                : 'No files uploaded'
        ]);
    }
    public function updateChangePasswordtData()
    {
        $request = service('request');

        // ✅ LOAD MODEL (MISSING IN YOUR CODE)
        $model = new CommonModel();

        $user_code = trim($request->getPost('user_code'));
        $new       = trim($request->getPost('new_password'));


        $update = $model->update_row(
            'users',
            [
                'password' => password_hash($new, PASSWORD_DEFAULT)
            ],
            [
                'user_code' => $user_code
            ]
        );
        return $this->response->setJSON([
            'status'  => $update ? 'success' : 'error',
            'message' => $update
                ? 'Password changed successfully'
                : 'Password update failed'
        ]);
    }
}

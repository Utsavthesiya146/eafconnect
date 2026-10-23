<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class Profile extends BaseController
{
    public function index()
    {
        if (!session()->get('is_member')) {
            return redirect()->to('/member_login');
        }
        $commonModel = new CommonModel();
        // Get session value
        $user_code = session()->get('user_code');
        // Query
        $qry = "SELECT * FROM users  WHERE status='1' AND user_code='" . $user_code . "'";
        $member = $commonModel->exe_query($qry)->getResult();
        $data = [
            'member' => $member
        ];
        return view('member/profile', $data);
    }
    public function details($user_code)
    {
        $commonModel = new CommonModel();
        $model       = new \App\Models\TyfcbModel();

        /*
    |--------------------------------------------------------------------------
    | SAFE USER CODE
    |--------------------------------------------------------------------------
    */
        $user_code       = trim((string) $user_code);
        $safe_user_code  = esc($user_code);


        /*
    |--------------------------------------------------------------------------
    | MEMBER DETAILS
    |--------------------------------------------------------------------------
    */

        $qry = "
        SELECT
            u.*,
            c.chapter_name
        FROM users u
        LEFT JOIN chapter_members cm
            ON cm.user_code = u.user_code
        LEFT JOIN chapters c
            ON c.chapter_code = cm.chapter_code
        WHERE u.status = '1'
        AND u.user_code = '{$safe_user_code}'
    ";

        $member = $commonModel
            ->exe_query($qry)
            ->getResult();


        /*
    |--------------------------------------------------------------------------
    | REFERRALS
    |--------------------------------------------------------------------------
    */

        $referrals = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE from_user = '{$safe_user_code}'
    ");

        $referrals_c = $referrals->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | GIVEN REFERRALS
    |--------------------------------------------------------------------------
    */

        $qry = "
        SELECT
            r.*,

            fu.name AS from_name,
            fu.email AS from_email,
            fu.profile_photo AS from_profile,

            tu.name AS to_name,
            tu.email AS to_email,
            tu.profile_photo AS to_profile,

            c.chapter_name

        FROM referrals r

        LEFT JOIN users fu
            ON fu.user_code = r.from_user

        LEFT JOIN users tu
            ON tu.user_code = r.to_user

        LEFT JOIN chapter_members cm
            ON cm.user_code = tu.user_code

        LEFT JOIN chapters c
            ON c.chapter_code = cm.chapter_code

        WHERE fu.status = '1'
        AND fu.user_code = '{$safe_user_code}'

        ORDER BY r.created_at DESC
    ";

        $given = $commonModel
            ->exe_query($qry)
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | MITB / TYFCB PAGINATION
    |--------------------------------------------------------------------------
    */

        $perPage = $this->request->getGet('perPage') ?? 10;
        $search  = trim((string) $this->request->getGet('search'));
        $status  = trim((string) $this->request->getGet('status'));


        /*
    |--------------------------------------------------------------------------
    | BASE MITB QUERY
    |--------------------------------------------------------------------------
    */

        $baseQuery = function () use ($model, $search, $status) {

            $builder = $model->select("
            tyfcb.*,

            from_user.name AS from_name,
            from_user.email AS from_email,
            from_user.profile_photo AS from_profile,

            to_user.name AS to_name,
            to_user.email AS to_email,
            to_user.profile_photo AS to_profile
        ")

                ->join(
                    'users AS from_user',
                    'from_user.user_code = tyfcb.from_user',
                    'left'
                )

                ->join(
                    'users AS to_user',
                    'to_user.user_code = tyfcb.to_user',
                    'left'
                );


            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

            if (!empty($search)) {

                $builder->groupStart()
                    ->like('from_user.name', $search)
                    ->orLike('to_user.name', $search)
                    ->orLike('tyfcb.mitb_code', $search)
                    ->orLike('tyfcb.business_type', $search)
                    ->groupEnd();
            }


            /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

            if (!empty($status)) {
                $builder->where('tyfcb.status', $status);
            }

            return $builder;
        };


        /*
    |--------------------------------------------------------------------------
    | MITB RECEIVED
    |--------------------------------------------------------------------------
    */

        $receivedBuilder = $baseQuery();

        $receivedBuilder
            ->where('tyfcb.to_user', $user_code)
            ->orderBy('tyfcb.mitb_id', 'DESC');

        $mitb_received = $receivedBuilder
            ->paginate($perPage, 'received');


        /*
    |--------------------------------------------------------------------------
    | MITB GIVEN
    |--------------------------------------------------------------------------
    */

        $givenBuilder = $baseQuery();

        $givenBuilder
            ->where('tyfcb.from_user', $user_code)
            ->orderBy('tyfcb.mitb_id', 'DESC');

        $mitb_given = $givenBuilder
            ->paginate($perPage, 'given');


        /*
    |--------------------------------------------------------------------------
    | MITB COUNTS
    |--------------------------------------------------------------------------
    */

        $givenCount = $model
            ->where('from_user', $user_code)
            ->countAllResults();

        $receivedCount = $model
            ->where('to_user', $user_code)
            ->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | TOTAL REVENUE
    |--------------------------------------------------------------------------
    */

        $totalRevenueRow = $model
            ->selectSum('amount')
            ->groupStart()
            ->where('from_user', $user_code)
            ->orWhere('to_user', $user_code)
            ->groupEnd()
            ->first();

        $totalRevenue = $totalRevenueRow['amount'] ?? 0;


        /*
    |--------------------------------------------------------------------------
    | TOTAL RECORDS
    |--------------------------------------------------------------------------
    */

        $totalRecords = $model
            ->groupStart()
            ->where('from_user', $user_code)
            ->orWhere('to_user', $user_code)
            ->groupEnd()
            ->countAllResults();


        /*
    |--------------------------------------------------------------------------
    | AVERAGE MEMBER VALUE
    |--------------------------------------------------------------------------
    */

        $avgMemberValue = ($totalRecords > 0)
            ? ($totalRevenue / $totalRecords)
            : 0;


        /*
    |--------------------------------------------------------------------------
    | F2F MEETINGS
    |--------------------------------------------------------------------------
    */

        $f2f = $commonModel->exe_query("
        SELECT *
        FROM one_to_ones
        WHERE from_user = '{$safe_user_code}'
    ");

        $meetingsCount = $f2f->getNumRows();


        // inducation

        $inducation = $commonModel->exe_query("
        SELECT *
        FROM users
        WHERE refferd_by = '{$safe_user_code}'
    ");

        $inducationCount = $inducation->getNumRows();



        /*
    |--------------------------------------------------------------------------
    | REFERRALS GIVEN COUNT
    |--------------------------------------------------------------------------
    */

        $r_g = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE from_user = '{$safe_user_code}'
    ");

        $r_g_c = $r_g->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | REFERRALS RECEIVED COUNT
    |--------------------------------------------------------------------------
    */

        $r_r = $commonModel->exe_query("
        SELECT *
        FROM referrals
        WHERE to_user = '{$safe_user_code}'
    ");

        $r_r_c = $r_r->getNumRows();


        /*
    |--------------------------------------------------------------------------
    | ACCEPTED VISITORS ONLY
    |--------------------------------------------------------------------------
    */

        $visitorBuilder = db_connect()->table('visitors');

        $visitors = $visitorBuilder
            ->select('visitors.*')
            ->where('user_code', $user_code)
            ->where('status', 'Accepted')
            ->orderBy('visitors_id', 'DESC')
            ->get()
            ->getResultArray();


        /*
    |--------------------------------------------------------------------------
    | ACCEPTED VISITOR COUNT
    |--------------------------------------------------------------------------
    */

        $visitors_c = count($visitors);


        /*
    |--------------------------------------------------------------------------
    | TOTAL MITB RECEIVED
    |--------------------------------------------------------------------------
    */

        $totalMITBReceivedRow = $model
            ->selectSum('amount')
            ->where('from_user', $user_code)
            ->first();

        $totalMITBReceived = $totalMITBReceivedRow['amount'] ?? 0;


        /*
    |--------------------------------------------------------------------------
    | TOTAL MITB GIVEN
    |--------------------------------------------------------------------------
    */

        $totalMITBGivenRow = $model
            ->selectSum('amount')
            ->where('to_user', $user_code)
            ->first();

        $totalMITBGiven = $totalMITBGivenRow['amount'] ?? 0;

        $db = db_connect();

        $sponsoredCount = $db->query("
    SELECT COUNT(*) AS total
    FROM visitors
    WHERE user_code = ?
    AND LOWER(status) = 'accepted'
", [$user_code])
            ->getRow()->total ?? 0;
        /*
    |--------------------------------------------------------------------------
    | VIEW DATA
    |--------------------------------------------------------------------------
    */
$commonModel = new \App\Models\CommonModel();

$user_code = trim((string) session()->get('user_code'));

$users = [];

if ($user_code !== '') {

    $users = $commonModel->db
        ->table('users')
        ->where('refferd_by', $user_code)
        ->orderBy('user_id', 'DESC')
        ->get()
        ->getResult();
}


        $data = [

            'member' => $member,
           'users' => $users,
            'sponsoredCount' => $sponsoredCount,
            /* Referrals */
            'given'       => $given,
            'referrals_c' => $referrals_c,
            'r_g_c'       => $r_g_c,
            'r_r_c'       => $r_r_c,
            'inducationCount'       => $inducationCount,
            /* MITB */
            'mitb_received' => $mitb_received,
            'mitb_given'    => $mitb_given,

            'givenCount'    => $givenCount,
            'receivedCount' => $receivedCount,

            /* Statistics */
            'totalRevenue'   => $totalRevenue,
            'totalRecords'   => $totalRecords,
            'avgMemberValue' => $avgMemberValue,

            /* Meetings */
            'meetingsCount' => $meetingsCount,

            /* Accepted Visitors */
            'visitors'   => $visitors,
            'visitors_c' => $visitors_c,

            /* MITB totals */
            'totalMITBReceived' => $totalMITBReceived,
            'totalMITBGiven'    => $totalMITBGiven,
        ];


        return view(
            'member/profile_details',
            $data
        );
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
            session()->set('profile_photo', $newName);
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
        $model   = new CommonModel();

        $user_code = trim($request->getPost('user_code'));
        $new       = trim($request->getPost('new_password'));
        $confirm   = trim($request->getPost('confirm_password'));

        // Validation
        if (empty($user_code) || empty($new) || empty($confirm)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'All fields are required.'
            ]);
        }

        if ($new != $confirm) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'New password and confirm password do not match.'
            ]);
        }

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
                ? 'Password changed successfully.'
                : 'Password update failed.'
        ]);
    }
}

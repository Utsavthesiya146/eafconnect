<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('member_login', 'Home::member_login');
$routes->get('member_register', 'Home::member_register');
$routes->get('admin', 'Home::admin_login');
$routes->get('contact', 'Home::contact');
$routes->post('contact_save', 'Home::save');

// Routes.php$routes->post('AddLead', 'YourController::AddLead');
$routes->post('addlead', 'Home::addlead');
$routes->post('home/contact_form', 'Home::contact_form');

$routes->get('register', 'Auth::register');
$routes->post('save-register', 'Auth::saveRegister');


$routes->get('blogs', 'Blogs::index');
$routes->get('blogs/details/(:any)', 'Blogs::details/$1');


$routes->get('login', 'Auth::login');
$routes->post('loginCheck', 'Auth::loginCheck');
$routes->post('loginCheckAdmin', 'Auth::loginCheckAdmin');


$routes->get('dashboard', 'Auth::dashboard');
$routes->get('logout', 'Auth::logout');
$routes->get('preview', 'Auth::preview');

$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {});
// admin dashboard
$routes->get('admin/dashboard', 'Admin\Dashboard::index');
$routes->get('admin/dashboard/mergeUserData', 'Admin\Dashboard::mergeUserData');

$routes->post('admin/events/ajax_save_attendance', 'Admin\Events::ajax_save_attendance');
$routes->post('admin/events/ajax_mark_all_present', 'Admin\Events::ajax_mark_all_present');


$routes->get('admin/members', 'Admin\Members::index');
$routes->post('admin/members/AddNewMemeberData', 'Admin\Members::AddNewMemeberData');
$routes->get('admin/members/delete_user/(:any)', 'Admin\Members::delete_user/$1');
$routes->post('admin/members/delete_application_action', 'Admin\Members::delete_application_action');

$routes->get('admin/members/edit_status/(:any)', 'Admin\Members::edit_status/$1');
$routes->post('admin/members/edit_status_action', 'Admin\Members::edit_status_action');



$routes->get('admin/chapter_attendance', 'Admin\Meetings::chapter_attendance');
$routes->post('admin/meetings/get_filtered_data2', 'Admin\Meetings::get_filtered_data2');
$routes->post('admin/meetings/get_filtered_data3', 'Admin\Meetings::get_filtered_data3');

$routes->get(
    'admin/meetings/attendance_view',
    'Admin\Meetings::attendance_view'
);
$routes->get(
    'admin/meetings/attendance_excel',
    'Admin\Meetings::attendance_excel'
);
$routes->get(
    'admin/meetings/attendance_report',
    'Admin\Meetings::attendance_report'
);

$routes->get(
    'admin/meetings/attendance_pdf',
    'Admin\Meetings::attendance_pdf'
);


//ads

$routes->get('admin/ads', 'Admin\Ads::index');
$routes->post('admin/ads/AddNewAdsData', 'Admin\Ads::AddNewAdsData');
$routes->get('admin/ads/delete_ads/(:any)', 'Admin\Ads::delete_ads/$1');
$routes->post('admin/ads/delete_ads_action', 'Admin\Ads::delete_ads_action');
$routes->get('admin/ads/edit_ads/(:any)', 'Admin\Ads::edit_ads/$1');
$routes->post('admin/ads/edit_ads_action', 'Admin\Ads::edit_ads_action');
$routes->post('admin/ads/get_filtered_data', 'Admin\Ads::get_filtered_data');
//blogs

$routes->get('admin/blogs', 'Admin\Blogs::index');
$routes->post('admin/blogs/AddNewBlogData', 'Admin\Blogs::AddNewBlogData');
$routes->get('admin/blogs/delete_blogs/(:any)', 'Admin\Blogs::delete_blogs/$1');
$routes->post('admin/blogs/delete_blogs_action', 'Admin\Blogs::delete_blogs_action');
$routes->get('admin/blogs/edit_blogs/(:any)', 'Admin\Blogs::edit_blogs/$1');
$routes->post('admin/blogs/edit_blogs_action', 'Admin\Blogs::edit_blogs_action');
$routes->post('admin/blogs/get_filtered_data', 'Admin\Blogs::get_filtered_data');
// chapter
$routes->get('admin/chapter', 'Admin\Chapter::index');
$routes->group('admin', function ($routes) {
    $routes->get('admin/edit_m/(:any)', 'Admin\Members::edit_m/$1');
});
$routes->post('admin/Chapter/AddNewChapterData', 'Admin\Chapter::AddNewChapterData');
$routes->get('admin/chapter/delete_chapter/(:any)', 'Admin\Chapter::delete_chapter/$1');
$routes->post('admin/Chapter/delete_chapter_action', 'Admin\Chapter::delete_chapter_action');
$routes->get('admin/chapter/edit_chapter_status/(:any)', 'Admin\Chapter::edit_chapter_status/$1');
$routes->post('admin/chapter/edit_chapter_status_action', 'Admin\Chapter::edit_chapter_status_action');
$routes->get('admin/chapter/edit_chapter/(:any)', 'Admin\Chapter::edit_chapter/$1');
$routes->post('admin/chapter/edit_chapter_action', 'Admin\Chapter::edit_chapter_action');
$routes->get('admin/chapter/add_chapter_members/(:any)', 'Admin\Chapter::add_chapter_members/$1');
$routes->post('admin/chapter/add_chapter_members_action', 'Admin\Chapter::add_chapter_members_action');
$routes->post('admin/chapter/get_filtered_data', 'Admin\Chapter::get_filtered_data');
$routes->post('admin/chapter/get_filtered_data2', 'Admin\Chapter::get_filtered_data2');

$routes->post('admin/chapter/delete_chapter_member_action', 'Admin\Chapter::delete_chapter_member_action');
$routes->get('admin/chapter/delete_chapter_member/(:any)', 'Admin\Chapter::delete_chapter_member/$1');

// Admin Users filter AJAX
$routes->post('admin/members/get_filtered_data', 'Admin\Members::get_filtered_data');
$routes->get('admin/chapter/details/(:any)', 'Admin\Chapter::details/$1');
$routes->get('admin/profile/details/(:any)', 'Admin\Profile::details/$1');


// connects
$routes->get('admin/connect', 'Admin\Connect::index');
$routes->post('admin/connect/AddNewReferralData', 'Admin\Connect::AddNewReferralData');
$routes->get('admin/connect/delete_refferral/(:any)', 'Admin\Connect::delete_refferral/$1');
$routes->post('admin/connect/delete_referral_action', 'Admin\Connect::delete_referral_action');
$routes->get('admin/connect/edit_refferral_status/(:any)', 'Admin\Connect::edit_refferral_status/$1');
$routes->post('admin/connect/edit_refferral_status_action', 'Admin\Connect::edit_refferral_status_action');
$routes->get('admin/connect/edit_refferral/(:any)', 'Admin\Connect::edit_refferral/$1');
$routes->post('admin/connect/edit_refferral_action', 'Admin\Connect::edit_refferral_action');
$routes->post('admin/connect/get_filtered_data', 'Admin\Connect::get_filtered_data');
$routes->get('admin/connect/details/(:any)', 'Admin\Connect::details/$1');

// MITB
$routes->get('admin/mitb', 'Admin\Mitb::index');
$routes->post('admin/mitb/get_filtered_data', 'Admin\Mitb::get_filtered_data');
$routes->get('admin/mitb/export_excel', 'Admin\Mitb::export_excel');
$routes->get('admin/mitb/export_pdf', 'Admin\Mitb::export_pdf');
$routes->get('admin/mitb/delete_mitb/(:any)', 'Admin\Mitb::delete_mitb/$1');
$routes->post('admin/mitb/delete_mitb_action', 'Admin\Mitb::delete_mitb_action');
$routes->get('admin/mitb/edit_mitb/(:any)', 'Admin\Mitb::edit_mitb/$1');
$routes->post('admin/mitb/edit_mitb_action', 'Admin\Mitb::edit_mitb_action');

// f2f
$routes->get('admin/f2f', 'Admin\F2f::index');
$routes->get('admin/f2f/delete_f2f/(:any)', 'Admin\F2f::delete_f2f/$1');
$routes->post('admin/f2f/delete_f2f_action', 'Admin\F2f::delete_f2f_action');
$routes->get('admin/f2f/edit_f2f/(:any)', 'Admin\F2f::edit_f2f/$1');
$routes->post('admin/f2f/edit_f2f_action', 'Admin\F2f::edit_f2f_action');
$routes->post('admin/f2f/get_filtered_data', 'Admin\F2f::get_filtered_data');

// event
$routes->get('admin/meetings', 'Admin\Meetings::index');
$routes->post('admin/Meetings/AddNewMeetingData', 'Admin\Meetings::AddNewMeetingData');
$routes->get('admin/meetings/delete_meeting/(:any)', 'Admin\Meetings::delete_meeting/$1');
$routes->post('admin/meetings/delete_meeting_action', 'Admin\Meetings::delete_meeting_action');
$routes->get('admin/meetings/edit_meeting/(:any)', 'Admin\Meetings::edit_meeting/$1');
$routes->post('admin/meetings/edit_meeting_action', 'Admin\Meetings::edit_meeting_action');
$routes->post('admin/meetings/get_filtered_data', 'Admin\Meetings::get_filtered_data');

// profile
$routes->get('admin/profile', 'Admin\Profile::index');
$routes->post('admin/profile/update_profile_data_action', 'Admin\Profile::update_profile_data_action',);
$routes->post('admin/profile/updateProfessionalSkillsForm', 'Admin\Profile::updateProfessionalSkillsForm',);
$routes->post('admin/profile/UpdateBusinessInformationForm', 'Admin\Profile::UpdateBusinessInformationForm');
$routes->post('admin/profile/UpdateBusinessLocationForm', 'Admin\Profile::UpdateBusinessLocationForm');
$routes->post('admin/profile/updateSocialMedia', 'Admin\Profile::updateSocialMedia');
$routes->post('admin/profile/updateAwardsData', 'Admin\Profile::updateAwardsData');
$routes->post('admin/profile/updateMediaDocumentData', 'Admin\Profile::updateMediaDocumentData');
$routes->post('admin/profile/updateChangePasswordtData', 'Admin\Profile::updateChangePasswordtData');
$routes->get('admin/profile/details/(:any)', 'Admin\Profile::details/$1');

// notifications
$routes->get('admin/notifications', 'Admin\Notifications::index');
$routes->get('admin/notifications/delete_notf/(:any)', 'Admin\Notifications::delete_notf/$1');
$routes->post('admin/notifications/delete_notf_action', 'Admin\Notifications::delete_notf_action');
$routes->get('admin/notifications/edi_notf/(:any)', 'Admin\Notifications::edi_notf/$1');
$routes->post('admin/notifications/edit_not_status_action', 'Admin\Notifications::edit_not_status_action');
$routes->post('admin/notifications/AddNewNotificatongData', 'Admin\Notifications::AddNewNotificatongData');
$routes->post('admin/notifications/get_members_by_chapter', 'Admin\Notifications::get_members_by_chapter');


// Broadcast
$routes->get('admin/broadcast', 'Admin\Broadcast::index');
$routes->get('admin/broadcast/delete_broadcast/(:any)', 'Admin\Broadcast::delete_broadcast/$1');
$routes->post('admin/broadcast/delete_broadcast_action', 'Admin\Broadcast::delete_broadcast_action');
$routes->get('admin/broadcast/edit_broadcast/(:any)', 'Admin\Broadcast::edit_broadcast/$1');
$routes->post('admin/broadcast/edit_broadcast_status_action', 'Admin\Broadcast::edit_broadcast_status_action');
$routes->post('admin/broadcast/AddNewBroadcastData', 'Admin\Broadcast::AddNewBroadcastData');
$routes->post('admin/broadcast/get_members_by_chapter', 'Admin\Broadcast::get_members_by_chapter');


// visitor
$routes->get('admin/visitor', 'Admin\Visitor::index');
$routes->post('admin/visitor/AddNewvisitorData', 'Admin\Visitor::AddNewvisitorData');
$routes->get('admin/visitor/delete_v/(:any)', 'Admin\Visitor::delete_v/$1');
$routes->post('admin/visitor/delete_v_action', 'Admin\Visitor::delete_v_action');
$routes->get('admin/visitor/edit_v/(:any)', 'Admin\Visitor::edit_v/$1');
$routes->post('admin/visitor/edit_v_action', 'Admin\Visitor::edit_v_action');
$routes->get('admin/visitor/status_v_a/(:any)', 'Admin\Visitor::status_v_a/$1');
$routes->post('admin/visitor/status_v_a_action', 'Admin\Visitor::status_v_a_action');
$routes->post('admin/visitor/get_filtered_data', 'Admin\Visitor::get_filtered_data');
// invitation
$routes->get('admin/invitations', 'Admin\Invitations::index');
$routes->post('admin/Invitations/AddNewInvitationsData', 'Admin\Invitations::AddNewInvitationsData');
$routes->get('admin/invitations/delete_invitation/(:any)', 'Admin\Invitations::delete_invitation/$1');
$routes->post('admin/invitations/delete_invitation_action', 'Admin\Invitations::delete_invitation_action');
$routes->get('admin/invitations/edit_invitation/(:any)', 'Admin\Invitations::edit_invitation/$1');
$routes->post('admin/invitations/edit_invitation_action', 'Admin\Invitations::edit_invitation_action');
$routes->post('admin/invitations/get_filtered_data', 'Admin\Invitations::get_filtered_data');

// leads
$routes->get('admin/leads', 'Admin\Leads::index');
$routes->get('admin/leads/delete_leads/(:any)', 'Admin\Leads::delete_leads/$1');
$routes->post('admin/leads/delete_leads_action', 'Admin\Leads::delete_leads_action');
$routes->get('admin/leads/edit_leads/(:segment)', 'Admin\Leads::edit_leads/$1');
$routes->post('admin/leads/edit_leads_action', 'Admin\Leads::edit_leads_action');
$routes->post('admin/leads/get_filtered_data', 'Admin\Leads::get_filtered_data');
$routes->get('admin/leads/add_member/(:segment)', 'Admin\Leads::add_member/$1');
$routes->post('admin/leads/add_member_action', 'Admin\Leads::add_member_action');

// contact
$routes->get('admin/contact', 'Admin\Contact::index');
$routes->get('admin/contact/delete_contact/(:any)', 'Admin\Contact::delete_contact/$1');
$routes->post('admin/contact/delete_contact_action', 'Admin\Contact::delete_contact_action');
$routes->get('admin/contact/edit_contact/(:segment)', 'Admin\Contact::edit_contact/$1');
$routes->post('admin/contact/edit_contact_action', 'Admin\Contact::edit_contact_action');
$routes->post('admin/contact/get_filtered_data', 'Admin\Contact::get_filtered_data');



$routes->post('admin/meetings/ajax_save_attendance', 'Admin\Meetings::ajax_save_attendance');

// Admin Routes
$routes->get('admin/attendance', 'Admin\Meetings::my_attendance');
$routes->get('admin/members/edit_m/(:any)', 'Admin\Members::edit_m/$1');

$routes->get('admin/meetings/attendance/(:any)', 'Admin\Meetings::attendance/$1');
$routes->post('admin/meetings/save_attendance', 'Admin\Meetings::save_attendance');
$routes->get('admin/my_attendance', 'Admin\Meetings::my_attendance');

// ===============================
// CHAPTER DETAILS (already using)
// ===============================
$routes->get('admin/chapter/details/(:any)', 'Admin\Chapter::details/$1');

// ===============================
// CHAPTER REPORT VIEW
// ===============================
$routes->get('admin/chapter/report/(:any)', 'Admin\Chapter::chapter_report/$1');

// ===============================
// PDF DOWNLOAD
// ===============================
$routes->get('admin/chapter/download_pdf/(:any)', 'Admin\Chapter::download_pdf/$1');
$routes->post('admin/meetings/get_filtered_data2', 'Admin\Meetings::get_filtered_data2');
$routes->get('admin/meetings/attendance/(:any)', 'Admin\Meetings::attendance/$1');

$routes->get('admin/meetings/exportAttendanceExcel', 'Admin\Meetings::exportAttendanceExcel');
$routes->get('admin/meetings/exportAttendancePdf', 'Admin\Meetings::exportAttendancePdf');


$routes->get('admin/meetings/attance_status/(:any)', 'Admin\Meetings::attance_status/$1');
$routes->post('admin/meetings/edit_attendance_action', 'Admin\Meetings::edit_attendance_action');


// member dashboard
$routes->get('member/dashboard', 'Member\Dashboard::index');

// profile
$routes->get('member/profile', 'Member\Profile::index');
$routes->post('member/profile/update_profile_data_action', 'Member\Profile::update_profile_data_action',);
$routes->post('member/profile/updateProfessionalSkillsForm', 'Member\Profile::updateProfessionalSkillsForm',);
$routes->post('member/profile/UpdateBusinessInformationForm', 'Member\Profile::UpdateBusinessInformationForm');
$routes->get('member/profile/details/(:any)', 'Member\Profile::details/$1');
$routes->post('member/profile/UpdateBusinessLocationForm', 'Member\Profile::UpdateBusinessLocationForm');
$routes->post('member/profile/updateSocialMedia', 'Member\Profile::updateSocialMedia');
$routes->post('member/profile/updateAwardsData', 'Member\Profile::updateAwardsData');
$routes->post('member/profile/updateMediaDocumentData', 'Member\Profile::updateMediaDocumentData');
$routes->post('member/profile/updateChangePasswordtData', 'Member\Profile::updateChangePasswordtData');
// mychapter
$routes->get('member/mychapter', 'Member\Mychapter::index');

// chapter
$routes->get('member/chapter', 'Member\Chapter::index');
$routes->get('member/chapter/details/(:any)', 'Member\Chapter::details/$1');
$routes->post('member/Chapter/AddNewChapterData', 'Member\Chapter::AddNewChapterData');
$routes->get('member/chapter/edit_chapter_status/(:any)', 'Member\Chapter::edit_chapter_status/$1');
$routes->post('member/chapter/edit_chapter_status_action', 'Member\Chapter::edit_chapter_status_action');
$routes->get('member/chapter/edit_chapter/(:any)', 'Member\Chapter::edit_chapter/$1');
$routes->post('member/chapter/edit_chapter_action', 'Member\Chapter::edit_chapter_action');
$routes->get('member/chapter/add_chapter_members/(:any)', 'Member\Chapter::add_chapter_members/$1');
$routes->post('member/chapter/add_chapter_members_action', 'Member\Chapter::add_chapter_members_action');
$routes->post('member/chapter/get_filtered_data', 'Member\Chapter::get_filtered_data');
$routes->post('member/chapter/get_filtered_data2', 'Member\Chapter::get_filtered_data2');
// reffrel
$routes->get('member/connect', 'Member\Connect::index');
$routes->post('member/connect/getMembers', 'Member\Connect::getMembers');
$routes->post('member/connect/AddNewReferralData', 'Member\Connect::AddNewReferralData');
$routes->get('member/connect/edit_refferral_status/(:any)', 'Member\Connect::edit_refferral_status/$1');
$routes->post('member/connect/edit_refferral_status_action', 'Member\Connect::edit_refferral_status_action');
$routes->get('member/connect/edit_refferral/(:any)', 'Member\Connect::edit_refferral/$1');
$routes->post('member/connect/edit_refferral_action', 'Member\Connect::edit_refferral_action');
$routes->get('member/connect/details', 'Member\Connect::details');
$routes->get('member/connect/details/(:any)', 'Member\Connect::details/$1');
// MITB
$routes->get('member/mitb', 'Member\Mitb::index');
$routes->post('member/mitb/get_filtered_data', 'Member\Mitb::get_filtered_data');
$routes->get('member/mitb/export_excel', 'Member\Mitb::export_excel');
$routes->get('member/mitb/export_pdf', 'Member\Mitb::export_pdf');
$routes->get('member/mitb/delete_mitb/(:any)', 'Member\Mitb::delete_mitb/$1');
$routes->post('member/mitb/delete_mitb_action', 'Member\Mitb::delete_mitb_action');
$routes->get('member/mitb/edit_mitb/(:any)', 'Member\Mitb::edit_mitb/$1');
$routes->post('member/mitb/edit_mitb_action', 'Member\Mitb::edit_mitb_action');
$routes->post('member/mitb/addNewmitbData', 'Member\Mitb::addNewmitbData');


// f2f
$routes->get('member/f2f', 'Member\F2f::index');
$routes->get('member/f2f/delete_f2f/(:any)', 'Member\F2f::delete_f2f/$1');
$routes->post('member/f2f/delete_f2f_action', 'Member\F2f::delete_f2f_action');
$routes->get('member/f2f/edit_f2f/(:any)', 'Member\F2f::edit_f2f/$1');
$routes->post('member/f2f/edit_f2f_action', 'Member\F2f::edit_f2f_action');
$routes->post('member/f2f/addNewf2fData', 'Member\F2f::addNewf2fData');
$routes->post('member/f2f/get_filtered_data', 'Member\F2f::get_filtered_data');

// notifications
$routes->get('member/notifications', 'Member\Notifications::index');
$routes->get('member/notifications/edi_notf/(:any)', 'Member\Notifications::edi_notf/$1');
$routes->post('member/notifications/edit_not_status_action', 'Member\Notifications::edit_not_status_action');
$routes->post('member/notifications/edit_not_status_action2', 'Member\Notifications::edit_not_status_action2');

// event
$routes->get('member/meetings', 'Member\Meetings::index');
$routes->post('member/Meetings/AddNewMeetingData', 'Member\Meetings::AddNewMeetingData');
$routes->get('member/meetings/delete_meeting/(:any)', 'Member\Meetings::delete_meeting/$1');
$routes->post('member/meetings/delete_meeting_action', 'Member\Meetings::delete_meeting_action');
$routes->get('member/meetings/edit_meeting/(:any)', 'Member\Meetings::edit_meeting/$1');
$routes->post('member/meetings/edit_meeting_action', 'Member\Meetings::edit_meeting_action');
$routes->get('member/meetings/send_mail/(:any)', 'Member\Meetings::send_mail/$1');
$routes->post('member/meetings/send_mail_action', 'Member\Meetings::send_mail_action');
$routes->post('member/meetings/get_filtered_data', 'Member\Meetings::get_filtered_data');
$routes->post(
    'member/meetings/freeze_attendance',
    'Member\Meetings::freeze_attendance'
);
$routes->post(
    'member/meetings/unfreeze_attendance',
    'Member\Meetings::unfreeze_attendance'
);



// visitor
$routes->get('member/visitor', 'Member\Visitor::index');
$routes->post('member/visitor/AddNewvisitorData', 'Member\Visitor::AddNewvisitorData');
$routes->get('member/visitor/delete_v/(:any)', 'Member\Visitor::delete_v/$1');
$routes->post('member/visitor/delete_v_action', 'Member\Visitor::delete_v_action');
$routes->get('member/visitor/edit_v/(:any)', 'Member\Visitor::edit_v/$1');
$routes->post('member/visitor/edit_v_action', 'Member\Visitor::edit_v_action');
$routes->get('member/visitor/status_v_a/(:any)', 'Member\Visitor::status_v_a/$1');
$routes->post('member/visitor/status_v_a_action', 'Member\Visitor::status_v_a_action');
$routes->post('member/visitor/get_filtered_data', 'Member\Visitor::get_filtered_data');

$routes->get('member/visitor/send_mail/(:any)', 'Member\Visitor::send_mail/$1');
$routes->post('member/visitor/send_mail_action', 'Member\Visitor::send_mail_action');
// repots
$routes->get('member/reports/weekly_slips', 'Member\Reports::weekly_slips');
$routes->get('member/reports/download_weekly_report', 'Member\Reports::download_weekly_report');
$routes->get('member/received_connects_report', 'Member\Reports::received_connects_report');
$routes->get('member/my_participation_report', 'Member\Reports::my_participation_report');


$routes->get('member/participation', 'Member\Reports::participation');



// invitation
$routes->get('member/invitations', 'Member\Invitations::index');
$routes->post('member/Invitations/AddNewInvitationsData', 'Member\Invitations::AddNewInvitationsData');
$routes->get('member/invitations/delete_invitation/(:any)', 'Member\Invitations::delete_invitation/$1');
$routes->post('member/invitations/delete_invitation_action', 'Member\Invitations::delete_invitation_action');
$routes->get('member/invitations/edit_invitation/(:any)', 'Member\Invitations::edit_invitation/$1');
$routes->post('member/invitations/edit_invitation_action', 'Member\Invitations::edit_invitation_action');
$routes->post('member/invitations/get_filtered_data', 'Member\Invitations::get_filtered_data');

$routes->get('member/invitations/send_mail/(:any)', 'Member\Invitations::send_mail/$1');
$routes->post('member/invitations/send_mail_action', 'Member\Invitations::send_mail_action');


$routes->get('member/my_attendance', 'Member\Meetings::my_attendance');
$routes->get('member/chapter_attendance', 'Member\Meetings::chapter_attendance');
$routes->post('member/meetings/get_filtered_data2', 'Member\Meetings::get_filtered_data2');
$routes->post('member/meetings/get_filtered_data3', 'Member\Meetings::get_filtered_data3');

$routes->get(
    'member/meetings/attendance_view',
    'Member\Meetings::attendance_view'
);
$routes->get(
    'member/meetings/attendance_excel',
    'Member\Meetings::attendance_excel'
);
$routes->get(
    'member/meetings/attendance_report',
    'Member\Meetings::attendance_report'
);
$routes->get(
    'member/meetings/attendance_report2',
    'Member\Meetings::attendance_report2'
);

$routes->get(
    'member/meetings/attendance_pdf',
    'Member\Meetings::attendance_pdf'
);
$routes->get('member/chapter/details/(:any)', 'Member\Chapter::details/$1');
$routes->get('member/chapter/report/(:any)', 'Member\Chapter::chapter_report/$1');
$routes->get('member/chapter/download_pdf/(:any)', 'Member\Chapter::download_pdf/$1');
$routes->get('member/auto-meeting/(:any)', 'Member\Dashboard::autoGenerateMeetings/$1');


$routes->get('member/meetings/attendance/(:any)', 'Member\Meetings::attendance/$1');
$routes->post('member/meetings/save_attendance', 'Member\Meetings::save_attendance');
$routes->post('member/meetings/ajax_save_attendance', 'Member\Meetings::ajax_save_attendance');

$routes->get('member/broadcast', 'Member\Broadcast::index');

$routes->get('member/meetings/exportAttendanceExcel/(:any)', 'Member\Meetings::exportAttendanceExcel/$1');
$routes->get('member/meetings/exportAttendancePdf/(:any)', 'Member\Meetings::exportAttendancePdf/$1');

$routes->get('member/topperformer', 'Member\Topperformer::index');


$routes->post('member/connect/getMembers', 'Member\Connect::getMembers');

$routes->match(['GET', 'POST'], 'member/search', 'Member\Dashboard::search');

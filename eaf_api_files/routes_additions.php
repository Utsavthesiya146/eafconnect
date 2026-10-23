<?php
// ============================================================
// ADD THESE ROUTES TO app/Config/Routes.php
// Paste this block at the END of the existing Routes.php file,
// before the closing ?> or the end of the file.
// ============================================================

// ── API v1 Routes (Mobile App) ──────────────────────────────
// These routes do NOT use PHP sessions — they use Bearer tokens.

// Public API routes (no auth required)
$routes->post('api/v1/login',  '\App\Controllers4\Api\Auth::login');
$routes->post('api/v1/logout', '\App\Controllers4\Api\Auth::logout');

// OPTIONS preflight for CORS (mobile clients)
$routes->options('api/v1/(:any)', static function () {
    return service('response')
        ->setStatusCode(200)
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});

// Protected API routes (require Bearer token via apiAuth filter)
$routes->group('api/v1', ['filter' => 'apiAuth'], static function ($routes) {

    // Dashboard
    $routes->get('dashboard', '\App\Controllers4\Api\Dashboard::index');

    // Profile
    $routes->get('profile',                   '\App\Controllers4\Api\Profile::index');
    $routes->post('profile/update',           '\App\Controllers4\Api\Profile::update');
    $routes->post('profile/skills',           '\App\Controllers4\Api\Profile::updateSkills');
    $routes->post('profile/business',         '\App\Controllers4\Api\Profile::updateBusiness');
    $routes->post('profile/social',           '\App\Controllers4\Api\Profile::updateSocial');
    $routes->post('profile/photo',            '\App\Controllers4\Api\Profile::uploadPhoto');
    $routes->post('profile/password',         '\App\Controllers4\Api\Profile::changePassword');
    $routes->get('profile/details/(:any)',    '\App\Controllers4\Api\Profile::details/$1');

    // Referrals (Connects)
    $routes->get('connect',         '\App\Controllers4\Api\Connect::index');
    $routes->post('connect/add',    '\App\Controllers4\Api\Connect::add');
    $routes->get('connect/members', '\App\Controllers4\Api\Connect::members');

    // MITB
    $routes->get('mitb',        '\App\Controllers4\Api\Mitb::index');
    $routes->post('mitb/add',   '\App\Controllers4\Api\Mitb::add');

    // F2F (One-to-One Meetings)
    $routes->get('f2f',       '\App\Controllers4\Api\F2f::index');
    $routes->post('f2f/add',  '\App\Controllers4\Api\F2f::add');

    // Meetings / Events
    $routes->get('meetings', '\App\Controllers4\Api\Meetings::index');

    // Visitors
    $routes->get('visitors',       '\App\Controllers4\Api\Visitors::index');
    $routes->post('visitors/add',  '\App\Controllers4\Api\Visitors::add');

    // Invitations
    $routes->get('invitations',       '\App\Controllers4\Api\Invitations::index');
    $routes->post('invitations/add',  '\App\Controllers4\Api\Invitations::add');

    // Notifications
    $routes->get('notifications',            '\App\Controllers4\Api\Notifications::index');
    $routes->post('notifications/read',      '\App\Controllers4\Api\Notifications::markRead');
    $routes->post('notifications/read_all',  '\App\Controllers4\Api\Notifications::markAllRead');

    // Chapter
    $routes->get('chapter', '\App\Controllers4\Api\Chapter::index');

    // Broadcasts
    $routes->get('broadcasts', '\App\Controllers4\Api\Broadcast::index');

    // Reports
    $routes->get('reports/weekly',        '\App\Controllers4\Api\Reports::weekly');
    $routes->get('reports/participation', '\App\Controllers4\Api\Reports::participation');

    // Members search
    $routes->get('members/search', '\App\Controllers4\Api\Members::search');
});

<div class="vertical-menu">
    <!-- LOGO / BRAND (EAF Connect Official Logo) -->
    <div class="navbar-brand-box" style="padding: 16px 20px; border-bottom: 1px solid #F1F5F9; background: #FFFFFF;">
        <a href="<?= base_url('member/dashboard'); ?>" class="d-flex align-items-center gap-2" style="text-decoration:none;">
            <img src="<?= base_url('images/logo5.png'); ?>" alt="EAF Logo" height="42" style="object-fit:contain;" onerror="this.src='<?= base_url('images/logo6.png'); ?>'">
            <div style="display:flex; flex-direction:column; line-height:1.15;">
                <span style="font-size:18px; font-weight:800; color:#101B35; letter-spacing:-0.3px; font-family:'Outfit', 'Plus Jakarta Sans', system-ui, sans-serif;">
                    EAF <span style="color:#C6A667; font-weight:700; font-size:16px;">Connect</span>
                </span>
                <span style="font-size:10px; font-weight:700; color:#64748B; letter-spacing:0.5px; text-transform:uppercase;">
                    Member Portal
                </span>
            </div>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn d-none" id="vertical-menu-btn">
        <i class="ri-menu-2-line align-middle"></i>
    </button>

    <div data-simplebar class="vertical-scroll" style="height: calc(100% - 75px);">
        <?php
        $currentPath = uri_string();
        $isHome = ($currentPath === '' || $currentPath === 'member/dashboard' || strpos($currentPath, 'dashboard') !== false);
        $isProfile = (strpos($currentPath, 'profile') !== false);
        $isConnect = (strpos($currentPath, 'connect') !== false);
        $isF2F = (strpos($currentPath, 'f2f') !== false);
        $isMitb = (strpos($currentPath, 'mitb') !== false);
        $isSearch = (strpos($currentPath, 'search') !== false);
        $isAttendance = (strpos($currentPath, 'my_attendance') !== false);
        $isAnalytics = (strpos($currentPath, 'topperformer') !== false);
        $isMeetings = (strpos($currentPath, 'meetings') !== false);
        $isMyChapter = (strpos($currentPath, 'mychapter') !== false);
        $isAllChapters = (strpos($currentPath, 'chapter') !== false && !strpos($currentPath, 'mychapter') && !strpos($currentPath, 'chapter_attendance'));
        $isVisitor = (strpos($currentPath, 'visitor') !== false);
        $isNotifications = (strpos($currentPath, 'notifications') !== false);
        $isBroadcast = (strpos($currentPath, 'broadcast') !== false);
        ?>

        <!--- Sidemenu -->

        <?php
        $session = \Config\Services::session();
        $user_code = $session->get('user_code');
        $db = \Config\Database::connect();

        // Current URL for active menu highlighting
        $currentUrl = trim(uri_string(), '/');

        // Active menu helper
        $isActive = function ($path) use ($currentUrl) {
            return $currentUrl === trim($path, '/');
        };

        // Unread notification count
        $unreadCount = $db->table('notifications')
            ->where('user_code', $user_code)
            ->where('status', 'unread')
            ->countAllResults();

        // Current user's role
        $rowData = $db->table('users')
            ->select('member_role')
            ->where('user_code', $user_code)
            ->get()
            ->getRowArray();

        $memberRole = $rowData['member_role'] ?? '';

        $canViewChapterAttendance = in_array(
            $memberRole,
            ['President', 'Vice President'],
            true
        );
        ?>

        <div id="sidebar-menu" style="padding: 12px 0 30px 0;">

            <ul class="metismenu list-unstyled" id="side-menu">

                <!-- ================= DASHBOARD ================= -->

                <li class="<?= $isActive('member/dashboard') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/dashboard') ?>"
                        class="waves-effect <?= $isActive('member/dashboard') ? 'active' : '' ?>">
                        <i class="fa fa-gauge-high"></i>
                        <span>My Dashboard</span>
                    </a>
                </li>

                <!-- ================= NETWORK ================= -->

                <li class="menu-title">Network</li>

                <li class="<?= $isActive('member/mychapter') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/mychapter') ?>"
                        class="waves-effect <?= $isActive('member/mychapter') ? 'active' : '' ?>">
                        <i class="fa fa-building-columns"></i>
                        <span>My Chapter</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/chapter') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/chapter') ?>"
                        class="waves-effect <?= $isActive('member/chapter') ? 'active' : '' ?>">
                        <i class="fa-solid fa-sitemap"></i>
                        <span>All Chapters</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/connect') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/connect') ?>"
                        class="waves-effect <?= $isActive('member/connect') ? 'active' : '' ?>">
                        <i class="fa-solid fa-handshake"></i>
                        <span>Connects</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/topperformer') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/topperformer') ?>"
                        class="waves-effect <?= $isActive('member/topperformer') ? 'active' : '' ?>">
                        <i class="fa-solid fa-ranking-star"></i>
                        <span>Top Performers</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/mitb') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/mitb') ?>"
                        class="waves-effect <?= $isActive('member/mitb') ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>MITB</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/f2f') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/f2f') ?>"
                        class="waves-effect <?= $isActive('member/f2f') ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i>
                        <span>F2F</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/visitor') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/visitor') ?>"
                        class="waves-effect <?= $isActive('member/visitor') ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-check"></i>
                        <span>Visitor Tracking</span>
                    </a>
                </li>
                <li class="<?= $isSearch ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/search'); ?>" class="waves-effect <?= $isSearch ? 'active' : '' ?>">
                        <i class="ri-search-line"></i>
                        <span>Search</span>
                    </a>
                </li>
                <!-- ================= PAGES ================= -->

                <li class="menu-title">Pages</li>

                <li class="<?= $isActive('member/notifications') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/notifications') ?>"
                        class="waves-effect d-flex align-items-center justify-content-between <?= $isActive('member/notifications') ? 'active' : '' ?>">

                        <span>
                            <i class="fa-solid fa-bell"></i>
                            <span>Notifications</span>
                        </span>

                        <?php if ($unreadCount > 0): ?>
                            <span class="badge rounded-pill bg-danger ms-2">
                                <?= (int) $unreadCount ?>
                            </span>
                        <?php endif; ?>

                    </a>
                </li>

                <li class="<?= $isActive('member/broadcast') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/broadcast') ?>"
                        class="waves-effect <?= $isActive('member/broadcast') ? 'active' : '' ?>">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Broadcast</span>
                    </a>
                </li>

                <!-- ================= PROFILE ================= -->

                <li class="menu-title">Profile</li>

                <li class="<?= $isActive('member/profile') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/profile') ?>"
                        class="waves-effect <?= $isActive('member/profile') ? 'active' : '' ?>">
                        <i class="fa-solid fa-circle-user"></i>
                        <span>My Profile</span>
                    </a>
                </li>



                <li class="<?= $isActive('member/meetings') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/meetings') ?>"
                        class="waves-effect <?= $isActive('member/meetings') ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Chapter Meetings</span>
                    </a>
                </li>

                <li class="<?= $isActive('member/my_attendance') ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/my_attendance') ?>"
                        class="waves-effect <?= $isActive('member/my_attendance') ? 'active' : '' ?>">
                        <i class="fa-solid fa-clipboard-user"></i>
                        <span>My Attendance</span>
                    </a>
                </li>

                <!-- ================= CHAPTER ATTENDANCE ================= -->

                <?php if ($canViewChapterAttendance): ?>
                    <li class="<?= $isActive('member/chapter_attendance') ? 'mm-active' : '' ?>">
                        <a href="<?= base_url('member/chapter_attendance') ?>"
                            class="waves-effect <?= $isActive('member/chapter_attendance') ? 'active' : '' ?>">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span>Chapter Attendance</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ================= LOGOUT ================= -->

                <li style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                    <a href="<?= base_url('logout') ?>"
                        class="waves-effect logout-menu"
                        style="color: #DC2626 !important;">

                        <i class="fa-solid fa-right-from-bracket"
                            style="color: #DC2626 !important;"></i>

                        <span style="color: #DC2626 !important; font-weight: 600;">
                            Logout
                        </span>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>
<!-- Left Sidebar End -->

<!-- Mobile Drawer Backdrop Overlay -->
<div class="sidebar-backdrop" onclick="document.body.classList.remove('sidebar-enable');"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure all mobile drawer links navigate smoothly on touch/tap
        var menuLinks = document.querySelectorAll('#sidebar-menu a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (href && href !== '#' && href.indexOf('javascript:void') === -1) {
                    // If drawer is open on mobile, close it and navigate
                    document.body.classList.remove('sidebar-enable');
                    window.location.href = href;
                }
            });
        });
    });
</script>
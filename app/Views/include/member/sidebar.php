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
        <div id="sidebar-menu" style="padding: 12px 0 30px 0;">
            <ul class="metismenu list-unstyled" id="side-menu">
                <!-- 1. Dashboard -->
                <li class="<?= $isHome ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/dashboard'); ?>" class="waves-effect <?= $isHome ? 'active' : '' ?>">
                        <i class="ri-dashboard-3-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- 2. My Profile -->
                <li class="<?= $isProfile ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/profile'); ?>" class="waves-effect <?= $isProfile ? 'active' : '' ?>">
                        <i class="ri-user-3-line"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <!-- 3. Connects (EAF Wordings) -->
                <li class="<?= $isConnect ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/connect'); ?>" class="waves-effect <?= $isConnect ? 'active' : '' ?>">
                        <i class="ri-shake-hands-line"></i>
                        <span>Connects</span>
                    </a>
                </li>

                <!-- 4. F2F (1-to-1) -->
                <li class="<?= $isF2F ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/f2f'); ?>" class="waves-effect <?= $isF2F ? 'active' : '' ?>">
                        <i class="ri-group-line"></i>
                        <span>F2F</span>
                    </a>
                </li>

                <!-- 5. MITB -->
                <li class="<?= $isMitb ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/mitb'); ?>" class="waves-effect <?= $isMitb ? 'active' : '' ?>">
                        <i class="ri-money-rupee-circle-line"></i>
                        <span>MITB</span>
                    </a>
                </li>

                <!-- 6. Search -->
                <li class="<?= $isSearch ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/search'); ?>" class="waves-effect <?= $isSearch ? 'active' : '' ?>">
                        <i class="ri-search-line"></i>
                        <span>Search</span>
                    </a>
                </li>

                <!-- 7. My Attendance -->
                <li class="<?= $isAttendance ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/my_attendance'); ?>" class="waves-effect <?= $isAttendance ? 'active' : '' ?>">
                        <i class="ri-calendar-check-line"></i>
                        <span>My Attendance</span>
                    </a>
                </li>

                <!-- 8. Top Performers -->
                <li class="<?= $isAnalytics ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/topperformer'); ?>" class="waves-effect <?= $isAnalytics ? 'active' : '' ?>">
                        <i class="ri-bar-chart-2-line"></i>
                        <span>Top Performers</span>
                    </a>
                </li>

                <!-- 9. Chapter Meetings -->
                <li class="<?= $isMeetings ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/meetings'); ?>" class="waves-effect <?= $isMeetings ? 'active' : '' ?>">
                        <i class="ri-video-chat-line"></i>
                        <span>Chapter Meetings</span>
                    </a>
                </li>

                <!-- 10. My Chapter -->
                <li class="<?= $isMyChapter ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/mychapter'); ?>" class="waves-effect <?= $isMyChapter ? 'active' : '' ?>">
                        <i class="ri-building-line"></i>
                        <span>My Chapter</span>
                    </a>
                </li>

                <!-- 11. All Chapters -->
                <li class="<?= $isAllChapters ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/chapter'); ?>" class="waves-effect <?= $isAllChapters ? 'active' : '' ?>">
                        <i class="ri-community-line"></i>
                        <span>All Chapters</span>
                    </a>
                </li>

                <!-- 12. Visitor Tracking -->
                <li class="<?= $isVisitor ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/visitor'); ?>" class="waves-effect <?= $isVisitor ? 'active' : '' ?>">
                        <i class="ri-user-follow-line"></i>
                        <span>Visitor Tracking</span>
                    </a>
                </li>

                <!-- 13. Notifications -->
                <li class="<?= $isNotifications ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/notifications'); ?>" class="waves-effect <?= $isNotifications ? 'active' : '' ?>">
                        <i class="ri-notification-3-line"></i>
                        <span>Notifications</span>
                    </a>
                </li>

                <!-- 14. Broadcast -->
                <li class="<?= $isBroadcast ? 'mm-active' : '' ?>">
                    <a href="<?= base_url('member/broadcast'); ?>" class="waves-effect <?= $isBroadcast ? 'active' : '' ?>">
                        <i class="ri-broadcast-line"></i>
                        <span>Broadcast</span>
                    </a>
                </li>

                <!-- 15. EAF Policies -->
                <li>
                    <a href="javascript:void(0);" class="waves-effect d-flex align-items-center justify-content-between">
                        <span>
                            <i class="ri-shield-check-line"></i>
                            <span>EAF Policies</span>
                        </span>
                        <i class="ri-arrow-down-s-line" style="font-size:14px; width:auto; height:auto; color:#94A3B8;"></i>
                    </a>
                </li>

                <!-- 16. Logout (Red Accent at bottom) -->
                <li style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                    <a href="<?= base_url('logout'); ?>" class="waves-effect logout-menu" style="color: #DC2626 !important;">
                        <i class="ri-logout-box-r-line" style="color: #DC2626 !important;"></i>
                        <span style="color: #DC2626 !important; font-weight:600;">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
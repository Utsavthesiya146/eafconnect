 <div class="vertical-menu">

     <!-- LOGO -->
     <div class="navbar-brand-box">
         <a href="<?= base_url(); ?>" class="logo logo-dark">
             <span class="logo-sm">
                 <img src="<?= base_url(); ?>images/logo6.png" alt="logo-sm-dark" width="60" style="border-radius:5px;">
             </span>
             <span class="logo-lg">
                 <img src="<?= base_url(); ?>images/logo6.png" alt="logo-dark" width="60" style="border-radius:5px;">
             </span>
         </a>
         <a href="<?= base_url(); ?>" class="logo logo-light">
             <span class="logo-sm">
                 <img src="<?= base_url(); ?>images/logo6.png" alt="logo-sm-light" width="60" style="border-radius:5px;">
             </span>
             <span class="logo-lg">
                 <img src="<?= base_url(); ?>images/logo6.png" alt="logo-light" width="60" style="border-radius:5px;">
             </span>
         </a>
     </div>
     <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn" id="vertical-menu-btn">
         <i class="ri-menu-2-line align-middle"></i>
     </button>
     <div data-simplebar class="vertical-scroll">

         <!--- Sidemenu -->
         <div id="sidebar-menu">

             <div class="dropdown mx-3 sidebar-user user-dropdown select-dropdown">
                 <button type="button" class="btn btn-light w-100 waves-effect waves-light border-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <span class="d-flex align-items-center">
                         <div class="flex-shrink-0">
                             <div class="avatar-xs rounded-circle flex-shrink-0">
                                 <div class="avatar-title border bg-light text-primary rounded-circle text-uppercase user-sort-name">
                                     <?= strtoupper(mb_substr(session()->get('name'), 0, 1)); ?>
                                 </div>
                             </div>
                         </div>
                         <div class="flex-grow-1 ms-2 text-start">
                             <h6 class="mb-1 fw-medium user-name-text"> <?= session()->get('name'); ?> </h6>
                             <p class="font-size-13 text-muted user-name-sub-text mb-0"> <?= session()->get('role'); ?> </p>
                         </div>
                         <div class="flex-shrink-0 text-end">

                         </div>
                     </span>
                 </button>
             </div>
             <!-- Left Menu Start -->
             <ul class="metismenu list-unstyled" id="side-menu">
                 <li class="menu-title">Menu</li>
                 <li>
                     <a href="<?= base_url(); ?>admin/dashboard" class="waves-effect">
                         <i class="fa fa-gauge-high"></i>
                         <span>Dashboard</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>admin/members" class="waves-effect">
                         <i class="fa fa-users"></i>
                         <span>Members</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>admin/chapter" class="waves-effect">
                         <i class="fa fa-sitemap"></i>
                         <span>Chapters</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>admin/connect" class="waves-effect">
                         <i class="fa fa-handshake-angle"></i>
                         <span>Connects</span>
                     </a>
                 </li>

                 <li class="menu-title">Pages</li>

                 <li>
                     <a href="<?= base_url(); ?>admin/notifications" class="waves-effect">
                         <i class="fa fa-bell"></i>
                         <span>Notifications</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>admin/mitb" class="waves-effect">
                         <i class="fa fa-money-bill-trend-up"></i>
                         <span>MITB</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/f2f" class="waves-effect">
                         <i class="fa fa-user-group"></i>
                         <span>F2F</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/visitor" class="waves-effect">
                         <i class="fa fa-user-check"></i>
                         <span>Visitor Tracking</span>
                     </a>
                 </li>
                 <!-- <li>
                     <a href="<?= base_url(); ?>admin/invitations" class="waves-effect">
                         <i class="fa fa-envelope"></i>
                         <span>Invitations</span>
                     </a>
                 </li> -->
                 <li>
                     <a href="<?= base_url(); ?>admin/meetings" class="waves-effect">
                         <i class="fa fa-calendar-days"></i>
                         <span>Chapter Meetings</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/chapter_attendance" class="waves-effect">
                         <i class="fa-solid fa-clipboard-user"></i>
                         <span>Chapter Attendance</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/my_attendance" class="waves-effect">
                         <i class="fa fa-clipboard-check"></i>
                         <span>Attendance</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>admin/blogs" class="waves-effect">
                         <i class="fa fa-blog"></i>
                         <span>Blogs</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>admin/ads" class="waves-effect">
                         <i class="fa fa-blog"></i>
                         <span>Ads</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/broadcast" class="waves-effect">
                         <i class="fa fa-bullhorn"></i>
                         <span>Broadcast</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/leads" class="waves-effect">
                         <i class="fa fa-file-signature"></i>
                         <span>New Forms</span>
                     </a>
                 </li>
                 <li>
                     <a href="<?= base_url(); ?>admin/contact" class="waves-effect">
                         <i class="fa fa-file-signature"></i>
                         <span>Contact Forms</span>
                     </a>
                 </li>



                 <li class="menu-title">Profile</li>

                 <li>
                     <a href="<?= base_url(); ?>admin/profile" class="waves-effect">
                         <i class="fa fa-id-badge"></i>
                         <span>My Profile</span>
                     </a>
                 </li>

                 <li>
                     <a href="<?= base_url(); ?>logout" class="waves-effect">
                         <i class="fa fa-right-from-bracket"></i>
                         <span>Logout</span>
                     </a>
                 </li>
             </ul>
         </div>
         <!-- Sidebar -->
     </div>

     <div class="dropdown px-3 sidebar-user sidebar-user-info">
         <button type="button" class="btn w-100 px-0 border-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <span class="d-flex align-items-center">
                 <img src="<?= get_member_avatar(session()->get('profile_photo'), session()->get('gender'), session()->get('user_code')); ?>"
                     class="img-fluid header-profile-user rounded-circle"
                     alt="Profile Image"
                     style="object-fit: cover; object-position: top center;">

                 <div class="flex-grow-1 ms-2 text-start">
                     <span class="ms-1 fw-medium user-name-text"><?= session()->get('name'); ?></span>
                 </div>

                 <div class="flex-shrink-0 text-end">
                     <i class="mdi mdi-dots-vertical font-size-16"></i>
                 </div>
             </span>
         </button>
         <div class="dropdown-menu dropdown-menu-end">
             <a class="dropdown-item" href="<?= base_url(); ?>member/profile"><i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
             <div class="dropdown-divider"></div>
             <a class="dropdown-item" href="<?= base_url(); ?>logout"><i class="mdi mdi-lock text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Logout</span></a>
         </div>
     </div>
 </div>
 <!-- Left Sidebar End -->
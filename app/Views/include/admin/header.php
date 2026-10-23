<!-- EAF Connect Design System & Typography (Midnight Navy & Champagne Gold) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
<link rel="stylesheet" href="<?= base_url('assets2/css/eaf-design-system.css') ?>?v=5.0">

<header id="page-topbar">
     <div class="navbar-header">
         <div class="d-flex">
             <!-- LOGO -->
             <div class="navbar-brand-box">
                 <a href="<?= base_url(); ?>" class="logo logo-dark">
                     <span class="logo-sm">
                         <img src="<?= base_url(); ?>images/logo5.png" alt="logo-sm-dark" height="60">
                     </span>
                     <span class="logo-lg">
                         <img src="<?= base_url(); ?>images/logo5.png" alt="logo-dark" height="60">
                     </span>
                 </a>

                 <a href="<?= base_url(); ?>" class="logo logo-light">
                     <span class="logo-sm">
                         <img src="<?= base_url(); ?>images/logo5.png" alt="logo-sm-light" height="60">
                     </span>
                     <span class="logo-lg">
                         <img src="<?= base_url(); ?>images/logo5.png" alt="logo-light" height="60">
                     </span>
                 </a>
             </div>

             <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn" id="vertical-menu-btn">
                 <i class="ri-menu-2-line align-middle"></i>
             </button>

             <!-- start page title -->
             <div class="page-title-box align-self-center d-none d-md-block">
                 <h4 class="page-title mb-0"></h4>
             </div>
             <!-- end page title -->
         </div>

         <div class="d-flex">

             <!-- App Search-->
             <!-- <form class="app-search d-none d-lg-block">
                 <div class="position-relative">
                     <input type="text" class="form-control" placeholder="Search...">
                     <span class="ri-search-line"></span>
                 </div>
             </form> -->

             <div class="dropdown d-inline-block d-lg-none ms-2">
                 <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                     data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="ri-search-line"></i>
                 </button>
                 <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                     aria-labelledby="page-header-search-dropdown">

                     <form class="p-3">
                         <div class="mb-3 m-0">
                             <div class="input-group">
                                 <input type="text" class="form-control" placeholder="Search ...">
                                 <div class="input-group-append">
                                     <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>



             <div class="dropdown d-none d-lg-inline-block ms-1">
                 <button type="button" class="btn header-item noti-icon waves-effect"
                     data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="ri-apps-2-line"></i>
                 </button>
                 <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                     <div class="px-lg-2">
                         <!-- <div class="row g-0">
                             <div class="col">
                                 <a class="dropdown-icon-item" href="#">
                                     <img src="<?= base_url(); ?>assets2/images/brands/github.png" alt="Github">
                                     <span>GitHub</span>
                                 </a>
                             </div>
                             <div class="col">
                                 <a class="dropdown-icon-item" href="#">
                                     <img src="<?= base_url(); ?>assets2/images/brands/bitbucket.png" alt="bitbucket">
                                     <span>Bitbucket</span>
                                 </a>
                             </div>
                             <div class="col">
                                 <a class="dropdown-icon-item" href="#">
                                     <img src="<?= base_url(); ?>assets2/images/brands/dribbble.png" alt="dribbble">
                                     <span>Dribbble</span>
                                 </a>
                             </div>
                         </div>

                         <div class="row g-0">
                             <div class="col">
                                 <a class="dropdown-icon-item" href="#">
                                     <img src="<?= base_url(); ?>assets2/images/brands/dropbox.png" alt="dropbox">
                                     <span>Dropbox</span>
                                 </a>
                             </div>
                             <div class="col">
                                 <a class="dropdown-icon-item" href="#">
                                     <img src="<?= base_url(); ?>assets2/images/brands/mail_chimp.png" alt="mail_chimp">
                                     <span>Mail Chimp</span>
                                 </a>
                             </div>
                             <div class="col">
                                 <a class="dropdown-icon-item" href="#">
                                     <img src="<?= base_url(); ?>assets2/images/brands/slack.png" alt="slack">
                                     <span>Slack</span>
                                 </a>
                             </div>
                         </div> -->
                     </div>
                 </div>
             </div>

             <div class="dropdown d-none d-lg-inline-block ms-1">
                 <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                     <i class="ri-fullscreen-line"></i>
                 </button>
             </div>
             <div class=" d-inline-block">
                 <button type="button" class="btn header-item noti-icon waves-effect"
                     data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="ri-settings-2-line"></i>
                 </button>
                 <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg rounded-4 overflow-hidden"
                     style="min-width:280px;">

                     <!-- Header -->
                     <div class="p-3 text-center text-white"
                         style="background: linear-gradient(135deg, #101B35, #172B50); border-bottom: 2px solid #C6A667;">

                         <a href="<?= base_url(); ?>member/profile">
                             <img src="<?= get_member_avatar(session()->get('profile_photo'), session()->get('gender'), session()->get('user_code')); ?>"
                                 class="rounded-circle border border-2 shadow"
                                 alt="Profile Image"
                                 width="75" height="75"
                                 style="object-fit:cover; object-position:top center; border-color: #C6A667 !important;">
                         </a>

                         <h6 class="mt-2 mb-0 fw-semibold" style="color:#ffffff;">
                             <?= session()->get('name'); ?>
                         </h6>
                         <small class="opacity-75">Welcome Back</small>
                     </div>

                     <!-- Body -->
                     <div class="p-2">
                         <!-- Profile -->
                         <a href="<?= base_url(); ?>member/profile/details/<?= session()->get('user_code'); ?>"
                             class="dropdown-item d-flex align-items-center rounded-3 py-2">
                             <i class="ri-user-line fs-5 text-primary me-2"></i>
                             <span>My Profile</span>
                         </a>
                         <!-- Settings -->
                         <a href="<?= base_url(); ?>member/profile"
                             class="dropdown-item d-flex align-items-center rounded-3 py-2">
                             <i class="ri-settings-3-line fs-5 text-warning me-2"></i>
                             <span>Account Settings</span>
                         </a>

                         <hr class="my-2">

                         <!-- Logout -->
                         <a href="<?= base_url(); ?>/logout"
                             class="dropdown-item d-flex align-items-center rounded-3 py-2 text-danger">
                             <i class="ri-logout-circle-r-line fs-5 me-2"></i>
                             <span>Logout</span>
                         </a>

                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
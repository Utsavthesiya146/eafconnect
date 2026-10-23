<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($blog->blogs_name) ?> - EAF Connect</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Remix Icons -->
    <link href="<?= base_url('assets2/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

    <!-- EAF Enterprise Design System -->
    <link href="<?= base_url('assets2/css/eaf-design-system.css'); ?>?v=5.0" rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: #FAF9F6; color: #101B35;">

    <!-- HEADER -->
    <?= view('include/header.php'); ?>

    <!-- ARTICLE HEADER -->
    <section class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(23,43,80,0.95) 0%, #101B35 80%); color:#fff; border-bottom:1px solid rgba(198,166,103,0.3);">
        <div class="container py-3">
            <div class="max-w-800 mx-auto" style="max-width: 860px;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge" style="background:#C6A667; color:#101B35; font-weight:700;">
                        <?= esc($blog->blogs_category ?: 'Article') ?>
                    </span>
                    <span class="text-white-50 fs-7">•</span>
                    <span class="text-white-50 fs-7"><i class="ri-calendar-line text-warning me-1"></i><?= date('d M Y', strtotime($blog->blogs_created_on)) ?></span>
                </div>
                <h1 class="display-6 fw-bold font-family-outfit text-white mb-3">
                    <?= esc($blog->blogs_name) ?>
                </h1>
                <div class="d-flex align-items-center gap-2 text-white-50 fs-7">
                    <span>By <?= esc($blog->author_name ?: 'EAF Editorial Team') ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ARTICLE CONTENT -->
    <section class="py-5">
        <div class="container py-3">
            <div class="row g-5 justify-content-center">
                <!-- MAIN BODY -->
                <div class="col-lg-8">
                    <div class="eaf-card p-4 p-md-5 mb-4">
                        <?php if (!empty($blog->blogs_image)): ?>
                            <img src="<?= base_url('uploads/blogs/' . $blog->blogs_image) ?>" alt="<?= esc($blog->blogs_name) ?>" class="img-fluid rounded-4 shadow-sm w-100 mb-4" style="max-height:420px; object-fit:cover;" onerror="this.style.display='none'">
                        <?php endif; ?>

                        <div class="fs-5 text-dark mb-4" style="line-height: 1.8;">
                            <?= nl2br($blog->blogs_discription) ?>
                        </div>

                        <?php if (!empty($blog->blogs_discription2)): ?>
                            <div class="mt-4 pt-4 border-top border-light" style="line-height: 1.8; color:#334155;">
                                <?= nl2br($blog->blogs_discription2) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-5 pt-4 border-top border-light d-flex align-items-center justify-content-between">
                            <a href="<?= base_url('blogs') ?>" class="eaf-btn eaf-btn-primary btn-sm">
                                <i class="ri-arrow-left-line"></i>
                                <span>Back to All Articles</span>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#joinCommunityModal" class="eaf-btn eaf-btn-gold btn-sm">
                                <span>Join EAF Network</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    <div class="eaf-card p-4 sticky-top" style="top:90px;">
                        <h5 class="fw-bold font-family-outfit mb-3 pb-2 border-bottom border-light">
                            <i class="ri-fire-fill text-warning me-2"></i>
                            Related Articles
                        </h5>

                        <?php if (!empty($related)): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($related as $item): ?>
                                    <div class="d-flex gap-3 align-items-center">
                                        <?php if (!empty($item->blogs_image)): ?>
                                            <img src="<?= base_url('uploads/blogs/' . $item->blogs_image) ?>" alt="<?= esc($item->blogs_name) ?>" style="width:70px; height:60px; object-fit:cover; border-radius:8px;" onerror="this.src='<?= base_url('images/logo5.png') ?>'">
                                        <?php else: ?>
                                            <div style="width:70px; height:60px; background:#101B35; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff;">
                                                <i class="ri-article-line"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-1 fw-bold fs-7" style="font-size:13px;">
                                                <a href="<?= base_url('blogs/details/' . $item->slug) ?>" class="text-decoration-none" style="color:#101B35;">
                                                    <?= esc(character_limiter($item->blogs_name, 45)) ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted" style="font-size:11px;">
                                                <?= date('d M Y', strtotime($item->blogs_created_on)) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted fs-7 mb-0">No related articles found.</p>
                        <?php endif; ?>

                        <div class="mt-4 pt-3 border-top border-light">
                            <div class="p-3 rounded-3 text-center" style="background:rgba(16,27,53,0.04); border:1px solid #E2E8F0;">
                                <h6 class="fw-bold mb-1">Want to Scale Your Business?</h6>
                                <p class="text-muted fs-7 mb-3" style="font-size:12px;">Connect with verified founders and build category leadership.</p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#joinCommunityModal" class="eaf-btn eaf-btn-gold btn-sm w-100 justify-content-center">
                                    Apply for Seat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <?= view('include/footer.php'); ?>

</body>
</html>
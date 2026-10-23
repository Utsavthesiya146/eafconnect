<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Insights & Articles - EAF Connect</title>
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

    <!-- HERO STRIP -->
    <section class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(23,43,80,0.95) 0%, #101B35 80%); color:#fff; border-bottom:1px solid rgba(198,166,103,0.3);">
        <div class="container py-3 text-center">
            <span class="eaf-badge eaf-badge-gold mb-2">Thought Leadership</span>
            <h1 class="display-5 fw-bold font-family-outfit mb-2 text-white">EAF Business Insights</h1>
            <p class="text-white-50 fs-6 max-w-600 mx-auto mb-0" style="max-width: 620px;">
                Expert strategies, scale-up frameworks, and referral networking insights curated for entrepreneurs and business leaders.
            </p>
        </div>
    </section>

    <!-- BLOGS GRID -->
    <section class="py-5">
        <div class="container py-3">
            <div class="row g-4">
                <?php if (!empty($blogs)): ?>
                    <?php foreach ($blogs as $row): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="eaf-card h-100 d-flex flex-column">
                                <div class="position-relative overflow-hidden" style="height: 220px; background:#101B35;">
                                    <?php if (!empty($row->blogs_image)): ?>
                                        <img src="<?= base_url('uploads/blogs/' . $row->blogs_image) ?>" alt="<?= esc($row->blogs_name) ?>" class="w-100 h-100 object-fit-cover" onerror="this.src='<?= base_url('images/logo5.png') ?>'">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50">
                                            <i class="ri-article-line fs-1"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="badge position-absolute top-0 end-0 m-3" style="background:#C6A667; color:#101B35; font-weight:700;">
                                        <?= esc($row->blogs_category ?: 'Networking') ?>
                                    </span>
                                </div>

                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <h4 class="fs-5 fw-bold font-family-outfit mb-2 text-navy">
                                        <a href="<?= base_url('blogs/details/' . $row->slug) ?>" class="text-decoration-none" style="color:#101B35;">
                                            <?= esc($row->blogs_name) ?>
                                        </a>
                                    </h4>

                                    <p class="text-muted fs-7 mb-4 flex-grow-1" style="font-size: 14px;">
                                        <?= word_limiter(strip_tags($row->blogs_title), 20) ?>
                                    </p>

                                    <div class="mt-auto pt-3 border-top border-light d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="ri-calendar-line text-warning me-1"></i>
                                            <?= date('d M Y', strtotime($row->blogs_created_on)) ?>
                                        </small>
                                        <a href="<?= base_url('blogs/details/' . $row->slug) ?>" class="eaf-btn eaf-btn-primary eaf-btn-sm">
                                            <span>Read Article</span>
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 border border-light shadow-sm max-w-500 mx-auto" style="max-width: 500px;">
                            <i class="ri-newspaper-line fs-1 text-muted mb-3 d-block"></i>
                            <h4 class="fw-bold font-family-outfit mb-2">No Articles Published Yet</h4>
                            <p class="text-muted fs-7 mb-4">Our editorial board is curating the latest strategic articles. Check back shortly.</p>
                            <a href="<?= base_url(); ?>" class="eaf-btn eaf-btn-primary">
                                <i class="ri-home-4-line"></i> Return Home
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PAGINATION -->
            <?php if (!empty($pager)): ?>
                <div class="mt-5 d-flex justify-content-center">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <?= view('include/footer.php'); ?>

</body>
</html>
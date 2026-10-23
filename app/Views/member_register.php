<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Member Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>images/favicon.png">
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="auth-maintenance d-flex align-items-center min-vh-100">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-3">
                                <div class="card my-auto overflow-hidden">
                                    <div class="row g-0">
                                        <div class="col-lg-6">
                                            <div class="bg-overlay bg-primary"></div>
                                            <div class="h-100 bg-auth align-items-end">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="p-lg-5 p-4">
                                                <div>
                                                    <div class="text-center mt-1">
                                                        <h4 class="font-size-18">Register account</h4>
                                                        <p class="text-muted">Get your free Tocly account now.</p>
                                                    </div>
                                                    <!-- <form action="<?= base_url('save-register') ?>" method="post" class="auth-input">
                                                        <?= csrf_field() ?>
                                                        <div class="mb-2">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control"
                                                                placeholder="Enter email" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">Username</label>
                                                            <input type="text" name="name" class="form-control"
                                                                placeholder="Enter username" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">Phone</label>
                                                            <input type="text" name="phone" class="form-control"
                                                                placeholder="Enter phone">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Password</label>
                                                            <input type="password" name="password"
                                                                class="form-control"
                                                                placeholder="Enter password" required>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0">
                                                                By registering you agree to the
                                                                <a href="#" class="text-primary">Terms of Use</a>
                                                            </p>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button class="btn btn-primary w-100" type="submit">
                                                                Register
                                                            </button>
                                                        </div>
                                                    </form> -->
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <p class="mb-0">Already have an account ? <a href="<?= base_url() ?>member_login" class="fw-medium text-primary"> Login</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- JAVASCRIPT -->
    <script src="<?= base_url(); ?>assets2/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url(); ?>assets2/js/app.js"></script>
</body>

</html>
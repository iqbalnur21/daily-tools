<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" href="<?= $assetsPath ?>/template_stisla/assets/img/counting.png" type="image/png">
    <title>Pembukuan Kavling - Login</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/@fontawesome/fontawesome-free/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap-social/bootstrap-social.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/style.css">
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/components.css">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="<?= $assetsPath ?>/template_stisla/assets/img/avatar/avatar-1.png" alt="logo" width="100" class="shadow-light rounded-circle">
                        </div>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Login</h4>
                            </div>
                            <div class="card-body">
                                <?php if (session()->getFlashdata('success')) { ?>
                                    <div class="alert alert-success alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismissible="alert">x</button>
                                            <b>Success !</b>
                                            <?= session()->getFlashdata('success') ?>
                                        </div>
                                    </div>
                                <?php } else if (session()->getFlashdata('error')) { ?>
                                    <div class="alert alert-danger alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismissible="alert">x</button>
                                            <b>Error !</b>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    </div>
                                <?php } else if (session()->has('error')) { ?>
                                    <div class="alert alert-danger alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismissible="alert">x</button>
                                            <b>Error !</b>
                                            <?php echo session('error'); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <form method="POST" action="<?= site_url('Auth/loginProcess') ?>" class="needs-validation" novalidate="">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input id="username" type="username" class="form-control" name="username" tabindex="1" required autofocus>
                                        <div class="invalid-feedback">
                                            Please fill in your Username
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <div class="float-right">
                                                <a href="auth-forgot-password.html" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                        <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                        <div class="invalid-feedback">
                                            please fill in your password
                                        </div>
                                    </div>

                                    <!-- <div class="g-recaptcha" data-sitekey="6LeyuoQoAAAAAExTTPCCh0e6rYKert_y3rzRoSs2"></div> -->

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; Balrafa 2025
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/popper.js/dist/popper.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/tooltip.js/dist/tooltip.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/nicescroll/dist/jquery.nicescroll.min.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/moment/moment.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/assets/js/stisla.js"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
    <!-- <script src="https://www.google.com/recaptcha/enterprise.js?render=6LeyuoQoAAAAAExTTPCCh0e6rYKert_y3rzRoSs2"></script>

    <script>
        function onClick(e) {
            e.preventDefault();
            grecaptcha.enterprise.ready(async () => {
                const token = await grecaptcha.enterprise.execute('6LeyuoQoAAAAAExTTPCCh0e6rYKert_y3rzRoSs2', {
                    action: 'login'
                });
                // IMPORTANT: The 'token' that results from execute is an encrypted response sent by
                // reCAPTCHA Enterprise to the end user's browser.
                // This token must be validated by creating an assessment.
                // See https://cloud.google.com/recaptcha-enterprise/docs/create-assessment
            });
        }
    </script> -->
    <!-- Template JS File -->
    <script src="<?= $assetsPath ?>/template_stisla/assets/js/scripts.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/assets/js/custom.js"></script>
</body>

</html>
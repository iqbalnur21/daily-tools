<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" href="<?= $assetsPath ?>/template_stisla/assets/img/counting.png" type="image/png">
    <title><?= $this->renderSection('title') ?></title>
    <?= $this->renderSection('style') ?>
</head>

<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar justify-content-between">
                <ul class="navbar-nav mr-3">
                    <li><a href="<?= site_url('KbCalculator') ?>" class="decoration-none">Home</a></li>
                </ul>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="<?= $assetsPath ?>/template_stisla/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, <?= session('username') ?></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?= site_url('Auth/logout') ?>" class="dropdown-item has-icon text-danger" data-confirm="Keluar ?|Yakin Ingin Keluar ?" data-confirm-yes="Logout()" id="Logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                <?= $this->renderSection('content') ?>
            </div>

            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2025 <div class="bullet"></div> Design By <a href="https://balrafa.tech/">Balrafa Tech</a>
                </div>
                <div class="footer-right">

                </div>
            </footer>
        </div>
    </div>

    <?= $this->renderSection('script') ?>

</body>
</html>
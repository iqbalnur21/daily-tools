<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" href="<?= $assetsPath ?>/template_stisla/assets/img/counting.png" type="image/png">
    <title>Aplikasi Perhitungan</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/@fontawesome/fontawesome-free/css/all.min.css">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/style.css">
    <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/custom.css">
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

<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar justify-content-end">

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
                <section class="section">
                    <div class="section-header justify-content-center">
                        <h1>Aplikasi Perhitungan</h1>
                    </div>
                    <div class="section-body">
                        <?php
                        $count = 0;
                        foreach ($counters as $key => $value) { ?>
                            <?php if ($value['counter_name'] == "Hutang Galon" || $value['counter_name'] == "Ganti Puasa") { ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= $value['counter_name'] ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row" style="justify-content: space-evenly;">
                                                <p class="text-muted">Last Update: <?= timeFormat($value['updated_at']) ?></p>
                                            </div>
                                            <div class="row" style="justify-content: space-evenly;">
                                                <button id="minus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span id="counter-<?= $value['counter_id'] ?>" class="counter-text"><?= $value['amount'] ?></span>
                                                <button id="plus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="card <?= $count != 4 ? 'mb-0' : '' ?>">
                                    <?php
                                    $count++;
                                    if ($count == 1) { ?>
                                        <div class="card-header">
                                            <h4>Qada Solat</h4>
                                        </div>
                                    <?php } ?>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row" style="justify-content: space-evenly;">
                                                <p class="text-muted">Last Update: <?= timeFormat($value['updated_at']) ?></p>
                                            </div>
                                            <div class="row" style="justify-content: space-evenly;">
                                                <div>
                                                    <b id="title-1" class="counter-text"><?= $value['counter_name'] ?></b>
                                                </div>
                                                <div style="display: contents;">
                                                    <button id="minus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <span id="counter-<?= $value['counter_id'] ?>" class="counter-text"><?= $value['amount'] ?></span>
                                                    <button id="plus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($count == 1) { ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </section>
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

    <!-- General JS Scripts -->
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <!-- <script src="<?= $assetsPath ?>/template_stisla/node_modules/tooltip.js/dist/tooltip.js"></script> -->
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/nicescroll/dist/jquery.nicescroll.min.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/node_modules/moment/moment.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/assets/js/stisla.js"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="<?= $assetsPath ?>/template_stisla/assets/js/scripts.js"></script>
    <script src="<?= $assetsPath ?>/template_stisla/assets/js/custom.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let timeout = null;

            $(".counter-btn").click(function() {
                let buttonId = $(this).attr("id");
                let counterId = buttonId.split('-')[1]; // Extract ID from button ID
                let counterSpan = $("#counter-" + counterId);
                let currentValue = parseInt(counterSpan.text());

                // Update counter value
                if (buttonId.includes("plus")) {
                    currentValue += 1;
                } else if (buttonId.includes("minus")) {
                    currentValue -= 1;
                }

                counterSpan.text(currentValue);

                // Reset previous timeout
                clearTimeout(timeout);

                // Start new timeout (2 seconds)
                timeout = setTimeout(function() {
                    sendAllCounterData();
                }, 2000);
            });

            function sendAllCounterData() {
                let data = {};

                $(".main-content span").each(function() {
                    let spanId = $(this).attr("id");
                    let spanText = $(this).text();
                    if (spanId) {
                        data[spanId] = spanText;
                    }
                });
                console.log('data:', data);
                $.ajax({
                    url: "<?= site_url('Home/update') ?>",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        showToast("All counters updated successfully!");
                    },
                    error: function(xhr, status, error) {
                        showToast("Error updating counters!");
                    }
                });
            }

            function showToast(message) {
                $("body").append(`<div class="toast-notification">${message}</div>`);
                $(".toast-notification").fadeIn().delay(2000).fadeOut(function() {
                    $(this).remove();
                });
            }
        });
    </script>
</body>
<style>
    .counter-text {
        text-align: center;
        align-self: center;
        font-weight: bold;
        font-size: 20px
    }

    .counter-btn {
        height: 40px;
    }

    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        display: none;
        z-index: 9999;
    }
</style>

</html>
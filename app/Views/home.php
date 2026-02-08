<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
Aplikasi Perhitungan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/@fontawesome/fontawesome-free/css/all.min.css">

<!-- CSS Libraries -->

<!-- Template CSS -->
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/style.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/custom.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/components.css">

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
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
    <!-- Modul Perhitungan Biaya Parkir Otomatis -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header text-white">
            <h4>Hitung Biaya Parkir</h4>
        </div>
        <div class="card-body">
            <div class="form-group text-center">
                <label for="jamMasuk"><strong>Jam Masuk (HH.MM)</strong></label>
                <input type="text" id="jamMasuk" class="form-control text-center mx-auto" maxlength="5"
                    placeholder="00.00" style="width:150px; font-size:1.2rem;"
                    inputmode="numeric" pattern="[0-9]*">
            </div>

            <div id="hasilParkir" class="text-center mt-4" style="display:none;">
                <h5 class="mb-2">Durasi Parkir: <span id="durasiParkir" class="text-primary"></span></h5>
                <h4 class="text-success">Total Biaya: <span id="biayaParkir"></span></h4>
                <p class="text-muted mt-2" id="waktuSekarang"></p>
            </div>
        </div>
    </div>

</section>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

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
<script>
    $(document).ready(function() {
        const $input = $('#jamMasuk');
        $input.focus(); // Fokus otomatis ke input saat halaman dibuka

        // Format otomatis HH.MM saat mengetik
        $input.on('input', function() {
            let val = $(this).val().replace(/\D/g, ''); // hanya angka
            if (val.length >= 3) {
                val = val.slice(0, 2) + '.' + val.slice(2, 4);
            }
            $(this).val(val);

            // Jalankan hitung otomatis jika sudah 5 karakter (HH.MM)
            if (val.length === 5) {
                hitungBiayaParkir();
            }
        });

        function hitungBiayaParkir() {
            let jamMasuk = $input.val();

            if (!/^\d{2}\.\d{2}$/.test(jamMasuk)) {
                showToast("Format jam tidak valid! Gunakan HH.MM");
                return;
            }

            let [jam, menit] = jamMasuk.split('.').map(Number);
            if (jam > 23 || menit > 59) {
                showToast("Jam atau menit tidak valid!");
                return;
            }

            let now = new Date();
            let jamSekarang = now.getHours();
            let menitSekarang = now.getMinutes();
            let waktuSekarangStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            // Hitung total menit
            let totalMenitMasuk = jam * 60 + menit;
            let totalMenitSekarang = jamSekarang * 60 + menitSekarang;

            // Jika jam masuk > jam sekarang (berarti dari hari sebelumnya)
            if (totalMenitMasuk > totalMenitSekarang) {
                totalMenitSekarang += 24 * 60;
            }

            let selisihMenit = totalMenitSekarang - totalMenitMasuk;
            let jamTerparkir = Math.ceil(selisihMenit / 60);

            // Hitung biaya parkir
            let biaya = 0;
            if (jamTerparkir <= 1) {
                biaya = 2000;
            } else {
                biaya = 2000 + (jamTerparkir - 1) * 1000;
                if (biaya > 8000) biaya = 8000;
            }

            // Tampilkan hasil
            $('#durasiParkir').text(jamTerparkir + ' jam');
            $('#biayaParkir').text('Rp ' + biaya.toLocaleString('id-ID'));
            $('#waktuSekarang').text('Waktu Sekarang: ' + waktuSekarangStr);
            $('#hasilParkir').fadeIn();
        }

        // Fungsi notifikasi toast
        function showToast(message) {
            $("body").append(`<div class="toast-notification">${message}</div>`);
            $(".toast-notification").fadeIn().delay(2000).fadeOut(function() {
                $(this).remove();
            });
        }
    });
</script>
<?= $this->endSection() ?>
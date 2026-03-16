<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
Aplikasi Perhitungan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/@fontawesome/fontawesome-free/css/all.min.css">

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

    <?php foreach ($counters as $saldoData): ?>
        <?php if (stripos($saldoData['counter_name'], 'Saldo') !== false): ?>
            <div class="card shadow-sm border-primary mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white">Informasi <?= esc($saldoData['counter_name']) ?></h4>
                </div>
                <div class="card-body text-center">
                    <h1 class="text-primary mb-4">Rp <span id="saldo-amount-<?= $saldoData['counter_id'] ?>"><?= number_format($saldoData['amount'], 0, ',', '.') ?></span></h1>

                    <div class="form-group">
                        <label>Nominal (otomatis bernilai ribuan)</label>
                        <div class="input-group mb-3" style="max-width: 350px; margin: auto;">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">Rp</span>
                            </div>
                            <input type="number" id="input-saldo-<?= $saldoData['counter_id'] ?>" class="form-control text-center text-lg font-weight-bold" placeholder="Contoh: 50" style="font-size: 1.2rem;">
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <button class="btn btn-danger btn-lg mx-2 btn-saldo-action" data-action="minus" data-id="<?= $saldoData['counter_id'] ?>" style="min-width: 120px;">
                            <i class="fas fa-minus"></i> Kurangi
                        </button>
                        <button class="btn btn-success btn-lg mx-2 btn-saldo-action" data-action="plus" data-id="<?= $saldoData['counter_id'] ?>" style="min-width: 120px;">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>

                    <div id="saldo-last-calc-container-<?= $saldoData['counter_id'] ?>" class="alert alert-light border text-left mx-auto position-relative" style="display: <?= $saldoData['last_calculation'] ? 'block' : 'none' ?>; max-width: 350px; font-size: 16px; font-weight: bold; color: #34395e; background-color:#f9f9f9;">

                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute btn-copy-saldo" data-id="<?= $saldoData['counter_id'] ?>" style="top: 10px; right: 10px;padding: 10px;" title="Copy Data">
                            <i class="fas fa-copy" style="font-size: 60px"></i>
                        </button>

                        <div id="saldo-last-calc-<?= $saldoData['counter_id'] ?>" style="white-space: pre-line; padding-right: 30px;">
                            <?= $saldoData['last_calculation'] ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="section-body">
        <?php
        $count = 0;
        foreach ($counters as $key => $value) {
            // Skip ALL counters that contain "Saldo"
            if (stripos($value['counter_name'], 'Saldo') !== false) continue;
        ?>
            <?php if ($value['counter_name'] == "Hutang Galon" || $value['counter_name'] == "Ganti Puasa" || $value['counter_name'] == "Ganti Puasa Nia") { ?>
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

<script src="<?= $assetsPath ?>/template_stisla/node_modules/jquery/dist/jquery.min.js"></script>
<script src="<?= $assetsPath ?>/template_stisla/node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= $assetsPath ?>/template_stisla/node_modules/nicescroll/dist/jquery.nicescroll.min.js"></script>
<script src="<?= $assetsPath ?>/template_stisla/node_modules/moment/moment.js"></script>
<script src="<?= $assetsPath ?>/template_stisla/assets/js/stisla.js"></script>

<script src="<?= $assetsPath ?>/template_stisla/assets/js/scripts.js"></script>
<script src="<?= $assetsPath ?>/template_stisla/assets/js/custom.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // --- EVENT KLIK TOMBOL PLUS / MINUS SALDO ---
        $('.btn-saldo-action').click(function() {
            let action = $(this).data('action');
            let counterId = $(this).data('id');
            let inputVal = $('#input-saldo-' + counterId).val();

            if (!inputVal || inputVal <= 0) {
                showToast('Masukkan nominal Saldo yang valid!');
                $('#input-saldo-' + counterId).focus();
                return;
            }

            // Disable tombol spesifik pada card ini sesaat agar tidak double click
            let buttons = $('.btn-saldo-action[data-id="' + counterId + '"]');
            buttons.prop('disabled', true);

            $.ajax({
                url: "<?= site_url('Home/updateSaldo') ?>",
                type: "POST",
                data: {
                    counter_id: counterId,
                    action: action,
                    amount: inputVal
                },
                success: function(response) {
                    if (response.success) {
                        // Update UI nominal text & history berdasarkan ID dinamis
                        $('#saldo-amount-' + response.counter_id).text(response.new_amount_format);
                        $('#saldo-last-calc-' + response.counter_id).text(response.last_calculation);
                        $('#saldo-last-calc-container-' + response.counter_id).fadeIn();
                        $('#input-saldo-' + response.counter_id).val(''); // Kosongkan input

                        let txtAction = action === 'plus' ? 'ditambahkan' : 'dikurangi';
                        showToast(`Saldo berhasil ${txtAction}!`);
                    } else {
                        showToast(response.message || 'Gagal update data');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan pada server!');
                },
                complete: function() {
                    // Enable kembali tombol
                    buttons.prop('disabled', false);
                }
            });
        });

        // --- EVENT KLIK TOMBOL COPY ---
        $('.btn-copy-saldo').click(function() {
            let counterId = $(this).data('id');
            // Ambil innerText murni agar format baris baru (Enter / \n) tetap terjaga
            let textElement = document.getElementById('saldo-last-calc-' + counterId);

            if (!textElement) return;

            let textToCopy = textElement.innerText.trim();

            if (!textToCopy) {
                showToast('Tidak ada data untuk dicopy!');
                return;
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    showToast('Data berhasil dicopy!');
                }).catch(function() {
                    fallbackCopyTextToClipboard(textToCopy);
                });
            } else {
                fallbackCopyTextToClipboard(textToCopy);
            }
        });

        // --- FUNGSI FALLBACK COPY (KHUSUS MOBILE/IOS) ---
        function fallbackCopyTextToClipboard(text) {
            let textArea = document.createElement("textarea");
            textArea.value = text;

            // Hindari layar scrolling otomatis ke bawah saat textarea dibuat
            textArea.style.top = "-30vh";
            textArea.style.left = "-100vw";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                let successful = document.execCommand('copy');
                if (successful) {
                    showToast('Data berhasil dicopy!');
                } else {
                    showToast('Gagal copy data!');
                }
            } catch (err) {
                showToast('Browser tidak mendukung copy otomatis');
            }

            // Hapus textarea setelah selesai
            document.body.removeChild(textArea);
        }
    });
</script>
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
        // $input.focus(); // Fokus otomatis ke input saat halaman dibuka

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
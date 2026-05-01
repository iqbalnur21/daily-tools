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
    /* ── General ───────────────────────────────────────────── */
    .counter-text {
        text-align: center;
        align-self: center;
        font-weight: bold;
        font-size: 20px;
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

    /* ── Series Tracker ─────────────────────────────────────── */
    .series-card {
        border-radius: 10px;
        overflow: hidden;
    }

    .series-card .card-header {
        background: linear-gradient(135deg, #6777ef, #4e5fe8);
        color: #fff;
    }

    .series-card .card-header h4 {
        color: #fff;
        margin: 0;
    }

    /* Episode buttons */
    .ep-btn {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: 2px solid #dee2e6;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
    }

    .ep-btn:hover {
        border-color: #adb5bd;
        background: #e9ecef;
    }

    .ep-btn.done {
        background: #28a745;
        border-color: #1e7e34;
        color: #fff;
    }

    /* Season row */
    .season-block {
        margin-top: 12px;
        margin-bottom: 12px;
    }

    .season-label {
        font-size: 12px;
        font-weight: 700;
        color: #6777ef;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 5px;
    }

    .ep-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
    }

    /* Progress bar mini */
    .series-progress-bar {
        height: 4px;
        border-radius: 2px;
        background: #e9ecef;
        overflow: hidden;
        margin-top: 6px;
    }

    .series-progress-bar .fill {
        height: 100%;
        background: #28a745;
        border-radius: 2px;
        transition: width .3s;
    }

    /* Modal season form */
    .season-form-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .season-form-row .season-num-label {
        min-width: 72px;
        font-weight: 600;
        font-size: 13px;
    }

    .btn-add-season-row {
        font-size: 12px;
    }

    .btn-remove-season {
        color: #dc3545;
        background: transparent;
        border: none;
        padding: 0 4px;
        font-size: 16px;
    }

    /* Rating stars display */
    .rating-display {
        color: #ffc107;
        font-size: 13px;
    }

    /* Notes snippet */
    .series-notes {
        font-size: 12px;
        color: #aaa;
        font-style: italic;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 260px;
    }

    /* Empty state */
    .series-empty {
        text-align: center;
        padding: 40px 20px;
        color: #aaa;
    }

    .series-empty i {
        font-size: 48px;
        margin-bottom: 12px;
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header justify-content-center">
        <h1>Aplikasi Perhitungan</h1>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         KOMPONEN SALDO  (sudah ada, tidak berubah)
    ════════════════════════════════════════════════════════ -->
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
                            <div class="input-group-prepend"><span class="input-group-text font-weight-bold">Rp</span></div>
                            <input type="number" id="input-saldo-<?= $saldoData['counter_id'] ?>" class="form-control text-center text-lg font-weight-bold" placeholder="Contoh: 50" style="font-size: 1.2rem;">
                            <div class="input-group-append"><span class="input-group-text font-weight-bold">.000</span></div>
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
                    <div id="saldo-last-calc-container-<?= $saldoData['counter_id'] ?>"
                        class="alert alert-light border text-left mx-auto position-relative"
                        style="display: <?= $saldoData['last_calculation'] ? 'block' : 'none' ?>; max-width: 350px; font-size: 16px; font-weight: bold; color: #34395e; background-color:#f9f9f9;">
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute btn-copy-saldo" data-id="<?= $saldoData['counter_id'] ?>" style="top:10px;right:10px;padding:10px;" title="Copy Data">
                            <i class="fas fa-copy" style="font-size:60px"></i>
                        </button>
                        <div id="saldo-last-calc-<?= $saldoData['counter_id'] ?>" style="white-space:pre-line;padding-right:30px;">
                            <?= $saldoData['last_calculation'] ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- ═══════════════════════════════════════════════════════
         KOMPONEN COUNTER (Hutang / Ganti Puasa / Qada Solat)
    ════════════════════════════════════════════════════════ -->
    <div class="section-body">
        <?php
        $count = 0;
        foreach ($counters as $key => $value):
            if (stripos($value['counter_name'], 'Saldo') !== false) continue;
            if (stripos($value['counter_name'], 'Parkir') !== false) continue;
        ?>
            <?php if (
                $value['counter_name'] == "Hutang Galon" ||
                $value['counter_name'] == "Ganti Puasa"  ||
                $value['counter_name'] == "Ganti Puasa Nia"
            ): ?>
                <div class="card">
                    <div class="card-header">
                        <h4><?= $value['counter_name'] ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row" style="justify-content:space-evenly;">
                                <p class="text-muted">Last Update: <?= timeFormat($value['updated_at']) ?></p>
                            </div>
                            <div class="row" style="justify-content:space-evenly;">
                                <button id="minus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle"><i class="fas fa-minus"></i></button>
                                <span id="counter-<?= $value['counter_id'] ?>" class="counter-text"><?= $value['amount'] ?></span>
                                <button id="plus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card <?= $count != 4 ? 'mb-0' : '' ?>">
                    <?php $count++;
                    if ($count == 1): ?>
                        <div class="card-header">
                            <h4>Qada Solat</h4>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row" style="justify-content:space-evenly;">
                                <p class="text-muted">Last Update: <?= timeFormat($value['updated_at']) ?></p>
                            </div>
                            <div class="row" style="justify-content:space-evenly;">
                                <div><b class="counter-text"><?= $value['counter_name'] ?></b></div>
                                <div style="display:contents;">
                                    <button id="minus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle"><i class="fas fa-minus"></i></button>
                                    <span id="counter-<?= $value['counter_id'] ?>" class="counter-text"><?= $value['amount'] ?></span>
                                    <button id="plus-<?= $value['counter_id'] ?>" class="counter-btn btn btn-primary rounded-circle"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         KOMPONEN PARKIR  (hide jika disabled = 1 di DB)
    ════════════════════════════════════════════════════════ -->
    <?php
    $parkirData = null;
    foreach ($counters as $c) {
        if (stripos($c['counter_name'], 'Parkir') !== false) {
            $parkirData = $c;
            break;
        }
    }
    if ($parkirData !== null):
    ?>
        <div class="card mt-4 shadow-sm">
            <div class="card-header text-white">
                <h4>Hitung Biaya Parkir</h4>
            </div>
            <div class="card-body">
                <div class="form-group text-center">
                    <label for="jamMasuk"><strong>Jam Masuk (HH.MM)</strong></label>
                    <input type="text" id="jamMasuk" class="form-control text-center mx-auto" maxlength="5"
                        placeholder="00.00" style="width:150px;font-size:1.2rem;" inputmode="numeric" pattern="[0-9]*">
                </div>
                <div id="hasilParkir" class="text-center mt-4" style="display:none;">
                    <h5 class="mb-2">Durasi Parkir: <span id="durasiParkir" class="text-primary"></span></h5>
                    <h4 class="text-success">Total Biaya: <span id="biayaParkir"></span></h4>
                    <p class="text-muted mt-2" id="waktuSekarang"></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════════
         KOMPONEN SERIES TRACKER
    ════════════════════════════════════════════════════════ -->
    <div class="card series-card mt-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-tv mr-2"></i> Series Tracker</h4>
            <button class="btn btn-light btn-sm font-weight-bold" id="btn-open-add-series">
                <i class="fas fa-plus mr-1"></i> Tambah
            </button>
        </div>
        <div class="card-body" id="series-list-container">
            <div class="series-empty" id="series-empty-state">
                <i class="fas fa-film"></i>
                Belum ada data. Klik <strong>Tambah</strong> untuk mulai mencatat.
            </div>
        </div>
    </div>

</section>

<!-- ═══════════════════════════════════════════════════════════
     MODAL: TAMBAH / EDIT SERIES
════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalSeries" tabindex="-1" role="dialog" aria-labelledby="modalSeriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalSeriesLabel">Tambah Series</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="series-edit-id" value="">

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label class="font-weight-bold">Judul <span class="text-danger">*</span></label>
                        <input type="text" id="series-title" class="form-control" placeholder="Contoh: Attack on Titan">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label class="font-weight-bold">Catatan</label>
                        <input type="text" id="series-notes" class="form-control" placeholder="Opsional: platform, link, dll">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Rating (1–10)</label>
                        <input type="number" id="series-rating" class="form-control" min="1" max="10" placeholder="Opsional">
                    </div>
                </div>

                <div class="form-row" id="watched-input-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Sudah nonton sampai Season</label>
                        <input type="number" id="series-watched-season" class="form-control" value="1" min="1">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Episode</label>
                        <input type="number" id="series-watched-episode" class="form-control" value="1" min="1">
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="font-weight-bold mb-0">Seasons &amp; Episode Total</label>
                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-season-row" id="btn-add-season">
                        <i class="fas fa-plus mr-1"></i> Tambah Season
                    </button>
                </div>
                <div id="seasons-form-container">
                    <!-- Season rows diisi JS -->
                </div>

                <!-- ═════ TAMBAHKAN SECTION INI ═════ -->
                <hr id="divider-disabled-series" style="border-top: 2px dashed #c8cbd5; margin: 30px 0 20px 0;">
                <div id="section-disabled-series">
                    <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-archive"></i> Arsip Series (Disabled)</h6>
                    <div id="disabled-series-list" class="list-group">
                        <!-- List dari DB diisi via JS -->
                    </div>
                </div>
                <!-- ═════════════════════════════════ -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save-series">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODAL: KONFIRMASI HAPUS
════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDeleteSeries" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">Hapus Series?</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus <strong id="delete-series-title"></strong>?</p>
                <p class="text-muted small">Semua data season dan episode akan ikut terhapus.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

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

<script>
    // ─────────────────────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const bg = type === 'danger' ? '#dc3545' : (type === 'warning' ? '#ffc107' : '#28a745');
        const $t = $(`<div class="toast-notification" style="background:${bg}">${message}</div>`);
        $('body').append($t);
        $t.fadeIn().delay(2200).fadeOut(function() {
            $(this).remove();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────────
    //  SALDO & COUNTER JS (Sudah ada, dibiarkan seperti asli...)
    // ─────────────────────────────────────────────────────────────────────────────
    $(document).ready(function() {
        $('.btn-saldo-action').click(function() {
            const action = $(this).data('action');
            const counterId = $(this).data('id');
            const inputVal = $('#input-saldo-' + counterId).val();
            if (!inputVal || inputVal <= 0) {
                showToast('Masukkan nominal Saldo yang valid!', 'warning');
                $('#input-saldo-' + counterId).focus();
                return;
            }
            const buttons = $('.btn-saldo-action[data-id="' + counterId + '"]');
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
                        $('#saldo-amount-' + response.counter_id).text(response.new_amount_format);
                        $('#saldo-last-calc-' + response.counter_id).text(response.last_calculation);
                        $('#saldo-last-calc-container-' + response.counter_id).fadeIn();
                        $('#input-saldo-' + response.counter_id).val('');
                        showToast(`Saldo berhasil ${action === 'plus' ? 'ditambahkan' : 'dikurangi'}!`);
                    } else {
                        showToast(response.message || 'Gagal update data', 'danger');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan pada server!', 'danger');
                },
                complete: function() {
                    buttons.prop('disabled', false);
                }
            });
        });

        $('.btn-copy-saldo').click(function() {
            const counterId = $(this).data('id');
            const textElement = document.getElementById('saldo-last-calc-' + counterId);
            if (!textElement) return;
            const textToCopy = textElement.innerText.trim();
            if (!textToCopy) {
                showToast('Tidak ada data untuk dicopy!', 'warning');
                return;
            }
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy)
                    .then(() => showToast('Data berhasil dicopy!'))
                    .catch(() => fallbackCopy(textToCopy));
            } else {
                fallbackCopy(textToCopy);
            }
        });

        function fallbackCopy(text) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;top:-30vh;left:-100vw;';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                document.execCommand('copy') ? showToast('Data berhasil dicopy!') : showToast('Gagal copy!', 'danger');
            } catch (e) {
                showToast('Browser tidak mendukung copy otomatis', 'danger');
            }
            document.body.removeChild(ta);
        }
    });

    $(document).ready(function() {
        let timeout = null;
        $(".counter-btn").click(function() {
            const buttonId = $(this).attr("id");
            const counterId = buttonId.split('-')[1];
            const $span = $("#counter-" + counterId);
            let val = parseInt($span.text());
            val += buttonId.includes("plus") ? 1 : -1;
            $span.text(val);
            clearTimeout(timeout);
            timeout = setTimeout(sendAllCounterData, 2000);
        });

        function sendAllCounterData() {
            let data = {};
            $(".main-content span").each(function() {
                const id = $(this).attr("id");
                if (id) data[id] = $(this).text();
            });
            $.ajax({
                url: "<?= site_url('Home/update') ?>",
                type: "POST",
                data: data,
                success: function() {
                    showToast("Counter updated!");
                },
                error: function() {
                    showToast("Error updating counters!", "danger");
                }
            });
        }
    });

    $(document).ready(function() {
        const $input = $('#jamMasuk');
        $input.on('input', function() {
            let val = $(this).val().replace(/\D/g, '');
            if (val.length >= 3) val = val.slice(0, 2) + '.' + val.slice(2, 4);
            $(this).val(val);
            if (val.length === 5) hitungBiayaParkir();
        });

        function hitungBiayaParkir() {
            const jamMasuk = $input.val();
            if (!/^\d{2}\.\d{2}$/.test(jamMasuk)) {
                showToast("Format jam tidak valid! Gunakan HH.MM", "warning");
                return;
            }
            let [jam, menit] = jamMasuk.split('.').map(Number);
            if (jam > 23 || menit > 59) {
                showToast("Jam atau menit tidak valid!", "warning");
                return;
            }
            const now = new Date();
            let totalMenitMasuk = jam * 60 + menit;
            let totalMenitSekarang = now.getHours() * 60 + now.getMinutes();
            if (totalMenitMasuk > totalMenitSekarang) totalMenitSekarang += 24 * 60;
            const selisih = totalMenitSekarang - totalMenitMasuk;
            const jamTerparkir = Math.ceil(selisih / 60);
            let biaya = jamTerparkir <= 1 ? 2000 : Math.min(2000 + (jamTerparkir - 1) * 1000, 8000);
            $('#durasiParkir').text(jamTerparkir + ' jam');
            $('#biayaParkir').text('Rp ' + biaya.toLocaleString('id-ID'));
            $('#waktuSekarang').text('Waktu Sekarang: ' + now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }));
            $('#hasilParkir').fadeIn();
        }
    });

    // ─────────────────────────────────────────────────────────────────────────────
    //  SERIES TRACKER - UPDATED LOGIC
    // ─────────────────────────────────────────────────────────────────────────────
    $(document).ready(function() {

        const BASE_URL = "<?= site_url() ?>";
        let deleteId = null;
        let editData = null;

        // ── LOAD LIST ───────────────────────────────────────────────
        function loadSeriesList() {
            $.get(BASE_URL + 'series/list', function(res) {
                if (!res.success) return;
                renderSeriesList(res.data);
            });
        }

        function renderSeriesList(list) {
            const $container = $('#series-list-container');
            $container.empty();

            if (!list || list.length === 0) {
                $container.append(`
                <div class="series-empty" id="series-empty-state">
                    <i class="fas fa-film"></i>
                    Belum ada data. Klik <strong>Tambah</strong> untuk mulai mencatat.
                </div>`);
                return;
            }

            list.forEach(function(series) {
                $container.append(buildSeriesCard(series));
            });
        }

        function buildSeriesCard(series) {
            // Hitung progress global & Season Aktif (current season)
            let totalEp = 0,
                doneEp = 0;
            let currentSeason = 1;

            (series.seasons || []).forEach(function(season) {
                let hasWatched = false;
                (season.episodes || []).forEach(function(ep) {
                    totalEp++;
                    if (ep.status == 2) {
                        doneEp++;
                        hasWatched = true;
                    }
                });
                if (hasWatched) {
                    currentSeason = season.season_num;
                }
            });

            const pct = totalEp > 0 ? Math.round(doneEp / totalEp * 100) : 0;
            const ratingStr = series.rating ? '★'.repeat(Math.round(series.rating / 2)) + '<span class="text-muted" style="font-size:11px"> ' + series.rating + '/10</span>' : '';
            const notesStr = series.notes ? `<div class="series-notes">${escHtml(series.notes)}</div>` : '';

            // Dropdown untuk Season (hanya tampil jika > 1 season)
            let seasonSelector = '';
            if ((series.seasons || []).length > 1) {
                let opts = series.seasons.map(s => `<option value="${s.season_num}" ${s.season_num == currentSeason ? 'selected' : ''}>Season ${s.season_num}</option>`).join('');
                seasonSelector = `<select class="form-control form-control-sm season-selector" data-series-id="${series.id}" style="width:120px; display:inline-block; font-weight:bold; color:#6777ef;">${opts}</select>`;
            }

            // Bangun season blocks
            let seasonsHtml = seasonSelector;
            (series.seasons || []).forEach(function(season) {
                let epsHtml = (season.episodes || []).map(function(ep) {
                    const cls = ep.status == 2 ? 'done' : '';
                    return `<button class="ep-btn ${cls}" data-ep-id="${ep.id}" data-series-id="${series.id}" data-season-num="${season.season_num}" data-ep-num="${ep.ep_num}" title="Episode ${ep.ep_num}">${ep.ep_num}</button>`;
                }).join('');

                let isHidden = season.season_num != currentSeason ? 'display:none;' : '';

                seasonsHtml += `
                <div class="season-block season-block-${series.id}" data-season="${season.season_num}" style="${isHidden}">
                    ${(series.seasons || []).length <= 1 ? `<div class="season-label">Season ${season.season_num}</div>` : ''}
                    <div class="ep-grid">${epsHtml}</div>
                </div>`;
            });

            if (!seasonsHtml) {
                seasonsHtml = '<p class="text-muted small">Belum ada season. Edit untuk menambah.</p>';
            }

            return `
        <div class="card mb-3 border" id="series-card-${series.id}">
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <h5 class="mb-0 font-weight-bold text-primary">${escHtml(series.title)}</h5>
                        ${notesStr}
                    </div>
                    <div class="d-flex align-items-center" style="gap:6px;">
                        ${ratingStr ? `<span class="rating-display mr-2">${ratingStr}</span>` : ''}
                        <button class="btn btn-sm btn-outline-primary btn-edit-series" data-id="${series.id}" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning btn-disable-series" data-id="${series.id}" title="Arsipkan/Hide">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-series" data-id="${series.id}" data-title="${escHtml(series.title)}" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Progress bar -->
                <div class="series-progress-bar mb-2 mt-2">
                    <div class="fill" style="width:${pct}%"></div>
                </div>
                <div class="series-progress-text" style="font-size:11px;color:#aaa;margin-bottom:8px;">${doneEp} / ${totalEp} episode selesai (${pct}%)</div>

                ${seasonsHtml}
            </div>
        </div>`;
        }

        function escHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        } // ── LOAD DISABLED SERIES ────────────────────────────────────
        function loadDisabledSeries() {
            $.get(BASE_URL + 'series/list-disabled', function(res) {
                const $container = $('#disabled-series-list');
                $container.empty();

                if (!res.success || res.data.length === 0) {
                    $container.append('<p class="text-muted small mb-0">Tidak ada series yang diarsipkan.</p>');
                    return;
                }

                res.data.forEach(function(s) {
                    $container.append(`
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 mb-2 border" style="border-radius:6px;">
                            <div>
                                <h6 class="mb-0 text-dark">${escHtml(s.title)}</h6>
                                ${s.notes ? `<small class="text-muted">${escHtml(s.notes)}</small>` : ''}
                            </div>
                            <button type="button" class="btn btn-sm btn-success btn-restore-series" data-id="${s.id}" title="Kembalikan (Enable)">
                                <i class="fas fa-undo mr-1"></i> Restore
                            </button>
                        </div>
                    `);
                });
            });
        }

        // ── KEMBALIKAN (RESTORE) SERIES ─────────────────────────────
        $(document).on('click', '.btn-restore-series', function() {
            const id = $(this).data('id');
            const $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                url: BASE_URL + 'series/restore/' + id,
                type: 'POST',
                success: function(res) {
                    if (res.success) {
                        showToast('Series berhasil dikembalikan ke list utama!');
                        loadDisabledSeries(); // Refresh list arsip
                        loadSeriesList(); // Refresh list utama di background
                    } else {
                        showToast('Gagal mengembalikan series', 'danger');
                        $btn.prop('disabled', false);
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan pada server!', 'danger');
                    $btn.prop('disabled', false);
                }
            });
        });

        // ── SEASON SELECTOR TOGGLE ──────────────────────────────────
        $(document).on('change', '.season-selector', function() {
            const sId = $(this).data('series-id');
            const val = $(this).val();
            $(`.season-block-${sId}`).hide();
            $(`.season-block-${sId}[data-season="${val}"]`).fadeIn();
        });

        // ── EPISODE TOGGLE BATCH LOGIC ──────────────────────────────
        let pendingEpisodeUpdates = {};
        let episodeSaveTimeout = null;

        $(document).on('click', '.ep-btn', function() {
            const $btn = $(this);
            const seriesId = $btn.data('series-id');
            const targetSeason = parseInt($btn.data('season-num'));
            const targetEp = parseInt($btn.data('ep-num'));

            // Set semua episode <= target menjadi done(hijau), sisanya hapus class
            $(`.ep-btn[data-series-id="${seriesId}"]`).each(function() {
                const sNum = parseInt($(this).data('season-num'));
                const eNum = parseInt($(this).data('ep-num'));
                const epId = $(this).data('ep-id');

                let newStatus = 0;
                if (sNum < targetSeason || (sNum === targetSeason && eNum <= targetEp)) {
                    newStatus = 2; // hijau / done
                    $(this).addClass('done');
                } else {
                    $(this).removeClass('done');
                }

                pendingEpisodeUpdates[epId] = newStatus;
            });

            // Update local progress bar
            refreshProgressBar(seriesId);

            // Debounce save 3 detik
            clearTimeout(episodeSaveTimeout);
            episodeSaveTimeout = setTimeout(savePendingEpisodes, 3000);
        });

        function savePendingEpisodes() {
            if (Object.keys(pendingEpisodeUpdates).length === 0) return;

            let updatesToSend = {
                ...pendingEpisodeUpdates
            };
            pendingEpisodeUpdates = {};

            $.ajax({
                url: BASE_URL + 'series/episodes/batch-update',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    updates: updatesToSend
                }),
                success: function(res) {
                    if (!res.success) {
                        showToast('Gagal menyimpan progress!', 'danger');
                    }
                }
            });
        }

        function refreshProgressBar(seriesId) {
            const $card = $('#series-card-' + seriesId);
            const total = $card.find('.ep-btn').length;
            const done = $card.find('.ep-btn.done').length;
            const pct = total > 0 ? Math.round(done / total * 100) : 0;
            $card.find('.series-progress-bar .fill').css('width', pct + '%');
            $card.find('.series-progress-text').text(`${done} / ${total} episode selesai (${pct}%)`);
        }

        // ── MODAL: SEASON FORM BUILDER ───────────────────────────────
        let seasonRowCount = 0;

        function addSeasonRow(seasonNum, totalEps, existingEpisodes) {
            seasonRowCount++;
            const rowId = 'season-row-' + seasonRowCount;
            const $row = $(`
            <div class="season-form-row" id="${rowId}" data-existing-eps='${JSON.stringify(existingEpisodes || [])}'>
                <span class="season-num-label">Season ${seasonNum}</span>
                <input type="number" class="form-control form-control-sm season-eps-input"
                       placeholder="Jumlah Episode" value="${totalEps || ''}" min="1" style="width:140px;"
                       data-season-num="${seasonNum}">
                <button type="button" class="btn-remove-season" data-row="${rowId}" title="Hapus season">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>`);
            $('#seasons-form-container').append($row);
        }

        function resetSeasonForm() {
            seasonRowCount = 0;
            $('#seasons-form-container').empty();
        }

        function getNextSeasonNum() {
            const nums = [];
            $('#seasons-form-container .season-eps-input').each(function() {
                nums.push(parseInt($(this).data('season-num')));
            });
            return nums.length > 0 ? Math.max(...nums) + 1 : 1;
        }

        $(document).on('click', '.btn-remove-season', function() {
            const rowId = $(this).data('row');
            $('#' + rowId).remove();
            $('#seasons-form-container .season-form-row').each(function(idx) {
                $(this).find('.season-num-label').text('Season ' + (idx + 1));
                $(this).find('.season-eps-input').data('season-num', idx + 1).attr('data-season-num', idx + 1);
            });
        });

        $('#btn-add-season').click(function() {
            addSeasonRow(getNextSeasonNum(), '');
        });

        // ── BUKA MODAL TAMBAH ───────────────────────────────────────
        // ── BUKA MODAL TAMBAH ───────────────────────────────────────
        $('#btn-open-add-series').click(function() {
            editData = null;
            $('#series-edit-id').val('');
            $('#series-title').val('');
            $('#series-notes').val('');
            $('#series-rating').val('');
            $('#series-watched-season').val('1');
            $('#series-watched-episode').val('1');
            $('#watched-input-row').show();

            // Tampilkan section arsip & load datanya
            $('#divider-disabled-series').show();
            $('#section-disabled-series').show();
            loadDisabledSeries();

            resetSeasonForm();
            addSeasonRow(1, '');
            $('#modalSeriesLabel').text('Tambah Series');
            $('#modalSeries').modal('show');
        });

        // ── BUKA MODAL EDIT ─────────────────────────────────────────
        $(document).on('click', '.btn-edit-series', function() {
            const id = $(this).data('id');
            $.get(BASE_URL + 'series/list', function(res) {
                if (!res.success) return;
                const series = res.data.find(s => s.id == id);
                if (!series) return;

                editData = series;
                $('#series-edit-id').val(series.id);
                $('#series-title').val(series.title);
                $('#series-notes').val(series.notes || '');
                $('#series-rating').val(series.rating || '');
                $('#watched-input-row').hide();

                // Sembunyikan section arsip saat edit
                $('#divider-disabled-series').hide();
                $('#section-disabled-series').hide();

                resetSeasonForm();
                (series.seasons || []).forEach(function(season) {
                    addSeasonRow(season.season_num, season.total_eps, season.episodes);
                });
                if ((series.seasons || []).length === 0) addSeasonRow(1, '');

                $('#modalSeriesLabel').text('Edit Series');
                $('#modalSeries').modal('show');
            });
        });

        // ── BUKA MODAL EDIT ─────────────────────────────────────────
        $(document).on('click', '.btn-edit-series', function() {
            const id = $(this).data('id');
            $.get(BASE_URL + 'series/list', function(res) {
                if (!res.success) return;
                const series = res.data.find(s => s.id == id);
                if (!series) return;

                editData = series;
                $('#series-edit-id').val(series.id);
                $('#series-title').val(series.title);
                $('#series-notes').val(series.notes || '');
                $('#series-rating').val(series.rating || '');
                $('#watched-input-row').hide(); // Sembunyikan input default saat Edit

                resetSeasonForm();
                (series.seasons || []).forEach(function(season) {
                    addSeasonRow(season.season_num, season.total_eps, season.episodes);
                });
                if ((series.seasons || []).length === 0) addSeasonRow(1, '');

                $('#modalSeriesLabel').text('Edit Series');
                $('#modalSeries').modal('show');
            });
        });

        // ── SIMPAN (TAMBAH / EDIT) ──────────────────────────────────
        $('#btn-save-series').click(function() {
            const title = $('#series-title').val().trim();
            const editId = $('#series-edit-id').val();

            if (!title) {
                showToast('Judul wajib diisi!', 'warning');
                $('#series-title').focus();
                return;
            }

            const seasons = [];
            $('#seasons-form-container .season-form-row').each(function(idx) {
                const totalEps = parseInt($(this).find('.season-eps-input').val());
                if (!totalEps || totalEps < 1) return;
                const existingEps = $(this).data('existing-eps') || [];
                seasons.push({
                    season_num: idx + 1,
                    total_eps: totalEps,
                    episodes: existingEps
                });
            });

            const payload = {
                title: title,
                type: 'series', // hardcode default ke series (menghapus anime/film)
                notes: $('#series-notes').val().trim() || null,
                rating: $('#series-rating').val() || null,
                seasons: seasons,
                watched_season: $('#series-watched-season').val(),
                watched_episode: $('#series-watched-episode').val()
            };

            const url = editId ? BASE_URL + 'series/update/' + editId : BASE_URL + 'series/store';
            const method = editId ? 'PUT' : 'POST';

            $('#btn-save-series').prop('disabled', true);
            $.ajax({
                url: url,
                type: method,
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function(res) {
                    if (res.success) {
                        showToast(editId ? 'Series berhasil diupdate!' : 'Series berhasil ditambah!');
                        $('#modalSeries').modal('hide');
                        loadSeriesList();
                    } else {
                        showToast(res.message || 'Gagal menyimpan', 'danger');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan pada server!', 'danger');
                },
                complete: function() {
                    $('#btn-save-series').prop('disabled', false);
                }
            });
        });

        // ── ARSIP / DISABLE ──────────────────────────────────────────
        $(document).on('click', '.btn-disable-series', function() {
            const id = $(this).data('id');
            if (confirm('Arsipkan/sembunyikan series ini dari list utama?')) {
                $.ajax({
                    url: BASE_URL + 'series/disable/' + id,
                    type: 'POST',
                    success: function(res) {
                        if (res.success) {
                            showToast('Series berhasil diarsipkan!');
                            loadSeriesList();
                        } else {
                            showToast('Gagal arsip series', 'danger');
                        }
                    }
                });
            }
        });

        // ── HAPUS ────────────────────────────────────────────────────
        $(document).on('click', '.btn-delete-series', function() {
            deleteId = $(this).data('id');
            $('#delete-series-title').text($(this).data('title'));
            $('#modalDeleteSeries').modal('show');
        });

        $('#btn-confirm-delete').click(function() {
            if (!deleteId) return;
            $('#btn-confirm-delete').prop('disabled', true);
            $.ajax({
                url: BASE_URL + 'series/delete/' + deleteId,
                type: 'DELETE',
                success: function(res) {
                    if (res.success) {
                        showToast('Series berhasil dihapus!');
                        $('#modalDeleteSeries').modal('hide');
                        loadSeriesList();
                    } else {
                        showToast(res.message || 'Gagal hapus', 'danger');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan pada server!', 'danger');
                },
                complete: function() {
                    $('#btn-confirm-delete').prop('disabled', false);
                    deleteId = null;
                }
            });
        });

        // ── INIT ─────────────────────────────────────────────────────
        loadSeriesList();
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
Aplikasi KB Kalender
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/@fontawesome/fontawesome-free/css/all.min.css">

<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/style.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/components.css">
<link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/custom.css">

<style>
  /* Custom UI Tweaks */
  .alert-custom {
    border-radius: 10px;
    border: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
  }

  .alert-custom .alert-icon i {
    font-size: 2rem;
    margin-top: 5px;
  }

  .info-card {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    border: 1px solid #f0f0f0;
  }

  .info-card .card-header {
    min-height: 50px;
    padding-bottom: 0;
    border-bottom: 1px solid #f8f9fa;
  }

  .info-card .card-header h4 {
    font-size: 14px;
    letter-spacing: 0.5px;
    color: #6c757d;
  }

  .info-card .card-body h5 {
    font-size: 1.15rem;
    font-weight: 700;
  }

  .table-clean th {
    background-color: #fcfcfc;
    border-top: none !important;
    text-transform: uppercase;
    font-size: 12px;
    color: #888;
    letter-spacing: 0.5px;
  }

  .table-clean td {
    vertical-align: middle;
    border-top: 1px solid #f4f6f9;
  }

  /* Pemisah vertikal untuk layout grouped */
  @media (min-width: 768px) {
    .border-md-right {
      border-right: 1px solid #eef1f5;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
  <div class="section-header">
    <h1>Dashboard Kesuburan</h1>
  </div>

  <?php
  $alertClass = 'alert-light';
  $iconClass  = 'fa-info-circle';

  if (strpos($conclusion['color_class'], 'bg-danger') !== false) {
    $alertClass = 'alert-danger';
    $iconClass  = 'fa-exclamation-triangle';
  } elseif (strpos($conclusion['color_class'], 'bg-warning') !== false) {
    $alertClass = 'alert-warning';
    $iconClass  = 'fa-exclamation-circle';
  } elseif (strpos($conclusion['color_class'], 'bg-success') !== false) {
    $alertClass = 'alert-success';
    $iconClass  = 'fa-check-circle';
  } elseif (strpos($conclusion['color_class'], 'bg-info') !== false) {
    $alertClass = 'alert-info';
    $iconClass  = 'fa-clock';
  }
  ?>

  <div class="row">
    <div class="col-12">
      <div class="alert <?= $alertClass ?> alert-has-icon alert-custom p-4 mb-4">
        <div class="alert-icon"><i class="fas <?= $iconClass ?>"></i></div>
        <div class="alert-body">
          <div class="alert-title mb-1" style="font-size: 1.25rem;">Status Hari Ini: <?= $conclusion['status'] ?></div>
          <p class="mb-0" style="font-size: 1rem; opacity: 0.9;"><?= $conclusion['message'] ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 col-md-12 col-12 mb-4">
      <div class="card info-card card-danger h-100 mb-0">
        <div class="card-header pb-3 pt-3">
          <h4><i class="fas fa-heart text-danger mr-2"></i> Jendela Masa Subur</h4>
        </div>
        <div class="card-body">
          <div class="row text-center text-md-left">
            <div class="col-md-6 border-md-right mb-3 mb-md-0">
              <span class="text-uppercase text-muted d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Mulai Tanggal</span>
              <h5 class="text-danger mb-0"><?= $conclusion['safe_start'] ?></h5>
            </div>
            <div class="col-md-6 pl-md-4">
              <span class="text-uppercase text-muted d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Sampai Tanggal</span>
              <h5 class="text-danger mb-0"><?= $conclusion['safe_end'] ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-12 col-12 mb-4">
      <div class="card info-card card-primary h-100 mb-0">
        <div class="card-header pb-3 pt-3">
          <h4><i class="fas fa-calendar-alt text-primary mr-2"></i> Prediksi Siklus Haid</h4>
        </div>
        <div class="card-body">
          <div class="row text-center text-md-left">
            <div class="col-md-7 border-md-right mb-3 mb-md-0">
              <span class="text-uppercase text-muted d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Perkiraan Haid Selanjutnya</span>
              <h5 class="text-primary mb-0" style="font-size: 1.05rem;">
                <?= $conclusion['next_period_start'] ?> <span class="text-muted font-weight-normal mx-1" style="font-size: 0.85rem;">s/d</span> <?= $conclusion['next_period_end'] ?>
              </h5>
            </div>
            <div class="col-md-5 pl-md-4">
              <span class="text-uppercase text-muted d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Terhitung Telat</span>
              <h5 class="text-warning mb-0">Mulai <?= $conclusion['late_date'] ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4 col-md-12 col-12 col-sm-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom-0 pb-0">
          <h4 class="text-dark">Catat Haid Bulan Ini</h4>
        </div>
        <div class="card-body pt-3">
          <form action="<?= base_url('KbCalculator/store') ?>" method="POST">
            <div class="form-group">
              <label class="text-muted">Tanggal Mulai Haid</label>
              <input type="date" name="start_date" class="form-control bg-light border-0" required>
            </div>
            <div class="form-group">
              <label class="text-muted">Tanggal Selesai Haid</label>
              <input type="date" name="end_date" class="form-control bg-light border-0" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg mt-4 shadow-sm" style="border-radius: 8px;">
              <i class="fas fa-save mr-1"></i> Simpan Data
            </button>
            <p class="text-muted mt-3 mb-0 text-center" style="font-size: 12px;"><i class="fas fa-lock text-success"></i> Data tersimpan aman sebagai riwayat medis Anda.</p>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md-12 col-12 col-sm-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom-0">
          <h4 class="text-dark">Riwayat Haid (Maks. 1 Tahun Terakhir)</h4>
        </div>
        <div class="card-body p-0">

          <?php if (empty($periods)): ?>
            <div class="empty-state" data-height="300">
              <div class="empty-state-icon bg-light text-muted">
                <i class="fas fa-calendar-times"></i>
              </div>
              <h2>Belum ada data</h2>
              <p class="lead text-muted">
                Silakan isi form di samping untuk mulai merekam siklus Anda.
              </p>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover table-clean mb-0">
                <thead>
                  <tr>
                    <th class="pl-4">Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th class="text-center">Durasi</th>
                    <th class="text-right pr-4">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($periods as $p):
                    $start = \CodeIgniter\I18n\Time::parse($p['start_date']);
                    $end = \CodeIgniter\I18n\Time::parse($p['end_date']);
                    $diff = $end->difference($start)->getDays() + 1;
                  ?>
                    <tr>
                      <td class="pl-4 font-weight-600"><?= $start->toLocalizedString('d MMM YYYY') ?></td>
                      <td class="text-muted"><?= $end->toLocalizedString('d MMM YYYY') ?></td>
                      <td class="text-center">
                        <span class="badge badge-light px-3 py-2 text-dark" style="font-weight: 500;">
                          <?= $diff ?> Hari
                        </span>
                      </td>
                      <td class="text-right pr-4">
                        <button class="btn btn-icon btn-outline-primary btn-sm btn-edit rounded-circle"
                          data-id="<?= $p['id'] ?>"
                          data-start="<?= $p['start_date'] ?>"
                          data-end="<?= $p['end_date'] ?>"
                          data-toggle="modal" data-target="#editModal"
                          title="Edit Data">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data Haid</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('KbCalculator/update') ?>" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="form-group">
            <label class="text-muted">Tanggal Mulai</label>
            <input type="date" name="start_date" id="edit_start" class="form-control bg-light border-0" required>
          </div>
          <div class="form-group mb-0">
            <label class="text-muted">Tanggal Selesai</label>
            <input type="date" name="end_date" id="edit_end" class="form-control bg-light border-0" required>
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-check mr-1"></i> Simpan Perubahan</button>
        </div>
      </form>
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
  $(document).ready(function() {
    $('.btn-edit').on('click', function() {
      const id = $(this).data('id');
      const start = $(this).data('start');
      const end = $(this).data('end');

      $('#edit_id').val(id);
      $('#edit_start').val(start);
      $('#edit_end').val(end);
    });
  });
</script>
<?= $this->endSection() ?>
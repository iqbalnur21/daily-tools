<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Aplikasi KB Kalender Simple</title>
  
  <link rel="icon" href="<?= $assetsPath ?>/template_stisla/assets/img/counting.png" type="image/png">

  <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/node_modules/@fontawesome/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/style.css">
  <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/components.css">
  <link rel="stylesheet" href="<?= $assetsPath ?>/template_stisla/assets/css/custom.css">
  
  <style>
      /* Custom style untuk status card */
      .conclusion-card {
          transition: all 0.3s;
      }
      .period-list {
          max-height: 400px;
          overflow-y: auto;
      }
  </style>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= $assetsPath ?>/template_stisla/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, Bunda</div></a>
          </li>
        </ul>
      </nav>

      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="#">KB Kalender</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">KB</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="active"><a class="nav-link" href="#"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
          </ul>
        </aside>
      </div>

      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard Kesuburan</h1>
          </div>

          <div class="row">
            <div class="col-12 mb-4">
              <div class="hero <?= $conclusion['color_class'] ?> conclusion-card">
                <div class="hero-inner">
                  <h2>Status Hari Ini: <?= $conclusion['status'] ?></h2>
                  <p class="lead"><?= $conclusion['message'] ?></p>
                  
                  <div class="mt-4">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="card bg-white text-dark mb-2">
                                  <div class="card-body p-3">
                                      <small class="text-uppercase font-weight-bold text-muted">Masa Subur Mulai</small>
                                      <h5 class="mb-0 text-danger"><?= $conclusion['safe_start'] ?></h5>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="card bg-white text-dark mb-2">
                                  <div class="card-body p-3">
                                      <small class="text-uppercase font-weight-bold text-muted">Masa Subur Berakhir</small>
                                      <h5 class="mb-0 text-danger"><?= $conclusion['safe_end'] ?></h5>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <small class="text-white-50">*Rentang tanggal di atas adalah waktu TIDAK BOLEH berhubungan tanpa pengaman.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Catat Haid Bulan Ini</h4>
                </div>
                <div class="card-body">
                  <form action="<?= base_url('kb/store') ?>" method="POST">
                    <div class="form-group">
                      <label>Tanggal Mulai Haid</label>
                      <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Tanggal Selesai Haid</label>
                      <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Simpan Data</button>
                    <small class="text-muted mt-2 d-block text-center">Data terlama otomatis dihapus jika > 6 entri.</small>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Riwayat 6 Bulan Terakhir</h4>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive period-list">
                    <table class="table table-striped mb-0">
                      <thead>
                        <tr>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Selesai</th>
                          <th>Durasi</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(empty($periods)): ?>
                            <tr><td colspan="4" class="text-center">Belum ada data.</td></tr>
                        <?php else: ?>
                            <?php foreach($periods as $p): 
                                $start = \CodeIgniter\I18n\Time::parse($p['start_date']);
                                $end = \CodeIgniter\I18n\Time::parse($p['end_date']);
                                $diff = $end->difference($start)->getDays() + 1;
                            ?>
                            <tr>
                              <td><?= $start->toLocalizedString('d MMM YYYY') ?></td>
                              <td><?= $end->toLocalizedString('d MMM YYYY') ?></td>
                              <td><?= $diff ?> Hari</td>
                              <td>
                                <button class="btn btn-warning btn-sm btn-edit" 
                                    data-id="<?= $p['id'] ?>"
                                    data-start="<?= $p['start_date'] ?>"
                                    data-end="<?= $p['end_date'] ?>"
                                    data-toggle="modal" data-target="#editModal">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </button>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      
      <footer class="main-footer">
        <div class="footer-left">
          KB Kalender Simple App
        </div>
      </footer>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Haid</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= base_url('kb/update') ?>" method="POST">
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" id="edit_start" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="end_date" id="edit_end" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <script src="<?= $assetsPath ?>/template_stisla/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="<?= $assetsPath ?>/template_stisla/node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?= $assetsPath ?>/template_stisla/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?= $assetsPath ?>/template_stisla/node_modules/nicescroll/dist/jquery.nicescroll.min.js"></script>
  <script src="<?= $assetsPath ?>/template_stisla/node_modules/moment/moment.js"></script>
  <script src="<?= $assetsPath ?>/template_stisla/assets/js/stisla.js"></script>
  
  <script src="<?= $assetsPath ?>/template_stisla/assets/js/scripts.js"></script>
  <script src="<?= $assetsPath ?>/template_stisla/assets/js/custom.js"></script>

  <script>
      // Script untuk memasukkan data ke dalam Modal Edit
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
</body>
</html>
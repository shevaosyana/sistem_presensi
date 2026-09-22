<?php 
$pageTitle = 'Tambah Tahun Akademik';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Tahun Akademik</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=tahunakademik">Tahun Akademik</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=tahunakademik&action=store" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="nama">Nama Tahun Akademik <span class="required">*</span></label>
          <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: 2024/2025" required>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="tahun_mulai">Tahun Mulai <span class="required">*</span></label>
            <input type="number" name="tahun_mulai" id="tahun_mulai" class="form-control" min="2000" max="<?= date('Y')+5 ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="tahun_selesai">Tahun Selesai <span class="required">*</span></label>
            <input type="number" name="tahun_selesai" id="tahun_selesai" class="form-control" min="2000" max="<?= date('Y')+5 ?>" required>
          </div>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=tahunakademik" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

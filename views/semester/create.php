<?php 
$pageTitle = 'Tambah Semester';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Semester</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=semester">Semester</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=semester&action=store" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="tahun_akademik_id">Tahun Akademik <span class="required">*</span></label>
          <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-control" required>
            <option value="">-- Pilih Tahun Akademik --</option>
            <?php foreach($listTA as $ta): ?>
              <option value="<?= $ta['id'] ?>"><?= e($ta['nama']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="nama_semester">Semester <span class="required">*</span></label>
          <select name="nama_semester" id="nama_semester" class="form-control" required>
            <option value="">-- Pilih Semester --</option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
            <option value="Pendek">Pendek</option>
          </select>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=semester" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

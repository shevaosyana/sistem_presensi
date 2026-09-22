<?php 
$pageTitle = 'Edit Ruangan';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Edit Ruangan</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=ruangan">Ruangan</a> / <span>Edit</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=ruangan&action=update&id=<?= $rng['id'] ?>" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="kode">Kode Ruangan <span class="required">*</span></label>
          <input type="text" name="kode" id="kode" class="form-control" value="<?= e($rng['kode']) ?>" required>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="nama">Nama Ruangan <span class="required">*</span></label>
          <input type="text" name="nama" id="nama" class="form-control" value="<?= e($rng['nama']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="kapasitas">Kapasitas (Orang) <span class="required">*</span></label>
          <input type="number" name="kapasitas" id="kapasitas" class="form-control" min="1" value="<?= e($rng['kapasitas']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="gedung">Nama Gedung</label>
          <input type="text" name="gedung" id="gedung" class="form-control" value="<?= e($rng['gedung'] ?? '') ?>">
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=ruangan" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

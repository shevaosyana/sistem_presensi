<?php 
$pageTitle = 'Tambah Prodi';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Program Studi</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=prodi">Program Studi</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=prodi&action=store" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="kode">Kode Prodi <span class="required">*</span></label>
          <input type="text" name="kode" id="kode" class="form-control" style="text-transform:uppercase" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="nama">Nama Program Studi <span class="required">*</span></label>
          <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="jenjang">Jenjang <span class="required">*</span></label>
          <select name="jenjang" id="jenjang" class="form-control" required>
            <option value="">-- Pilih Jenjang --</option>
            <option value="D3">D3</option>
            <option value="D4">D4</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
          </select>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=prodi" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Tambah Dosen';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Dosen</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=dosen">Dosen</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 800px">
    <form action="?page=dosen&action=store" method="POST">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="nidn">NIDN <span class="required">*</span></label>
            <input type="text" name="nidn" id="nidn" class="form-control" required>
            <div class="form-text">Digunakan juga sebagai username login. Password default sama dengan NIDN.</div>
          </div>
          <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap & Gelar <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="prodi_id">Homebase Program Studi <span class="required">*</span></label>
            <select name="prodi_id" id="prodi_id" class="form-control" required>
              <option value="">-- Pilih Program Studi --</option>
              <?php foreach($listProdi as $p): ?>
                <option value="<?= $p['id'] ?>"><?= e($p['kode'].' - '.$p['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
            <div style="display:flex;gap:1rem;margin-top:0.5rem">
              <label><input type="radio" name="jenis_kelamin" value="L" required> Laki-laki</label>
              <label><input type="radio" name="jenis_kelamin" value="P" required> Perempuan</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=dosen" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Edit Dosen';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Edit Dosen</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=dosen">Dosen</a> / <span>Edit</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 800px">
    <form action="?page=dosen&action=update&id=<?= $dsn['id'] ?>" method="POST">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="nidn">NIDN <span class="required">*</span></label>
            <input type="text" name="nidn" id="nidn" class="form-control" value="<?= e($dsn['nidn']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap & Gelar <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= e($dsn['nama']) ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="prodi_id">Homebase Program Studi <span class="required">*</span></label>
            <select name="prodi_id" id="prodi_id" class="form-control" required>
              <option value="">-- Pilih Program Studi --</option>
              <?php foreach($listProdi as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $dsn['prodi_id']==$p['id']?'selected':'' ?>><?= e($p['kode'].' - '.$p['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
            <div style="display:flex;gap:1rem;margin-top:0.5rem">
              <label><input type="radio" name="jenis_kelamin" value="L" <?= $dsn['jenis_kelamin']=='L'?'checked':'' ?> required> Laki-laki</label>
              <label><input type="radio" name="jenis_kelamin" value="P" <?= $dsn['jenis_kelamin']=='P'?'checked':'' ?> required> Perempuan</label>
            </div>
          </div>
        </div>

        <div class="form-group mt-2">
          <label class="form-label">Reset Password</label>
          <div style="display:flex;align-items:center;gap:0.5rem">
            <input type="checkbox" name="reset_password" id="reset_password" value="1">
            <label for="reset_password">Kembalikan password ke default (sama dengan NIDN)</label>
          </div>
        </div>
      </div>
      
      <div class="card-footer form-actions">
        <a href="?page=dosen" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

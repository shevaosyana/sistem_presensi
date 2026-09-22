<?php 
$pageTitle = 'Edit Mahasiswa';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Edit Mahasiswa</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=mahasiswa">Mahasiswa</a> / <span>Edit</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 800px">
    <form action="?page=mahasiswa&action=update&id=<?= $mhs['id'] ?>" method="POST">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="nim">NIM <span class="required">*</span></label>
            <input type="text" name="nim" id="nim" class="form-control" value="<?= e($mhs['nim']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= e($mhs['nama']) ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="prodi_id">Program Studi <span class="required">*</span></label>
            <select name="prodi_id" id="prodi_id" class="form-control" required>
              <option value="">-- Pilih Program Studi --</option>
              <?php foreach($listProdi as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $mhs['prodi_id']==$p['id']?'selected':'' ?>><?= e($p['kode'].' - '.$p['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="angkatan">Tahun Angkatan <span class="required">*</span></label>
            <input type="number" name="angkatan" id="angkatan" class="form-control" value="<?= e($mhs['angkatan']) ?>" min="2000" max="<?= date('Y')+1 ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
          <div style="display:flex;gap:1rem">
            <label><input type="radio" name="jenis_kelamin" value="L" <?= $mhs['jenis_kelamin']=='L'?'checked':'' ?> required> Laki-laki</label>
            <label><input type="radio" name="jenis_kelamin" value="P" <?= $mhs['jenis_kelamin']=='P'?'checked':'' ?> required> Perempuan</label>
          </div>
        </div>

        <div class="form-group mt-2">
          <label class="form-label">Reset Password</label>
          <div style="display:flex;align-items:center;gap:0.5rem">
            <input type="checkbox" name="reset_password" id="reset_password" value="1">
            <label for="reset_password">Kembalikan password ke default (sama dengan NIM)</label>
          </div>
        </div>
      </div>
      
      <div class="card-footer form-actions">
        <a href="?page=mahasiswa" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Edit Mata Kuliah';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Edit Mata Kuliah</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=matakuliah">Mata Kuliah</a> / <span>Edit</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=matakuliah&action=update&id=<?= $mk['id'] ?>" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="prodi_id">Program Studi <span class="required">*</span></label>
          <select name="prodi_id" id="prodi_id" class="form-control" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php foreach($listProdi as $p): ?>
              <option value="<?= $p['id'] ?>" <?= $mk['prodi_id']==$p['id']?'selected':'' ?>><?= e($p['nama']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="kode">Kode MK <span class="required">*</span></label>
            <input type="text" name="kode" id="kode" class="form-control" style="text-transform:uppercase" value="<?= e($mk['kode']) ?>" required placeholder="Misal: IF101">
          </div>
          <div class="form-group">
            <label class="form-label" for="sks">SKS <span class="required">*</span></label>
            <input type="number" name="sks" id="sks" class="form-control" min="1" max="10" value="<?= e($mk['sks']) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="nama">Nama Mata Kuliah <span class="required">*</span></label>
          <input type="text" name="nama" id="nama" class="form-control" value="<?= e($mk['nama']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="jenis">Jenis Mata Kuliah <span class="required">*</span></label>
          <select name="jenis" id="jenis" class="form-control" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="Teori" <?= $mk['jenis']=='Teori'?'selected':'' ?>>Teori</option>
            <option value="Praktikum" <?= $mk['jenis']=='Praktikum'?'selected':'' ?>>Praktikum</option>
            <option value="Teori & Praktikum" <?= $mk['jenis']=='Teori & Praktikum'?'selected':'' ?>>Teori & Praktikum</option>
          </select>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=matakuliah" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

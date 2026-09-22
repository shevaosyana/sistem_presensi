<?php 
$pageTitle = 'Tambah Mata Kuliah';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Mata Kuliah</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=matakuliah">Mata Kuliah</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=matakuliah&action=store" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="prodi_id">Program Studi <span class="required">*</span></label>
          <select name="prodi_id" id="prodi_id" class="form-control" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php foreach($listProdi as $p): ?>
              <option value="<?= $p['id'] ?>"><?= e($p['nama']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="kode">Kode MK <span class="required">*</span></label>
            <input type="text" name="kode" id="kode" class="form-control" style="text-transform:uppercase" required placeholder="Misal: IF101">
          </div>
          <div class="form-group">
            <label class="form-label" for="sks">SKS <span class="required">*</span></label>
            <input type="number" name="sks" id="sks" class="form-control" min="1" max="10" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="nama">Nama Mata Kuliah <span class="required">*</span></label>
          <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="jenis">Jenis Mata Kuliah <span class="required">*</span></label>
          <select name="jenis" id="jenis" class="form-control" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="Teori">Teori</option>
            <option value="Praktikum">Praktikum</option>
            <option value="Teori & Praktikum">Teori & Praktikum</option>
          </select>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=matakuliah" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

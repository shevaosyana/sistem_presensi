<?php 
$pageTitle = 'Tambah Kelas';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Kelas</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=kelas">Kelas</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 800px">
    <form action="?page=kelas&action=store" method="POST">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="semester_id">Semester <span class="required">*</span></label>
            <select name="semester_id" id="semester_id" class="form-control" required>
              <option value="">-- Pilih Semester --</option>
              <?php foreach($listSemester as $s): ?>
                <option value="<?= $s['id'] ?>"><?= e($s['ta_nama'].' - '.$s['nama_semester']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="mata_kuliah_id">Mata Kuliah <span class="required">*</span></label>
            <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control" required>
              <option value="">-- Pilih Mata Kuliah --</option>
              <?php foreach($listMK as $mk): ?>
                <option value="<?= $mk['id'] ?>"><?= e($mk['kode'].' - '.$mk['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="dosen_id">Dosen Pengampu <span class="required">*</span></label>
            <select name="dosen_id" id="dosen_id" class="form-control" required>
              <option value="">-- Pilih Dosen --</option>
              <?php foreach($listDosen as $d): ?>
                <option value="<?= $d['id'] ?>"><?= e($d['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="nama">Nama Kelas (Grup) <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Misal: TI-3A" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="kapasitas">Kapasitas Mahasiswa <span class="required">*</span></label>
          <input type="number" name="kapasitas" id="kapasitas" class="form-control" min="1" max="100" value="40" required style="max-width:200px">
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=kelas" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

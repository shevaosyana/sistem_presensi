<?php 
$pageTitle = 'Tambah Jadwal';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah Jadwal Kuliah</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=jadwal">Jadwal</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=jadwal&action=store" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="kelas_id">Pilih Kelas <span class="required">*</span></label>
          <select name="kelas_id" id="kelas_id" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($listKelas as $kls): ?>
              <option value="<?= $kls['id'] ?>"><?= e($kls['nama']) ?> (<?= e($kls['mk_nama']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="ruangan_id">Pilih Ruangan <span class="required">*</span></label>
          <select name="ruangan_id" id="ruangan_id" class="form-control" required>
            <option value="">-- Pilih Ruangan --</option>
            <?php foreach($listRuangan as $r): ?>
              <option value="<?= $r['id'] ?>"><?= e($r['nama']) ?> (<?= e($r['kode']) ?>) - Kapasitas: <?= $r['kapasitas'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="hari">Hari <span class="required">*</span></label>
          <select name="hari" id="hari" class="form-control" required>
            <option value="">-- Pilih Hari --</option>
            <?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
              <option value="<?= $h ?>"><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="jam_mulai">Jam Mulai <span class="required">*</span></label>
              <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="jam_selesai">Jam Selesai <span class="required">*</span></label>
              <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="toleransi_menit">Toleransi Keterlambatan (Menit)</label>
          <input type="number" name="toleransi_menit" id="toleransi_menit" class="form-control" value="15" min="0">
          <small class="text-muted">Jika mahasiswa absen melebihi waktu Toleransi (misal 15 menit), otomatis statusnya TERLAMBAT.</small>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=jadwal" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

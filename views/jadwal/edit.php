<?php 
$pageTitle = 'Edit Jadwal';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Edit Jadwal Kuliah</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=jadwal">Jadwal</a> / <span>Edit</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=jadwal&action=update&id=<?= $j['id'] ?>" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="kelas_id">Pilih Kelas <span class="required">*</span></label>
          <select name="kelas_id" id="kelas_id" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($listKelas as $kls): ?>
              <option value="<?= $kls['id'] ?>" <?= $j['kelas_id']==$kls['id']?'selected':'' ?>><?= e($kls['nama']) ?> (<?= e($kls['mk_nama']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="ruangan_id">Pilih Ruangan <span class="required">*</span></label>
          <select name="ruangan_id" id="ruangan_id" class="form-control" required>
            <option value="">-- Pilih Ruangan --</option>
            <?php foreach($listRuangan as $r): ?>
              <option value="<?= $r['id'] ?>" <?= $j['ruangan_id']==$r['id']?'selected':'' ?>><?= e($r['nama']) ?> (<?= e($r['kode']) ?>) - Kapasitas: <?= $r['kapasitas'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="hari">Hari <span class="required">*</span></label>
          <select name="hari" id="hari" class="form-control" required>
            <option value="">-- Pilih Hari --</option>
            <?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
              <option value="<?= $h ?>" <?= $j['hari']==$h?'selected':'' ?>><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="jam_mulai">Jam Mulai <span class="required">*</span></label>
              <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= e($j['jam_mulai']) ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="jam_selesai">Jam Selesai <span class="required">*</span></label>
              <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?= e($j['jam_selesai']) ?>" required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="toleransi_menit">Toleransi Keterlambatan (Menit)</label>
          <input type="number" name="toleransi_menit" id="toleransi_menit" class="form-control" value="<?= e($j['toleransi_menit']) ?>" min="0">
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=jadwal" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

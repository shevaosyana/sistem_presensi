<?php 
$pageTitle = 'Rekap Presensi Saya';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Rekap Kehadiran Saya</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Rekap</span>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <form action="" method="GET" class="d-flex" style="gap:15px; align-items:flex-end">
        <input type="hidden" name="page" value="rekap">
        <div style="flex:1">
          <label class="form-label" for="kelas_id">Pilih Mata Kuliah / Kelas</label>
          <select name="kelas_id" id="kelas_id" class="form-control" onchange="this.form.submit()">
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($listKelas as $kls): ?>
              <option value="<?= $kls['id'] ?>" <?= $kelasId==$kls['id']?'selected':'' ?>><?= e($kls['mk_nama']) ?> - <?= e($kls['nama']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>
    </div>
  </div>

  <?php if($kelasId && $rekap): ?>
    <!-- Kartu Ringkasan -->
    <div class="row mb-4">
      <?php
        $persen = ($rekap['total'] > 0) ? round(($rekap['hadir'] + $rekap['terlambat']) / $rekap['total'] * 100, 1) : 0;
        $status = ($persen >= 80) ? 'AMAN' : (($persen >= 60) ? 'PERINGATAN' : 'TIDAK BOLEH UAS');
        $statusClass = ($persen >= 80) ? 'badge-success' : (($persen >= 60) ? 'badge-warning' : 'badge-danger');
      ?>
      <div class="col-md-2 col-6">
        <div class="stat-card" style="background:#d1fae5">
          <div class="stat-number" style="color:#065f46"><?= $rekap['hadir'] ?></div>
          <div class="stat-label">Hadir</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card" style="background:#fef3c7">
          <div class="stat-number" style="color:#92400e"><?= $rekap['terlambat'] ?></div>
          <div class="stat-label">Terlambat</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card" style="background:#dbeafe">
          <div class="stat-number" style="color:#1e40af"><?= $rekap['izin'] ?></div>
          <div class="stat-label">Izin</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card" style="background:#f3f4f6">
          <div class="stat-number" style="color:#374151"><?= $rekap['sakit'] ?></div>
          <div class="stat-label">Sakit</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card" style="background:#fee2e2">
          <div class="stat-number" style="color:#991b1b"><?= $rekap['alpha'] ?></div>
          <div class="stat-label">Alpha</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card" style="background:#ede9fe">
          <div class="stat-number <?= FormatHelper::persenClass($persen) ?>" style="font-size:1.5rem"><?= FormatHelper::persen($persen) ?></div>
          <div class="stat-label"><span class="badge <?= $statusClass ?>"><?= $status ?></span></div>
        </div>
      </div>
    </div>

    <!-- Tabel Detail per Pertemuan -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Detail Kehadiran per Pertemuan</h3>
      </div>
      <div class="table-wrapper">
        <table class="table">
          <thead>
            <tr>
              <th>Pertemuan</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Jam Absen</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($detail)): ?>
              <tr><td colspan="5" class="text-center text-muted">Belum ada data detail.</td></tr>
            <?php else: foreach($detail as $d): ?>
              <tr>
                <td class="fw-600">Ke-<?= $d['pertemuan_ke'] ?></td>
                <td><?= FormatHelper::tanggalIndo($d['tanggal']) ?></td>
                <td><?= FormatHelper::badgeStatus($d['status']) ?></td>
                <td><?= $d['jam_presensi'] ? FormatHelper::jam($d['jam_presensi']) : '-' ?></td>
                <td>
                  <small class="text-muted">
                    <?php 
                      if ($d['status'] === 'TERLAMBAT' && ($d['keterangan'] === 'Tepat Waktu' || empty($d['keterangan']))) {
                          echo 'Terlambat';
                      } elseif ($d['status'] === 'HADIR' && empty($d['keterangan'])) {
                          echo 'Tepat Waktu';
                      } else {
                          echo e($d['keterangan'] ?? '-');
                      }
                    ?>
                  </small>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php elseif($kelasId): ?>
    <div class="alert alert-warning">Belum ada data rekap untuk kelas ini.</div>
  <?php endif; ?>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Detail Rekap Mahasiswa';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
$persen = ($rekapSum['total'] > 0) ? round(($rekapSum['hadir'] + $rekapSum['terlambat']) / $rekapSum['total'] * 100, 1) : 0;
$statusLabel = ($persen >= 80) ? 'AMAN' : (($persen >= 60) ? 'PERINGATAN' : 'TIDAK BOLEH UAS');
$statusClass  = ($persen >= 80) ? 'badge-success' : (($persen >= 60) ? 'badge-warning' : 'badge-danger');
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Detail Rekap: <?= e($mhs['nama']) ?></h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=rekap">Rekapitulasi</a> / <span>Detail</span>
      </div>
    </div>
    <a href="?page=rekap&kelas_id=<?= $kid ?>" class="btn btn-light">← Kembali</a>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-sm mb-0">
            <tr><th width="35%">NIM</th><td><?= e($mhs['nim']) ?></td></tr>
            <tr><th>Nama</th><td><?= e($mhs['nama']) ?></td></tr>
            <tr><th>Kelas</th><td><?= e($kls['nama']) ?> (<?= e($kls['mk_nama']) ?>)</td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center justify-content-center h-100" style="flex-direction:column; gap:8px">
            <div style="font-size:3rem; font-weight:700;" class="<?= FormatHelper::persenClass($persen) ?>"><?= FormatHelper::persen($persen) ?></div>
            <span class="badge <?= $statusClass ?> p-2" style="font-size:14px"><?= $statusLabel ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col"><div class="stat-card" style="background:#d1fae5"><div class="stat-number" style="color:#065f46"><?= $rekapSum['hadir'] ?></div><div class="stat-label">Hadir</div></div></div>
    <div class="col"><div class="stat-card" style="background:#fef3c7"><div class="stat-number" style="color:#92400e"><?= $rekapSum['terlambat'] ?></div><div class="stat-label">Terlambat</div></div></div>
    <div class="col"><div class="stat-card" style="background:#dbeafe"><div class="stat-number" style="color:#1e40af"><?= $rekapSum['izin'] ?></div><div class="stat-label">Izin</div></div></div>
    <div class="col"><div class="stat-card" style="background:#f3f4f6"><div class="stat-number" style="color:#374151"><?= $rekapSum['sakit'] ?></div><div class="stat-label">Sakit</div></div></div>
    <div class="col"><div class="stat-card" style="background:#fee2e2"><div class="stat-number" style="color:#991b1b"><?= $rekapSum['alpha'] ?></div><div class="stat-label">Alpha</div></div></div>
    <div class="col"><div class="stat-card" style="background:#f9fafb"><div class="stat-number"><?= $rekapSum['total'] ?></div><div class="stat-label">Total Ptm</div></div></div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Histori Kehadiran per Pertemuan</h3>
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
            <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
          <?php else: foreach($detail as $d): ?>
            <tr>
              <td class="fw-600">Ke-<?= $d['pertemuan_ke'] ?></td>
              <td><?= FormatHelper::tanggalIndo($d['tanggal']) ?></td>
              <td><?= FormatHelper::badgeStatus($d['status']) ?></td>
              <td><?= $d['jam_presensi'] ? FormatHelper::jam($d['jam_presensi']) : '-' ?></td>
              <td><small class="text-muted"><?= e($d['keterangan'] ?? '-') ?></small></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

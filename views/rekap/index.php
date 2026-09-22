<?php 
$pageTitle = 'Rekapitulasi Presensi';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Rekapitulasi Kehadiran Kelas</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Rekapitulasi</span>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <form action="" method="GET" class="d-flex" style="gap:15px; align-items:flex-end">
        <input type="hidden" name="page" value="rekap">
        <div style="flex:1">
          <label class="form-label" for="kelas_id">Pilih Kelas</label>
          <select name="kelas_id" id="kelas_id" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($listKelas as $kls): ?>
              <option value="<?= $kls['id'] ?>" <?= $kelasId==$kls['id']?'selected':'' ?>><?= e($kls['nama']) ?> - <?= e($kls['mk_nama']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <button type="submit" class="btn btn-primary">Tampilkan Rekap</button>
        </div>
      </form>
    </div>
  </div>

  <?php if($kelasId && $kelas): ?>
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h3 class="card-title">Rekap Kelas: <?= e($kelas['nama']) ?></h3>
          <p class="text-muted mb-0"><?= e($kelas['mk_nama']) ?> | Dosen: <?= e($kelas['dosen_nama']) ?></p>
        </div>
        <a href="?page=laporan&action=cetak&kelas_id=<?= $kelasId ?>" target="_blank" class="btn btn-success">🖨️ Cetak Laporan</a>
      </div>
      <div class="table-wrapper">
        <table class="table">
          <thead>
            <tr>
              <th class="table-no" rowspan="2" style="vertical-align:middle">No</th>
              <th rowspan="2" style="vertical-align:middle">NIM</th>
              <th rowspan="2" style="vertical-align:middle">Nama Mahasiswa</th>
              <th colspan="5" class="text-center">Kehadiran (Pertemuan)</th>
              <th rowspan="2" style="vertical-align:middle; text-align:center">% Hadir</th>
              <th rowspan="2" style="vertical-align:middle; text-align:center">Aksi</th>
            </tr>
            <tr>
              <th class="text-center text-success" title="Hadir">H</th>
              <th class="text-center text-warning" title="Terlambat">T</th>
              <th class="text-center text-info" title="Izin">I</th>
              <th class="text-center text-secondary" title="Sakit">S</th>
              <th class="text-center text-danger" title="Alpha">A</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($rekap)): ?>
              <tr><td colspan="10" class="text-center text-muted">Belum ada data rekap presensi (Sesi presensi mungkin belum pernah dibuka).</td></tr>
            <?php else: $no=1; foreach($rekap as $r): 
              $persen = (float)$r['persen'];
            ?>
              <tr>
                <td class="table-no"><?= $no++ ?></td>
                <td class="fw-600"><?= e($r['nim']) ?></td>
                <td><?= e($r['nama']) ?></td>
                <td class="text-center fw-600 text-success"><?= $r['hadir'] ?></td>
                <td class="text-center fw-600 text-warning"><?= $r['terlambat'] ?></td>
                <td class="text-center fw-600 text-info"><?= $r['izin'] ?></td>
                <td class="text-center fw-600 text-secondary"><?= $r['sakit'] ?></td>
                <td class="text-center fw-600 text-danger"><?= $r['alpha'] ?></td>
                <td class="text-center fw-bold <?= FormatHelper::persenClass($persen) ?>">
                  <?= FormatHelper::persen($persen) ?>
                </td>
                <td class="text-center">
                  <a href="?page=rekap&action=detail&mhs_id=<?= $r['mahasiswa_id'] ?>&kelas_id=<?= $kelasId ?>" class="btn btn-sm btn-light">Detail</a>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php elseif($kelasId): ?>
    <div class="alert alert-danger">Kelas tidak ditemukan.</div>
  <?php endif; ?>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

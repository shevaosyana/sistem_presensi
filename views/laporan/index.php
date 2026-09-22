<?php 
$pageTitle = 'Laporan Presensi';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
require_once ROOT_PATH.'/helpers/security.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Laporan Presensi</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Laporan</span>
      </div>
    </div>
    <?php if($kelasId && $kelas): ?>
      <a href="?page=laporan&action=cetak&kelas_id=<?= $kelasId ?>" target="_blank" class="btn btn-success">🖨️ Cetak / Print</a>
    <?php endif; ?>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <form action="" method="GET" class="d-flex" style="gap:15px; align-items:flex-end">
        <input type="hidden" name="page" value="laporan">
        <div style="flex:1">
          <label class="form-label" for="kelas_id">Pilih Kelas</label>
          <select name="kelas_id" id="kelas_id" class="form-control">
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($listKelas as $kls): ?>
              <option value="<?= $kls['id'] ?>" <?= $kelasId==$kls['id']?'selected':'' ?>><?= e($kls['nama']) ?> - <?= e($kls['mk_nama']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
      </form>
    </div>
  </div>

  <?php if($kelasId && $kelas): ?>
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <div>
          <h3 class="card-title">Rekap: <?= e($kelas['mk_nama']) ?> - Kelas <?= e($kelas['nama']) ?></h3>
          <p class="text-muted mb-0">Dosen: <?= e($kelas['dosen_nama']) ?> | <?= e($kelas['nama_semester']) ?> <?= e($kelas['ta_nama']) ?></p>
        </div>
      </div>
      <div class="table-wrapper">
        <table class="table">
          <thead>
            <tr>
              <th class="table-no" rowspan="2" style="vertical-align:middle">No</th>
              <th rowspan="2" style="vertical-align:middle">NIM</th>
              <th rowspan="2" style="vertical-align:middle">Nama</th>
              <th colspan="5" class="text-center">Kehadiran</th>
              <th rowspan="2" style="vertical-align:middle; text-align:center">Total</th>
              <th rowspan="2" style="vertical-align:middle; text-align:center">%</th>
              <th rowspan="2" style="vertical-align:middle; text-align:center">Status</th>
              <th rowspan="2" style="vertical-align:middle; text-align:center">Keamanan</th>
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
              <tr><td colspan="12" class="text-center text-muted">Belum ada data rekap.</td></tr>
            <?php else: $no=1; $db = Database::getInstance(); foreach($rekap as $r):
              $persen = (float)$r['persen'];
              $statusLbl = ($persen >= 80) ? 'AMAN' : (($persen >= 60) ? 'PERINGATAN' : 'TDK BOLEH UAS');
              $badgeClass = ($persen >= 80) ? 'badge-success' : (($persen >= 60) ? 'badge-warning' : 'badge-danger');
              $integrity = verifikasiIntegritasBatch($r['detail_ids'] ?? '', $db);
            ?>
              <tr>
                <td class="table-no"><?= $no++ ?></td>
                <td><?= e($r['nim']) ?></td>
                <td><?= e($r['nama']) ?></td>
                <td class="text-center text-success fw-600"><?= $r['hadir'] ?></td>
                <td class="text-center text-warning fw-600"><?= $r['terlambat'] ?></td>
                <td class="text-center text-info fw-600"><?= $r['izin'] ?></td>
                <td class="text-center text-secondary fw-600"><?= $r['sakit'] ?></td>
                <td class="text-center text-danger fw-600"><?= $r['alpha'] ?></td>
                <td class="text-center"><?= $r['total'] ?></td>
                <td class="text-center fw-bold <?= FormatHelper::persenClass($persen) ?>"><?= FormatHelper::persen($persen) ?></td>
                <td class="text-center"><span class="badge <?= $badgeClass ?>"><?= $statusLbl ?></span></td>
                <td class="text-center">
                  <?php if ($integrity['status'] && $integrity['pesan'] === 'Data Valid'): ?>
                    <span class="badge badge-success">Aman</span>
                  <?php elseif ($integrity['pesan'] === 'Tidak ada data'): ?>
                    <span class="badge badge-secondary">Tidak ada data</span>
                  <?php else: ?>
                    <span class="badge badge-danger"><strong>Termanipulasi!</strong></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

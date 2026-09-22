<?php 
$pageTitle = 'Dashboard Mahasiswa';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Dashboard Mahasiswa</h1>
      <p class="page-subtitle">Selamat datang, <?= e(SessionHelper::getNama()) ?>.</p>
    </div>
  </div>

  <?php if($semAktif): ?>
    <div class="alert alert-info" style="margin-bottom:1.5rem">
      <span class="alert-icon">ℹ️</span>
      Semester Aktif: <strong><?= e($semAktif['nama_semester']) ?> (<?= e($semAktif['ta_nama']) ?>)</strong>
    </div>
  <?php else: ?>
    <div class="alert alert-warning" style="margin-bottom:1.5rem">
      <span class="alert-icon">⚠️</span>
      Belum ada semester aktif yang diatur oleh Admin.
    </div>
  <?php endif; ?>

  <div class="grid-2">
    <!-- Jadwal Hari Ini -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">📅 Jadwal Hari Ini (<?= $hariIni ?>)</h3>
      </div>
      <div class="card-body" style="padding:0">
        <?php if(empty($jadwalHariIni)): ?>
          <div class="table-empty" style="padding:2rem">
            <div class="empty-icon">🏖️</div>
            <p>Tidak ada jadwal kuliah hari ini.</p>
          </div>
        <?php else: ?>
          <div class="table-wrapper">
            <table class="table">
              <tbody>
                <?php foreach($jadwalHariIni as $j): ?>
                  <tr>
                    <td>
                      <div class="fw-700"><?= FormatHelper::jamKuliah($j['jam_mulai'], $j['jam_selesai']) ?></div>
                      <div class="fs-sm text-muted">R.<?= e($j['ruangan_kode']) ?></div>
                    </td>
                    <td>
                      <div class="fw-600"><?= e($j['mk_nama']) ?></div>
                      <div class="fs-sm text-muted">Dosen: <?= e($j['dosen_nama']) ?></div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
      <div class="card-footer" style="text-align:center">
        <a href="?page=jadwal&action=saya" class="btn btn-light btn-sm">Lihat Seluruh Jadwal</a>
      </div>
    </div>

    <!-- Cepat Akses -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">🚀 Akses Cepat</h3>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;justify-content:center;height:calc(100% - 60px)">
        <a href="?page=presensi" class="btn btn-primary btn-lg" style="justify-content:center;padding:1rem;font-size:1.1rem">
          ✅ Lakukan Presensi
        </a>
        <a href="?page=rekap" class="btn btn-outline-primary btn-lg" style="justify-content:center;padding:1rem;font-size:1.1rem">
          📈 Lihat Rekap Kehadiran
        </a>
      </div>
    </div>
  </div>

  <!-- Rekap Semester Ini -->
  <div class="card mt-3">
    <div class="card-header">
      <h3 class="card-title">📊 Rekapitulasi Kehadiran Semester Ini</h3>
    </div>
    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>Mata Kuliah</th>
            <th class="text-center">Hadir</th>
            <th class="text-center">Sakit</th>
            <th class="text-center">Izin</th>
            <th class="text-center">Alpha</th>
            <th class="text-center">% Kehadiran</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($rekap)): ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada data rekapitulasi.</td></tr>
          <?php else: foreach($rekap as $r): ?>
            <tr>
              <td>
                <div class="fw-600"><?= e($r['kode'].' - '.$r['mk_nama']) ?></div>
                <div class="fs-sm text-muted">Kelas <?= e($r['kelas_nama']) ?></div>
              </td>
              <td class="text-center text-success fw-700"><?= $r['hadir'] ?></td>
              <td class="text-center"><?= $r['sakit'] ?></td>
              <td class="text-center"><?= $r['izin'] ?></td>
              <td class="text-center text-danger fw-700"><?= $r['alpha'] ?></td>
              <td class="text-center fw-700 <?= FormatHelper::persenClass((float)$r['persen']) ?>">
                <?= $r['persen'] ?>%
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

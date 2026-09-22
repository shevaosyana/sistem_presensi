<?php 
$pageTitle = 'Dashboard Dosen';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Dashboard Dosen</h1>
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
    <!-- Presensi Berlangsung -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">🔴 Kelas Sedang Berlangsung</h3>
      </div>
      <div class="card-body" style="padding:0">
        <?php if(empty($presensiAktif)): ?>
          <div class="table-empty" style="padding:2rem">
            <div class="empty-icon">😴</div>
            <p>Tidak ada presensi yang sedang terbuka.</p>
          </div>
        <?php else: ?>
          <div class="table-wrapper">
            <table class="table">
              <tbody>
                <?php foreach($presensiAktif as $p): ?>
                  <tr>
                    <td>
                      <div class="fw-700"><?= e($p['mk_nama']) ?></div>
                      <div class="fs-sm text-muted">Kelas <?= e($p['kelas_nama']) ?> • R.<?= e($p['ruangan_kode']) ?></div>
                    </td>
                    <td class="text-right">
                      <a href="?page=presensi&action=kelola&id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Kelola Sesi</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

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
                      <div class="fs-sm text-muted">Kelas <?= e($j['kelas_nama']) ?></div>
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
  </div>

  <div class="card mt-3">
    <div class="card-header">
      <h3 class="card-title">🏫 Kelas Saya (Semester Ini)</h3>
    </div>
    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
            <th>SKS</th>
            <th width="100">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($kelasSaya)): ?>
            <tr><td colspan="4" class="text-center text-muted">Tidak ada kelas yang diampu pada semester ini.</td></tr>
          <?php else: foreach($kelasSaya as $k): ?>
            <tr>
              <td><?= e($k['mk_kode'].' - '.$k['mk_nama']) ?></td>
              <td><?= e($k['nama']) ?></td>
              <td><?= e($k['sks']) ?> SKS</td>
              <td>
                <a href="?page=rekap&kelas_id=<?= $k['id'] ?>" class="btn btn-outline-primary btn-sm">Lihat Rekap</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

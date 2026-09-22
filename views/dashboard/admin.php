<?php 
$pageTitle = 'Dashboard Admin';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Dashboard Administrator</h1>
      <p class="page-subtitle">Selamat datang di <?= APP_FULL_NAME ?>.</p>
    </div>
  </div>

  <div class="stat-grid">
    <a href="?page=presensi" class="stat-card">
      <div class="stat-icon blue">📋</div>
      <div class="stat-info">
        <div class="stat-value"><?= number_format($data['presensi_hari_ini']) ?></div>
        <div class="stat-label">Presensi Hari Ini</div>
      </div>
    </a>
    <a href="?page=kelas" class="stat-card">
      <div class="stat-icon green">🏫</div>
      <div class="stat-info">
        <div class="stat-value"><?= number_format($data['total_jadwal']) ?></div>
        <div class="stat-label">Total Kelas Aktif</div>
      </div>
    </a>
    <a href="?page=mahasiswa" class="stat-card">
      <div class="stat-icon orange">🎓</div>
      <div class="stat-info">
        <div class="stat-value"><?= number_format($data['total_mahasiswa']) ?></div>
        <div class="stat-label">Total Mahasiswa</div>
      </div>
    </a>
    <a href="?page=dosen" class="stat-card">
      <div class="stat-icon cyan">👨‍🏫</div>
      <div class="stat-info">
        <div class="stat-value"><?= number_format($data['total_dosen']) ?></div>
        <div class="stat-label">Total Dosen</div>
      </div>
    </a>
  </div>

  <div class="grid-2">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">📌 Presensi Perkuliahan</h3>
      </div>
      <div class="card-body" style="text-align:center;padding:3rem 1rem">
        <div style="font-size:3.5rem;font-weight:700;color:var(--primary);line-height:1">
          <?= number_format($data['presensi_hari_ini']) ?>
        </div>
        <p class="text-muted mt-1">Sesi presensi aktif berlangsung hari ini.</p>
        <a href="?page=presensi" class="btn btn-outline-primary mt-2">Buka Menu Presensi</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">⚡ Akses Cepat Presensi</h3>
      </div>
      <div class="card-body">
        <div style="display:flex;flex-direction:column;gap:.75rem">
          <a href="?page=presensi" class="btn btn-light" style="justify-content:flex-start">📋 Kelola Sesi Presensi</a>
          <a href="?page=kelas" class="btn btn-light" style="justify-content:flex-start">🏫 Data Kelas & Anggota</a>
          <a href="?page=mahasiswa" class="btn btn-light" style="justify-content:flex-start">🎓 Data Mahasiswa</a>
          <a href="?page=laporan" class="btn btn-light" style="justify-content:flex-start">📊 Rekapitulasi & Laporan</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

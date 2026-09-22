<?php
$role    = SessionHelper::getRole();
$nama    = SessionHelper::getNama();
$foto    = SessionHelper::getFoto();
$curPage = $_GET['page'] ?? '';

$menuAdmin = [
    ['page'=>'dashboard',    'icon'=>'🏠', 'label'=>'Dashboard'],
    ['page'=>'presensi',     'icon'=>'📋', 'label'=>'Kelola Presensi'],
    ['page'=>'kelas',        'icon'=>'🏫', 'label'=>'Data Kelas'],
    ['page'=>'mahasiswa',    'icon'=>'🎓', 'label'=>'Data Mahasiswa'],
    ['page'=>'dosen',        'icon'=>'👨‍🏫', 'label'=>'Data Dosen'],
    ['page'=>'laporan',      'icon'=>'📊', 'label'=>'Rekap & Laporan'],
];

$menuDosen = [
    ['page'=>'dashboard', 'icon'=>'🏠', 'label'=>'Dashboard'],
    ['page'=>'presensi',  'icon'=>'📋', 'label'=>'Kelola Presensi'],
    ['page'=>'rekap',     'icon'=>'📊', 'label'=>'Rekap Kehadiran'],
    ['page'=>'user',      'icon'=>'🙍', 'label'=>'Profil Saya', 'action'=>'profil'],
];

$menuMhs = [
    ['page'=>'dashboard', 'icon'=>'🏠', 'label'=>'Dashboard'],
    ['page'=>'presensi',  'icon'=>'📋', 'label'=>'Presensi Saya'],
    ['page'=>'rekap',     'icon'=>'📊', 'label'=>'Riwayat Kehadiran'],
    ['page'=>'user',      'icon'=>'🙍', 'label'=>'Profil Saya', 'action'=>'profil'],
];

$menus = match($role) { 'admin' => $menuAdmin, 'dosen' => $menuDosen, default => $menuMhs };

$initials = strtoupper(mb_substr($nama,0,1));
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-icon">📋</div>
    <div class="sidebar-brand-text">
      <?= APP_NAME ?>
      <span class="sidebar-brand-sub"><?= FAKULTAS_NAME ?></span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php foreach ($menus as $m): ?>
      <?php
        $href = BASE_URL.'/?page='.$m['page'].(isset($m['action'])? '&action='.$m['action'] : '');
        $active = ($curPage === $m['page']) ? 'active' : '';
      ?>
      <a href="<?= $href ?>" class="nav-item <?= $active ?>" data-page="<?= $m['page'] ?>">
        <span class="nav-icon"><?= $m['icon'] ?></span>
        <?= $m['label'] ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar">
        <?php if ($foto): ?>
          <img src="<?= UPLOAD_URL.e($foto) ?>" alt="foto">
        <?php else: ?>
          <?= $initials ?>
        <?php endif; ?>
      </div>
      <div>
        <div class="user-name"><?= e($nama) ?></div>
        <div class="user-role"><?= ucfirst($role) ?></div>
      </div>
    </div>
    <a href="<?= BASE_URL ?>/?page=auth&action=logout" class="btn btn-outline-danger btn-sm" style="width:100%;justify-content:center">
      🚪 Logout
    </a>
  </div>
</aside>
<div class="main-content">

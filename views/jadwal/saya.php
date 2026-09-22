<?php 
$pageTitle = 'Jadwal Saya';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Jadwal Kuliah Saya</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Jadwal Saya</span>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Mata Kuliah Semester Ini</h3>
    </div>
    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Hari / Waktu</th>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
            <?php if($role==='mahasiswa'): ?>
              <th>Dosen</th>
            <?php endif; ?>
            <th>Ruangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($jadwal)): ?>
            <tr><td colspan="<?= $role==='mahasiswa'?7:6 ?>" class="text-center text-muted py-3">Anda belum memiliki jadwal kuliah.</td></tr>
          <?php else: $no=1; foreach($jadwal as $j): 
            $isHariIni = ($j['hari'] === $hariIni);
          ?>
            <tr class="<?= $isHariIni ? 'table-primary' : '' ?>">
              <td class="table-no"><?= $no++ ?></td>
              <td>
                <span class="fw-600 <?= $isHariIni ? 'text-primary' : '' ?>"><?= $j['hari'] ?></span>
                <?php if($isHariIni): ?> <span class="badge badge-primary">Hari Ini</span> <?php endif; ?><br>
                <small class="text-muted"><?= FormatHelper::jamKuliah($j['jam_mulai'], $j['jam_selesai']) ?></small>
              </td>
              <td>
                <span class="fw-600"><?= e($j['mk_nama']) ?></span><br>
                <small class="text-muted"><?= e($j['mk_kode']) ?> (<?= $j['sks'] ?> SKS)</small>
              </td>
              <td><?= e($j['kelas_nama']) ?></td>
              <?php if($role==='mahasiswa'): ?>
                <td><?= e($j['dosen_nama']) ?></td>
              <?php endif; ?>
              <td>
                <?= e($j['ruangan_nama']) ?>
                <small class="text-muted d-block">(<?= e($j['ruangan_kode']) ?>)</small>
              </td>
              <td>
                <a href="?page=presensi&jadwal_id=<?= $j['id'] ?>" class="btn btn-sm btn-primary">Lihat Presensi</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

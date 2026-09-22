<?php 
$pageTitle = 'Dashboard Presensi';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Presensi: <?= e($jadwal['mk_nama']) ?></h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=jadwal&action=saya">Jadwal Saya</a> / <span>Presensi</span>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Info Jadwal -->
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-header">
          <h3 class="card-title">Informasi Jadwal</h3>
        </div>
        <div class="card-body">
          <table class="table table-sm">
            <tr><th width="40%">Mata Kuliah</th><td><?= e($jadwal['mk_nama']) ?></td></tr>
            <tr><th>Kelas</th><td><?= e($jadwal['kelas_nama']) ?></td></tr>
            <tr><th>Hari</th><td><?= $jadwal['hari'] ?></td></tr>
            <tr><th>Waktu</th><td><?= FormatHelper::jam($jadwal['jam_mulai']) ?> - <?= FormatHelper::jam($jadwal['jam_selesai']) ?></td></tr>
            <tr><th>Ruangan</th><td><?= e($jadwal['ruangan_nama']) ?></td></tr>
            <tr><th>Dosen</th><td><?= e($jadwal['dosen_nama']) ?></td></tr>
          </table>
        </div>
      </div>
      
      <!-- Aksi Hari Ini -->
      <div class="card">
        <div class="card-header text-center">
          <h3 class="card-title w-100">Presensi Hari Ini</h3>
        </div>
        <div class="card-body text-center">
          <?php if($jadwal['hari'] !== FormatHelper::hariIni()): ?>
            <div class="alert alert-warning mb-0">
              Bukan hari jadwal perkuliahan.
            </div>
          <?php else: ?>
            
            <?php if($role === 'dosen'): ?>
              <?php if(!$sesiAktif): ?>
                <a href="?page=presensi&action=buka&id=<?= $jadwal['id'] ?>" class="btn btn-success btn-lg w-100 py-3" onclick="return confirm('Buka sesi presensi sekarang?')">
                  🟢 BUKA PRESENSI SEKARANG
                </a>
              <?php else: ?>
                <div class="alert alert-info">
                  Sesi presensi sedang <strong>AKTIF</strong>.<br>
                  Pertemuan ke-<?= $sesiAktif['pertemuan_ke'] ?>
                </div>
                <a href="?page=presensi&action=kelola&id=<?= $sesiAktif['id'] ?>" class="btn btn-primary w-100 mb-2">Lihat Mahasiswa Absen</a>
                <a href="?page=presensi&action=tutup&id=<?= $sesiAktif['id'] ?>" class="btn btn-danger w-100" onclick="return confirm('Tutup presensi? Mhs yang belum absen akan otomatis Alpha.')">🔴 TUTUP PRESENSI</a>
              <?php endif; ?>
            
            <?php elseif($role === 'mahasiswa'): ?>
              <?php if(!$sesiAktif): ?>
                <div class="alert alert-secondary mb-0">
                  Sesi presensi belum dibuka oleh Dosen.
                </div>
              <?php elseif($sudahAbsen): ?>
                <div class="alert alert-success mb-0">
                  ✅ Anda sudah melakukan presensi hari ini.
                </div>
              <?php else: ?>
                <form action="?page=presensi&action=submit" method="POST">
                  <input type="hidden" name="presensi_id" value="<?= $sesiAktif['id'] ?>">
                  <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                    👆 KLIK UNTUK HADIR
                  </button>
                  <small class="d-block mt-2 text-muted">Batas keterlambatan: <?= $jadwal['toleransi_menit'] ?> menit.</small>
                </form>
              <?php endif; ?>
            <?php endif; ?>

          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Histori Presensi -->
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Histori Pertemuan</h3>
        </div>
        <div class="table-wrapper">
          <table class="table">
            <thead>
              <tr>
                <th>Pertemuan</th>
                <th>Tanggal</th>
                <th>Status Sesi</th>
                <?php if($role === 'dosen'): ?><th>Aksi</th><?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($history)): ?>
                <tr><td colspan="<?= $role==='dosen'?4:3 ?>" class="text-center text-muted">Belum ada histori pertemuan.</td></tr>
              <?php else: foreach($history as $h): ?>
                <tr>
                  <td>Ke-<?= $h['pertemuan_ke'] ?></td>
                  <td><?= FormatHelper::tanggalIndo($h['tanggal']) ?></td>
                  <td>
                    <?php if($h['status'] === 'AKTIF'): ?>
                      <span class="badge badge-success">Sedang Aktif</span>
                    <?php else: ?>
                      <span class="badge badge-secondary">Selesai</span>
                    <?php endif; ?>
                  </td>
                  <?php if($role === 'dosen'): ?>
                    <td>
                      <a href="?page=presensi&action=kelola&id=<?= $h['id'] ?>" class="btn btn-sm btn-info">Detail / Kelola</a>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

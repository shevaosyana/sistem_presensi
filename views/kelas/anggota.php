<?php 
$pageTitle = 'Anggota Kelas';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Anggota Kelas: <?= e($kelasInfo['nama']) ?></h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=kelas">Kelas</a> / <span>Anggota</span>
      </div>
    </div>
    <a href="?page=kelas" class="btn btn-light">Kembali ke Kelas</a>
  </div>

  <div class="grid-2">
    <!-- Form Tambah Mahasiswa -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">➕ Tambah Mahasiswa ke Kelas</div>
      </div>
      <form action="?page=kelas&action=tambah_anggota&id=<?= $kelasInfo['id'] ?>" method="POST">
        <div class="card-body">
          <div class="form-group">
            <label class="form-label" for="mahasiswa_id">Pilih Mahasiswa</label>
            <select name="mahasiswa_id" id="mahasiswa_id" class="form-control" required>
              <option value="">-- Pilih Mahasiswa --</option>
              <?php foreach($listMahasiswa as $m): ?>
                <option value="<?= $m['id'] ?>"><?= e($m['nim'].' - '.$m['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-primary">Tambah ke Kelas</button>
        </div>
      </form>
    </div>

    <!-- Info Kelas -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">ℹ️ Informasi Kelas</div>
      </div>
      <div class="card-body">
        <table class="table" style="background:transparent;border:none">
          <tr><th style="width:150px;background:none;padding:0.5rem 0">Mata Kuliah</th><td style="padding:0.5rem 0">: <?= e($kelasInfo['mk_nama']) ?></td></tr>
          <tr><th style="background:none;padding:0.5rem 0">Dosen Pengampu</th><td style="padding:0.5rem 0">: <?= e($kelasInfo['dosen_nama']) ?></td></tr>
          <tr><th style="background:none;padding:0.5rem 0">Kapasitas</th><td style="padding:0.5rem 0">: <?= count($anggota) ?> / <?= $kelasInfo['kapasitas'] ?> Mahasiswa</td></tr>
        </table>
      </div>
    </div>
  </div>

  <!-- Daftar Anggota -->
  <div class="card mt-3">
    <div class="card-header">
      <div class="card-title">👥 Daftar Anggota Kelas</div>
    </div>
    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Program Studi</th>
            <th>Waktu Daftar</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($anggota)): ?>
            <tr><td colspan="6" class="text-center text-muted py-3">Belum ada mahasiswa di kelas ini.</td></tr>
          <?php else: $no=1; foreach($anggota as $a): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($a['nim']) ?></td>
              <td><?= e($a['nama']) ?></td>
              <td><?= e($a['prodi_nama'] ?? $a['prodi_kode'] ?? '-') ?></td>
              <td><?= date('d-m-Y H:i', strtotime($a['waktu_daftar'])) ?></td>
              <td class="table-action">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=kelas&action=hapus_anggota&id=<?= $kelasInfo['id'] ?>&anggota_id=<?= $a['id'] ?>', 'Mahasiswa <?= e($a['nama']) ?> dari kelas ini')">Keluarkan</button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

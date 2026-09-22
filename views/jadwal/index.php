<?php 
$pageTitle = 'Master Jadwal Kuliah';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Master Jadwal Kuliah</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Jadwal</span>
      </div>
    </div>
    <a href="?page=jadwal&action=create" class="btn btn-primary">➕ Tambah Jadwal</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="jadwal">
        <select name="kelas" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Kelas --</option>
          <?php foreach($listKelas as $kls): ?>
            <option value="<?= $kls['id'] ?>" <?= $kelasId==$kls['id']?'selected':'' ?>><?= e($kls['nama']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="search" class="form-control" placeholder="Cari matkul, dosen, ruangan..." value="<?= e($search) ?>">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if($search || $kelasId): ?>
          <a href="?page=jadwal" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Hari / Waktu</th>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
            <th>Dosen</th>
            <th>Ruangan</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($jadwal)): ?>
            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($jadwal as $j): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td>
                <span class="fw-600"><?= $j['hari'] ?></span><br>
                <small class="text-muted"><?= FormatHelper::jamKuliah($j['jam_mulai'], $j['jam_selesai']) ?></small>
              </td>
              <td>
                <span class="fw-600"><?= e($j['mk_nama']) ?></span><br>
                <small class="text-muted"><?= e($j['mk_kode']) ?></small>
              </td>
              <td>
                <?= e($j['kelas_nama']) ?><br>
                <span class="badge badge-info"><?= $j['nama_semester'] ?></span>
              </td>
              <td><?= e($j['dosen_nama']) ?></td>
              <td>
                <?= e($j['ruangan_nama']) ?>
                <small class="text-muted d-block">(<?= e($j['ruangan_kode']) ?>)</small>
              </td>
              <td class="table-action">
                <a href="?page=jadwal&action=edit&id=<?= $j['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=jadwal&action=delete&id=<?= $j['id'] ?>', 'Jadwal <?= e($j['mk_nama']) ?>')">🗑️</button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    
    <?php if($totalPages > 1): ?>
    <div class="card-footer">
      <div class="pagination">
        <?php for($i=1; $i<=$totalPages; $i++): ?>
          <a href="?page=jadwal&halaman=<?= $i ?><?= $search?"&search=".urlencode($search):'' ?><?= $kelasId?"&kelas=$kelasId":'' ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Data Kelas';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Kelas</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Kelas</span>
      </div>
    </div>
    <a href="?page=kelas&action=create" class="btn btn-primary">➕ Tambah Kelas</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="kelas">
        <select name="smt" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Semester --</option>
          <?php foreach($listSemester as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $smtId==$s['id']?'selected':'' ?>><?= e($s['ta_nama'].' - '.$s['nama_semester']) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if($smtId): ?>
          <a href="?page=kelas" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Nama Kelas</th>
            <th>Mata Kuliah</th>
            <th>Dosen Pengampu</th>
            <th>Semester</th>
            <th class="text-center">Jml Mhs</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($kelas)): ?>
            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($kelas as $k): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($k['nama']) ?></td>
              <td><?= e($k['mk_nama']) ?></td>
              <td><?= e($k['dosen_nama']) ?></td>
              <td><?= e($k['ta_nama'].' - '.$k['nama_semester']) ?></td>
              <td class="text-center">
                <span class="badge <?= $k['total_anggota'] >= $k['kapasitas'] ? 'badge-danger' : 'badge-info' ?>">
                  <?= $k['total_anggota'] ?> / <?= $k['kapasitas'] ?>
                </span>
              </td>
              <td class="table-action">
                <a href="?page=kelas&action=anggota&id=<?= $k['id'] ?>" class="btn btn-info btn-sm" title="Kelola Anggota Kelas">👥</a>
                <a href="?page=kelas&action=edit&id=<?= $k['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=kelas&action=delete&id=<?= $k['id'] ?>', 'Kelas <?= e($k['nama']) ?>')">🗑️</button>
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
          <a href="?page=kelas&halaman=<?= $i ?><?= $smtId?"&smt=$smtId":'' ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

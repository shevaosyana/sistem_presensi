<?php 
$pageTitle = 'Data Semester';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Semester</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Semester</span>
      </div>
    </div>
    <a href="?page=semester&action=create" class="btn btn-primary">➕ Tambah Semester</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="semester">
        <select name="ta" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Tahun Akademik --</option>
          <?php foreach($listTA as $taItem): ?>
            <option value="<?= $taItem['id'] ?>" <?= $taId==$taItem['id']?'selected':'' ?>><?= e($taItem['nama']) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if($taId): ?>
          <a href="?page=semester" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Tahun Akademik</th>
            <th>Semester</th>
            <th>Status Aktif</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($semester)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($semester as $s): ?>
            <tr class="<?= $s['is_aktif']==1 ? 'bg-light' : '' ?>">
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($s['ta_nama']) ?></td>
              <td><?= e($s['nama_semester']) ?></td>
              <td>
                <?php if($s['is_aktif']==1): ?>
                  <span class="badge badge-success">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-secondary">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td class="table-action">
                <?php if($s['is_aktif']!=1): ?>
                  <a href="?page=semester&action=set_aktif&id=<?= $s['id'] ?>" class="btn btn-info btn-sm" title="Set Semester Aktif">🌟</a>
                <?php endif; ?>
                <a href="?page=semester&action=edit&id=<?= $s['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=semester&action=delete&id=<?= $s['id'] ?>', 'Semester <?= e($s['nama_semester']) ?> TA <?= e($s['ta_nama']) ?>')">🗑️</button>
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
          <a href="?page=semester&halaman=<?= $i ?><?= $taId?"&ta=$taId":'' ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

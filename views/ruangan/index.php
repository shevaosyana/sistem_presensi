<?php 
$pageTitle = 'Master Ruangan';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Master Ruangan</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Ruangan</span>
      </div>
    </div>
    <a href="?page=ruangan&action=create" class="btn btn-primary">➕ Tambah Ruangan</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="ruangan">
        <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode ruangan..." value="<?= e($search) ?>">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if($search): ?>
          <a href="?page=ruangan" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Kode</th>
            <th>Nama Ruangan</th>
            <th>Kapasitas</th>
            <th>Gedung</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($ruangan)): ?>
            <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($ruangan as $r): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($r['kode']) ?></td>
              <td><?= e($r['nama']) ?></td>
              <td><?= $r['kapasitas'] ?> Orang</td>
              <td><?= e($r['gedung'] ?? '-') ?></td>
              <td class="table-action">
                <a href="?page=ruangan&action=edit&id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=ruangan&action=delete&id=<?= $r['id'] ?>', 'Ruangan <?= e($r['nama']) ?>')">🗑️</button>
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
          <a href="?page=ruangan&halaman=<?= $i ?><?= $search?"&search=".urlencode($search):'' ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

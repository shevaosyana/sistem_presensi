<?php 
$pageTitle = 'Tahun Akademik';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tahun Akademik</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Tahun Akademik</span>
      </div>
    </div>
    <a href="?page=tahunakademik&action=create" class="btn btn-primary">➕ Tambah Tahun Akademik</a>
  </div>

  <div class="card">
    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Nama Tahun Akademik</th>
            <th>Tahun Mulai</th>
            <th>Tahun Selesai</th>
            <th>Status</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($ta)): ?>
            <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($ta as $t): ?>
            <tr class="<?= $t['status']=='aktif' ? 'bg-light' : '' ?>">
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($t['nama']) ?></td>
              <td><?= $t['tahun_mulai'] ?></td>
              <td><?= $t['tahun_selesai'] ?></td>
              <td>
                <?php if($t['status']=='aktif'): ?>
                  <span class="badge badge-success">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-secondary">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td class="table-action">
                <?php if($t['status']!='aktif'): ?>
                  <a href="?page=tahunakademik&action=set_aktif&id=<?= $t['id'] ?>" class="btn btn-info btn-sm" title="Set Aktif">🌟</a>
                <?php endif; ?>
                <a href="?page=tahunakademik&action=edit&id=<?= $t['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=tahunakademik&action=delete&id=<?= $t['id'] ?>', 'Tahun Akademik <?= e($t['nama']) ?>')">🗑️</button>
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
          <a href="?page=tahunakademik&halaman=<?= $i ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Data Program Studi';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Program Studi</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Program Studi</span>
      </div>
    </div>
    <a href="?page=prodi&action=create" class="btn btn-primary">➕ Tambah Prodi</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="prodi">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" name="search" class="form-control" placeholder="Cari Kode / Nama..." value="<?= e($search) ?>">
        </div>
        <?php if($search): ?>
          <a href="?page=prodi" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Kode</th>
            <th>Jenjang</th>
            <th>Nama Program Studi</th>
            <th class="text-center">Jml Mahasiswa</th>
            <th class="text-center">Jml Dosen</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($prodi)): ?>
            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($prodi as $p): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($p['kode']) ?></td>
              <td><?= e($p['jenjang']) ?></td>
              <td class="fw-600"><?= e($p['nama']) ?></td>
              <td class="text-center">
                <span class="badge badge-info"><?= $p['total_mhs'] ?></span>
              </td>
              <td class="text-center">
                <span class="badge badge-warning"><?= $p['total_dosen'] ?></span>
              </td>
              <td class="table-action">
                <a href="?page=prodi&action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=prodi&action=delete&id=<?= $p['id'] ?>', 'Prodi <?= e($p['nama']) ?>')">🗑️</button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    
    <?php if($totalPages > 1): ?>
    <div class="card-footer">
      <div class="pagination">
        <?php for($i=1; $i<=$totalPages; $i++): 
          $url = "?page=prodi&halaman=$i";
          if($search) $url .= "&search=".urlencode($search);
        ?>
          <a href="<?= $url ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

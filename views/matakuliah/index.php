<?php 
$pageTitle = 'Mata Kuliah';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Mata Kuliah</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Mata Kuliah</span>
      </div>
    </div>
    <a href="?page=matakuliah&action=create" class="btn btn-primary">➕ Tambah Mata Kuliah</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="matakuliah">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" name="search" class="form-control" placeholder="Cari Kode / Nama MK..." value="<?= e($search) ?>">
        </div>
        <select name="prodi" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Prodi --</option>
          <?php foreach($listProdi as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $prodi==$p['id']?'selected':'' ?>><?= e($p['nama']) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if($search || $prodi): ?>
          <a href="?page=matakuliah" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Kode MK</th>
            <th>Nama Mata Kuliah</th>
            <th>SKS</th>
            <th>Program Studi</th>
            <th>Jenis</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($matakuliah)): ?>
            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($matakuliah as $mk): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($mk['kode']) ?></td>
              <td class="fw-600"><?= e($mk['nama']) ?></td>
              <td><?= $mk['sks'] ?></td>
              <td><?= e($mk['prodi_nama']) ?></td>
              <td>
                <span class="badge badge-<?= $mk['jenis']=='Teori'?'info':($mk['jenis']=='Praktikum'?'warning':'success') ?>">
                  <?= e($mk['jenis']) ?>
                </span>
              </td>
              <td class="table-action">
                <a href="?page=matakuliah&action=edit&id=<?= $mk['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=matakuliah&action=delete&id=<?= $mk['id'] ?>', 'Mata Kuliah <?= e($mk['nama']) ?>')">🗑️</button>
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
          $url = "?page=matakuliah&halaman=$i";
          if($search) $url .= "&search=".urlencode($search);
          if($prodi) $url .= "&prodi=".urlencode($prodi);
        ?>
          <a href="<?= $url ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

<?php 
$pageTitle = 'Data Dosen';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Dosen</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Dosen</span>
      </div>
    </div>
    <a href="?page=dosen&action=create" class="btn btn-primary">➕ Tambah Dosen</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="dosen">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" name="search" class="form-control" placeholder="Cari NIDN / Nama..." value="<?= e($search) ?>">
        </div>
        <select name="prodi" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Prodi --</option>
          <?php foreach($listProdi as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $prodi==$p['id']?'selected':'' ?>><?= e($p['nama']) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if($search || $prodi): ?>
          <a href="?page=dosen" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>NIDN</th>
            <th>Nama Lengkap</th>
            <th>Program Studi</th>
            <th>L/P</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($dosen)): ?>
            <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($dosen as $d): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($d['nidn']) ?></td>
              <td><?= e($d['nama']) ?></td>
              <td><?= e($d['prodi_nama']) ?></td>
              <td><?= $d['jenis_kelamin'] ?></td>
              <td class="table-action">
                <a href="?page=dosen&action=edit&id=<?= $d['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=dosen&action=delete&id=<?= $d['id'] ?>', 'Dosen <?= e($d['nama']) ?>')">🗑️</button>
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
          $url = "?page=dosen&halaman=$i";
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

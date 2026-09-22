<?php 
$pageTitle = 'Data Mahasiswa';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Mahasiswa</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Mahasiswa</span>
      </div>
    </div>
    <a href="?page=mahasiswa&action=create" class="btn btn-primary">➕ Tambah Mahasiswa</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="mahasiswa">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari NIM / Nama..." value="<?= e($search) ?>">
        </div>
        <select name="prodi" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Prodi --</option>
          <?php foreach($listProdi as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $prodi==$p['id']?'selected':'' ?>><?= e($p['nama']) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="angkatan" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Angkatan --</option>
          <?php foreach($listAngkatan as $a): ?>
            <option value="<?= $a ?>" <?= $angk==$a?'selected':'' ?>><?= e($a) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if($search || $prodi || $angk): ?>
          <a href="?page=mahasiswa" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Program Studi</th>
            <th>L/P</th>
            <th>Angkatan</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($mahasiswa)): ?>
            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($mahasiswa as $m): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($m['nim']) ?></td>
              <td><?= e($m['nama']) ?></td>
              <td><?= e($m['prodi_nama']) ?></td>
              <td><?= $m['jenis_kelamin'] ?></td>
              <td><?= $m['angkatan'] ?></td>
              <td class="table-action">
                <a href="?page=mahasiswa&action=edit&id=<?= $m['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=mahasiswa&action=delete&id=<?= $m['id'] ?>', 'Mahasiswa <?= e($m['nama']) ?>')">🗑️</button>
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
          $url = "?page=mahasiswa&halaman=$i";
          if($search) $url .= "&search=".urlencode($search);
          if($prodi) $url .= "&prodi=".urlencode($prodi);
          if($angk) $url .= "&angkatan=".urlencode($angk);
        ?>
          <a href="<?= $url ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

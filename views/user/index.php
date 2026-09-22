<?php 
$pageTitle = 'Manajemen User';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Manajemen User</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>User</span>
      </div>
    </div>
    <a href="?page=user&action=create" class="btn btn-primary">➕ Tambah User</a>
  </div>

  <div class="card">
    <div class="card-header">
      <form action="" method="GET" class="filter-bar" style="margin:0">
        <input type="hidden" name="page" value="user">
        <select name="role" class="form-control" style="width:auto" onchange="this.form.submit()">
          <option value="">-- Semua Role --</option>
          <?php foreach($roles as $r): ?>
            <option value="<?= $r['id'] ?>" <?= $roleId==$r['id']?'selected':'' ?>><?= ucfirst($r['nama_role']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="search" class="form-control" placeholder="Cari username..." value="<?= e($search) ?>">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if($search || $roleId): ?>
          <a href="?page=user" class="btn btn-light">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th class="table-no">No</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th class="table-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($users)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
          <?php else: $no = ($page-1)*$perPage+1; foreach($users as $u): ?>
            <tr>
              <td class="table-no"><?= $no++ ?></td>
              <td class="fw-600"><?= e($u['username']) ?></td>
              <td><?= ucfirst($u['role_nama']) ?></td>
              <td>
                <?php if($u['is_active']==1): ?>
                  <span class="badge badge-success">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-danger">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td class="table-action">
                <?php if($u['id'] !== SessionHelper::getUserId()): ?>
                  <a href="?page=user&action=toggle&id=<?= $u['id'] ?>" class="btn btn-info btn-sm" title="Ubah Status">🔄</a>
                <?php endif; ?>
                <a href="?page=user&action=edit&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                <?php if($u['id'] !== SessionHelper::getUserId()): ?>
                  <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('?page=user&action=delete&id=<?= $u['id'] ?>', 'User <?= e($u['username']) ?>')">🗑️</button>
                <?php endif; ?>
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
          <a href="?page=user&halaman=<?= $i ?><?= $search?"&search=".urlencode($search):'' ?><?= $roleId?"&role=$roleId":'' ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

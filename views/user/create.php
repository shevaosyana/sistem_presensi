<?php 
$pageTitle = 'Tambah User';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Tambah User</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=user">User</a> / <span>Tambah</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <form action="?page=user&action=store" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="username">Username <span class="required">*</span></label>
          <input type="text" name="username" id="username" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="password">Password <span class="required">*</span></label>
          <input type="password" name="password" id="password" class="form-control" minlength="6" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="role_id">Role <span class="required">*</span></label>
          <select name="role_id" id="role_id" class="form-control" required>
            <option value="">-- Pilih Role --</option>
            <?php foreach($roles as $r): ?>
              <option value="<?= $r['id'] ?>"><?= ucfirst($r['nama_role']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">
            <input type="checkbox" name="is_active" value="1" checked> User Aktif
          </label>
        </div>
      </div>
      <div class="card-footer form-actions">
        <a href="?page=user" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

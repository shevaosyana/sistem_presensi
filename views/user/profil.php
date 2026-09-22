<?php 
$pageTitle = 'Profil Saya';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Profil Saya</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <span>Profil</span>
      </div>
    </div>
  </div>

  <div class="card" style="max-width: 600px">
    <div class="card-header">
      <div class="card-title">Pengaturan Akun</div>
    </div>
    <form action="?page=user&action=update_profil" method="POST">
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <input type="text" class="form-control" value="<?= e($user['username']) ?>" readonly style="background:#f3f4f6">
        </div>
        
        <div class="form-group">
          <label class="form-label" for="role">Hak Akses</label>
          <input type="text" class="form-control" value="<?= ucfirst($user['role_nama'] ?? SessionHelper::getRole()) ?>" readonly style="background:#f3f4f6">
        </div>

        <hr>
        <h5>Ganti Password</h5>
        <small class="text-muted d-block mb-3">Kosongkan semua isian di bawah jika Anda tidak ingin mengganti password.</small>

        <div class="form-group">
          <label class="form-label" for="password_lama">Password Lama</label>
          <input type="password" name="password_lama" id="password_lama" class="form-control">
        </div>

        <div class="form-group">
          <label class="form-label" for="password_baru">Password Baru</label>
          <input type="password" name="password_baru" id="password_baru" class="form-control" minlength="6">
        </div>

        <div class="form-group">
          <label class="form-label" for="konfirmasi_password">Konfirmasi Password Baru</label>
          <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" minlength="6">
        </div>
      </div>
      <div class="card-footer form-actions">
        <button type="submit" class="btn btn-primary">💾 Update Profil</button>
      </div>
    </form>
  </div>
</div>
<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>

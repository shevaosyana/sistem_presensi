<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= ASSET_URL ?>/css/style.css">
</head>
<body class="login-page">
<div class="login-box">
  <div class="login-logo">
    <div class="login-logo-icon">📋</div>
    <h1 class="login-title"><?= APP_NAME ?></h1>
    <p class="login-subtitle"><?= FAKULTAS_NAME ?></p>
  </div>
  
  <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type']==='error'?'danger':$flash['type'] ?>" style="font-size:.8rem;padding:.75rem">
      <span class="alert-icon"><?= $flash['type']==='error'?'❌':($flash['type']==='success'?'✅':'ℹ️') ?></span>
      <?= e($flash['message']) ?>
    </div>
  <?php endif; ?>

  <form action="<?= BASE_URL ?>/?page=auth&action=proses" method="POST">
    <div class="form-group">
      <label class="form-label" for="username">Username</label>
      <input type="text" name="username" id="username" class="form-control" required autofocus placeholder="Masukkan username (NIM/NIDN)">
    </div>
    <div class="form-group mb-3">
      <label class="form-label" for="password">Password</label>
      <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password">
    </div>
    <button type="submit" class="btn btn-login">Masuk ke Sistem</button>
  </form>
  
  <div class="login-footer">
    &copy; <?= date('Y') ?> <?= APP_FULL_NAME ?>
  </div>
</div>
</body>
</html>

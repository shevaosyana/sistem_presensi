<header class="navbar">
  <button class="navbar-toggle" id="sidebarToggle">☰</button>
  <span class="navbar-brand"><?= APP_NAME ?></span>
  <div class="navbar-spacer"></div>
  <div class="navbar-actions">
    <span class="navbar-date" id="navbarDate"></span>
    <div class="navbar-user">
      <span><?= e(SessionHelper::getNama()) ?></span>
      <span style="font-size:.7rem;color:var(--text-muted)"><?= ucfirst(SessionHelper::getRole()) ?></span>
    </div>
  </div>
</header>
<?php
$flash = SessionHelper::getFlash();
if ($flash): ?>
<div style="padding:0 1.5rem .75rem">
  <div class="alert alert-<?= $flash['type']==='error'?'danger':$flash['type'] ?>" data-auto-hide="4000">
    <span class="alert-icon"><?= $flash['type']==='success'?'✅':($flash['type']==='error'?'❌':'ℹ️') ?></span>
    <?= e($flash['message']) ?>
  </div>
</div>
<?php endif; ?>

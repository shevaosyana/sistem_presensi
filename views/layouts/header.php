<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= APP_FULL_NAME ?> — <?= FAKULTAS_NAME ?>">
<title><?= isset($pageTitle) ? e($pageTitle).' — ' : '' ?><?= APP_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= ASSET_URL ?>/css/style.css">
</head>
<body>
<div class="app-wrapper">
<!-- Overlay mobile -->
<div class="overlay" id="sidebarOverlay"></div>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($appName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-light">
<header class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container">
    <a class="navbar-brand fw-semibold" href="/"><?= e($appName) ?></a>
    <nav class="d-flex gap-3"><a class="nav-link text-white" href="/">Home</a><a class="nav-link text-white" href="/contatti">Contatti</a><a class="nav-link text-white" href="/app">Area riservata</a></nav>
 </div></header>
<main class="container py-4" style="max-width: 960px">
    <?php foreach ($flash ?? [] as $f): ?>
        <div class="alert alert-<?= e($f['type']==='ok'?'success':($f['type']==='info'?'info':'danger')) ?>" role="alert"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js" defer></script>
</body>
</html>

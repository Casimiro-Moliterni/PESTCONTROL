<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($appName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<header class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container">
    <a class="brand" href="/"><?= e($appName) ?></a>
    <nav>
        <a href="/">Home</a>
        <a href="/contatti">Contatti</a>
        <a href="/app">Area riservata</a>
    </nav>
 </div></header>
<main class="container py-4" style="max-width: 960px">
    <?php foreach ($flash ?? [] as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

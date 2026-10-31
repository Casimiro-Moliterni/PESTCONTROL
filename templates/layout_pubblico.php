<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($appName) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="/"><?= e($appName) ?></a>
    <nav>
        <a href="/">Home</a>
        <a href="/contatti">Contatti</a>
        <a href="/app">Area riservata</a>
    </nav>
</header>
<main class="container narrow">
    <?php foreach ($flash ?? [] as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
</body>
</html>

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
    <a class="brand" href="/app"><?= e($appName) ?></a>
    <nav>
        <a href="/app">Dashboard</a>
        <?php if ($auth->can('clienti', 'lettura')): ?><a href="/app/clienti">Clienti</a><?php endif; ?>
        <?php if ($auth->can('richieste', 'lettura')): ?><a href="/app/richieste">Richieste</a><?php endif; ?>
        <?php if ($auth->can('interventi', 'lettura')): ?><a href="/app/interventi">Interventi</a><?php endif; ?>
    </nav>
    <form method="post" action="/app/logout" class="logout">
        <?= $csrf->field() ?>
        <span><?= e(($auth->user()['nome'] ?? '') . ' ' . ($auth->user()['cognome'] ?? '')) ?> (<?= e($auth->user()['ruolo'] ?? '') ?>)</span>
        <button type="submit" class="btn btn-small">Esci</button>
    </form>
</header>
<main class="container">
    <?php foreach ($flash ?? [] as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
</body>
</html>

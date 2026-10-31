<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($appName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container-fluid px-lg-4">
    <a class="navbar-brand fw-semibold" href="/app"><?= e($appName) ?></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav"><ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/app">Dashboard</a></li>
        <?php if ($auth->can('clienti', 'lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/clienti">Clienti</a></li><?php endif; ?>
        <?php if ($auth->can('richieste', 'lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/richieste">Richieste</a></li><?php endif; ?>
        <?php foreach (['sopralluoghi'=>'Sopralluoghi','preventivi'=>'Preventivi','interventi'=>'Interventi','calendario'=>'Calendario','utenti'=>'Utenti'] as $mod=>$label): if ($auth->can($mod,'lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/<?= e($mod) ?>"><?= e($label) ?></a></li><?php endif; endforeach; ?>
        <?php if ($auth->can('azienda','lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/azienda">Azienda</a></li><?php endif; ?>
        <?php if ($auth->can('utenti','lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/ruoli">Ruoli</a></li><?php endif; ?>
        <?php if ($auth->can('notifiche','lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/notifiche">Notifiche</a></li><?php endif; ?>
    </ul>
    <form method="post" action="/app/logout" class="d-flex align-items-center gap-3">
        <?= $csrf->field() ?>
        <span><?= e(($auth->user()['nome'] ?? '') . ' ' . ($auth->user()['cognome'] ?? '')) ?> (<?= e($auth->user()['ruolo'] ?? '') ?>)</span>
        <button type="submit" class="btn btn-outline-light btn-sm">Esci</button>
    </form>
    </div></div></nav>
<main class="container-fluid px-lg-4 py-4">
    <?php foreach ($flash ?? [] as $f): ?>
        <div class="alert alert-<?= e($f['type'] === 'ok' ? 'success' : 'danger') ?> alert-dismissible fade show" role="alert"><?= e($f['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container-fluid px-lg-4">
    <a class="navbar-brand fw-semibold" href="/app"><?= e($appName) ?></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Apri il menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav"><ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/app">Riepilogo</a></li>
        <?php if ($auth->can('richieste','lettura') || $auth->can('sopralluoghi','lettura') || $auth->can('preventivi','lettura') || $auth->can('interventi','lettura') || $auth->can('calendario','lettura')): ?><li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Attività</a><ul class="dropdown-menu">
            <?php foreach(['richieste'=>'Richieste','sopralluoghi'=>'Sopralluoghi','preventivi'=>'Preventivi','interventi'=>'Interventi','calendario'=>'Calendario'] as $mod=>$label): if($auth->can($mod,'lettura')): ?><li><a class="dropdown-item" href="/app/<?= e($mod) ?>"><?= e($label) ?></a></li><?php endif; endforeach; ?>
        </ul></li><?php endif; ?>
        <?php if ($auth->can('clienti','lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/clienti">Clienti e sedi</a></li><?php endif; ?>
        <?php if ($auth->can('utenti','lettura') || $auth->can('azienda','lettura')): ?><li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Impostazioni</a><ul class="dropdown-menu">
            <?php if($auth->can('utenti','lettura')): ?><li><a class="dropdown-item" href="/app/utenti">Utenti</a></li><li><a class="dropdown-item" href="/app/ruoli">Ruoli e permessi</a></li><?php endif; ?>
            <?php if($auth->can('azienda','lettura')): ?><li><a class="dropdown-item" href="/app/azienda">Dati aziendali</a></li><?php endif; ?>
        </ul></li><?php endif; ?>
        <?php if ($auth->can('notifiche','lettura')): ?><li class="nav-item"><a class="nav-link" href="/app/notifiche">Notifiche</a></li><?php endif; ?>
    </ul>
    <div class="d-flex align-items-center gap-3 text-white">
        <span class="small d-none d-lg-inline"><?= e(($auth->user()['nome'] ?? '') . ' ' . ($auth->user()['cognome'] ?? '')) ?> · <?= e($auth->user()['ruolo'] ?? '') ?></span>
        <form method="post" action="/app/logout" class="m-0"><?= $csrf->field() ?><button type="submit" class="btn btn-outline-light btn-sm">Esci</button></form>
    </div>
    </div></div></nav>
<main class="container-fluid px-lg-4 py-4">
    <?php foreach ($flash ?? [] as $f): ?>
        <div class="alert alert-<?= e($f['type'] === 'ok' ? 'success' : 'danger') ?> alert-dismissible fade show" role="alert"><?= e($f['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js" defer></script>
</body>
</html>

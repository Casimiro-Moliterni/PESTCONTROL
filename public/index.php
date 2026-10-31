<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

(new App\Core\App(require ROOT . '/config/config.php'))->run();

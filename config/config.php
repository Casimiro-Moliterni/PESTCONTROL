<?php
declare(strict_types=1);

return [
    'db' => [
        'host'    => getenv('DB_HOST') ?: 'db',
        'name'    => getenv('DB_NAME') ?: 'pest_control',
        'user'    => getenv('DB_USER') ?: 'pest',
        'pass'    => getenv('DB_PASS') ?: 'pest',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name'               => 'Pest Control Manager',
        // Azienda a cui vengono assegnate le richieste dal sito pubblico.
        'public_azienda_id'  => (int) (getenv('PUBLIC_AZIENDA_ID') ?: 1),
    ],
    'stati' => require __DIR__ . '/stati.php',
];

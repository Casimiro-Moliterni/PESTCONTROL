<?php
declare(strict_types=1);

use App\Controller\{AuthController, ClienteController, DashboardController, InterventoController,
    PublicController, RichiestaController, SedeController};
use App\Core\Router;

return static function (Router $r): void {
    // --- Area pubblica ---
    $r->get('/', [PublicController::class, 'home'], null);
    $r->get('/contatti', [PublicController::class, 'contactForm'], null);
    $r->post('/contatti', [PublicController::class, 'contactStore'], null);

    // --- Autenticazione ---
    $r->get('/app/login', [AuthController::class, 'loginForm'], null);
    $r->post('/app/login', [AuthController::class, 'login'], null);
    $r->post('/app/logout', [AuthController::class, 'logout'], 'auth');

    // --- Area riservata ---
    $r->get('/app', [DashboardController::class, 'index']);

    // Clienti
    $r->get('/app/clienti', [ClienteController::class, 'index'], ['clienti', 'lettura']);
    $r->get('/app/clienti/nuovo', [ClienteController::class, 'create'], ['clienti', 'creazione']);
    $r->post('/app/clienti', [ClienteController::class, 'store'], ['clienti', 'creazione']);
    $r->get('/app/clienti/{id}', [ClienteController::class, 'show'], ['clienti', 'lettura']);
    $r->get('/app/clienti/{id}/modifica', [ClienteController::class, 'edit'], ['clienti', 'modifica']);
    $r->post('/app/clienti/{id}', [ClienteController::class, 'update'], ['clienti', 'modifica']);
    $r->post('/app/clienti/{id}/archivia', [ClienteController::class, 'archive'], ['clienti', 'eliminazione']);

    // Sedi
    $r->get('/app/clienti/{id}/sedi/nuova', [SedeController::class, 'create'], ['clienti', 'modifica']);
    $r->post('/app/clienti/{id}/sedi', [SedeController::class, 'store'], ['clienti', 'modifica']);
    $r->get('/app/sedi/{id}/modifica', [SedeController::class, 'edit'], ['clienti', 'modifica']);
    $r->post('/app/sedi/{id}', [SedeController::class, 'update'], ['clienti', 'modifica']);
    $r->post('/app/sedi/{id}/archivia', [SedeController::class, 'archive'], ['clienti', 'eliminazione']);

    // Richieste
    $r->get('/app/richieste', [RichiestaController::class, 'index'], ['richieste', 'lettura']);
    $r->get('/app/richieste/{id}', [RichiestaController::class, 'show'], ['richieste', 'lettura']);
    $r->post('/app/richieste/{id}', [RichiestaController::class, 'update'], ['richieste', 'modifica']);
    $r->post('/app/richieste/{id}/converti', [RichiestaController::class, 'convert'], ['richieste', 'modifica']);

    // Interventi
    $r->get('/app/interventi', [InterventoController::class, 'index'], ['interventi', 'lettura']);
    $r->get('/app/interventi/nuovo', [InterventoController::class, 'create'], ['interventi', 'creazione']);
    $r->post('/app/interventi', [InterventoController::class, 'store'], ['interventi', 'creazione']);
    $r->get('/app/interventi/{id}', [InterventoController::class, 'show'], ['interventi', 'lettura']);
    $r->get('/app/interventi/{id}/modifica', [InterventoController::class, 'edit'], ['interventi', 'modifica']);
    $r->post('/app/interventi/{id}', [InterventoController::class, 'update'], ['interventi', 'modifica']);
    $r->post('/app/interventi/{id}/esecuzione', [InterventoController::class, 'execute'], ['interventi', 'esecuzione']);
    $r->post('/app/interventi/{id}/archivia', [InterventoController::class, 'archive'], ['interventi', 'eliminazione']);
};

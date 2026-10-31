<?php
declare(strict_types=1);

use App\Controller\{AuthController, ClienteController, DashboardController, InterventoController, UtentiController, AziendaController,
    PublicController, RichiestaController, SedeController, SopralluogoController, PreventivoController, CalendarioController, AllegatoController, RuoloController, NotificaController, ContrattoController, DispositivoController, MagazzinoController, MappaController, CampoController, RapportoController};
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
    $r->post('/app/interventi/{id}/prodotti', [MagazzinoController::class, 'consume'], ['interventi','esecuzione']);
    $r->get('/app/interventi/{id}/rapporto', [RapportoController::class, 'print'], ['interventi','lettura']);
    $r->get('/app/campo', [CampoController::class, 'index'], ['interventi','lettura']);

    // Contratti e visite ricorrenti
    $r->get('/app/contratti', [ContrattoController::class,'index'], ['contratti','lettura']);
    $r->get('/app/contratti/nuovo', [ContrattoController::class,'create'], ['contratti','creazione']);
    $r->post('/app/contratti', [ContrattoController::class,'store'], ['contratti','creazione']);
    $r->get('/app/contratti/{id}', [ContrattoController::class,'show'], ['contratti','lettura']);
    $r->post('/app/contratti/{id}/visite/{visitId}/ripianifica', [ContrattoController::class,'reschedule'], ['contratti','modifica']);
    $r->post('/app/contratti/{id}/visite/{visitId}/intervento', [ContrattoController::class,'createIntervention'], ['contratti','modifica']);

    // Dispositivi, punti di monitoraggio e scansione QR autenticata
    $r->get('/app/dispositivi', [DispositivoController::class,'index'], ['dispositivi','lettura']);
    $r->get('/app/dispositivi/nuovo', [DispositivoController::class,'create'], ['dispositivi','creazione']);
    $r->post('/app/dispositivi', [DispositivoController::class,'store'], ['dispositivi','creazione']);
    $r->get('/app/dispositivi/scansione', [DispositivoController::class,'scan'], ['dispositivi','lettura']);
    $r->get('/app/dispositivi/codice', [DispositivoController::class,'byCode'], ['dispositivi','lettura']);
    $r->get('/app/dispositivi/qr/{token}', [DispositivoController::class,'fromQr'], ['dispositivi','lettura']);
    $r->get('/app/dispositivi/{id}', [DispositivoController::class,'show'], ['dispositivi','lettura']);
    $r->post('/app/dispositivi/{id}/controlli', [DispositivoController::class,'check'], ['dispositivi','esecuzione']);

    // Magazzino, lotti e movimenti
    $r->get('/app/magazzino', [MagazzinoController::class,'index'], ['magazzino','lettura']);
    $r->get('/app/magazzino/nuovo', [MagazzinoController::class,'create'], ['magazzino','creazione']);
    $r->post('/app/magazzino', [MagazzinoController::class,'store'], ['magazzino','creazione']);
    $r->get('/app/magazzino/{id}', [MagazzinoController::class,'show'], ['magazzino','lettura']);
    $r->get('/app/magazzino/{id}/modifica', [MagazzinoController::class,'edit'], ['magazzino','modifica']);
    $r->post('/app/magazzino/{id}', [MagazzinoController::class,'update'], ['magazzino','modifica']);
    $r->post('/app/magazzino/{id}/movimenti', [MagazzinoController::class,'movement'], ['magazzino','modifica']);
    $r->get('/app/mappa', [MappaController::class,'index'], ['mappa','lettura']);

    // Sopralluoghi
    $r->get('/app/sopralluoghi', [SopralluogoController::class, 'index'], ['sopralluoghi','lettura']);
    $r->get('/app/sopralluoghi/nuovo', [SopralluogoController::class, 'create'], ['sopralluoghi','creazione']);
    $r->post('/app/sopralluoghi', [SopralluogoController::class, 'store'], ['sopralluoghi','creazione']);
    $r->get('/app/sopralluoghi/{id}', [SopralluogoController::class, 'show'], ['sopralluoghi','lettura']);
    $r->get('/app/sopralluoghi/{id}/modifica', [SopralluogoController::class, 'edit'], ['sopralluoghi','modifica']);
    $r->post('/app/sopralluoghi/{id}', [SopralluogoController::class, 'update'], ['sopralluoghi','modifica']);
    $r->post('/app/sopralluoghi/{id}/archivia', [SopralluogoController::class, 'archive'], ['sopralluoghi','eliminazione']);

    // Preventivi
    $r->get('/app/preventivi', [PreventivoController::class, 'index'], ['preventivi','lettura']);
    $r->get('/app/preventivi/nuovo', [PreventivoController::class, 'create'], ['preventivi','creazione']);
    $r->post('/app/preventivi', [PreventivoController::class, 'store'], ['preventivi','creazione']);
    $r->get('/app/preventivi/{id}', [PreventivoController::class, 'show'], ['preventivi','lettura']);
    $r->get('/app/preventivi/{id}/modifica', [PreventivoController::class, 'edit'], ['preventivi','modifica']);
    $r->post('/app/preventivi/{id}', [PreventivoController::class, 'update'], ['preventivi','modifica']);
    $r->post('/app/preventivi/{id}/archivia', [PreventivoController::class, 'archive'], ['preventivi','eliminazione']);

    // Calendario: vista elenco cronologica (giorno/settimana/mese tramite filtro data).
    $r->get('/app/calendario', [CalendarioController::class, 'index'], ['calendario','lettura']);
    $r->get('/app/calendario/nuovo', [CalendarioController::class, 'create'], ['calendario','creazione']);
    $r->post('/app/calendario', [CalendarioController::class, 'store'], ['calendario','creazione']);
    $r->get('/app/calendario/{id}', [CalendarioController::class, 'show'], ['calendario','lettura']);
    $r->get('/app/calendario/{id}/modifica', [CalendarioController::class, 'edit'], ['calendario','modifica']);
    $r->post('/app/calendario/{id}', [CalendarioController::class, 'update'], ['calendario','modifica']);
    $r->post('/app/calendario/{id}/archivia', [CalendarioController::class, 'archive'], ['calendario','eliminazione']);

    // Azienda e account (permessi dedicati)
    $r->get('/app/utenti', [UtentiController::class, 'index'], ['utenti','lettura']);
    $r->get('/app/utenti/nuovo', [UtentiController::class, 'create'], ['utenti','creazione']);
    $r->post('/app/utenti', [UtentiController::class, 'store'], ['utenti','creazione']);
    $r->get('/app/utenti/{id}/modifica', [UtentiController::class, 'edit'], ['utenti','modifica']);
    $r->post('/app/utenti/{id}', [UtentiController::class, 'update'], ['utenti','modifica']);
    $r->get('/app/azienda', [AziendaController::class, 'index'], ['azienda','lettura']);
    $r->post('/app/azienda', [AziendaController::class, 'update'], ['azienda','modifica']);
    $r->get('/app/ruoli', [RuoloController::class, 'index'], ['utenti','lettura']);
    $r->get('/app/ruoli/{id}/modifica', [RuoloController::class, 'edit'], ['utenti','modifica']);
    $r->post('/app/ruoli/{id}', [RuoloController::class, 'update'], ['utenti','modifica']);
    $r->get('/app/notifiche', [NotificaController::class, 'index'], ['notifiche','lettura']);
    $r->post('/app/notifiche/{id}/letta', [NotificaController::class, 'read'], ['notifiche','modifica']);
    $r->post('/app/interventi/{id}/allegati', [AllegatoController::class, 'upload'], ['interventi','esecuzione']);
    $r->get('/app/allegati/{id}', [AllegatoController::class, 'download'], ['interventi','lettura']);
};

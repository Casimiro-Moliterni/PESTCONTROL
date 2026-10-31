<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Repository\InterventoRepository;
use App\Repository\RichiestaRepository;

final class DashboardController extends Controller
{
    public function index(): Response
    {
        $auth = $this->ctx->auth;
        $interventi = new InterventoRepository($this->ctx->db, $this->aziendaId());
        $operatoreId = $auth->soloAssegnati() ? $auth->id() : null;

        $prossimi = $auth->can('interventi', 'lettura')
            ? $interventi->search(['da_oggi' => true, 'operatore_id' => $operatoreId, 'limit' => 8])
            : [];
        $conteggi = $auth->can('interventi', 'lettura') ? $interventi->countByStato($operatoreId) : [];
        $nuoveRichieste = $auth->can('richieste', 'lettura')
            ? (new RichiestaRepository($this->ctx->db, $this->aziendaId()))->countByStato('nuova')
            : null;

        return $this->render('dashboard', compact('prossimi', 'conteggi', 'nuoveRichieste'));
    }
}

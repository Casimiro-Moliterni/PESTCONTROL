<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Validator;
use App\Repository\ClienteRepository;
use App\Repository\RichiestaRepository;
use App\Repository\SedeRepository;
use App\Repository\UtenteRepository;

final class RichiestaController extends Controller
{
    public function index(): Response
    {
        $stato = $this->ctx->request->query('stato');
        $richieste = $this->repo()->search($stato);
        return $this->render('richieste/index', compact('richieste', 'stato'));
    }

    public function show(int $id): Response
    {
        $richiesta = $this->repo()->find($id) ?? $this->notFound();
        $utenti = (new UtenteRepository($this->ctx->db, $this->aziendaId()))->assegnabili();
        return $this->render('richieste/show', compact('richiesta', 'utenti'));
    }

    public function update(int $id): Response
    {
        $this->repo()->find($id) ?? $this->notFound();
        $data = $this->ctx->request->only(['stato', 'assegnato_a']);
        $errors = Validator::validate($data, ['stato' => 'required|' . $this->inRule('richiesta_stato'), 'assegnato_a' => 'int']);

        $utentiValidi = array_column((new UtenteRepository($this->ctx->db, $this->aziendaId()))->assegnabili(), 'id');
        if ($data['assegnato_a'] !== '' && !in_array((int) $data['assegnato_a'], array_map('intval', $utentiValidi), true)) {
            $errors['assegnato_a'] = 'Utente non valido.';
        }
        if ($errors) {
            return $this->redirect("/app/richieste/$id", 'error', 'Dati non validi: ' . implode(' ', $errors));
        }
        $this->repo()->update($id, $data);
        $this->ctx->audit->record('modifica', 'richiesta', $id, $data);
        return $this->redirect("/app/richieste/$id", 'ok', 'Richiesta aggiornata.');
    }

    /** Converte la richiesta in cliente (+ sede se è indicata una località), senza reinserire i dati. */
    public function convert(int $id): Response
    {
        $this->requirePermission('clienti', 'creazione');
        $richiesta = $this->repo()->find($id) ?? $this->notFound();
        if ($richiesta['cliente_id']) {
            return $this->redirect('/app/clienti/' . $richiesta['cliente_id'], 'info', 'Richiesta già convertita.');
        }

        $clienteId = $this->ctx->db->transaction(function () use ($richiesta, $id): int {
            $clienti = new ClienteRepository($this->ctx->db, $this->aziendaId());
            $clienteId = $clienti->create([
                'tipo'          => $richiesta['azienda_richiedente'] ? 'azienda' : 'privato',
                'denominazione' => $richiesta['azienda_richiedente'] ?: $richiesta['nome'],
                'email'         => $richiesta['email'],
                'telefono'      => $richiesta['telefono'],
                'stato'         => 'prospect',
                'note'          => 'Creato dalla richiesta #' . $id,
            ]);
            if ($richiesta['localita']) {
                (new SedeRepository($this->ctx->db, $this->aziendaId()))->create([
                    'cliente_id' => $clienteId,
                    'nome'       => 'Sede principale',
                    'indirizzo'  => $richiesta['localita'],
                ]);
            }
            $this->repo()->update($id, ['stato' => 'convertita', 'cliente_id' => $clienteId]);
            return $clienteId;
        });

        $this->ctx->audit->record('conversione', 'richiesta', $id, ['cliente_id' => $clienteId]);
        return $this->redirect("/app/clienti/$clienteId", 'ok', 'Richiesta convertita in cliente.');
    }

    private function repo(): RichiestaRepository
    {
        return new RichiestaRepository($this->ctx->db, $this->aziendaId());
    }
}

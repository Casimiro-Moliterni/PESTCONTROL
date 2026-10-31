<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Validator;
use App\Repository\ClienteRepository;
use App\Repository\InterventoRepository;
use App\Repository\SedeRepository;

final class ClienteController extends Controller
{
    private const FIELDS = [
        'tipo', 'denominazione', 'codice_fiscale', 'partita_iva', 'email', 'telefono', 'pec',
        'indirizzo', 'citta', 'cap', 'provincia', 'stato', 'etichetta', 'note',
    ];

    public function index(): Response
    {
        $q = $this->ctx->request->query('q');
        $stato = $this->ctx->request->query('stato');
        $clienti = $this->repo()->search($q, $stato);
        return $this->render('clienti/index', compact('clienti', 'q', 'stato'));
    }

    public function create(): Response
    {
        return $this->form(['tipo' => 'privato', 'stato' => 'prospect'], null);
    }

    public function store(): Response
    {
        $data = $this->ctx->request->only(self::FIELDS);
        $errors = Validator::validate($data, $this->rules());
        if ($errors) {
            return $this->form($data, null, $errors, 422);
        }
        $id = $this->repo()->create($data);
        $this->ctx->audit->record('creazione', 'cliente', $id);
        return $this->redirect("/app/clienti/$id", 'ok', 'Cliente creato.');
    }

    public function show(int $id): Response
    {
        $cliente = $this->repo()->find($id) ?? $this->notFound();
        $sedi = (new SedeRepository($this->ctx->db, $this->aziendaId()))->forCliente($id);
        $interventi = $this->ctx->auth->can('interventi', 'lettura')
            ? (new InterventoRepository($this->ctx->db, $this->aziendaId()))->search(['cliente_id' => $id, 'limit' => 10])
            : [];
        return $this->render('clienti/show', compact('cliente', 'sedi', 'interventi'));
    }

    public function edit(int $id): Response
    {
        $cliente = $this->repo()->find($id) ?? $this->notFound();
        return $this->form($cliente, $id);
    }

    public function update(int $id): Response
    {
        $this->repo()->find($id) ?? $this->notFound();
        $data = $this->ctx->request->only(self::FIELDS);
        $errors = Validator::validate($data, $this->rules());
        if ($errors) {
            return $this->form($data, $id, $errors, 422);
        }
        $this->repo()->update($id, $data);
        $this->ctx->audit->record('modifica', 'cliente', $id);
        return $this->redirect("/app/clienti/$id", 'ok', 'Cliente aggiornato.');
    }

    public function archive(int $id): Response
    {
        $this->repo()->find($id) ?? $this->notFound();
        $this->repo()->archive($id);
        $this->ctx->audit->record('archiviazione', 'cliente', $id);
        return $this->redirect('/app/clienti', 'ok', 'Cliente archiviato.');
    }

    private function form(array $cliente, ?int $id, array $errors = [], int $status = 200): Response
    {
        return $this->render('clienti/form', compact('cliente', 'id', 'errors'), 'layout', $status);
    }

    private function rules(): array
    {
        return [
            'denominazione'  => 'required|max:200',
            'tipo'           => 'required|' . $this->inRule('cliente_tipo'),
            'stato'          => 'required|' . $this->inRule('cliente_stato'),
            'codice_fiscale' => 'max:20',
            'partita_iva'    => 'max:20',
            'email'          => 'email|max:190',
            'pec'            => 'email|max:190',
            'telefono'       => 'max:40',
            'indirizzo'      => 'max:255',
            'citta'          => 'max:100',
            'cap'            => 'max:10',
            'provincia'      => 'max:5',
            'etichetta'      => 'max:60',
        ];
    }

    private function repo(): ClienteRepository
    {
        return new ClienteRepository($this->ctx->db, $this->aziendaId());
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Validator;
use App\Repository\ClienteRepository;
use App\Repository\SedeRepository;

final class SedeController extends Controller
{
    private const FIELDS = [
        'nome', 'indirizzo', 'citta', 'cap', 'provincia', 'latitudine', 'longitudine',
        'referente_nome', 'referente_telefono', 'istruzioni_accesso', 'orari_consentiti', 'note',
    ];

    public function create(int $clienteId): Response
    {
        $cliente = $this->cliente($clienteId);
        return $this->form($cliente, ['nome' => 'Sede principale'], null);
    }

    public function store(int $clienteId): Response
    {
        $cliente = $this->cliente($clienteId);
        $data = $this->ctx->request->only(self::FIELDS);
        $errors = Validator::validate($data, $this->rules());
        if ($errors) {
            return $this->form($cliente, $data, null, $errors, 422);
        }
        $id = $this->repo()->create($data + ['cliente_id' => $clienteId]);
        $this->ctx->audit->record('creazione', 'sede', $id);
        return $this->redirect("/app/clienti/$clienteId", 'ok', 'Sede creata.');
    }

    public function edit(int $id): Response
    {
        $sede = $this->repo()->find($id) ?? $this->notFound();
        return $this->form($this->cliente((int) $sede['cliente_id']), $sede, $id);
    }

    public function update(int $id): Response
    {
        $sede = $this->repo()->find($id) ?? $this->notFound();
        $cliente = $this->cliente((int) $sede['cliente_id']);
        $data = $this->ctx->request->only(self::FIELDS);
        $errors = Validator::validate($data, $this->rules());
        if ($errors) {
            return $this->form($cliente, $data, $id, $errors, 422);
        }
        $this->repo()->update($id, $data);
        $this->ctx->audit->record('modifica', 'sede', $id);
        return $this->redirect('/app/clienti/' . $cliente['id'], 'ok', 'Sede aggiornata.');
    }

    public function archive(int $id): Response
    {
        $sede = $this->repo()->find($id) ?? $this->notFound();
        $this->repo()->archive($id);
        $this->ctx->audit->record('archiviazione', 'sede', $id);
        return $this->redirect('/app/clienti/' . $sede['cliente_id'], 'ok', 'Sede archiviata.');
    }

    private function form(array $cliente, array $sede, ?int $id, array $errors = [], int $status = 200): Response
    {
        return $this->render('sedi/form', compact('cliente', 'sede', 'id', 'errors'), 'layout', $status);
    }

    private function rules(): array
    {
        return [
            'nome'               => 'required|max:150',
            'indirizzo'          => 'max:255',
            'citta'              => 'max:100',
            'cap'                => 'max:10',
            'provincia'          => 'max:5',
            'latitudine'         => 'decimal',
            'longitudine'        => 'decimal',
            'referente_nome'     => 'max:150',
            'referente_telefono' => 'max:40',
            'orari_consentiti'   => 'max:255',
        ];
    }

    private function cliente(int $id): array
    {
        return (new ClienteRepository($this->ctx->db, $this->aziendaId()))->find($id) ?? $this->notFound();
    }

    private function repo(): SedeRepository
    {
        return new SedeRepository($this->ctx->db, $this->aziendaId());
    }
}

<?php
declare(strict_types=1);

namespace App\Repository;

final class SedeRepository extends Repository
{
    protected string $table = 'sedi';
    protected array $fillable = [
        'cliente_id', 'nome', 'indirizzo', 'citta', 'cap', 'provincia', 'latitudine', 'longitudine',
        'referente_nome', 'referente_telefono', 'istruzioni_accesso', 'orari_consentiti', 'note',
    ];

    public function forCliente(int $clienteId): array
    {
        return $this->all('cliente_id = :c', ['c' => $clienteId], 'nome ASC');
    }

    /** Sedi con nome cliente, per i menu di selezione (senza JS: una lista unica raggruppata per cliente). */
    public function withCliente(): array
    {
        return $this->db->fetchAll(
            'SELECT s.id, s.nome, c.denominazione AS cliente
             FROM sedi s JOIN clienti c ON c.id = s.cliente_id
             WHERE s.azienda_id = :az AND s.archiviato_at IS NULL AND c.archiviato_at IS NULL
             ORDER BY c.denominazione, s.nome',
            ['az' => $this->aziendaId]
        );
    }
}

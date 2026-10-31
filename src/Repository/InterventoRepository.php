<?php
declare(strict_types=1);

namespace App\Repository;

final class InterventoRepository extends Repository
{
    protected string $table = 'interventi';
    protected array $fillable = [
        'cliente_id', 'sede_id', 'tipo_servizio', 'infestante', 'descrizione', 'istruzioni',
        'pianificato_il', 'durata_stimata_min', 'inizio_effettivo', 'fine_effettiva',
        'stato', 'esito', 'note_operatore',
    ];

    private const SELECT = 'SELECT i.*, c.denominazione AS cliente, s.nome AS sede_nome,
            s.indirizzo AS sede_indirizzo, s.citta AS sede_citta,
            s.referente_nome, s.referente_telefono, s.istruzioni_accesso, s.orari_consentiti
        FROM interventi i
        JOIN clienti c ON c.id = i.cliente_id
        JOIN sedi s ON s.id = i.sede_id
        WHERE i.azienda_id = :az AND i.archiviato_at IS NULL';

    /**
     * Filtri ammessi: stato, operatore_id, cliente_id, da_oggi (bool), limit (int).
     */
    public function search(array $filters = []): array
    {
        $sql = self::SELECT;
        $params = ['az' => $this->aziendaId];
        if (!empty($filters['stato'])) {
            $sql .= ' AND i.stato = :stato';
            $params['stato'] = $filters['stato'];
        }
        if (!empty($filters['operatore_id'])) {
            $sql .= ' AND EXISTS (SELECT 1 FROM intervento_operatori io WHERE io.intervento_id = i.id AND io.utente_id = :op)';
            $params['op'] = $filters['operatore_id'];
        }
        if (!empty($filters['cliente_id'])) {
            $sql .= ' AND i.cliente_id = :cl';
            $params['cl'] = $filters['cliente_id'];
        }
        if (!empty($filters['da_oggi'])) {
            $sql .= " AND i.pianificato_il >= CURDATE() AND i.stato NOT IN ('completato','annullato')";
        }
        $sql .= ' ORDER BY i.pianificato_il IS NULL, i.pianificato_il ASC';
        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int) $filters['limit'];
        }
        return $this->db->fetchAll($sql, $params);
    }

    public function detail(int $id): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' AND i.id = :id', ['az' => $this->aziendaId, 'id' => $id]);
    }

    public function operatori(int $id): array
    {
        return $this->db->fetchAll(
            'SELECT u.id, u.nome, u.cognome FROM intervento_operatori io
             JOIN utenti u ON u.id = io.utente_id WHERE io.intervento_id = :i ORDER BY u.cognome',
            ['i' => $id]
        );
    }

    public function isAssigned(int $id, int $utenteId): bool
    {
        return (bool) $this->db->fetchValue(
            'SELECT 1 FROM intervento_operatori WHERE intervento_id = :i AND utente_id = :u',
            ['i' => $id, 'u' => $utenteId]
        );
    }

    /** Sostituisce gli operatori assegnati (gli id vanno già validati dal chiamante). */
    public function setOperatori(int $id, array $utenteIds): void
    {
        $this->db->transaction(function () use ($id, $utenteIds) {
            $this->db->execute('DELETE FROM intervento_operatori WHERE intervento_id = :i', ['i' => $id]);
            foreach (array_unique($utenteIds) as $uid) {
                $this->db->insert('intervento_operatori', ['intervento_id' => $id, 'utente_id' => $uid]);
            }
        });
    }

    public function countByStato(?int $operatoreId = null): array
    {
        $sql = 'SELECT i.stato, COUNT(*) AS n FROM interventi i WHERE i.azienda_id = :az AND i.archiviato_at IS NULL';
        $params = ['az' => $this->aziendaId];
        if ($operatoreId) {
            $sql .= ' AND EXISTS (SELECT 1 FROM intervento_operatori io WHERE io.intervento_id = i.id AND io.utente_id = :op)';
            $params['op'] = $operatoreId;
        }
        $rows = $this->db->fetchAll($sql . ' GROUP BY i.stato', $params);
        return array_column($rows, 'n', 'stato');
    }
}

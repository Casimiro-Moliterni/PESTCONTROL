<?php
declare(strict_types=1);

namespace App\Repository;

use App\Core\Database;

/**
 * Base dei repository. Ogni query è vincolata a azienda_id (multi-tenant)
 * e ignora i record archiviati (niente cancellazioni definitive).
 */
abstract class Repository
{
    protected string $table;
    /** Colonne scrivibili: tutto il resto viene scartato. */
    protected array $fillable = [];

    public function __construct(protected Database $db, protected int $aziendaId)
    {
    }

    /** $where e $order sono frammenti SQL scritti nel codice; i valori passano da $params. */
    public function all(string $where = '1=1', array $params = [], string $order = 'id DESC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE azienda_id = :_az AND archiviato_at IS NULL AND ($where) ORDER BY $order",
            $params + ['_az' => $this->aziendaId]
        );
    }

    public function find(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = :_id AND azienda_id = :_az AND archiviato_at IS NULL",
            ['_id' => $id, '_az' => $this->aziendaId]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert($this->table, $this->only($data) + ['azienda_id' => $this->aziendaId]);
    }

    public function update(int $id, array $data): void
    {
        $this->db->update($this->table, $this->only($data), ['id' => $id, 'azienda_id' => $this->aziendaId]);
    }

    public function archive(int $id): void
    {
        $this->db->execute(
            "UPDATE {$this->table} SET archiviato_at = NOW() WHERE id = :id AND azienda_id = :az",
            ['id' => $id, 'az' => $this->aziendaId]
        );
    }

    /** Filtra sulle colonne ammesse e trasforma le stringhe vuote in NULL. */
    protected function only(array $data): array
    {
        $row = array_intersect_key($data, array_flip($this->fillable));
        return array_map(static fn($v) => $v === '' ? null : $v, $row);
    }
}

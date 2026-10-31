<?php
declare(strict_types=1);

namespace App\Repository;

final class RichiestaRepository extends Repository
{
    protected string $table = 'richieste';
    protected array $fillable = [
        'tipo', 'nome', 'azienda_richiedente', 'email', 'telefono', 'localita', 'descrizione',
        'preferenza_contatto', 'origine', 'stato', 'assegnato_a', 'cliente_id',
    ];

    public function search(?string $stato): array
    {
        $sql = 'SELECT r.*, CONCAT(u.nome, " ", u.cognome) AS assegnato
                FROM richieste r LEFT JOIN utenti u ON u.id = r.assegnato_a
                WHERE r.azienda_id = :az AND r.archiviato_at IS NULL';
        $params = ['az' => $this->aziendaId];
        if ($stato) {
            $sql .= ' AND r.stato = :stato';
            $params['stato'] = $stato;
        }
        return $this->db->fetchAll($sql . ' ORDER BY r.ricevuta_il DESC', $params);
    }

    public function countByStato(string $stato): int
    {
        return (int) $this->db->fetchValue(
            'SELECT COUNT(*) FROM richieste WHERE azienda_id = :az AND archiviato_at IS NULL AND stato = :s',
            ['az' => $this->aziendaId, 's' => $stato]
        );
    }
}

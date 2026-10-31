<?php
declare(strict_types=1);

namespace App\Repository;

final class UtenteRepository extends Repository
{
    protected string $table = 'utenti';

    /** Utenti attivi a cui si possono assegnare richieste e interventi. */
    public function assegnabili(): array
    {
        return $this->db->fetchAll(
            'SELECT u.id, u.nome, u.cognome, r.nome AS ruolo
             FROM utenti u JOIN ruoli r ON r.id = u.ruolo_id
             WHERE u.azienda_id = :az AND u.attivo = 1
             ORDER BY u.cognome, u.nome',
            ['az' => $this->aziendaId]
        );
    }
}

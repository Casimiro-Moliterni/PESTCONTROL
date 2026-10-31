<?php
declare(strict_types=1);

namespace App\Core;

/** Registro delle operazioni rilevanti (chi, cosa, quando). */
final class AuditLog
{
    public function __construct(private Database $db, private Auth $auth, private Request $request)
    {
    }

    public function record(string $azione, string $entita, ?int $entitaId = null, array $dettagli = []): void
    {
        $this->db->insert('audit_log', [
            'azienda_id' => $this->auth->aziendaId() ?: null,
            'utente_id'  => $this->auth->id() ?: null,
            'azione'     => $azione,
            'entita'     => $entita,
            'entita_id'  => $entitaId,
            'dettagli'   => $dettagli ? json_encode($dettagli, JSON_UNESCAPED_UNICODE) : null,
            'ip'         => $this->request->ip(),
        ]);
    }
}

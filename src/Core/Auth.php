<?php
declare(strict_types=1);

namespace App\Core;

/** Autenticazione e autorizzazione (sempre verificate lato server). */
final class Auth
{
    private const SESSION_KEY = 'utente_id';
    private const DUMMY_HASH = '$2y$10$dC7Y/NiB.dv6wFpoiLC9U.Jd.kvu7u8WEw.ciHjYmZPGbi9.ge6qu';

    private ?array $user = null;
    private bool $userLoaded = false;
    private ?array $permissions = null;

    public function __construct(private Database $db, private Session $session)
    {
    }

    public function attempt(string $email, string $password): bool
    {
        $user = $this->db->fetchOne('SELECT id, password_hash FROM utenti WHERE email = :e AND attivo = 1', ['e' => $email]);
        // Verifica sempre un hash, per non rivelare via timing se l'email esiste.
        $hash = $user['password_hash'] ?? self::DUMMY_HASH;
        if (!password_verify($password, $hash) || $user === null) {
            return false;
        }
        $this->session->regenerate();
        $this->session->set(self::SESSION_KEY, (int) $user['id']);
        $this->userLoaded = false;
        return true;
    }

    public function logout(): void
    {
        $this->session->destroy();
        $this->user = null;
        $this->permissions = null;
    }

    /** Utente corrente riletto dal DB: un account disattivato perde subito l'accesso. */
    public function user(): ?array
    {
        if (!$this->userLoaded) {
            $this->userLoaded = true;
            $id = $this->session->get(self::SESSION_KEY);
            $this->user = $id === null ? null : $this->db->fetchOne(
                'SELECT u.id, u.azienda_id, u.ruolo_id, u.nome, u.cognome, u.email, r.nome AS ruolo, r.solo_assegnati
                 FROM utenti u JOIN ruoli r ON r.id = u.ruolo_id
                 WHERE u.id = :id AND u.attivo = 1',
                ['id' => (int) $id]
            );
        }
        return $this->user;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function id(): int
    {
        return (int) ($this->user()['id'] ?? 0);
    }

    public function aziendaId(): int
    {
        return (int) ($this->user()['azienda_id'] ?? 0);
    }

    public function soloAssegnati(): bool
    {
        return (bool) ($this->user()['solo_assegnati'] ?? false);
    }

    public function can(string $modulo, string $azione): bool
    {
        $user = $this->user();
        if ($user === null) {
            return false;
        }
        if ($this->permissions === null) {
            $rows = $this->db->fetchAll(
                'SELECT modulo, azione FROM ruolo_permessi WHERE ruolo_id = :r',
                ['r' => $user['ruolo_id']]
            );
            $this->permissions = [];
            foreach ($rows as $row) {
                $this->permissions[$row['modulo'] . '.' . $row['azione']] = true;
            }
        }
        return isset($this->permissions["$modulo.$azione"]);
    }
}

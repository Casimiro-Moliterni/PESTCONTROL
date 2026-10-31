<?php
declare(strict_types=1);
namespace App\Repository;
final class NotificaRepository extends WorkflowRepository
{
 protected string $table='notifiche';
 protected array $fillable=['utente_id','titolo','messaggio','link','letta_il'];
 public function records(): array { return $this->db->fetchAll('SELECT * FROM notifiche WHERE azienda_id=:az AND (utente_id IS NULL OR utente_id=:u) ORDER BY creato_il DESC LIMIT 100',['az'=>$this->aziendaId,'u'=>$this->dbUserId]); }
 private int $dbUserId=0;
 public function forUser(int $id): array {$this->dbUserId=$id; return $this->records();}
}

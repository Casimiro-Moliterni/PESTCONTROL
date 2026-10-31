<?php
declare(strict_types=1);
namespace App\Repository;
final class UtentiAdminRepository extends Repository
{
 protected string $table='utenti';
 protected array $fillable=['ruolo_id','nome','cognome','email','telefono','password_hash','attivo'];
 public function find(int $id): ?array {return $this->db->fetchOne('SELECT * FROM utenti WHERE id=:id AND azienda_id=:az',['id'=>$id,'az'=>$this->aziendaId]);}
 public function elenco(): array {return $this->db->fetchAll('SELECT u.id,u.nome,u.cognome,u.email,u.telefono,u.attivo,u.ruolo_id,r.nome ruolo FROM utenti u JOIN ruoli r ON r.id=u.ruolo_id WHERE u.azienda_id=:az ORDER BY u.cognome,u.nome',['az'=>$this->aziendaId]);}
 public function ruoli(): array {return $this->db->fetchAll('SELECT id,nome FROM ruoli WHERE azienda_id=:az ORDER BY nome',['az'=>$this->aziendaId]);}
}

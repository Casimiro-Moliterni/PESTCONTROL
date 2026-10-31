<?php
declare(strict_types=1);
namespace App\Repository;
final class DispositivoRepository extends Repository
{
 protected string $table='dispositivi';
 protected array $fillable=['cliente_id','sede_id','prodotto_id','codice','qr_token','tipo','modello','posizione','descrizione','latitudine','longitudine','installato_il','stato','esca'];
 public function records(): array {return $this->db->fetchAll('SELECT d.*,c.denominazione cliente,s.nome sede_nome FROM dispositivi d JOIN clienti c ON c.id=d.cliente_id JOIN sedi s ON s.id=d.sede_id WHERE d.azienda_id=:az AND d.archiviato_at IS NULL ORDER BY c.denominazione,s.nome,d.codice',['az'=>$this->aziendaId]);}
 public function detail(int $id): ?array {return $this->db->fetchOne('SELECT d.*,c.denominazione cliente,s.nome sede_nome,s.latitudine sede_lat,s.longitudine sede_lng FROM dispositivi d JOIN clienti c ON c.id=d.cliente_id JOIN sedi s ON s.id=d.sede_id WHERE d.id=:id AND d.azienda_id=:az AND d.archiviato_at IS NULL',['id'=>$id,'az'=>$this->aziendaId]);}
 public function byToken(string $token): ?array {return $this->db->fetchOne('SELECT id FROM dispositivi WHERE qr_token=:t AND azienda_id=:az AND archiviato_at IS NULL',['t'=>$token,'az'=>$this->aziendaId]);}
 public function checks(int $id): array {return $this->db->fetchAll('SELECT x.*,CONCAT(u.nome," ",u.cognome) operatore FROM controlli_dispositivi x JOIN utenti u ON u.id=x.operatore_id WHERE x.azienda_id=:az AND x.dispositivo_id=:id ORDER BY x.controllato_il DESC',['az'=>$this->aziendaId,'id'=>$id]);}
}

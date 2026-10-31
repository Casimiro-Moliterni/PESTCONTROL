<?php
declare(strict_types=1);
namespace App\Repository;
final class EventoRepository extends WorkflowRepository
{
 protected string $table='eventi_calendario';
 protected array $fillable=['tipo','titolo','descrizione','inizio','fine','intervento_id','sopralluogo_id','assegnato_a','creato_da'];
 public function records(?string $from=null,?string $to=null): array { $sql='SELECT e.*,CONCAT(u.nome," ",u.cognome) operatore FROM eventi_calendario e LEFT JOIN utenti u ON u.id=e.assegnato_a WHERE e.azienda_id=:az AND e.archiviato_at IS NULL';$p=['az'=>$this->aziendaId];if($from!==null&&$to!==null){$sql.=' AND e.inizio>=:dal AND e.inizio<:al';$p['dal']=$from;$p['al']=$to;}return $this->db->fetchAll($sql.' ORDER BY e.inizio',$p); }
}

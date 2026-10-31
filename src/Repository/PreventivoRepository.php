<?php
declare(strict_types=1);
namespace App\Repository;
final class PreventivoRepository extends WorkflowRepository
{
 protected string $table='preventivi';
 protected array $fillable=['cliente_id','sede_id','sopralluogo_id','numero','titolo','descrizione','voci','imponibile','iva','sconto','validita_fino','stato','note'];
 public function records(): array { return $this->db->fetchAll('SELECT p.*,c.denominazione cliente FROM preventivi p JOIN clienti c ON c.id=p.cliente_id WHERE p.azienda_id=:az AND p.archiviato_at IS NULL ORDER BY p.creato_il DESC',['az'=>$this->aziendaId]); }
}

<?php
declare(strict_types=1);
namespace App\Repository;
final class SopralluogoRepository extends WorkflowRepository
{
 protected string $table='sopralluoghi';
 protected array $fillable=['cliente_id','sede_id','richiesta_id','titolo','modalita','infestante','area_interessata','descrizione','rilevazioni','trattamenti_consigliati','prodotti','precauzioni','costo_stimato','pianificato_il','operatore_id','stato','note'];
 public function records(): array { return $this->db->fetchAll('SELECT s.*,c.denominazione cliente,sd.nome sede_nome FROM sopralluoghi s JOIN clienti c ON c.id=s.cliente_id LEFT JOIN sedi sd ON sd.id=s.sede_id WHERE s.azienda_id=:az AND s.archiviato_at IS NULL ORDER BY s.pianificato_il DESC,s.id DESC',['az'=>$this->aziendaId]); }
}

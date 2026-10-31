<?php
declare(strict_types=1);
namespace App\Controller;
use App\Repository\SopralluogoRepository;
final class SopralluogoController extends WorkflowController
{
 protected string $module='sopralluoghi'; protected string $template='sopralluoghi';
 protected array $fields=['cliente_id','sede_id','titolo','modalita','infestante','area_interessata','descrizione','rilevazioni','trattamenti_consigliati','prodotti','precauzioni','costo_stimato','pianificato_il','operatore_id','stato','note'];
 protected array $required=['cliente_id'=>'Cliente','titolo'=>'Titolo','stato'=>'Stato'];
 protected array $defaults=['cliente_id'=>'','sede_id'=>'','titolo'=>'','modalita'=>'presenza','infestante'=>'','area_interessata'=>'','descrizione'=>'','rilevazioni'=>'','trattamenti_consigliati'=>'','prodotti'=>'','precauzioni'=>'','costo_stimato'=>'','pianificato_il'=>'','operatore_id'=>'','stato'=>'da_pianificare','note'=>''];
 protected function repo(): SopralluogoRepository {return new SopralluogoRepository($this->ctx->db,$this->aziendaId());}
}

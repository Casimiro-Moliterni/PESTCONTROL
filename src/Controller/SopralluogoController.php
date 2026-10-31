<?php
declare(strict_types=1);
namespace App\Controller;
use App\Repository\SopralluogoRepository;
use App\Core\Response;
use App\Repository\SedeRepository;
final class SopralluogoController extends WorkflowController
{
 protected string $module='sopralluoghi'; protected string $template='sopralluoghi';
 protected array $fields=['cliente_id','sede_id','titolo','modalita','infestante','area_interessata','descrizione','rilevazioni','trattamenti_consigliati','prodotti','precauzioni','costo_stimato','pianificato_il','operatore_id','stato','note'];
 protected array $required=['cliente_id'=>'Cliente','titolo'=>'Titolo','stato'=>'Stato'];
 protected array $defaults=['cliente_id'=>'','sede_id'=>'','titolo'=>'','modalita'=>'presenza','infestante'=>'','area_interessata'=>'','descrizione'=>'','rilevazioni'=>'','trattamenti_consigliati'=>'','prodotti'=>'','precauzioni'=>'','costo_stimato'=>'','pianificato_il'=>'','operatore_id'=>'','stato'=>'da_pianificare','note'=>''];
 public function create(): Response { $data=$this->defaults;$cid=$this->ctx->request->query('cliente_id');$sid=$this->ctx->request->query('sede_id');if($sid&&ctype_digit($sid)){ $s=(new SedeRepository($this->ctx->db,$this->aziendaId()))->find((int)$sid);if($s){$data['sede_id']=$s['id'];$data['cliente_id']=$s['cliente_id'];}}elseif($cid&&ctype_digit($cid)){ $c=(new \App\Repository\ClienteRepository($this->ctx->db,$this->aziendaId()))->find((int)$cid);if($c)$data['cliente_id']=$c['id'];}return $this->form($data,null); }
 protected function repo(): SopralluogoRepository {return new SopralluogoRepository($this->ctx->db,$this->aziendaId());}
}

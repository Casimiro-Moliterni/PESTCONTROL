<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Response;
use App\Repository\EventoRepository;
final class CalendarioController extends WorkflowController
{
 protected string $module='calendario'; protected string $template='calendario';
 protected array $fields=['tipo','titolo','descrizione','inizio','fine','assegnato_a','intervento_id','sopralluogo_id'];
 protected array $required=['tipo'=>'Tipo','titolo'=>'Titolo','inizio'=>'Inizio'];
 protected array $defaults=['tipo'=>'appuntamento','titolo'=>'','descrizione'=>'','inizio'=>'','fine'=>'','assegnato_a'=>'','intervento_id'=>'','sopralluogo_id'=>''];
 protected function repo(): EventoRepository {return new EventoRepository($this->ctx->db,$this->aziendaId());}
 public function index(): Response {
  $vista=$this->ctx->request->query('vista','settimana')??'settimana';if(!in_array($vista,['giorno','settimana','mese'],true))$vista='settimana';
  $data=$this->ctx->request->query('data',date('Y-m-d'))??date('Y-m-d');$base=\DateTimeImmutable::createFromFormat('!Y-m-d',$data)?:new \DateTimeImmutable('today');
  if($vista==='giorno'){$start=$base;$end=$base->modify('+1 day');}elseif($vista==='mese'){$start=$base->modify('first day of this month');$end=$start->modify('+1 month');}else{$start=$base->modify('monday this week');$end=$start->modify('+7 days');}
  $records=$this->repo()->records($start->format('Y-m-d 00:00:00'),$end->format('Y-m-d 00:00:00'));$module=$this->module;$startLabel=$start->format('d/m/Y');$endLabel=$end->modify('-1 day')->format('d/m/Y');
  return $this->render('calendario/index',compact('records','module','vista','data','startLabel','endLabel'));
 }
}

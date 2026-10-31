<?php
declare(strict_types=1);
namespace App\Controller;
use App\Repository\PreventivoRepository;
final class PreventivoController extends WorkflowController
{
 protected string $module='preventivi'; protected string $template='preventivi';
 protected array $fields=['cliente_id','sede_id','sopralluogo_id','numero','titolo','descrizione','voci','imponibile','iva','sconto','validita_fino','stato','note'];
 protected array $required=['cliente_id'=>'Cliente','numero'=>'Numero','titolo'=>'Titolo','stato'=>'Stato'];
 protected array $defaults=['cliente_id'=>'','sede_id'=>'','sopralluogo_id'=>'','numero'=>'','titolo'=>'','descrizione'=>'','voci'=>'','imponibile'=>'0','iva'=>'22','sconto'=>'0','validita_fino'=>'','stato'=>'bozza','note'=>''];
 public function create(): \App\Core\Response { $record=$this->defaults; $sid=$this->ctx->request->query('sopralluogo_id');if($sid&&ctype_digit($sid)){ $s=(new \App\Repository\SopralluogoRepository($this->ctx->db,$this->aziendaId()))->find((int)$sid);if($s){$record['sopralluogo_id']=$s['id'];$record['cliente_id']=$s['cliente_id'];$record['sede_id']=$s['sede_id'];$record['titolo']='Servizi consigliati - '.$s['titolo'];$record['descrizione']=$s['trattamenti_consigliati']?:$s['descrizione'];$record['imponibile']=$s['costo_stimato']?:'0';}} return $this->form($record,null); }
 protected function repo(): PreventivoRepository {return new PreventivoRepository($this->ctx->db,$this->aziendaId());}
}

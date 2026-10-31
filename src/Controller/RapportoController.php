<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller;use App\Core\Response;use App\Repository\InterventoRepository;use App\Repository\ProdottoRepository;
final class RapportoController extends Controller
{
 public function print(int $id): Response{$repo=new InterventoRepository($this->ctx->db,$this->aziendaId());$intervento=$repo->detail($id)??$this->notFound();if($this->ctx->auth->soloAssegnati()&&!$repo->isAssigned($id,$this->ctx->auth->id()))$this->notFound();$operatori=$repo->operatori($id);$materiali=(new ProdottoRepository($this->ctx->db,$this->aziendaId()))->visitProducts($id);$allegati=$this->ctx->db->fetchAll('SELECT id,nome_originale,mime FROM allegati WHERE azienda_id=:az AND entita="intervento" AND entita_id=:id ORDER BY id',['az'=>$this->aziendaId(),'id'=>$id]);$azienda=$this->ctx->db->fetchOne('SELECT * FROM aziende WHERE id=:az',['az'=>$this->aziendaId()]);return $this->render('rapporti/intervento',compact('intervento','operatori','materiali','allegati','azienda'),'layout');}
}

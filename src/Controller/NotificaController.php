<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller; use App\Core\Response; use App\Repository\NotificaRepository;
final class NotificaController extends Controller
{
 public function index(): Response{$records=(new NotificaRepository($this->ctx->db,$this->aziendaId()))->forUser($this->ctx->auth->id());return $this->render('notifiche/index',compact('records'));}
 public function read(int $id): Response{$n=$this->ctx->db->fetchOne('SELECT id FROM notifiche WHERE id=:id AND azienda_id=:az AND (utente_id=:u OR utente_id IS NULL)',['id'=>$id,'az'=>$this->aziendaId(),'u'=>$this->ctx->auth->id()]);if(!$n)$this->notFound();$this->ctx->db->execute('UPDATE notifiche SET letta_il=NOW() WHERE id=:id AND azienda_id=:az',['id'=>$id,'az'=>$this->aziendaId()]);return $this->redirect('/app/notifiche','ok','Notifica segnata come letta.');}
}

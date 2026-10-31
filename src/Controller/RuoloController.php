<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller; use App\Core\Response;
final class RuoloController extends Controller
{
 private const MODULES=['clienti','richieste','sopralluoghi','preventivi','interventi','calendario','contratti','dispositivi','magazzino','mappa','utenti','azienda','notifiche'];
 private const ACTIONS=['lettura','creazione','modifica','eliminazione','approvazione','esportazione','esecuzione'];
 public function index(): Response{$ruoli=$this->ctx->db->fetchAll('SELECT r.*,COUNT(rp.modulo) permessi FROM ruoli r LEFT JOIN ruolo_permessi rp ON rp.ruolo_id=r.id WHERE r.azienda_id=:az GROUP BY r.id ORDER BY r.id',['az'=>$this->aziendaId()]);return $this->render('amministrazione/ruoli',compact('ruoli'));}
 public function edit(int $id): Response{$ruolo=$this->ruolo($id);$rows=$this->ctx->db->fetchAll('SELECT modulo,azione FROM ruolo_permessi WHERE ruolo_id=:id',['id'=>$id]);$permessi=[];foreach($rows as $r)$permessi[$r['modulo'].'.'.$r['azione']]=true;$modules=self::MODULES;$actions=self::ACTIONS;return $this->render('amministrazione/ruolo_form',compact('ruolo','permessi','modules','actions'));}
 public function update(int $id): Response{$this->ruolo($id);$this->ctx->db->transaction(function()use($id){$this->ctx->db->execute('DELETE FROM ruolo_permessi WHERE ruolo_id=:id',['id'=>$id]);foreach(self::MODULES as $m)foreach(self::ACTIONS as $a)if($this->ctx->request->post('permessi',[])&&in_array($m.'.'.$a,(array)$this->ctx->request->post('permessi',[]),true))$this->ctx->db->insert('ruolo_permessi',['ruolo_id'=>$id,'modulo'=>$m,'azione'=>$a]);});$this->ctx->audit->record('permessi','ruolo',$id);return $this->redirect('/app/ruoli','ok','Permessi aggiornati.');}
 private function ruolo(int $id): array{return $this->ctx->db->fetchOne('SELECT * FROM ruoli WHERE id=:id AND azienda_id=:az',['id'=>$id,'az'=>$this->aziendaId()])??$this->notFound();}
}

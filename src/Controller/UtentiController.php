<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller; use App\Core\Response; use App\Repository\UtentiAdminRepository;
final class UtentiController extends Controller
{
 private function repo(): UtentiAdminRepository{return new UtentiAdminRepository($this->ctx->db,$this->aziendaId());}
 public function index(): Response{return $this->render('amministrazione/utenti',['utenti'=>$this->repo()->elenco()]);}
 public function create(): Response{return $this->render('amministrazione/utente_form',['utente'=>['nome'=>'','cognome'=>'','email'=>'','telefono'=>'','ruolo_id'=>'','attivo'=>1],'ruoli'=>$this->repo()->ruoli(),'id'=>null]);}
 public function store(): Response{return $this->save(null);}
 public function edit(int $id): Response{$u=$this->repo()->find($id)??$this->notFound();unset($u['password_hash']);return $this->render('amministrazione/utente_form',['utente'=>$u,'ruoli'=>$this->repo()->ruoli(),'id'=>$id]);}
 public function update(int $id): Response{$this->repo()->find($id)??$this->notFound();return $this->save($id);}
 private function save(?int $id): Response
 {
  $d=$this->ctx->request->only(['nome','cognome','email','telefono','ruolo_id','password']);
  if($d['nome']===''||$d['cognome']===''||!filter_var($d['email'],FILTER_VALIDATE_EMAIL)||!ctype_digit($d['ruolo_id']))return $this->redirect('/app/utenti'.($id?'/'.$id.'/modifica':'/nuovo'),'error','Controlla i campi obbligatori.');
  $ruoli=array_column($this->repo()->ruoli(),'id');if(!in_array((int)$d['ruolo_id'],array_map('intval',$ruoli),true))$this->notFound();
  $payload=['nome'=>$d['nome'],'cognome'=>$d['cognome'],'email'=>$d['email'],'telefono'=>$d['telefono'],'ruolo_id'=>(int)$d['ruolo_id'],'attivo'=>(int)($this->ctx->request->post('attivo')==='1')];
  if(!$id||$d['password']!==''){if(strlen($d['password'])<12)return $this->redirect('/app/utenti'.($id?'/'.$id.'/modifica':'/nuovo'),'error','La password deve contenere almeno 12 caratteri.');$payload['password_hash']=password_hash($d['password'],PASSWORD_DEFAULT);}
  if($id)$this->repo()->update($id,$payload);else $id=$this->repo()->create($payload);
  $this->ctx->audit->record($id?'salvataggio':'creazione','utente',$id);return $this->redirect('/app/utenti','ok','Utente salvato.');
 }
}

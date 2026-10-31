<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller; use App\Core\Response;
final class AziendaController extends Controller
{
 public function index(): Response{$azienda=$this->ctx->db->fetchOne('SELECT * FROM aziende WHERE id=:id',['id'=>$this->aziendaId()])??$this->notFound();return $this->render('amministrazione/azienda',compact('azienda'));}
 public function update(): Response{$d=$this->ctx->request->only(['ragione_sociale','partita_iva','codice_fiscale','email','pec','telefono','indirizzo','citta','cap','provincia']);if($d['ragione_sociale']==='')return $this->redirect('/app/azienda','error','La ragione sociale è obbligatoria.');$this->ctx->db->update('aziende',$d,['id'=>$this->aziendaId()]);$this->ctx->audit->record('modifica','azienda',$this->aziendaId());return $this->redirect('/app/azienda','ok','Dati aziendali aggiornati.');}
}

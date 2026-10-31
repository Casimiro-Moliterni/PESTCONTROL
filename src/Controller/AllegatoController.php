<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller; use App\Core\Response; use App\Repository\InterventoRepository;
final class AllegatoController extends Controller
{
 public function upload(int $id): Response
 {
  $intervento=(new InterventoRepository($this->ctx->db,$this->aziendaId()))->find($id)??$this->notFound();
  if(!isset($_FILES['file'])||$_FILES['file']['error']!==UPLOAD_ERR_OK||$_FILES['file']['size']>10*1024*1024)return $this->redirect('/app/interventi/'.$id,'error','File non valido o superiore a 10 MB.');
  $tmp=$_FILES['file']['tmp_name'];$mime=(new \finfo(FILEINFO_MIME_TYPE))->file($tmp)?:'application/octet-stream';
  $allowed=['image/jpeg','image/png','image/webp','application/pdf'];if(!in_array($mime,$allowed,true))return $this->redirect('/app/interventi/'.$id,'error','Sono ammessi PDF e immagini JPG, PNG o WebP.');
  $dir=ROOT.'/storage/uploads/'.$this->aziendaId();if(!is_dir($dir)&&!mkdir($dir,0770,true))return $this->redirect('/app/interventi/'.$id,'error','Impossibile salvare l’allegato.');
  $name=bin2hex(random_bytes(20));if(!move_uploaded_file($tmp,$dir.'/'.$name))return $this->redirect('/app/interventi/'.$id,'error','Caricamento non riuscito.');
  $this->ctx->db->insert('allegati',['azienda_id'=>$this->aziendaId(),'entita'=>'intervento','entita_id'=>$id,'nome_originale'=>basename((string)$_FILES['file']['name']),'percorso'=>$this->aziendaId().'/'.$name,'mime'=>$mime,'dimensione'=>(int)$_FILES['file']['size'],'caricato_da'=>$this->ctx->auth->id()]);
  $this->ctx->audit->record('allegato','intervento',$id);return $this->redirect('/app/interventi/'.$id,'ok','Allegato caricato.');
 }
 public function download(int $id): Response
 {
  $a=$this->ctx->db->fetchOne('SELECT * FROM allegati WHERE id=:id AND azienda_id=:az',['id'=>$id,'az'=>$this->aziendaId()])??$this->notFound();
  if(($a['entita']??'')==='intervento' && $this->ctx->auth->soloAssegnati()) { $assegnato=$this->ctx->db->fetchValue('SELECT 1 FROM intervento_operatori WHERE intervento_id=:i AND utente_id=:u',['i'=>$a['entita_id'],'u'=>$this->ctx->auth->id()]); if(!$assegnato)$this->notFound(); }
  $full=ROOT.'/storage/uploads/'.$a['percorso'];if(!is_file($full))$this->notFound();
  return new Response((string)file_get_contents($full),200,['Content-Type'=>$a['mime']?:'application/octet-stream','Content-Length'=>(string)filesize($full),'Content-Disposition'=>'attachment; filename="'.str_replace('"','',basename($a['nome_originale'])).'"','X-Content-Type-Options'=>'nosniff']);
 }
}

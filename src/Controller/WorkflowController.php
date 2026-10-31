<?php
declare(strict_types=1);
namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Repository\ClienteRepository;
use App\Repository\SedeRepository;
use App\Repository\SopralluogoRepository;
use App\Repository\InterventoRepository;
use App\Repository\UtenteRepository;

/** CRUD server side riusato dai moduli pianificazione della fase 1. */
abstract class WorkflowController extends Controller
{
    protected string $module; protected string $template; protected array $fields=[]; protected array $required=[]; protected array $defaults=[];
    abstract protected function repo(): object;
    public function index(): Response { return $this->render('workflows/index',['records'=>$this->repo()->records(),'module'=>$this->module]); }
    public function create(): Response { return $this->form($this->defaults,null); }
    public function show(int $id): Response { $record=$this->repo()->find($id)??$this->notFound(); if(!empty($record['cliente_id']))$record['cliente_nome']=(new ClienteRepository($this->ctx->db,$this->aziendaId()))->find((int)$record['cliente_id'])['denominazione']??'—'; if(!empty($record['sede_id']))$record['sede_nome']=(new SedeRepository($this->ctx->db,$this->aziendaId()))->find((int)$record['sede_id'])['nome']??'—'; $module=$this->module; return $this->render('workflows/show',compact('record','module')); }
    public function edit(int $id): Response { $record=$this->repo()->find($id)??$this->notFound(); foreach($record as $key=>$value) if(is_string($value)&&preg_match('/^(pianificato_il|inizio|fine)$/',$key)&&$value!=='') $record[$key]=date('Y-m-d\\TH:i',strtotime($value)); return $this->form($record,$id); }
    public function store(): Response { return $this->save(null); }
    public function update(int $id): Response { $this->repo()->find($id)??$this->notFound(); return $this->save($id); }
    public function archive(int $id): Response { $this->repo()->find($id)??$this->notFound(); $this->repo()->archive($id); $this->ctx->audit->record('archiviazione',$this->module,$id); return $this->redirect('/app/'.$this->module,'ok','Elemento archiviato.'); }
    private function save(?int $id): Response
    {
        $data=$this->ctx->request->only($this->fields);
        $errors=[]; foreach($this->required as $field=>$label) if(trim((string)($data[$field]??''))==='') $errors[$field]="$label obbligatorio.";
        foreach(['cliente_id','sede_id','operatore_id','assegnato_a','intervento_id','sopralluogo_id'] as $ref) if(!empty($data[$ref])&&!ctype_digit($data[$ref])) $errors[$ref]='Valore non valido.';
        if(!empty($data['cliente_id']) && !(new ClienteRepository($this->ctx->db,$this->aziendaId()))->find((int)$data['cliente_id'])) $errors['cliente_id']='Cliente non valido.';
        if(!empty($data['sede_id'])) { $sede=(new SedeRepository($this->ctx->db,$this->aziendaId()))->find((int)$data['sede_id']); if(!$sede || (!empty($data['cliente_id']) && (int)$sede['cliente_id']!==(int)$data['cliente_id'])) $errors['sede_id']='Sede non appartenente al cliente selezionato.'; }
        if(!empty($data['sopralluogo_id'])) { $s=(new SopralluogoRepository($this->ctx->db,$this->aziendaId()))->find((int)$data['sopralluogo_id']); if(!$s || (!empty($data['cliente_id'])&&(int)$s['cliente_id']!==(int)$data['cliente_id']))$errors['sopralluogo_id']='Sopralluogo non valido per il cliente selezionato.'; }
        if(!empty($data['intervento_id']) && !(new InterventoRepository($this->ctx->db,$this->aziendaId()))->find((int)$data['intervento_id'])) $errors['intervento_id']='Intervento non valido.';
        if(!empty($data['operatore_id'])||!empty($data['assegnato_a'])) { $idOp=(int)($data['operatore_id']?:$data['assegnato_a']); $valid=array_column((new UtenteRepository($this->ctx->db,$this->aziendaId()))->assegnabili(),'id'); if(!in_array($idOp,array_map('intval',$valid),true)) $errors['operatore_id']='Operatore non valido.'; }
        foreach(['imponibile','iva','sconto','costo_stimato'] as $num) if(isset($data[$num]) && $data[$num]!=='' && (!is_numeric($data[$num]) || (float)$data[$num]<0)) $errors[$num]='Inserire un importo valido.';
        if($errors) return $this->form($data,$id,$errors,422);
        foreach($data as $key=>$value) if(str_ends_with($key,'_il') && $value!=='' && strtotime($value)!==false) $data[$key]=str_replace('T',' ', $value).(strlen($value)===16?':00':'');
        if($id===null) $id=$this->repo()->create($data); else $this->repo()->update($id,$data);
        if($this->module==='sopralluoghi') $this->syncCalendar((int)$id,$data);
        $this->ctx->audit->record($id?'salvataggio':'creazione',$this->module,$id);
        return $this->redirect('/app/'.$this->module.'/'.$id,'ok','Dati salvati.');
    }
    protected function form(array $record,?int $id,array $errors=[],int $status=200): Response
    {
        $clienti=(new ClienteRepository($this->ctx->db,$this->aziendaId()))->all('1=1',[],'denominazione');
        $sedi=(new SedeRepository($this->ctx->db,$this->aziendaId()))->withCliente();
        $utenti=(new UtenteRepository($this->ctx->db,$this->aziendaId()))->assegnabili();
        return $this->render('workflows/form',compact('record','id','errors','clienti','sedi','utenti','module'),'layout',$status);
    }
    private function syncCalendar(int $id,array $data): void
    {
        $event=$this->ctx->db->fetchValue('SELECT id FROM eventi_calendario WHERE azienda_id=:az AND sopralluogo_id=:id AND archiviato_at IS NULL',['az'=>$this->aziendaId(),'id'=>$id]);
        if(empty($data['pianificato_il'])) { if($event)$this->ctx->db->update('eventi_calendario',['archiviato_at'=>date('Y-m-d H:i:s')],['id'=>(int)$event,'azienda_id'=>$this->aziendaId()]); return; }
        $values=['tipo'=>'sopralluogo','titolo'=>'Sopralluogo: '.($data['titolo']??(' #'.$id)),'inizio'=>$data['pianificato_il'],'sopralluogo_id'=>$id,'assegnato_a'=>!empty($data['operatore_id'])?(int)$data['operatore_id']:null,'creato_da'=>$this->ctx->auth->id()];
        if($event)$this->ctx->db->update('eventi_calendario',['titolo'=>$values['titolo'],'inizio'=>$values['inizio'],'assegnato_a'=>$values['assegnato_a']],['id'=>(int)$event,'azienda_id'=>$this->aziendaId()]);
        else $this->ctx->db->insert('eventi_calendario',['azienda_id'=>$this->aziendaId()]+$values);
    }
}

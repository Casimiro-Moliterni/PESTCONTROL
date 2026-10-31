<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Validator;
use App\Repository\InterventoRepository;
use App\Repository\SedeRepository;
use App\Repository\UtenteRepository;
use App\Repository\ProdottoRepository;

final class InterventoController extends Controller
{
    private const FIELDS = [
        'sede_id', 'tipo_servizio', 'infestante', 'descrizione', 'istruzioni',
        'pianificato_il', 'durata_stimata_min', 'stato',
    ];

    public function index(): Response
    {
        $stato = $this->ctx->request->query('stato');
        $interventi = $this->repo()->search([
            'stato'        => $stato,
            'operatore_id' => $this->operatoreFiltro(),
        ]);
        return $this->render('interventi/index', compact('interventi', 'stato'));
    }

    public function create(): Response
    {
        $sedeId = $this->ctx->request->query('sede_id');
        return $this->form(['stato' => 'da_pianificare', 'sede_id' => $sedeId], [], null);
    }

    public function store(): Response
    {
        [$data, $operatori, $errors] = $this->readInput();
        if ($errors) {
            return $this->form($data, $operatori, null, $errors, 422);
        }
        $id = $this->repo()->create($data);
        $this->repo()->setOperatori($id, $operatori);
        $this->syncCalendar($id,$data,$operatori);
        $this->notifyOperators($id,$operatori);
        $this->ctx->audit->record('creazione', 'intervento', $id);
        return $this->redirect("/app/interventi/$id", 'ok', 'Intervento creato.');
    }

    public function show(int $id): Response
    {
        $intervento = $this->repo()->detail($id) ?? $this->notFound();
        $this->assertVisible($id);
        $operatori = $this->repo()->operatori($id);
        $allegati = $this->ctx->db->fetchAll('SELECT id,nome_originale,dimensione,creato_il FROM allegati WHERE azienda_id=:az AND entita="intervento" AND entita_id=:id ORDER BY id DESC',['az'=>$this->aziendaId(),'id'=>$id]);
        $prodottoRepo = new ProdottoRepository($this->ctx->db,$this->aziendaId());
        $materiali = $prodottoRepo->visitProducts($id);
        $prodotti = array_values(array_filter($prodottoRepo->records(),static fn(array $p): bool => !in_array((int)$p['id'],array_map('intval',array_column($materiali,'prodotto_id')),true)));
        $lotti=[]; foreach($prodotti as $p) foreach($prodottoRepo->lotti((int)$p['id']) as $lot) $lotti[]=$lot+['nome_prodotto'=>$p['nome']];
        $puoEseguire = $this->ctx->auth->can('interventi', 'esecuzione')
            && ($this->ctx->auth->can('interventi', 'modifica') || $this->repo()->isAssigned($id, $this->ctx->auth->id()));
        return $this->render('interventi/show', compact('intervento', 'operatori', 'puoEseguire','allegati','materiali','prodotti','lotti'));
    }

    public function edit(int $id): Response
    {
        $intervento = $this->repo()->find($id) ?? $this->notFound();
        $intervento['pianificato_il'] = $intervento['pianificato_il']
            ? date('Y-m-d\TH:i', strtotime($intervento['pianificato_il'])) : '';
        $assegnati = array_column($this->repo()->operatori($id), 'id');
        return $this->form($intervento, $assegnati, $id);
    }

    public function update(int $id): Response
    {
        $this->repo()->find($id) ?? $this->notFound();
        $precedenti = array_map('intval', array_column($this->repo()->operatori($id), 'id'));
        [$data, $operatori, $errors] = $this->readInput();
        if ($errors) {
            return $this->form($data, $operatori, $id, $errors, 422);
        }
        $this->repo()->update($id, $data);
        $this->repo()->setOperatori($id, $operatori);
        $this->syncCalendar($id,$data,$operatori);
        $this->notifyOperators($id,array_values(array_diff($operatori,$precedenti)));
        $this->ctx->audit->record('modifica', 'intervento', $id);
        return $this->redirect("/app/interventi/$id", 'ok', 'Intervento aggiornato.');
    }

    /** Aggiornamento della scheda da parte dell'operatore (stato, esito, note). */
    public function execute(int $id): Response
    {
        $intervento = $this->repo()->find($id) ?? $this->notFound();
        $auth = $this->ctx->auth;
        if (!$auth->can('interventi', 'modifica') && !$this->repo()->isAssigned($id, $auth->id())) {
            $this->notFound(); // non rivela l'esistenza di interventi altrui
        }

        $data = $this->ctx->request->only(['stato', 'esito', 'note_operatore']);
        $errors = Validator::validate($data, ['stato' => 'required|' . $this->inRule('intervento_stato_operatore')]);
        if ($errors) {
            return $this->redirect("/app/interventi/$id", 'error', 'Stato non valido.');
        }

        $update = $data;
        if ($data['stato'] === 'in_corso' && !$intervento['inizio_effettivo']) {
            $update['inizio_effettivo'] = date('Y-m-d H:i:s');
        }
        if (in_array($data['stato'], ['completato', 'da_verificare'], true)) {
            $update['inizio_effettivo'] = $intervento['inizio_effettivo'] ?? date('Y-m-d H:i:s');
            $update['fine_effettiva'] = date('Y-m-d H:i:s');
        }
        $this->repo()->update($id, $update);
        if ($data['stato'] === 'completato' && !empty($intervento['visita_contratto_id'])) {
            $this->ctx->db->update('visite_contratto', ['stato' => 'completata'], ['id' => (int) $intervento['visita_contratto_id'], 'azienda_id' => $this->aziendaId()]);
        }
        $this->ctx->audit->record('esecuzione', 'intervento', $id, ['stato' => $data['stato']]);
        return $this->redirect("/app/interventi/$id", 'ok', 'Scheda aggiornata.');
    }

    public function archive(int $id): Response
    {
        $this->repo()->find($id) ?? $this->notFound();
        $this->repo()->archive($id);
        $this->ctx->audit->record('archiviazione', 'intervento', $id);
        return $this->redirect('/app/interventi', 'ok', 'Intervento archiviato.');
    }

    /** @return array{0: array, 1: int[], 2: array} dati validati, operatori, errori */
    private function readInput(): array
    {
        $data = $this->ctx->request->only(self::FIELDS);
        $errors = Validator::validate($data, [
            'sede_id'            => 'required|int',
            'tipo_servizio'      => 'required|' . $this->inRule('servizio_tipo'),
            'stato'              => 'required|' . $this->inRule('intervento_stato'),
            'infestante'         => 'max:150',
            'pianificato_il'     => 'datetime',
            'durata_stimata_min' => 'int',
        ]);

        // La sede determina il cliente e deve appartenere all'azienda.
        $sede = ctype_digit($data['sede_id']) ? (new SedeRepository($this->ctx->db, $this->aziendaId()))->find((int) $data['sede_id']) : null;
        if ($sede === null) {
            $errors['sede_id'] = $errors['sede_id'] ?? 'Sede non valida.';
        } else {
            $data['cliente_id'] = $sede['cliente_id'];
        }

        // Operatori: solo id di utenti assegnabili della stessa azienda.
        $validi = array_map('intval', array_column((new UtenteRepository($this->ctx->db, $this->aziendaId()))->assegnabili(), 'id'));
        $scelti = array_map('intval', (array) $this->ctx->request->post('operatori', []));
        $operatori = array_values(array_intersect($scelti, $validi));

        if ($data['pianificato_il'] !== '' && empty($errors['pianificato_il'])) {
            $data['pianificato_il'] = date('Y-m-d H:i:00', strtotime($data['pianificato_il']));
        }
        return [$data, $operatori, $errors];
    }

    private function form(array $intervento, array $assegnati, ?int $id, array $errors = [], int $status = 200): Response
    {
        $sedi = (new SedeRepository($this->ctx->db, $this->aziendaId()))->withCliente();
        $utenti = (new UtenteRepository($this->ctx->db, $this->aziendaId()))->assegnabili();
        return $this->render('interventi/form', compact('intervento', 'assegnati', 'id', 'errors', 'sedi', 'utenti'), 'layout', $status);
    }

    private function operatoreFiltro(): ?int
    {
        return $this->ctx->auth->soloAssegnati() ? $this->ctx->auth->id() : null;
    }

    private function assertVisible(int $id): void
    {
        if ($this->ctx->auth->soloAssegnati() && !$this->repo()->isAssigned($id, $this->ctx->auth->id())) {
            $this->notFound();
        }
    }

    private function repo(): InterventoRepository
    {
        return new InterventoRepository($this->ctx->db, $this->aziendaId());
    }

    private function syncCalendar(int $id,array $data,array $operatori): void
    {
        $exists=$this->ctx->db->fetchValue('SELECT id FROM eventi_calendario WHERE azienda_id=:az AND intervento_id=:i AND archiviato_at IS NULL',['az'=>$this->aziendaId(),'i'=>$id]);
        if(empty($data['pianificato_il'])) {if($exists)$this->ctx->db->update('eventi_calendario',['archiviato_at'=>date('Y-m-d H:i:s')],['id'=>(int)$exists,'azienda_id'=>$this->aziendaId()]);return;}
        $values=['tipo'=>'intervento','titolo'=>'Intervento #'.$id,'descrizione'=>$data['descrizione']??null,'inizio'=>$data['pianificato_il'],'intervento_id'=>$id,'assegnato_a'=>$operatori[0]??null,'creato_da'=>$this->ctx->auth->id()];
        if($exists)$this->ctx->db->update('eventi_calendario',['inizio'=>$values['inizio'],'titolo'=>$values['titolo'],'descrizione'=>$values['descrizione'],'assegnato_a'=>$values['assegnato_a']],['id'=>(int)$exists,'azienda_id'=>$this->aziendaId()]);
        else $this->ctx->db->insert('eventi_calendario',['azienda_id'=>$this->aziendaId()]+$values);
    }

    private function notifyOperators(int $id,array $operatori): void
    {
        foreach($operatori as $uid)$this->ctx->db->insert('notifiche',['azienda_id'=>$this->aziendaId(),'utente_id'=>$uid,'titolo'=>'Intervento assegnato','messaggio'=>'Ti è stato assegnato l’intervento #'.$id.'.','link'=>'/app/interventi/'.$id]);
    }
}

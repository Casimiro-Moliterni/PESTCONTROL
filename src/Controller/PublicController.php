<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Validator;
use App\Repository\RichiestaRepository;

/** Pagine pubbliche, separate dall'area riservata. */
final class PublicController extends Controller
{
    private const FIELDS = [
        'tipo', 'nome', 'azienda_richiedente', 'email', 'telefono', 'localita',
        'descrizione', 'preferenza_contatto',
    ];
    private const MIN_SECONDS = 3;

    public function home(): Response
    {
        return $this->render('pubblico/home', [], 'layout_pubblico');
    }

    public function contactForm(): Response
    {
        $this->ctx->session->set('_form_ts', time());
        return $this->renderForm([]);
    }

    public function contactStore(): Response
    {
        $request = $this->ctx->request;

        // Antispam: honeypot + tempo minimo di compilazione. Rispondiamo come se fosse andata bene.
        $troppoVeloce = time() - (int) $this->ctx->session->get('_form_ts', 0) < self::MIN_SECONDS;
        if ($request->post('sito_web', '') !== '' || $troppoVeloce) {
            return $this->render('pubblico/grazie', [], 'layout_pubblico');
        }

        $data = $request->only(self::FIELDS);
        $errors = Validator::validate($data, [
            'tipo'                => 'required|' . $this->inRule('richiesta_tipo'),
            'nome'                => 'required|max:150',
            'azienda_richiedente' => 'max:200',
            'email'               => 'required|email|max:190',
            'telefono'            => 'max:40',
            'localita'            => 'max:255',
            'descrizione'         => 'required|max:5000',
            'preferenza_contatto' => 'in:email,telefono',
        ]);
        if ($request->post('privacy') !== '1') {
            $errors['privacy'] = 'Devi accettare l\'informativa privacy.';
        }
        if ($errors) {
            return $this->renderForm($data, $errors, 422);
        }

        $repo = new RichiestaRepository($this->ctx->db, (int) $this->ctx->config['app']['public_azienda_id']);
        $id=$repo->create($data + ['origine' => 'sito', 'stato' => 'nuova']);
        $aziendaId=(int)$this->ctx->config['app']['public_azienda_id'];
        $destinatari=$this->ctx->db->fetchAll('SELECT DISTINCT u.id FROM utenti u JOIN ruolo_permessi rp ON rp.ruolo_id=u.ruolo_id WHERE u.azienda_id=:az AND u.attivo=1 AND rp.modulo="richieste" AND rp.azione="lettura"',['az'=>$aziendaId]);
        foreach($destinatari as $utente)$this->ctx->db->insert('notifiche',['azienda_id'=>$aziendaId,'utente_id'=>(int)$utente['id'],'titolo'=>'Nuova richiesta dal sito','messaggio'=>'Richiesta ricevuta da '.$data['nome'].'.','link'=>'/app/richieste/'.$id]);
        $this->ctx->session->forget('_form_ts');
        return $this->render('pubblico/grazie', [], 'layout_pubblico');
    }

    private function renderForm(array $data, array $errors = [], int $status = 200): Response
    {
        return $this->render('pubblico/contatti', compact('data', 'errors'), 'layout_pubblico', $status);
    }
}

<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller;use App\Core\Response;
final class MappaController extends Controller
{
 public function index(): Response{$punti=$this->ctx->db->fetchAll('SELECT s.id sede_id,s.cliente_id,s.nome sede,s.indirizzo,s.citta,s.latitudine,s.longitudine,c.denominazione cliente FROM sedi s JOIN clienti c ON c.id=s.cliente_id WHERE s.azienda_id=:az AND s.archiviato_at IS NULL AND c.archiviato_at IS NULL AND s.latitudine IS NOT NULL AND s.longitudine IS NOT NULL',['az'=>$this->aziendaId()]);$dispositivi=$this->ctx->db->fetchAll('SELECT d.id,d.codice,d.tipo,d.posizione,COALESCE(d.latitudine,s.latitudine) latitudine,COALESCE(d.longitudine,s.longitudine) longitudine,d.stato,d.sede_id,s.nome sede,c.denominazione cliente FROM dispositivi d JOIN sedi s ON s.id=d.sede_id JOIN clienti c ON c.id=d.cliente_id WHERE d.azienda_id=:az AND d.archiviato_at IS NULL AND (d.latitudine IS NOT NULL OR s.latitudine IS NOT NULL) AND (d.longitudine IS NOT NULL OR s.longitudine IS NOT NULL)',['az'=>$this->aziendaId()]);$json=json_encode(['sedi'=>$punti,'dispositivi'=>$dispositivi],JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);return $this->render('mappa/index',compact('punti','dispositivi','json'));}
}

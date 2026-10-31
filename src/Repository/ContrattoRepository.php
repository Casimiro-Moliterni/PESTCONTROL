<?php
declare(strict_types=1);
namespace App\Repository;
final class ContrattoRepository extends Repository
{
 protected string $table='contratti';
 protected array $fillable=['cliente_id','sede_id','preventivo_id','numero','titolo','servizi','data_inizio','data_fine','frequenza','totale_visite','prezzo','modalita_pagamento','attivita_escluse','note','stato'];
 public function find(int $id): ?array {return $this->db->fetchOne('SELECT c.*,cl.denominazione cliente,s.nome sede_nome FROM contratti c JOIN clienti cl ON cl.id=c.cliente_id LEFT JOIN sedi s ON s.id=c.sede_id WHERE c.id=:id AND c.azienda_id=:az AND c.archiviato_at IS NULL',['id'=>$id,'az'=>$this->aziendaId]);}
 public function records(): array {return $this->db->fetchAll('SELECT c.*,cl.denominazione cliente,(SELECT COUNT(*) FROM visite_contratto v WHERE v.contratto_id=c.id) visite_generate,(SELECT COUNT(*) FROM visite_contratto v WHERE v.contratto_id=c.id AND v.stato="completata") visite_completate FROM contratti c JOIN clienti cl ON cl.id=c.cliente_id WHERE c.azienda_id=:az AND c.archiviato_at IS NULL ORDER BY c.data_inizio DESC',['az'=>$this->aziendaId]);}
 public function visits(int $id): array {return $this->db->fetchAll('SELECT v.*,i.stato stato_intervento FROM visite_contratto v LEFT JOIN interventi i ON i.id=v.intervento_id WHERE v.azienda_id=:az AND v.contratto_id=:c ORDER BY v.numero_visita',['az'=>$this->aziendaId,'c'=>$id]);}
 public function visit(int $id,int $visitId): ?array {return $this->db->fetchOne('SELECT v.*,c.cliente_id,c.sede_id,c.titolo,c.servizi,c.stato stato_contratto FROM visite_contratto v JOIN contratti c ON c.id=v.contratto_id WHERE v.azienda_id=:az AND v.contratto_id=:c AND v.id=:v AND c.archiviato_at IS NULL',['az'=>$this->aziendaId,'c'=>$id,'v'=>$visitId]);}
 public function history(int $visitId): array {return $this->db->fetchAll('SELECT h.*,CONCAT(u.nome," ",u.cognome) utente FROM ripianificazioni_visite h LEFT JOIN utenti u ON u.id=h.modificato_da WHERE h.azienda_id=:az AND h.visita_id=:v ORDER BY h.modificato_il DESC',['az'=>$this->aziendaId,'v'=>$visitId]);}
}

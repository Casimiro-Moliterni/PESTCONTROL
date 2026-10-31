<?php
declare(strict_types=1);
namespace App\Repository;
final class ProdottoRepository extends Repository
{
 protected string $table='prodotti';
 protected array $fillable=['codice','nome','categoria','produttore','fornitore','descrizione','unita_misura','giacenza','scorta_minima','costo_acquisto','prezzo_vendita','tracciamento_lotto','scheda_tecnica_path','scheda_sicurezza_path','note'];
 public function records(): array {return $this->all('1=1',[],'nome ASC');}
 public function lotti(int $id): array {return $this->db->fetchAll('SELECT * FROM lotti_magazzino WHERE azienda_id=:az AND prodotto_id=:p ORDER BY scadenza IS NULL,scadenza,lotto',['az'=>$this->aziendaId,'p'=>$id]);}
 public function movements(int $id): array {return $this->db->fetchAll('SELECT m.*,l.lotto,CONCAT(u.nome," ",u.cognome) utente FROM movimenti_magazzino m LEFT JOIN lotti_magazzino l ON l.id=m.lotto_id LEFT JOIN utenti u ON u.id=m.utente_id WHERE m.azienda_id=:az AND m.prodotto_id=:p ORDER BY m.creato_il DESC LIMIT 100',['az'=>$this->aziendaId,'p'=>$id]);}
 public function visitProducts(int $interventoId): array {return $this->db->fetchAll('SELECT ip.*,p.nome prodotto,p.unita_misura,l.lotto FROM intervento_prodotti ip JOIN prodotti p ON p.id=ip.prodotto_id LEFT JOIN lotti_magazzino l ON l.id=ip.lotto_id WHERE ip.azienda_id=:az AND ip.intervento_id=:i ORDER BY p.nome',['az'=>$this->aziendaId,'i'=>$interventoId]);}
}

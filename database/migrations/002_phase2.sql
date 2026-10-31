-- Operatività sul campo (applicare una sola volta dopo 001_phase1.sql).
ALTER TABLE interventi ADD COLUMN contratto_id INT UNSIGNED NULL AFTER durata_stimata_min;
ALTER TABLE interventi ADD COLUMN visita_contratto_id INT UNSIGNED NULL AFTER contratto_id;

CREATE TABLE prodotti (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, codice VARCHAR(60) NULL,
 nome VARCHAR(180) NOT NULL, categoria VARCHAR(60) NOT NULL DEFAULT 'prodotto', produttore VARCHAR(150) NULL,
 fornitore VARCHAR(150) NULL, descrizione TEXT NULL, unita_misura VARCHAR(30) NOT NULL DEFAULT 'pz',
 giacenza DECIMAL(12,3) NOT NULL DEFAULT 0, scorta_minima DECIMAL(12,3) NOT NULL DEFAULT 0,
 costo_acquisto DECIMAL(12,2) NULL, prezzo_vendita DECIMAL(12,2) NULL, tracciamento_lotto TINYINT(1) NOT NULL DEFAULT 0,
 scheda_tecnica_path VARCHAR(500) NULL, scheda_sicurezza_path VARCHAR(500) NULL, note TEXT NULL,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 archiviato_at DATETIME NULL, UNIQUE KEY uq_prodotto_codice (azienda_id,codice), FOREIGN KEY (azienda_id) REFERENCES aziende(id),
 INDEX idx_prodotti_azienda (azienda_id,archiviato_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE dispositivi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL,
 sede_id INT UNSIGNED NOT NULL, prodotto_id INT UNSIGNED NULL, codice VARCHAR(80) NOT NULL, qr_token CHAR(64) NOT NULL,
 tipo VARCHAR(80) NOT NULL, modello VARCHAR(120) NULL, posizione VARCHAR(255) NULL, descrizione TEXT NULL,
 latitudine DECIMAL(10,7) NULL, longitudine DECIMAL(10,7) NULL, installato_il DATE NULL, stato VARCHAR(30) NOT NULL DEFAULT 'attivo',
 esca VARCHAR(150) NULL, ultimo_controllo DATETIME NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, archiviato_at DATETIME NULL,
 UNIQUE KEY uq_dispositivo_codice (azienda_id,codice), UNIQUE KEY uq_dispositivo_qr (qr_token),
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id), FOREIGN KEY (sede_id) REFERENCES sedi(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id), INDEX idx_dispositivi_sede (azienda_id,sede_id,stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contratti (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL,
 sede_id INT UNSIGNED NULL, preventivo_id INT UNSIGNED NULL, numero VARCHAR(50) NOT NULL, titolo VARCHAR(180) NOT NULL,
 servizi TEXT NULL, data_inizio DATE NOT NULL, data_fine DATE NULL, frequenza VARCHAR(30) NOT NULL, totale_visite INT UNSIGNED NOT NULL,
 prezzo DECIMAL(12,2) NOT NULL DEFAULT 0, modalita_pagamento VARCHAR(120) NULL, attivita_escluse TEXT NULL, note TEXT NULL,
 stato VARCHAR(30) NOT NULL DEFAULT 'bozza', creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, archiviato_at DATETIME NULL,
 UNIQUE KEY uq_contratto_numero (azienda_id,numero), FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id), FOREIGN KEY (sede_id) REFERENCES sedi(id), INDEX idx_contratti_azienda (azienda_id,stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE visite_contratto (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, contratto_id INT UNSIGNED NOT NULL,
 numero_visita INT UNSIGNED NOT NULL, pianificata_il DATETIME NULL, stato VARCHAR(30) NOT NULL DEFAULT 'da_pianificare',
 intervento_id INT UNSIGNED NULL, note TEXT NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_visita_numero (contratto_id,numero_visita), FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (contratto_id) REFERENCES contratti(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE contratti ADD CONSTRAINT fk_contratti_preventivo FOREIGN KEY (preventivo_id) REFERENCES preventivi(id);
ALTER TABLE interventi ADD CONSTRAINT fk_interventi_contratto FOREIGN KEY (contratto_id) REFERENCES contratti(id);
ALTER TABLE interventi ADD CONSTRAINT fk_interventi_visita FOREIGN KEY (visita_contratto_id) REFERENCES visite_contratto(id);

CREATE TABLE controlli_dispositivi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, dispositivo_id INT UNSIGNED NOT NULL,
 operatore_id INT UNSIGNED NOT NULL, controllato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, esito VARCHAR(60) NOT NULL,
 attivita TEXT NULL, tipo_esca VARCHAR(150) NULL, quantita_esca DECIMAL(10,3) NULL, consumo VARCHAR(60) NULL,
 anomalia TEXT NULL, note TEXT NULL, latitudine DECIMAL(10,7) NULL, longitudine DECIMAL(10,7) NULL,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (dispositivo_id) REFERENCES dispositivi(id), FOREIGN KEY (operatore_id) REFERENCES utenti(id), INDEX idx_controllo_device (azienda_id,dispositivo_id,controllato_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE ripianificazioni_visite (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, visita_id INT UNSIGNED NOT NULL,
 data_precedente DATETIME NULL, data_nuova DATETIME NOT NULL, motivo VARCHAR(255) NULL, modificato_da INT UNSIGNED NULL,
 modificato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (visita_id) REFERENCES visite_contratto(id), FOREIGN KEY (modificato_da) REFERENCES utenti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE lotti_magazzino (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, prodotto_id INT UNSIGNED NOT NULL,
 lotto VARCHAR(100) NOT NULL, scadenza DATE NULL, quantita DECIMAL(12,3) NOT NULL DEFAULT 0, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_lotto (azienda_id,prodotto_id,lotto), FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE movimenti_magazzino (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, prodotto_id INT UNSIGNED NOT NULL,
 lotto_id INT UNSIGNED NULL, intervento_id INT UNSIGNED NULL, tipo VARCHAR(30) NOT NULL, quantita DECIMAL(12,3) NOT NULL,
 causale VARCHAR(255) NOT NULL, utente_id INT UNSIGNED NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id), FOREIGN KEY (lotto_id) REFERENCES lotti_magazzino(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id), FOREIGN KEY (utente_id) REFERENCES utenti(id), INDEX idx_movimenti_prodotto (azienda_id,prodotto_id,creato_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE intervento_prodotti (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, intervento_id INT UNSIGNED NOT NULL,
 prodotto_id INT UNSIGNED NOT NULL, lotto_id INT UNSIGNED NULL, quantita_prevista DECIMAL(12,3) NOT NULL DEFAULT 0,
 quantita_prelevata DECIMAL(12,3) NOT NULL DEFAULT 0, quantita_utilizzata DECIMAL(12,3) NOT NULL DEFAULT 0,
 quantita_restituita DECIMAL(12,3) NOT NULL DEFAULT 0, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id), FOREIGN KEY (lotto_id) REFERENCES lotti_magazzino(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE eventi_calendario ADD COLUMN contratto_id INT UNSIGNED NULL;
ALTER TABLE eventi_calendario ADD COLUMN visita_contratto_id INT UNSIGNED NULL;
ALTER TABLE eventi_calendario ADD CONSTRAINT fk_evento_contratto FOREIGN KEY (contratto_id) REFERENCES contratti(id);
ALTER TABLE eventi_calendario ADD CONSTRAINT fk_evento_visita FOREIGN KEY (visita_contratto_id) REFERENCES visite_contratto(id);

-- Permessi per i ruoli iniziali. L'operatore aggiorna i dispositivi assegnati sul campo;
-- l'ufficio mantiene la gestione amministrativa del magazzino.
INSERT IGNORE INTO ruolo_permessi (ruolo_id,modulo,azione)
 SELECT r.id,m.modulo,a.azione FROM ruoli r CROSS JOIN
 (SELECT 'contratti' modulo UNION SELECT 'dispositivi' UNION SELECT 'magazzino' UNION SELECT 'mappa') m CROSS JOIN
 (SELECT 'lettura' azione UNION SELECT 'creazione' UNION SELECT 'modifica' UNION SELECT 'eliminazione' UNION SELECT 'esecuzione') a
 WHERE r.nome='Amministratore';
INSERT IGNORE INTO ruolo_permessi (ruolo_id,modulo,azione)
 SELECT r.id,m.modulo,a.azione FROM ruoli r CROSS JOIN
 (SELECT 'contratti' modulo UNION SELECT 'dispositivi' UNION SELECT 'magazzino' UNION SELECT 'mappa') m CROSS JOIN
 (SELECT 'lettura' azione UNION SELECT 'creazione' UNION SELECT 'modifica') a WHERE r.nome='Ufficio';
INSERT IGNORE INTO ruolo_permessi (ruolo_id,modulo,azione)
 SELECT r.id,m.modulo,a.azione FROM ruoli r CROSS JOIN
 (SELECT 'dispositivi' modulo UNION SELECT 'magazzino' UNION SELECT 'mappa') m CROSS JOIN
 (SELECT 'lettura' azione UNION SELECT 'esecuzione') a WHERE r.nome='Operatore';

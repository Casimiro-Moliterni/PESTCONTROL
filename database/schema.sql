-- Pest Control Manager - schema MariaDB (Fase 1 / MVP)
SET NAMES utf8mb4;

CREATE TABLE aziende (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ragione_sociale VARCHAR(200) NOT NULL,
    partita_iva VARCHAR(20) NULL,
    email VARCHAR(190) NULL,
    telefono VARCHAR(40) NULL,
    codice_fiscale VARCHAR(20) NULL,
    pec VARCHAR(190) NULL,
    indirizzo VARCHAR(255) NULL,
    citta VARCHAR(100) NULL,
    cap VARCHAR(10) NULL,
    provincia VARCHAR(5) NULL,
    logo_path VARCHAR(500) NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ruoli (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NOT NULL,
    nome VARCHAR(80) NOT NULL,
    descrizione VARCHAR(255) NULL,
    solo_assegnati TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (azienda_id) REFERENCES aziende(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ruolo_permessi (
    ruolo_id INT UNSIGNED NOT NULL,
    modulo VARCHAR(40) NOT NULL,
    azione VARCHAR(40) NOT NULL,
    PRIMARY KEY (ruolo_id, modulo, azione),
    FOREIGN KEY (ruolo_id) REFERENCES ruoli(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE utenti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NOT NULL,
    ruolo_id INT UNSIGNED NOT NULL,
    nome VARCHAR(80) NOT NULL,
    cognome VARCHAR(80) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    telefono VARCHAR(40) NULL,
    password_hash VARCHAR(255) NOT NULL,
    attivo TINYINT(1) NOT NULL DEFAULT 1,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (azienda_id) REFERENCES aziende(id),
    FOREIGN KEY (ruolo_id) REFERENCES ruoli(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clienti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NOT NULL,
    tipo VARCHAR(30) NOT NULL DEFAULT 'privato',
    denominazione VARCHAR(200) NOT NULL,
    codice_fiscale VARCHAR(20) NULL,
    partita_iva VARCHAR(20) NULL,
    email VARCHAR(190) NULL,
    telefono VARCHAR(40) NULL,
    pec VARCHAR(190) NULL,
    indirizzo VARCHAR(255) NULL,
    citta VARCHAR(100) NULL,
    cap VARCHAR(10) NULL,
    provincia VARCHAR(5) NULL,
    stato VARCHAR(30) NOT NULL DEFAULT 'prospect',
    etichetta VARCHAR(60) NULL,           -- classificazione commerciale (es. VIP), distinta dallo stato
    note TEXT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    archiviato_at DATETIME NULL,
    INDEX idx_clienti_azienda (azienda_id, archiviato_at),
    FOREIGN KEY (azienda_id) REFERENCES aziende(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sedi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NOT NULL,
    cliente_id INT UNSIGNED NOT NULL,
    nome VARCHAR(150) NOT NULL,
    indirizzo VARCHAR(255) NULL,
    citta VARCHAR(100) NULL,
    cap VARCHAR(10) NULL,
    provincia VARCHAR(5) NULL,
    latitudine DECIMAL(10,7) NULL,
    longitudine DECIMAL(10,7) NULL,
    referente_nome VARCHAR(150) NULL,
    referente_telefono VARCHAR(40) NULL,
    istruzioni_accesso TEXT NULL,
    orari_consentiti VARCHAR(255) NULL,
    note TEXT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    archiviato_at DATETIME NULL,
    INDEX idx_sedi_cliente (cliente_id),
    FOREIGN KEY (azienda_id) REFERENCES aziende(id),
    FOREIGN KEY (cliente_id) REFERENCES clienti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
 latitudine DECIMAL(10,7) NULL, longitudine DECIMAL(10,7) NULL, installato_il DATE NULL,
 stato VARCHAR(30) NOT NULL DEFAULT 'attivo', esca VARCHAR(150) NULL, ultimo_controllo DATETIME NULL,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 archiviato_at DATETIME NULL, UNIQUE KEY uq_dispositivo_codice (azienda_id,codice), UNIQUE KEY uq_dispositivo_qr (qr_token),
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id), FOREIGN KEY (sede_id) REFERENCES sedi(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id),
 INDEX idx_dispositivi_sede (azienda_id,sede_id,stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE controlli_dispositivi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, dispositivo_id INT UNSIGNED NOT NULL,
 operatore_id INT UNSIGNED NOT NULL, controllato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 esito VARCHAR(60) NOT NULL, attivita TEXT NULL, tipo_esca VARCHAR(150) NULL, quantita_esca DECIMAL(10,3) NULL,
 consumo VARCHAR(60) NULL, anomalia TEXT NULL, note TEXT NULL, latitudine DECIMAL(10,7) NULL, longitudine DECIMAL(10,7) NULL,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (azienda_id) REFERENCES aziende(id),
 FOREIGN KEY (dispositivo_id) REFERENCES dispositivi(id), FOREIGN KEY (operatore_id) REFERENCES utenti(id), INDEX idx_controllo_device (azienda_id,dispositivo_id,controllato_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE richieste (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    azienda_richiedente VARCHAR(200) NULL,
    email VARCHAR(190) NULL,
    telefono VARCHAR(40) NULL,
    localita VARCHAR(255) NULL,
    descrizione TEXT NOT NULL,
    preferenza_contatto VARCHAR(30) NULL,
    origine VARCHAR(30) NOT NULL DEFAULT 'sito',
    stato VARCHAR(30) NOT NULL DEFAULT 'nuova',
    assegnato_a INT UNSIGNED NULL,
    cliente_id INT UNSIGNED NULL,
    ricevuta_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    archiviato_at DATETIME NULL,
    INDEX idx_richieste_stato (azienda_id, stato),
    FOREIGN KEY (azienda_id) REFERENCES aziende(id),
    FOREIGN KEY (assegnato_a) REFERENCES utenti(id),
    FOREIGN KEY (cliente_id) REFERENCES clienti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contratti (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL,
 sede_id INT UNSIGNED NULL, preventivo_id INT UNSIGNED NULL, numero VARCHAR(50) NOT NULL, titolo VARCHAR(180) NOT NULL,
 servizi TEXT NULL, data_inizio DATE NOT NULL, data_fine DATE NULL, frequenza VARCHAR(30) NOT NULL,
 totale_visite INT UNSIGNED NOT NULL, prezzo DECIMAL(12,2) NOT NULL DEFAULT 0, modalita_pagamento VARCHAR(120) NULL,
 attivita_escluse TEXT NULL, note TEXT NULL, stato VARCHAR(30) NOT NULL DEFAULT 'bozza',
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 archiviato_at DATETIME NULL, UNIQUE KEY uq_contratto_numero (azienda_id,numero),
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id), FOREIGN KEY (sede_id) REFERENCES sedi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE interventi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NOT NULL,
    cliente_id INT UNSIGNED NOT NULL,
    sede_id INT UNSIGNED NOT NULL,
    tipo_servizio VARCHAR(30) NOT NULL,
    infestante VARCHAR(150) NULL,
    descrizione TEXT NULL,
    istruzioni TEXT NULL,
    pianificato_il DATETIME NULL,
    durata_stimata_min INT UNSIGNED NULL,
    contratto_id INT UNSIGNED NULL,
    visita_contratto_id INT UNSIGNED NULL,
    inizio_effettivo DATETIME NULL,
    fine_effettiva DATETIME NULL,
    stato VARCHAR(30) NOT NULL DEFAULT 'da_pianificare',
    esito TEXT NULL,
    note_operatore TEXT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    archiviato_at DATETIME NULL,
    INDEX idx_interventi_pian (azienda_id, pianificato_il),
    FOREIGN KEY (azienda_id) REFERENCES aziende(id),
    FOREIGN KEY (cliente_id) REFERENCES clienti(id),
    FOREIGN KEY (sede_id) REFERENCES sedi(id),
    FOREIGN KEY (contratto_id) REFERENCES contratti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE intervento_operatori (
    intervento_id INT UNSIGNED NOT NULL,
    utente_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (intervento_id, utente_id),
    FOREIGN KEY (intervento_id) REFERENCES interventi(id) ON DELETE CASCADE,
    FOREIGN KEY (utente_id) REFERENCES utenti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE visite_contratto (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, contratto_id INT UNSIGNED NOT NULL,
 numero_visita INT UNSIGNED NOT NULL, pianificata_il DATETIME NULL, stato VARCHAR(30) NOT NULL DEFAULT 'da_pianificare',
 intervento_id INT UNSIGNED NULL, note TEXT NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_visita_numero (contratto_id,numero_visita), FOREIGN KEY (azienda_id) REFERENCES aziende(id),
 FOREIGN KEY (contratto_id) REFERENCES contratti(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE interventi ADD CONSTRAINT fk_interventi_visita FOREIGN KEY (visita_contratto_id) REFERENCES visite_contratto(id);

CREATE TABLE ripianificazioni_visite (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, visita_id INT UNSIGNED NOT NULL,
 data_precedente DATETIME NULL, data_nuova DATETIME NOT NULL, motivo VARCHAR(255) NULL, modificato_da INT UNSIGNED NULL,
 modificato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (azienda_id) REFERENCES aziende(id),
 FOREIGN KEY (visita_id) REFERENCES visite_contratto(id), FOREIGN KEY (modificato_da) REFERENCES utenti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE lotti_magazzino (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, prodotto_id INT UNSIGNED NOT NULL,
 lotto VARCHAR(100) NOT NULL, scadenza DATE NULL, quantita DECIMAL(12,3) NOT NULL DEFAULT 0,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uq_lotto (azienda_id,prodotto_id,lotto),
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE movimenti_magazzino (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, prodotto_id INT UNSIGNED NOT NULL,
 lotto_id INT UNSIGNED NULL, intervento_id INT UNSIGNED NULL, tipo VARCHAR(30) NOT NULL, quantita DECIMAL(12,3) NOT NULL,
 causale VARCHAR(255) NOT NULL, utente_id INT UNSIGNED NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id), FOREIGN KEY (lotto_id) REFERENCES lotti_magazzino(id),
 FOREIGN KEY (intervento_id) REFERENCES interventi(id), FOREIGN KEY (utente_id) REFERENCES utenti(id), INDEX idx_movimenti_prodotto (azienda_id,prodotto_id,creato_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE intervento_prodotti (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, intervento_id INT UNSIGNED NOT NULL,
 prodotto_id INT UNSIGNED NOT NULL, lotto_id INT UNSIGNED NULL, quantita_prevista DECIMAL(12,3) NOT NULL DEFAULT 0,
 quantita_prelevata DECIMAL(12,3) NOT NULL DEFAULT 0, quantita_utilizzata DECIMAL(12,3) NOT NULL DEFAULT 0,
 quantita_restituita DECIMAL(12,3) NOT NULL DEFAULT 0, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id), FOREIGN KEY (prodotto_id) REFERENCES prodotti(id), FOREIGN KEY (lotto_id) REFERENCES lotti_magazzino(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sopralluoghi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL,
 sede_id INT UNSIGNED NULL, richiesta_id INT UNSIGNED NULL, titolo VARCHAR(180) NOT NULL,
 modalita VARCHAR(30) NOT NULL DEFAULT 'presenza', infestante VARCHAR(150) NULL, area_interessata VARCHAR(255) NULL,
 descrizione TEXT NULL, rilevazioni TEXT NULL, trattamenti_consigliati TEXT NULL, prodotti TEXT NULL,
 precauzioni TEXT NULL, costo_stimato DECIMAL(10,2) NULL, pianificato_il DATETIME NULL,
 operatore_id INT UNSIGNED NULL, stato VARCHAR(30) NOT NULL DEFAULT 'da_pianificare', note TEXT NULL,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 archiviato_at DATETIME NULL, FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id),
 FOREIGN KEY (sede_id) REFERENCES sedi(id), FOREIGN KEY (richiesta_id) REFERENCES richieste(id), FOREIGN KEY (operatore_id) REFERENCES utenti(id),
 INDEX idx_sopralluoghi_data (azienda_id,pianificato_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE preventivi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL, sede_id INT UNSIGNED NULL,
 sopralluogo_id INT UNSIGNED NULL, numero VARCHAR(40) NOT NULL, titolo VARCHAR(180) NOT NULL, descrizione TEXT NULL,
 voci TEXT NULL, imponibile DECIMAL(10,2) NOT NULL DEFAULT 0, iva DECIMAL(5,2) NOT NULL DEFAULT 22,
 sconto DECIMAL(10,2) NOT NULL DEFAULT 0, validita_fino DATE NULL, stato VARCHAR(30) NOT NULL DEFAULT 'bozza',
 note TEXT NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 archiviato_at DATETIME NULL, UNIQUE KEY uq_preventivo_numero (azienda_id,numero), FOREIGN KEY (azienda_id) REFERENCES aziende(id),
 FOREIGN KEY (cliente_id) REFERENCES clienti(id), FOREIGN KEY (sede_id) REFERENCES sedi(id), FOREIGN KEY (sopralluogo_id) REFERENCES sopralluoghi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE contratti ADD CONSTRAINT fk_contratti_preventivo FOREIGN KEY (preventivo_id) REFERENCES preventivi(id);

CREATE TABLE eventi_calendario (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, tipo VARCHAR(30) NOT NULL,
 titolo VARCHAR(180) NOT NULL, descrizione TEXT NULL, inizio DATETIME NOT NULL, fine DATETIME NULL,
 intervento_id INT UNSIGNED NULL, sopralluogo_id INT UNSIGNED NULL, contratto_id INT UNSIGNED NULL, visita_contratto_id INT UNSIGNED NULL, assegnato_a INT UNSIGNED NULL,
 creato_da INT UNSIGNED NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, archiviato_at DATETIME NULL,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id),
 FOREIGN KEY (sopralluogo_id) REFERENCES sopralluoghi(id), FOREIGN KEY (contratto_id) REFERENCES contratti(id), FOREIGN KEY (visita_contratto_id) REFERENCES visite_contratto(id), FOREIGN KEY (assegnato_a) REFERENCES utenti(id), FOREIGN KEY (creato_da) REFERENCES utenti(id),
 INDEX idx_eventi_data (azienda_id,inizio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE allegati (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, entita VARCHAR(40) NOT NULL, entita_id INT UNSIGNED NOT NULL,
 nome_originale VARCHAR(255) NOT NULL, percorso VARCHAR(500) NOT NULL, mime VARCHAR(120) NULL, dimensione INT UNSIGNED NOT NULL DEFAULT 0,
 caricato_da INT UNSIGNED NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (caricato_da) REFERENCES utenti(id), INDEX idx_allegati_entita (azienda_id,entita,entita_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE notifiche (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, utente_id INT UNSIGNED NULL,
 titolo VARCHAR(180) NOT NULL, messaggio TEXT NOT NULL, link VARCHAR(255) NULL, letta_il DATETIME NULL,
 creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (utente_id) REFERENCES utenti(id),
 INDEX idx_notifiche_utente (azienda_id,utente_id,letta_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE audit_log (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT UNSIGNED NULL,
    utente_id INT UNSIGNED NULL,
    azione VARCHAR(40) NOT NULL,
    entita VARCHAR(40) NOT NULL,
    entita_id INT UNSIGNED NULL,
    dettagli TEXT NULL,
    ip VARCHAR(45) NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_entita (entita, entita_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

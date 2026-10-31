-- Pest Control Manager - schema MariaDB (Fase 1 / MVP)
SET NAMES utf8mb4;

CREATE TABLE aziende (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ragione_sociale VARCHAR(200) NOT NULL,
    partita_iva VARCHAR(20) NULL,
    email VARCHAR(190) NULL,
    telefono VARCHAR(40) NULL,
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
    FOREIGN KEY (sede_id) REFERENCES sedi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE intervento_operatori (
    intervento_id INT UNSIGNED NOT NULL,
    utente_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (intervento_id, utente_id),
    FOREIGN KEY (intervento_id) REFERENCES interventi(id) ON DELETE CASCADE,
    FOREIGN KEY (utente_id) REFERENCES utenti(id)
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

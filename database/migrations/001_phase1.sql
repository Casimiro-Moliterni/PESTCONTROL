-- Applicare una sola volta su installazioni esistenti della Fase 1.
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS codice_fiscale VARCHAR(20) NULL;
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS pec VARCHAR(190) NULL;
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS indirizzo VARCHAR(255) NULL;
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS citta VARCHAR(100) NULL;
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS cap VARCHAR(10) NULL;
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS provincia VARCHAR(5) NULL;
ALTER TABLE aziende ADD COLUMN IF NOT EXISTS logo_path VARCHAR(500) NULL;

CREATE TABLE IF NOT EXISTS sopralluoghi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL,
 sede_id INT UNSIGNED NULL, richiesta_id INT UNSIGNED NULL, titolo VARCHAR(180) NOT NULL,
 modalita VARCHAR(30) NOT NULL DEFAULT 'presenza', infestante VARCHAR(150) NULL, area_interessata VARCHAR(255) NULL,
 descrizione TEXT NULL, rilevazioni TEXT NULL, trattamenti_consigliati TEXT NULL, prodotti TEXT NULL, precauzioni TEXT NULL,
 costo_stimato DECIMAL(10,2) NULL, pianificato_il DATETIME NULL, operatore_id INT UNSIGNED NULL,
 stato VARCHAR(30) NOT NULL DEFAULT 'da_pianificare', note TEXT NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, archiviato_at DATETIME NULL,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id), FOREIGN KEY (sede_id) REFERENCES sedi(id),
 FOREIGN KEY (richiesta_id) REFERENCES richieste(id), FOREIGN KEY (operatore_id) REFERENCES utenti(id), INDEX idx_sopralluoghi_data (azienda_id,pianificato_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS preventivi (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, cliente_id INT UNSIGNED NOT NULL, sede_id INT UNSIGNED NULL,
 sopralluogo_id INT UNSIGNED NULL, numero VARCHAR(40) NOT NULL, titolo VARCHAR(180) NOT NULL, descrizione TEXT NULL, voci TEXT NULL,
 imponibile DECIMAL(10,2) NOT NULL DEFAULT 0, iva DECIMAL(5,2) NOT NULL DEFAULT 22, sconto DECIMAL(10,2) NOT NULL DEFAULT 0,
 validita_fino DATE NULL, stato VARCHAR(30) NOT NULL DEFAULT 'bozza', note TEXT NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, archiviato_at DATETIME NULL,
 UNIQUE KEY uq_preventivo_numero (azienda_id,numero), FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (cliente_id) REFERENCES clienti(id),
 FOREIGN KEY (sede_id) REFERENCES sedi(id), FOREIGN KEY (sopralluogo_id) REFERENCES sopralluoghi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eventi_calendario (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, tipo VARCHAR(30) NOT NULL, titolo VARCHAR(180) NOT NULL,
 descrizione TEXT NULL, inizio DATETIME NOT NULL, fine DATETIME NULL, intervento_id INT UNSIGNED NULL, sopralluogo_id INT UNSIGNED NULL,
 assegnato_a INT UNSIGNED NULL, creato_da INT UNSIGNED NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, archiviato_at DATETIME NULL,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (intervento_id) REFERENCES interventi(id), FOREIGN KEY (sopralluogo_id) REFERENCES sopralluoghi(id),
 FOREIGN KEY (assegnato_a) REFERENCES utenti(id), FOREIGN KEY (creato_da) REFERENCES utenti(id), INDEX idx_eventi_data (azienda_id,inizio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS allegati (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, entita VARCHAR(40) NOT NULL, entita_id INT UNSIGNED NOT NULL,
 nome_originale VARCHAR(255) NOT NULL, percorso VARCHAR(500) NOT NULL, mime VARCHAR(120) NULL, dimensione INT UNSIGNED NOT NULL DEFAULT 0,
 caricato_da INT UNSIGNED NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (azienda_id) REFERENCES aziende(id),
 FOREIGN KEY (caricato_da) REFERENCES utenti(id), INDEX idx_allegati_entita (azienda_id,entita,entita_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifiche (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, azienda_id INT UNSIGNED NOT NULL, utente_id INT UNSIGNED NULL, titolo VARCHAR(180) NOT NULL,
 messaggio TEXT NOT NULL, link VARCHAR(255) NULL, letta_il DATETIME NULL, creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (azienda_id) REFERENCES aziende(id), FOREIGN KEY (utente_id) REFERENCES utenti(id), INDEX idx_notifiche_utente (azienda_id,utente_id,letta_il)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO ruolo_permessi (ruolo_id,modulo,azione)
SELECT r.id,m.modulo,a.azione FROM ruoli r
CROSS JOIN (SELECT 'sopralluoghi' modulo UNION SELECT 'preventivi' UNION SELECT 'calendario' UNION SELECT 'utenti' UNION SELECT 'azienda' UNION SELECT 'notifiche') m
CROSS JOIN (SELECT 'lettura' azione UNION SELECT 'creazione' UNION SELECT 'modifica' UNION SELECT 'eliminazione') a
WHERE r.nome='Amministratore';
INSERT IGNORE INTO ruolo_permessi (ruolo_id,modulo,azione)
SELECT r.id,m.modulo,a.azione FROM ruoli r
CROSS JOIN (SELECT 'sopralluoghi' modulo UNION SELECT 'preventivi' UNION SELECT 'calendario') m
CROSS JOIN (SELECT 'lettura' azione UNION SELECT 'creazione' UNION SELECT 'modifica') a
WHERE r.nome='Ufficio';
INSERT IGNORE INTO ruolo_permessi (ruolo_id,modulo,azione)
SELECT r.id,'notifiche',a.azione FROM ruoli r CROSS JOIN (SELECT 'lettura' azione UNION SELECT 'modifica') a;

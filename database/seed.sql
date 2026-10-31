-- Dati iniziali: 1 azienda, 3 ruoli, 2 utenti demo (password: Cambiami123!)
INSERT INTO aziende (id, ragione_sociale) VALUES (1, 'Azienda Demo Pest Control');

INSERT INTO ruoli (id, azienda_id, nome, descrizione, solo_assegnati) VALUES
 (1, 1, 'Amministratore', 'Accesso completo', 0),
 (2, 1, 'Ufficio', 'Gestione clienti, richieste e interventi', 0),
 (3, 1, 'Operatore', 'Vede ed esegue solo gli interventi assegnati', 1);

-- Amministratore: tutte le azioni su tutti i moduli
INSERT INTO ruolo_permessi (ruolo_id, modulo, azione)
SELECT 1, m.modulo, a.azione
FROM (SELECT 'clienti' AS modulo UNION SELECT 'richieste' UNION SELECT 'interventi') m
CROSS JOIN (SELECT 'lettura' AS azione UNION SELECT 'creazione' UNION SELECT 'modifica'
            UNION SELECT 'eliminazione' UNION SELECT 'approvazione' UNION SELECT 'esportazione'
            UNION SELECT 'esecuzione') a;

-- Ufficio: tutto tranne eliminazione e approvazione
INSERT INTO ruolo_permessi (ruolo_id, modulo, azione)
SELECT 2, m.modulo, a.azione
FROM (SELECT 'clienti' AS modulo UNION SELECT 'richieste' UNION SELECT 'interventi') m
CROSS JOIN (SELECT 'lettura' AS azione UNION SELECT 'creazione' UNION SELECT 'modifica'
            UNION SELECT 'esportazione') a;

-- Operatore: legge ed esegue interventi
INSERT INTO ruolo_permessi (ruolo_id, modulo, azione) VALUES
 (3, 'interventi', 'lettura'), (3, 'interventi', 'esecuzione');

INSERT INTO ruolo_permessi (ruolo_id, modulo, azione)
SELECT r.id, m.modulo, a.azione FROM ruoli r
CROSS JOIN (SELECT 'sopralluoghi' modulo UNION SELECT 'preventivi' UNION SELECT 'calendario' UNION SELECT 'utenti' UNION SELECT 'azienda') m
CROSS JOIN (SELECT 'lettura' azione UNION SELECT 'creazione' UNION SELECT 'modifica' UNION SELECT 'eliminazione') a WHERE r.id=1;
INSERT INTO ruolo_permessi (ruolo_id, modulo, azione)
SELECT r.id, m.modulo, a.azione FROM ruoli r
CROSS JOIN (SELECT 'sopralluoghi' modulo UNION SELECT 'preventivi' UNION SELECT 'calendario') m
CROSS JOIN (SELECT 'lettura' azione UNION SELECT 'creazione' UNION SELECT 'modifica') a WHERE r.id=2;
INSERT INTO ruolo_permessi (ruolo_id, modulo, azione) VALUES
 (1,'notifiche','lettura'),(1,'notifiche','modifica'),(2,'notifiche','lettura'),(2,'notifiche','modifica'),(3,'notifiche','lettura'),(3,'notifiche','modifica');

INSERT INTO utenti (azienda_id, ruolo_id, nome, cognome, email, password_hash) VALUES
 (1, 1, 'Admin', 'Demo', 'admin@example.com', '$2y$10$iL.bo09OzjCVPF66/LSgwuVGXTyBDDfatcmPW1OdjWyxIscbZAuk.'),
 (1, 3, 'Mario', 'Operatore', 'operatore@example.com', '$2y$10$iL.bo09OzjCVPF66/LSgwuVGXTyBDDfatcmPW1OdjWyxIscbZAuk.');

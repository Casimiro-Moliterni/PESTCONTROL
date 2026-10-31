# Pest Control Manager — Fase 1 (MVP)

PHP 8.2+ (OOP, senza framework) · MariaDB · HTML/CSS, tutto server-side, nessun JavaScript.

## Avvio
    docker compose up --build
    # app:    http://localhost:8080/app/login
    # sito:   http://localhost:8080/

Utenti demo (password `Cambiami123!` — da cambiare):
- admin@example.com (Amministratore)
- operatore@example.com (Operatore: vede ed esegue solo gli interventi assegnati)

Senza Docker: PHP 8.2 con `pdo_mysql` e `mbstring`, document root su `public/`, variabili `DB_HOST/DB_NAME/DB_USER/DB_PASS`, importare `database/schema.sql` e `database/seed.sql`.

## Architettura
    public/index.php      front controller (unico punto d'ingresso)
    config/               config, liste di stati/tipologie, rotte
    src/Core/             App, Router, Context, Request/Response, View, Session, Csrf,
                          Auth (login + permessi), AuditLog, Database (PDO), Validator
    src/Repository/       accesso ai dati; ogni query è filtrata per azienda_id e ignora gli archiviati
    src/Controller/       un controller per modulo, sottili: validano, chiamano i repository, rendono la vista
    templates/            solo presentazione (HTML con escape)
    database/             schema.sql, seed.sql

Regole applicate dal documento: permessi verificati lato server nel Router (ruolo → modulo.azione),
multi-tenant su `azienda_id`, archiviazione al posto della cancellazione, audit log delle operazioni,
stati/tipologie fuori dal codice (`config/stati.php`), CSRF su ogni POST, area pubblica separata da `/app`.

## Come aggiungere un modulo
1. Tabella in `schema.sql` (con `azienda_id` e `archiviato_at`).
2. `XRepository extends Repository` (tabella + colonne `fillable`).
3. `XController extends Controller`.
4. Rotte in `config/routes.php` con il permesso richiesto, template in `templates/x/`.
5. Permessi del modulo in `ruolo_permessi`.

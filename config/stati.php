<?php
declare(strict_types=1);

/**
 * Liste di valori (chiave => etichetta) usate da form e filtri.
 * Centralizzate qui per non disperdere valori rigidi nel codice;
 * in una fase successiva possono diventare tabelle configurabili per azienda.
 */
return [
    'cliente_tipo' => [
        'privato' => 'Privato', 'azienda' => 'Azienda', 'ente_pubblico' => 'Ente pubblico',
        'condominio' => 'Condominio / amministrazione', 'altro' => 'Altro',
    ],
    'cliente_stato' => [
        'prospect' => 'Prospect', 'attivo' => 'Attivo', 'sospeso' => 'Sospeso', 'cessato' => 'Cessato',
    ],
    'richiesta_tipo' => [
        'informazioni' => 'Richiesta di informazioni', 'sopralluogo' => 'Richiesta di sopralluogo',
        'preventivo' => 'Richiesta di preventivo', 'urgente' => 'Intervento urgente',
    ],
    'richiesta_stato' => [
        'nuova' => 'Nuova', 'presa_in_carico' => 'Presa in carico', 'in_lavorazione' => 'In lavorazione',
        'convertita' => 'Convertita', 'chiusa' => 'Rifiutata / chiusa',
    ],
    'servizio_tipo' => [
        'disinfestazione' => 'Disinfestazione', 'derattizzazione' => 'Derattizzazione',
        'deblattizzazione' => 'Deblattizzazione', 'monitoraggio' => 'Monitoraggio',
        'disinfezione' => 'Disinfezione', 'altro' => 'Altro',
    ],
    'intervento_stato' => [
        'da_pianificare' => 'Da pianificare', 'pianificato' => 'Pianificato', 'assegnato' => 'Assegnato',
        'in_corso' => 'In corso', 'completato' => 'Completato', 'da_verificare' => 'Da verificare',
        'annullato' => 'Annullato',
    ],
    // Stati che l'operatore può impostare dalla scheda di esecuzione.
    'intervento_stato_operatore' => [
        'in_corso' => 'In corso', 'completato' => 'Completato', 'da_verificare' => 'Da verificare',
    ],
    'sopralluogo_stato' => ['da_pianificare'=>'Da pianificare','pianificato'=>'Pianificato','in_corso'=>'In corso','completato'=>'Completato','annullato'=>'Annullato'],
    'preventivo_stato' => ['bozza'=>'Bozza','da_inviare'=>'Da inviare','inviato'=>'Inviato','in_attesa'=>'In attesa','accettato'=>'Accettato','rifiutato'=>'Rifiutato','scaduto'=>'Scaduto','annullato'=>'Annullato','archiviato'=>'Archiviato'],
    'calendario_tipo' => ['sopralluogo'=>'Sopralluogo','intervento'=>'Intervento','appuntamento'=>'Appuntamento','scadenza'=>'Scadenza','promemoria'=>'Promemoria'],
    'contratto_stato' => ['bozza'=>'Bozza','da_accettare'=>'Da accettare','attivo'=>'Attivo','sospeso'=>'Sospeso','scaduto'=>'Scaduto','cessato'=>'Cessato'],
    'contratto_frequenza' => ['settimanale'=>'Ogni settimana','quindicinale'=>'Ogni due settimane','mensile'=>'Ogni mese','bimestrale'=>'Ogni due mesi','trimestrale'=>'Ogni tre mesi','semestrale'=>'Ogni sei mesi','annuale'=>'Ogni anno'],
    'visita_stato' => ['da_pianificare'=>'Da pianificare','pianificata'=>'Pianificata','completata'=>'Completata','annullata'=>'Annullata'],
    'dispositivo_stato' => ['attivo'=>'Attivo','da_controllare'=>'Da controllare','fuori_servizio'=>'Fuori servizio','rimosso'=>'Rimosso'],
    'dispositivo_esito' => ['regolare'=>'Regolare','attivita_rilevata'=>'Attività infestante rilevata','esca_consumata'=>'Esca consumata','anomalia'=>'Anomalia','non_accessibile'=>'Non accessibile'],
    'magazzino_movimento' => ['carico'=>'Carico','scarico'=>'Scarico','rettifica'=>'Rettifica'],
];

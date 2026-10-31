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
];

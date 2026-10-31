PEST CONTROL MANAGER
Documento di analisi funzionale 

OBIETTIVO DEL PROGETTO
Realizzare un gestionale/CRM per aziende che operano nel settore del pest control: disinfestazione, derattizzazione, monitoraggio infestanti, disinfezione e servizi correlati.

Il sistema dovrà supportare sia la gestione commerciale e amministrativa sia l'operatività sul campo. Dovrà essere utilizzabile dal titolare e dal personale d'ufficio tramite un'area gestionale, e dagli operatori tramite un'interfaccia adatta anche a smartphone e tablet.

Le funzionalità dovranno essere abilitate in base al ruolo e ai permessi dell'utente. Le eventuali pagine pubbliche (contatti, richieste di preventivo, candidature e blog) saranno separate dall'area riservata.

1. GESTIONE CLIENTI E SEDI

Il sistema dovrà permettere di creare e gestire le anagrafiche dei clienti, conservando i dati di contatto e le informazioni amministrative.

Dati indicativi:
- Nome e cognome oppure ragione sociale.
- Tipologia di cliente: privato, azienda, ente pubblico, condominio/amministrazione condominiale o altro.
- Codice fiscale e/o partita IVA, quando applicabili.
- Email, telefono, PEC e dati di fatturazione.
- Indirizzo e ulteriori informazioni di contatto.
- Stato del cliente, ad esempio prospect, attivo, sospeso o cessato.
- Note e documenti allegati.

È opportuno distinguere lo stato del cliente dalla sua classificazione commerciale. Ad esempio, "VIP" è più una categoria o un'etichetta commerciale che uno stato operativo.

Un cliente potrà avere più sedi o luoghi presso i quali effettuare gli interventi. Esempi:
- Un'azienda con più stabilimenti.
- Un'amministrazione che gestisce diversi condomini.
- Un ente pubblico con più edifici.
- Un privato con più immobili.

Ogni sede dovrà avere un indirizzo, eventuali coordinate geografiche, referenti sul posto, istruzioni di accesso, orari consentiti e informazioni utili per gli operatori.

Il sistema dovrà inoltre gestire i contatti associati al cliente e alle singole sedi. Un amministratore condominiale, ad esempio, potrà essere il referente di più condomini senza che questi vengano confusi con la sua anagrafica personale.

2. GESTIONE DELLE RICHIESTE E DEI POTENZIALI CLIENTI

Le richieste ricevute dall'azienda dovranno essere raccolte in un'unica sezione. Potranno provenire dal sito pubblico, da email, telefono o inserimento manuale.

Esempi di richiesta:
- Richiesta di informazioni.
- Richiesta di sopralluogo.
- Richiesta di preventivo.
- Richiesta di intervento urgente.
- Candidatura di un potenziale dipendente.

Ogni richiesta dovrà avere almeno:
- Tipologia.
- Dati del richiedente.
- Descrizione.
- Data di ricezione.
- Stato (nuova, presa in carico, in lavorazione, convertita, rifiutata/chiusa).
- Utente incaricato.
- Eventuali allegati e comunicazioni.

Una richiesta commerciale potrà essere convertita in un nuovo cliente, in un sopralluogo o in un preventivo, evitando di reinserire manualmente i dati già acquisiti.

Le candidature dovrebbero essere gestite in un flusso separato o in una sezione dedicata, perché contengono dati personali e documenti come i CV.

3. GESTIONE DEI SOPRALLUOGHI

Prima di proporre o pianificare determinati lavori, l'azienda può effettuare un sopralluogo per valutare il luogo, il tipo di infestazione, le attività necessarie, i tempi, le risorse e i costi stimati.

Il sopralluogo potrà essere:
- In presenza, con un incaricato che si reca presso la sede del cliente.
- Da remoto, sulla base delle informazioni e delle fotografie fornite dal cliente.

Il sopralluogo dovrà essere associato a un cliente e, quando disponibile, a una specifica sede. Per richieste non ancora qualificate potrà essere previsto un sopralluogo provvisorio collegato a un richiedente non ancora trasformato in cliente.

Informazioni da raccogliere:
- Cliente, sede e referente.
- Tipologia di sopralluogo e modalità di esecuzione.
- Data, orario e operatore incaricato.
- Tipologia di infestante e area interessata.
- Descrizione del problema e condizioni rilevate.
- Fotografie, documenti e planimetrie.
- Dimensioni o caratteristiche dell'area da trattare.
- Attività e trattamenti consigliati.
- Prodotti, attrezzature e quantità indicative.
- Numero e competenze degli operatori necessari.
- Durata stimata e complessità dell'attività.
- Distanza o tempo di percorrenza dalla sede aziendale, se utile alla stima.
- Eventuali rischi, limitazioni di accesso e precauzioni.
- Frequenza consigliata dei trattamenti.
- Stima dei costi e ulteriori note.

Il sopralluogo dovrà avere uno stato, ad esempio: da pianificare, pianificato, in corso, completato, annullato.

Al completamento del sopralluogo sarà possibile generare un preventivo precompilato con i dati raccolti. Il preventivo dovrà comunque essere modificabile prima dell'invio.

È utile prevedere la duplicazione di un sopralluogo precedente come modello, ma la copia dovrà essere indipendente dall'originale. Dovranno essere copiati solo i dati selezionati: non dovranno essere riutilizzati automaticamente date, fotografie, coordinate, dati sensibili o informazioni specifiche del vecchio cliente.

4. GESTIONE DEI PREVENTIVI

Il preventivo dovrà permettere di presentare al cliente i servizi proposti, i relativi costi, le condizioni e le tempistiche.

Potrà essere creato:
- Da un sopralluogo completato.
- Da una richiesta ricevuta.
- Manualmente, tramite un modulo vuoto.
- Duplicando un preventivo precedente o utilizzando un modello.

Il preventivo dovrà contenere:
- Numero progressivo e data di emissione.
- Cliente, sede e referente.
- Descrizione del problema e del servizio proposto.
- Voci di servizio, quantità, prezzi e importi.
- Eventuali prodotti o materiali inclusi.
- Numero di interventi previsti e relativa frequenza.
- Durata stimata e periodo di validità.
- Condizioni economiche, IVA, eventuali sconti e note.
- Eventuali esclusioni, condizioni operative e allegati.
- Data prevista o periodo indicativo di esecuzione, se già concordato.

Stati suggeriti:
- Bozza.
- Da inviare.
- Inviato.
- In attesa di risposta.
- Accettato.
- Rifiutato.
- Scaduto.
- Annullato.
- Archiviato.

"Confermato" dovrebbe essere sostituito o ricondotto ad "Accettato", per indicare chiaramente l'accettazione da parte del cliente. "In corso" descrive invece più propriamente lo stato dell'intervento o del contratto, non quello del preventivo.

Il sistema dovrà consentire l'invio del preventivo in PDF tramite email e registrare data e modalità di invio. È utile prevedere l'accettazione del preventivo da parte del cliente, con tracciamento della data e della versione accettata.

Quando un preventivo viene modificato dopo l'invio, è opportuno conservare le versioni precedenti, così da poter ricostruire quale versione è stata effettivamente accettata.

5. CONTRATTI E PIANI DI SERVIZIO RICORRENTI

È necessario distinguere il contratto dal singolo intervento.

Un cliente potrà sottoscrivere un contratto che prevede più interventi distribuiti nel tempo. Esempio: un contratto triennale con 36 visite, una al mese.

Il contratto dovrà contenere:
- Cliente e sedi interessate.
- Servizi inclusi.
- Data di inizio e fine.
- Frequenza prevista.
- Numero totale di interventi inclusi.
- Eventuali limiti o attività escluse.
- Prezzo, modalità di pagamento e condizioni.
- Stato del contratto (bozza, da accettare, attivo, sospeso, scaduto, cessato).
- Documenti e allegati.

Il sistema dovrà generare o pianificare le visite previste dal contratto, tenendo conto di festività, disponibilità degli operatori e variazioni concordate con il cliente.

Dovrà essere possibile consultare lo stato di avanzamento, ad esempio: 8 interventi completati su 36, 2 pianificati e 26 ancora da pianificare.

La modifica del calendario non dovrà alterare il contratto originario. Le visite dovranno poter essere riprogrammate mantenendo lo storico delle modifiche.

6. GESTIONE DEGLI INTERVENTI E DELLE VISITE OPERATIVE

L'intervento rappresenta il lavoro da eseguire presso una sede del cliente. Per i servizi ricorrenti è utile distinguere il contratto o piano di servizio dalle singole visite operative.

Il sistema dovrà collegare, quando presenti:
Cliente → Sede → Sopralluogo → Preventivo → Contratto → Intervento/visita.

Non tutti gli interventi dovranno necessariamente seguire l'intero flusso. Potranno esistere interventi urgenti, interventi a chiamata o attività che non richiedono un sopralluogo o un preventivo.

Dati dell'intervento:
- Cliente, sede e referente.
- Tipologia di servizio: disinfestazione, derattizzazione, deblattizzazione, monitoraggio, disinfezione o altro.
- Tipologia di infestante.
- Descrizione e istruzioni operative.
- Data e orario pianificati.
- Data e orario effettivi di inizio e fine.
- Operatori assegnati.
- Veicoli e attrezzature utilizzati.
- Prodotti e quantità effettivamente impiegati.
- Tempo impiegato.
- Fotografie prima/dopo e documenti.
- Coordinate o posizione dell'attività, quando necessarie.
- Esito dell'intervento.
- Eventuali anomalie, criticità o attività da ripetere.
- Firma o conferma del referente del cliente, se prevista.

È preferibile utilizzare modelli operativi o checklist differenti in base al tipo di servizio. Alcune attività possono avere una procedura simile, ma non bisogna assumere che tutte le disinfestazioni siano identiche: prodotti, dosaggi, attrezzature, precauzioni, tempi di rientro e documentazione possono cambiare.

L'operatore dovrà poter consultare gli interventi assegnati e aggiornare la scheda direttamente da smartphone o tablet, anche prevedendo una modalità offline per luoghi con connessione assente o instabile.

Lo stato dell'intervento potrà essere: da pianificare, pianificato, assegnato, in corso, completato, da verificare, annullato.

L'azienda potrà monitorare lo stato operativo. La posizione GPS non dovrà essere considerata da sola una prova certa dell'esecuzione del lavoro.

7. DISPOSITIVI, TRAPPOLE E PUNTI DI MONITORAGGIO

Il sistema dovrà permettere di censire e monitorare dispositivi installati presso le sedi dei clienti, ad esempio:
- Trappole per roditori.
- Erogatori di esca.
- Trappole collanti.
- Lampade UV.
- Dispositivi di monitoraggio insetti.
- Stazioni di monitoraggio e altri dispositivi.

Ogni dispositivo dovrà avere:
- Codice identificativo univoco.
- Codice a barre o QR code.
- Cliente e sede di appartenenza.
- Posizione precisa, descrizione e, se utile, coordinate GPS.
- Tipologia e modello.
- Data di installazione.
- Stato (attivo, da controllare, fuori servizio, rimosso).
- Prodotto o esca utilizzata, se applicabile.
- Data dell'ultimo controllo.
- Data dell'ultimo cambio esca o sostituzione.
- Storico delle verifiche e degli interventi.
- Fotografie e note.

L'operatore potrà scansionare il QR code con il telefono o il tablet per aprire la scheda del dispositivo e registrare un controllo.

La scheda dovrà consentire di registrare:
- Data e ora del controllo.
- Operatore.
- Esito e attività effettuata.
- Quantità e tipo di esca sostituita, se applicabile.
- Consumo o evidenza di attività infestante.
- Fotografie e note.
- Eventuali anomalie o necessità di intervento.

Ogni operazione dovrà essere tracciata. Il titolare potrà configurare un flusso di verifica o approvazione per determinate operazioni, senza rendere obbligatoria l'approvazione manuale di ogni controllo ordinario.

SICUREZZA DEL QR CODE:
Il QR code non dovrà contenere un collegamento che permetta a chiunque di modificare direttamente la scheda. La scansione dovrà identificare il dispositivo, ma per registrare un'operazione sarà necessario autenticarsi e verificare i permessi dell'operatore.

Per ridurre gli aggiornamenti effettuati senza essere fisicamente sul posto si potranno utilizzare controlli aggiuntivi: posizione del dispositivo, posizione del telefono, distanza massima consentita, data e ora, registrazione dell'utente e controlli di anomalia.

La geolocalizzazione non è infallibile: il GPS può avere margini di errore e può essere alterato. Il sistema dovrà quindi prevedere una tolleranza configurabile e una procedura di eccezione per i casi legittimi.

Sarà utile una mappa della sede con la posizione dei dispositivi e filtri come: dispositivi non controllati da più di 30 giorni, controlli in scadenza, dispositivi con consumo di esca o anomalie rilevate.

8. MAGAZZINO E GESTIONE DEI PRODOTTI

Il sistema dovrà permettere di catalogare i prodotti, i materiali di consumo, le esche, le trappole e le attrezzature.

Per ogni prodotto:
- Nome, codice interno e categoria.
- Produttore e fornitore.
- Descrizione.
- Unità di misura.
- Quantità disponibile.
- Scorta minima.
- Costo di acquisto.
- Eventuale prezzo di vendita.
- Lotto e data di scadenza, ove applicabili.
- Scheda tecnica e scheda di sicurezza.
- Informazioni di autorizzazione e utilizzo, ove applicabili.

Il magazzino dovrà registrare carichi, scarichi, rettifiche e trasferimenti. Ogni movimento dovrà indicare data, quantità, causale e utente che lo ha effettuato.

I prodotti e i materiali potranno essere associati agli interventi. È importante distinguere:
- Quantità prevista o prenotata per l'intervento.
- Quantità effettivamente prelevata.
- Quantità effettivamente utilizzata.
- Quantità restituita.

Per i prodotti soggetti a lotto e scadenza sarà utile tracciare quale lotto è stato impiegato in uno specifico intervento. Il sistema potrà generare avvisi per scorte basse e prodotti in scadenza.

L'obiettivo è avere una giacenza attendibile e uno storico dei movimenti, non soltanto verificare eventuali ammanchi.

9. GESTIONE DI VEICOLI E ATTREZZATURE AZIENDALI

Il sistema dovrà permettere di censire veicoli, macchinari e attrezzature utilizzati dall'azienda.

VEICOLI:
- Nome o identificativo.
- Targa, marca e modello.
- Data di immatricolazione.
- Chilometraggio.
- Assicurazione e scadenza.
- Revisione e scadenza.
- Tagliandi e manutenzioni.
- Cambio gomme e altri interventi.
- Costi di gestione.
- Documenti e allegati.
- Stato del veicolo (disponibile, assegnato, in manutenzione, fuori servizio).

MACCHINARI E ATTREZZATURE:
- Nome, categoria, marca e modello.
- Matricola o codice identificativo.
- Data di acquisto.
- Ore di utilizzo, ove applicabili.
- Manutenzioni effettuate e programmate.
- Guasti e riparazioni.
- Costi e documenti.
- Stato e disponibilità.

I macchinari utilizzati frequentemente, ad esempio atomizzatori o altre attrezzature per la disinfestazione, dovranno poter essere assegnati agli interventi.

Il sistema dovrà generare promemoria per scadenze assicurative, revisioni, manutenzioni e controlli periodici. Le scadenze dovranno essere configurabili in base alla tipologia di mezzo o attrezzatura.

10. CALENDARIO E PIANIFICAZIONE

Il calendario dovrà consentire di visualizzare, creare, modificare, annullare e riprogrammare eventi.

Tipologie di evento:
- Sopralluogo.
- Intervento.
- Visita periodica prevista da un contratto.
- Appuntamento con un cliente.
- Scadenza o manutenzione.
- Attività generica o promemoria.

Viste richieste:
- Giorno.
- Settimana.
- Mese.
- Elenco delle attività.

Ogni evento dovrà poter essere assegnato a uno o più operatori e, quando necessario, a un veicolo o a un'attrezzatura.

Il calendario dovrà considerare:
- Disponibilità degli operatori.
- Orari di lavoro e assenze.
- Durata stimata dell'attività.
- Sovrapposizioni.
- Tempi di trasferimento tra sedi, se disponibili.
- Disponibilità di veicoli e attrezzature.

Quando viene confermato un sopralluogo o pianificato un intervento, il relativo evento dovrà comparire automaticamente nel calendario. Gli interventi ricorrenti dovranno generare le relative visite secondo la frequenza prevista dal contratto.

È importante evitare che il calendario diventi l'unica fonte dei dati: l'intervento, il sopralluogo o la manutenzione dovranno rimanere entità autonome. Il calendario ne rappresenterà la pianificazione e la visualizzazione.

Il sistema dovrà inviare notifiche e promemoria agli utenti interessati, con tempi configurabili. Dovrà inoltre segnalare conflitti di pianificazione e modifiche agli appuntamenti.

11. OPERATORI, UTENTI E PERMESSI

Il titolare potrà creare gli utenti e associare a ciascuno un ruolo.

Dati indicativi:
- Nome e cognome.
- Email e telefono.
- Ruolo e mansione.
- Stato dell'account.
- Eventuali competenze o abilitazioni.
- Disponibilità e orari di lavoro.
- Documenti e scadenze formative, ove necessari.

I permessi dovranno essere configurabili per modulo e operazione:
- Lettura.
- Creazione.
- Modifica.
- Eliminazione.
- Approvazione.
- Esportazione o download.

È opportuno prevedere ruoli predefiniti, ad esempio amministratore, responsabile operativo, ufficio, operatore e sola consultazione, lasciando al titolare la possibilità di personalizzarli.

L'operatore dovrà visualizzare principalmente gli interventi assegnati, le informazioni necessarie per eseguirli e le schede dei dispositivi da controllare.

Il sistema dovrà registrare le operazioni rilevanti effettuate dagli utenti, soprattutto modifiche a interventi, prodotti, magazzino, contratti e dati economici.

12. GESTIONE AZIENDA E CONFIGURAZIONE

Il gestionale dovrà contenere una sezione per i dati dell'azienda:
- Ragione sociale.
- Partita IVA e codice fiscale.
- Sede legale e sedi operative.
- Contatti.
- Dati fiscali e di fatturazione.
- Logo e dati da riportare sui documenti.
- Utenti e configurazioni aziendali.

Se il prodotto verrà utilizzato da più aziende, ogni azienda dovrà avere un ambiente dati separato. Gli utenti di un'azienda non dovranno poter consultare i dati di un'altra.

13. REPORT E STATISTICHE

Il sistema dovrà offrire report operativi, commerciali ed economici.

REPORT OPERATIVI:
- Interventi eseguiti, pianificati e annullati.
- Interventi per tipologia, cliente e sede.
- Interventi completati per operatore.
- Tempi stimati e tempi effettivi.
- Dispositivi controllati e controlli in ritardo.
- Consumo di prodotti e materiali.
- Manutenzioni e disponibilità dei mezzi.

REPORT COMMERCIALI:
- Richieste ricevute.
- Preventivi emessi, accettati e rifiutati.
- Tasso di conversione da richiesta a cliente e da preventivo a contratto.
- Clienti attivi e inattivi.
- Contratti in scadenza.

REPORT ECONOMICI:
- Ricavi fatturati e incassi effettivi.
- Importi ancora da incassare.
- Ricavi per cliente, sede, servizio e periodo.
- Costi di materiali, mezzi e personale, se registrati.
- Marginalità stimata per intervento o contratto.

È importante distinguere il valore di un preventivo, il valore di un intervento, l'importo fatturato e l'importo effettivamente incassato. Non sono dati equivalenti.

14. FATTURAZIONE E PAGAMENTI

La sezione dovrà permettere di gestire o integrare il processo di fatturazione.

Funzionalità da valutare:
- Dati di fatturazione del cliente.
- Generazione o collegamento delle fatture.
- Fatturazione di interventi singoli o contratti periodici.
- Scadenze e condizioni di pagamento.
- Registrazione degli incassi e dei pagamenti parziali.
- Stato dei documenti (da fatturare, fatturato, pagato, scaduto).
- Esportazione o integrazione con un software di fatturazione elettronica.

La gestione fiscale completa può essere demandata a un servizio esterno, almeno nella prima versione.

15. DOCUMENTI, RELAZIONI TECNICHE E CERTIFICAZIONI

Per ogni cliente, sede, sopralluogo, contratto e intervento dovrà essere possibile archiviare documenti e fotografie.

Il sistema dovrà poter generare documenti PDF, ad esempio:
- Scheda sopralluogo.
- Preventivo.
- Ordine o conferma del servizio.
- Rapporto di intervento.
- Registro dei controlli dei dispositivi.
- Relazione tecnica.
- Documentazione relativa ai prodotti utilizzati.
- Attestazione o certificato di servizio, quando previsto.

Per i servizi destinati ad aziende alimentari o soggette a procedure HACCP sarà utile gestire report di monitoraggio infestanti, planimetrie con i punti di controllo, evidenze, anomalie, azioni correttive e storico delle verifiche.

La documentazione dovrà essere personalizzabile con logo e dati aziendali. Dovrà essere possibile inviarla al cliente e conservarne lo storico.

16. AREA PUBBLICA: CONTATTI E RICHIESTE

Il sito pubblico dovrà offrire un modulo per inviare richieste all'azienda.

Campi indicativi:
- Nome e cognome.
- Azienda, se presente.
- Email e telefono.
- Indirizzo o località dell'intervento.
- Tipologia di servizio richiesto.
- Descrizione del problema.
- Fotografie o allegati.
- Preferenza di contatto.

La richiesta dovrà essere registrata nel gestionale e resa visibile agli utenti autorizzati.

Il modulo dovrà includere adeguate misure antispam e le informazioni privacy necessarie. I dati non dovranno essere pubblicati automaticamente.

17. AREA PUBBLICA: BLOG

Il sistema potrà includere un blog aziendale per pubblicare articoli informativi sui servizi, sulle infestazioni e sulle attività svolte.

Funzionalità:
- Creazione e modifica degli articoli.
- Categorie e tag.
- Immagine di copertina.
- Stato (bozza, pubblicato, archiviato).
- Data di pubblicazione.
- SEO di base.

I commenti pubblici possono essere valutati in una fase successiva: richiedono moderazione, protezione dallo spam e gestione dei contenuti inviati dagli utenti.

18. AREA PUBBLICA: CANDIDATURE

Il sito potrà offrire una sezione "Lavora con noi" con un modulo per raccogliere candidature.

Campi indicativi:
- Nome e cognome.
- Email e telefono.
- Ruolo o posizione desiderata.
- Messaggio.
- Curriculum vitae allegato.

Le candidature dovranno essere accessibili solo agli utenti autorizzati e gestite secondo regole specifiche di conservazione e protezione dei dati personali.

19. GEOLOCALIZZAZIONE E SUPPORTO AGLI OPERATORI

La geolocalizzazione potrà supportare la pianificazione degli interventi e la navigazione tra i luoghi di lavoro.

Funzionalità possibili:
- Visualizzazione su mappa delle sedi dei clienti.
- Visualizzazione dei dispositivi installati.
- Navigazione verso la sede o il dispositivo.
- Registrazione della posizione durante specifiche attività operative, se necessaria.
- Calcolo indicativo delle distanze e dei tempi di percorrenza.
- Associazione dei rifornimenti ai veicoli e registrazione dei chilometri.
- Segnalazione di anomalie o attività non pianificate, da sottoporre a verifica.

Per gli interventi con molti dispositivi, l'applicazione potrà proporre all'operatore un elenco ordinato dei punti da controllare. Sarà possibile filtrare, ad esempio, i dispositivi non verificati da un determinato periodo o quelli che presentano anomalie.

L'avvio e la conclusione automatica di un intervento sulla base del GPS potranno essere valutati come supporto, ma non dovranno essere considerati una prova certa. È preferibile permettere all'operatore di confermare l'inizio e la fine, registrando eventualmente la posizione come dato aggiuntivo.

ATTENZIONE: il tracciamento continuativo dei lavoratori o dei veicoli aziendali può comportare obblighi in materia di privacy e controlli a distanza dei lavoratori. Prima di implementare questa funzione occorre definire finalità, necessità, proporzionalità, informative, tempi di conservazione e verificare gli adempimenti previsti dalla normativa applicabile. Il sistema non dovrà essere progettato come strumento di sorveglianza indiscriminata.

20. NOTIFICHE E AUTOMAZIONI

Il sistema dovrà prevedere notifiche interne e, quando opportuno, email.

Esempi:
- Nuova richiesta dal sito.
- Nuovo sopralluogo assegnato.
- Preventivo in attesa di risposta.
- Preventivo accettato.
- Intervento in programma.
- Intervento non completato.
- Controllo di un dispositivo in scadenza.
- Scorta di magazzino sotto la soglia minima.
- Prodotto prossimo alla scadenza.
- Manutenzione o revisione in scadenza.
- Contratto prossimo alla scadenza.

Le automazioni dovranno essere configurabili e dovranno evitare notifiche duplicate o eccessive. Ogni notifica importante dovrà poter essere consultata nello storico.

21. NOTE ARCHITETTURALI E REGOLE GENERALI

- Separare l'anagrafica del cliente dalle sedi presso cui vengono svolti i servizi.
- Distinguere preventivo, contratto, intervento e singola visita operativa.
- Non rendere obbligatorio il passaggio da sopralluogo e preventivo per ogni intervento.
- Conservare lo storico delle modifiche e delle operazioni rilevanti.
- Gestire ruoli e permessi lato server: nascondere un pulsante nell'interfaccia non è sufficiente per proteggere un'operazione.
- Prevedere allegati e fotografie con permessi di accesso adeguati.
- Evitare cancellazioni definitive dei dati operativi quando è necessario conservarne lo storico; valutare archiviazione o annullamento.
- Prevedere esportazioni e backup.
- Rendere configurabili stati, categorie, frequenze e modelli operativi, evitando valori rigidi nel codice quando devono variare da azienda ad azienda.
- Prevedere un'interfaccia mobile per gli operatori sul campo.
- Registrare data, ora e utente delle operazioni importanti.
- Se il gestionale sarà venduto a più aziende, progettare fin dall'inizio la separazione dei dati tra aziende (multi-tenant).
- Prevedere permessi e visibilità differenti per titolari, ufficio, responsabili e operatori.

22. PROPOSTA DI PRIORITÀ PER LO SVILUPPO

FASE 1 – MVP (prima versione utilizzabile)
- Azienda e utenti.
- Ruoli e permessi.
- Clienti, contatti e sedi.
- Richieste.
- Sopralluoghi.
- Preventivi.
- Interventi e assegnazione operatori.
- Calendario.
- Rapporti di intervento e allegati.
- Notifiche essenziali.

FASE 2 – OPERATIVITÀ SUL CAMPO
- Contratti e interventi ricorrenti.
- Dispositivi, trappole e QR code.
- Mappa delle sedi e dei dispositivi.
- Applicazione/interfaccia mobile per operatori.
- Magazzino e tracciamento dei prodotti.
- Documenti e report tecnici.

FASE 3 – GESTIONE AZIENDALE AVANZATA
- Veicoli e attrezzature.
- Manutenzioni e scadenze.
- Report economici e marginalità.
- Fatturazione e integrazioni.
- Geolocalizzazione avanzata, nel rispetto degli obblighi applicabili.
- Blog, candidature e funzionalità avanzate del sito pubblico.

La suddivisione è indicativa: prima di fissare le priorità definitive sarà utile confrontarsi con una o più aziende del settore e verificare quali processi utilizzano quotidianamente.

FINE DOCUMENTO

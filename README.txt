GCdb / GameCenter database
=======================================
Autore: Giuseppe di Fazio
Repository GitHub: https://github.com/Shiroyasha1501/GameCenter-database-2.0




INDICE
------

1. Presentazione del progetto
2. Struttura del README.txt
3. Tecnologie utilizzate
4. Struttura generale del progetto e delle cartelle
5. Componenti del progetto
6. Installazione del database e accesso al sito dopo l'installazione
7. Credenziali di accesso dimostrative
8. Evoluzione rispetto al primo homework
9. Easter egg e percorso nascosto
10. Sviluppo del progetto: difficoltà, evoluzione e idee scartate




1. PRESENTAZIONE DEL PROGETTO
-----------------------------

GCdb, abbreviazione di "GameCenter database", è un sito web dinamico dedicato
alla consultazione e alla gestione di un piccolo archivio videoludico, attraverso
il quale l'utente può consultare giochi, generi e aziende, registrarsi, accedere
alla propria area personale, aggiungere giochi alla propria galleria e utilizzare
altre sezioni collegate al tema del progetto.
Il sito nasce come evoluzione del precedente homework XHTML/CSS: la struttura
generale e l'identità grafica del primo lavoro sono state mantenute come punto
di partenza, ma il progetto è stato ampliato con PHP e MySQL, così da
avere sezioni dinamiche che recuperano, elaborano e mostrano dati provenienti
da un database.
L'idea del progetto nasce da un'abitudine personale: tenere traccia dei
contenuti visti o seguiti nel tempo. Per film e serie televisive esistono
strumenti molto diffusi, come TV Time o IMDb, che permettono di ricordare cosa
si è visto, a che punto si è arrivati, quali contenuti sono stati completati e
quali invece restano ancora da recuperare.
Partendo da questa osservazione, mi sono chiesto perché non applicare una logica
simile anche ai videogiochi. Spesso, infatti, chi gioca molto finisce per non
ricordare con precisione quali titoli ha già giocato, quali ha lasciato in
sospeso, quali vorrebbe recuperare o quali gli sono piaciuti di più;
esistono strumenti che provano a svolgere queste funzioni, ma non
sempre con l'ordine e la chiarezza a cui GCdb aspira.
Il progetto vuole quindi rispondere a un'esigenza effettivamente presente e non
puramente teorica: costruire una piccola piattaforma ordinata, chiara e
navigabile, pensata per gestire i videogiochi in modo simile a come alcune
applicazioni gestiscono film e serie TV.


2. STRUTTURA DEL README.TXT
---------------------------

Questo README ha una struttura volutamente più lunga, schematica e dettagliata
rispetto al README del primo homework perché il
progetto non è più composto soltanto da pagine statiche XHTML/CSS, ma include
PHP, MySQL, sessioni, file dati modulari e altro ancora:
un documento troppo breve o soltanto discorsivo non sarebbe stato sufficiente
per spiegare in modo chiaro l'organizzazione del lavoro.
La struttura del README segue quindi l'evoluzione del progetto, il tutto
scandito dalla presenza di un indice iniziale: prima viene
presentata l'idea generale di GCdb, poi vengono indicate le tecnologie usate,
l'organizzazione delle cartelle, i principali componenti del sito, la procedura
di installazione, i vari utenti, le differenze rispetto al primo homework, l'easter egg e,
infine, le difficoltà incontrate durante lo sviluppo.
Questa impostazione è utile anche perché il progetto contiene parti diverse tra
loro, con alcune pagine che fanno parte della normale navigazione del sito
e altre hanno funzioni più tecniche.

La scelta di costruire un README così strutturato nasce anche da un piccolo
aneddoto personale. Durante un lavoro completamente separato dal progetto, mi
sono trovato a cercare informazioni su Ape Escape per PlayStation 1, in
particolare sulla possibilità di eseguirlo correttamente su PlayStation 3. Da
quella ricerca è nato un vero e proprio percorso tra forum, guide, patch e
strumenti dedicati alla gestione di alcune protezioni presenti in determinati
giochi PS1, che potevano includere sistemi di protezione aggiuntivi, come LibCrypt o
controlli anti-modchip. In alcuni casi, se queste protezioni non venivano
gestite correttamente, il gioco poteva non funzionare come previsto in contesti
diversi dalla console originale o in presenza di immagini disco modificate.
Ape Escape rientrava, sfortunatamente, in questi casi.
Durante questo percorso ho trovato "LibCrypt Patcher", un progetto pubblicato su
GitHub dal "salvatore" Alex Free, il cui scopo è di analizzare
l'immagine disco di un gioco PS1 protetto e applicare automaticamente le patch
necessarie per gestire la protezione, usando dati già memorizzati nel programma.
La cosa che mi ha colpito non è stata solo l'utilità dello
strumento, ma anche il modo in cui era documentato: il README spiegava il problema,
il contesto, i requisiti, il funzionamento e i passaggi da seguire;
un processo che, senza uno strumento del genere, avrebbe richiesto molte
ricerche e tentativi manuali veniva invece reso molto più semplice e veloce.
La citazione ad Alex Free e a LibCrypt Patcher è quindi un piccolo omaggio a un
progetto che ha reso più accessibile un problema tecnico non immediato. Allo
stesso modo, questo README prova a rendere GCdb più facile da leggere, usare,
installare e spiegare, accompagnando chi apre il progetto nella lettura
della lista dei file e nella comprensione delle scelte che hanno portato alla sua struttura
finale.


3. TECNOLOGIE UTILIZZATE
------------------------

Le tecnologie principali utilizzate sono:

- XHTML 1.0 Strict;
- CSS esterno;
- PHP;
- MySQL;
- sessioni PHP;
- cookie PHP;
- XAMPP come ambiente locale;
- Apache come web server locale;
- MySQL come database server.


4. STRUTTURA GENERALE DEL PROGETTO E DELLE CARTELLE
---------------------------------------------------

Il progetto è organizzato in modo da separare il più possibile le varie
responsabilità del sito per evitare che tutte le parti del codice siano
concentrate dentro un unico file o ripetute manualmente in ogni pagina, rendendo
il lavoro più leggibile, più semplice da modificare e più facile da spiegare.
Le pagine principali, come "index.php", "giochi.php" e "galleria.php", rappresentano le sezioni
effettivamente visitabili dall'utente e contengono la logica specifica della
sezione: per esempio, alcune pagine gestiscono la ricerca dei giochi, altre la
visualizzazione dei generi, altre ancora la consultazione delle aziende.
Accanto alle pagine principali sono presenti file inclusi tramite require_once,
provenienti dalla cartella "includes", perché rappresentano
parti comuni del sito oppure funzioni di supporto: 
se si vuole modificare una parte comune del sito, è sufficiente intervenire su un solo file.
Un'altra parte importante del progetto è la cartella "dati", dove sono raccolti
i file PHP che contengono i dati iniziali usati durante l'installazione del
database, il tutto fatto per mantenere "install.php" più ordinato,
separando la creazione delle tabelle dai dati dimostrativi da inserire.
La cartella "file", infine, contiene invece le risorse multimediali del sito
ed è divisa internamente in sezioni, in modo da distinguere le copertine dei giochi,
i loghi delle aziende e gli altri file extra del progetto.

diFazio.Giuseppe.PHP_MySQL/
|
|-- index.php
|-- giochi.php
|-- generi.php
|-- aziende.php
|-- utenti.php
|-- galleria.php
|-- galleria_utente.php
|-- glossario.php
|-- info.php
|-- accesso.php
|-- registrazione.php
|-- logout.php
|-- install.php
|-- minigioco.php
|-- terminale_accesso.php
|-- rivelazione_terminale.php
|-- accesso-non-autorizzato.php
|
|-- style.css
|-- style_chiaro.css
|-- README.txt
|
|-- includes/
|   |-- connection.php
|   |-- dati_generali.php
|   |-- dati_speciali.php
|   |-- header.php
|   |-- footer.php
|   |-- menu.php
|   |-- tema.php
|   |-- tema_footer.php
|   |-- stili_tema.php
|
|-- dati/
|   |-- utenti_dati.php
|   |-- generi_dati.php
|   |-- giochi_dati.php
|   |-- gioco_genere_dati.php
|   |-- aziende_dati.php
|   |-- gioco_azienda_dati.php
|   |-- gallerie_dati.php
|   |-- generi_preferiti_dati.php
|   |-- minigioco_dati.php
|   |-- glossario_dati.php
|
|-- file/
|   |-- giochi/
|   |-- aziende/
|   |-- altro/


5. COMPONENTI DEL PROGETTO
--------------------------

index.php
    Homepage del progetto: presenta GCdb, introduce l'idea generale del sito e
    mostra alcuni contenuti dinamici collegati al database.

giochi.php
    Pagina del catalogo giochi, che permette di consultare i videogiochi presenti
    nel database, cercarli, ordinarli e visualizzare informazioni come PEGI,
    generi e aziende collegate. Gli utenti loggati, inoltre, possono aggiungere i giochi
    alla propria galleria personale già da qui.

generi.php
    Pagina dedicata ai generi videoludici, che mostra generi e sottogeneri presenti
    nel database, permettendo anche agli utenti loggati di aggiungerli o
    rimuoverli dai propri generi preferiti.

aziende.php
    Pagina dedicata alle aziende: mostra studi di sviluppo e publisher,
    collegandoli ai giochi presenti nel catalogo e al ruolo svolto.

utenti.php
    Pagina per la consultazione degli utenti registrati, che permette di cercare
    utenti, vedere alcune informazioni sulla loro galleria e accedere alla
    galleria pubblica corrispondente.

galleria.php
    Pagina personale dell'utente loggato: permette di gestire la propria
    galleria di giochi, inserire voti, scrivere recensioni e visualizzare i
    propri generi preferiti.

galleria_utente.php
    Pagina che mostra in sola lettura la galleria pubblica di un utente. Se
    l'utente selezionato coincide con quello attualmente loggato, viene
    privilegiata la galleria personale modificabile.

glossario.php
    Pagina enciclopedica del progetto che contiene termini videoludici e brevi
    spiegazioni, organizzati come sezione di consultazione. Il glossario è
    mantenuto in un file dati separato e non viene inserito nel database.

minigioco.php
    Sezione extra del sito. Contiene un quiz basato su frasi celebri di
    personaggi videoludici ed è accessibile solo tramite la modalità speciale.

info.php
    Pagina informativa sul progetto e sull'autore: spiega alcune scelte
    generali e contiene informazioni di contatto.

accesso.php
    Pagina di login che permette agli utenti registrati di accedere alla propria
    area personale e contiene anche l'accesso speciale al terminale nascosto.

registrazione.php
    Pagina di registrazione che permette di creare un nuovo utente da utilizzare
    per accedere al sito e gestire una galleria personale.

logout.php
    Pagina che termina la sessione corrente, usata sia dagli utenti
    normali sia dalla modalità speciale.

install.php
    Pagina di installazione del progetto. Crea il database, le tabelle e
    inserisce i dati iniziali usando i file esterni della cartella dati.

terminale_accesso.php
    Pagina legata all'easter egg del progetto. Gestisce l'accesso alla modalità
    speciale tramite credenziali dedicate e richiede l'inserimento di un nome
    simbolico.

accesso-non-autorizzato.php
    Schermata in stile terminale collegata all'easter egg del sito. Richiede un
    codice nascosto e, se corretto, abilita temporaneamente l'accesso alla
    pagina rivelazione_terminale.php.

rivelazione_terminale.php
    Pagina finale dell'easter egg. Mostra le credenziali del terminale segreto,
    consuma subito il permesso di accesso e reindirizza al logout per permettere
    un nuovo accesso tramite le credenziali speciali.

style.css
    Foglio di stile principale che definisce il tema scuro, la struttura delle
    pagine, il menu, le card, i bottoni, i pannelli, il terminale e gli elementi
    grafici generali.

style_chiaro.css
    Foglio di stile del tema chiaro. Funziona come override del tema principale
    e modifica colori, contrasti e sfondi per ottenere una versione chiara del
    sito senza duplicare tutto il CSS.

includes/connection.php
    File che stabilisce la connessione al database MySQL usando i parametri
    definiti nel file di configurazione.

includes/dati_generali.php
    File che contiene i dati generali di connessione, come host, utente,
    password, porta e nome del database.

includes/menu.php
    File che contiene il menu laterale comune del sito. Il menu cambia in base
    allo stato dell'utente e include anche il menu a scomparsa dei cataloghi.

includes/header.php
    File che contiene l'header comune delle pagine principali. Riceve il
    sottotitolo della pagina tramite una variabile impostata prima
    dell'inclusione.

includes/footer.php
    File che contiene il footer comune delle pagine principali. Include anche il
    collegamento per tornare in alto e il controllo del tema.

includes/tema.php
    File che gestisce il cambio tema tramite cookie e mantiene la pagina
    corrente quando l'utente passa dal tema scuro al tema chiaro o viceversa.

includes/tema_footer.php
    File incluso nel footer. Mostra il tema attuale e il link per cambiarlo.

includes/stili_tema.php
    File che carica style.css e, quando necessario, anche style_chiaro.css.

includes/dati_speciali.php
    File di supporto per alcune parti speciali del progetto, in particolare
    legate al terminale e alla modalità nascosta.

dati/utenti_dati.php
    File contenente gli utenti iniziali usati durante l'installazione del
    database.

dati/giochi_dati.php
    File contenente i dati iniziali dei giochi usati durante l'installazione
    del database.

dati/generi_dati.php
    File contenente i dati iniziali dei generi e sottogeneri.

dati/gioco_genere_dati.php
    File contenente le associazioni iniziali tra giochi e generi.

dati/aziende_dati.php
    File contenente i dati iniziali delle aziende collegate ai giochi.

dati/gioco_azienda_dati.php
    File contenente le associazioni iniziali tra giochi e aziende, con il ruolo
    svolto dall'azienda.

dati/gallerie_dati.php
    File contenente le gallerie iniziali degli utenti, con giochi, voti e
    recensioni.

dati/generi_preferiti_dati.php
    File contenente i generi preferiti iniziali di alcuni utenti.

dati/glossario_dati.php
    File contenente i termini e le definizioni usate nella pagina glossario.

dati/minigioco_dati.php
    File contenente le domande e le risposte del minigioco.

file/giochi/
    Cartella che contiene le copertine dei videogiochi.

file/aziende/
    Cartella che contiene i loghi delle aziende.

file/altro/
    Cartella che contiene immagini, audio e risorse extra usate dal progetto.


6. INSTALLAZIONE DEL DATABASE E ACCESSO AL SITO DOPO L'INSTALLAZIONE
--------------------------------------------------------------------

L'installazione avviene tramite il file "install.php", che ha il compito di
preparare il database necessario al funzionamento del sito, occupandosi di:
- connettersi al server MySQL;
- creare il database, se non esiste;
- selezionare il database appena creato;
- creare le tabelle necessarie;
- inserire i dati iniziali;
- preparare il sito all'utilizzo.

Le tabelle create sono:
- utenti;
- pegi;
- giochi;
- generi;
- gioco_genere;
- aziende;
- gioco_azienda;
- galleria;
- generi_preferiti;
- domande_minigioco;
- opzioni_minigioco.

Una volta completata l'installazione, la pagina mostra un riepilogo
dell'operazione e permette di raggiungere direttamente "index.php", cioè la home
del sito, non rendendo necessario digitare manualmente l'indirizzo della
home nella barra del browser.
Se si prova ad aprire nuovamente "install.php" dopo l'installazione, il file non
ricrea da zero inutilmente ciò che è già presente, perché usa istruzioni come
"CREATE DATABASE IF NOT EXISTS", "CREATE TABLE IF NOT EXISTS" e "INSERT IGNORE",
riducendo il rischio di duplicare i dati iniziali.

(IMPORTANTE - CONFIGURAZIONE PORTA MYSQL:
Il progetto è configurato di default per utilizzare la porta 3307 (configurazione 
comune per installazioni MySQL specifiche o MariaDB su alcuni sistemi). 

Se il tuo ambiente locale (XAMPP, MAMP, WAMP) utilizza la porta standard 3306, 
il sito restituirà un errore di connessione. In tal caso:
1. Apri il file 'includes/dati_generali.php'.
2. Modifica la riga $porta = 3307; in $porta = 3306;
3. Salva e ricarica la pagina)


7. CREDENZIALI DI ACCESSO DIMOSTRATIVE
--------------------------------------

Durante l'installazione vengono creati alcuni utenti dimostrativi, utili per
provare subito le funzioni del sito senza dover registrare manualmente nuovi
profili. 

Le credenziali principali sono:

Username: giuseppe
Password: homework2

Username: ProfessorMarcoTemperini
Password: LpW

L'utente "giuseppe" è pensato come profilo principale di prova, con una galleria
già popolata e generi preferiti. L'utente "ProfessorMarcoTemperini", invece, è
stato inserito come profilo vuoto, così da permettere una prova più pulita del
sito partendo da un utente senza galleria iniziale e senza generi preferiti.

Gli altri utenti dimostrativi sono:

Username: lelouch
Password: geass

Username: tarnished
Password: elden

Username: zagreo
Password: hades

Username: idraulicoRosso
Password: mario

Username: viandanteDiHyrule
Password: zelda

Username: scalatoreCeleste
Password: madeline

Username: bossFinale
Password: finale

Username: collezionistaPixel
Password: pixel

Username: rageQuitter
Password: rage

Username: esploratoreOpenWorld
Password: openworld

È comunque possibile creare un nuovo utente tramite la pagina
"registrazione.php". Gli utenti creati manualmente possono poi accedere al sito,
aggiungere giochi alla propria galleria, indicare generi preferiti e usare le
funzioni previste per gli utenti registrati.


8. EVOLUZIONE RISPETTO AL PRIMO HOMEWORK
----------------------------------------

Il primo lavoro era un sito statico: le pagine erano scritte manualmente, i
contenuti erano fissi e non era presente alcuna interazione reale con un
database o con un sistema di utenti.
Con questa seconda versione il progetto è stato trasformato in un sito dinamico
basato su PHP e MySQL, dove la struttura grafica e l'identità visiva del primo
homework sono state mantenute come punto di partenza, ma ampliate
in modo significativo, sia dal punto di vista tecnico sia dal punto di
vista funzionale.

Il cambiamento principale, come da consegna, riguarda l'introduzione del database: nel primo
homework i contenuti erano inseriti direttamente nelle pagine; in questa
versione, invece, giochi, generi, aziende, utenti, gallerie, generi preferiti e
domande del minigioco sono gestiti tramite tabelle MySQL, con le pagine PHP
che interrogano il database e generano dinamicamente i contenuti da mostrare.

Un altro cambiamento importante riguarda la gestione degli utenti che,
nel primo homework, non era presente, mentre in questa versione sì, con
l'utente che può registrarsi, effettuare il login, accedere alla propria
area personale e uscire tramite logout. Le sessioni PHP permettono inoltre di
distinguere gli utenti non loggati dagli utenti autenticati e dalla modalità
speciale legata all'easter egg.

È stata inoltre introdotta la galleria personale, dove ogni utente può
aggiungere generi preferiti e giochi, assegnare un voto e scrivere una recensione,
rendendo il sito più vicino all'idea iniziale del progetto di creare uno
strumento per tenere traccia dei videogiochi consultati, giocati o apprezzati.
Accanto alla galleria personale è stata aggiunta anche la possibilità di
consultare le gallerie pubbliche degli altri utenti e di vedere un riepilogo della
loro attività, come il numero di giochi salvati e il voto medio. La galleria
pubblica di un utente viene mostrata in sola lettura, così da distinguere
chiaramente ciò che può essere modificato dal proprietario e ciò che può essere
soltanto consultato dagli altri.

Un'altra evoluzione riguarda il glossario. Nel primo homework il glossario era
una sezione statica; in questa versione è stato mantenuto come sezione
enciclopedica separata dal database, perché contiene soprattutto contenuti
testuali e descrittivi, non dati applicativi centrali come giochi, utenti o
gallerie.

Dal punto di vista dell'organizzazione del codice, il progetto è stato
modularizzato, con elementi come header, menu, footer, gestione
del tema e connessione al database che sono stati spostati nella cartella "includes"
e riutilizzati tramite require_once, il tutto per rendere il progetto più
ordinato, più semplice da mantenere e più facile da spiegare.
Il sito è stato ampliato anche graficamente: il tema scuro del primo homework è
stato mantenuto come base, ma è stato rifinito con card, pannelli, bottoni,
menu laterale, effetti hover e una disposizione più ordinata dei contenuti.
Inoltre è stato aggiunto un tema chiaro, realizzato tramite il file
"style_chiaro.css", che funziona come override del foglio di stile principale,
e una media query di base. Il primo sito era pensato soprattutto per una
visualizzazione desktop, mentre in questa versione è stato introdotto un primo
adattamento per schermi più piccoli: sotto una certa larghezza il menu laterale
non rimane più affiancato al contenuto, ma viene disposto sopra la sezione
principale. È stato inoltre aggiunto il meta viewport nelle pagine principali, così che i
browser mobili interpretino correttamente la larghezza reale dello schermo.
Anche le card, la barra di ricerca e alcuni pannelli vengono portati
a larghezza piena, così da rendere il sito più leggibile su tablet, smartphone
o finestre del browser ridotte: Non riscrittura completa del layout in ottica mobile-first,
ma coerente con l'impostazione originale del progetto.

Infine, l'easter egg del primo homework è stato ampliato: invece di limitarsi a
un semplice elemento nascosto, il progetto contiene ora un piccolo percorso
segreto composto da più passaggi.
Questa scelta viene approfondita nella sezione successiva, poiché merita una 
trattazione tecnica "singolare".


9. EASTER EGG E PERCORSO NASCOSTO
---------------------------------
(Questa sezione contiene spoiler sul percorso nascosto del progetto)

Un discorso a parte riguarda l'easter egg. Già nel primo homework era presente
l'idea di inserire un contenuto nascosto, non immediatamente visibile all'utente,
partendo dalla pagina "info.php" e dall'area dedicata all'autore. In quel caso,
però, il sito era statico e quindi le possibilità erano limitate: l'easter egg
poteva essere soprattutto un collegamento nascosto o una piccola sorpresa
grafica.

In questa seconda versione, grazie all'uso di PHP e delle sessioni, l'easter egg
è stato trasformato in un vero percorso nascosto composto da più passaggi. La
scelta di inserire questo contenuto nasce dal desiderio di citare alcuni lavori
precedenti svolti durante il corso di "Tecniche della programmazione", quasi
come la chiusura di un cerchio.
Negli homework in C erano stati usati alcuni nomi utente particolari e alcune
frasi che, in questo progetto, vengono riprese come citazione interna. Poiché
anche GCdb nasce come homework universitario e mantiene un tema collegato
al mondo videoludico, ho scelto di inserire un piccolo riferimento a
quei lavori precedenti: non una funzione necessaria al normale utilizzo 
del sito, ma un contenuto extra che mostra 
anche alcune possibilità offerte da PHP, dalle sessioni e dai reindirizzamenti.
Per rendere più chiaro il collegamento con i vecchi homework di "Tecniche della
programmazione", tra le risorse extra del progetto è stato inserito anche uno
screenshot dell'applicazione in C a cui l'easter egg fa riferimento, come
testimonianza del collegamento tra GCdb e quei progetti in C. 

Il percorso per raggiungere l'easter egg è volutamente nascosto e non viene
presentato come una voce normale del menu. Il primo passaggio parte dalla pagina
"info.php", cioè la pagina dedicata alle informazioni sul progetto e sull'autore.
All'interno di questa pagina occorre cliccare l'immagine dell'autore che, in
realtà, porta alla pagina "accesso-non-autorizzato.php".
La pagina "accesso-non-autorizzato.php" simula una schermata di terminale
per dare l'impressione di essere usciti dal sito
normale e di essere entrati in una zona nascosta. 
In questa schermata l'utente deve inserire un codice, cioè "zero", in riferimento
all'easter egg del primo homework. Se il codice non è corretto, l'accesso viene
negato; se invece viene inserito il codice giusto, la pagina imposta un permesso
temporaneo tramite sessione e permette di raggiungere la pagina
"rivelazione_terminale.php". Questa scelta è importante perché evita che la
pagina finale dell'easter egg possa essere aperta liberamente digitando
semplicemente l'indirizzo nella barra del browser.
La pagina "rivelazione_terminale.php" rappresenta il momento della scoperta.
Qui viene mostrata una schermata legata a L, personaggio di "Death Note", con
una GIF e le credenziali necessarie per accedere al terminale segreto.
Anche questa pagina è protetta da un controllo di sessione: può essere
visualizzata solo se il permesso è stato ottenuto dalla schermata precedente e,
dopo essere stato usato, viene consumato, così non è possibile
rivedere direttamente la rivelazione senza seguire di nuovo il percorso
previsto.

Le credenziali mostrate nella rivelazione sono:

Username: terminale
Password: PerfectHomework

Queste credenziali permettono di tornare alla pagina di accesso e usare il
login speciale. In particolare, l'utente deve effettuare il logout, perché la
modalità speciale non deve sovrapporsi alla normale sessione utente. Questa
scelta serve a distinguere chiaramente il funzionamento ordinario del sito dalla
parte nascosta: un utente normale può gestire la propria galleria, mentre la
modalità speciale serve solo per accedere a contenuti extra.

Se le credenziali inserite sono corrette, non viene creato un normale accesso
utente collegato alla tabella "utenti", ma viene avviato il percorso della
modalità speciale e l'utente viene quindi portato alla pagina
"terminale_accesso.php".
Questo è il punto in cui la citazione agli homework di "Tecniche della programmazione"
diventa più evidente: qui viene infatti chiesto di inserire un nome.
I nomi riconosciuti non sono casuali: sono i nomi
usati nei tre homework di "Tecniche della programmazione" e, a seconda del nome
inserito, il sistema mostra una frase diversa, riprendendo direttamente le
scritte e i riferimenti di quei vecchi lavori.
Le frasi speciali non sono salvate nel database, ma nel file
"includes/dati_speciali.php", perché non sono dati applicativi centrali
come giochi, utenti, gallerie o aziende, ma di contenuti particolari
legati all'easter egg: tenerli in un file PHP separato
rende più semplice gestirli e mantiene distinto il percorso nascosto dal resto
del database.

Una volta completato l'accesso al terminale, viene attivata la modalità
speciale, nata solo per sbloccare il minigioco e 
completare il percorso nascosto, senza interferire con le funzioni
principali del sito.

Il minigioco, punto finale del percorso nascosto, consiste in un quiz basato su
frasi famose pronunciate da personaggi dei videogiochi, dove ogni domanda presenta
una citazione e più possibili risposte, tra cui il vero nome del personaggio
associato alla frase. Questa scelta mantiene il collegamento con il tema generale
del progetto, perché anche il contenuto extra resta legato al mondo videoludico.
Dal punto di vista tecnico, il minigioco utilizza il database: le domande e le
opzioni vengono inserite durante l'installazione tramite il file
"dati/minigioco_dati.php" e poi recuperate dinamicamente dalla pagina
"minigioco.php". Le domande vengono selezionate casualmente e salvate in
sessione, così il quiz resta stabile durante lo svolgimento e può poi essere
valutato al momento dell'invio. Dopo la conclusione vengono mostrate le risposte
corrette e il punteggio ottenuto.

Ho fatto tutto questo perché penso che questa citazione personale ai lavori precedenti sia anche
un'occasione per verificare e mostrare alcune conoscenze acquisite nel tempo e con
la sperimentazione.


10. SVILUPPO DEL PROGETTO: DIFFICOLTÀ, EVOLUZIONE E IDEE SCARTATE
-----------------------------------------------------------------

Il progetto non è stato immediato né completamente lineare: durante lo sviluppo
sono state valutate, modificate, unite e in alcuni casi scartate diverse idee, 
non perché fossero sbagliate, ma perché, generalmente, avrebbero
reso il tutto troppo ampio o meno controllabile.

Un elemento che ha influenzato concretamente il modo in cui il progetto è stato
sviluppato è stato anche il periodo in cui è stato realizzato. Durante lo
sviluppo ho avuto, e ho tuttora, una paralisi di Bell, che mi ha costretto a
lavorare in sessioni più brevi e meno regolari del previsto, impedendomi anche
di seguire le lezioni. Per questo motivo non ho potuto seguire sempre un piano 
rigido definito una volta per tutte all'inizio, ma ho portato avanti il progetto in modo più progressivo: alcune
parti sono state realizzate in un momento, poi riprese dopo alcuni giorni,
corrette, ampliate o collegate ad altre idee nate successivamente.
Questo ha portato a un metodo di lavoro più sperimentale e incrementale, tant'è che in
alcuni casi non c'è stata prima una progettazione completa e poi una
realizzazione definitiva, ma è avvenuto il contrario: una soluzione veniva
implementata, provata nel sito, osservata nel suo funzionamento concreto e poi
mantenuta, modificata o ridimensionata. Questo modo di procedere ha richiesto
più controlli finali, ma ha anche permesso di sperimentare maggiormente e di
trasformare alcune idee nate sul momento in parti effettive del progetto.

Una delle prime idee considerate riguardava la distinzione tra utenti normali e
utenti amministratori. In una versione iniziale era stata presa in considerazione
la possibilità di avere utenti amministratori capaci di inserire, modificare o
eliminare giochi, generi e aziende direttamente dal sito: una soluzione che
avrebbe reso il progetto più vicino a un vero gestionale, ma avrebbe richiesto
molte pagine aggiuntive, controlli più complessi e una gestione più precisa dei permessi.
Considerando il modo in cui il progetto stava crescendo, si è scelto di
scartare questa idea, almeno per il secondo homework, poiché inserire un'area
amministrativa avrebbe spostato troppa attenzione sulla gestione interna del
catalogo, mentre l'obiettivo principale del progetto era la consultazione dei
dati e la gestione della galleria personale dell'utente. 

La galleria personale è stata uno dei punti più importanti dello sviluppo.
All'inizio l'idea era più semplice: permettere all'utente di salvare giochi
all'interno di una propria area. Successivamente, riprendendo più volte questa
parte del progetto, la funzione è stata ampliata con voti, recensioni,
ordinamenti e generi preferiti. 
Sono state valutate anche altre possibilità, come indicare lo stato di
completamento di un gioco, la percentuale raggiunta, il fatto di averlo
completato al 100%, di averlo lasciato in sospeso o di volerlo recuperare in
futuro. Queste idee erano coerenti con l'ispirazione iniziale del progetto ed
erano state anche inserite a livello di struttura. Tuttavia, con
il progredire del progetto, queste funzioni stavano rendendo la
gestione meno chiara e iniziavano a causare problemi che in quel momento non era
semplice isolare e correggere con sicurezza; per questo motivo sono state
rimosse o lasciate come possibili sviluppi futuri.

La consultazione delle gallerie degli altri utenti era invece una delle idee
previste fin dall'inizio, con l'obiettivo di valorizzare maggiormente la tabella
utenti e la relazione tra utenti e giochi, evitando che gli utenti servissero
solo per il login. Da questa idea è nata, infatti, la pagina dedicata agli utenti, con la
possibilità di cercare profili registrati, vedere alcune statistiche sulla loro
galleria e aprire la galleria pubblica corrispondente.
È stata però evitata una deriva troppo social: le gallerie 
altrui sono solo consultabili e non sono
stati inseriti commenti, amicizie o funzioni troppo complesse per non perdere il controllo
della sua struttura.

Anche la parte degli easter egg e del minigioco ha subito varie modifiche: infatti,
in fase iniziale, non era prevista una vera sezione minigioco e l'idea di
partenza era piuttosto quella di distribuire piccoli easter egg in varie pagine
del sito, creando un percorso nascosto legato a elementi grafici o interazioni
particolari. Questa soluzione, però, rischiava di diventare dispersiva e
difficile da spiegare, soprattutto in un progetto che stava già crescendo in
molte direzioni.
Inoltre, un'altra idea valutata riguardava l'inserimento di un audio nella pagina
"rivelazione_terminale.php", per rendere la schermata della rivelazione più
particolare e più vicina a un piccolo momento scenico. Dopo aver raggiunto la
pagina finale dell'easter egg, insieme alla GIF e alle credenziali del terminale
segreto sarebbe dovuto partire anche un file audio, così da rendere la scoperta
più riconoscibile e memorabile.
Durante le prove, però, questa soluzione ha creato un problema di validazione:
l'inserimento più semplice dell'audio avrebbe richiesto l'uso dei tag HTML5
"audio" e "source", mentre il progetto dichiara XHTML 1.0 Strict. Per questo
motivo quei tag non risultavano coerenti con il tipo di documento usato.
È stata valutata anche una soluzione alternativa tramite "object", più vicina
alla sintassi accettabile in XHTML, ma in quel caso il comportamento dell'audio
dipendeva molto dal browser. Inoltre, i browser moderni tendono spesso a bloccare
l'autoplay dei contenuti audio, quindi non era possibile garantire un risultato
stabile, soprattutto per quanto riguarda l'avvio automatico.
Il file audio è stato comunque mantenuto nella cartella
"file/altro/extras", così da mostrare quale sarebbe stato l'effetto pensato
inizialmente, senza però compromettere la validazione XHTML della pagina.

Un'altra parte che ha richiesto varie decisioni è stata la struttura grafica
delle pagine. A un certo punto è stata valutata l'idea di rinnovare più
radicalmente l'aspetto del sito, cambiando la disposizione degli elementi e
sperimentando strutture diverse, per esempio con schede disposte in modo
laterale o con layout più complessi. Per questa parte ho chiesto anche un parere
a un amico che studia design alla NABA (un saluto ad Emanuel) e sono stati preparati alcuni
bozzetti e immagini di anteprima per capire come avrebbe potuto apparire una
versione più rinnovata del sito.
Nella cartella "file/altro/extras" è presente anche un modello grafico che, per un certo
periodo, era stato considerato come possibile nuovo punto di partenza. Tuttavia,
anche a causa delle condizioni fisiche e del fatto che potevo lavorare solo in
sessioni brevi, si è scelto di non stravolgere completamente il layout: era più
sicuro rafforzare una struttura già funzionante, migliorandola e rendendola più
dinamica, piuttosto che sostituirla con una nuova struttura che avrebbe richiesto
molti altri controlli. 

Il CSS risente in parte del modo incrementale con cui il progetto è cresciuto.
Non tutte le classi sono nate nello stesso momento: alcune sono state create per
le pagine principali, altre per le pagine di accesso, altre per il terminale,
altre ancora per il glossario, il minigioco, la rivelazione o il tema chiaro. In
alcuni casi alcune soluzioni grafiche sono state provate, poi cambiate o
riutilizzate in modo diverso, in altri ancora sono state completamente scartate:
per questo motivo il foglio di stile è stato controllato più volte, cercando di eliminare le parti non più
necessarie senza però rimuovere in modo troppo aggressivo classi ancora collegate
a sezioni particolari del progetto.
Durante lo sviluppo è stata valutata anche una maggiore suddivisione del foglio
di stile. L'idea era dividere style.css in più file separati, così da ottenere
una struttura ancora più modulare. Tuttavia, durante le prove, è emerso che la
divisione del CSS poteva alterare l'ordine della cascata e quindi cambiare
l'aspetto del sito. Il problema non era il concetto di dividere il CSS, ma il
fatto che alcune regole dipendevano dall'ordine con cui venivano caricate e
sovrascritte.
Alla fine, l'idea è stata scartata e si è arrivati alla soluzione finale:
style.css per il tema scuro e style_chiaro.css per il tema chiaro. Il file
style.css contiene la struttura generale del sito e il tema principale, mentre
style_chiaro.css funziona come override leggero: non duplica tutto il CSS, ma
modifica soprattutto colori, contrasti, sfondi e alcuni stati hover,
mantenendo una struttura grafica stabile e intervenendo
solo dove necessario.
Il tema chiaro merita un approfondimento: il sito nasceva con un
tema scuro, più vicino all'estetica videoludica pensata per GCdb. In seguito, chiedendo un
parere esterno e non tecnico a mia madre, per capire che impressione potesse
avere una persona che usa il sito da semplice utente, la risposta è stata:
"Tutto buio... Non c'è una versione chiara?"; abituato a usare temi scuri, non avevo considerato subito che alcuni
utenti potessero preferire un tema più luminoso. Da questa osservazione è nata
l'idea di aggiungere un tema chiaro, che ha richiesto diverse correzioni. Alcuni colori che
funzionavano bene su sfondo scuro, ad esempio, risultavano poco leggibili nel tema chiaro,
quindi per risolvere il problema sono stati controllati e corretti badge, link,
pulsanti, footer, menu e altri elementi.

Una difficoltà pratica importante ha riguardato l'ambiente di sviluppo. Sul
computer era già presente un'installazione MySQL usata per il progetto di Basi
di Dati: quando ho provato a usare MySQL tramite XAMPP, in alcuni casi il server
non partiva correttamente oppure partiva e poi si arrestava. Il problema era
legato alla presenza di più configurazioni MySQL e al possibile conflitto sulla
porta utilizzata.
Per risolverlo, è stato necessario modificare la porta di MySQL in
XAMPP, evitando il conflitto con l'altra installazione già presente sul computer.
Dopo aver cambiato porta, però, il sito non riusciva comunque a collegarsi
correttamente al database, perché i parametri di connessione usati dal progetto
non erano ancora allineati alla nuova configurazione. A quel punto è stato
modificato il file includes/dati_generali.php, inserendo la porta corretta tra i
parametri di connessione. Questo episodio ha reso più evidente
l'importanza della modularizzazione: separare i dati di connessione dal resto
del codice permette di intervenire in un solo punto quando cambia la
configurazione dell'ambiente.

Durante la revisione finale era poi emerso un problema riguardante la
visualizzazione del sito su schermi più piccoli, in particolare da telefono. Il
layout originale era nato principalmente per una visualizzazione desktop, in
continuità con il primo homework XHTML/CSS, e solo verso la fase finale del
progetto è nata l'idea di curare almeno un adattamento di base per schermi
ridotti, come smartphone, tablet o finestre del browser non a schermo intero.
Inizialmente, anche a causa dei ritmi di lavoro non sempre regolari, questa
parte era stata verificata solo in modo parziale. Durante alcuni test da
telefono ho però notato che il sito non veniva visualizzato nel modo desiderato:
alcune pagine apparivano come una semplice versione rimpicciolita del layout
desktop, il menu laterale e il contenuto non sempre si disponevano in modo
ordinato, alcune card risultavano troppo compresse e alcune immagini non si
adattavano correttamente allo spazio disponibile.
Il problema è stato corretto intervenendo in modo mirato sul foglio di stile,
aggiungendo il meta viewport, necessario per permettere ai browser mobili di
interpretare correttamente la larghezza reale dello schermo, e
introducendo alcune media query di base: ora, infatti, sotto determinate larghezze, il menu
laterale non resta più affiancato al contenuto principale, ma viene disposto
sopra di esso; le card vengono portate a larghezza piena e alcune immagini
vengono ridimensionate o centrate per evitare una visualizzazione disordinata.
L'intervento è stato volutamente limitato e coerente con la natura del progetto:
non è stata realizzata una riscrittura completa del sito in ottica mobile-first,
perché avrebbe significato modificare troppo la struttura originaria del lavoro.

Un altro aspetto che ha richiesto attenzione è stata la gestione dei percorsi dei
file. Nel primo homework le immagini erano inserite direttamente nelle pagine
statiche, quindi il collegamento tra contenuto e immagine era più semplice da
controllare manualmente. Con il passaggio a PHP e MySQL, invece, giochi e
aziende vengono recuperati dal database; di conseguenza anche copertine e loghi
dovevano essere gestiti in modo più ordinato, senza scrivere ogni immagine a mano
dentro le singole pagine.
La soluzione scelta è stata quella di salvare nei dati iniziali il nome del file
immagine associato a ciascun gioco o azienda. Per esempio, nel file
dati/giochi_dati.php ogni gioco contiene anche il nome della propria copertina,
mentre nel file dati/aziende_dati.php ogni azienda contiene il nome del proprio
logo. Le pagine PHP recuperano questi valori dal database e costruiscono il
percorso dell'immagine usando le cartelle corrette: file/giochi per le copertine
dei videogiochi e file/aziende per i loghi delle aziende.
Questa scelta ha reso il sistema più dinamico, perché aggiungendo un nuovo gioco
nei dati iniziali è sufficiente indicare anche il nome della copertina
corrispondente. Allo stesso tempo, però, ha richiesto molta attenzione: il nome
scritto nei dati deve corrispondere esattamente al nome del file presente nella
cartella, perché anche una differenza apparentemente minima, come una lettera maiuscola
o minuscola diversa, può creare problemi in alcuni ambienti.
Durante il progetto le risorse multimediali sono state quindi riorganizzate in
una cartella "file", divisa in "file/giochi", "file/aziende" e "file/altro": ciò ha richiesto di aggiornare i
riferimenti nelle pagine PHP, nei dati iniziali e nelle sezioni speciali. 

La gestione dei file durante lo sviluppo è stata "un'altra gatta da pelare":
in alcune fasi si sono verificati problemi con file che non venivano
più letti correttamente, versioni che risultavano danneggiate o pagine che non
si aprivano più come previsto e ciò ha richiesto di ricostruire più volte alcune
parti del progetto, soprattutto quando non era disponibile un backup aggiornato
di ogni singolo file.
In alcuni momenti è stato quindi necessario ripartire da versioni intermedie del
progetto, recuperare le parti ancora funzionanti e reintegrare manualmente le
modifiche fatte successivamente. Verso la parte finale del lavoro, una parte del
progetto era disponibile in backup e questo ha permesso di ricostruire le pagine
senza dover ripartire completamente da zero. Tuttavia, alcune pagine sono state
comunque risistemate più volte, anche dal punto di vista della formattazione e
dell'indentazione, perché provenivano da versioni diverse o da file recuperati in
momenti successivi, rendendo il lavoro più lungo del previsto, ma anche
portando a una maggiore attenzione verso l'ordine del progetto. 

Il risultato finale è quindi il frutto di un processo di selezione e
sperimentazione, dove la condizione in cui il progetto
è stato sviluppato ha reso il lavoro meno lineare, ma ha anche favorito un modo
di procedere per prove, correzioni e miglioramenti successivi (un approccio alla
Akira Toriyama con "Dragon Ball" insomma).
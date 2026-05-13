<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Info</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Informazioni sul progetto, sulle scelte di sviluppo e sulle tecniche impiegate.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="box">
            <h2>Perché?</h2>

            <p>
                Questo progetto non nasce come un'idea isolata ma, al contrario, prende forma in continuità
                con il lavoro svolto nel corso di <strong>Basi di Dati</strong> con il
                <a href="https://www.diag.uniroma1.it/~nanni/" title="Pagina web del professore">
                    <strong>prof. Umberto Nanni</strong>
                </a>,
                all'interno del quale avevo già scelto di lavorare su un archivio dedicato al mondo dei videogiochi.
            </p>

            <p>
                La scelta di riprendere questo ambito nasce dal fatto che appariva, allo stesso tempo,
                <strong>coerente</strong>, <strong>utile</strong> e <strong>personale</strong>:
                coerente, perché mi permetteva di mantenere un filo logico con un lavoro precedente
                già costruito in modo serio e strutturato; utile, perché il tema si adattava molto bene
                a un sito web dinamico, offrendo la possibilità di organizzare contenuti diversi come giochi,
                generi, utenti, gallerie e recensioni; personale, perché nasce anche
                da un mio interesse reale per il mondo videoludico.
            </p>

            <p>
                Inserisco qui alcune immagini relative al modello realizzato per il progetto precedente:
            </p>

            <div class="schedaProgetto">
                <img class="immagineProgetto" src="file/altro/modello-logico.png" alt="Il modello logico" width="300" />
                <img class="immagineProgetto" src="file/altro/menu-principale-struttura.png" alt="La struttura del menu principale" width="300" />
                <img class="immagineProgetto" src="file/altro/form-gioco-struttura.png" alt="La struttura del form per i giochi" width="300" />
            </div>

            <p>
                Il risultato è quindi un progetto che riprende un'idea già sviluppata e la porta avanti
                in una forma diversa: ciò che in <strong>Basi di Dati</strong> era stato costruito come
                <em>sistema di organizzazione delle informazioni</em>, qui viene rielaborato come
                <strong>applicazione web dinamica</strong>, dando forma a un
                <strong>lavoro unitario e coerente</strong>.
            </p>
        </div>
    <div class="box">
    <h2>Evoluzione del primo homework</h2>

    <p>
        Questa versione del sito nasce come <strong>sviluppo diretto del primo homework</strong>.
        La struttura generale, il tema grafico e l'idea di base sono rimasti legati al progetto
        XHTML/CSS iniziale, ma il sito è stato ampliato con funzionalità dinamiche realizzate
        tramite <strong>PHP</strong> e <strong>MySQL</strong>.
    </p>

    <p>
        Rispetto alla prima versione, il sito non si limita più a presentare contenuti statici:
        ora è possibile effettuare l'<strong>accesso utente</strong>, creare un nuovo profilo,
        conservare informazioni in una <strong>base di dati</strong> e preparare una galleria
        personale collegata agli utenti registrati.
    </p>

    <p>
        Il progetto introduce inoltre una distinzione tra navigazione normale e contenuti
        sbloccabili. Alcune parti del sito cambiano in base allo stato dell'utente:
        se non è stato effettuato l'accesso viene proposta la pagina di login, mentre dopo
        l'accesso il menu mostra le funzioni legate al profilo personale.
    </p>

    <p>
        In questo modo il lavoro mantiene l'identità del primo homework, ma la arricchisce con
        elementi più vicini a una vera applicazione web: dati persistenti, sessioni, registrazione,
        accesso, contenuti personalizzati e una piccola parte segreta da scoprire.
    </p>
</div>
        <div class="box">
            <h2>Tecniche utilizzate</h2>

            <ul>
                <li>uso di <strong>XHTML 1.0 Strict</strong> per la struttura delle pagine;</li>
                <li>uso di <strong>CSS esterno</strong> per la presentazione grafica;</li>
                <li><strong>separazione tra contenuto, stile e logica PHP</strong>;</li>
                <li>uso di <strong>PHP</strong> per rendere dinamiche le pagine;</li>
                <li>uso di <strong>MySQL</strong> per la memorizzazione dei dati principali;</li>
                <li>uso di <strong>sessioni</strong> per gestire l'accesso degli utenti e la modalità speciale;</li>
                <li>uso di <strong>form POST</strong> per login, registrazione e interazioni dell'utente;</li>
                <li>uso di file comuni come <strong>menu.php</strong>, <strong>connection.php</strong> e <strong>dati_generali.php</strong>;</li>
                <li>presenza di uno script <strong>install.php</strong> per creare e popolare il database;</li>
                <li>uso di pseudo-classi come <strong>hover</strong> per rendere l'interfaccia più interattiva.</li>
            </ul>
        </div>

        <div class="box">
            <h2>Autore</h2>

            <a href="accesso-non-autorizzato.php">
                <img class="fotoAutore" src="file/altro/z.jpg" alt="Identità segreta?" width="200" />
            </a>

            <p class="autoreNome">
                <strong>Giuseppe di Fazio</strong>
            </p>

            <p>
                Studente del corso di <em>Linguaggi per il Web</em> presso
                <strong>Sapienza Università di Roma</strong>.
            </p>

            <p>
                Contatto:
                <a href="mailto:giuseppedifazio11@gmail.com" title="Per contattare l'autore">
                    <strong>giuseppedifazio11@gmail.com</strong>
                </a>
            </p>

            <div class="clear"></div>
        </div>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

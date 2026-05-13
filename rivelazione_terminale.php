<?php
session_start();

error_reporting(0);
ini_set("display_errors", "0");

if (!isset($_SESSION["permesso_rivelazione_terminale"])) {
    header("Location: index.php");
    exit();
}

unset($_SESSION["permesso_rivelazione_terminale"]);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Rivelazione terminale</title>
    <meta http-equiv="refresh" content="15; url=logout.php" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class="bodyAccesso">

<div id="paginaAccesso">

    <div class="pannelloAccesso pannelloRivelazioneL">

        <div class="boxRivelazioneL">
            <div class="boxImmagineL">
                <img class="immagineL" src="file/altro/l.gif" alt="?" />
            </div>
        </div>

        <div class="testaAccesso">
            <p>
                Complimenti, non mi aspettavo arrivassi fin qui. <br />
                Il mio nome è L. <br />
                Hai inserito correttamente il codice segreto
                e hai sbloccato le credenziali per il terminale nascosto. <br />
                Accedendo con queste credenziali, perderai le tue "capacità" da utente
                classico, però avrai una sorpresa...<br />
                A presto.
            </p>
        </div>

        <div class="boxInfoSegreteL">
            <p class="titoloInfoSegreteL">Credenziali terminale segreto</p>

            <p class="rigaInfoSegretaL">
                <span class="etichettaInfoSegretaL">Username:</span>
                <strong>terminale</strong>
            </p>

            <p class="rigaInfoSegretaL">
                <span class="etichettaInfoSegretaL">Password:</span>
                <strong>PerfectHomework</strong>
            </p>
        </div>

        <p class="notaRivelazioneL">
            Verrai reindirizzato automaticamente al logout tra 15 secondi, così potrai accedere al terminale segreto.
        </p>

        <div class="azioniAccesso">
            <p>
                <a class="bottoneAccesso" href="logout.php">Fai logout e continua</a>
            </p>
            <p>
                <a class="linkSecondarioAccesso" href="index.php">Torna alla home</a>
            </p>
        </div>

    </div>

</div>

</body>
</html>

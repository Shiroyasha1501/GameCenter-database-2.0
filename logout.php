<?php
session_start();

if (!isset($_SESSION["id_utente"]) && !isset($_SESSION["modalita_speciale"])) {
    header("Location: index.php");
    exit();
}

error_reporting(0);
ini_set("display_errors", "0");

session_unset();

session_destroy();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Logout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class="bodyAccesso">

<div id="paginaAccesso">

    <div class="pannelloAccesso">

        <div class="testaAccesso">
            <p class="logoAccesso">GCdb</p>
            <h1>Logout effettuato</h1>
            <p>
                Sei uscito correttamente dal sistema.
                La sessione utente o la modalità speciale sono state terminate.
            </p>
        </div>

        <div class="boxLoginAccesso">

            <div class="intestazioneLoginCard">
                <div class="iconaLoginCard">↩</div>
                <h2>Sessione conclusa</h2>
                <p>
                    Puoi tornare alla home oppure effettuare un nuovo accesso.
                </p>
            </div>

            <div class="azioniAccesso">
                <p>
                    <a class="bottoneSubmitAccesso" href="index.php">
                        Torna alla home
                    </a>
                </p>

                <p>
                    <a class="linkSecondarioAccesso" href="accesso.php">
                        Effettua un nuovo accesso
                    </a>
                </p>
            </div>

        </div>

    </div>

</div>

</body>
</html>

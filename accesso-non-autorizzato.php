<?php
session_start();

error_reporting(0);
ini_set("display_errors", "0");

$codiceCorretto = "zero";
$stato = "iniziale";
$msg = "";

if (isset($_SESSION["codice_finale_usato"])) {
    $stato = "gia_usato";
    $msg = "CODICE CORRETTO GIA' INSERITO IN PRECEDENZA.";
}

if (!isset($_SESSION["codice_finale_usato"]) && isset($_POST["invio"])) {

    if (trim($_POST["codice"]) == "") {
        $stato = "errore";
        $msg = "ERRORE: nessun codice inserito.";
    } else {
        $codiceInserito = trim($_POST["codice"]);

        if (strtolower($codiceInserito) == $codiceCorretto) {
            $_SESSION["codice_finale_usato"] = 1;
            $_SESSION["permesso_rivelazione_terminale"] = 1;

            header("Location: rivelazione_terminale.php");
            exit();
        } else {
            $stato = "errore";
            $msg = "ACCESSO NEGATO: codice non riconosciuto.";
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Terminale protetto</title>

    <?php
    if ($stato == "errore" || $stato == "gia_usato") {
        echo '<meta http-equiv="refresh" content="4; url=index.php" />';
    }
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />

    
</head>

<body class="bodyTerminale">

<div id="schermoTerminale">

    <div class="terminaleReale">

        <?php
        if ($stato == "iniziale") {
        ?>

            <p class="rigaTerminale">|| GCdb PROTECTED ACCESS ||</p>
            <p class="rigaTerminale">&gt; sorgente rilevata: immagine autore</p>
            <p class="rigaTerminale">&gt; protocollo nascosto inizializzato...</p>
            <p class="rigaTerminale">&gt; richiesta verifica identita'</p>
            <p class="rigaTerminale">&nbsp;</p>

            <p class="rigaTerminale">
                &gt; Inserire codice di accesso:
            </p>

            <form action="accesso-non-autorizzato.php" method="post" class="formTerminale">
                <p>
                    <input class="campoTerminale" type="text" name="codice" id="codice" />
                    <input class="bottoneTerminaleVisibile" type="submit" name="invio" value="Verifica" />
                </p>
            </form>

            <p class="rigaTerminale">&gt; attesa input<span class="cursoreTerminale">_</span></p>

            <p class="rigaTerminale">&nbsp;</p>

            <p class="rigaTerminale">
                <a class="linkTerminale" href="index.php">[ torna alla homepage ]</a>
            </p>

        <?php
        } else if ($stato == "errore") {
        ?>

            <p class="rigaTerminale">|| GCdb PROTECTED ACCESS ||</p>
            <p class="rigaTerminale erroreTerminale">&gt; <?php echo htmlspecialchars($msg); ?></p>
            <p class="rigaTerminale erroreTerminale">&gt; tentativo registrato</p>
            <p class="rigaTerminale">&gt; reindirizzamento automatico alla home in corso<span class="cursoreTerminale">_</span></p>

            <p class="rigaTerminale">&nbsp;</p>

            <p class="rigaTerminale">
                <a class="linkTerminale" href="index.php">[ torna subito alla homepage ]</a>
            </p>

        <?php
        } else if ($stato == "gia_usato") {
        ?>

            <p class="rigaTerminale">|| GCdb PROTECTED ACCESS ||</p>
            <p class="rigaTerminale erroreTerminale">&gt; <?php echo htmlspecialchars($msg); ?></p>
            <p class="rigaTerminale">&gt; la schermata finale non e' piu' disponibile</p>
            <p class="rigaTerminale">&gt; ritorno alla home in corso<span class="cursoreTerminale">_</span></p>

            <p class="rigaTerminale">&nbsp;</p>

            <p class="rigaTerminale">
                <a class="linkTerminale" href="index.php">[ torna subito alla homepage ]</a>
            </p>

        <?php
        }
        ?>

    </div>

</div>

</body>
</html>

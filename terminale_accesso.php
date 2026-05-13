<?php
session_start();

error_reporting(0);
ini_set("display_errors", "0");

require_once("includes/dati_speciali.php");

$msg = "";
$frase = "";
$nome = "";
$accessoConcesso = false;
$mostraIntro = false;

if (!isset($_SESSION["accesso_terminale_avviato"]) && !isset($_SESSION["modalita_speciale"])) {
    header("Location: accesso.php");
    exit();
}

if (isset($_SESSION["modalita_speciale"])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION["accesso_terminale_avviato"]) && !isset($_SESSION["terminale_intro_vista"]) && !isset($_POST["invio"])) {
    $_SESSION["terminale_intro_vista"] = 1;
    $mostraIntro = true;
}

if (isset($_POST["invio"])) {

    if ($_POST["nome"] == "") {
        $msg = "ERRORE: nessun nome inserito.";
    } else {

        $nome = trim($_POST["nome"]);

        if (strpos($nome, " ") !== false) {
            $msg = "ERRORE: il nome non deve contenere spazi.";
        } else {

            $frase = fraseSpeciale($nome);

            $_SESSION["modalita_speciale"] = 1;
            $_SESSION["nome_speciale"] = $nome;

            unset($_SESSION["accesso_terminale_avviato"]);
            unset($_SESSION["terminale_intro_vista"]);

            $accessoConcesso = true;
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Terminale di accesso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />



    <?php
    if ($mostraIntro) {
        echo '<meta http-equiv="refresh" content="2;url=terminale_accesso.php" />';
    }

    if ($accessoConcesso) {
        echo '<meta http-equiv="refresh" content="3;url=index.php" />';
    }
    ?>
</head>

<body class="bodyTerminale">

<div id="schermoTerminale">

    <div class="terminaleReale">

        <?php
        if ($mostraIntro) {
        ?>

            <p class="rigaTerminale">|| INGRESSO MEMORIA TDP ||</p>
            <p class="rigaTerminale">&nbsp;</p>
            <p class="rigaTerminale">&gt; caricamento archivio precedente...</p>
            <p class="rigaTerminale">&gt; inizializzazione sequenze nascoste...</p>
            <p class="rigaTerminale">&gt; controllo accesso terminale GCdb...</p>
            <p class="rigaTerminale">&gt; preparazione richiesta nome utente<span class="cursoreTerminale">_</span></p>

        <?php
        } else if ($accessoConcesso) {
        ?>

            <p class="rigaTerminale">||RICHIESTA DI ACCESSO||</p>
            <p class="rigaTerminale">&nbsp;</p>
            <p class="rigaTerminale">Per favore, inserisca il nome utente (nessuno spazio): <?php echo htmlspecialchars($nome); ?></p>
            <p class="rigaTerminale"><?php echo htmlspecialchars($frase); ?></p>
            <p class="rigaTerminale">&nbsp;</p>
            <p class="rigaTerminale">&gt; modalità speciale attivata.</p>
            <p class="rigaTerminale">&gt; ritorno alla homepage in corso<span class="cursoreTerminale">_</span></p>

        <?php
        } else {
        ?>

            <p class="rigaTerminale">||RICHIESTA DI ACCESSO||</p>
            <p class="rigaTerminale">&nbsp;</p>

            <?php
            if ($msg != "") {
                echo '<p class="rigaTerminale erroreTerminale">' . htmlspecialchars($msg) . '</p>';
            }
            ?>

            <form action="terminale_accesso.php" method="post" class="formTerminale">
                <p class="rigaTerminale">
                    Per favore, inserisca il nome utente (nessuno spazio):
                    <input class="campoTerminale" type="text" name="nome" id="nome" />
                </p>

                <p class="rigaTerminale">
                    <input class="submitNascosto" type="submit" name="invio" value="Invia" />
                </p>
            </form>

        <?php
        }
        ?>

    </div>

</div>

</body>
</html>

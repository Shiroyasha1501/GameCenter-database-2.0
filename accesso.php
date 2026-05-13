<?php
session_start();

error_reporting(0);
ini_set("display_errors", "0");

$msg = "";

if (isset($_SESSION["id_utente"]) || isset($_SESSION["modalita_speciale"])) {
    header("Location: index.php");
    exit();
}

require_once("includes/connection.php");

if (isset($_POST["invio"])) {

    if ($_POST["username"] == "" || $_POST["password"] == "") {
        $msg = "Inserisci username e password.";
    } else {

        if ($_POST["username"] == "terminale" && $_POST["password"] == "PerfectHomework") {
            $_SESSION["accesso_terminale_avviato"] = 1;
            header("Location: terminale_accesso.php");
            exit();
        }

        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $passwordInserita = mysqli_real_escape_string($conn, $_POST["password"]);

        $sql = "SELECT id, username, password
        FROM utenti
        WHERE username = '$username'";

        $risultato = mysqli_query($conn, $sql);

        if (!$risultato) {
            die("Errore nella query: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($risultato) == 0) {
            $msg = "Utente non trovato oppure accesso non valido.";
        } else {
            $riga = mysqli_fetch_array($risultato);

            if ($passwordInserita == $riga["password"]) {
                $_SESSION["id_utente"] = $riga["id"];
                $_SESSION["username"] = $riga["username"];

                header("Location: index.php");
                exit();
            } else {
                $msg = "Password errata.";
            }
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Accesso</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body class="bodyAccesso">

<div id="paginaAccesso">

    <div class="pannelloAccesso">

        <div class="testaAccesso">
            <p class="logoAccesso">GCdb</p>
            <h1>Accesso utente</h1>
            <p>
                Entra nel tuo profilo per gestire la tua galleria personale,
                salvare giochi e scrivere recensioni.
            </p>
        </div>

      <div class="boxLoginAccesso">

    <div class="intestazioneLoginCard">
        <div class="iconaLoginCard">🎮</div>
        <h2>Accedi al tuo profilo</h2>
        <p>
            Inserisci username e password per entrare nel tuo spazio personale.
        </p>
    </div>

    <?php
    if ($msg != "") {
        echo "<p class=\"messaggioErrore\">" . htmlspecialchars($msg) . "</p>";
    }
    ?>

    <form action="accesso.php" method="post">

        <div class="gruppoCampoAccesso">
            <label for="username">Username</label>
            <input class="campoAccesso" type="text" name="username" id="username" />
        </div>

        <div class="gruppoCampoAccesso">
            <label for="password">Password</label>
            <input class="campoAccesso" type="password" name="password" id="password" />
        </div>

        <div class="gruppoBottoneAccesso">
            <input class="bottoneSubmitAccesso" type="submit" name="invio" value="Accedi" />
        </div>

    </form>

    <p class="testoSecondarioAccesso">
        Non hai ancora un profilo?
        <a class="linkSecondarioAccesso" href="registrazione.php">Crea nuovo utente</a>
    </p>

</div>

        <div class="azioniAccesso">
            <p>
                <a class="bottoneAnnullaAccesso" href="index.php">
                    Torna alla home
                </a>
            </p>
        </div>

    </div>

</div>

</body>
</html>

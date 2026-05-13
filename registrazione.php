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

    if ($_POST["username"] == "" || $_POST["password"] == "" || $_POST["conferma_password"] == "") {
        $msg = "Compila tutti i campi.";
    } else if ($_POST["password"] != $_POST["conferma_password"]) {
        $msg = "Le password inserite non coincidono.";
    } else {

        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $passwordInserita = mysqli_real_escape_string($conn, $_POST["password"]);

        $sql = "SELECT id
                FROM utenti
                WHERE username = '$username'";

        $risultato = mysqli_query($conn, $sql);

        if (!$risultato) {
            $msg = "Errore durante il controllo dello username.";
        } else if (mysqli_num_rows($risultato) > 0) {
            $msg = "Username già utilizzato. Scegline un altro.";
        } else {

            $sql = "INSERT INTO utenti (username, password, data_registrazione)
                    VALUES ('$username', '$passwordInserita', NOW())";

            if (!mysqli_query($conn, $sql)) {
                $msg = "Errore durante la registrazione.";
            } else {

                $_SESSION["id_utente"] = mysqli_insert_id($conn);
                $_SESSION["username"] = $username;

                header("Location: index.php");
                exit();
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
    <title>GCdb - Registrazione</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class="bodyAccesso">

<div id="paginaAccesso">

    <div class="pannelloAccesso">

        <div class="testaAccesso">
            <p class="logoAccesso">GCdb</p>
            <h1>Registrazione utente</h1>
            <p>
                Crea un nuovo profilo per salvare giochi nella tua galleria
                personale e scrivere recensioni.
            </p>
        </div>

        <div class="boxLoginAccesso">

            <div class="intestazioneLoginCard">
                <div class="iconaLoginCard">🕹</div>
                <h2>Crea il tuo profilo</h2>
                <p>
                    Scegli uno username e una password per entrare nel sistema.
                </p>
            </div>

            <?php
            if ($msg != "") {
                echo "<p class=\"messaggioErrore\">" . htmlspecialchars($msg) . "</p>";
            }
            ?>

            <form action="registrazione.php" method="post">

                <div class="gruppoCampoAccesso">
                    <label for="username">Username</label>
                    <input class="campoAccesso" type="text" name="username" id="username"/>
                </div>

                <div class="gruppoCampoAccesso">
                    <label for="password">Password</label>
                    <input class="campoAccesso" type="password" name="password" id="password"/>
                </div>

                <div class="gruppoCampoAccesso">
                    <label for="conferma_password">Conferma password</label>
                    <input class="campoAccesso" type="password" name="conferma_password" id="conferma_password" />
                </div>

                <div class="gruppoBottoneAccesso">
                    <input class="bottoneSubmitAccesso" type="submit" name="invio" value="Registrati" />
                </div>

            </form>

            <p class="testoSecondarioAccesso">
                Hai già un profilo?
                <a class="linkSecondarioAccesso" href="accesso.php">Vai all'accesso</a>
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

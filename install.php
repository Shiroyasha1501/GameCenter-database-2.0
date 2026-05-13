<?php

error_reporting(0);
ini_set("display_errors", "0");

mysqli_report(MYSQLI_REPORT_OFF);

require_once("includes/dati_generali.php");

$erroreInstallazione = false;
$messaggioErrore = "";
$installazioneGiaPresente = false;
$installazioneCompletata = false;

function eseguiQuery($conn, $sql, $messaggioPersonalizzato, &$erroreInstallazione, &$messaggioErrore)
{
    if (!$erroreInstallazione) {
        if (!mysqli_query($conn, $sql)) {
            $erroreInstallazione = true;
            $messaggioErrore = $messaggioPersonalizzato . mysqli_error($conn);
        }
    }
}

$conn = mysqli_connect($host, $user, $password, "", $porta);

if (!$conn) {
    $erroreInstallazione = true;
    $messaggioErrore = "Errore di connessione a MySQL: " . mysqli_connect_error();
} else {

    mysqli_set_charset($conn, "utf8");

   $databaseEsiste = mysqli_select_db($conn, $nome_db);

if ($databaseEsiste) {

    $tabelleNecessarie = array(
        "utenti",
        "pegi",
        "giochi",
        "generi",
        "gioco_genere",
        "aziende",
        "gioco_azienda",
        "galleria",
        "generi_preferiti",
        "domande_minigioco",
        "opzioni_minigioco"
    );

    $tabelleTrovate = 0;

    for ($i = 0; $i < count($tabelleNecessarie); $i++) {
        $nomeTabella = mysqli_real_escape_string($conn, $tabelleNecessarie[$i]);

        $sql = "SHOW TABLES LIKE '$nomeTabella'";
        $risultato = mysqli_query($conn, $sql);

        if ($risultato && mysqli_num_rows($risultato) > 0) {
            $tabelleTrovate++;
        }
    }

    if ($tabelleTrovate == count($tabelleNecessarie)) {

        $controlliDati = array(
            "utenti" => 12,
            "pegi" => 5,
            "giochi" => 26,
            "generi" => 52,
            "aziende" => 28,
            "domande_minigioco" => 25
        );

        $datiInizialiPresenti = true;

        foreach ($controlliDati as $tabella => $numeroMinimo) {
            $sql = "SELECT COUNT(*) AS totale FROM `$tabella`";
            $risultato = mysqli_query($conn, $sql);

            if (!$risultato) {
                $datiInizialiPresenti = false;
            } else {
                $riga = mysqli_fetch_assoc($risultato);

                if ($riga["totale"] < $numeroMinimo) {
                    $datiInizialiPresenti = false;
                }
            }
        }

        if ($datiInizialiPresenti) {
            $installazioneGiaPresente = true;
        }
    }
}
    if (!$installazioneGiaPresente) {

        $sql = "CREATE DATABASE IF NOT EXISTS `$nome_db` CHARACTER SET utf8 COLLATE utf8_general_ci";
        eseguiQuery($conn, $sql, "Errore nella creazione del database: ", $erroreInstallazione, $messaggioErrore);

        if (!$erroreInstallazione) {
            if (!mysqli_select_db($conn, $nome_db)) {
                $erroreInstallazione = true;
                $messaggioErrore = "Errore nella selezione del database: " . mysqli_error($conn);
            }
        }

        $sql = "CREATE TABLE IF NOT EXISTS utenti (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(100) NOT NULL,
            data_registrazione DATETIME NOT NULL
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella utenti: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS pegi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            codice VARCHAR(10) NOT NULL UNIQUE,
            descrizione TEXT
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella pegi: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS giochi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titolo VARCHAR(100) NOT NULL,
            anno INT,
            descrizione TEXT,
            copertina VARCHAR(100),
            id_pegi INT,
            FOREIGN KEY (id_pegi) REFERENCES pegi(id)
                ON DELETE SET NULL
                ON UPDATE CASCADE
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella giochi: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS generi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(80) NOT NULL UNIQUE,
            descrizione TEXT,
            tag VARCHAR(50),
            icona VARCHAR(20)
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella generi: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS gioco_genere (
            id_gioco INT NOT NULL,
            id_genere INT NOT NULL,
            PRIMARY KEY (id_gioco, id_genere),
            FOREIGN KEY (id_gioco) REFERENCES giochi(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            FOREIGN KEY (id_genere) REFERENCES generi(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella gioco_genere: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS aziende (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL UNIQUE,
            paese VARCHAR(50),
            descrizione TEXT,
            logo VARCHAR(100)
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella aziende: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS gioco_azienda (
            id_gioco INT NOT NULL,
            id_azienda INT NOT NULL,
            ruolo VARCHAR(30) NOT NULL,
            PRIMARY KEY (id_gioco, id_azienda, ruolo),
            FOREIGN KEY (id_gioco) REFERENCES giochi(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            FOREIGN KEY (id_azienda) REFERENCES aziende(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella gioco_azienda: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS galleria (
            id_galleria INT AUTO_INCREMENT PRIMARY KEY,
            id_utente INT NOT NULL,
            id_gioco INT NOT NULL,
            voto INT,
            recensione TEXT,
            data_aggiunta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT chk_voto_galleria CHECK (voto IS NULL OR (voto >= 1 AND voto <= 10)),
            FOREIGN KEY (id_utente) REFERENCES utenti(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            FOREIGN KEY (id_gioco) REFERENCES giochi(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            UNIQUE (id_utente, id_gioco)
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella galleria: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS generi_preferiti (
            id_utente INT NOT NULL,
            id_genere INT NOT NULL,
            data_aggiunta DATETIME NOT NULL,
            PRIMARY KEY (id_utente, id_genere),
            FOREIGN KEY (id_utente) REFERENCES utenti(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            FOREIGN KEY (id_genere) REFERENCES generi(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella generi_preferiti: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS domande_minigioco (
            id INT AUTO_INCREMENT PRIMARY KEY,
            testo TEXT NOT NULL,
            categoria VARCHAR(50)
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella domande_minigioco: ", $erroreInstallazione, $messaggioErrore);

        $sql = "CREATE TABLE IF NOT EXISTS opzioni_minigioco (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_domanda INT NOT NULL,
            testo VARCHAR(120) NOT NULL,
            corretta BOOLEAN NOT NULL,
            FOREIGN KEY (id_domanda) REFERENCES domande_minigioco(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        )";
        eseguiQuery($conn, $sql, "Errore nella creazione della tabella opzioni_minigioco: ", $erroreInstallazione, $messaggioErrore);

        require_once("dati/utenti_dati.php");

        for ($i = 0; $i < count($utentiIniziali); $i++) {

            $id = $utentiIniziali[$i]["id"];
            $username = mysqli_real_escape_string($conn, $utentiIniziali[$i]["username"]);
            $passwordUtente = mysqli_real_escape_string($conn, $utentiIniziali[$i]["password"]);

            $sql = "INSERT IGNORE INTO utenti (id, username, password, data_registrazione)
                    VALUES ($id, '$username', '$passwordUtente', NOW())";

            eseguiQuery($conn, $sql, "Errore nell'inserimento degli utenti: ", $erroreInstallazione, $messaggioErrore);
        }

        $sql = "INSERT IGNORE INTO pegi (id, codice, descrizione) VALUES
            (1, 'PEGI 3', 'Contenuto adatto a tutte le fasce di eta.'),
            (2, 'PEGI 7', 'Contenuto adatto dai sette anni in su.'),
            (3, 'PEGI 12', 'Contenuto adatto dai dodici anni in su.'),
            (4, 'PEGI 16', 'Contenuto adatto dai sedici anni in su.'),
            (5, 'PEGI 18', 'Contenuto destinato a un pubblico adulto.')";
        eseguiQuery($conn, $sql, "Errore nell'inserimento dei PEGI: ", $erroreInstallazione, $messaggioErrore);

        require_once("dati/generi_dati.php");
        for ($i = 0; $i < count($generiIniziali); $i++) {

            $id = $generiIniziali[$i]["id"];
            $nome = mysqli_real_escape_string($conn, $generiIniziali[$i]["nome"]);
            $descrizione = mysqli_real_escape_string($conn, $generiIniziali[$i]["descrizione"]);
            $tag = mysqli_real_escape_string($conn, $generiIniziali[$i]["tag"]);
            $icona = mysqli_real_escape_string($conn, $generiIniziali[$i]["icona"]);

            $sql = "INSERT IGNORE INTO generi (id, nome, descrizione, tag, icona)
                    VALUES ($id, '$nome', '$descrizione', '$tag', '$icona')";

            eseguiQuery($conn, $sql, "Errore nell'inserimento dei generi: ", $erroreInstallazione, $messaggioErrore);
        }

        require_once("dati/giochi_dati.php");

        for ($i = 0; $i < count($giochiIniziali); $i++) {

            $id = $giochiIniziali[$i]["id"];
            $titolo = mysqli_real_escape_string($conn, $giochiIniziali[$i]["titolo"]);
            $anno = $giochiIniziali[$i]["anno"];
            $descrizione = mysqli_real_escape_string($conn, $giochiIniziali[$i]["descrizione"]);
            $copertina = mysqli_real_escape_string($conn, $giochiIniziali[$i]["copertina"]);
            $idPegi = $giochiIniziali[$i]["id_pegi"];

            $sql = "INSERT IGNORE INTO giochi (id, titolo, anno, descrizione, copertina, id_pegi)
                    VALUES ($id, '$titolo', $anno, '$descrizione', '$copertina', $idPegi)";

            eseguiQuery($conn, $sql, "Errore nell'inserimento dei giochi: ", $erroreInstallazione, $messaggioErrore);
        }

        require_once("dati/gioco_genere_dati.php");

        for ($i = 0; $i < count($associazioniGiocoGenere); $i++) {

            $idGioco = $associazioniGiocoGenere[$i]["id_gioco"];
            $idGenere = $associazioniGiocoGenere[$i]["id_genere"];

            $sql = "INSERT IGNORE INTO gioco_genere (id_gioco, id_genere)
                    VALUES ($idGioco, $idGenere)";

            eseguiQuery($conn, $sql, "Errore nell'associazione giochi-generi: ", $erroreInstallazione, $messaggioErrore);
        }

        require_once("dati/aziende_dati.php");

        for ($i = 0; $i < count($aziendeIniziali); $i++) {

            $id = $aziendeIniziali[$i]["id"];
            $nome = mysqli_real_escape_string($conn, $aziendeIniziali[$i]["nome"]);
            $paese = mysqli_real_escape_string($conn, $aziendeIniziali[$i]["paese"]);
            $descrizione = mysqli_real_escape_string($conn, $aziendeIniziali[$i]["descrizione"]);
            $logo = mysqli_real_escape_string($conn, $aziendeIniziali[$i]["logo"]);

            $sql = "INSERT IGNORE INTO aziende (id, nome, paese, descrizione, logo)
                    VALUES ($id, '$nome', '$paese', '$descrizione', '$logo')";

            eseguiQuery($conn, $sql, "Errore nell'inserimento delle aziende: ", $erroreInstallazione, $messaggioErrore);
        }

        require_once("dati/gioco_azienda_dati.php");

        for ($i = 0; $i < count($associazioniGiocoAzienda); $i++) {

            $idGioco = $associazioniGiocoAzienda[$i]["id_gioco"];
            $idAzienda = $associazioniGiocoAzienda[$i]["id_azienda"];
            $ruolo = mysqli_real_escape_string($conn, $associazioniGiocoAzienda[$i]["ruolo"]);

            $sql = "INSERT IGNORE INTO gioco_azienda (id_gioco, id_azienda, ruolo)
                    VALUES ($idGioco, $idAzienda, '$ruolo')";

            eseguiQuery($conn, $sql, "Errore nell'associazione giochi-aziende: ", $erroreInstallazione, $messaggioErrore);
        }

        require_once("dati/minigioco_dati.php");

        for ($i = 0; $i < count($domandeMinigioco); $i++) {

            $idDomanda = $domandeMinigioco[$i]["id"];
            $testoDomanda = mysqli_real_escape_string($conn, $domandeMinigioco[$i]["testo"]);
            $categoria = mysqli_real_escape_string($conn, $domandeMinigioco[$i]["categoria"]);

            $sql = "INSERT IGNORE INTO domande_minigioco (id, testo, categoria)
                    VALUES ($idDomanda, '$testoDomanda', '$categoria')";

            eseguiQuery($conn, $sql, "Errore nell'inserimento delle domande del minigioco: ", $erroreInstallazione, $messaggioErrore);

            for ($j = 0; $j < count($domandeMinigioco[$i]["opzioni"]); $j++) {

                $testoOpzione = mysqli_real_escape_string($conn, $domandeMinigioco[$i]["opzioni"][$j]["testo"]);
                $corretta = $domandeMinigioco[$i]["opzioni"][$j]["corretta"];

                $sql = "INSERT IGNORE INTO opzioni_minigioco (id_domanda, testo, corretta)
                        VALUES ($idDomanda, '$testoOpzione', $corretta)";

                eseguiQuery($conn, $sql, "Errore nell'inserimento delle opzioni del minigioco: ", $erroreInstallazione, $messaggioErrore);
            }
        }

        require_once("dati/gallerie_dati.php");

        for ($i = 0; $i < count($gallerieIniziali); $i++) {

            $idUtente = $gallerieIniziali[$i]["id_utente"];
            $idGioco = $gallerieIniziali[$i]["id_gioco"];
            $voto = $gallerieIniziali[$i]["voto"];
            $recensione = mysqli_real_escape_string($conn, $gallerieIniziali[$i]["recensione"]);

            $sql = "INSERT IGNORE INTO galleria (id_utente, id_gioco, voto, recensione, data_aggiunta)
                    VALUES ($idUtente, $idGioco, $voto, '$recensione', NOW())";

            eseguiQuery($conn, $sql, "Errore nell'inserimento della galleria: ", $erroreInstallazione, $messaggioErrore);
        }

        require_once("dati/generi_preferiti_dati.php");

        for ($i = 0; $i < count($generiPreferitiIniziali); $i++) {

            $idUtente = $generiPreferitiIniziali[$i]["id_utente"];
            $idGenere = $generiPreferitiIniziali[$i]["id_genere"];

            $sql = "INSERT IGNORE INTO generi_preferiti (id_utente, id_genere, data_aggiunta)
                    VALUES ($idUtente, $idGenere, NOW())";

            eseguiQuery($conn, $sql, "Errore nell'inserimento dei generi preferiti: ", $erroreInstallazione, $messaggioErrore);
        }

        if (!$erroreInstallazione) {
            $installazioneCompletata = true;
        }
    }

    mysqli_close($conn);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Installazione</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class="bodyAccesso">

<div id="paginaAccesso">

    <div class="pannelloAccesso">

        <div class="testaAccesso">
            <p class="logoAccesso">GCdb</p>
            <h1>Installazione del sistema</h1>
            <p>
                Configurazione iniziale del database dell'applicazione web
                per il catalogo videoludico e la galleria personale.
            </p>
        </div>

        <div class="boxLoginAccesso boxInstallazione">

            <div class="intestazioneLoginCard">
                <div class="iconaInstallazione">DB</div>

                <?php
                if ($erroreInstallazione) {
                    echo "<h2>Installazione non completata</h2>";
                    echo "<p>Si è verificato un problema durante la creazione del database o delle tabelle.</p>";
                } else if ($installazioneGiaPresente) {
                    echo "<h2>Sistema già installato</h2>";
                    echo "<p>Il database e le tabelle principali risultano già presenti.</p>";
                } else {
                    echo "<h2>Installazione completata</h2>";
                    echo "<p>Il database è stato creato e popolato correttamente con i dati iniziali.</p>";
                }
                ?>
            </div>

            <?php
            if ($erroreInstallazione) {
                echo '<p class="messaggioErrore">' . htmlspecialchars($messaggioErrore) . '</p>';
            } else if ($installazioneGiaPresente) {
                echo '<p class="messaggioAvviso">Nessuna nuova installazione eseguita: il sistema risulta già configurato.</p>';
            } else if ($installazioneCompletata) {
                echo '<p class="messaggioSuccesso">Installazione eseguita con successo.</p>';
            }
            ?>

            <?php
            if (!$erroreInstallazione) {
            ?>

                <ul class="listaInstallazione">
                    <li>Database <strong><?php echo htmlspecialchars($nome_db); ?></strong>.</li>
                    <li>Tabelle principali: utenti, giochi, generi, PEGI, aziende, galleria, generi preferiti e minigioco.</li>
                </ul>

                <p class="notaInstallazione">
                    Per reinstallare da zero è necessario eliminare prima il database tramite phpMyAdmin.
                </p>

            <?php
            }
            ?>

            <div class="azioniInstallazione">
                <?php
                if ($erroreInstallazione) {
                ?>
                    <a class="bottoneSubmitAccesso" href="install.php">Riprova installazione</a>
                    <a class="bottoneAnnullaAccesso" href="index.php">Torna alla home</a>
                <?php
                } else {
                ?>
                    <a class="bottoneSubmitAccesso" href="index.php">Vai alla home</a>
                    <a class="bottoneAnnullaAccesso" href="accesso.php">Vai all'accesso</a>
                <?php
                }
                ?>
            </div>

        </div>

    </div>

</div>

</body>
</html>

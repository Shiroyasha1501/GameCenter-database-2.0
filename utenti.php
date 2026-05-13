<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

require_once("includes/connection.php");

$ricerca = "";

if (isset($_GET["q"])) {
    $ricerca = trim($_GET["q"]);
}

$ordine = "username_asc";

if (isset($_GET["ordine"])) {
    $ordine = $_GET["ordine"];
}

if ($ordine == "username_desc") {
    $orderBy = "utenti.username DESC";
} else if ($ordine == "giochi_desc") {
    $orderBy = "numero_giochi DESC, utenti.username ASC";
} else if ($ordine == "voto_desc") {
    $orderBy = "voto_medio DESC, utenti.username ASC";
} else {
    $ordine = "username_asc";
    $orderBy = "utenti.username ASC";
}

$condizioneRicerca = "";

if ($ricerca != "") {
    $ricercaSql = mysqli_real_escape_string($conn, $ricerca);
    $condizioneRicerca = "WHERE utenti.username LIKE '%$ricercaSql%'";
}

$sql = "SELECT utenti.id,
               utenti.username,
               utenti.data_registrazione,
               COUNT(galleria.id_gioco) AS numero_giochi,
               AVG(galleria.voto) AS voto_medio,
               MAX(galleria.data_aggiunta) AS ultimo_aggiornamento
        FROM utenti
        LEFT JOIN galleria ON utenti.id = galleria.id_utente
        $condizioneRicerca
        GROUP BY utenti.id, utenti.username, utenti.data_registrazione
        ORDER BY $orderBy";
$risultatoUtenti = mysqli_query($conn, $sql);

$numeroUtenti = 0;

if ($risultatoUtenti) {
    $numeroUtenti = mysqli_num_rows($risultatoUtenti);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Gallerie utenti</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Cerca gli utenti registrati e consulta le loro gallerie pubbliche.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="heroPagina">
            <p class="badgePagina">GALLERIE UTENTI</p>

            <h2>Utenti registrati</h2>

            <p>
                Questa sezione permette di cercare gli utenti presenti nel database e
                di visualizzare una sintesi delle loro gallerie. <br />
                Le gallerie degli utenti sono consultabili in sola lettura:
                solo il proprietario può modificare la propria galleria personale.
            </p>

        </div>

        <div class="pannelloPagina">
            <h2>Cerca un utente</h2>

            <form action="utenti.php" method="get">
                <p>
                    <label for="q">Cerca per username:</label>
                </p>

                <p>
                    <input class="barraRicerca" type="text" name="q" id="q"
                           value="<?php echo htmlspecialchars($ricerca); ?>" />

                    <input type="hidden" name="ordine" value="<?php echo htmlspecialchars($ordine); ?>" />

                    <input class="bottoneRicerca" type="submit" value="Cerca" />

                    <a class="bottoneReset" href="utenti.php">Mostra tutto</a>
                </p>
            </form>

            <?php
            if ($ricerca != "") {
                echo '<p class="risultatoRicerca">';
                echo 'Risultati per: <strong>' . htmlspecialchars($ricerca) . '</strong>';
                echo '</p>';
            } else {
                echo '<p class="risultatoRicerca">';
                echo 'Utenti trovati: <strong>' . $numeroUtenti . '</strong>';
                echo '</p>';
            }
            ?>
        </div>

        <div class="pannelloPagina">
            <h2>Ordina utenti</h2>

            <p>
                <a class="bottoneOrdine" href="utenti.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=username_asc">Username A-Z</a>
                <a class="bottoneOrdine" href="utenti.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=username_desc">Username Z-A</a>
                <a class="bottoneOrdine" href="utenti.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=giochi_desc">Più giochi</a>
                <a class="bottoneOrdine" href="utenti.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=voto_desc">Voto medio più alto</a>
            </p>

            <p class="meta">
                Ordinamento attuale: <?php echo htmlspecialchars($ordine); ?>
            </p>
        </div>

        <div class="pannelloPagina">
            <h2>Elenco utenti</h2>

            <?php
            if ($risultatoUtenti && mysqli_num_rows($risultatoUtenti) > 0) {

                while ($utente = mysqli_fetch_array($risultatoUtenti)) {

                    $votoMedio = "Nessun voto";

                    if ($utente["voto_medio"] != NULL) {
                        $votoMedio = number_format($utente["voto_medio"], 1, ",", ".");
                    }

                    $ultimoAggiornamento = "Nessun gioco aggiunto";

                    if ($utente["ultimo_aggiornamento"] != NULL) {
                        $ultimoAggiornamento = $utente["ultimo_aggiornamento"];
                    }
            ?>

                    <div class="card cardAzienda">
                        <h3><?php echo htmlspecialchars($utente["username"]); ?></h3>

                        <p class="meta">
                            Registrato il: <?php echo htmlspecialchars($utente["data_registrazione"]); ?>
                        </p>

                        <p>
                            Giochi in galleria:
                            <strong><?php echo $utente["numero_giochi"]; ?></strong>
                        </p>

                        <p>
                            Voto medio:
                            <strong><?php echo htmlspecialchars($votoMedio); ?></strong>
                        </p>

                        <p>
                            Ultimo aggiornamento:
                            <strong><?php echo htmlspecialchars($ultimoAggiornamento); ?></strong>
                        </p>

                        <p>
                            <?php
                            if (isset($_SESSION["id_utente"]) && $_SESSION["id_utente"] == $utente["id"]) {
                            ?>
                                <a class="bottone" href="galleria.php">Vai alla mia galleria</a>
                            <?php
                            } else {
                            ?>
                                <a class="bottone" href="galleria_utente.php?id_utente=<?php echo $utente["id"]; ?>">
                                    Vedi galleria
                                </a>
                            <?php
                            }
                            ?>
                        </p>
                    </div>

            <?php
                }
            } else {
            ?>

                <div class="box">
                    <h3>Nessun utente trovato</h3>

                    <p>
                        Non sono presenti utenti corrispondenti alla ricerca effettuata.
                    </p>
                </div>

            <?php
            }
            ?>

            <div class="clear"></div>
        </div>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

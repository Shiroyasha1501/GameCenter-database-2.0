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

$messaggio = "";
$errore = "";

$ordine = "nome_asc";

if (isset($_GET["ordine"])) {
    $ordine = $_GET["ordine"];
}

if ($ordine == "nome_desc") {
    $orderBy = "nome DESC";
} else {
    $ordine = "nome_asc";
    $orderBy = "nome ASC";
}

if (isset($_POST["aggiungi_preferito"])) {

    if (!isset($_SESSION["id_utente"])) {

        $errore = "Devi effettuare l'accesso per aggiungere un genere ai preferiti.";

    } else {

        $idUtente = $_SESSION["id_utente"];
        $idGenere = intval($_POST["id_genere"]);

        if ($idGenere > 0) {

            $sqlPreferito = "INSERT IGNORE INTO generi_preferiti (id_utente, id_genere, data_aggiunta)
                             VALUES ($idUtente, $idGenere, NOW())";

            if (mysqli_query($conn, $sqlPreferito)) {

                if (mysqli_affected_rows($conn) > 0) {
                    $messaggio = "Genere aggiunto ai preferiti.";
                } else {
                    $messaggio = "Questo genere era già tra i tuoi preferiti.";
                }

            } else {
                $errore = "Errore durante l'aggiunta del genere ai preferiti.";
            }
        }
    }
}

if ($ricerca == "") {

    $sql = "SELECT id, nome, descrizione, tag
            FROM generi
            ORDER BY $orderBy";

} else {

    $ricercaSql = mysqli_real_escape_string($conn, $ricerca);

    $sql = "SELECT id, nome, descrizione, tag
            FROM generi
            WHERE nome LIKE '%$ricercaSql%'
               OR descrizione LIKE '%$ricercaSql%'
               OR tag LIKE '%$ricercaSql%'
            ORDER BY $orderBy";
}

$risultatoGeneri = mysqli_query($conn, $sql);

$numeroGeneri = 0;

if ($risultatoGeneri) {
    $numeroGeneri = mysqli_num_rows($risultatoGeneri);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Generi</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Esplora i principali generi e sottogeneri del mondo videoludico.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="heroPagina">
            <p class="badgePagina">CATALOGO GENERI</p>

            <h2>Generi videoludici</h2>

            <p>
                Questa sezione mostra i generi presenti nel database di GCdb, permettendo di <strong>cercarli</strong>, <strong>ordinarli</strong> ed eventualmente <strong>aggiungerli nei preferiti</strong> (dopo l'accesso).
            </p>
            
        </div>

        <?php
        if ($messaggio != "") {
            echo '<p class="messaggioSuccesso">' . htmlspecialchars($messaggio) . '</p>';
        }

        if ($errore != "") {
            echo '<p class="messaggioErrore">' . htmlspecialchars($errore) . '</p>';
        }
        ?>

        <div class="pannelloPagina">
            <h2>Cerca un genere</h2>

            <form action="generi.php" method="get">
                <p>
                    <label for="q">Cerca per nome, descrizione o categoria:</label>
                </p>

                <p>
                    <input class="barraRicerca" type="text" name="q" id="q"
                           value="<?php echo htmlspecialchars($ricerca); ?>" />

                    <input type="hidden" name="ordine" value="<?php echo htmlspecialchars($ordine); ?>" />

                    <input class="bottoneRicerca" type="submit" value="Cerca" />

                    <a class="bottoneReset" href="generi.php">Mostra tutto</a>
                </p>
            </form>

            <?php
            if ($ricerca != "") {
                echo '<p class="risultatoRicerca">';
                echo 'Risultati per: <strong>' . htmlspecialchars($ricerca) . '</strong> ';
                echo '(' . $numeroGeneri . ' generi trovati)';
                echo '</p>';
            } else {
                echo '<p class="risultatoRicerca">';
                echo 'Generi presenti nel database: <strong>' . $numeroGeneri . '</strong>';
                echo '</p>';
            }
            ?>
        </div>

        <div class="pannelloPagina">
            <h2>Ordina i generi</h2>

            <p>
                <a class="bottoneOrdine" href="generi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=nome_asc">Nome A-Z</a>
                <a class="bottoneOrdine" href="generi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=nome_desc">Nome Z-A</a>
            </p>

            <p class="meta">
                Ordinamento attuale:
                <strong>
                    <?php
                    if ($ordine == "nome_desc") {
                        echo "nome Z-A";
                    } else {
                        echo "nome A-Z";
                    }
                    ?>
                </strong>
            </p>
        </div>

        <div class="pannelloPagina">

            <?php
            if ($ricerca == "") {
                echo "<h2>Tutti i generi</h2>";
            } else {
                echo "<h2>Risultati della ricerca</h2>";
            }
            ?>

            <?php
            if (!$risultatoGeneri || $numeroGeneri == 0) {
            ?>

                <div class="box">
                    <h2>Nessun genere trovato</h2>

                    <p>
                        La ricerca non ha prodotto risultati. Prova con un'altra parola
                        oppure torna alla visualizzazione completa dei generi.
                    </p>
                </div>

            <?php
            } else {

                while ($genere = mysqli_fetch_array($risultatoGeneri)) {
            ?>

                    <div class="card cardGenere">

                        <h3><?php echo htmlspecialchars($genere["nome"]); ?></h3>

                        <p>
                            <?php echo htmlspecialchars($genere["descrizione"]); ?>
                        </p>

                        <p class="tagGenere">
                            <?php echo htmlspecialchars($genere["tag"]); ?>
                        </p>

                        <?php
                        if (isset($_SESSION["id_utente"])) {
                        ?>
                            <form action="generi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=<?php echo htmlspecialchars($ordine); ?>" method="post">
                                <p>
                                    <input type="hidden" name="id_genere" value="<?php echo $genere["id"]; ?>" />

                                    <input class="bottoneSalvaGalleria"
                                           type="submit"
                                           name="aggiungi_preferito"
                                           value="Aggiungi ai preferiti" />
                                </p>
                            </form>
                        <?php
                        } else {
                        ?>
                            <p>
                                <a class="bottone" href="accesso.php">Accedi per aggiungerlo ai preferiti</a>
                            </p>
                        <?php
                        }
                        ?>

                    </div>

            <?php
                }
            }
            ?>

            <div class="clear"></div>

        </div>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

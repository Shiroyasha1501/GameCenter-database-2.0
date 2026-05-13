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

$ordine = "nome_asc";

if (isset($_GET["ordine"])) {
    $ordine = $_GET["ordine"];
}

if ($ordine == "nome_desc") {
    $orderBy = "aziende.nome DESC";
} else {
    $ordine = "nome_asc";
    $orderBy = "aziende.nome ASC";
}

if ($ricerca == "") {

    $sql = "SELECT aziende.id,
                   aziende.nome,
                   aziende.paese,
                   aziende.descrizione,
                   aziende.logo,
                   COUNT(DISTINCT gioco_azienda.id_gioco) AS numero_giochi,
                   GROUP_CONCAT(DISTINCT CONCAT(giochi.titolo, ' - ', gioco_azienda.ruolo)
                                ORDER BY giochi.titolo
                                SEPARATOR ', ') AS giochi_collegati
            FROM aziende
            LEFT JOIN gioco_azienda ON aziende.id = gioco_azienda.id_azienda
            LEFT JOIN giochi ON gioco_azienda.id_gioco = giochi.id
            GROUP BY aziende.id, aziende.nome, aziende.paese, aziende.descrizione, aziende.logo
            ORDER BY $orderBy";

} else {

    $ricercaSql = mysqli_real_escape_string($conn, $ricerca);

    $sql = "SELECT aziende.id,
                   aziende.nome,
                   aziende.paese,
                   aziende.descrizione,
                   aziende.logo,
                   COUNT(DISTINCT gioco_azienda.id_gioco) AS numero_giochi,
                   GROUP_CONCAT(DISTINCT CONCAT(giochi.titolo, ' - ', gioco_azienda.ruolo)
                                ORDER BY giochi.titolo
                                SEPARATOR ', ') AS giochi_collegati
            FROM aziende
            LEFT JOIN gioco_azienda ON aziende.id = gioco_azienda.id_azienda
            LEFT JOIN giochi ON gioco_azienda.id_gioco = giochi.id
            WHERE aziende.nome LIKE '%$ricercaSql%'
               OR aziende.paese LIKE '%$ricercaSql%'
               OR aziende.descrizione LIKE '%$ricercaSql%'
               OR aziende.logo LIKE '%$ricercaSql%'
               OR giochi.titolo LIKE '%$ricercaSql%'
               OR gioco_azienda.ruolo LIKE '%$ricercaSql%'
            GROUP BY aziende.id, aziende.nome, aziende.paese, aziende.descrizione, aziende.logo
            ORDER BY $orderBy";
}

$risultatoAziende = mysqli_query($conn, $sql);

$numeroAziende = 0;

if ($risultatoAziende) {
    $numeroAziende = mysqli_num_rows($risultatoAziende);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Aziende</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Esplora studi di sviluppo e publisher collegati ai giochi del catalogo.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="heroPagina">
            <p class="badgePagina">CATALOGO AZIENDE</p>

            <h2>Aziende videoludiche</h2>

            <p>
                Questa sezione mostra le aziende presenti nel database di GCdb 
                (studi di sviluppo, publisher e realta indipendenti collegate ai giochi),
                permettendo di <strong>cercarle</strong> e <strong>ordinarli</strong>.
            </p>

        </div>

        <div class="pannelloPagina">
            <h2>Cerca un'azienda</h2>

            <form action="aziende.php" method="get">
                <p>
                    <label for="q">Cerca per nome, paese, descrizione, gioco o ruolo:</label>
                </p>

                <p>
                    <input class="barraRicerca" type="text" name="q" id="q"
                           value="<?php echo htmlspecialchars($ricerca); ?>" />

                    <input type="hidden" name="ordine" value="<?php echo htmlspecialchars($ordine); ?>" />

                    <input class="bottoneRicerca" type="submit" value="Cerca" />

                    <a class="bottoneReset" href="aziende.php">Mostra tutto</a>
                </p>
            </form>

            <?php
            if ($ricerca != "") {
                echo '<p class="risultatoRicerca">';
                echo 'Risultati per: <strong>' . htmlspecialchars($ricerca) . '</strong> ';
                echo '(' . $numeroAziende . ' aziende trovate)';
                echo '</p>';
            } else {
                echo '<p class="risultatoRicerca">';
                echo 'Aziende presenti nel database: <strong>' . $numeroAziende . '</strong>';
                echo '</p>';
            }
            ?>
        </div>

        <div class="pannelloPagina">
            <h2>Ordina le aziende</h2>

            <p>
                <a class="bottoneOrdine" href="aziende.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=nome_asc">Nome A-Z</a>
                <a class="bottoneOrdine" href="aziende.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=nome_desc">Nome Z-A</a>
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
                echo "<h2>Tutte le aziende</h2>";
            } else {
                echo "<h2>Risultati della ricerca</h2>";
            }
            ?>

            <?php
            if (!$risultatoAziende || $numeroAziende == 0) {
            ?>

                <div class="box">
                    <h2>Nessuna azienda trovata</h2>

                    <p>
                        La ricerca non ha prodotto risultati. Prova con un'altra parola
                        oppure torna alla visualizzazione completa delle aziende.
                    </p>
                </div>

            <?php
            } else {

                while ($azienda = mysqli_fetch_array($risultatoAziende)) {
            ?>

                    <div class="card cardAzienda">

                        <?php
                        if ($azienda["logo"] != "" && file_exists("file/aziende/" . $azienda["logo"])) {
                        ?>
                            <img class="logoAziendaCatalogo"
                                 src="file/aziende/<?php echo htmlspecialchars($azienda["logo"]); ?>"
                                 alt="Logo di <?php echo htmlspecialchars($azienda["nome"]); ?>" />
                        <?php
                        }
                        ?>

                        <h3><?php echo htmlspecialchars($azienda["nome"]); ?></h3>

                        <p class="meta">
                            <strong>Paese:</strong>
                            <?php
                            if ($azienda["paese"] != "") {
                                echo htmlspecialchars($azienda["paese"]);
                            } else {
                                echo "Non specificato";
                            }
                            ?>
                        </p>

                        <p>
                            <?php echo htmlspecialchars($azienda["descrizione"]); ?>
                        </p>

                        <p class="tagAzienda">
                            Giochi collegati:
                            <?php echo htmlspecialchars($azienda["numero_giochi"]); ?>
                        </p>

                        <?php
                        if ($azienda["giochi_collegati"] != "") {
                        ?>
                            <div class="boxInterno giochiCollegatiAzienda">
                                <strong>Collegamenti:</strong>
                                <p>
                                    <?php echo htmlspecialchars($azienda["giochi_collegati"]); ?>
                                </p>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="boxInterno giochiCollegatiAzienda">
                                <strong>Collegamenti:</strong>
                                <p>Nessun gioco collegato.</p>
                            </div>
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

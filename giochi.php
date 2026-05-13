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
    $orderBy = "giochi.titolo DESC";
} else if ($ordine == "anno_asc") {
    $orderBy = "giochi.anno ASC, giochi.titolo ASC";
} else if ($ordine == "anno_desc") {
    $orderBy = "giochi.anno DESC, giochi.titolo ASC";
} else {
    $ordine = "nome_asc";
    $orderBy = "giochi.titolo ASC";
}

if ($ricerca == "") {

    $sql = "SELECT giochi.id,
                   giochi.titolo,
                   giochi.anno,
                   giochi.descrizione,
                   giochi.copertina,
                   pegi.codice AS codice_pegi,
                   GROUP_CONCAT(DISTINCT generi.nome ORDER BY generi.nome SEPARATOR ', ') AS generi_collegati,
                   GROUP_CONCAT(DISTINCT CONCAT(aziende.nome, ' - ', gioco_azienda.ruolo)
                                ORDER BY aziende.nome
                                SEPARATOR ', ') AS aziende_collegate
            FROM giochi
            LEFT JOIN pegi ON giochi.id_pegi = pegi.id
            LEFT JOIN gioco_genere ON giochi.id = gioco_genere.id_gioco
            LEFT JOIN generi ON gioco_genere.id_genere = generi.id
            LEFT JOIN gioco_azienda ON giochi.id = gioco_azienda.id_gioco
            LEFT JOIN aziende ON gioco_azienda.id_azienda = aziende.id
            GROUP BY giochi.id, giochi.titolo, giochi.anno, giochi.descrizione, giochi.copertina, pegi.codice
            ORDER BY $orderBy";

} else {

    $ricercaSql = mysqli_real_escape_string($conn, $ricerca);

    $sql = "SELECT giochi.id,
                   giochi.titolo,
                   giochi.anno,
                   giochi.descrizione,
                   giochi.copertina,
                   pegi.codice AS codice_pegi,
                   GROUP_CONCAT(DISTINCT generi.nome ORDER BY generi.nome SEPARATOR ', ') AS generi_collegati,
                   GROUP_CONCAT(DISTINCT CONCAT(aziende.nome, ' - ', gioco_azienda.ruolo)
                                ORDER BY aziende.nome
                                SEPARATOR ', ') AS aziende_collegate
            FROM giochi
            LEFT JOIN pegi ON giochi.id_pegi = pegi.id
            LEFT JOIN gioco_genere ON giochi.id = gioco_genere.id_gioco
            LEFT JOIN generi ON gioco_genere.id_genere = generi.id
            LEFT JOIN gioco_azienda ON giochi.id = gioco_azienda.id_gioco
            LEFT JOIN aziende ON gioco_azienda.id_azienda = aziende.id
            WHERE giochi.titolo LIKE '%$ricercaSql%'
               OR giochi.descrizione LIKE '%$ricercaSql%'
               OR giochi.anno LIKE '%$ricercaSql%'
               OR pegi.codice LIKE '%$ricercaSql%'
               OR generi.nome LIKE '%$ricercaSql%'
               OR aziende.nome LIKE '%$ricercaSql%'
               OR gioco_azienda.ruolo LIKE '%$ricercaSql%'
            GROUP BY giochi.id, giochi.titolo, giochi.anno, giochi.descrizione, giochi.copertina, pegi.codice
            ORDER BY $orderBy";
}

$risultatoGiochi = mysqli_query($conn, $sql);

$numeroGiochi = 0;

if ($risultatoGiochi) {
    $numeroGiochi = mysqli_num_rows($risultatoGiochi);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Giochi</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Esplora il catalogo dei giochi presenti nel database.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="heroPagina">
            <p class="badgePagina">CATALOGO GIOCHI</p>

            <h2>Giochi del catalogo</h2>

            <p>
                Questa sezione mostra i giochi presenti nel database di GCdb, permettendo di <strong>cercarli</strong>, <strong>ordinarli</strong> ed eventualmente <strong>aggiungerli nei preferiti</strong> (dopo l'accesso).
            </p>
        </div>

        <div class="pannelloPagina">
            <h2>Cerca un gioco</h2>

            <form action="giochi.php" method="get">
                <p>
                    <label for="q">Cerca per titolo, anno, PEGI, genere, azienda o descrizione:</label>
                </p>

                <p>
                    <input class="barraRicerca" type="text" name="q" id="q"
                           value="<?php echo htmlspecialchars($ricerca); ?>" />

                    <input type="hidden" name="ordine" value="<?php echo htmlspecialchars($ordine); ?>" />

                    <input class="bottoneRicerca" type="submit" value="Cerca" />

                    <a class="bottoneReset" href="giochi.php">Mostra tutto</a>
                </p>
            </form>

            <?php
            if ($ricerca != "") {
                echo '<p class="risultatoRicerca">';
                echo 'Risultati per: <strong>' . htmlspecialchars($ricerca) . '</strong> ';
                echo '(' . $numeroGiochi . ' giochi trovati)';
                echo '</p>';
            } else {
                echo '<p class="risultatoRicerca">';
                echo 'Giochi presenti nel database: <strong>' . $numeroGiochi . '</strong>';
                echo '</p>';
            }
            ?>
        </div>

        <div class="pannelloPagina">
            <h2>Ordina i giochi</h2>

            <p>
                <a class="bottoneOrdine" href="giochi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=nome_asc">Nome A-Z</a>
                <a class="bottoneOrdine" href="giochi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=nome_desc">Nome Z-A</a>
                <a class="bottoneOrdine" href="giochi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=anno_asc">Anno crescente</a>
                <a class="bottoneOrdine" href="giochi.php?q=<?php echo urlencode($ricerca); ?>&amp;ordine=anno_desc">Anno decrescente</a>
            </p>

            <p class="meta">
                Ordinamento attuale:
                <strong>
                    <?php
                    if ($ordine == "nome_desc") {
                        echo "nome Z-A";
                    } else if ($ordine == "anno_asc") {
                        echo "anno crescente";
                    } else if ($ordine == "anno_desc") {
                        echo "anno decrescente";
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
                echo "<h2>Tutti i giochi</h2>";
            } else {
                echo "<h2>Risultati della ricerca</h2>";
            }
            ?>

            <?php
            if (!$risultatoGiochi || $numeroGiochi == 0) {
            ?>

                <div class="box">
                    <h2>Nessun gioco trovato</h2>

                    <p>
                        La ricerca non ha prodotto risultati. Prova con un'altra parola
                        oppure torna alla visualizzazione completa dei giochi.
                    </p>
                </div>

            <?php
            } else {

                while ($gioco = mysqli_fetch_array($risultatoGiochi)) {
            ?>

                    <div class="card cardGiocoCatalogo">

                        <?php
                        if ($gioco["copertina"] != "" && file_exists("file/giochi/" . $gioco["copertina"])) {
                        ?>
                            <img class="copertinaCatalogo"
                                 src="file/giochi/<?php echo htmlspecialchars($gioco["copertina"]); ?>"
                                 alt="Copertina di <?php echo htmlspecialchars($gioco["titolo"]); ?>" />
                        <?php
                        }
                        ?>

                        <h3><?php echo htmlspecialchars($gioco["titolo"]); ?></h3>

                        <p class="meta">
                            <strong>Anno:</strong>
                            <?php echo htmlspecialchars($gioco["anno"]); ?>

                            |

                            <strong>PEGI:</strong>
                            <?php
                            if ($gioco["codice_pegi"] != "") {
                                echo htmlspecialchars($gioco["codice_pegi"]);
                            } else {
                                echo "Non specificato";
                            }
                            ?>
                        </p>

                        <p>
                            <?php echo htmlspecialchars($gioco["descrizione"]); ?>
                        </p>

                        <p class="tagGioco">
                            <strong>Generi:</strong>
                            <?php
                            if ($gioco["generi_collegati"] != "") {
                                echo htmlspecialchars($gioco["generi_collegati"]);
                            } else {
                                echo "Non specificati";
                            }
                            ?>
                        </p>

                        <div class="boxInterno aziendeCollegateGioco">
                            <strong>Aziende collegate:</strong>

                            <p>
                                <?php
                                if ($gioco["aziende_collegate"] != "") {
                                    echo htmlspecialchars($gioco["aziende_collegate"]);
                                } else {
                                    echo "Nessuna azienda collegata.";
                                }
                                ?>
                            </p>
                        </div>

                        <?php
                        if (isset($_SESSION["id_utente"])) {
                        ?>
                            <form action="galleria.php" method="post">
                                <p>
                                    <input type="hidden"
                                        name="id_gioco"
                                        value="<?php echo $gioco["id"]; ?>" />

                                    <input class="bottone"
                                        type="submit"
                                        name="aggiungi_gioco"
                                        value="Aggiungi alla mia galleria" />
                                </p>
                            </form>
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

<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

require_once("includes/connection.php");

$idUtente = 0;

if (isset($_GET["id_utente"])) {
    $idUtente = intval($_GET["id_utente"]);
}

if (isset($_SESSION["id_utente"]) && $idUtente == $_SESSION["id_utente"]) {
    header("Location: galleria.php");
    exit();
}

$sqlUtente = "SELECT utenti.id,
                     utenti.username,
                     utenti.data_registrazione
              FROM utenti
              WHERE utenti.id = $idUtente";

$risultatoUtente = mysqli_query($conn, $sqlUtente);
$utente = false;

if ($risultatoUtente && mysqli_num_rows($risultatoUtente) > 0) {
    $utente = mysqli_fetch_array($risultatoUtente);
}

$risultatoGalleria = false;
$numeroGiochi = 0;
$risultatoGeneriPreferiti = false;
$numeroGeneriPreferiti = 0;

if ($utente) {
    $sqlGeneriPreferiti = "SELECT generi.id,
                                  generi.nome,
                                  generi.descrizione,
                                  generi.tag
                           FROM generi_preferiti
                           INNER JOIN generi ON generi_preferiti.id_genere = generi.id
                           WHERE generi_preferiti.id_utente = $idUtente
                           ORDER BY generi.nome";

    $risultatoGeneriPreferiti = mysqli_query($conn, $sqlGeneriPreferiti);

    if ($risultatoGeneriPreferiti) {
        $numeroGeneriPreferiti = mysqli_num_rows($risultatoGeneriPreferiti);
    }

    $sqlGalleria = "SELECT giochi.id,
                           giochi.titolo,
                           giochi.anno,
                           giochi.descrizione,
                           giochi.copertina,
                           pegi.codice AS codice_pegi,
                           galleria.voto,
                           galleria.recensione,
                           galleria.data_aggiunta,
                           GROUP_CONCAT(DISTINCT generi.nome ORDER BY generi.nome SEPARATOR ', ') AS generi_collegati,
                           GROUP_CONCAT(DISTINCT CONCAT(aziende.nome, ' - ', gioco_azienda.ruolo)
                                        ORDER BY aziende.nome
                                        SEPARATOR ', ') AS aziende_collegate
                    FROM galleria
                    INNER JOIN giochi ON galleria.id_gioco = giochi.id
                    LEFT JOIN pegi ON giochi.id_pegi = pegi.id
                    LEFT JOIN gioco_genere ON giochi.id = gioco_genere.id_gioco
                    LEFT JOIN generi ON gioco_genere.id_genere = generi.id
                    LEFT JOIN gioco_azienda ON giochi.id = gioco_azienda.id_gioco
                    LEFT JOIN aziende ON gioco_azienda.id_azienda = aziende.id
                    WHERE galleria.id_utente = $idUtente
                    GROUP BY giochi.id,
                             giochi.titolo,
                             giochi.anno,
                             giochi.descrizione,
                             giochi.copertina,
                             pegi.codice,
                             galleria.voto,
                             galleria.recensione,
                             galleria.data_aggiunta
                    ORDER BY galleria.data_aggiunta DESC";

    $risultatoGalleria = mysqli_query($conn, $sqlGalleria);

    if ($risultatoGalleria) {
        $numeroGiochi = mysqli_num_rows($risultatoGalleria);
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Galleria utente</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Visualizzazione pubblica della galleria di un utente.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <?php
        if (!$utente) {
        ?>

            <div class="heroPagina">
                <p class="badgePagina">GALLERIA UTENTE</p>

                <h2>Utente non trovato</h2>

                <p>
                    L'utente richiesto non è presente nel database.
                </p>
            </div>

            <div class="pannelloPagina">
                <p>
                    <a class="bottone" href="utenti.php">Torna alle gallerie utenti</a>
                </p>
            </div>

        <?php
        } else {
        ?>

            <div class="heroPagina">
                <p class="badgePagina">GALLERIA UTENTE</p>

                <h2>Galleria di <?php echo htmlspecialchars($utente["username"]); ?></h2>

                <p>
                    Questa pagina mostra i giochi salvati dall'utente selezionato.
                    La galleria è consultabile in sola lettura.
                </p>

                <p>
                    Giochi presenti:
                    <strong><?php echo $numeroGiochi; ?></strong>
                </p>
            </div>

            <div class="pannelloPagina">
                <h2>Dati utente</h2>

                <p>
                    Username:
                    <strong><?php echo htmlspecialchars($utente["username"]); ?></strong>
                </p>

                <p>
                    Data registrazione:
                    <strong><?php echo htmlspecialchars($utente["data_registrazione"]); ?></strong>
                </p>

                <div class="boxInterno">
                    <h4>Generi preferiti</h4>

                    <?php
                    if ($risultatoGeneriPreferiti && mysqli_num_rows($risultatoGeneriPreferiti) > 0) {

                        while ($generePreferito = mysqli_fetch_array($risultatoGeneriPreferiti)) {
                    ?>

                            <p class="tagGenere">
                                <?php echo htmlspecialchars($generePreferito["nome"]); ?>
                                <?php
                                if ($generePreferito["tag"] != "") {
                                    echo " - " . htmlspecialchars($generePreferito["tag"]);
                                }
                                ?>
                            </p>

                    <?php
                        }
                    } else {
                    ?>

                        <p>
                            Questo utente non ha ancora aggiunto generi preferiti.
                        </p>

                    <?php
                    }
                    ?>
                </div>

                <p>
                    <a class="bottoneReset" href="utenti.php">Torna alle gallerie utenti</a>
                </p>
            </div>

            <div class="pannelloPagina">
                <h2>Giochi nella galleria</h2>

                <?php
                if ($risultatoGalleria && mysqli_num_rows($risultatoGalleria) > 0) {

                    while ($gioco = mysqli_fetch_array($risultatoGalleria)) {
                ?>

                        <div class="card cardGalleria">

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
                                Anno: <?php echo htmlspecialchars($gioco["anno"]); ?>
                                |
                                PEGI: <?php echo htmlspecialchars($gioco["codice_pegi"]); ?>
                            </p>

                            <p>
                                <?php echo htmlspecialchars($gioco["descrizione"]); ?>
                            </p>

                            <?php
                            if ($gioco["generi_collegati"] != "") {
                            ?>
                                <p class="tagGioco">
                                    Generi: <?php echo htmlspecialchars($gioco["generi_collegati"]); ?>
                                </p>
                            <?php
                            }
                            ?>

                            <?php
                            if ($gioco["aziende_collegate"] != "") {
                            ?>
                                <div class="boxInterno aziendeCollegateGioco">
                                    <strong>Aziende collegate:</strong>

                                    <p>
                                        <?php echo htmlspecialchars($gioco["aziende_collegate"]); ?>
                                    </p>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="boxInterno boxValutazioneGalleria">
                                <h4>Valutazione dell'utente</h4>

                                <p>
                                    Voto:
                                    <strong>
                                        <?php
                                        if ($gioco["voto"] == NULL) {
                                            echo "Non assegnato";
                                        } else {
                                            echo htmlspecialchars($gioco["voto"]) . "/10";
                                        }
                                        ?>
                                    </strong>
                                </p>

                                <p>
                                    Recensione:
                                    <strong>
                                        <?php
                                        if (trim($gioco["recensione"]) == "") {
                                            echo "Nessuna recensione inserita";
                                        } else {
                                            echo htmlspecialchars($gioco["recensione"]);
                                        }
                                        ?>
                                    </strong>
                                </p>

                                <p class="meta">
                                    Aggiunto il: <?php echo htmlspecialchars($gioco["data_aggiunta"]); ?>
                                </p>
                            </div>

                            <div class="clear"></div>
                        </div>

                <?php
                    }
                } else {
                ?>

                    <div class="box">
                        <h3>Galleria vuota</h3>

                        <p>
                            Questo utente non ha ancora aggiunto giochi alla propria galleria.
                        </p>
                    </div>

                <?php
                }
                ?>

                <div class="clear"></div>
            </div>

        <?php
        }
        ?>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

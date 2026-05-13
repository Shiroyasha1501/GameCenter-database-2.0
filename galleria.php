<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

require_once("includes/connection.php");

if (!isset($_SESSION["id_utente"])) {
    header("Location: accesso.php");
    exit();
}

$idUtente = $_SESSION["id_utente"];

$messaggio = "";
$errore = "";

$ordine = "data";

if (isset($_GET["ordine"])) {
    $ordine = $_GET["ordine"];
}

if ($ordine == "nome_asc") {
    $orderBy = "giochi.titolo ASC";
} else if ($ordine == "nome_desc") {
    $orderBy = "giochi.titolo DESC";
} else if ($ordine == "voto_alto") {
    $orderBy = "galleria.voto DESC, giochi.titolo ASC";
} else if ($ordine == "voto_basso") {
    $orderBy = "galleria.voto ASC, giochi.titolo ASC";
} else {
    $ordine = "data";
    $orderBy = "galleria.data_aggiunta DESC";
}

if (isset($_POST["aggiungi_gioco"])) {

    $idGioco = intval($_POST["id_gioco"]);

    if ($idGioco > 0) {

        $sql = "INSERT IGNORE INTO galleria (id_utente, id_gioco, voto, recensione, data_aggiunta)
                VALUES ($idUtente, $idGioco, NULL, '', NOW())";

        if (mysqli_query($conn, $sql)) {

            if (mysqli_affected_rows($conn) > 0) {
                $messaggio = "Gioco aggiunto alla tua galleria.";
            } else {
                $messaggio = "Questo gioco era già presente nella tua galleria.";
            }

        } else {
            $errore = "Errore durante l'aggiunta del gioco alla galleria.";
        }

    } else {
        $errore = "Gioco non valido.";
    }
}

if (isset($_POST["salva"])) {

    $idGioco = intval($_POST["id_gioco"]);
    $voto = trim($_POST["voto"]);
    $recensione = mysqli_real_escape_string($conn, trim($_POST["recensione"]));

    if ($voto == "") {
        $votoSql = "NULL";
    } else {
        $voto = intval($voto);

        if ($voto < 1 || $voto > 10) {
            $errore = "Il voto deve essere compreso tra 1 e 10.";
        }

        $votoSql = $voto;
    }

    if ($errore == "" && $idGioco > 0) {

        $sql = "UPDATE galleria
                SET voto = $votoSql,
                    recensione = '$recensione'
                WHERE id_utente = $idUtente
                  AND id_gioco = $idGioco";

        if (mysqli_query($conn, $sql)) {
            $messaggio = "Galleria aggiornata correttamente.";
        } else {
            $errore = "Errore durante l'aggiornamento della galleria.";
        }
    }
}

if (isset($_POST["rimuovi"])) {

    $idGioco = intval($_POST["id_gioco"]);

    if ($idGioco > 0) {

        $sql = "DELETE FROM galleria
                WHERE id_utente = $idUtente
                  AND id_gioco = $idGioco";

        if (mysqli_query($conn, $sql)) {
            $messaggio = "Gioco rimosso dalla galleria.";
        } else {
            $errore = "Errore durante la rimozione del gioco.";
        }
    }
}

if (isset($_POST["aggiungi_genere"])) {

    $idGenere = intval($_POST["id_genere"]);

    if ($idGenere > 0) {

        $sql = "INSERT IGNORE INTO generi_preferiti (id_utente, id_genere, data_aggiunta)
                VALUES ($idUtente, $idGenere, NOW())";

        if (mysqli_query($conn, $sql)) {

            if (mysqli_affected_rows($conn) > 0) {
                $messaggio = "Genere aggiunto ai preferiti.";
            } else {
                $messaggio = "Questo genere era già tra i tuoi preferiti.";
            }

        } else {
            $errore = "Errore durante l'aggiunta del genere preferito.";
        }
    }
}

if (isset($_POST["rimuovi_genere"])) {

    $idGenere = intval($_POST["id_genere"]);

    if ($idGenere > 0) {

        $sql = "DELETE FROM generi_preferiti
                WHERE id_utente = $idUtente
                  AND id_genere = $idGenere";

        if (mysqli_query($conn, $sql)) {
            $messaggio = "Genere rimosso dai preferiti.";
        } else {
            $errore = "Errore durante la rimozione del genere preferito.";
        }
    }
}

$sqlGeneriDisponibili = "SELECT generi.id, generi.nome
                         FROM generi
                         WHERE generi.id NOT IN (
                             SELECT id_genere
                             FROM generi_preferiti
                             WHERE id_utente = $idUtente
                         )
                         ORDER BY generi.nome";

$risultatoGeneriDisponibili = mysqli_query($conn, $sqlGeneriDisponibili);

$sqlGeneriPreferiti = "SELECT generi.id,
                              generi.nome,
                              generi.descrizione,
                              generi.tag,
                              generi_preferiti.data_aggiunta
                       FROM generi_preferiti
                       INNER JOIN generi ON generi_preferiti.id_genere = generi.id
                       WHERE generi_preferiti.id_utente = $idUtente
                       ORDER BY generi.nome";

$risultatoGeneriPreferiti = mysqli_query($conn, $sqlGeneriPreferiti);

$numeroGeneriPreferiti = 0;

if ($risultatoGeneriPreferiti) {
    $numeroGeneriPreferiti = mysqli_num_rows($risultatoGeneriPreferiti);
}

$sql = "SELECT giochi.id,
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
        ORDER BY $orderBy";

$risultatoGalleria = mysqli_query($conn, $sql);

$numeroGiochi = 0;

if ($risultatoGalleria) {
    $numeroGiochi = mysqli_num_rows($risultatoGalleria);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - La mia galleria</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Gestisci la tua galleria personale di giochi e generi preferiti.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="heroPagina">
            <p class="badgePagina">AREA PERSONALE</p>

            <h2>La mia galleria</h2>

            <p>
                In questa sezione puoi <strong>visualizzare i giochi che hai aggiunto
                alla tua raccolta personale</strong>, <strong>assegnare un voto</strong>, <strong>scrivere una recensione</strong>
                e <strong>indicare i tuoi generi preferiti</strong>.
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
            <h2>Riepilogo</h2>

            <p>
                Utente:
                <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>
            </p>

            <p>
                Giochi presenti nella tua galleria:
                <strong><?php echo $numeroGiochi; ?></strong>
            </p>

            <p>
                Generi preferiti:
                <strong><?php echo $numeroGeneriPreferiti; ?></strong>
            </p>

            <p>
                <a class="bottone" href="giochi.php">Aggiungi altri giochi dal catalogo</a>
            </p>
        </div>

        <div class="pannelloPagina">
            <h2>I miei generi preferiti</h2>

            <form action="galleria.php" method="post">
                <p>
                    <label for="id_genere">Aggiungi un genere preferito:</label>
                </p>

                <p>
                    <select class="selectGenerePreferito" name="id_genere" id="id_genere">
                        <?php
                        if ($risultatoGeneriDisponibili && mysqli_num_rows($risultatoGeneriDisponibili) > 0) {

                            while ($genereDisponibile = mysqli_fetch_array($risultatoGeneriDisponibili)) {
                                echo '<option value="' . $genereDisponibile["id"] . '">';
                                echo htmlspecialchars($genereDisponibile["nome"]);
                                echo '</option>';
                            }

                        } else {
                            echo '<option value="0">Nessun genere disponibile</option>';
                        }
                        ?>
                    </select>

                    <input class="bottoneSalvaGalleria"
                           type="submit"
                           name="aggiungi_genere"
                           value="Aggiungi genere" />
                </p>
            </form>

            <?php
            if (!$risultatoGeneriPreferiti || $numeroGeneriPreferiti == 0) {
            ?>

                <p>
                    Non hai ancora selezionato generi preferiti.
                </p>

            <?php
            } else {
            ?>

                <div class="listaGeneriPreferiti">

                    <?php
                    while ($generePreferito = mysqli_fetch_array($risultatoGeneriPreferiti)) {
                    ?>

                        <div class="card cardGenerePreferito">
                            <h3><?php echo htmlspecialchars($generePreferito["nome"]); ?></h3>

                            <p>
                                <?php echo htmlspecialchars($generePreferito["descrizione"]); ?>
                            </p>

                            <p class="tagGenere">
                                <?php echo htmlspecialchars($generePreferito["tag"]); ?>
                            </p>

                            <form action="galleria.php" method="post">
                                <p>
                                    <input type="hidden" name="id_genere"
                                           value="<?php echo $generePreferito["id"]; ?>" />

                                    <input class="bottoneRimuoviGalleria"
                                           type="submit"
                                           name="rimuovi_genere"
                                           value="Rimuovi genere" />
                                </p>
                            </form>
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

        <div class="pannelloPagina">
            <h2>Ordina la galleria</h2>

            <p>
                <a class="bottoneOrdine" href="galleria.php?ordine=data">Più recenti</a>
                <a class="bottoneOrdine" href="galleria.php?ordine=nome_asc">Nome gioco A-Z</a>
                <a class="bottoneOrdine" href="galleria.php?ordine=nome_desc">Nome gioco Z-A</a>
                <a class="bottoneOrdine" href="galleria.php?ordine=voto_alto">Voto più alto</a>
                <a class="bottoneOrdine" href="galleria.php?ordine=voto_basso">Voto più basso</a>
            </p>

            <p class="meta">
                Ordinamento attuale:
                <strong>
                    <?php
                    if ($ordine == "nome_asc") {
                        echo "nome gioco A-Z";
                    } else if ($ordine == "nome_desc") {
                        echo "nome gioco Z-A";
                    } else if ($ordine == "voto_alto") {
                        echo "voto più alto";
                    } else if ($ordine == "voto_basso") {
                        echo "voto più basso";
                    } else {
                        echo "più recenti";
                    }
                    ?>
                </strong>
            </p>
        </div>

        <div class="pannelloPagina">

            <h2>I miei giochi</h2>

            <?php
            if (!$risultatoGalleria || $numeroGiochi == 0) {
            ?>

                <div class="box">
                    <h2>Galleria vuota</h2>

                    <p>
                        Non hai ancora aggiunto giochi alla tua galleria personale.
                        Vai nella pagina dei giochi e usa il pulsante
                        <strong>Aggiungi alla mia galleria</strong>.
                    </p>

                    <p>
                        <a class="bottone" href="giochi.php">Vai al catalogo giochi</a>
                    </p>
                </div>

            <?php
            } else {

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

                        <div class="boxInterno boxValutazioneGalleria">
                            <h4>La tua valutazione</h4>

                            <form action="galleria.php?ordine=<?php echo htmlspecialchars($ordine); ?>" method="post">
                                <p>
                                    <input type="hidden" name="id_gioco"
                                           value="<?php echo $gioco["id"]; ?>" />

                                    <label for="voto<?php echo $gioco["id"]; ?>">Voto da 1 a 10:</label>
                                    <input class="campoVotoGalleria"
                                           type="text"
                                           name="voto"
                                           id="voto<?php echo $gioco["id"]; ?>"
                                           value="<?php echo htmlspecialchars($gioco["voto"]); ?>" />
                                </p>

                                <p>
                                    <label for="recensione<?php echo $gioco["id"]; ?>">Recensione personale:</label>
                                </p>

                                <p>
                                    <textarea class="campoRecensioneGalleria"
                                              name="recensione"
                                              id="recensione<?php echo $gioco["id"]; ?>"
                                              rows="4"
                                              cols="40"><?php echo htmlspecialchars($gioco["recensione"]); ?></textarea>
                                </p>

                                <p>
                                    <input class="bottoneSalvaGalleria"
                                           type="submit"
                                           name="salva"
                                           value="Salva valutazione" />

                                    <input class="bottoneRimuoviGalleria"
                                           type="submit"
                                           name="rimuovi"
                                           value="Rimuovi dalla galleria" />
                                </p>
                            </form>
                        </div>

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

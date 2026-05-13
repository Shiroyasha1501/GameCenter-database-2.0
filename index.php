<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

require_once("includes/connection.php");

$sqlGiocoHome = "SELECT giochi.id,
                        giochi.titolo,
                        giochi.anno,
                        giochi.descrizione,
                        giochi.copertina,
                        pegi.codice AS codice_pegi,
                        GROUP_CONCAT(DISTINCT generi.nome ORDER BY generi.nome SEPARATOR ', ') AS generi_collegati
                 FROM giochi
                 LEFT JOIN pegi ON giochi.id_pegi = pegi.id
                 LEFT JOIN gioco_genere ON giochi.id = gioco_genere.id_gioco
                 LEFT JOIN generi ON gioco_genere.id_genere = generi.id
                 GROUP BY giochi.id, giochi.titolo, giochi.anno, giochi.descrizione, giochi.copertina, pegi.codice
                 ORDER BY RAND()
                 LIMIT 1";

$risultatoGiocoHome = mysqli_query($conn, $sqlGiocoHome);
$giocoHome = false;

if ($risultatoGiocoHome && mysqli_num_rows($risultatoGiocoHome) > 0) {
    $giocoHome = mysqli_fetch_array($risultatoGiocoHome);
}

$sqlAziendaHome = "SELECT aziende.id,
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
                   ORDER BY RAND()
                   LIMIT 1";

$risultatoAziendaHome = mysqli_query($conn, $sqlAziendaHome);
$aziendaHome = false;

if ($risultatoAziendaHome && mysqli_num_rows($risultatoAziendaHome) > 0) {
    $aziendaHome = mysqli_fetch_array($risultatoAziendaHome);
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Home</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Un archivio dinamico dedicato a giochi, generi, aziende, utenti e recensioni';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="box">
            <h2>Benvenuto</h2>

            <p>
                Questo sito raccoglie informazioni sui <strong>videogiochi</strong> e su diversi
                <strong>concetti importanti del settore</strong>, come generi, aziende,
                sistemi di classificazione e raccolte personali degli utenti.
            </p>

            <p>
                L'obiettivo del progetto è costruire un piccolo archivio web chiaro,
                ordinato e facilmente estendibile, realizzato con <strong>PHP</strong>
                e <strong>MySQL</strong>.
            </p>

            <p>
                I contenuti principali vengono letti dal database e possono cambiare
                dinamicamente a ogni caricamento della pagina.
            </p>
        </div>

        <div class="box">
            <h2>Gioco in evidenza</h2>

            <?php
            if ($giocoHome) {
            ?>

                <div class="schedaGioco">

                    <?php
                    if ($giocoHome["copertina"] != "" && file_exists("file/giochi/" . $giocoHome["copertina"])) {
                    ?>
                        <img class="copertina"
                             src="file/giochi/<?php echo htmlspecialchars($giocoHome["copertina"]); ?>"
                             alt="Copertina di <?php echo htmlspecialchars($giocoHome["titolo"]); ?>"
                             width="120" />
                    <?php
                    }
                    ?>

                    <h3><?php echo htmlspecialchars($giocoHome["titolo"]); ?></h3>

                    <p class="meta">
                        <strong>Anno:</strong>
                        <?php echo htmlspecialchars($giocoHome["anno"]); ?>

                        |

                        <strong>PEGI:</strong>
                        <?php
                        if ($giocoHome["codice_pegi"] != "") {
                            echo htmlspecialchars($giocoHome["codice_pegi"]);
                        } else {
                            echo "Non specificato";
                        }
                        ?>
                    </p>

                    <p class="meta">
                        <strong>Generi:</strong>
                        <?php
                        if ($giocoHome["generi_collegati"] != "") {
                            echo htmlspecialchars($giocoHome["generi_collegati"]);
                        } else {
                            echo "Non specificati";
                        }
                        ?>
                    </p>

                    <p>
                        <?php echo htmlspecialchars($giocoHome["descrizione"]); ?>
                    </p>

                    <p>
                        <a class="bottone" href="giochi.php">Vai alla pagina giochi</a>
                    </p>

                    <div class="clear"></div>
                </div>

            <?php
            } else {
            ?>

                <p>
                    Nessun gioco disponibile. Controlla di aver eseguito correttamente
                    l'installazione del database.
                </p>

            <?php
            }
            ?>

        </div>

        <div class="box">
            <h2>Azienda in evidenza</h2>

            <?php
            if ($aziendaHome) {
            ?>

                <div class="schedaGioco">

                    <?php
                    if ($aziendaHome["logo"] != "" && file_exists("file/aziende/" . $aziendaHome["logo"])) {
                    ?>
                        <img class="logoAziendaHome"
                             src="file/aziende/<?php echo htmlspecialchars($aziendaHome["logo"]); ?>"
                             alt="Logo di <?php echo htmlspecialchars($aziendaHome["nome"]); ?>" />
                    <?php
                    }
                    ?>

                    <h3><?php echo htmlspecialchars($aziendaHome["nome"]); ?></h3>

                    <p class="meta">
                        <strong>Paese:</strong>
                        <?php
                        if ($aziendaHome["paese"] != "") {
                            echo htmlspecialchars($aziendaHome["paese"]);
                        } else {
                            echo "Non specificato";
                        }
                        ?>

                        |

                        <strong>Giochi collegati:</strong>
                        <?php echo htmlspecialchars($aziendaHome["numero_giochi"]); ?>
                    </p>

                    <p>
                        <?php echo htmlspecialchars($aziendaHome["descrizione"]); ?>
                    </p>

                    <?php
                    if ($aziendaHome["giochi_collegati"] != "") {
                    ?>
                        <p class="meta">
                            <strong>Collegamenti:</strong>
                            <?php echo htmlspecialchars($aziendaHome["giochi_collegati"]); ?>
                        </p>
                    <?php
                    }
                    ?>

                    <p>
                        <a class="bottone" href="aziende.php">Vai alla pagina aziende</a>
                    </p>

                    <div class="clear"></div>
                </div>

            <?php
            } else {
            ?>

                <p>
                    Nessuna azienda disponibile. Controlla di aver eseguito correttamente
                    l'installazione del database.
                </p>

            <?php
            }
            ?>

        </div>

        <div class="box">
            <h2>Termini utili</h2>

            <p>
                Nel sito incontrerai parole come
                <span class="help"><strong>PEGI</strong>[?]
                    <span class="spiegazione">
                        Sistema europeo di classificazione dei videogiochi in base all'età consigliata.
                    </span>
                </span>,
                <span class="help"><strong>DLC</strong>[?]
                    <span class="spiegazione">
                        Contenuto aggiuntivo scaricabile che amplia o modifica il gioco base.
                    </span>
                </span>
                e
                <span class="help"><strong>Patch</strong>[?]
                    <span class="spiegazione">
                        Aggiornamento software che corregge errori o modifica aspetti del gioco.
                    </span>
                </span>.
            </p>

            <p>
                Per una spiegazione più completa puoi visitare la pagina
                <a title="Dai una letta al glossario" href="glossario.php"><strong>Glossario</strong></a>.
            </p>
        </div>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

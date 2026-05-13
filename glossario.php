<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

require_once("dati/glossario_dati.php");

$ricerca = "";
$terminiFiltrati = array();

if (isset($_GET["q"])) {
    $ricerca = trim($_GET["q"]);
}

for ($i = 0; $i < count($terminiGlossario); $i++) {

    $termine = $terminiGlossario[$i]["termine"];
    $definizione = $terminiGlossario[$i]["definizione"];

    if ($ricerca == "") {
        $terminiFiltrati[] = $terminiGlossario[$i];
    } else {
        if (
            stripos($termine, $ricerca) !== false ||
            stripos($definizione, $ricerca) !== false
        ) {
            $terminiFiltrati[] = $terminiGlossario[$i];
        }
    }
}

$sezioni = array("A-D", "E-L", "M-R", "S-Z");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Glossario</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Le parole fondamentali del sapere videoludico';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="paginaSacra">

            <div class="liberHeroGlossario">
                <div class="sigilloLiber">✦</div>

                <p class="formulaSacra">
                    <em>"A colui che attraversa i mondi digitali, siano svelate le parole del codice ludico."</em>
                </p>

                <h2 class="titoloSacrale">Liber Terminorum Ludicorum</h2>

                <p class="introSacra">
                    In questa pagina sono custoditi i <strong>termini fondamentali</strong>
                    del linguaggio videoludico. Ogni voce cela un significato:
                    passa con il cursore sopra il termine e la definizione si rivelerà.
                </p>
            </div>

            <div class="pannelloPagina">
                <h2>Ricerca nel Liber</h2>

                <form action="glossario.php" method="get">
                    <p>
                        <label for="q">Cerca un termine o una parola nella definizione:</label>
                    </p>

                    <p>
                        <input class="barraRicerca" type="text" name="q" id="q"
                               value="<?php echo htmlspecialchars($ricerca); ?>" />

                        <input class="bottoneRicerca" type="submit" value="Cerca" />

                        <a class="bottoneReset" href="glossario.php">Mostra tutto</a>
                    </p>
                </form>

                <div class="indiceGlossario">
                    <a href="#sezioneAD"><strong>A-D</strong></a>
                    <a href="#sezioneEL"><strong>E-L</strong></a>
                    <a href="#sezioneMR"><strong>M-R</strong></a>
                    <a href="#sezioneSZ"><strong>S-Z</strong></a>
                </div>

                <?php
                if ($ricerca != "") {
                    echo '<p class="risultatoRicerca">';
                    echo 'Risultati per: <strong>' . htmlspecialchars($ricerca) . '</strong> ';
                    echo '(' . count($terminiFiltrati) . ' termini trovati)';
                    echo '</p>';
                }
                ?>
            </div>

            <?php
            if (count($terminiFiltrati) == 0) {
            ?>

                <div class="sezioneGlossario">
                    <h3 class="letteraSacra">Nessun termine trovato</h3>

                    <p class="introSacra">
                        La ricerca non ha prodotto risultati. Prova con un'altra parola
                        oppure torna alla visualizzazione completa del Liber.
                    </p>
                </div>

            <?php
            } else {

                for ($s = 0; $s < count($sezioni); $s++) {

                    $sezioneCorrente = $sezioni[$s];
                    $idSezione = "";

                    if ($sezioneCorrente == "A-D") {
                        $idSezione = "sezioneAD";
                    } else if ($sezioneCorrente == "E-L") {
                        $idSezione = "sezioneEL";
                    } else if ($sezioneCorrente == "M-R") {
                        $idSezione = "sezioneMR";
                    } else if ($sezioneCorrente == "S-Z") {
                        $idSezione = "sezioneSZ";
                    }

                    $haTermini = false;

                    for ($i = 0; $i < count($terminiFiltrati); $i++) {
                        if ($terminiFiltrati[$i]["sezione"] == $sezioneCorrente) {
                            $haTermini = true;
                        }
                    }

                    if ($haTermini) {
            ?>

                        <div class="sezioneGlossario" id="<?php echo $idSezione; ?>">
                            <h3 class="letteraSacra"><?php echo $sezioneCorrente; ?></h3>

                            <?php
                            for ($i = 0; $i < count($terminiFiltrati); $i++) {

                                if ($terminiFiltrati[$i]["sezione"] == $sezioneCorrente) {
                            ?>

                                    <div class="card voceCardGlossario">
                                        <span class="help termineSacro">
                                            <strong><?php echo htmlspecialchars($terminiFiltrati[$i]["termine"]); ?></strong>

                                            <span class="spiegazione">
                                                <?php echo htmlspecialchars($terminiFiltrati[$i]["definizione"]); ?>
                                            </span>
                                        </span>

                                        <p class="sezioneVoceGlossario">
                                            <?php echo htmlspecialchars($terminiFiltrati[$i]["sezione"]); ?>
                                        </p>
                                    </div>

                            <?php
                                }
                            }
                            ?>

                            <div class="clear"></div>
                        </div>

            <?php
                    }
                }
            }
            ?>

            <p class="chiusuraSacra">
                <em>Ogni termine qui raccolto costituisce una chiave d'accesso al grande archivio del linguaggio videoludico.</em>
            </p>

        </div>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

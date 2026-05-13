<?php
session_start();

require_once("includes/tema.php");

error_reporting(0);
ini_set("display_errors", "0");

if (!isset($_SESSION["modalita_speciale"])) {
    header("Location: accesso.php");
    exit();
}

require_once("includes/connection.php");

$numeroDomande = 5;
$messaggio = "";
$quizConcluso = false;
$punteggio = 0;
$domandeQuiz = array();

if (isset($_GET["nuovo"])) {
    unset($_SESSION["domande_quiz"]);
}

if (!isset($_SESSION["domande_quiz"])) {

    $sql = "SELECT id
            FROM domande_minigioco
            ORDER BY RAND()
            LIMIT $numeroDomande";

    $risultato = mysqli_query($conn, $sql);

    $_SESSION["domande_quiz"] = array();

    if ($risultato) {
        while ($riga = mysqli_fetch_array($risultato)) {
            $_SESSION["domande_quiz"][] = $riga["id"];
        }
    }
}

if (isset($_POST["invia_quiz"])) {

    $quizConcluso = true;

    if (isset($_POST["risposta"])) {

        foreach ($_SESSION["domande_quiz"] as $idDomanda) {

            if (isset($_POST["risposta"][$idDomanda])) {

                $idOpzioneScelta = intval($_POST["risposta"][$idDomanda]);

                $sql = "SELECT corretta
                        FROM opzioni_minigioco
                        WHERE id = $idOpzioneScelta
                          AND id_domanda = $idDomanda";

                $risultato = mysqli_query($conn, $sql);

                if ($risultato && mysqli_num_rows($risultato) > 0) {
                    $riga = mysqli_fetch_array($risultato);

                    if ($riga["corretta"] == 1) {
                        $punteggio++;
                    }
                }
            }
        }
    }

    $messaggio = "Quiz completato: hai totalizzato " . $punteggio . " punti su " . count($_SESSION["domande_quiz"]) . ".";
}

if (isset($_SESSION["domande_quiz"]) && count($_SESSION["domande_quiz"]) > 0) {

    $listaId = implode(",", $_SESSION["domande_quiz"]);

    $sql = "SELECT id, testo, categoria
            FROM domande_minigioco
            WHERE id IN ($listaId)
            ORDER BY FIELD(id, $listaId)";

    $risultatoDomande = mysqli_query($conn, $sql);

    if ($risultatoDomande) {

        while ($domanda = mysqli_fetch_array($risultatoDomande)) {

            $idDomanda = $domanda["id"];

            $sqlOpzioni = "SELECT id, testo, corretta
                           FROM opzioni_minigioco
                           WHERE id_domanda = $idDomanda
                           ORDER BY id";

            $risultatoOpzioni = mysqli_query($conn, $sqlOpzioni);

            $opzioni = array();

            if ($risultatoOpzioni) {
                while ($opzione = mysqli_fetch_array($risultatoOpzioni)) {
                    $opzioni[] = $opzione;
                }
            }

            $domanda["opzioni"] = $opzioni;
            $domandeQuiz[] = $domanda;
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <title>GCdb - Minigioco</title>
    <?php require_once("includes/stili_tema.php"); ?>
</head>

<body>

<div id="contenitore">
    <?php
    $sottotitoloPagina = 'Area speciale: quiz sulle frasi celebri dei personaggi videoludici.';
    require_once("includes/header.php");
    ?>

    <?php require_once("includes/menu.php"); ?>

    <div id="contenuto">

        <div class="heroPagina">
            <p class="badgePagina">AREA SEGRETA</p>

            <h2>Minigioco GCdb</h2>

            <p>
                Questa sezione è accessibile solo dopo aver attivato la modalità speciale.
                Il quiz propone frasi celebri tratte dal mondo videoludico e chiede
                di riconoscere il personaggio corretto.
            </p>

        </div>

        <?php
        if ($messaggio != "") {
            if ($punteggio == count($_SESSION["domande_quiz"])) {
                echo '<p class="messaggioSuccesso">' . htmlspecialchars($messaggio) . ' Perfetto.</p>';
            } else {
                echo '<p class="messaggioAvviso">' . htmlspecialchars($messaggio) . '</p>';
            }
        }
        ?>

        <div class="pannelloPagina">

            <h2>Quiz personaggi</h2>

            <?php
            if (count($domandeQuiz) == 0) {
            ?>

                <div class="box">
                    <h2>Nessuna domanda disponibile</h2>

                    <p>
                        Non sono presenti domande nel database. Controlla che
                        <strong>install.php</strong> abbia inserito correttamente
                        i dati del minigioco.
                    </p>
                </div>

            <?php
            } else {
            ?>

                <form action="minigioco.php" method="post">

                    <?php
                    for ($i = 0; $i < count($domandeQuiz); $i++) {

                        $domanda = $domandeQuiz[$i];
                        $idDomanda = $domanda["id"];
                    ?>

                        <div class="card cardDomandaMinigioco">

                            <p class="numeroDomandaMinigioco">
                                Domanda <?php echo ($i + 1); ?>
                            </p>

                            <h3>
                                <?php echo htmlspecialchars($domanda["testo"]); ?>
                            </h3>

                            <p class="categoriaDomandaMinigioco">
                                Categoria:
                                <strong><?php echo htmlspecialchars($domanda["categoria"]); ?></strong>
                            </p>

                            <?php
                            for ($j = 0; $j < count($domanda["opzioni"]); $j++) {

                                $opzione = $domanda["opzioni"][$j];
                                $idOpzione = $opzione["id"];

                                $classeOpzione = "opzioneMinigioco";
                                $testoEsito = "";

                                if ($quizConcluso) {

                                    $opzioneScelta = false;

                                    if (isset($_POST["risposta"][$idDomanda]) && intval($_POST["risposta"][$idDomanda]) == $idOpzione) {
                                        $opzioneScelta = true;
                                    }

                                    if ($opzione["corretta"] == 1) {
                                        $classeOpzione = "opzioneMinigioco opzioneCorrettaMinigioco";
                                        $testoEsito = "Risposta corretta";
                                    } else if ($opzioneScelta) {
                                        $classeOpzione = "opzioneMinigioco opzioneErrataMinigioco";
                                        $testoEsito = "La tua risposta";
                                    }
                                }
                            ?>

                                <p class="<?php echo $classeOpzione; ?>">
                                    <input type="radio"
                                           name="risposta[<?php echo $idDomanda; ?>]"
                                           id="opzione<?php echo $idOpzione; ?>"
                                           value="<?php echo $idOpzione; ?>"
                                           <?php
                                           if ($quizConcluso) {
                                               echo 'disabled="disabled"';
                                           }
                                           ?> />

                                    <label for="opzione<?php echo $idOpzione; ?>">
                                        <?php echo htmlspecialchars($opzione["testo"]); ?>
                                    </label>

                                    <?php
                                    if ($testoEsito != "") {
                                        echo '<span class="esitoOpzioneMinigioco"> - ' . htmlspecialchars($testoEsito) . '</span>';
                                    }
                                    ?>
                                </p>

                            <?php
                            }
                            ?>

                        </div>

                    <?php
                    }
                    ?>

                    <?php
                    if (!$quizConcluso) {
                    ?>
                        <p class="azioniMinigioco">
                            <input class="bottoneInviaMinigioco"
                                   type="submit"
                                   name="invia_quiz"
                                   value="Invia risposte" />

                            <a class="bottoneNuovoMinigioco" href="minigioco.php?nuovo=1">Cambia domande</a>
                        </p>
                    <?php
                    } else {
                    ?>
                        <p class="azioniMinigioco">
                            <a class="bottoneInviaMinigioco" href="minigioco.php?nuovo=1">Nuovo quiz</a>
                            <a class="bottoneNuovoMinigioco" href="index.php">Torna alla home</a>
                        </p>
                    <?php
                    }
                    ?>

                </form>

            <?php
            }
            ?>

        </div>

    </div>
    <?php require_once("includes/footer.php"); ?>

</div>

</body>
</html>

<div id="sidebar">
    <h2>Menù</h2>

    <?php
    if (isset($_SESSION["id_utente"])) {
    ?>
        <div class="boxUtenteMenu">
            <p class="titoloUtenteMenu">Benvenuto</p>

            <p class="nomeUtenteMenu">
                <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </p>

            <p>
                <a class="linkAreaMenu" href="galleria.php">La mia galleria</a>
            </p>

            <p>
                <a class="linkLogoutMenu" href="logout.php">Fai logout</a>
            </p>
        </div>
    <?php
    } else if (isset($_SESSION["modalita_speciale"])) {
    ?>
        <div class="boxUtenteMenu boxSpecialeMenu">
            <p class="titoloUtenteMenu">Modalità speciale</p>

            <p class="nomeUtenteMenu">
                <?php echo htmlspecialchars($_SESSION["nome_speciale"]); ?>
            </p>

            <p>
                <a class="linkAreaMenu linkAreaSpecialeMenu" href="minigioco.php">Minigioco</a>
            </p>

            <p>
                <a class="linkLogoutMenu" href="logout.php">Fai logout</a>
            </p>
        </div>
    <?php
    }
    ?>

    <ul class="menu">
        <?php
            if (!isset($_SESSION["id_utente"]) && !isset($_SESSION["modalita_speciale"])) {
                echo '<li><a class="linkAccessoMenu" href="accesso.php" title="Accedi o registrati">Accedi o registrati</a></li>';
            }
        ?>

        <li>
            <a href="index.php" title="Vai alla schermata home">Home</a>
        </li>

        <li class="voceMenuTendina">
            <span class="titoloTendina">Cataloghi</span>

            <ul class="sottomenu">
                <li>
                    <a href="giochi.php" title="Dai un'occhiata ai giochi nel catalogo">Giochi</a>
                </li>

                <li>
                    <a href="generi.php" title="Dai un'occhiata ai generi nel catalogo">Generi</a>
                </li>

                <li>
                    <a href="aziende.php" title="Dai un'occhiata alle aziende nel catalogo">Aziende</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="utenti.php" title="Cerca gli utenti e guarda le loro gallerie">Gallerie utenti</a>
        </li>

        <li>
            <a href="glossario.php" title="Dai una letta al glossario">Glossario</a>
        </li>

        <li>
            <a href="info.php" title="Vuoi maggiori informazioni">Info</a>
        </li>

    </ul>
</div>

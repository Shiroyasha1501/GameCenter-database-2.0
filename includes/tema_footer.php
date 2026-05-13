<div class="temaFooter">
    <?php
    $parametriTema = $_GET;
    $parametriTema["cambia_tema"] = 1;
    $linkTema = $_SERVER["PHP_SELF"] . "?" . http_build_query($parametriTema);

    if (isset($_COOKIE["tema_gcdb"]) && $_COOKIE["tema_gcdb"] == "chiaro") {
        echo '<span>Tema attuale: Chiaro</span>';
        echo ' · ';
        echo '<a href="' . htmlspecialchars($linkTema) . '">Passa al tema scuro</a>';
    } else {
        echo '<span>Tema attuale: Scuro</span>';
        echo ' · ';
        echo '<a href="' . htmlspecialchars($linkTema) . '">Passa al tema chiaro</a>';
    }
    ?>
</div>

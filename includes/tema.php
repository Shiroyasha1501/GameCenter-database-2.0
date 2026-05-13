<?php
$temaCorrente = "scuro";

if (isset($_COOKIE["tema_gcdb"]) && $_COOKIE["tema_gcdb"] == "chiaro") {
    $temaCorrente = "chiaro";
}

if (isset($_GET["cambia_tema"])) {
    if ($temaCorrente == "chiaro") {
        $nuovoTema = "scuro";
    } else {
        $nuovoTema = "chiaro";
    }

    setcookie("tema_gcdb", $nuovoTema, time() + 60 * 60 * 24 * 30, "/");

    $paginaCorrente = $_SERVER["PHP_SELF"];
    $parametri = $_GET;
    unset($parametri["cambia_tema"]);

    if (count($parametri) > 0) {
        $paginaCorrente .= "?" . http_build_query($parametri);
    }

    header("Location: " . $paginaCorrente);
    exit();
}
?>

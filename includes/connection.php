<?php
require_once("includes/dati_generali.php");

$conn = mysqli_connect($host, $user, $password, $nome_db, $porta);

if (!$conn) {
    die("Errore di connessione al database: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>

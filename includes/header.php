<?php
if (!isset($sottotitoloPagina)) {
    $sottotitoloPagina = "";
}
?>

<div id="header">
    <h1>GameCenter database</h1>
    <h2>Dai videogiocatori per i videogiocatori</h2>
    <p class="sottotitolo">
        <?php echo htmlspecialchars($sottotitoloPagina); ?>
    </p>
</div>

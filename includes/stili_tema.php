<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" type="text/css" href="style.css" />

<?php
if (isset($_COOKIE["tema_gcdb"]) && $_COOKIE["tema_gcdb"] == "chiaro") {
    echo '<link rel="stylesheet" type="text/css" href="style_chiaro.css" />';
}
?>

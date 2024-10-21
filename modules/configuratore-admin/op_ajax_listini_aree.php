<?php

if (!$core)
    die ('Accesso diretto');


if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}
$this->noTemplateParse = true;

$ID = (int) $_POST['ID'];

echo '<button class="btn btn-info btn-small" onclick="listiniCategorie('. $ID .')">Categorie</button>';
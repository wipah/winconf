<?php

if (!$core)
    die("Accesso diretto");

if (!$categoria_ID = (int) $_GET['ID']) {
    echo 'Manca la categoria';
    return;
}

$query = 'DELETE FROM configuratore_categorie WHERE ID = ' . $categoria_ID;

if (!$db->query($query)) {
    echo 'Impossibile cancellare la categoria. ' . $query;
} else {
    echo 'Categoria cancellata. <br/>';
}

$query = 'DELETE FROM configuratore_opzioni_check_dimensioni WHERE categoria_ID = ' . $categoria_ID;
if (!$db->query($query)) {
    echo 'Impossibile cancellare il controllo dimensioni per opzione. ' . $query;
} else {
    echo 'Check dimensioni opzione cancellato. <br/>';
}

$query = 'DELETE FROM configuratore_opzioni_check_dipendenze WHERE categoria_ID = ' . $categoria_ID;
if (!$db->query($query)) {
    echo 'Impossibile cancellare il controllo dipendenze per opzione. ' . $query;
} else {
    echo 'Check dipendenze opzione cancellato. <br/>';
}

$query = 'DELETE FROM configuratore_step WHERE categoria_ID = ' . $categoria_ID;
if (!$db->query($query)) {
    echo 'Impossibile cancellare lo step. ' . $query;
} else {
    echo 'Step cancellato. <br/>';
}


$query = 'DELETE FROM configuratore_sottostep WHERE categoria_ID = ' . $categoria_ID;
if (!$db->query($query)) {
    echo 'Impossibile cancellare il sotto. ' . $query;
} else {
    echo 'Sottostep cancellato. <br/>';
}
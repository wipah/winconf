<?php

if (!$core)
    die ("Accesso diretto");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

if (!$categoria_ID = (int) $_GET['categoria_ID'])
    echo 'Categoria ID non passata';

$query = 'SELECT * 
          FROM configuratore_step 
          WHERE categoria_ID = ' . $categoria_ID . '
          ORDER BY ordine ASC;';

if (!$result = $db->query($query)) {
    echo 'Query error. ' . $query;
    return;
}

if (!$db->affected_rows){
    echo 'Nessuno step';
    return;
}

while ($row = mysqli_fetch_assoc($result)) {
    echo '<div>' . $row['sigla'] . '</div>';
}
<?php

if (!$core)
    die ("Direct access");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

$ID = (int) $_POST['ID'];
$sottostep_ID = (int) $_POST['sottostep_ID'];

echo '<h2>Editor dipendenze (Sottostep ID: ' . $sottostep_ID .' )</h2>';

$query = "SELECT * 
          FROM configuratore_opzioni_check_dipendenze
          WHERE sottostep_ID = $sottostep_ID;";

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Nessuna dipendenza trovata.';
} else {
    echo '<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Opzione</th>
                <th></th>
            </tr>
        </thead>
        <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
            <td>' . $row['ID'] . '</td>
            <td></td>
            <td></td>
          </tr>';
    }
    echo '</tbody></table>';
}

echo '<span class="btn btn-default" onclick="dipendenzeEditor('. $sottostep_ID . ', 0)">Nuova dipendenza</span>';
<?php
if (!$core)
    die("Accesso diretto non consentito");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

if (!$sottostep_ID = (int) $_POST['sottostep_ID'] ) {
    echo 'Manca l\'ID dello step';
}

$query = 'SELECT * 
          FROM configuratore_opzioni
          WHERE sottostep_ID = ' . $sottostep_ID . ' 
          ORDER BY ordine ASC';

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

echo '<h2 class="mt-3">Editor opzioni</h2>';


if (!$db->affected_rows) {
    echo 'Nessuna opzione scelta';
} else {
    echo '<table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sigla</th>
                    <th>Check dipendenze</th>
                    <th>Check dimensioni</th>
                    <th>Ordine</th>
                    <th>Operazioni</th>
                </tr>
            </thead>
            <tbody>';
    while ($rowOpzioni = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $rowOpzioni['ID'] . '</td>
                <td>' . $rowOpzioni['opzione_nome'] . '</td>
                <td>' . $rowOpzioni['opzione_sigla'] . '</td>
                <td>' . $rowOpzioni['check_dipendenze'] . ' <br/><span onclick="mostraDipendenze(' . $sottostep_ID . ',' . $rowOpzioni['ID'] . ');">Editor dipendenze</span></td>
                <td>' . $rowOpzioni['check_dimensioni'] . '<br>Editor dimensioni</td>
                <td> Sopra | Sotto </td>
                <td> Modifica opzione | Elimina opzione</td>
              </tr>  ';
    }
    echo '</tbody>
    </table>';

}

echo '<span class="btn btn-info" onclick="opzioniEditor(' . $sottostep_ID . ',0);">Aggiungi opzione</span>';
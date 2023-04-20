<?php
if (!$core)
    die("Accesso diretto non consentito");


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
    while ($rowOpzioni = mysqli_fetch_assoc($result)) {
        echo '<div>' . $rowOpzioni['nome'] . '</div>';
    }

}

echo '<span class="btn btn-info" onclick="opzioniEditor(' . $sottostep_ID . ',0);">Aggiungi opzione</span>';
<?php
if (!$core)
    die("Accesso diretto rilevato");

$query = 'SELECT * FROM documenti';

if (!$result = $db->query($query)) {
    echo 'Query error. ' . $query;
}

if (!$db->affected_rows) {
    echo $this->getBox('info','Nessun documento ancora inserito');
} else {
    echo '<table class="table table bordered table responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Totale</th>
                    <th>Stato</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td></td>
              </tr>';
    }
    echo '</tbody>
    </table>';
}
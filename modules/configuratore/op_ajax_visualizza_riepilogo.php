<?php
$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$documento_ID = (int) $_POST['documento_ID'];

$query = 'SELECT  CORPO.*
                , OPZIONI.opzione_nome
          FROM documenti_corpo CORPO
          LEFT JOIN configuratore_opzioni OPZIONI 
            ON OPZIONI.ID = CORPO.opzione_ID
          WHERE CORPO.documento_ID = ' . $documento_ID . '
            AND CORPO.valorizzata = 1';

$result = $db->query($query);

echo '<table class="table">
<thead>
    <tr>
        <th>Sigla</th>
        <th>Opzione scelta</th>
    </tr>
</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>
            <td>' . $row['sigla'] . '</td>
            <td>' .  $row['opzione_nome'] . '</td>
          </tr>';
}

echo '</tbody>
<tfoot>
    <tr>
        <td>TOTALE €</td>
        <td id="documentoTotale"></td>
    </tr>
</tfoot>
</table>';
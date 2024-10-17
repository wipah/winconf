<?php
$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$documento_ID = (int) $_POST['documento_ID'];

$query = 'SELECT  CORPO.*
                , OPZIONI.opzione_nome
                , SOTTOSTEP.immagine_riepilogo
                , MEDIA.filename
          FROM documenti_corpo CORPO
          LEFT JOIN configuratore_opzioni OPZIONI 
            ON OPZIONI.ID = CORPO.opzione_ID
          LEFT JOIN configuratore_sottostep SOTTOSTEP 
            ON SOTTOSTEP.ID = OPZIONI.sottostep_ID
          LEFT JOIN configuratore_media MEDIA
                ON MEDIA.IDX = CORPO.opzione_ID
                AND MEDIA.contesto_ID = 7
                AND MEDIA.visibile = 1
          WHERE CORPO.documento_ID = ' . $documento_ID . '
            AND CORPO.valorizzata = 1';

$result = $db->query($query);

echo '<table class="table winconf-table-secondary">
<thead>
    <tr>
        <th>Sigla</th>
        <th>Opzione scelta</th>
    </tr>
</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    if ( (int) $row['immagine_riepilogo'] == 1) {
        $img = '<img style="max-width:250px;" class="img-fluid"  src="' . $conf['URI'] . 'modules/media/uploads/' . htmlentities($row['filename']) .'" alt="Riepilogo visivo opzione">';
    } else {
        $img =  '';
    }
    echo '<tr>
            <td>' . $row['sigla'] . '</td>
            <td>' . $row['opzione_nome'] . ' <br/>
                    ' . $img . '
            </td>
          </tr>';
}

echo '</tbody>
<tfoot>
    <tr>
        <td>TOTALE €</td>
        <td id="documentoTotale"></td>
    </tr>
</tfoot>
</table>
<div class="row">
    <div class="col">
        <button onclick="finalizzaDocumento();" id="btnFinalizza" disabled>Finalizza</button>
    </div>
</div>
';
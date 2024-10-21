<?php
$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$documento_ID = (int) $_POST['documento_ID'];

$query = 'SELECT  CORPO.*
                , OPZIONI.opzione_nome
                , SOTTOSTEP.immagine_riepilogo
                , SOTTOSTEP.tipo_scelta
                , SOTTOSTEP.sottostep_nome
                , MEDIA.filename
          FROM documenti_corpo CORPO
          LEFT JOIN configuratore_opzioni OPZIONI 
            ON OPZIONI.ID = CORPO.opzione_ID
          LEFT JOIN configuratore_sottostep SOTTOSTEP 
            /* ON SOTTOSTEP.ID = OPZIONI.sottostep_ID */
               ON SOTTOSTEP.ID = CORPO.sottostep_ID
          LEFT JOIN configuratore_media MEDIA
                ON MEDIA.IDX = CORPO.opzione_ID
                AND MEDIA.contesto_ID = 7
                AND MEDIA.visibile = 1
          WHERE CORPO.documento_ID = ' . $documento_ID . '
            AND CORPO.valorizzata = 1';

$result = $db->query($query);

echo '<table class="table winconf-table-secondary">
<thead>

</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    
    if ( (int) $row['immagine_riepilogo'] == 1) {
        $img = '<img style="max-width:250px;" class="img-fluid"  src="' . $conf['URI'] . 'modules/media/uploads/' . htmlentities($row['filename']) .'" alt="Riepilogo visivo opzione">';
    } else {
        $img =  '';
    }

    if ( (int) $row['tipo_scelta'] === 99 ||  (int) $row['tipo_scelta'] === 98 || (int) $row['tipo_scelta'] === 97) {
        $scelta =  (int) $row['valore_numerico'] . 'mm';

        $nome = $row['sottostep_nome'];
    } else if ( (int) $row['tipo_scelta'] == 3 || (int) $row['tipo_scelta'] == 4 ) {
        $scelta = $row['valore_numerico'];
        $nome = $row['sottostep_nome'];
    } else if ( (int) $row['tipo_scelta'] === 2) {
        $scelta = $row['valore_testo'];
        $nome = $row['sottostep_nome'];
    } else {
        $nome = $row['sottostep_nome'];
        $scelta = $row['opzione_nome'];
    }
    echo '<tr>
            <td><h5>' . $nome . '</h5></td>
          </tr> 
          <tr>
            <td><p style="margin-left: 80px">' . $scelta . ' <br/>
                    ' . $img . '</p>
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
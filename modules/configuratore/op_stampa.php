<?php

$this->noTemplateParse = true;

if(!$user->validateLogin())
    return;

if (!isset($_GET['documento_ID'])) {
    echo 'Il documento non è stato passato.';
    return;
}

$documento_ID = (int) $_GET['documento_ID'];

$query = 'SELECT DOCUMENTO.*
               , CLIENTE.ID
               , CLIENTE.erp_ID
               , CLIENTE.ragione_sociale
          FROM documenti DOCUMENTO 
          LEFT JOIN clienti CLIENTE
            ON CLIENTE.ID = DOCUMENTO.customer_ID
          WHERE DOCUMENTO.ID = ' . $documento_ID . ' 
          AND DOCUMENTO.user_ID = ' . $user->ID;

$result = $db->query($query);

if (!$db->affected_rows) {
    echo 'Documento non trovato.';
    return;
}

$rowTestata = mysqli_fetch_assoc($result);

$query = 'SELECT CORPO.*, opzione_nome
          FROM documenti_corpo CORPO
          LEFT JOIN configuratore_opzioni OPZIONE
            ON OPZIONE.ID = CORPO.opzione_ID
          WHERE documento_ID = ' . $documento_ID;

$result = $db->query($query);

if (!$db->affected_rows) {
    echo 'Il documento non contiene linee';
    return;
}


echo '<!doctype html>
<html lang="en">
    <head>
        <title>Documento ' . $documento_ID . '</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </head>
<body>
<style>
.documentoHeader {
    background-color: #2a64a6;
    color: white;
    font-size: large;
    padding: 8px;
    border-bottom: 2px solid #174a85;
}

body {
 padding: 36px;
}

.documentoHeaderCampi {
    background-color: #5a9dea;
    color: #1f1818;
    font-size: large;
    padding: 8px;
    border-bottom: 1px solid darkblue;
    border-right: 1px solid darkblue;
}
</style>
<div class="row">
    <div class="col-md-3 documentoHeader">
    DOCUMENTO
    </div>
    <div class="col-md-3 documentoHeader">
    DATA
    </div>
    <div class="col-md-1 documentoHeader">
    LARGH.
    </div>
    <div class="col-md-1 documentoHeader">
    LUNG.
    </div>
    <div class="col-md-3 documentoHeader">
    CLIENTE
    </div>
    <div class="col-md-1 documentoHeader">
    STATO
    </div>
</div>

<div class="row">
    <div class="col-md-3 documentoHeaderCampi">
    ' . $documento_ID . '
    </div>
    <div class="col-md-3 documentoHeaderCampi">
    ' . $rowTestata['data_ordine']  . '
    </div>
    <div class="col-md-1 documentoHeaderCampi">
    ' . $rowTestata['larghezza'] . 'mm
    </div>
    <div class="col-md-1 documentoHeaderCampi">
    ' . $rowTestata['lunghezza'] . 'mm
    </div>
    <div class="col-md-3 documentoHeaderCampi documentoHeaderCampi">
    ' . $rowTestata['ragione_sociale'] . '
    </div>
    <div class="col-md-1 documentoHeaderCampi">
    ' . ($rowTestata['stato'] == 1 ? 'Terminato' : 'Bozza' ) . '
    </div>
</div>

<hr/>
<table class="table table-bordered">
        <thead>
            <tr>
                <td>ID</td>
                <td>Sigla</td>
                <td>Descrizione</td>
                <td>Costo</td>
            </tr>
        </thead>
      <tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>
            <td>' . $row['ID'] . '</td>
            <td>' . $row['sigla'] . '</td>
            <td>' . $row['opzione_nome'] . '</td>
            <td>' . $row['importo'] . '</td>

          </tr>';
}
echo '</tbody>
<tfood>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>' . $rowTestata['totale']  .'</td>
    </tr>
</tfood>
</table>
<div class="row">
    <div class="col-md">' . $rowTestata['note'] . '</div>
</div>
</body>
</html>';
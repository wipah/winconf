<?php

if (!$core)
    die("Direct access");

$this->noTemplateParse = true;

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}


$categoria_ID = (int) $_POST['categoria_ID'];
$sottostep_ID = (int) $_POST['sottostep_ID'];

$ID = (int) $_POST['ID'];

if ($ID !== 0) {
    $query = 'SELECT * 
              FROM configuratore_opzioni_check_dipendenze 
              WHERE ID = ' . $ID . ' LIMIT 1';

    if (!$result = $db->query($query)) {
        echo 'Query error. ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo 'La dipendenza non esiste';
        return;
    }

    $row = mysqli_fetch_assoc($result);
}

$query = "SELECT configuratore_step.step_nome
               , configuratore_sottostep.sottostep_nome
               , configuratore_sottostep.ID sottostep_ID
               , configuratore_opzioni.opzione_nome
               , configuratore_opzioni.ID opzione_ID
FROM configuratore_step
LEFT JOIN configuratore_sottostep
ON configuratore_sottostep.step_ID = configuratore_step.ID
LEFT JOIN configuratore_opzioni
    ON configuratore_opzioni.sottostep_ID = configuratore_sottostep.ID
WHERE configuratore_sottostep.sottostep_nome IS NOT NULL 
    AND configuratore_opzioni.ID IS NOT NULL 
    AND configuratore_step.categoria_ID = $categoria_ID 
ORDER BY configuratore_step.ordine ASC, configuratore_sottostep.ordine ASC, configuratore_opzioni.ordine ASC";

if (!$result = $db->query($query)) {
    echo 'Errore nella query. ' . $query;
    return;
}

$selectStep = '<div class="form-group row">
    <label for="opzione" class="col-4 col-form-label">Opzione</label> 
    <div class="col-8">
      <select id="opzione" name="opzione" class="custom-select">';

while ($rowSelect = mysqli_fetch_assoc($result)) {
    $selectStep .= '<option ' . ( (int) $row['opzioni_formula_ID'] === $rowSelect['opzione_ID'] ? ' selected ' : '' ) . ' value="' . $rowSelect['opzione_ID'] . '">' . $rowSelect['step_nome'] . ' - ' . $rowSelect['sottostep_nome'] . ' - ' . $rowSelect['opzione_nome'] . '</option>';
}

$selectStep .= '
      </select>
    </div>
  </div> ';


$selectConfronto = '<div class="form-group row">
    <label for="confronto" class="col-4 col-form-label">Tipo confronto</label> 
    <div class="col-8">
      <select id="confronto" name="confronto" class="custom-select">
        <option ' . ( (int) $row['confronto'] === 0 ? ' selected ' : '') . ' value="0">Minore di </option>
        <option ' . ( (int) $row['confronto'] === 1 ? ' selected ' : '') . ' value="1">Minore o uguale di</option>
        <option ' . ( (int) $row['confronto'] === 2 ? ' selected ' : '') . ' value="2">Uguale a</option>
        <option ' . ( (int) $row['confronto'] === 3 ? ' selected ' : '') . ' value="3">Maggiore di</option>
        <option ' . ( (int) $row['confronto'] === 4 ? ' selected ' : '') . ' value="4">Maggiore o uguale a</option>
        <option ' . ( (int) $row['confronto'] === 5 ? ' selected ' : '') . ' value="5">Diverso da</option>
      </select></div></div>';



echo $selectStep;
echo $selectConfronto;

echo '  <div class="form-group row">
    <label for="valore" class="col-4 col-form-label">Valore</label> 
    <div class="col-8">
      <input id="valore" name="valore" placeholder="valore" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="esito" class="col-4 col-form-label">Esito</label> 
    <div class="col-8">
      <select id="esito" name="esito" class="custom-select" aria-describedby="esitoHelpBlock">
        <option ' . ( $row['esito'] === 0 ? ' selected ' : ' ' ) . ' value="0">Escludi</option>
        <option ' . ( $row['esito'] === 1 ? ' selected ' : ' ' ) . ' value="1">Includi</option>
      </select> 
      <span id="esitoHelpBlock" class="form-text text-muted">Se la condizione è valida lo step può essere escluso o incluso.</span>
    </div>
  </div>
  
    <div class="form-group row">
    <div class="offset-4 col-8">
      <span onclick="salvaDipendenza(' . $sottostep_ID .' ,' . $ID .');" class="btn btn-primary">Aggiorna</span>
    </div>
  </div>
  
<script>
function salvaDipendenza(sottostep_ID, ID) {
    
    opzione     = $("#opzione").find(":selected").val();
    confronto   = $("#confronto").find(":selected").val();
    esito       = $("#esito").find(":selected").val();
    valore      = $("#valore").val();
    
    
    $.post( jsPath + "configuratore-admin/ajax-sottostep-dipendenze-editor-post/", { 
                                                                                  ID            : ID,
                                                                                  sottostep_ID  : sottostep_ID,
                                                                                  opzione_ID    : opzione,
                                                                                  confronto     : confronto ,
                                                                                  esito         : esito ,
                                                                                  valore        : valore ,
    }).done(function( data ) {
        console.log(data)
        mostraDipendenze(sottostep_ID);
        $("#modalDialog").modal();
    });
    
}
</script>';

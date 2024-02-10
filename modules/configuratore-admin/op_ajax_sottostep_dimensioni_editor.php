<?php

if (!$core)
    die("Direct access");

$this->noTemplateParse = true;

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}


$categoria_ID       =   (int) $_POST['categoria_ID'];
$step_ID            =   (int) $_POST['step_ID'];
$sottostep_ID       =   (int) $_POST['sottostep_ID'];
$opzione_ID         =  (int) $_POST['opzione_ID'];
$ID                 =   (int) $_POST['ID'];


if ($opzione_ID === 0) {
    echo '<h2>Check dimensione del sottostep</h2>';
} else {
    echo '<h2>Dimensione dell\'opzione<h2></h2>';
}

if ($ID !== 0) {
    $query = 'SELECT * 
              FROM configuratore_opzioni_check_dimensioni 
              WHERE ID = ' . $ID . ' LIMIT 1';


    if (!$result = $db->query($query)) {
        echo 'Query error. ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo 'Il controllo sulla dimensione non esiste. (ID: ' . $ID . ')';
        return;
    }

    $row = mysqli_fetch_assoc($result);
}



$selectConfronto = '
    <div class="form-group row">
    <label for="confronto" class="col-4 col-form-label">Tipo confronto</label> 
    <div class="col-8">
      <select id="confronto" name="confronto" class="custom-select">
        <option ' . ( (int) $row['confronto'] === 0 ? ' selected ' : '') . ' value="0">Minore di </option>
        <option ' . ( (int) $row['confronto'] === 1 ? ' selected ' : '') . ' value="1">Minore o uguale di</option>
        <option ' . ( (int) $row['confronto'] === 2 ? ' selected ' : '') . ' value="2">Uguale a</option>
        <option ' . ( (int) $row['confronto'] === 3 ? ' selected ' : '') . ' value="3">Maggiore o uguale</option>
        <option ' . ( (int) $row['confronto'] === 4 ? ' selected ' : '') . ' value="4">Maggiore</option>
        <option ' . ( (int) $row['confronto'] === 5 ? ' selected ' : '') . ' value="5">Diverso da</option>
      </select></div></div>';



echo '<div class="form-group row">
    <label for="dimensione" class="col-4 col-form-label">Dimensione</label> 
    <div class="col-8">
      <select id="dimensione" name="dimensione" class="custom-select">
        <option ' . ( (int) $row['dimensione'] === 0 ? ' selected ' : '') . ' value="0">Larghezza</option>
        <option ' . ( (int) $row['dimensione'] === 1 ? ' selected ' : '') . ' value="1">Altezza</option>
<!--        <option ' . ( (int) $row['dimensione'] === 2 ? ' selected ' : '') . ' value="2">Spessore</option> -->
      </select></div></div>';


echo $selectStep;
echo $selectConfronto;

echo '  <div class="form-group row">
    <label for="valore" class="col-4 col-form-label">Valore</label> 
    <div class="col-8">
      <input value="' . $row['valore'] . '" id="valore" name="valore" placeholder="valore" type="text" class="form-control">
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
      <span onclick="salvaDimensione(' . $categoria_ID . ', ' . $step_ID . ',' . $sottostep_ID .' ,' . $opzione_ID . ', ' . $ID .');" class="btn btn-primary">Aggiorna</span>
    </div>
  </div>
  
<script>
function salvaDimensione(categoria_ID, step_ID, sottostep_ID, opzione_ID, ID) 
{
    /* Questa funzione salva il check della dimensione.
     Se è passata una opzione_ID verrà salvata l\'opzione relativa al sottostep altrimenti verrà salvato il ckeck
     della dimensione del sottostep.   
     */
     
    dimensione      =   $("#dimensione").find(":selected").val();
    confronto       =   $("#confronto").find(":selected").val();
    esito           =   $("#esito").find(":selected").val();
    valore          =   $("#valore").val();
    
    $.post( jsPath + "configuratore-admin/ajax-sottostep-dimensioni-editor-post/", { 
                                                                                  categoria_ID  : categoria_ID,
                                                                                  step_ID       : step_ID,
                                                                                  sottostep_ID  : sottostep_ID,
                                                                                  opzione_ID    : opzione_ID,
                                                                                  ID            : ID,
                                                                                  dimensione    : dimensione,
                                                                                  confronto     : confronto ,
                                                                                  esito         : esito ,
                                                                                  valore        : valore ,
    }).done(function( data ) {
        console.log(data)
        
        if (opzione_ID == 0) {
            mostraDimensioniSottostep(categoria_ID, step_ID, sottostep_ID, opzione_ID);    
        } else {
            
            mostraDimensioniOpzioni(categoria_ID, step_ID, sottostep_ID, opzione_ID);
        }
        
        
        $("#modalDialog").modal();
    });
    
}
</script>';

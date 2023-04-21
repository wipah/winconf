<?php

if (!$core)
    die("Accesso diretto");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

if (!$step_ID = (int) $_GET['step_ID']) {
    echo 'Manca l\'ID dello step';
    return;
}

$query = 'SELECT * 
          FROM configuratore_step 
          WHERE ID = ' . $step_ID;

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Lo step non esiste! ' . $query;
    return;
}

$rowStep = mysqli_fetch_assoc($result);

echo '

<style>
.sottoStepLista {
    padding: 4px;
    background-color: #b7bcc5;
    border-bottom: 1px solid #777;
}
</style>
<h1>Sottostep per ' . $rowStep['step_nome'] . '</h1>
<div id="sottoStep"></div>
<div id="opzioni"></div>
<div id="dipendenze"></div>
<div id="dimensioni"></div>

<div id="modalDialog" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog  modal-lg"" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi finestra</button>
      </div>
    </div>
  </div>
</div>

<script>
step_ID = ' . $step_ID . ';

mostraSottostep();

function mostraSottostep() {
    $.post( jsPath + "configuratore-admin/ajax-sottostep/", { step_ID: step_ID })
      .done(function( data ) {
        $("#sottoStep").html(data);
    });    
}

function mostraOpzioni(sottostep_ID) {    
    $.post( jsPath + "configuratore-admin/ajax-step-mostra-opzioni/", { sottostep_ID: sottostep_ID })
    .done(function( data ) {
        $("#opzioni").html(data);
    });
}

function sottoStepEditor(ID) {
    $("#modalDialog").modal();
    $.post( jsPath + "configuratore-admin/ajax-sottostep-editor/", { step_ID: ' . $step_ID . ' ,sottostep_ID: ID })
    .done(function( data ) {
        $("#modalBody").html(data);
    });
}

function opzioniEditor(sottostep_ID, ID) {
    $("#modalDialog").modal();
    $.post( jsPath + "configuratore-admin/ajax-sottostep-opzioni-editor/", { step_ID: ' . $step_ID . ' , sottostep_ID: sottostep_ID, ID: ID })
    .done(function( data ) {
        $("#modalBody").html(data);
    });
}

function dipendenzeEditor(sottostep_ID, ID) {
    console.log("[Editor dipendenze]");
    console.log("->sottostep_ID: " + sottostep_ID);
    console.log("->ID: " + ID);
    
    $("#modalDialog").modal();
    $.post( jsPath + "configuratore-admin/ajax-sottostep-dipendenze-editor/", { categoria_ID: ' . $rowStep['categoria_ID'] . ', sottostep_ID: sottostep_ID ,step_ID: ' . $step_ID . ' , sottostep_ID: sottostep_ID, ID: ID })
    .done(function( data ) {
        $("#modalBody").html(data);
    });
}

function mostraDipendenze(sottostep_ID, ID) {
    console.log("[Mostra dipendenze]");
    console.log("->sottostep_ID: " + sottostep_ID);
    console.log("->ID: " + ID);
    
    
    $.post( jsPath + "configuratore-admin/ajax-dipendenze/", { sottostep_ID: sottostep_ID, step_ID: step_ID })
      .done(function( data ) {
        $("#dipendenze").html(data);
    });    
}

</script>';
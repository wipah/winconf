<?php

if (!$step_ID = (int) $_GET['step_ID']) {
    echo 'Manca l\'ID dello step';
}


$query = 'SELECT * FROM configuratore_sottostep WHERE step_ID = ' . $step_ID;

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Lo step non esiste!';
    return;
}

$rowStep = mysqli_fetch_assoc($result);

echo '<h1>Sottostep per ' . $rowStep['sottostep_nome'] . '</h1>
<div id="sottoStep"></div>
<div id="opzioni"></div>



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
    $.post( jsPath + "configuratore-admin/ajax-sottostep-editor/", { sottostep_ID: ID })
    .done(function( data ) {
        $("#modalBody").html(data);
    });
}

</script>
';
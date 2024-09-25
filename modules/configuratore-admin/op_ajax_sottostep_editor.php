<?php

if (!$core)
    die ("Accesso diretto");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

$categoria_ID   = (int) $_POST['categoria_ID'];
$step_ID        = (int) $_POST['step_ID'];
$ID             = (int) $_POST['ID'];

if ($ID === 0) {
    $button = 'Salva sottostep';
} else {
    $button = 'Modifica il sottostep';
    $query = 'SELECT * FROM 
              configuratore_sottostep
              WHERE ID = ' . $ID;

    if (!$result = $db->query($query)) {
        echo 'Query error. ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo 'Sottostep non trovato.';
        return;
    }

    $row = mysqli_fetch_assoc($result);
}

echo '
  <div class="form-group row">
    <label for="sottostepNome" class="col-4 col-form-label">Nome</label> 
    <div class="col-8">
      <input value="' . $row['sottostep_nome'] . '" id="sottostepNome" name="sottostepNome" placeholder="Nome del sottostep" type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="sottostepSigla" class="col-4 col-form-label">Sigla</label> 
    <div class="col-8">
      <input value="' . $row['sottostep_sigla'] . '" id="sottostepSigla" name="sottostepSigla" placeholder="Sigla breve del sottostep" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="sottostepDescrizione" class="col-4 col-form-label">Descrizione</label> 
    <div class="col-8">
      <textarea id="sottostepDescrizione" name="sottostepDescrizione" placeholder="Descrizione del sottostep" class="form-control" required="required">' . $row['sottostep_descrizione'] . '</textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="sottostepTipoScelta" class="col-4 col-form-label">Tipo scelta</label> 
    <div class="col-8">
      <select id="sottostepTipoScelta" name="sottostepTipoScelta" class="custom-select" aria-describedby="sottostepTipoSceltaHelpBlock">
        <option ' . ( (int) $row['tipo_scelta'] === 0 ? ' selected ' : '' )  . ' value="0">Scelta singola</option>
        <!-- <option ' . ( (int) $row['tipo_scelta'] === 1 ? ' selected ' : '' )  . ' value="1">Scelta multipla</option> -->
        <option ' . ( (int) $row['tipo_scelta'] === 2 ? ' selected ' : '' )  . ' value="2">Campo libero</option>
        <option disabled>---</option>
        <option ' . ( (int) $row['tipo_scelta'] === 2 ? ' selected ' : '' )  . ' value="99">Larghezza</option>
        <option ' . ( (int) $row['tipo_scelta'] === 2 ? ' selected ' : '' )  . ' value="98">Altezza</option>
        <option ' . ( (int) $row['tipo_scelta'] === 2 ? ' selected ' : '' )  . ' value="97">Spessore</option>
      </select> 
      <span id="sottostepTipoSceltaHelpBlock" class="form-text text-muted">Tipo di scelta</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="sottostepCheckDipendenze" class="col-4 col-form-label">Check dipendenze</label> 
    <div class="col-8">
      <select id="sottostepCheckDipendenze" name="sottostepCheckDipendenze" class="custom-select" aria-describedby="sottostepCheckDipendenzeHelpBlock">
        <option ' . ( (int) $row['check_dipendenze'] === 0 ? ' selected ' : '' )  . ' value="0">No, non controlla le dipendenze</option>
        <option ' . ( (int) $row['check_dipendenze'] === 1 ? ' selected ' : '' )  . ' value="1">Sì, controlla le dipendenze</option>
      </select> 
      <span id="sottostepCheckDipendenzeHelpBlock" class="form-text text-muted">Determina se controllare eventuali dipendenze</span>
    </div>
  </div>
  
    <div class="form-group row">
    <label for="sottostepCheckDipendenze" class="col-4 col-form-label">Check dimensioni</label> 
    <div class="col-8">
      <select id="sottostepCheckDimensioni" name="sottostepCheckDimensioni" class="custom-select" aria-describedby="sottostepCheckDipendenzeHelpBlock">
        <option ' . ( (int) $row['check_dimensioni'] === 0 ? ' selected ' : '' )  . ' value="0">No, non controlla le dimensioni</option>
        <option ' . ( (int) $row['check_dimensioni'] === 1 ? ' selected ' : '' )  . ' value="1">Sì, controlla le dimensioni</option>
      </select> 
      <span id="sottostepCheckDipendenzeHelpBlock" class="form-text text-muted">Determina se controllare eventuali dimensioni</span>
    </div>
  </div>
  
  
  <div class="form-group row">
    <label for="sottostepVisibile" class="col-4 col-form-label">Visibile</label> 
    <div class="col-8">
      <select id="sottostepVisibile" name="sottostepVisibile" class="custom-select" aria-describedby="sottostepVisibileHelpBlock" required="required">
        <option ' . ( (int) $row['visibile'] === 0 ? ' selected ' : '' )  . '  value="0">No, lo step non è visibile.</option>
        <option ' . ( (int) $row['visibile'] === 1 ? ' selected ' : '' )  . ' value="1">Sì, lo step è visibile</option>
      </select> 
      <span id="sottostepVisibileHelpBlock" class="form-text text-muted">Determina la visibilità del sottostep</span>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8 clearfix">
      <span onclick="sottostepPostData(' . $categoria_ID . ',' . $step_ID .', ' . $ID . ');" name="submit" type="submit" class="btn btn-primary float-right">' . $button . '</span>
    </div>
  </div>
  
<script>
function sottostepPostData(categoria_ID, step_ID, sottostep_ID) 
{
    console.log("[Sottostep post data]");
    console.log("-> step_ID: " + step_ID);
    console.log("-> sottostep_ID: " + sottostep_ID);
    
    sottostepNome           = $("#sottostepNome").val();
    sottostepSigla          = $("#sottostepSigla").val();
    sottostepDescrizione    = $("#sottostepDescrizione").val();
    sottostepTipoScelta     = $("#sottostepTipoScelta").find(":selected").val();
    sottostepDipendenza     = $("#sottostepCheckDipendenze").find(":selected").val();
    sottostepDimensioni     = $("#sottostepCheckDimensioni").find(":selected").val();
    sottostepVisibile       = $("#sottostepVisibile").find(":selected").val();
     
    $.post( jsPath + "configuratore-admin/ajax-sottostep-editor-post/", { categoria_ID          : categoria_ID ,
                                                                          step_ID               : step_ID ,
                                                                          sottostep_ID          : sottostep_ID ,
                                                                          sottostepNome         : sottostepNome,
                                                                          sottostepSigla        : sottostepSigla,
                                                                          sottostepDescrizione  : sottostepDescrizione,
                                                                          sottostepTipoScelta   : sottostepTipoScelta,
                                                                          sottostepDipendenza   : sottostepDipendenza,
                                                                          sottostepDimensioni   : sottostepDimensioni,
                                                                          sottostepVisibile     : sottostepVisibile,
                                                                        })
    .done(function( data ) {
        console.log(data)
        mostraSottostep();
        $("#modalDialog").modal();
    });

}
</script>  
  ';
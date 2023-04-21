<?php

if (!$core)
    die("Accesso diretto");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

$ID =  (int) $_POST['ID'];

if (!$sottostep_ID = (int) $_POST['sottostep_ID']) {
    echo 'Sottostep ID non passato';
    return;
}

if ($ID === 0) {
    $button = 'Salva nuova opzione';
} else {
    $button = 'Modifica opzione';
}


$query = 'SELECT * FROM configuratore_formule';

if (!$resultFormule = $db->query($query)) {
    echo 'Query error. ' . $query;
    return;
}

$selectFormule = '<div class="form-group row">
                        <label for="formula" class="col-4 col-form-label">Formula</label> 
                        <div class="col-8">
                            <select id="formula" name="formula" class="custom-select" aria-describedby="formulaHelpBlock" required="required">';
while ($rowFormule = mysqli_fetch_assoc($resultFormule)) {
    $selectFormule .= '<option ' . ( (int) $row['opzioni_formula_ID'] === (int) $rowFormule['ID'] ? ' selected ' : '' ) . ' value="' .$rowFormule['ID'] . '">' . $rowFormule['formula_sigla'] .'</option>';
}
$selectFormule .= '</select> 
      <span id="formulaHelpBlock" class="form-text text-muted">Formula utilizzata per il calcolo del preventivo</span>
    </div>
  </div>';

echo '
   <div class="form-group row">
    <label for="nome" class="col-4 col-form-label">Nome</label> 
    <div class="col-8">
      <input id="nome" name="nome" placeholder="Nome dell\'opzione" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="sigla" class="col-4 col-form-label">Sigla</label> 
    <div class="col-8">
      <input id="sigla" name="sigla" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="descrizione" class="col-4 col-form-label">Descrizione</label> 
    <div class="col-8">
      <textarea id="descrizione" name="descrizione" cols="40" rows="5" class="form-control"></textarea>
    </div>
  </div>

  <div class="form-group row">
    <label for="sottostepCheckDipendenze" class="col-4 col-form-label">Check dipendenze</label> 
    <div class="col-8">
      <select id="checkDipendenze" name="checkDipendenze" class="custom-select" aria-describedby="checkDipendenzeHelpBlock">
        <option ' . ( (int) $row['check_dipendenze'] === 0 ? ' selected ' : '' )  . ' value="0">No, non controlla le dipendenze</option>
        <option ' . ( (int) $row['check_dipendenze'] === 1 ? ' selected ' : '' )  . ' value="1">Sì, controlla le dipendenze</option>
      </select> 
      <span id="checkDipendenzeHelpBlock" class="form-text text-muted">Determina se controllare eventuali dipendenze</span>
    </div>
  </div>
  
  <div class="form-group row">
    <label for="sottostepCheckDimensioni" class="col-4 col-form-label">Check dimensioni</label> 
    <div class="col-8">
      <select id="checkDimensioni" name="checkDimensioni" class="custom-select" aria-describedby="checkDimensioniHelpBlock">
        <option ' . ( (int) $row['check_dimensioni'] === 0 ? ' selected ' : '' )  . ' value="0">No, non controlla le dimensioni</option>
        <option ' . ( (int) $row['check_dimensioni'] === 1 ? ' selected ' : '' )  . ' value="1">Sì, controlla le dimensioni</option>
      </select> 
      <span id="checkDimensioniHelpBlock" class="form-text text-muted">Determina se controllare le dimensioni (larghezza e lunghezza)</span>
    </div>
  </div>

  ' . $selectFormule . '
  
  <div class="form-group row">
    <label for="Valore formula" class="col-4 col-form-label">Valore formula</label> 
    <div class="col-8">
      <input id="valoreFormula" name="valoreFormula" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="visibile" class="col-4 col-form-label">Visibile</label> 
    <div class="col-8">
      <select id="visibile" name="visibile" class="custom-select">
        <option value="1">Si</option>
        <option value="0">No</option>
      </select>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <span onclick="salvaOpzione(' . $ID . ', ' . $sottostep_ID .');"  class="btn btn-primary">' . $button . '</span>
    </div>
  </div>
  
<script>
function salvaOpzione(ID, sottostep_ID) {
    console.log ("[SALVATAGGIO OPZIONE]");
    console.log ("-> ID: " + ID + ", sottostep_ID:" + sottostep_ID);
    
    nome            =   $("#nome").val();
    sigla           =   $("#sigla").val();
    descrizione     =   $("#descrizione").val();
    checkDipendenze =   $("#checkDipendenze").find(":selected").val();
    checkDimensioni =   $("#checkDimensioni").find(":selected").val();
    formula         =   $("#formula").find(":selected").val();
    valoreFormula   =   $("#valoreFormula").val();
    visibile        =   $("#visibile").find(":selected").val();
    
    $.post( jsPath + "configuratore-admin/ajax-sottostep-opzioni-editor-post/", { ID                    : ID,
                                                                                  sottostep_ID          : sottostep_ID,
                                                                                  nome                  : nome,
                                                                                  sigla                 : sigla ,
                                                                                  descrizione           : descrizione ,
                                                                                  checkDipendenze       : checkDipendenze ,
                                                                                  checkDimensioni       : checkDimensioni ,
                                                                                  formula               : formula ,
                                                                                  valoreFormula         : valoreFormula ,
                                                                                  visibile              : visibile})
    .done(function( data ) {
        console.log(data)
        mostraOpzioni(sottostep_ID);
        $("#modalDialog").modal();
    });
    
} 
</script>';
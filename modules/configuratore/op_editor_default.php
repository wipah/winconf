<?php

if(!$user->validateLogin())
    return;

if (!isset($_GET['ID'])) {
    echo 'Manca il numero del documento';
    return;
}

$documento_ID = (int) $_GET['ID'];

$queryTestata = 'SELECT * 
                 FROM documenti 
                 WHERE ID = ' . $documento_ID . ' 
                 AND user_ID = ' . $user->ID;

if (!$resultTestata = $db->query($queryTestata)) {
    echo 'Query error.';
    return;
}

if (!$db->affected_rows) {
    echo 'Documento non esistente';
    return;
}

echo '
<script>
documento_ID = ' . $documento_ID . ';
console.log("Il documento ha ID " + documento_ID);
</script>

<style>
.configuratoreDivCliente {
    background-color: #2a64a6 ;
    padding: 6px;
    color: white;
}
.configuratoreTopBar{
    padding: 6px;
    border-bottom: 1px solid blue !important;
}

.configuratoreTopBarButton{
    padding: 12px;
    background-color: #2a64a6;
    color:white;
    margin-right: 12px;
    border-bottom: 1px solid blue !important;
}

.topBarButtonSelected{
    background-color: #e8b08a;
border-bottom: 1px solid #9aa2ad !important;
}

.layoutEditorSottostep {
    margin-bottom: 24px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e3e3e3;
}
</style>';

$rowTestata = mysqli_fetch_assoc($resultTestata);


$configuratore->lunghezza = $rowTestata['lunghezza'];
$configuratore->larghezza = $rowTestata['larghezza'];

$queryCliente = 'SELECT * 
                 FROM clienti 
                 WHERE ID = ' . $rowTestata['customer_ID'];
if (!$resultCliente = $db->query($queryCliente)) {
    echo 'Errore nella query. ' . $queryCliente;
    return;
}
$rowCliente = mysqli_fetch_assoc($resultCliente);

$this->title = 'WINCONF - Configuratore - Documento ' . $documento_ID;
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore/">Configuratore</a>';
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore/editor/?ID=' . $documento_ID . '">Editor ordine ' . $documento_ID . '</a>';

echo '<div class="row configuratoreDivCliente">
        <div class="col-md-2" style="border-right: 1px solid white; text-align: center">
            <div style="font-size: small">Ordine</div>
            <div><span style="font-size: x-large">4543</span> del <span style="font-size: x-large">16-06-2023</span> </div>
        </div>
        <div class="col-md-5" style="border-right: 1px solid white">
            <div style="font-size: small">Cliente</div>
            <div style="font-size: x-large">' . $rowCliente['ragione_sociale'] .' - ' . $rowCliente['partita_iva'] . ' - ' . $rowCliente['indirizzo_citta'] . '</div>
        </div>
        <div class="col-md-5">
            <div style="font-size: small">Note</div>    
            <textarea onkeyup="aggiornaNote();" id="editorNote" style="background-color: #aeccf1; width: 100% ">' . $rowTestata['note'] . '</textarea>
        </div>
      </div>';

$documentoSteps = $configuratore->stepDaOrdine($documento_ID);

echo '<div class="configuratoreTopBar">';
foreach ($documentoSteps as $step_ID => $stepNome) {
    echo '<button class="configuratoreTopBarButton" id="layoutStepBottone-' . $step_ID . '" class="step" onclick="mostraStep(' . $step_ID  .')">' . $stepNome . '</button>';
}
echo '</div>';

$stepSchede = '';
foreach ($documentoSteps as $step_ID => $stepNome) {
    $stepSchede .= '<div class="layoutStepScheda mt-8" style="display:none" id="layoutStepScheda-' . $step_ID . '"></div>';
}

echo '
<div class="row mt-6">
    <div class="col-md-9">
    ' . $stepSchede . '
    </div>
    <div class="col-md-3">
        <h2>Riepilogo</h2>
        <div id="documentoTotale"></div>
    </div>
</div>';
echo '<script>
$(function() {
    ottieniUltimoStep();
    ottieniTotale();
});';

foreach ($documentoSteps as $step_ID => $stepNome) {
    echo '$(function() {
            visualizzaStep(' . $step_ID . ');
          });';
}
echo '</script>';


echo '<script>
function mostraStep (step_ID) 
{
    $(".layoutStepScheda").hide();
    $("#layoutStepScheda-" + step_ID).show();
    
    $(".configuratoreTopBarButton").removeClass("topBarButtonSelected");
    $("#layoutStepBottone-" + step_ID).addClass("topBarButtonSelected");
    
}

function cambiaSingolaOpzione(linea_ID, opzione_ID, step_ID, sottostep_ID) 
{
    console.log ("[CAMBIO OPZIONE]");    
    console.log ("--> Linea ID: "   + linea_ID);    
    console.log ("--> opzione ID: " + opzione_ID);
    
    $("#layoutEditorSottostepStatus-" + linea_ID).addClass("lds-dual-ring");
    
    $.post( jsPath + "configuratore/editor/ajax-cambia-opzione/", { linea_ID: linea_ID
                                                                  , opzione_ID: opzione_ID
                                                                  , documento_ID: documento_ID
                                                                  , step_ID : step_ID
                                                                  , sottostep_ID: sottostep_ID})
      .done(function( data ) {
    
        $("#layoutEditorSottostepStatus-" + linea_ID).removeClass("lds-dual-ring");
        obj = JSON.parse(data); 
        console.log("--> stato: " + obj.status + ". Messaggio " + obj.message + ". step_ID = " + obj.step_ID);

    
        if ( parseInt(obj.step_ID) !== 0) {
            visualizzaStep(obj.step_ID);
            mostraStep(obj.step_ID);
            ottieniTotale();
        }
            
      });
}

function ottieniUltimoStep () 
{
    console.log ("[OTTIENI ULTIMO STEP]");
    $.post( jsPath + "configuratore/editor/ajax-ottieni-ultimo-step/", { documento_ID: ' . $documento_ID . ' })
      .done(function( data ) {
        obj = JSON.parse(data);
        mostraStep(obj.step_ID);
      });
}

function ottieniTotale () 
{
    console.log ("[OTTIENI TOTALE]");
   
    $.post( jsPath + "configuratore/editor/ajax-ottieni-totale/", { documento_ID: ' . $documento_ID . ' })
      .done(function( data ) {
        $("#documentoTotale").html(data);
      });
}

function aggiornaNote() 
{
    console.log ("[AGGIORNA NOTE]");
    note = $("#editorNote").val();
    $.post( jsPath + "configuratore/editor/ajax-aggiorna-note/", { documento_ID: ' . $documento_ID . ', note: note })
      .done(function( data ) {
        
      });
}
function visualizzaStep(step_ID) {
    console.log ("[CARICO STEP]");
    console.log ("--> step_ID: " + step_ID);
    
    $.post( jsPath + "configuratore/editor/ajax-visualizza-step/", { documento_ID: ' . $documento_ID . ', step_ID : step_ID })
      .done(function( data ) {
        $("#layoutStepScheda-" + step_ID).html(data);
      });
}

</script>';
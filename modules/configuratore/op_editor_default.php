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


<style>
.configuratoreDivCliente {
    background-color: var(--primary-color) ;
    padding: 6px;
    color: var(--primary-text-color);
}
.configuratoreTopBar{
    padding: 6px;
    
}

.configuratoreTopBarButton{
    padding: 12px;
    background-color: var(--secondary-color) ;
    color: var(--secondary-text-color) ;
    margin-right: 12px;
}

.topBarButtonSelected{
    background-color: var(--accent-color2);
    color:  var(--accent-text-color2);
    border-bottom: 1px solid #9aa2ad !important;
}

.layoutEditorSottostep {
    margin-bottom: 24px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e3e3e3;
}
</style>';

$rowTestata = mysqli_fetch_assoc($resultTestata);

echo '<script>
documento_ID = ' . $documento_ID . ';
stato        = ' . $rowTestata['stato'] . ';
console.log("Il documento ha ID " + documento_ID);
</script>';

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

if ( (int) $rowTestata['stato'] === 1) {
    echo '<div class="alert alert-primary" role="alert">
            <strong>Ordine già inviato</strong>. Qualsiasi modifica all\'ordine non sarà memorizzata.
          </div>';
}
echo '<div class="row configuratoreDivCliente">
        <div class="col-md-2" style="border-right: 1px solid white; text-align: center">
            <div style="font-size: small">Ordine</div>
            <div><span style="font-size: x-large">' . $documento_ID .'</span> del <span style="font-size: x-large">' . $rowTestata['data_ordine'] . '</span> </div>
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
<div class="row mt-5">
    <div class="col-md-9">
    ' . $stepSchede . '
    </div>
    <div class="col-md-3">
        <h2>Dimensioni</h2>
        <div class="row" style="text-align: center">
            <div class="col-md-5"><span style="font-size: xx-large">' . $configuratore->larghezza . '</span> <br/><small>mm</small></div>
            <div class="col-md-2" style="vertical-align: center;"> X </div>
            <div class="col-md-5"><span style="font-size: xx-large">' . $configuratore->lunghezza . '</span> <br/><small>mm</small></div>
        </div>
        <h2 class="mt-3">Riepilogo</h2>
        <div id="layoutRiepilogo">Caricamento in corso</div>
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
            visualizzaRiepilogo();
            ottieniTotale();
            ottieniStato();
          });';
}
echo '</script>';


echo '<script>
function mostraStep (step_ID) 
{
    console.log("[MOSTRA STEP]");
    console.log("--> step_ID: " + step_ID);
    
    $(".layoutStepScheda").hide();
    $("#layoutStepScheda-" + step_ID).show();
    
    $(".configuratoreTopBarButton").removeClass("topBarButtonSelected");
    $("#layoutStepBottone-" + step_ID).addClass("topBarButtonSelected");
    
}


function rimuoviDivMaggioreDi(currentValue) {
    // Seleziona tutti i div con ID che corrispondono al pattern editorSottostep-XX
    $(\'div[id^="editorSottostep-"]\').each(function() {
        // Ottieni il valore dell\'attributo ID
        var idValue = $(this).attr(\'id\');

        // Estrai il numero dall\'ID usando una regex
        var idNumber = parseInt(idValue.replace(\'editorSottostep-\', \'\'), 10);

        // Verifica se il numero è maggiore di currentValue
        if (idNumber > currentValue) {
            // Rimuovi il div corrente
            $(this).remove();
        }
    });
}

function cambiaSingolaOpzione(linea_ID, opzione_ID, step_ID, sottostep_ID) 
{
    console.log ("[CAMBIO OPZIONE]");    
    console.log ("--> Linea ID: "   + linea_ID);    
    console.log ("--> opzione ID: " + opzione_ID);
    
    if (stato === 1)
        return;

    var hasGreaterSelect = false;

    // Itera attraverso tutte le select con l\'attributo aria-progressivo
    $(\'select[aria-progressivo]\').each(function() {
        // Ottieni il valore dell\'attributo aria-progressivo della select corrente
        var progressivoValue = $(this).attr(\'aria-progressivo\');
        
        // Converte il valore dell\'attributo in un numero intero per il confronto
        progressivoValue = parseInt(progressivoValue, 10);

        // Verifica se il valore è maggiore del valore corrente
        if (progressivoValue > linea_ID) {
            hasGreaterSelect = true;
            return false; // Interrompe il ciclo each se trova un valore maggiore
        }
    });

    // Esegui la tua logica in base al risultato
    if (hasGreaterSelect) {
        console.log(\'Ci sono select con aria-progressivo maggiore di \' + linea_ID);
        
        if (!confirm ("Il cambio di questa opzione cancellerà le opzioni successive. Confermare?")) {
            return;   
        } else {
            rimuoviDivMaggioreDi(linea_ID);
        }
    }
    
    
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
            visualizzaRiepilogo();
            
        }
        
        ottieniTotale();
        ottieniStato();
      });
}

function ottieniUltimoStep () 
{
    console.log ("[OTTIENI ULTIMO STEP]");
    $.post( jsPath + "configuratore/editor/ajax-ottieni-ultimo-step/", { documento_ID: ' . $documento_ID . ' })
      .done(function( data ) {
        console.log("--> Dati ricevuti " + data);
        obj = JSON.parse(data);
        console.log ("--> ID ottenuto " + obj.step_ID);
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
    
    if (stato === 1)
        return;
    
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

function visualizzaRiepilogo () {
    console.log ("[VISUALIZZA RIEPILOGO]");
    
    $.post( jsPath + "configuratore/editor/ajax-visualizza-riepilogo/", { documento_ID: ' . $documento_ID . ' })
      .done(function( data ) {
        $("#layoutRiepilogo").html(data);
    });
}

function ottieniStato() 
{
    console.log ("[OTTIENI STATO]");
   
    $.post( jsPath + "configuratore/editor/ajax-ottieni-stato/", { documento_ID: ' . $documento_ID . ' })
      .done(function( data ) {
        if (parseInt(data) === 1) {
            $("#btnFinalizza").prop(\'disabled\', false);
        } else {
            $("#btnFinalizza").prop(\'disabled\', true);
        }
      });
}

function finalizzaDocumento( ) {
    if (!confirm("Attenzione, i documenti finalizzati non potranno più essere aperti. Continuare?"))
        return;
    
    if (stato === 1)
        return;
    
    location.href = jsPath + "configuratore/editor/finalizza/?documento_ID=' . $documento_ID . '";
}
</script>';
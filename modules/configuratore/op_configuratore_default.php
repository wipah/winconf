<?php

if (!$user->validateLogin())
    return;

echo '<h2>Crea nuovo documento</h2>';


$query = 'SELECT * FROM clienti';

if (!$resultClient = $db->query($query)) {
    echo 'Errore nella query ' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Non esistono clienti';
    return;
}

$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore/">Configuratore</a>';
$this->title = 'Configuratore';

echo '
<div class="row">
    <div class="col-md-2"><strong>Cliente</strong></div>
    <div class="col-md-10"><select class="form form-control" id="cliente" name="cliente">';

while ($lineaClienti = mysqli_fetch_assoc($resultClient)) {
    echo    '<option value="' . $lineaClienti['ID'] . '">' . $lineaClienti['ragione_sociale'] . '</option>';
}
echo '</select>
</div>
</div>';

$query = 'SELECT * 
          FROM configuratore_categorie WHERE visibile = 1 
          ORDER by ordine ASC';

if (!$result = $db->query($query)) {
    echo 'Query error';
    return;
}

if (!$db->affected_rows) {
    echo 'Non esistono categorie';
    return;
}

echo '
<div class="row mt-3">
    <div class="col-md-2"><strong>Categoria</strong></div>
    <div class="col-md-10">
  <select class="form-control" id="categoria" name="categoria">';
 while ($row = mysqli_fetch_assoc($result)) {
    echo '<option class="" value="' . $row['ID'] . ' ">' . $row['categoria_nome'] . '</option>';
}
echo '</select>
    </div>
</div>



<div class="clearfix">
    <button id="configuratoreCreaProgetto" class="btn btn-info float-right" onclick="creaProgetto();">Crea progetto</button>
</div>
<div id="log"></div>
<script>

function creaProgetto() {
    console.log("*** Creazione progetto ***");

    cliente   = $("#cliente").val();
    categoria = $("#categoria").val();
    
    $("configuratoreCreaProgetto").attr("disabled", true)
    $.post("' . $conf['URI'] . 'configuratore/editor/nuovo/", {  categoria  : categoria,
                                                                 cliente    : cliente,
                                                                 })
      .done(function( data ) {
          location.href = jsPath + "configuratore/editor/?ID=" + data;
        $("#log").html(data);
      });
}
</script>
';
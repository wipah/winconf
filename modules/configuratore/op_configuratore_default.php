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

$this->menuItems[] = '<a href="' . htmlspecialchars($conf['URI']) . 'configuratore/">Configuratore</a>';
$this->title = 'Configuratore';

echo '
<div class="row">
    <div class="col-md-2"><strong>Cliente</strong></div>
    <div class="col-md-10">
        <select class="form-control" id="cliente" name="cliente">
';

while ($lineaClienti = mysqli_fetch_assoc($resultClient)) {
    // Utilizza htmlspecialchars per prevenire attacchi XSS
    echo '<option value="' . htmlspecialchars($lineaClienti['ID']) . '">' . htmlspecialchars($lineaClienti['ragione_sociale']) . '</option>';
}

echo '</select>
    </div>
</div>';

// Query per recuperare le categorie con le relative immagini visibili
$query = 'SELECT CATEGORIE.*, MEDIA.filename
          FROM configuratore_categorie CATEGORIE
          LEFT JOIN configuratore_media MEDIA
          ON MEDIA.IDX = CATEGORIE.ID 
          AND MEDIA.contesto_ID = 1
          AND MEDIA.visibile = 1
          WHERE CATEGORIE.visibile = 1 
          ORDER BY CATEGORIE.ordine ASC';

if (!$result = $db->query($query)) {
    echo 'Errore nella query delle categorie.';
    return;
}

if (!$db->affected_rows) {
    echo 'Non esistono categorie.';
    return;
}

// Aggiungi CSS personalizzato per le card selezionate e per la standardizzazione delle immagini
echo '
<style>
    .category-card {
        cursor: pointer;
        transition: transform 0.2s, border-color 0.2s;
        height: 100%;
        position: relative;
    }
    .category-card:hover {
        transform: scale(1.05);
    }
    .category-card.selected {
        border: 2px solid #007bff;
    }
    .category-card img {
        width: 100%;
        height: 200px; /* Altezza fissa per tutte le immagini */
        object-fit: cover; /* Ritaglia l\'immagine per riempire l\'area */
    }
    .card-body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 60px; /* Altezza fissa per il corpo della card */
    }
    /* Opzionale: Aggiungi un overlay per indicare la selezione */
    .category-card.selected::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 2px solid #007bff;
        box-sizing: border-box;
    }
</style>
';

echo '
<div class="row mt-3">
    <div class="col-md-2"><strong>Categoria</strong></div>
    <div class="col-md-10">
        <div class="row" id="categoriaCards">
';

while ($row = mysqli_fetch_assoc($result)) {
    $categoryID = htmlspecialchars($row['ID']);
    $categoryName = htmlspecialchars($row['categoria_nome']);

    // Verifica se esiste un'immagine associata, altrimenti usa un'immagine di default
    if (!empty($row['filename'])) {
        // Costruisci il percorso completo dell'immagine
        $imageRelativePath = 'modules/media/uploads/' . htmlspecialchars($row['filename']);
        $imageFullPath = $conf['path'] . $imageRelativePath;
        $imageURL = rtrim($conf['URI'], '/') . '/' . $imageRelativePath;

        // Verifica se il file esiste sul server
        if (!file_exists($imageFullPath)) {
            // Se il file non esiste, usa l\'immagine di default
            $defaultImageRelativePath = 'modules/media/uploads/default/image.jpg'; // Sostituisci con il percorso reale dell\'immagine di default
            $imageURL = rtrim($conf['URI'], '/') . '/' . $defaultImageRelativePath;
        }
    } else {
        // Usa l\'immagine di default se non c\'è alcuna immagine associata
        $defaultImageRelativePath = 'modules/media/uploads/default/image.jpg'; // Sostituisci con il percorso reale dell\'immagine di default
        $imageURL = rtrim($conf['URI'], '/') . '/' . $defaultImageRelativePath;
    }

    echo '
        <div class="col-md-4 mb-3">
            <div class="card category-card" data-category-id="' . $categoryID . '">
                <img src="' . htmlspecialchars($imageURL) . '" class="card-img-top" alt="' . $categoryName . '">
                <div  class="card-body d-flex flex-column">
                    <h5 class="text-center">' . $categoryName . '</h5>
                    <div style="text-align: left !important;" class="text-left mt-2">' . $row['categoria_descrizione']  .'</div>
                </div>
            </div>
        </div>
    ';
}

echo '
        </div>
    </div>
</div>
';

echo '
<div class="clearfix">
    <button id="configuratoreCreaProgetto" class="btn btn-info float-right" onclick="creaProgetto();">Crea progetto</button>
</div>
<div id="log"></div>
<script>
$(document).ready(function(){
    // Gestione della selezione/deselezione delle card
    $(".category-card").on("click", function(){
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
        } else {
            $(".category-card").removeClass("selected");
            $(this).addClass("selected");
        }
    });
});

function creaProgetto() {
    console.log("*** Creazione progetto ***");

    var cliente = $("#cliente").val();
    var categoria = $(".category-card.selected").data("category-id");

    if (!categoria) {
        alert("Per favore, seleziona una categoria.");
        return;
    }

    $("#configuratoreCreaProgetto").attr("disabled", true);
    $.post("' . htmlspecialchars($conf['URI']) . 'configuratore/editor/nuovo/", {  
            categoria: categoria,
            cliente: cliente
        })
      .done(function(data) {
          location.href = "' . htmlspecialchars($conf['URI']) . 'configuratore/editor/?ID=" + data;
          $("#log").html(data);
      })
      .fail(function(xhr, status, error) {
          alert("Errore durante la creazione del progetto: " + error);
          $("#configuratoreCreaProgetto").attr("disabled", false);
      });
}
</script>
';

$(document).ready(function() {
    // Funzione per gestire l'upload
    $(".upload-system").each(function() {
        var $uploadSystem = $(this);
        var contesto_ID = $uploadSystem.data("contesto-id");
        var IDX = $uploadSystem.data("idx");
        var tipo_editor = $uploadSystem.data("tipo-editor");

        // Imposta l'attributo 'accept' in base a tipo_editor
        var $fileInput = $uploadSystem.find(".upload-input");
        if (tipo_editor == 1) {
            // Solo immagini
            $fileInput.attr("accept", "image/*");
        } else if (tipo_editor == 2) {
            // Immagini, PDF, Excel, Word, e altri
            $fileInput.attr("accept", "image/*,.pdf,.xlsx,.xls,.doc,.docx,.ppt,.pptx,.txt,.csv");
        }

        // Gestione del click sul pulsante "Carica"
        $uploadSystem.find(".upload-button").on("click", function() {
            var $input = $uploadSystem.find(".upload-input");
            var files = $input[0].files;

            if (files.length === 0) {
                alert("Seleziona almeno un file da caricare.");
                return;
            }

            var formData = new FormData();
            formData.append("contesto_ID", contesto_ID);
            formData.append("IDX", IDX);
            formData.append("tipo_editor", tipo_editor);

            if (tipo_editor == 1) {
                // Upload singolo file
                formData.append("file", files[0]);
            } else if (tipo_editor == 2) {
                // Upload multiplo file
                $.each(files, function(i, file) {
                    formData.append("files[]", file);
                });
            }

            // Determina l'URL corretto per l'upload usando forward slashes
            var uploadUrl = tipo_editor == 1 ? jsPath + "media/upload/" : jsPath + "media/uploadmedia/";

            $.ajax({
                url: uploadUrl,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success) {
                        // Aggiorna la lista dei media
                        loadMedia($uploadSystem);
                        // Resetta l'input file
                        $input.val('');
                    } else {
                        alert(response.error);
                    }
                },
                error: function() {
                    alert("Errore durante il caricamento.");
                }
            });
        });

        // Carica i media al caricamento della pagina
        loadMedia($uploadSystem);
    });

    // Funzione per caricare i media esistenti
    function loadMedia($uploadSystem) {
        var contesto_ID = $uploadSystem.data("contesto-id");
        var IDX = $uploadSystem.data("idx");
        var tipo_editor = $uploadSystem.data("tipo-editor");

        $.ajax({
            url: jsPath + "media/load_media/", // Usa forward slashes
            type: "GET",
            data: { contesto_ID: contesto_ID, IDX: IDX },
            success: function(response) {
                if(response.success) {
                    var mediaList = $uploadSystem.find(".media-list");
                    mediaList.empty();
                    $.each(response.data, function(index, media) {
                        var visibilityChecked = media.visibile == 1 ? 'checked' : '';
                        var visibilityClass = media.visibile == 1 ? 'visible' : 'hidden';
                        var listItem = '<li class="media-item ' + visibilityClass + '" data-id="' + media.ID + '">' +
                            getMediaPreview(media) +
                            '<div class="mt-2">' +
                            '<label>' +
                            '<input type="checkbox" class="visibility-toggle" data-id="' + media.ID + '" ' + visibilityChecked + '> Visibile' +
                            '</label>' +
                            ' ' +
                            '<button class="btn btn-danger btn-sm delete-media">Elimina</button>' +
                            '</div>' +
                            '</li>';
                        mediaList.append(listItem);
                    });

                    // Abilita il drag & drop solo se tipo_editor è 2 (multiplo)
                    if(tipo_editor == 2) {
                        mediaList.sortable({
                            update: function(event, ui) {
                                var orderedIDs = $(this).sortable("toArray", { attribute: "data-id" });
                                updateOrder($uploadSystem, orderedIDs);
                            }
                        });
                    } else {
                        mediaList.sortable("destroy"); // Disabilita sortable per tipo_editor=1
                    }
                } else {
                    alert(response.error);
                }
            },
            error: function() {
                alert("Errore nel caricamento dei media.");
            }
        });
    }

    // Funzione per generare l'anteprima del media
    function getMediaPreview(media) {
        var preview = "";
        var fileUrl = jsPath + "media/uploads/" + media.filename; // Usa forward slashes
        var ext = media.estensione.toLowerCase(); // Assicurati che l'estensione sia in minuscolo

        if (/^(jpg|jpeg|png|gif|webp)$/.test(ext)) {
            // Anteprima per immagini
            preview = '<img src="' + fileUrl + '" alt="' + media.filename + '" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">';
        } else if (/^pdf$/.test(ext)) {
            // Anteprima per PDF
            preview = '<a href="' + fileUrl + '" target="_blank">PDF</a>';
        } else if (/^(xlsx|xls)$/.test(ext)) {
            // Anteprima per file Excel
            preview = '<a href="' + fileUrl + '" target="_blank">Excel</a>';
        } else if (/^(doc|docx)$/.test(ext)) {
            // Anteprima per file Word
            preview = '<a href="' + fileUrl + '" target="_blank">Word</a>';
        } else {
            // Anteprima per altri tipi di file
            preview = '<a href="' + fileUrl + '" target="_blank">' + media.filename + '</a>';
        }

        return preview;
    }

    // Funzione per aggiornare l'ordine dei media
    function updateOrder($uploadSystem, orderedIDs) {
        var contesto_ID = $uploadSystem.data("contesto-id");
        var IDX = $uploadSystem.data("idx");

        $.ajax({
            url: jsPath + "media/reordermedia/", // Usa forward slashes
            type: "POST",
            data: { contesto_ID: contesto_ID, IDX: IDX, orderedIDs: orderedIDs },
            success: function(response) {
                if(!response.success) {
                    alert("Errore nell'aggiornamento dell'ordine.");
                }
            },
            error: function() {
                alert("Errore nell'aggiornamento dell'ordine.");
            }
        });
    }

    // Funzione per eliminare un media
    $(document).on("click", ".delete-media", function() {
        var $mediaItem = $(this).closest(".media-item");
        var mediaID = $mediaItem.data("id");
        var $uploadSystem = $mediaItem.closest(".upload-system");

        $.ajax({
            url: jsPath + "media/deletemedia/", // Usa forward slashes
            type: "POST",
            data: { ID: mediaID },
            success: function(response) {
                if(response.success) {
                    loadMedia($uploadSystem);
                } else {
                    alert(response.error);
                }
            },
            error: function() {
                alert("Errore durante l'eliminazione.");
            }
        });
    });

    // Funzione per aggiornare la visibilità di un media
    $(document).on("change", ".visibility-toggle", function() {
        var mediaID = $(this).data("id");
        var visibile = $(this).is(":checked") ? 1 : 0;
        var $mediaItem = $(this).closest(".media-item");
        var $checkbox = $(this); // Salva il riferimento al checkbox

        $.ajax({
            url: jsPath + "media/update_visibility/", // Assicurati che questo URL sia corretto
            type: "POST",
            data: { ID: mediaID, visibile: visibile },
            success: function(response) {
                if(response.success) {
                    if(visibile == 1) {
                        $mediaItem.removeClass("hidden").addClass("visible");
                    } else {
                        $mediaItem.removeClass("visible").addClass("hidden");
                    }
                } else {
                    alert(response.error);
                    // Reverti lo stato del checkbox in caso di errore
                    $checkbox.prop('checked', !visibile);
                }
            },
            error: function() {
                alert("Errore nell'aggiornamento della visibilità.");
                // Reverti lo stato del checkbox in caso di errore
                $checkbox.prop('checked', !visibile);
            }
        });
    });
});
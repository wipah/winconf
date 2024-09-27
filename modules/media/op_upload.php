<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

// media/upload.php
header('Content-Type: application/json');

$response = ['success' => false];

// Verifica il metodo della richiesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i parametri
    $contesto_ID = isset($_POST['contesto_ID']) ? intval($_POST['contesto_ID']) : null;
    $IDX = isset($_POST['IDX']) ? intval($_POST['IDX']) : null;
    $tipo_editor = isset($_POST['tipo_editor']) ? intval($_POST['tipo_editor']) : null;

    if (!$contesto_ID || !$IDX || !$tipo_editor) {
        $response['error'] = 'Parametri mancanti.';
        echo json_encode($response);
        exit;
    }

    // Determina i tipi di file consentiti in base a tipo_editor
    if ($tipo_editor == 1) {
        // Solo immagini
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    } else if ($tipo_editor == 2) {
        // Documenti multipli: immagini, PDF, Excel, altro
        $allowed_types = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            // Aggiungi altri tipi MIME se necessario
        ];
    } else {
        $response['error'] = 'Tipo editor non valido.';
        echo json_encode($response);
        exit;
    }

    // Gestione singolo upload
    if ($tipo_editor == 1 && isset($_FILES['file'])) {
        $file = $_FILES['file'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            // Verifica il tipo MIME
            if (!in_array($file['type'], $allowed_types)) {
                $response['error'] = 'Tipo di file non consentito: ' . htmlspecialchars($file['name']);
                echo json_encode($response);
                exit;
            }

            // Ottieni l'estensione
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeName = uniqid() . '.' . strtolower($ext);
            $destination = __DIR__ . '/uploads/' . $safeName; // Aggiorna il percorso se necessario

            // Sposta il file
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Determina l'ordine
                $result = $db->query("SELECT MAX(ordine) as max_order FROM configuratore_media WHERE contesto_ID = $contesto_ID AND IDX = $IDX");
                $row = $result->fetch_assoc();
                $ordine = ($row['max_order'] !== null) ? $row['max_order'] + 1 : 1;

                // Inserisci nel DB
                $stmt = $db->prepare('INSERT INTO configuratore_media (IDX, contesto_ID, filename, estensione, ordine, visibile) VALUES (?, ?, ?, ?, ?, 1)');
                if (!$stmt) {
                    $response['error'] = 'Errore nella preparazione della query.';
                    echo json_encode($response);
                    exit;
                }
                $stmt->bind_param('iissi', $IDX, $contesto_ID, $safeName, $ext, $ordine);
                if ($stmt->execute()) {
                    $stmt->close();
                    $response['success'] = true;
                } else {
                    $response['error'] = 'Errore nell\'inserimento nel database.';
                }
            } else {
                $response['error'] = 'Errore nel salvataggio del file: ' . htmlspecialchars($file['name'] . ' verso ' . htmlspecialchars($destination));
            }
        } else {
            $response['error'] = 'Errore durante l\'upload del file: ' . htmlspecialchars($file['name']);
        }
    } else {
        $response['error'] = 'File non inviati correttamente.';
    }

    echo json_encode($response);
} else {
    $response['error'] = 'Metodo non supportato.';
    echo json_encode($response);
}
?>

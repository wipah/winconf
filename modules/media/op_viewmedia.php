<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

// load_media.php
header('Content-Type: application/json');

$response = ['success' => false];

// Verifica il metodo della richiesta
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recupera i parametri
    $contesto_ID = isset($_GET['contesto_ID']) ? intval($_GET['contesto_ID']) : null;
    $IDX = isset($_GET['IDX']) ? intval($_GET['IDX']) : null;

    if (!$contesto_ID || !$IDX) {
        $response['error'] = 'Parametri mancanti.';
        echo json_encode($response);
        exit;
    }

    // Query per ottenere i media
    $stmt = $db->prepare('SELECT ID, filename, estensione FROM configuratore_media WHERE contesto_ID = ? AND IDX = ? ORDER BY ordine ASC');
    if ($stmt) {
        $stmt->bind_param('ii', $contesto_ID, $IDX);
        $stmt->execute();
        $result = $stmt->get_result();

        $media = [];
        while ($row = $result->fetch_assoc()) {
            $media[] = $row;
        }

        $response['success'] = true;
        $response['data'] = $media;
        $stmt->close();
    } else {
        $response['error'] = 'Errore nella preparazione della query.';
    }

    echo json_encode($response);
} else {
    $response['error'] = 'Metodo non supportato.';
    echo json_encode($response);
}
?>

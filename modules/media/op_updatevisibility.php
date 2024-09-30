<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

// modules/media/update_visibility.php
header('Content-Type: application/json');

$response = ['success' => false];

// Verifica il metodo della richiesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i parametri
    $ID = isset($_POST['ID']) ? intval($_POST['ID']) : null;
    $visibile = isset($_POST['visibile']) ? intval($_POST['visibile']) : null; // 1 per visibile, 0 per nascosto

    if ($ID === null || ($visibile !== 0 && $visibile !== 1)) {
        $response['error'] = 'Parametri mancanti o non validi.';
        echo json_encode($response);
        exit;
    }

    // Aggiorna il campo 'visibile' nel database
    $stmt = $db->prepare('UPDATE configuratore_media SET visibile = ? WHERE ID = ?');
    if ($stmt) {
        $stmt->bind_param('ii', $visibile, $ID);
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Errore nell\'aggiornamento del database.';
        }
        $stmt->close();
    } else {
        $response['error'] = 'Errore nella preparazione della query.';
    }

    echo json_encode($response);
} else {
    $response['error'] = 'Metodo non supportato.';
    echo json_encode($response);
}


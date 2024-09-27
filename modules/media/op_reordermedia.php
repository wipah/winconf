<?php


$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

// media/reordermedia.php
header('Content-Type: application/json');

$response = ['success' => false];

// Verifica il metodo della richiesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i parametri
    $contesto_ID = isset($_POST['contesto_ID']) ? intval($_POST['contesto_ID']) : null;
    $IDX = isset($_POST['IDX']) ? intval($_POST['IDX']) : null;
    $orderedIDs = isset($_POST['orderedIDs']) ? $_POST['orderedIDs'] : [];

    if (!$contesto_ID || !$IDX || !is_array($orderedIDs)) {
        $response['error'] = 'Parametri mancanti o non validi.';
        echo json_encode($response);
        exit;
    }

    // Inizia una transazione
    $db->begin_transaction();

    try {
        $stmt = $db->prepare('UPDATE configuratore_media SET ordine = ? WHERE ID = ? AND contesto_ID = ? AND IDX = ?');
        if (!$stmt) {
            throw new Exception('Errore nella preparazione della query di aggiornamento.');
        }

        foreach ($orderedIDs as $order => $ID) {
            $currentOrder = $order + 1;
            $stmt->bind_param('iiii', $currentOrder, $ID, $contesto_ID, $IDX);
            if (!$stmt->execute()) {
                throw new Exception('Errore nell\'esecuzione della query di aggiornamento.');
            }
        }

        $stmt->close();
        $db->commit();
        $response['success'] = true;
    } catch (Exception $e) {
        $db->rollback();
        $response['error'] = 'Errore durante l\'aggiornamento dell\'ordine: ' . $e->getMessage();
    }

    echo json_encode($response);
} else {
    $response['error'] = 'Metodo non supportato.';
    echo json_encode($response);
}

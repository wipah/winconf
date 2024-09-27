<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

// media/deletemedia.php
header('Content-Type: application/json');

$response = ['success' => false];

// Verifica il metodo della richiesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera l'ID del media
    $ID = isset($_POST['ID']) ? intval($_POST['ID']) : null;

    if (!$ID) {
        $response['error'] = 'ID mancante.';
        echo json_encode($response);
        exit;
    }

    // Ottieni il filename dal database
    $stmt = $db->prepare('SELECT filename FROM configuratore_media WHERE ID = ?');
    if ($stmt) {
        $stmt->bind_param('i', $ID);
        $stmt->execute();
        $stmt->bind_result($filename);
        if ($stmt->fetch()) {
            $stmt->close();

            // Elimina il file dal filesystem
            $filePath = '../uploads/' . $filename; // Aggiorna il percorso se necessario
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Elimina la registrazione dal DB
            $stmt = $db->prepare('DELETE FROM configuratore_media WHERE ID = ?');
            if ($stmt) {
                $stmt->bind_param('i', $ID);
                if ($stmt->execute()) {
                    $response['success'] = true;
                } else {
                    $response['error'] = 'Errore nell\'eliminazione dal database.';
                }
                $stmt->close();
            } else {
                $response['error'] = 'Errore nella preparazione della query di eliminazione.';
            }
        } else {
            $response['error'] = 'Media non trovato.';
            $stmt->close();
        }
    } else {
        $response['error'] = 'Errore nella preparazione della query.';
    }

    echo json_encode($response);
} else {
    $response['error'] = 'Metodo non supportato.';
    echo json_encode($response);
}

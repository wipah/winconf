<?php
$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;
$documento_ID = (int) $_POST['documento_ID'];

$query = 'SELECT *
FROM documenti_corpo
WHERE documento_ID = ' . $documento_ID . '
AND valorizzata = 0 AND visibile = 1 AND esclusa = 0
ORDER BY ID ASC
LIMIT 1';

if (!$result = $db->query($query)) {
    echo json_encode(['status' => -1, 'message' => 'Errore nella query di selezione dell\'ultimo step']);
    return;
}

$row = mysqli_fetch_assoc($result);


echo json_encode(['status' => 1, 'step_ID' => $row['step_ID']]);
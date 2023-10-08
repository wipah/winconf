<?php
if (!$core)
    die("Accesso diretto");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

$categoria_ID = (int) $_POST['categoria_ID'];
$step_ID = (int) $_POST['step_ID'];
$sottostep_ID = (int) $_POST['sottostep_ID'];

$sottostepNome          =   $core->in($_POST['sottostepNome']);
$sottostepSigla         =   $core->in($_POST['sottostepSigla']);
$sottostepDescrizione   =   $core->in($_POST['sottostepDescrizione']);
$sottostepTipoScelta    =   (int) $_POST['sottostepTipoScelta'];
$sottostepDipendenza    =   (int) $_POST['sottostepDipendenza'];
$sottostepDimensioni   =   (int) $_POST['sottostepDimensioni'];
$sottostepVisibile      =   (int) $_POST['sottostepVisibile'];

$query = 'SELECT MAX(ordine) ordine 
          FROM configuratore_sottostep 
          WHERE step_ID = ' . $step_ID;

$risultatoStep = $db->query($query);
$rowStep = mysqli_fetch_assoc($risultatoStep);

$ordine = $rowStep['ordine'] + 1;

if ($sottostep_ID === 0 ) {
    $query = 'INSERT INTO configuratore_sottostep 
                (
                    categoria_ID,
                    step_ID,
                    sottostep_nome, 
                    sottostep_sigla, 
                    sottostep_descrizione, 
                    tipo_scelta, 
                    check_dipendenze,
                    check_dimensioni,
                    ordine,
                    visibile
                )
                 VALUES 
                (
                        \'' . $categoria_ID  . '\' 
                    ,   \'' . $step_ID  . '\' 
                    ,   \'' . $sottostepNome  . '\' 
                    ,   \'' . $sottostepSigla  . '\' 
                    ,   \'' . $sottostepDescrizione  . '\' 
                    ,   \'' . $sottostepTipoScelta  . '\' 
                    ,   \'' . $sottostepDipendenza  . '\' 
                    ,   \'' . $sottostepDimensioni  . '\' 
                    ,   \'' . $ordine  . '\' 
                    ,   \'' . $sottostepVisibile  . '\' 
                )';
} else {
    $query = '
    UPDATE configuratore_sottostep 
        SET   sottostep_nome          = \'' . $sottostepNome . '\'
            , sottostep_sigla         = \'' . $sottostepSigla . '\'
            , sottostep_descrizione   = \'' . $sottostepDescrizione . '\'
            , tipo_scelta             = \'' . $sottostepTipoScelta . '\'
            , check_dipendenze        = \'' . $sottostepDipendenza . '\'
            , check_dimensioni        = \'' . $sottostepDimensioni . '\'
            , visibile                = \'' . $sottostepVisibile . '\'
    WHERE ID = ' . $sottostep_ID . ' 
    LIMIT 1';
}

echo $query;

if (!$db->query($query)) {
    echo 'Query error';
}
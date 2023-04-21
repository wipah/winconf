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
$sottostepVisibile      =   (int) $_POST['sottostepVisibile'];

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
            , visibile                = \'' . $sottostepVisibile . '\'
    WHERE ID = ' . $sottostep_ID . ' 
    LIMIT 1';
}

echo $query;

if (!$db->query($query)) {
    echo 'Query error';
}
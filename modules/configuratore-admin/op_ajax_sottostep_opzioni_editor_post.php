<?php

$this->noTemplateParse = true;

var_dump($_POST);

if (!$sottostep_ID = (int) $_POST['sottostep_ID']) {
    echo 'Manca il sottostep';
    return;
}

$ID = (int) $_POST['ID'];

$nome               =   $core->in($_POST['nome']);
$sigla              =   $core->in($_POST['sigla']);
$descrizione        =   $core->in($_POST['descrizione']);
$checkDipendenze    =   (int) $_POST['checkDipendenze'];
$checkDimensioni    =   (int) $_POST['checkDimensioni'];
$formula            =   (int) $_POST['formula'];
$valoreFormula      =   (float) $_POST['valoreFormula'];
$visibile           =   (int) $_POST['visibile'];

if ($ID === 0 ) {
    $query = 'INSERT INTO configuratore_opzioni 
                (
                   sottostep_ID
                 , opzioni_formula_ID
                 , opzione_nome
                 , opzione_sigla
                 , opzione_descrizione
                 , check_dipendenze
                 , check_dimensioni
                 , opzione_formula_valore
                 , visibile
                ) VALUES 
                (   \'' . $sottostep_ID . '\'
                 ,   \'' . $formula  . '\' 
                 ,  \'' . $nome  . '\' 
                 ,  \'' . $sigla  . '\' 
                 ,  \'' . $descrizione  . '\' 
                 ,  \'' . $checkDipendenze  . '\' 
                 ,  \'' . $checkDimensioni  . '\' 
                 ,  \'' . $valoreFormula  . '\' 
                 ,  \'' . $visibile  . '\' 
                );';
} else {
    $query = 'UPDATE configuratore_opzioni SET
                  opzioni_formula_ID = ' . $formula . '
                 , opzione_nome = \'' . $nome . '\'
                 , sigla = \'' . $sigla . '\'
                 , opzione_descrizione = \'' . $descrizione . '\'
                 , check_dipendenze = ' . $checkDipendenze . '
                 , check_dimensioni = ' . $checkDimensioni . '
                 , opzione_formula_valore = ' . $valoreFormula . '
                 , visibile = ' . $visibile . '
                WHERE ID = ' . $ID .  ' LIMIT 1 ';
}

if (!$db->query($query)) {
    echo '--KO-- '. $query;
    return;
}

echo '--KO--';

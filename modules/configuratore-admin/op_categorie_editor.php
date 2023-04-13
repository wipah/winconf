<?php
if (!$core)
    die("Accesso diretto rilevato");

echo '<h1>Editor categoria</h1>';

if (isset($_GET['save'])) {
    $nome        = $core->in($_POST['nome']);
    $sigla       = $core->in($_POST['sigla']);
    $descrizione = $core->in($_POST['descrizione']);
    $visibile    = (int) $_POST['visibile'];
}

if ($ID = (int) ($_GET['ID'])) {

    if (isset($_GET['save'])) {
        $query = 'UPDATE configuratore_categorie 
                  SET  categoria_descrizione = \'' . $descrizione . '\'
                     , categoria_sigla       = \''.  $sigla. '\'
                     , categoria_nome        = \''.  $nome . '\'
                     , visibile              = '.  $visibile . '
                  WHERE ID = ' . $ID . '
                  LIMIT 1';
        if ($db->query($query)){
            $this->getBox('danger', 'Errore nella query. ' . $query);
        } else {
            $this->getBox('info', 'Categoria modificata con successo. ' . $query);
        }
    }

    $action = $conf['URI'] . 'configuratore-admin/categorie/editor/?ID=' . $ID . '&save';
    $button = 'Salva le modifiche';

    $query = 'SELECT * 
              FROM configuratore_categorie 
              WHERE ID = ' . $ID . ' 
              LIMIT 1';

    if (!$result = $db->query($query)) {
        echo 'Query error. ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo $this->getBox('warning', 'Non è stato trovata nessuna categoria con l\'ID ' . $ID);
        return;
    } else {
        $row = mysqli_fetch_assoc($result);
    }
} else {
    if (isset($_GET['save'])) {
        $query = 'INSERT INTO configuratore_categorie 
                  (
                      categoria_nome 
                    , categoria_sigla 
                    , categoria_descrizione 
                    , visibile 
                  ) 
                  VALUES 
                  (
                      \'' . $nome . '\'
                    , \'' . $sigla . '\'
                    , \'' . $descrizione . '\'
                    , \'' . $visibile . '\'
                  )';

        if (!$db->query($query)) {
            echo $this->getBox('danger', 'Query error. ' . $query);
        } else {
            echo $this->getBox('info', 'Categoria creata con successo');
            return;
        }

    }
    $action = $conf['URI']. 'configuratore-admin/categorie/editor/?save';
    $button = 'Salva la categoria';
}

echo '
<form method="post" action="'.$action.'">
  <div class="form-group row">
    <label for="nome" class="col-4 col-form-label">Nome</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-columns"></i>
          </div>
        </div> 
        <input value="' . $row['categoria_nome'] . '" id="nome" name="nome" placeholder="Nome della categoria" type="text" class="form-control" aria-describedby="nomeHelpBlock" required="required">
      </div> 
      <span id="nomeHelpBlock" class="form-text text-muted">Nome della categoria</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="sigla" class="col-4 col-form-label">Sigla</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-table"></i>
          </div>
        </div> 
        <input value="' . $row['categoria_sigla'] . '" id="sigla" name="sigla" placeholder="Sigla breve della categoria" type="text" class="form-control" aria-describedby="siglaHelpBlock" required="required">
      </div> 
      <span id="siglaHelpBlock" class="form-text text-muted">Sigla breve della categoria.</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="descrizione" class="col-4 col-form-label">Descrizione estesa</label> 
    <div class="col-8">
      <textarea id="descrizione" name="descrizione" cols="40" rows="5" class="form-control" aria-describedby="descrizioneHelpBlock" required="required">' . $row['categoria_descrizione'] .'</textarea> 
      <span id="descrizioneHelpBlock" class="form-text text-muted">Descrizione estesa della categoria</span>
    </div>
  </div>
  
  <div class="form-group row">
    <label for="select" class="col-4 col-form-label">Visibile</label> 
    <div class="col-8">
      <select id="select" name="visibile" class="custom-select">
        <option ' . (  (int) $row['visibile'] === 0 ? 'selected' : '' ).' value="0">Non visibile</option>
        <option ' . (  (int) $row['visibile'] === 1 ? 'selected' : '' ).' value="1">Visibile</option>
      </select>
    </div>
  </div> 
   
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">' . $button  . '</button>
    </div>
  </div>
</form>';
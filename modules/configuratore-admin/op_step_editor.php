<?php
if (!$core)
    die("Accesso diretto rilevato");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/">Backend</a>';
$this->menuItems[] = '<em>Step editor</em>';

echo '<h1>Step editor</h1>';
$this->title = 'WinConf - Editor step';

if (isset($_GET['save'])) {
    $nome = $core->in($_POST['nome']);
    $sigla = $core->in($_POST['sigla']);
    $descrizione = $core->in($_POST['descrizione']);
    $formulaValore = (float)$_POST['formula_valore'];
    $formula = (int)$_POST['formula'];
    $visibile = (int)$_POST['visibile'];
}

if ($ID = (int)($_GET['ID'])) {

    if (isset($_GET['save'])) {
        $query = 'UPDATE configuratore_step 
                  SET  step_descrizione        = \'' . $descrizione . '\'
                     , step_sigla              = \'' . $sigla . '\'
                     , step_nome               = \'' . $nome . '\'
                     , visibile                     = ' . $visibile . '
                  WHERE ID = ' . $ID . '
                  LIMIT 1';
        if ($db->query($query)) {
            $this->getBox('danger', 'Errore nella query. ' . $query);
        } else {
            $this->getBox('info', 'Categoria modificata con successo. ' . $query);
        }
    }

    $action = $conf['URI'] . 'configuratore-admin/step/editor/?ID=' . $ID . '&save';
    $button = 'Salva lo step';

    $query = 'SELECT * 
              FROM configuratore_step 
              WHERE ID = ' . $ID . ' 
              LIMIT 1';

    if (!$result = $db->query($query)) {
        echo 'Query error. ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo $this->getBox('warning', 'Non è stato trovata nessuno step con l\'ID ' . $ID);
        return;
    } else {
        $row = mysqli_fetch_assoc($result);
    }
} else {

    if (!isset($_GET['categoria_ID'])) {
        echo 'Manca l\'ID della categoria.';
        return;
    }

    $categoria_ID = (int) $_GET['categoria_ID'];

    if (isset($_GET['save'])) {
        $query = 'INSERT INTO configuratore_step
                  (
                      categoria_ID  
                    , step_nome 
                    , step_sigla 
                    , step_descrizione  
                    , visibile 
                  ) 
                  VALUES 
                  (
                      \'' . $categoria_ID . '\'
                    , \'' . $nome . '\'
                    , \'' . $sigla . '\'
                    , \'' . $descrizione . '\'
                    , \'' . $visibile . '\'
                  )';

        if (!$db->query($query)) {
            echo $this->getBox('danger', 'Query error. ' . $query);
        } else {
            echo $this->getBox('info', 'Step creato con successo.');
            return;
        }

    }
    $action = $conf['URI'] . 'configuratore-admin/step/editor/?save&categoria_ID=' . $categoria_ID;
    $button = 'Salva lo step';
}

echo '
<form method="post" action="' . $action . '">
  <div class="form-group row">
    <label for="nome" class="col-4 col-form-label">Nome</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-columns"></i>
          </div>
        </div> 
        <input value="' . $row['step_nome'] . '" id="nome" name="nome" placeholder="Nome della categoria" type="text" class="form-control" aria-describedby="nomeHelpBlock" required="required">
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
        <input maxlength="16" value="' . $row['step_sigla'] . '" id="sigla" name="sigla" placeholder="Sigla breve della categoria" type="text" class="form-control" aria-describedby="siglaHelpBlock" required="required">
      </div> 
      <span id="siglaHelpBlock" class="form-text text-muted">Sigla breve della categoria.</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="descrizione" class="col-4 col-form-label">Descrizione estesa</label> 
    <div class="col-8">
      <textarea id="descrizione" name="descrizione" cols="40" rows="5" class="form-control" aria-describedby="descrizioneHelpBlock" required="required">' . $row['step_descrizione'] . '</textarea> 
      <span id="descrizioneHelpBlock" class="form-text text-muted">Descrizione estesa della categoria</span>
    </div>
  </div>

  <div class="form-group row">
    <label for="select" class="col-4 col-form-label">Visibile</label> 
    <div class="col-8">
      <select id="select" name="visibile" class="custom-select">
        <option ' . ((int)$row['visibile'] === 0 ? 'selected' : '') . ' value="0">Non visibile</option>
        <option ' . ((int)$row['visibile'] === 1 ? 'selected' : '') . ' value="1">Visibile</option>
      </select>
    </div>
  </div> 
   
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit=" class="btn btn-info float-right">' . $button . '</button>
    </div>
  </div>
</form>
<hr/>
  <div class="form-group row">
    <label for="select" class="col-4 col-form-label">Immagine (max 250px di altezza)</label> 
    <div class="col-8">
  ';

if (isset($ID)) {
    echo '

    <div class="upload-system" data-contesto-id="3" data-idx="' . $ID . '" data-tipo-editor="1">
         
            <div class="upload-area">
                <input type="file" class="upload-input" accept="image/*">
                <button class="btn btn-primary upload-button">Carica</button>
            </div>
            <ul class="media-list mt-3"></ul>
     </div>
    
';
} else {
    echo 'Salva e ricarica lo step per inserire l\'immagine';
}

echo '
    </div>
  </div> 
';

echo '<hr/>
  <div class="form-group row">
    <label for="select" class="col-4 col-form-label">Documenti</label> 
    <div class="col-8">';

if (isset($ID)) {
    echo '

    <div class="upload-system" data-contesto-id="4" data-idx="' . $ID . '" data-tipo-editor="2">
         
            <div class="upload-area">
                <input type="file" class="upload-input" accept="image/*">
                <button class="btn btn-primary upload-button">Carica</button>
            </div>
            <ul class="media-list mt-3"></ul>
     </div>
    
';
} else {
    echo 'Salva e ricarica lo step per i documenti.';
}

echo '
    </div>
  </div>';
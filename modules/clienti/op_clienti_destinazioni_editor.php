<?php

if (!$core)
    die("Accesso diretto rilevato");

if (!$user->validateLogin())
    return;

$this->title = 'WinConf - Editor, indirizzi secondari';



$this->menuItems[] = '<a href="' . $conf['URI'] . 'clienti/">Clienti</a>';
$this->menuItems[] = '<em>Editor destinazione cliente</em>';

if (!isset($_GET['ID'])) {
    echo 'ID non passato';
    return;
}

$ID = (int) $_GET['ID'];
$companyName            =   $core->in($_POST['companyName']);
$addressStreet          =   $core->in($_POST['addressStreet']);
$addressNumber          =   $core->in($_POST['addressNumber']);
$addressCity            =   $core->in($_POST['addressCity']);
$addressProvince        =   $core->in($_POST['addressProvince']);
$addressPostalCode      =   $core->in($_POST['addressPostalCode']);
$codeERP                =   $core->in($_POST['codeERP']);
$VAT                    =   $core->in($_POST['VAT']);
$fiscalCode             =   $core->in($_POST['fiscalCode']);
$email                  =   $core->in($_POST['email']);
$PEC                    =   $core->in($_POST['PEC']);
$bankName               =   $core->in($_POST['bankName']);
$iban                   =   $core->in($_POST['iban']);
$electronicInvoiceCode  =   $core->in($_POST['electronicInvoiceCode']);
$privacy                =   (int) $_POST['privacy'];
$privacySignature       =   $core->in($_POST['privacySignature']);

if ($_GET['op'] === 'edit') {



    if (isset($_GET['save'])) {
        $this->noTemplateParse = true;

        $query = "UPDATE customers_addresses
                  SET
                         erp_ID = '{$codeERP}',
                         ragione_sociale = '{$companyName}',
                         codice_fiscale = '{$fiscalCode}',
                         vat = '{$VAT}',
                         address_street = '{$addressStreet}',
                         address_number = '{$addressNumber}',
                         address_city = '{$addressCity}',
                         address_town = '{$addressProvince}',
                         address_postcode = '{$addressPostalCode}',
                         bank_name = '{$bankName}',
                         iban = '{$iban}',
                         electronic_invoice = '{$electronicInvoiceCode}',
                         email = '{$email}',
                         pec = '{$PEC}'
                  WHERE ID = $ID
                  AND company_ID = {$user->company_ID}
                  LIMIT 1";

        if (!$result = $db->query($query)) {
            echo 'Query error. ' . $query;
            return;
        }

        echo 'Destinazione aggiornata con successo.';
        return;

    }

    $action = 'update';
    $query = 'SELECT * 
          FROM customers_addresses 
          WHERE ID = ' . $ID . ' 
          AND company_ID = ' . $user->company_ID . ' LIMIT 1';

    if (!$result = $db->query($query)) {
        echo 'Query error. ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo 'Nessuna destinazione trovata.';
        return;
    }

    $row = mysqli_fetch_assoc($result);
} elseif ($_GET['op'] === 'new') {
    if (isset($_GET['save'])) {
        $this->noTemplateParse = true;

        $query = 'INSERT INTO customers_addresses
                    (
                         customer_ID,
                         erp_ID,
                         company_ID,
                         ragione_sociale,
                         codice_fiscale,
                         vat,
                         address_street,
                         address_number,
                         address_city,
                         address_town,
                         address_postcode,
                         address_nation,
                         bank_name,
                         iban,
                         electronic_invoice,
                         email,
                         pec
                    ) VALUES (
                        \'' . $ID .'\',          
                        \'' . $codeERP .'\',          
                        \'' . $user->company_ID .'\',          
                        \'' . $companyName .'\',          
                        \'' . $fiscalCode .'\',          
                        \'' . $VAT .'\',          
                        \'' . $addressStreet .'\',          
                        \'' . $addressNumber .'\',          
                        \'' . $addressCity .'\',          
                        \'' . $addressProvince .'\',          
                        \'' . $addressPostalCode .'\',          
                        \'italy\',                   
                        \'' . $bankName .'\',          
                        \'' . $iban .'\',          
                        \'' . $electronicInvoiceCode .'\',          
                        \'' . $email .'\',          
                        \'' . $PEC .'\'          
                    )  ';

        if (!$db->query($query)) {
            echo 'Query error. ' . $query;
            return;
        }

        echo '<div style="border: 1px solid green; padding: 8px;">Destinazione inserita con successo.</div>';

        return;
    }

}

echo '<div id="customerEditorAddresses">
  <div class="form-group row">
    <label for="companyName" class="col-2 col-form-label">Ragione sociale</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-book-o"></i>
          </div>
        </div> 
        <input required="required" value="' . $row['ragione_sociale'] . '" id="companyName" name="companyName" placeholder="Ragione Sociale cliente" type="text" class="form-control">
      </div>
    </div>
  </div>
  
  <div class="form-group row">
    <label for="addressStreet" class="col-2 col-form-label">Via</label> 
    <div class="col-3">
      <input  required="required" value="' . $row['address_street'] . '" id="addressStreet" name="addressStreet" type="text" class="form-control">
    </div>

    <label for="addressNumber" class="col-xs-1 col-form-label">N.</label> 
    <div class="col-1">
      <input  required="required" value="' . $row['address_number'] . '" id="addressNumber" name="addressNumber" type="text" class="form-control">
    </div>
    
    <label for="addressCity" class="col-xs-1 col-form-label">Città</label> 
    <div class="col-2">
      <input  required="required" value="' . $row['address_city'] . '" id="addressCity" name="addressCity" type="text" class="form-control">
    </div>
    
    <label for="addressProvince" class="col-xs-1 col-form-label">Prov</label> 
    <div class="col-1">
      <input  required="required" value="' . $row['address_town'] . '" id="addressProvince" name="addressProvince" type="text" class="form-control" maxlength="3">
    </div>
    
  </div>
  
  <div class="form-group row">
    <label for="addressPostalCode" class="col-2 col-form-label">Cap</label> 
    <div class="col-3">
      <input  required="required" value="' . $row['address_postcode'] . '" id="addressPostalCode" name="addressPostalCode" type="text" class="form-control">
    </div>
  </div>
  
  <div class="form-group row">
    <label for="codeERP" class="col-2 col-form-label">Codice Gestionale</label> 
    <div class="col-8">
      <input disabled value="' . $row['erp_ID'] . '" id="codeERP" name="codeERP" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="VAT" class="col-2 col-form-label">Partita IVA</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['vat'] . '" id="VAT" name="VAT" placeholder="Partita Iva" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="fiscalCode" class="col-2 col-form-label">Codice fiscale</label> 
    <div class="col-6">
      <input  required="required" value="' . $row['codice_fiscale'] . '" id="fiscalCode" name="fiscalCode" placeholder="Codice Fiscale" type="text" class="form-control">
    </div>
    
     <div class="col-md-2">
        <button onclick="$(\'#fiscalCode\').val( $(\'#VAT\').val() );" class="btn btn-secondary btn-sm">Copia da P.IVA</button>
    </div>
  </div>
  <div class="form-group row">
    <label for="email" class="col-2 col-form-label">Email</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['email'] . '" id="email" name="email" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="PEC" class="col-2 col-form-label">PEC</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['pec'] . '"  id="PEC" name="PEC" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="electronicInvoiceCode" class="col-2 col-form-label">Fatturazione Elettronica</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['electronic_invoice'] . '" id="electronicInvoiceCode" name="electronicInvoiceCode" type="text" class="form-control">
    </div>
  </div>
  
  <hr />
  <h3>Dati bancari</h3>
  <!--
  <div class="form-group row">
    <label for="bankName" class="col-2 col-form-label">Istituto bancario</label> 
    <div class="col-8">
      <input value="' . $row['bank_name'] . '" id="bankName" name="bankName" type="text" class="form-control">
    </div>
  </div>
 -->
 
  <div class="form-group row">
    <label for="iban" class="col-2 col-form-label">IBAN</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['iban'] . '" id="iban" name="iban" type="text" class="form-control">
    </div>
  </div>
 
  <hr />
  
  <div class="form-group row">
    <div class="offset-4 col-8 pull-right">
      <button id="btnSubmit" onclick="sendData();" name="submit" type="submit" class="btn btn-primary">Aggiorna/Inserisci destinazione</button>
    </div>
  </div>
  
</div>
<div id="crudResult"></div>

<script>

function sendData()
{
    
    customerErrors = false;
    $("#customerEditorAddresses input:required").each(function() {
      if ($(this).val() === "") {
        customerErrors = true;
        $(this).css("background-color", "#FBB");
      }    
    });
    companyName             =   $("#companyName").val();
    addressStreet           =   $("#addressStreet").val();
    addressNumber           =   $("#addressNumber").val();
    addressCity             =   $("#addressCity").val();
    addressProvince         =   $("#addressProvince").val();
    addressPostalCode       =   $("#addressPostalCode").val();
    codeERP                 =   $("#codeERP").val();
    VAT                     =   $("#VAT").val();
    fiscalCode              =   $("#fiscalCode").val();
    email                   =   $("#email").val();
    PEC                     =   $("#PEC").val();
    electronicInvoiceCode   =   $("#electronicInvoiceCode").val();
    bankName                =   $("#bankName").val();
    iban                    =   $("#iban").val();
    
    postURI                 = "' . $conf['URI'] .  'clienti/addresses/editor/?op=' . ( $action === 'update' ? 'edit' : 'new&ID=' . $ID) . '&save' . ($action === 'update' ? '&ID=' . $ID : '') .'";
    
    $("#btnSubmit").prop("disabled", true);
    
    $.post(postURI,{   companyName              :   companyName, 
                       customer_ID              :   ' . ( (int) $_GET['ID']) . ', 
                       codeERP                  :   codeERP, 
                       addressStreet            :   addressStreet,
                       addressNumber            :   addressNumber,
                       addressCity              :   addressCity,
                       addressProvince          :   addressProvince,
                       addressPostalCode        :   addressPostalCode ,
                       VAT                      :   VAT, 
                       fiscalCode               :   fiscalCode, 
                       email                    :   email, 
                       PEC                      :   PEC, 
                       bankName                 :   bankName, 
                       iban                     :   iban, 
                       electronicInvoiceCode    :   electronicInvoiceCode, 
                      })
    .done(function( data ) {
       $("#crudResult").html(data);
    });
}

</script>';
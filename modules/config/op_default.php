<?php

if (!$user->validateLogin())
    return;

echo '<h2>Configurazione</h2>
<h3>Testata documenti</h3>
<textarea class="tinymce" id="header">' . $user->header . '</textarea>
<h3>Piede documenti</h3>
<textarea  class="tinymce" id="footer">' . $user->footer .'</textarea>
<div style="text-align: right">
    <button class="btn btn-info" id="salva" onclick="salva()">Salva</button>
</div>
<script>

function salva() {
   header = tinymce.get("header").getContent();;
   footer = tinymce.get("footer").getContent();;
   
   
   $.post( jsPath + "config/salva/", { header: header, footer: footer })
     .done(function( data ) {
     }); 
}
tinymce.init({
  selector: \'textarea.tinymce\',
  height: 500,
  plugins: [
    \'advlist\', \'autolink\', \'lists\', \'link\', \'image\', \'charmap\', \'preview\',
    \'anchor\', \'searchreplace\', \'visualblocks\', \'code\', \'fullscreen\',
    \'insertdatetime\', \'media\', \'table\', \'help\', \'wordcount\'
  ],
  toolbar: \'undo redo | blocks | \' +
  \'bold italic backcolor | alignleft aligncenter \' +
  \'alignright alignjustify | bullist numlist outdent indent | \' +
  \'removeformat | help\',
  content_style: \'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }\'
});
</script>
';
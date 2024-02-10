<?php
namespace framework;

class core{
    public string $shortCodeLang = 'it';

    public function echoPost()
    {
        echo '<pre>' . print_r($_POST, 1) . '</pre>';
    }
    public function in($text)
    {
        return str_replace("'", "\'", $text);
    }

    public function valuta ( int|float|null $valuta) : string {

            // Formatta il numero come stringa di valuta
            $numero_formattato = number_format($valuta, 2, ',', '.');

            return "€ $numero_formattato";

    }

}
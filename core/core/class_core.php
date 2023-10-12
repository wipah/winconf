<?php
namespace framework;

class core{
    public string $shortCodeLang = 'it';

    public function echoPost() {
        echo '<pre>' . print_r($_POST, 1) . '</pre>';
    }

    public function in($text)
    {
        return str_replace("'", "\'", $text);
    }

}
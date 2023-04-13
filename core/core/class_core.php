<?php
namespace framework;

class core{
    public string $shortCodeLang = 'it';

    public function in($text)
    {
        return str_replace("'", "\'", $text);
    }

}
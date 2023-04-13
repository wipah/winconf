<?php
namespace framework;

class math extends core
{
    public function formatValue($value)
    {
        return '&euro; '. number_format($value, 2, '.', ',');
    }

}
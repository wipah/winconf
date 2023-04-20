<?php

if ($core)
    die("Direct access");

$this->noTemplateParse = true;

var_dump($_POST);
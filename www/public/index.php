<?php
//ini_set("display_errors",1);
require __DIR__ . '/../vendor/autoload.php';

$app = \CHK\Core\Bootstrap::boot();

$app->run(); 
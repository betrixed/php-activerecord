<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$PHP_DIR = dirname(__DIR__);
$PCAN_DIR = $PHP_DIR . "/vendor/betrixed/pcan/src";
$loader = require_once $PCAN_DIR . "/WC/Loader.php";


$loader->addPathArray([
    'ActiveRecord' => $PHP_DIR . "/activerecord/lib",
    'NamespaceTest' =>  $PHP_DIR . "/activerecord/test/models/NamespaceTest"
]);

if (PHP_MAJOR_VERSION < 8 ) {
    require_once $PCAN_DIR . "/php80.php";
}
$loader->addClassFolder($PHP_DIR . "/activerecord/test/helpers");
$loader->addClassFolder($PHP_DIR . "/activerecord/test/models");

$loader->register();

require_once $PHP_DIR . "/activerecord/test/helpers/config.php";

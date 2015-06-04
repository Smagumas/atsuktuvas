<?php
if(defined('CORE'))
    exit;
define('CORE', true);

require_once __DIR__ . '/config.php';
require __DIR__ .'/libs/Smarty.class.php';
require __DIR__ .'/global.php';
require_once __DIR__ . '/functions/DBCon.php';
require_once __DIR__ . '/functions/pages/page_functions.php';
$DBCon = new DataBaseConnection();
$DBCon->connect($_host, $_user, $_password, $_database);


$DBCon->debugging = true;
$DBCon->caching = true;
$DBCon->cache_lifetime = 120;


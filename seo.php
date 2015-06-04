<?php
ob_start();
require_once dirname(__FILE__) . '/config.php';
require_once 'functions/DBCon.php';
$DBCon = new DataBaseConnection('mysql');
$DBCon->connect($dbConf['host'], $dbConf['username'], $dbConf['password'], $dbConf['database']);

$query = "INSERT INTO cms_seo_visits(Host, Ip, User_agent, Request_uri, Referrer) VALUES ('$_SERVER[REMOTE_ADDR]',
'$hostname', '$_SERVER[HTTP_USER_AGENT]','$_SERVER[REQUEST_URI]', '$_SERVER[HTTP_REFERER]')";
$DBCon->query($query);
$req = $_SERVER['REQUEST_URI'];
if ($req == '/robots.txt') {
    header('Content-type: text/plain');
    if (strpos($_SERVER['HTTP_HOST'], 'www') === false) {
        $site .= 'sitemap.xml';
        $temp = <<<A
User-Agent: *
Disallow: /
A;
    } else {
        $temp = <<<A
User-Agent: *
Allow: /
A;
    }
} else if ($req == '/google.html') {
    //google webmaster tool verifikatorius
    header('Content-type: text/html; charset=UTF-8');
    $temp = 'google-site-verification: google.html';
}
echo $temp;
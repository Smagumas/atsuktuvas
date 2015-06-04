<?php

$start = microtime(true);
$startMemory = memory_get_usage();
ob_start();
header('Content-type: text/html; charset=UTF-8');
if(!isset($_SESSION))
{
    session_start();
}
require('../core.php');

$errors = NULL;
if (isset($_POST['admin_form'])) {

    $_POST = str_replace("\'", "", $_POST);
    $login = strip_tags($_POST['username']);
    $pass = md5(strip_tags($_POST['password']));
    $query = "SELECT count(*) as cnt FROM cms_users WHERE IsActive=1 AND Ban=0 AND User='$login' AND Password='$pass'";
    $result = $DBCon->query($query);
    $row = $DBCon->fetch_assoc($result);
    if ($row['cnt'] != 1) {
        $errors = 'Neteisingi prisijungimo duomenys';
    }
    if ($errors == NULL) {
        $_SESSION['User_Login'] = $login;
        $Stay = $_POST['remember'];
        if ($Stay == 'on') {
            setcookie('password', $pass, time() + 60 * 60 * 24 * 100);
            setcookie('username', $login, time() + 60 * 60 * 24 * 100);
        }
        header('location:/admin/main');

    }
}
//dieprint($_GET);
if(isset($_GET['action']) && $_GET['action'] == 'logout'){
    //unset($_SESSION);
    session_destroy();
    session_unset();
}
if (!isset($_SESSION) || empty($_SESSION['User_Login'])) {
    $DBCon->Display('login');
}else{


include 'class.imageris.php';

$query = "SELECT Id, Title, Module
	FROM cms_modules
	WHERE IsActive = 1 AND Show_In_Menu = 1
	ORDER BY PositionOrder";
$result = $DBCon->query($query);
$menu = array();
while ($row = $DBCon->fetch_assoc($result)) {
    $menu[] = $row;
}

$DBCon->Assign('menu',$menu);


$kintamieji = explode('/', $_SERVER['REQUEST_URI']);

$admin_page = 'admin';
//Jei nera nurodyta net pirmo settinam PROJEKTĄ kaip reikalinga modulį
if ($kintamieji[1] == $admin_page){

    $query = "SELECT Id, Title, Module
	FROM cms_modules
	WHERE IsActive = 1 AND Show_In_Menu = 1 AND Module='$kintamieji[2]'";
    $result = $DBCon->query($query);
    $module_row = $DBCon->fetch_assoc($result);
    $DBCon->Assign('module_title',$module_row['Title']);

    $split = explode('?', end($kintamieji));
    $kintamieji[sizeof($kintamieji)-1] = $split[0];
    $path = implode("/", $kintamieji);
    $DBCon->Assign('path',$path);
    $DBCon->Assign('module_name',$kintamieji[2]);
    $_SESSION['submodule'] = $kintamieji[2];
    $_SESSION['module'] = $kintamieji[2];
    if ($kintamieji[2] == ''){
        $module = "modules/main/main.php";
        $DBCon->Assign('module', "modules/main/main.tpl");
    } elseif (isset($kintamieji[2]) || isset($kintamieji[3]) ){
        $module = "modules/$kintamieji[2]/$kintamieji[2].php";
        $DBCon->Assign('module', "modules/$kintamieji[2]/$kintamieji[2].tpl");
    } else {
        $module = "modules/$kintamieji[2]/$kintamieji[2].php";
        $DBCon->Assign('module', "modules/$kintamieji[2]/$kintamieji[2].tpl");
    }
}else{
    $module = "/index.php";
}

if (file_exists($module) == 1) {

    require($module);
    Load_Module();
    $DBCon->Display('inside');
}

}

?>
<?php
echo $errors;
//println(microtime(true) - $start);
//println(formatSize(memory_get_usage() - $startMemory));
?>
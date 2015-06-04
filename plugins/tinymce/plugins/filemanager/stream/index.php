<?php
session_start();
if  ($_SESSION['user_login']=='') {
die('Klaida');
exit();
}
	require_once("./stream.php");
?>
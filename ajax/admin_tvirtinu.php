<?php
require_once '../config.php';
require('../libs/smarty/Smarty.class.php');
require_once '../functions/db_connection.php';
error_reporting(0);
ob_start();
header('Content-type: text/html; charset=UTF-8');
$DBCon = new DataBaseConnection('mysql');
$DBCon->connect($dbConf['host'], $dbConf['username'], $dbConf['password'], $dbConf['database']);
if($_POST['action']=='update') { 
	$table=$_POST['table'];
	$pk=$_POST['pk'];
	$pk_val=$_POST['pk_val'];
	$field=$_POST['field'];
	$val=$_POST['val'];
	$klase=$_POST['klase'];
	
	$dabar=date('Y-m-d');
	$date=new DateTime($dabar);
	$date->add(new DateInterval('P31D'));
	$dabar=$date->format('Y-m-d');
	
	
	$row_parent_id=$DBCon->fetch_array($DBCon->query("select parent_id from mod_skelbimai where $pk='$pk_val'"));
	
	if(is_numeric($row_parent_id[parent_id])) {
		$DBCon->query("delete from mod_skelbimai where id='$row_parent_id[parent_id]'");
		$DBCon->query("update mod_skelbimai set parent_id=null where $pk='$pk_val'");
	}
	
	if($val==1) {
		$query="update $table set $field='$val', galioja_iki='$dabar' where $pk='$pk_val'";
	} else {
		$query="update $table set $field='$val' where $pk='$pk_val'";
	}
	
	
	$result=$DBCon->query($query);
	if($val==1) {
		$temp.=<<<A
			<a href="" onclick="update_ajax_tvirtinu('$table', '$pk', '$pk_val', '$field', '0', '$klase'); return false" title='TAIP' style="padding-left:8px; background:green; text-decoration:none;">&nbsp;</a>
A;
	} else {
		$temp.=<<<A
			<a href="" onclick="update_ajax_tvirtinu('$table', '$pk', '$pk_val', '$field', '1', '$klase'); return false" title='NE' style="padding-left:8px; background:red; text-decoration:none;">&nbsp;</a>
A;
	}
}



echo $temp;




?>

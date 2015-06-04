<?php
require_once('../core.php');
error_reporting(0);
ob_start();
header('Content-type: text/html; charset=UTF-8');
if($_POST['action']=='update') { 
	$table=$_POST['table'];
	$pk=$_POST['pk'];
	$pk_val=$_POST['pk_val'];
	$field=$_POST['field'];
	$val=$_POST['val'];
	$klase=$_POST['klase'];
	$query="update $table set $field='$val' where $pk='$pk_val'";
	$result=$DBCon->query($query);
	if($val==1) {
		$temp.=<<<A
			<a href="" onclick="update_ajax('$table', '$pk', '$pk_val', '$field', '0', '$klase'); return false" title='TAIP' style="padding-left:8px; background:green; text-decoration:none;">&nbsp;</a>
A;
	} else {
		$temp.=<<<A
			<a href="" onclick="update_ajax('$table', '$pk', '$pk_val', '$field', '1', '$klase'); return false" title='NE' style="padding-left:8px; background:red; text-decoration:none;">&nbsp;</a>
A;
	}
}


if($_POST['action']=='update_radio') { 
	$table=$_POST['table'];
	$pk=$_POST['pk'];
	$pk_val=$_POST['pk_val'];
	$field=$_POST['field'];
	$val=$_POST['val'];
	$klase=$_POST['klase'];
	
	mysql_query("update $table set $field='0'");
	
	$query="update $table set $field='$val' where $pk='$pk_val'";
	$result=$DBCon->query($query);
	if($val==1) {
		$temp.=<<<A
			<a href="" onclick="update_ajax_radio('$table', '$pk', '$pk_val', '$field', '1', '$klase');" title='TAIP' style="padding-left:8px; background:green; text-decoration:none;">&nbsp;</a>
A;
	}
	else {
		$temp.=<<<A
			<a href="" onclick="update_ajax_radio('$table', '$pk', '$pk_val', '$field', '1', '$klase');" title='NE' style="padding-left:8px; background:red; text-decoration:none;">&nbsp;</a>
A;
	}
}

if($_POST['action']=='delete') {
	$table=$_POST['table'];
	$pk=$_POST['pk'];
	$pk_val=$_POST['pk_val'];
	$query="delete from $table where $pk='$pk_val'";
	$result=$DBCon->query($query);
}
echo $temp;




?>

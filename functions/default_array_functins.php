<?php

function default_column_values($action, $xml){
	//echo $action;
	global  $DBCon, $table, $orderby, $pk;
	$id=$_GET[id];
	$default_array=array();
	$result = $xml->xpath('table/row');
	//print_r($result);
	if ($action=='insert') {
		for ($i=0; $i<count($result); $i++) {
			//print_r(get_row_columns_insert($result[$i]));
			$default_array = array_merge($default_array, get_row_columns_insert($result[$i]));
			
			//print_r($temp_array);
			//echo "<br>";
		}
	}
	else if ($action=='edit') {
		
		$_SESSION[spalvinti]=$id;
		for ($i=0; $i<count($result); $i++) {
				$columns.=get_row_columns($result[$i]);
}
			$columns=substr($columns, 0, -2);
			$query=<<<A
	select $columns from $table where $pk='$id'
A;
			//echo $query;
			$result = $DBCon->query($query);
			$default_array = $DBCon->fetch_assoc($result);
			
		}
	//print_r($default_array);
	return $default_array;
}

function get_row_columns($result_xml){
	

$result = $result_xml->xpath('col');

for ($i=0; $i<count($result); $i++) {
	$attr=$result[$i]->attributes();
	
	$cols.=<<<A
	$attr[column], 
A;
	}
	return $cols;
	
	}

function get_row_columns_insert($result_xml){
	global $xml, $DBCon, $table, $orderby, $pk;
	$temp_array= array();
	$result = $result_xml->xpath('col');
	
	for ($i=0; $i<count($result); $i++) {
		$attr=$result[$i]->attributes();
		
		$column=$attr["column"];
		$default=$attr["default"];
		if ($default!='') {
			$default=str_replace('@lang', $_SESSION[lang], 	$default);
			//echo $default. '<br>';
			$result = $DBCon->query($default);
			$temp_array_result = $DBCon->fetch_row($result);
			$temp_array["$column"]=$temp_array_result[0];
		}
	}
	//print_r($temp_array);
	return $temp_array;
	
}


function EndsWith($Haystack, $Needle){
	// Recommended version, using strpos
	return strrpos($Haystack, $Needle) === strlen($Haystack)-strlen($Needle);
}


function StartsWith($Haystack, $Needle){
	// Recommended version, using strpos
	return strpos($Haystack, $Needle) === 0;
}




?>
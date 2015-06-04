<?php
function sql_delete() {
    global $xml, $DBCon, $table;
    $id = $_POST['id'];
    $query = "DELETE FROM $table WHERE id=$id";
    $result = $DBCon->query($query);
    $pref = $_SERVER['QUERY_STRING'];
    $q_log = str_replace("'", '', $query);
    $q_log = str_replace('"', '', $q_log);
    $DBCon->query("INSERT INTO admin_action_log (User_Id, Action, Query)
    VALUES ('$_SESSION[user_id]', 'Deleted row in $table table', '$q_log')");
    header("location:$_SERVER[REQUEST_URI]");
    exit();
}

function sql_insert($disablePostEdit = false) {

    global $xml, $DBCon, $table, $pk, $lang_column, $sort_column, $generate_alias_col;
    $columns_array2 = list_all_colums();
    foreach ($columns_array2 as $column => $value) {

        if ($value == '') {
        } else {
            if ($table == 'cms_admins' and $column == 'pass') {
                $value = md5($value);
            }
            $value = addslashes($value);
            $value = str_replace("../uploads", "uploads", $value);
            $columns_array[$column] = "'$value'";
        }
    }
    if (($_GET['filter_name'] != '') and (!array_key_exists($_GET['filter_name'], $columns_array2))) {
        $val = (string)$_GET['filter_value'];
        $name = $_GET['filter_name'];
        $columns_array[] .= "$name='$val'";
    }

    if ($lang_column != '') {
        $columns_array[$lang_column] = "'$_SESSION[lang]'";
    }

    if ($sort_column != '') {
        $q_s1 = "select $pk as pk_val from $table order by $sort_column asc, id desc";
        $r_s1 = $DBCon->query($q_s1);
        $sortas = 1;
        $sortas_next = $sortas;
        while ($w_s1 = $DBCon->fetch_array($r_s1)) {
            $sortas++;
            die('cia');
            $DBCon->query("update $table set $sort_column='$sortas' where $pk='$w_s1[pk_val]'");
        }
        $columns_array["$sort_column"] = "'$sortas_next'";
    }
    $insert_columns = array();
    $insert_values = array();

    if (count($columns_array) > 0) {
        foreach ($columns_array as $key => $value) {
            $insert_columns[] = $key;
            $insert_values[] = $value;
        }
        $columns = implode(',', $insert_columns);

        $values = implode(',', $insert_values);
        $query = 'insert into ' . $table . ' (' . $columns . ') values  (' . $values . ')';

        $result = $DBCon->query($query);

        $id = mysql_insert_id();
        if ($result) { //loginam keitimus i db
            foreach ($columns_array as $key => $value) { //value irgi masyvas
                $value = str_replace("'", '', $value);
                $sql = <<<A
insert into admin_action_log_detailed (UserId, DateTime, `Table`, Pk, `Column`, OldValue, NewValue, Action) values (
'$_SESSION[user_login]', NOW(), '$table', '$id', '$key', null, '$value', 'insert')
A;
                $DBCon->query($sql);
            }

            $q_log = str_replace("'", '', $query);
            $q_log = str_replace('"', '', $q_log);
        } else {
            die('Klaida sql' . $query);
        }
    }


    $q_log = str_replace("'", '', $query);
    $q_log = str_replace('"', '', $q_log);

    $q = <<<A
		SELECT max(id) as max_id  FROM $table
A;
    $r = $DBCon->query($q);
    $eil = $DBCon->fetch_array($r);
    $max_id = $eil[max_id];
    $id = $max_id;


    $resultatas = $xml->xpath('/xml/post_actions/update_data');
    if ($resultatas) {
        mysql_query("UPDATE $table SET sukurimo_data=now() WHERE $pk=$id");
    }
    $resultatas1 = $xml->xpath('/xml/post_actions/insert_reload');
    if ($resultatas1) {
        $editas_po_inserto = 1;
    }
    if ($disablePostEdit)
        $editas_po_inserto = 0;


    if ($generate_alias_col != '') {
        $query = <<<A
	SELECT $generate_alias_col FROM $table WHERE $pk=$id
A;
        $result = $DBCon->query($query);
        $row = $DBCon->fetch_row($result);
        $alias = $row[0];
        $alias = get_alias($alias);
        $query = <<<A
	UPDATE $table SET Alias='$alias' WHERE $pk=$id
A;

        $DBCon->query($query);


    }

    $DBCon->query("insert into admin_action_log (User_Id, Action, Query) values ('$_SESSION[user_id]',
'sukure irasa $table lenteleje','$q_log')");

/*
    if ($editas_po_inserto) {
        header("location:$_SERVER[REQUEST_URI]?edit&id=$id&default=$_GET[default]&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]&page=$_GET[page]");
    } else {
        header("location:$_SERVER[REQUEST_URI]?list&default=$_GET[default]&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]&page=$_GET[page]");
    }
    exit();
*/
}

function sql_update($disablePostEdit = false) {
    //print_r($_POST);
    global $xml, $DBCon, $table, $pk, $generate_alias_col;
    $id = $_POST['id'];
    $collumns_array = list_all_colums();

    /* veiksmu detalus loginimas*/

    $result = $DBCon->query("select * from $table where $pk=$id");
    $row_senas = $DBCon->fetch_array($result);

    /* veiksmu detalus loginimas*/

    //formuojam query
    $columns_array = array();
    $changes_array = array();
    foreach ($collumns_array as $column => $value) {
        if ($table == 'cms_admins' and $column == 'pass') {
            $value = md5($value);
        }
        if ($row_senas[$column] != $value) { //jei pasikeite reiksme
            $changes_array[$column] = array($row_senas[$column], $value); //pakeitimu masyvas
            if ($value == '') {
                $columns_array[] = "$column=null";
            } else {
                $value = addslashes($value);
                $value = str_replace("../uploads", "uploads", $value);
                $columns_array[] .= "$column='$value'";
            }
        }
    }

    if (count($columns_array) > 0) {
        $columns = implode(',', $columns_array);
        $query = 'update ' . $table . ' set ' . $columns . ' where ' . $pk . ' =' . $id;
        //die($query);
        $result = $DBCon->query($query);

        if ($result) {
            foreach ($changes_array as $key => $value) { //value irgi masyvas
                $value[0] = str_replace("'", '', $value[0]);
                $value[1] = str_replace("'", '', $value[1]);
                $sql = <<<A
insert into admin_action_log_detailed (UserId, DateTime, `Table`, Pk, `Column`, OldValue, NewValue, Action) values (
'$_SESSION[user_login]', NOW(), '$table', '$id', '$key', '$value[0]', '$value[1]', 'update')
A;
                $DBCon->query($sql);
            }

            $q_log = str_replace("'", '', $query);
            $q_log = str_replace('"', '', $q_log);
        } else {
            die('Klaida sql' . $query);
        }
    }


    $resultatas1 = $xml->xpath('/xml/post_actions/insert_reload');
    if ($resultatas1) {
        $editas_po_inserto = 1;
    }
    if ($disablePostEdit)
        $editas_po_inserto = 0;

    if ($generate_alias_col != '') {

        $query = <<<A
	select $generate_alias_col from $table where $pk=$id
A;
        $result = $DBCon->query($query);
        $row = $DBCon->fetch_row($result);
        $alias = $row[0];
        $alias = get_alias($alias);
        $query = <<<A
	update $table set alias='$alias' where $pk=$id
A;
        $DBCon->query($query);
    }

    $DBCon->query("insert into admin_action_log (User_Id, Action, Query) values ('$_SESSION[user_id]',
'redagavo irasa $table lenteleje','$q_log')");
    $pref = $_SERVER['QUERY_STRING'];
    $pref = str_replace('edit', '', $pref);
    $pref = str_replace('srautas', '', $pref);
/*
    if ($editas_po_inserto) {
        header("location:$_SERVER[REQUEST_URI]?edit&id=$id&default=$_GET[default]&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]&page=$_GET[page]");
    } else {
        header("location:$_SERVER[REQUEST_URI]?$pref");
    }
    exit();
*/
}

function list_all_colums() {
    $collumns_array = array();

    global $xml;
    $areas = $xml->xpath('/xml/edit/area');
    for ($i_a = 0; $i_a < count($areas); $i_a++) {

        $tables = $areas[$i_a]->xpath('table');

        for ($i_t = 0; $i_t < count($tables); $i_t++) {

            $tr = $tables[$i_t]->xpath('row');

            for ($i = 0; $i < count($tr); $i++) {

                $collumns_array = array_merge($collumns_array, sql_help_enum_controls($tr[$i]));
            }

        }
    }


    return $collumns_array;

}

function swich_position($position) {
    global $DBCon, $table, $pk, $sort_column;
    $id = $_GET['id'];

    $query = <<<A
	select $sort_column from $table where $pk='$id'
A;
    $result = $DBCon->query($query);
    $temp_result = $DBCon->fetch_row($result);

    $pozicija = $temp_result[0];
    if ($position == 'down') {

        $DBCon->query("update $table set $sort_column=$sort_column-1 where $sort_column=$pozicija+1");
        $DBCon->query("update $table set $sort_column=$sort_column+1 where $pk='$id'");
    } elseif ($position == 'up') {
        $DBCon->query("update $table set $sort_column=$sort_column+1 where $sort_column=$pozicija-1");
        $DBCon->query("update $table set $sort_column=$sort_column-1 where $pk='$id'");

    }
    $DBCon->query("insert into admin_action_log (User_Id, Action, Query) values ('$_SESSION[user_id]',
'keite iraso pozicija $table lenteleje','')");
    $DBCon->query($query);
    header("location:$_SERVER[REQUEST_URI]?list");

}

function show_kategorijos() {
    global $DBCon;
    $query = "select Id, Title from cms_meniu where Parent='0' and Title!='' and Lang='lt' ORDER BY Position";
    $result = mysql_query($query);
    $tempas = '';
    $tempas .= <<<A
		<option value="">----Pasirinkite-----</option>
A;
    while ($row = mysql_fetch_array($result)) {
        if ($_GET['filter_value'] == $row['id']) {
            $color = "selected='selected'";
        } else {
            $color = '';
        }
        $tempas .= <<<A
		<option value="$row[id]" $color0>$row[name] ($row[id])</option>
A;
        $query1 = "select Id, Title from cms_meniu where Parent='$row[id]' and Title!='' and Lang='lt' ORDER BY Position";
        $result1 = mysql_query($query1);
        while ($row1 = mysql_fetch_array($result1)) {
            if ($_GET['filter_value'] == $row1['id']) {
                $color1 = "selected='selected'";
            } else {
                $color1 = '';
            }
            $tempas .= <<<A
			<option value="$row1[id]" $color11>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row1[name] </option>
A;
            $query2 = "select Id, Title from cms_meniu where Parent='$row1[id]' and Title!='' and Lang='lt' ORDER BY Position";
            $result2 = mysql_query($query2);
            while ($row2 = mysql_fetch_array($result2)) {
                if ($_GET['filter_value'] == $row2['id']) {
                    $color2 = "selected='selected'";
                } else {
                    $color2 = '';
                }
                $tempas .= <<<A
				<option value="$row2[id]" $color22>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row2[name]</option>
A;
                $query3 = "select Id, Title from cms_meniu where Parent='$row2[id]' and Title!='' and Lang='lt' ORDER BY Position";
                $result3 = mysql_query($query3);
                while ($row3 = mysql_fetch_array($result3)) {
                    if ($_GET['filter_value'] == $row3['id']) {
                        $color3 = "selected='selected'";
                    } else {
                        $color3 = '';
                    }
                    $tempas .= <<<A
					<option value="$row3[id]" $color33>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row3[name]</option>
A;
                }
            }
        }
    }
    return $tempas;
}

function list_query($columns, $custom_where) {
    global $table, $orderby, $pk, $paging, $xml;
    $orderby = strtolower($orderby);
    if ($paging == '') {
        $query = 'select ' . $columns . ', ' . $pk . ' as pk from ' . $table . $custom_where . ' order by ' . $orderby;
   printdie($query);
    } else {
        $page = $_GET['page'];
        if ($page == '') {
            $offset = 0;
        } elseif ($page != '') {
            $page = $_GET['page'];
            $offset = (($page - 1) * $paging) + 1;
        }
        $temp_array = array();
        $sort_array = explode(',', $orderby);
        foreach ($sort_array as $val) {
            if ((EndsWith($val, ' desc') == false) and (EndsWith($val, ' asc') == false)) {
                $val = "$val asc";
            }
            $temp_array[] = $val;
        }
        if (!array_key_exists("$pk", $sort_array)) {
            $sort_array[] = "$pk asc";
        }
        foreach ($temp_array as $val) {
            $temp_order = "$val, ";
        }
        $temp_order = substr($temp_order, 0, -2);
        $temp_order_desc = str_replace(" asc", " desc", $temp_order);
        if ($_GET['mode'] and $_GET['col']) {
            $temp_order = "$_GET[col] $_GET[mode]";
        }
        $result = $xml->xpath('/xml/filter_area/filter_field');
        for ($i = 0; $i < count($result); $i++) {
            $attr = $result[$i]->attributes();
            $filter_colum = $attr['column'];
            $value = $attr['system_name'];
            $tipas = $attr['display'];
            if (isset($_GET["$value"]) and $_GET["$value"] != '') {
                $offset = 0;
                $paging = 1000000;
                $va = $_GET["$value"];
                $va2 = $_GET["$value2"];
                if ($tipas == 'combo') {
                    $custom_where .= " and  $filter_colum='$va'";
                }
                if ($tipas == 'input') {
                    if ($filter_colum == 'title_lt') {
                        $custom_where .= " and  (replace(replace(replace($filter_colum , ' ',''),'/',''),'-','') like  concat( '%' ,  replace(replace(replace('$va', ' ',''),'/',''),'-',''), '%') or dovana_lt like'%$va%')";
                    } else {
                        $custom_where .= " and  $filter_colum like '%$va%'";
                    }
                }
                if ($tipas == 'intervalas') {
                    if ($va2 == '') {
                        $va2 = date('Y-m-d');
                    }
                    $custom_where .= " and  $filter_colum between '$va' and '$va2'";
                }
                if ($tipas == 'checkbox') {
                    $custom_where .= " and  $filter_colum='$va'";
                }
                if ($tipas == 'not_nulas') {
                    $custom_where .= " and  $filter_colum!=''";
                }
                if ($tipas == 'data') {
                    $custom_where .= " and  $filter_colum='$va' and  $filter_colum!='' and  $filter_colum!='0000-00-00'";
                }
            }
        }
        $custom_where_did = '   ';
        $custom_where_did = substr($custom_where_did, 6);
        if ($custom_where_did != '') {
            $custom_where_did = 'and (' . $custom_where_did . ')';
            $offset = 0;
            $paging = 1000000;
        }


        $query = "select  $columns, $pk as pk from $table WHERE 1=1 $custom_where $custom_where_did order by $temp_order limit $offset,
$paging";


    }
    //echo $query;
    return $query;
}


?>

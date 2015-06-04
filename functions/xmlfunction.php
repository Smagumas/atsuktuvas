<?php

function define_main_vars($xml_name) {
    global $xml, $table, $orderby, $where, $pk, $lang_column, $sort_column, $paging, $generate_alias_col, $editable_fields;
    $xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . '/admin/modules/' . $_SESSION['module'] . '/xml/' . $xml_name . '.xml');
    $result = $xml->xpath('/xml/list');
    $result = $result[0];
    $table = $result['table'];
    $orderby = $result['orderby'];
    $sort_column = $result['sort_column'];
    $paging = $result['paging'];
    $lang_column = $result['lang_column'];
    $where = $result['where'];
    $pk = $result['pk'];
    $generate_alias_col = $result['generate_alias_col'];
    $editable_fields = $result['editable_fields'];
}

require('default_array_functins.php');
require('sql_functions.php');
function list_form() {
    global $xml, $DBCon, $table, $pk, $sort_column, $paging, $editable_fields, $delete_srautas;
    $filter_area = $xml->xpath('/xml/filter_area/filter_field');
    if ($filter_area) {
        $temp = <<<A
			<form method="GET" action="$_SERVER[REQUEST_URI]">

			<input type="hidden" name="submodule" value="$_SESSION[submodule]">
A;
        $temp .= '<table id="filtrai">';
        for ($i = 0; $i < count($filter_area); $i++) {
            $attr = $filter_area[$i]->attributes();
            $tipas = $attr['display'];

            if ($tipas == 'input') {
                $temp .= '<tr>';
                $temp .= show_search($i);
                $temp .= '<tr />';
            }
            if ($tipas == 'combo') {
                $temp .= '<tr>';
                $temp .= show_filter($i);
                $temp .= '<tr />';
            }
            if ($tipas == 'intervalas') {
                $temp .= '<tr>';
                $temp .= show_interval_search($i);
                $temp .= '<tr />';
            }
            if ($tipas == 'checkbox') {
                $temp .= '<tr>';
                $temp .= show_checkbox_search($i);
                $temp .= '<tr />';
            }
            if ($tipas == 'data') {
                $temp .= '<tr>';
                $temp .= show_data_search($i);
                $temp .= '<tr />';
            }
            if ($tipas == 'not_nulas') {
                $temp .= '<tr>';
                $temp .= show_not_nulas($i);
                $temp .= '<tr />';
            }


        }
        $url = 'http://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
        $temp .= <<<A
	       <tr><td><a href='$url'>
Nuimti filtrus</a></td>
			<td><input type="submit" value="Filtruoti" /></td></tr></table></form>
A;
    }

    if ($editable_fields) {
        $temp .= <<<A
	<br /><br />
	<form action="$_SERVER[REQUEST_URI]?update&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]&page=$_GET[page]" method="post">
	<input type="submit" value="Išsaugoti pakeitimus" /><br /><br />
A;
    }
    $url = 'http://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
    $temp .= <<<A
<a href="$url/new" class="new_punkt">Sukurti naują įrašą</a><br>
A;
    $temp .= do_paging();

    if ($sort_column != '') {
        $temp .= <<<A
	<script src="/admin/static/jquery-1.9.1.js"></script>
	<script src="/admin/static/jquery_tablednd.js"></script>
	<script type="text/javascript">
	 $(document).ready(function() {
		$('#table_sort').tableDnD({
			onDrop: function(table, row) {
				sort=$.tableDnD.serialize();
				$("#info").load("/admin/xml_system/sort.php?table=$table&column=$sort_column&"+sort , function(){location.reload();}); 
			}
		});
      });
</script>
<div id="info"></div>
A;
    }

    $temp .= "
<table class=\"table table-striped table-hover\" id='table_sort'>
                <thead>
                 <tr id='sort_header'>";

    $p = $paging * $_GET['page'];
    $result = $xml->xpath('/xml/list/col');

    for ($i = 0; $i < count($result); $i++) {

        $attr = $result[$i]->attributes();
        $rusiuot = $attr['rusiuoti'];
        $stylen = $attr['style'];

        //---------------------srautiniam redagavimui--------------
        $editable = $attr['editable'];
        if (isset($editable)) {
            $redaguojamas["$i"]['editable'] = 1;
            $redaguojamas["$i"]['real_column'] = $attr['real_column'];
            $redaguojamas["$i"]['display'] = $attr['display'];
        }
        //----------------------------------------------------------

        if (!isset($rusiuot)) {

            $res = $xml->xpath('/xml/filter_area/filter_field');
            for ($u = 0; $u < count($res); $u++) {
                $attrs = $res[$u]->attributes();
                $filter_colum = $attrs['column'];
                $value = $attrs['system_name'];
                $va = $_GET["$value"];
                if ($_GET["$value"]) {
                    $yra = 1;
                }
            }
            $url = 'http://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
            $temp .= <<<A
<td style='$stylen'><a href='$url
A;


            if ($_GET['mode'] == 'desc' and $_GET['col'] == $attr['column']) {
                if ($yra == 1) {
                    $r = $xml->xpath('/xml/filter_area/filter_field');
                    for ($l = 0; $l < count($r); $l++) {
                        $at = $r[$l]->attributes();
                        $filter_colum = $at['column'];
                        $value = $at['system_name'];
                        $va = $_GET["$value"];
                        $temp .= "&$value=$va&$filter_colum=$filter_colum";
                    }
                }
                $temp .= "?mode=asc&col=$attr[column]' title='didėjimo tvarka'><img src='/admin/static/images/sort_asc.png' border='0' alt='' /><b>
$attr[name]</b></a>";
            } elseif ($_GET['mode'] == 'asc' and $_GET['col'] == $attr['column']) {
                if ($yra == 1) {
                    $r = $xml->xpath('/xml/filter_area/filter_field');
                    for ($l = 0; $l < count($r); $l++) {
                        $at = $r[$l]->attributes();
                        $filter_colum = $at['column'];
                        $value = $at['system_name'];
                        $va = $_GET["$value"];
                        $temp .= "&$value=$va&$filter_colum=$filter_colum";
                    }
                }
                $temp .= "?mode=desc&col=$attr[column]' title='mažėjimo tvarka'><img src='/admin/static/images/sort_desc.png' border='0'
 alt='' /><b>
$attr[name]</b></a>";
            } elseif ($_SESSION['sort_mode'] == 'desc' and $_SESSION['sort_column'] == $attr['column']) {
                if ($yra == 1) {
                    $r = $xml->xpath('/xml/filter_area/filter_field');
                    for ($l = 0; $l < count($r); $l++) {
                        $at = $r[$l]->attributes();
                        $filter_colum = $at['column'];
                        $value = $at['system_name'];
                        $va = $_GET["$value"];
                        $temp .= "&$value=$va&$filter_colum=$filter_colum";
                    }
                }
                $temp .= "?mode=asc&col=$attr[column]' title='didėjimo tvarka'><img src='/admin/static/images/sort_asc.png' border='0' alt='' /><b>
$attr[name]</b></a>";
            } elseif ($_SESSION['sort_mode'] == 'asc' and $_SESSION['sort_column'] == $attr['column']) {
                if ($yra == 1) {
                    $r = $xml->xpath('/xml/filter_area/filter_field');
                    for ($l = 0; $l < count($r); $l++) {
                        $at = $r[$l]->attributes();
                        $filter_colum = $at['column'];
                        $value = $at['system_name'];
                        $va = $_GET["$value"];
                        $temp .= "&$value=$va&$filter_colum=$filter_colum";
                    }
                }
                $temp .= "?mode=desc&col=$attr[column]' title='mažėjimo tvarka'><img src='/admin/static/images/sort_desc.png' border='0' alt='' /><b>
$attr[name]</b></a>";
            } else {
                if ($yra == 1) {
                    $r = $xml->xpath('/xml/filter_area/filter_field');
                    for ($l = 0; $l < count($r); $l++) {
                        $at = $r[$l]->attributes();
                        $filter_colum = $at['column'];
                        $value = $at['system_name'];
                        $va = $_GET["$value"];
                        $temp .= "&$value=$va&$filter_colum=$filter_colum";
                    }
                }

                $temp .= "?mode=asc&col=$attr[column]' title='rušiuoti'><b>$attr[name]</b></a>";
            }
            if ($attr['type'] == 'urlencoded') {
                $attr['column'] = urldecode($attr['column']);
            }
            $columns .= $attr['column'] . ', ';


        } elseif ((isset($rusiuot))) {
            $temp .= "<td style='$stylen'><b>$attr[name]</b></td>\n";
            $columns .= $attr['column'] . ', ';
        }
    }

    if ($sort_column != '') {
        $columns .= $sort_column . ', ';
    }


    if ($sort_column != '') {
        $temp .= "<th><b>Pozicija</b></th>";
    }

    if ($delete_srautas) {
        $temp .= "<th colspan=\"3\"><b>Veiksmai</b></th></tr></thead>";
    } else {
        $temp .= "<th colspan=\"2\"><b>Veiksmai</b></th></tr></thead>";
    }
    //formuojam selecta ir ji vykdom


    $custom_where = custom_where();
    $columns = substr($columns, 0, -2);

    $query = list_query($columns, $custom_where);

    $result = $DBCon->query($query);
    while ($rows = $DBCon->fetch_assoc($result)) {

        if ($rows['pk'] == $_SESSION['spalvinti']) {
            $bgs = "background-color:#7FDAFF;";
        } else {
            $bgs = "background-color:none;";
        }
        $temp .= "<tr id='idas_$rows[pk]' style='$bgs'  >\n";
        //$_SESSION[spalvinti]='';
        $c = 0;
        $r = 0;

        foreach ($rows as $key => $val) {

            $c++;
            if ($key != 'pk') {
                if ($c == 1) {
                    $url = 'http://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
                    $temp .= "<td style='$stylen'><a href='$url/edit?id=$rows[pk]' class='selected_bg'>
$val</a></td>\n";
                } else {
                    if ($redaguojamas["$r"]['editable'] == 1) {
                        $real_columnas = $redaguojamas["$r"]['real_column'];
                        $columnas = $redaguojamas["$r"]['real_column'] . '@' . $rows['pk'];
                        $kaip_rodyti = $redaguojamas["$r"]['display'];
                        if ($kaip_rodyti == 'radio') {
                            if ($val == 1) {
                                $chck1 = 'checked="checked"';
                                $chck2 = '';
                            } else {
                                $chck2 = 'checked="checked"';
                                $chck1 = '';
                            }
                            $temp .= "<td style='$stylen'><input type='radio' name='$columnas' value='1' $chck1 />Taip <br /><input type='radio' name='$columnas' value='0' $chck2 />Ne</td>\n";
                            unset($chck1, $chck2);
                        } elseif ($kaip_rodyti == 'piktograma') {
                            if ($val == 1) {
                                $klase = $rows[pk] . '_' . $c;
                                $temp .= <<<A
								<td align="center" class="$klase" style='$stylen'>
									<a href="" onclick="update_ajax('$table', '$pk', '$rows[pk]', '$real_columnas', '0', '$klase'); return false" title='TAIP' style="padding-left:8px; background:green; text-decoration:none;">&nbsp;</a>
								</td>
A;
                            } else {
                                $klase = $rows[pk] . '_' . $c;
                                $temp .= <<<A
								<td align="center" class="$klase" style='$stylen'><a href="" onclick="update_ajax('$table', '$pk', '$rows[pk]', '$real_columnas', '1', '$klase'); return false" title='NE' style="padding-left:8px; background:red; text-decoration:none;">&nbsp;</a></td>
A;
                            }
                        } elseif ($kaip_rodyti == 'piktograma_tvirtinti') {
                            if ($val == 1) {
                                $klase = $rows[pk] . '_' . $c;
                                $temp .= <<<A
								<td align="center" class="$klase" style='$stylen'>
									<a href="" onclick="update_ajax_tvirtinu('$table', '$pk', '$rows[pk]', '$real_columnas', '0', '$klase'); return false" title='TAIP' style="padding-left:8px; background:green; text-decoration:none;">&nbsp;</a>
								</td>
A;
                            } else {
                                $klase = $rows[pk] . '_' . $c;
                                $temp .= <<<A
								<td align="center" class="$klase" style='$stylen'><a href="" onclick="update_ajax_tvirtinu('$table', '$pk', '$rows[pk]', '$real_columnas', '1', '$klase'); return false" title='NE' style="padding-left:8px; background:red; text-decoration:none;">&nbsp;</a></td>
A;
                            }
                        } elseif ($kaip_rodyti == 'piktograma_radio') {
                            if ($val == 1) {
                                $klase = $rows[pk] . '_' . $c;
                                $temp .= <<<A
								<td align="center" class="radio_pik $klase" style='$stylen'>
									<a href="" onclick="update_ajax_radio('$table', '$pk', '$rows[pk]', '$real_columnas', '1', '$klase'); " title='TAIP' style="padding-left:8px; background:green; text-decoration:none;">&nbsp;</a>
								</td>
A;
                            } else {
                                $klase = $rows[pk] . '_' . $c;
                                $temp .= <<<A
								<td align="center" class="radio_pik $klase" style='$stylen'><a href="" onclick="update_ajax_radio('$table', '$pk', '$rows[pk]', '$real_columnas', '1', '$klase'); " title='NE' style="padding-left:8px; background:red; text-decoration:none;">&nbsp;</a></td>
A;
                            }
                        }

                    } else {
                        $temp .= "<td>$val</td>\n";
                    }
                }
            } else {
                $url = 'http://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
                $temp .= <<<A
						<td align="center">
						<a href="$url/edit?id=$val"
						class="btn btn-primary" title="redaguoti">
						<i class="fa fa-edit"></i>
						</a></td>
						<td align="center">
						<a href="" class="btn btn-red" border="0" alt=""
						onclick="ajax_delete('$table', '$pk', '$val'); return false;"/>
						<i class="fa fa-times fa fa-white"></i>
						</a></td>
A;
                if ($delete_srautas) {
                    $temp .= <<<A
					<td><input type='checkbox' name='$val' /></td>
A;
                }


            }
            $r++;
        }
        $temp .= "</tr>\n";
    }
    $temp .= "</table><br /><br />";
    $temp .= do_paging();
    $temp .= "<br /><br />";
    if ($editable_fields) {
        $temp .= <<<A
	<br /><br />
	<input type="submit" value="Išsaugoti pakeitimus" />
	</form>
A;
    }


    return $temp;
}


/*
pakeitimo funkcija
*/
function edit_form($action) {
    global $xml, $DBCon, $table, $pk;
    $id = $_GET['id'];
    $kintamieji = explode('/', $_SERVER['REQUEST_URI']);
    if (isset($_GET['new']) || $kintamieji[3] == 'new') {
        $sub_titlas = 'Sukurti';
        $submitas2 = <<<A
		<input type="submit" name="edit_news_sbm1" value="Sukurti ir grįžti į sąrašą" class="adm_sbm_btn" />
A;
    } else {
        $sub_titlas = 'Pakeisti';
        $submitas2 = <<<A
		<input type="submit" name="edit_news_sbm1" value="Pakeisti ir grįžti į sąrašą" class="adm_sbm_btn" />
A;
    }
    if ($_GET['unset_name'] != '') {

        $sql = "update $table set $_GET[unset_name]=null where $pk='$_GET[id]'";
        $DBCon->query($sql);
    }
    $redirect = 'Location: http://' . $_SERVER['SERVER_NAME'] . '/' . $kintamieji[1] . '/' . $kintamieji[2];

    if ($_POST['form_action'] == 'edit') {
        sql_update(isset($_POST['edit_news_sbm1']));
        //printdie($redirect);
        header($redirect);
    } else if ($_POST['form_action'] == 'insert') {
        sql_insert(isset($_POST['edit_news_sbm1']));
        //printdie($redirect);
        header($redirect);
    }
    $perziurai = '';
    if ($table == 'mod_posts') {
        $aliasui = "select alias from mod_posts where id='$id'";
        $r_aliasui = $DBCon->query($aliasui);
        $w_aliasui = $DBCon->fetch_array($r_aliasui);
        $perziurai = "<br /><a href='$GLOBALS[base]$w_aliasui[alias]/' style='font-size:14px;' target='_blank'>Peržiųrėti straipsnį svetainėje</a><br /><br />";
    }
    $temp = '';

    $temp .= <<<A
<form action="" method="POST" enctype="multipart/form-data" name="edit_news_form">
<input type="hidden" name="id" value="$id" />
<input type="hidden" name="form_action" value="$action" />
$perziurai
A;

    //print_r($xml);
    $result = $xml->xpath('/xml/edit/area');
    //print_r($result);
    for ($i = 0; $i < count($result); $i++) {
        //print_r($xml[0]);
        $attr = $result[$i]->attributes();
        $temp .= <<<A
	<fieldset>
<legend>$attr[name]:</legend>
A;
        $tmp = edit_form_table($action, $result[$i]);
        $temp .= <<<A
		$tmp
		<br /><br />
		$submitas2
	</fieldset>

A;

    }

//<input type="hidden" name="default" value="$_SESSION[default]">

    $temp .= <<<A
</form>

A;
    $res1 = $xml->xpath('/xml/edit/frame');


    if ($res1) {
        for ($u = 0; $u < count($res1); $u++) {
            $temp .= show_frame($action, $u);
        }
    }
    return "$temp";

}

function edit_form_table($action, $xml) {
    global $DBCon, $table, $orderby, $pk;
    $tmp = '';
    $temp = <<<A
<table border="0">
A;
    $values_array = default_column_values($action, $xml);


    $result = $xml->xpath('table/row');
    for ($i = 0; $i < count($result); $i++) {
        $attr = $result[$i]->attributes();
        if ($attr['rowspan'] == '') {
            $rowspan = '1';
        } else {
            $rowspan = $attr['rowspan'];
        }

        $tmp .= <<<A
 <tr rowspan="$rowspan"> 
A;

        $tmp .= list_module_tabs_content_table_cols($result[$i], $values_array);
        $tmp .= <<<A
</tr>   
A;
    }


    $temp .= <<<A
$tmp
  </table>
A;


    return "$temp";
}

function list_module_tabs_content_table_cols($result_xml, $values_array) {
    $result = $result_xml->xpath('col');

    for ($i = 0; $i < count($result); $i++) {


        $attr = $result[$i]->attributes();

        if ($attr['colspan'] == '') {
            $colspan = 1;
        } else {
            $colspan = $attr['colspan'];
        }
        $temp = $values_array["$attr[column]"];
        $temp_control = help_create_controls($attr, $temp);
        $display = $attr['display'];
        $attr['name'] = urldecode($attr['name']);
        if ((StartsWith($display, 'textareahtml')) or (StartsWith($display, 'textarea'))) {
            $colspan = $colspan + 3;
            $tmp = '';
            $tmp .= <<<A
			
<td colspan="$colspan">$attr[name]<br><br>$temp_control</td>
A;
        } else {
            $tmp = <<<A
<td colspan="$colspan">$attr[name]</td><td colspan="$colspan">$temp_control</td>
A;
        }
    }
    return $tmp;
}

function help_create_controls($attr, $reiksme) {
    global $table, $pk;
    $display = $attr['display'];
    $name = $attr['column'];
    $id = $attr['id'];
    $count = $attr['count'];
    //echo $name.' - '.$reiksme.'<br />';

    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
 style="$style"
A;
    }

    if ($attr['readonly'] == '1') {

        if ($display == 'checkbox') {
            $temp = $reiksme == 1 ? "Taip" : "Ne";
        } else {
            $temp = $reiksme;
        }
    } else if ($display == 'textarea') {
        if ($attr['size'] == '') {
            $attr['size'] = "15x4";
        }
        $dimensions = explode("x", $attr['size']);
        $temp = '';
        $temp .= <<<A
    <textarea name="$name" cols="$dimensions[0]" rows="$dimensions[1]"$style>$reiksme</textarea>
A;
    } else if ($display == 'textareahtml') {
        if ($attr['size'] == '') {
            $attr['size'] = "15x4";
        }
        $dimensions = explode("x", $attr['size']);
        $temp = '';
        $temp .= <<<A
    <textarea name="$name" class="mce">$reiksme</textarea>
A;
    } elseif (StartsWith($display, 'smalldatetime') or StartsWith($display, 'datetime') or StartsWith($display, 'money') or StartsWith($display, 'int') or StartsWith($display, 'decimal')) {
        if ($name != 'galioja_iki') {
            $dabar = date('Y-m-d');
        } elseif ($name == 'galioja_iki') {
            $dabar = date('Y-m-d');
            $date = new DateTime($dabar);
            $date->add(new DateInterval('P31D'));
            $dabar = $date->format('Y-m-d');
        }

        if ($reiksme == '' and !StartsWith($display, 'decimal')) {
            $temp = <<<A
			<input type="text"  name="$name" class="$display" value="$dabar" $style/>
A;
        } else {
            $temp = <<<A
			<input type="text"  name="$name" class="$display" value="$reiksme" $style/>
A;
        }
    } else if ($display == 'password') {
        $temp = <<<A
   <input type="password"  name="$name" value="$reiksme"$style/>
A;
    } else if ($display == 'radio') {

        //if($reiksme== 1 ? $checked1 = 'checked="checked"' : $checked2 = 'checked="checked"');
        if ($name == 'visible') {
            if ($reiksme == 1) {
                $checked1 = 'checked="checked"';

            } elseif ($reiksme == 0 and $reiksme != null) {
                $checked2 = 'checked="checked"';

            } else {

                $checked1 = 'checked="checked"';
                $checked2 = '';
            }
        } else {
            if ($reiksme == 1 ? $checked1 = 'checked="checked"' : $checked2 = 'checked="checked"')
                ;
        }

        $temp = <<<A
    <input type="radio" name="$name" value="1" $checked1/> Taip<br />
        <input type="radio" name="$name" value="0" $checked2/> Ne
A;
    } elseif ($display == 'checkbox') {
        if ($name == 'visible' or $name == 'deretis' or $name == 'prekyboje') {
            if ($reiksme == 1) {
                $checked1 = 'checked="checked"';
            } else {
                $checked1 = 'checked=""';
            }
        } else {
            if ($reiksme == 1 ? $checked1 = 'checked="checked"' : $checked2 = 'checked="checked"')
                ;
        }
        $temp = <<<A
			<input type="hidden" name="$name" value="0" />
			<input type="checkbox" name="$name" value="1" $checked1 />
			
			<!--<input type="checkbox" name="$name" value="1" />--><br />
A;
    } else if ($display == 'combo') {

        $temp = <<<A
     <select size="1" name="$name"$style>
A;


        // echo $attr[select];
        $attr['select'] = str_replace('@lang', $_SESSION['lang'], $attr['select']);

        $result = mysql_query($attr['select']) or die('klaida sqle' . $attr['select']);
        while ($rows = mysql_fetch_row($result)) {
            //print_r($rows);
            unset($set);
            if (isset($_GET['edit'])) {
                if ($rows[0] == $reiksme) {
                    $set = 'selected';
                }
            } else {
                if ($rows[0] == $reiksme or $rows[0] == $_GET['filter_value']) {
                    $set = 'selected';
                }
            }

            $temp .= <<<A
			<option value="$rows[0]" $set>$rows[1]</option>
A;


        }


        $temp .= <<<A
</select>
    
A;
    } else if ($display == 'foto') {

        if ($reiksme != '') {
            $dimensijos = $attr[dimensions];
            $vv = str_replace('/static/uploads/', '/static/uploads/cache/80x80/', $reiksme);
            $temp .= <<<A
   <a href="$reiksme" target="_blank"><img src="$vv" /></a>  <a href="$_SERVER[SCRIPT_NAME]?edit&id=$_GET[id]&unset_name=$name&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]" onclick="javascript:return confirm('Ar tikrai ištrinti foto?')">x</a>
   <a href="/admin/xml_system/edit_foto/index.php?table=$table&column=$name&dimensions=$dimensijos&pk=$pk&pk_val=$_GET[id]" title="Radaguoti foto" rel="fancy">r</a><br />
A;
        }

        //if($table=='mod_catalogue') {
        if ($count > 0 and $id != '') {
            $temp .= <<<A
				&nbsp;&nbsp;<a href="" onclick="changeFotoType(1, $count); $(this).fadeOut(); $('.viaPC_$count').fadeIn(); return false;" class="viaBrowse_$count">Foto is serverio</a>
				&nbsp;&nbsp;<a href="" onclick="changeFotoType(2, $count); $(this).fadeOut(); $('.viaBrowse_$count').fadeIn(); return false;" class="viaPC_$count" style="display:none;">Foto is kompiuterio</a>
				<input type="hidden" id="AddFotoType" value="1" disabled="disabled" />
A;
        }
        $temp .= <<<A
    <input type="file" name="$name" id="$id" /><br /><br /><br />
    
A;
    } else if ($display == 'file') {

        if ($reiksme != '') {

            $reiksme1 = explode('_', $reiksme);

            $temp .= <<<A
   <a href="$reiksme" target="_blank">Failas</a>  <a href="$_SERVER[SCRIPT_NAME]?edit&id=$_GET[id]&unset_name=$name&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]" onclick="javascript:return confirm('Ar tikrai ištrinti faila?')">x</a><br />  
A;
        }

        if ($id != '') {
            $temp .= <<<A
				&nbsp;&nbsp;<a href="" onclick="changeFileType(1); $(this).fadeOut(); $('.viaPC_file').fadeIn(); return false;" class="viaBrowse_file">Failas is serverio</a>
				&nbsp;&nbsp;<a href="" onclick="changeFileType(2); $(this).fadeOut(); $('.viaBrowse_file').fadeIn(); return false;" class="viaPC_file" style="display:none;">Failas is kompiuterio</a>
				<input type="hidden" id="AddFileType" value="1" disabled="disabled" />
A;
        }

        $temp .= <<<A
    <input type="file" name="$name" id="$id" />
    
A;
    } elseif ($display == 'required') {
        $temp .= <<<A
    *<input type="text"  name="$name"  value='$reiksme' $style/>
A;
    } else {

        /*
        suzaisti su required
        */
        $temp .= <<<A
    <input type="text"  name="$name" id="$id" value='$reiksme'$style/>
A;
    }


    //print_r($temp);
    return $temp;
}


function sql_help_enum_controls($result_xml) {

    $return_array = array();
    $result = $result_xml->xpath('col');

    for ($i = 0; $i < count($result); $i++) {

        $attr = $result[$i]->attributes();
        $column = $attr['column'];

        $post = $_POST["$column"];

        if ($attr['display'] == 'foto') {
            $return_array = array_merge($return_array, photo_upload($attr, $column));
        } else if ($attr['display'] == 'file') {
            $return_array = array_merge($return_array, file_upload($attr, $column));
        } else if (($attr['readonly'] != '1') and isset($_POST["$column"])) {
            $return_array["$column"] = $post;
        }
        if ($attr['more_action'] != '') {
            $temp_array = array_merge($return_array, more_actions($attr, $column));
            foreach ($temp_array as $key => $value) {
                $return_array["$key"] = $value;
            }
        }


    }

    return $return_array;
}


function file_upload($attr, $real_column) {
    $return_array = array();
    $column = $attr["column"];
    $post = $_POST["$column"];

    $options = explode(";", $attr[options]);

    foreach ($options as $opcija) {
        $opcija = trim($opcija);

        if (StartsWith($opcija, 'dir')) {
            $reali_opcija = explode(":", $opcija);
            $dir = $reali_opcija[1];
        }
    }

    if (is_uploaded_file($_FILES["$real_column"]['tmp_name'])) {

        //die('aa');
        $key = date("YmdHis");


        //die ($_FILES["$real_column"]['name']);


        $_FILES["$real_column"]['name'] = str_replace(" ", "-", $_FILES["$real_column"]['name']);

        $_FILES["$real_column"]['name'] = preg_replace("[^A-Za-z0-9_-/.]", "", $_FILES["$real_column"]['name']);


        //die ($_FILES["$real_column"]['name']);

        //$post="../static/uploads/$dir/" . $key .'-'. $_FILES["$real_column"]['name'];
        $post = "../static/uploads/$dir/" . $_FILES["$real_column"]['name'];

        if (!move_uploaded_file($_FILES["$real_column"]['tmp_name'], $post)) {

            die('Negaliu irasyti failo: ' . $post);
        }


        $post = substr($post, 2); //nuimam .. prieky
        //echo $post;
        //die($post);
        $return_array["$column"] = $post;
    }
    //echo "photo_upload";
    //print_r($return_array);
    return $return_array;
}


function photo_upload($attr, $real_column) {
    $return_array = array();
    $column = $attr["column"];
    $post = $_POST["$column"];

    //options="resize:117x75; mode:2; dir:catalogue/asd/; watermark:kitas.png,right, bototm"

    $options = explode(";", $attr[options]);


    foreach ($options as $opcija) {
        $opcija = trim($opcija);
        if (StartsWith($opcija, 'resize')) {
            $reali_opcija = explode(":", $opcija);
            $resize = explode("x", $reali_opcija[1]);

        } elseif (StartsWith($opcija, 'mode')) {
            $reali_opcija = explode(":", $opcija);
            $mode = $reali_opcija[1];


        } elseif (StartsWith($opcija, 'dir')) {
            $reali_opcija = explode(":", $opcija);
            $dir = $reali_opcija[1];
        } elseif (StartsWith($opcija, 'watermark')) {
            $reali_opcija = explode(":", $opcija);
            $reali_opcija = explode(",", $reali_opcija[1]);
            //	print_r($reali_opcija);
            $watermark_image = '..' . $reali_opcija[0];
            $watermark_pos1 = $reali_opcija[1];
            $watermark_pos2 = $reali_opcija[2];
        }


    }

    if ($mode == '') {
        $mode = 2;
    }


    if (is_uploaded_file($_FILES["$real_column"]['tmp_name'])) {
        $key = date("YmdHis");
        $_FILES["$real_column"]['name'] = strtolower($_FILES["$real_column"]['name']);
        $_FILES["$real_column"]['name'] = str_replace(" ", "-", $_FILES["$real_column"]['name']);

        $_FILES["$real_column"]['name'] = preg_replace("[^A-Za-z0-9/.-_]", "", $_FILES["$real_column"]['name']);
        $post = "../static/uploads/$dir/" . $_FILES["$real_column"]['name'];
        $i = 0;
        while (file_exists($post)) {
            $i++;
            $aa = str_replace('.', "_$i.", $_FILES["$real_column"]['name']);
            $post = "../static/uploads/$dir/" . $aa;
        }
        $img = new imageris();
        $img->image_set($_FILES["$real_column"]['tmp_name']);

        if ($resize[0] != '') {
            if ($mode == 3) {
                $img->image_resize($resize[0], $resize[1], $mode);
            } elseif ($mode == 2) {
                $width_real = $img->image_get_width();
                $height_real = $img->image_get_height();

                if (($resize[0] >= $width_real) and ($resize[1] >= $height_real)) {

                    echo 'neberesaizinu';
                } else {
                    $img->image_resize($resize[0], $resize[1], $mode);
                }
            } else {
                $img->image_crop($resize[0], $resize[1]);
            }
            if ($watermark_image != '') {

                if (!file_exists($watermark_image)) {
                    echo 'NEegzistuoja watermarkas';
                }

                $img->image_put_image($watermark_image, $watermark_pos1, $watermark_pos2);
            }
            $img->image_save($post);
        } else {
            move_uploaded_file($_FILES["$real_column"]['tmp_name'], $post);
        }

        $post = substr($post, 2);

        $return_array["$column"] = $post;
    }
    return $return_array;
}

function more_actions($attr, $real_column) {

    global $xml;
    $return_array = array();
    $action_name = $attr[more_action];

    $query = "//more_actions//action[@name='$action_name']";
    //print_r($xml);
    $result_temp = $xml->xpath($query);


    $result = $result_temp[0]->xpath('action');
    for ($i = 0; $i < count($result); $i++) {
        $attr2 = $result[$i]->attributes();

        if ($attr[display] == 'foto') {
            $return_array = array_merge($return_array, photo_upload($attr2, $real_column));
        }

    }

    return $return_array;
}


function generate_menu($parent, $parent_alias, $url_name_aray, $dir_count) {
    global $DBCon, $dbTbl, $menu_array;
    $dir_count++;
    $has_childs = false;

    foreach ($menu_array as $key => $value) {
        if ($value['parent'] == $parent) {
            $temp_meniu = generate_menu($key, $value['alias'], $url_name_aray, $dir_count);
            $value[name] = str_repeat('&nbsp;', $dir_count) . ' ' . $value[name];
            unset($style);
            if ($value[real_id] == $_GET['default']) {
                $style = <<<A
 style="color: rgb(255, 153, 51)"
A;

            }

            $temp .= <<<A
 <tr>
      <td nowrap><a href="$_SERVER[REQUEST_URI]"$style>$value[name]</a></td>
    </tr> 
    $temp_meniu
A;
        }
    }
    return $temp;

}

function list_meniu() {

    global $DBCon, $menu_array;

    $xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . '/admin/modules/' . $_SESSION['module'] . '/xml/xml_menu.xml');
    $result = $xml->xpath('/xml/list');
    $result = $result[0];
    $select = $result['select'];
    $type = $result['type'];
    $GLOBALS['submodule'] = $result['submodule'];
    $GLOBALS['filter_name'] = $result['filter_name'];
    $meniu_id = $result['meniu_id'];
    $attr = $result[0]->attributes();
    $vvv = $attr['filter_value'];

    if ($type == 'tree') {

        $query = $select;
        //echo $query;
        $result = $DBCon->query($query);
        // die($query);
        while ($row = $DBCon->fetch_array($result)) {

            $menu_array[$row['id']] = array('name' => $row['name'], 'parent' => $row['parent'], 'alias' => $row['alias'], 'real_id' => $row["$vvv"], 'show' => $row['show']);

        }


        $temp = generate_menu($meniu_id, '', array(), 0);
        if ($_SESSION['submodule'] == '') {

            $_SESSION['submodule'] = (string)$GLOBALS[submodule];

        }
        //die ($temp);
    } else {
        $i = 0;
        $select = str_replace("'%Y'", '"%Y"', $select);
        $result = $DBCon->query($select);
        while ($rows = $DBCon->fetch_row($result)) {
            if (($i == 0) && ($_SESSION['submodule'] == '')) {
                $_SESSION['submodule'] = $rows[1];
            }
            unset ($style);
            if (($_SESSION['submodule'] == $rows[1]) and ($_GET[filter_name] == $rows[3]) and ($_GET[filter_value] == $rows[4])) {
                $style = <<<A
 style="color: rgb(255, 153, 51); text-decoration:none"
A;
            } else {
                $style = <<<A
 style="text-decoration:none"
A;
            }


            $temp = <<<A
 <tr>
      <td nowrap><a href='$_SERVER[REQUEST_URI]'$style>$rows[0]</a></td>
    </tr> 
A;

            $i++;

        }
    }
    //uzsetinam kintamuosius
    define_main_vars($_SESSION['submodule']);
    return $temp;
}


function show_frame($action, $u) {
    global $xml;

    $result = $xml->xpath('/xml/edit/frame');
    $attr = $result[$u]->attributes();

    $default_file = $attr["url"];
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
 style="$style"
A;
    }

    $dir_path = dirname(realpath(__FILE__));

    if (isset($_GET['new'])) {
        $temp = <<<A
		<fieldset>
<legend>$attr[name]:</legend>
<iframe $style src="../$attr[url]" ></iframe>
</fieldset>
A;
    } else {
        $temp = <<<A
		<fieldset>
<legend>$attr[name]:</legend>
<iframe $style src="../$attr[url]?fk=$_GET[id]&tipas=$_GET[filter_value]" ></iframe>
</fieldset>
A;
    }
    return $temp;
}


function show_search($i) {
    global $xml;


    $result = $xml->xpath('/xml/filter_area/filter_field');
    $attr = $result[$i]->attributes();
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
 style="$style"
A;
    }
    $search_colum = $attr['column'];
    $value = $attr['system_name'];
    $name = $attr['name'];
    if (isset($_GET["$value"])) {
        $vv = $_GET["$value"];
    } else {
        $vv = '';
    }
    $temp = <<<A
<td>$name: </td> 
<input type="hidden" name="$search_colum" value="$search_colum">
<td><input type="text" name="$value" value="$vv"$style /></td>
A;
    return $temp;
}


function show_checkbox_search($i) {
    global $xml;

    $result = $xml->xpath('/xml/filter_area/filter_field');
    $attr = $result[$i]->attributes();
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
		style="$style"
A;
    }
    $search_colum = $attr['column'];
    $value = $attr['system_name'];
    $name = $attr['name'];
    $chk = $attr['checked'];
    if ($_GET["$value"]) {
        $vv = $_GET["$value"];
    } else {
        $vv = '';
    }
    $checked1 = $vv == 1 ? 'checked="checked"' : "";

    $temp = <<<A
	<td>$name: </td> 
		<input type="hidden" name="$search_colum" value="$search_colum">
		<td>
			<!--<input type="hidden" name="$value" value="0" />-->
			<input type="checkbox" name="$value" value="1" $checked1 />
		</td>
A;
    return $temp;
}

function show_not_nulas($i) {
    global $xml;

    $result = $xml->xpath('/xml/filter_area/filter_field');
    $attr = $result[$i]->attributes();
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
		style="$style"
A;
    }
    $search_colum = $attr['column'];
    $value = $attr['system_name'];
    $name = $attr['name'];
    if ($_GET["$value"]) {
        $vv = $_GET["$value"];
    } else {
        $vv = '';
    }
    if ($vv == 1 ? $checked1 = 'checked="checked"' : $checked2 = 'checked="checked"')
        ;

    $temp = <<<A
	<td>$name: </td> 
		<input type="hidden" name="$search_colum" value="$search_colum">
		<td>
			<input type="checkbox" name="$value" value="1" $checked1 />
		</td>
A;
    return $temp;
}

function show_data_search($i) {
    global $xml;
    $result = $xml->xpath('/xml/filter_area/filter_field');
    $attr = $result[$i]->attributes();
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
		style="$style"
A;
    }
    $search_colum = $attr['column'];
    $value = $attr['system_name'];
    $name = $attr['name'];
    if ($_GET["$value"]) {
        $vv = $_GET["$value"];
    }
    $temp = <<<A
	<td>$name: </td> 
	<input type="hidden" name="$search_colum" value="$search_colum">
	<td><input type="text" name="$value" value="$vv" $style class="smalldatetime" /></td>
A;
    return $temp;
}


function show_interval_search($i) {
    global $xml;
    $result = $xml->xpath('/xml/filter_area/filter_field');
    $attr = $result[$i]->attributes();
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
 style="$style"
A;
    }
    $search_colum = $attr['column'];
    $value = $attr['system_name'];
    $value2 = $attr['system_name2'];
    $name = $attr['name'];
    if ($_GET["$value"]) {
        $vv = $_GET["$value"];
    } else {
        $vv = '';
    }
    if ($_GET["$value2"]) {
        $vv2 = $_GET["$value2"];
    } else {
        $vv2 = Date('Y-m-d');
    }

    $temp = <<<A
<td>$name: </td> 
<input type="hidden" name="$search_colum" value="$search_colum">
<td>nuo <input type="text" name="$value" value="$vv"$style class="smalldatetime" /> iki <input type="text" name="$value2" value="$vv2"$style class="smalldatetime" /></td>
A;
    return $temp;
}

function show_filter($i) {
    global $xml;

    $result = $xml->xpath('/xml/filter_area/filter_field');
    $attr = $result[$i]->attributes();
    $style = $attr['style'];
    if ($style != '') {
        $style = <<<A
 style="$style"
A;
    }
    $filter_colum = $attr['column'];
    $name = $attr['name'];
    $value = $attr['system_name'];
    $attr['select'] = str_replace('@lang', $_SESSION['lang'], $attr['select']);
    //$result = mysql_query( $attr[select] ) or die('klaida sqle');

    if (preg_match("/\@default\b/i", "$attr[select]")) {
        $ath = str_replace('@default', '"' . $_GET['default'] . '"', $attr['select']);
    } elseif (preg_match("/\@filter_name\b/i", "$attr[select]")) {
        $ath = str_replace('@filter_name', '"' . $_GET['filter_name'] . '"', $attr['select']);
    } elseif (preg_match("/\@filter_value\b/i", "$attr[select]")) {
        $ath = str_replace('@filter_value', '"' . $_GET['filter_value'] . '"', $attr['select']);
    } else {
        $ath = $attr['select'];
    }

    $result = mysql_query($ath) or die('klaida sqle' . $ath);
    $temp = '';
    $temp .= "<td>$name: </td>";
    $temp .= "<td><select name='$value' $style>";
    $temp .= "<option value=''>$name</option>";
    while ($rows = mysql_fetch_row($result)) {
        if ($rows[0] != '') {
            unset($aaa);
            $opt = $rows[0];
            if ($_GET["$value"] == $opt) {

                $aaa = 'SELECTED';
            } else {
                $aaa = '';
            }
            $temp .= <<<A
				<option value="$opt" $aaa >$rows[1]</option>
A;
        }
    }

    $temp .= <<<A
	        </select></td>
			<input type="hidden" name="$filter_colum" value="$filter_colum">
			
A;

    return $temp;

}


function do_paging() {
    global $DBCon, $table, $paging, $records_count, $xml;
    if ($paging != '') {
        $custom_where = custom_where();
        $result = $xml->xpath('/xml/filter_area/filter_field');
        for ($i = 0; $i < count($result); $i++) {
            $attr = $result[$i]->attributes();
            $filter_colum = $attr['column'];
            $value = $attr['system_name'];
            if (isset($_GET["$value"]) and $_GET["$value"] != '') {
                $va = $_GET["$value"];
                $custom_where .= " and $filter_colum='$va'";

            }
        }
        $query = <<<A
select count(*) from  $table WHERE 1=1 $custom_where
A;

        $result = $DBCon->query($query);
        $default_array = $DBCon->fetch_row($result);
        $records_count = $default_array[0];
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = '';
        }
        if ($page == '') {
            $page = 1;
        }

        $style1 = '';
        $style2 = '';
        $style3 = '';
        $style4 = '';
        $style5 = '';

        //-------------------------------rodyti po----------------------
        if ($_SESSION['paging'] == '') {
            $_SESSION['paging'] = 10;
        }
        if (isset($_GET['page_count']) && is_numeric($_GET['page_count'])) {
            $_SESSION['paging'] = $_GET['page_count'];
        }
        $paging = $_SESSION['paging'];
        if ($_SESSION['paging'] == 10) {
            $style1 = "color: rgb(255, 153, 51)";
        }
        if ($_SESSION['paging'] == 25) {
            $style2 = "color: rgb(255, 153, 51)";
        }
        if ($_SESSION['paging'] == 50) {
            $style3 = "color: rgb(255, 153, 51)";
        }
        if ($_SESSION['paging'] == 100) {
            $style4 = "color: rgb(255, 153, 51)";
        }
        if ($_SESSION['paging'] == 9999999) {
            $style5 = "color: rgb(255, 153, 51)";
        }

        $temp = <<<A
		<br /><span style="font-weight:bold;">Rodyti po:</span> 
		<a href="$_SERVER[REQUEST_URI]?page_count=10" style="$style1">10</a>
		<a href="$_SERVER[REQUEST_URI]?page_count=25" style="$style2">25</a>
		<a href="$_SERVER[REQUEST_URI]?page_count=50" style="$style3">50</a>
		<a href="$_SERVER[REQUEST_URI]?page_count=100" style="$style4">100</a>
		<a href="$_SERVER[REQUEST_URI]?page_count=9999999" style="$style5">Visi</a>&nbsp;&nbsp;<br />
A;
        //--------------------------------------------------------------

        $viso_puslapiu = ceil($records_count / $paging);;
        if ($viso_puslapiu > 1) {
            for ($i = 1; $i <= $viso_puslapiu; $i++) {

                unset($style);
                if ($page == $i) {
                    $style = <<<A
 style="color: rgb(255, 153, 51)"
A;
                }
                $result = $xml->xpath('/xml/filter_area/filter_field');
                for ($u = 0; $u < count($result); $u++) {
                    $attr = $result[$u]->attributes();
                    $filter_colum = $attr['column'];
                    $value = $attr['system_name'];
                    $va = $_GET["$value"];
                    if ($_GET["$value"]) {
                        $yra = 1;
                    }
                }
                $temp .= <<<A
<a href="$_SERVER[REQUEST_URI]?page=$i&filter_name=$_GET[filter_name]&filter_value=$_GET[filter_value]
A;
                if ($yra == 1) {
                    $result = $xml->xpath('/xml/filter_area/filter_field');
                    for ($k = 0; $k < count($result); $k++) {
                        $attr = $result[$k]->attributes();
                        $filter_colum = $attr['column'];
                        $value = $attr['system_name'];
                        $va = $_GET["$value"];
                        $temp .= "&$value=$va&$filter_colum=$filter_colum";
                    }
                    if ($_GET['mode'] == 'desc' and $_GET['col']) {
                        $temp .= "&mode=desc&col=$_GET[col]";
                        $_SESSION['sort_mode'] = 'desc';
                        $_SESSION['sort_column'] = $_GET['col'];
                    } elseif ($_GET['mode'] == 'asc' and $_GET['col']) {
                        $temp .= "&mode=asc&col=$_GET[col]";
                        $_SESSION['sort_mode'] = 'asc';
                        $_SESSION['sort_column'] = $_GET['col'];
                    }
                } elseif ($_GET['mode'] == 'desc' and $_GET['col']) {
                    $temp .= "&mode=desc&col=$_GET[col]";
                    $_SESSION['sort_mode'] = 'desc';
                    $_SESSION['sort_column'] = $_GET['col'];
                } elseif ($_GET['mode'] == 'asc' and $_GET['col']) {
                    $temp .= "&mode=asc&col=$_GET[col]";
                    $_SESSION['sort_mode'] = 'asc';
                    $_SESSION['sort_column'] = $_GET['col'];
                    //echo("ASCENDINGAS<br>");
                }

                $temp .= <<<A
"$style>$i</a> -
A;
            }
        }

        if ($_GET['mode'] == 'desc' and $_GET['col']) {
            $_SESSION['sort_mode'] = 'desc';
            $_SESSION['sort_column'] = $_GET['col'];
        } elseif ($_GET['mode'] == 'asc' and $_GET['col']) {
            $_SESSION['sort_mode'] = 'asc';
            $_SESSION['sort_column'] = $_GET['col'];
        }

    }
    return $temp;
}

function custom_where() {
    global $where, $lang_column;
    if (isset($_GET['filter_name'])) {
        $_GET['filter_name'] = str_replace('\"', '"', $_GET['filter_name']);
        //where 1=1
        $custom_where = <<<A

A;
        if ($where != '') {
            $custom_where .= <<<A
 and $where 
A;
        }
        if ($lang_column != '') {
            $custom_where .= <<<A
 and $lang_column='$_SESSION[lang]' 		
A;
        }
        if ($_GET['filter_name'] != '' and $_GET['filter_name'] != 'meniu_id_lt') {
            $custom_where .= <<<A
 and $_GET[filter_name]='$_GET[filter_value]' 		
A;
        }
        if ($_GET['filter_name'] != '' and $_GET['filter_name'] == 'meniu_id_lt') {
            $custom_where .= <<<A
	and $_GET[filter_name]='$_GET[filter_value]' 	
A;
        }
        return $custom_where;
    }
}

?>



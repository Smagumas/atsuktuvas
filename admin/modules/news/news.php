<?php
error_reporting(E_PARSE);

function Load_Module() {
    global $DBCon, $kintamieji;

    require($_SERVER['DOCUMENT_ROOT'] . '/functions/xmlfunction.php');

    $DBCon->Assign('list_menu', list_meniu());
    $DBCon->Assign('template', dirname(__FILE__) . '/news.tpl');
    if ($kintamieji[3] == 'new') {
        $DBCon->Assign('list_form', edit_form('insert'));
    } else if ($kintamieji[3] == 'edit') {
        $DBCon->Assign('list_form', edit_form('edit'));
    } else {
        $DBCon->Assign('list_form', list_form());
    }

}

?>
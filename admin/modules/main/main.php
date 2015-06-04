<?php
function Load_Module(){
    global $DBCon;
    $DBCon->Assign('template', dirname( __FILE__ ). '/main.tpl');
}

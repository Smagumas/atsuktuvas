<?php
require_once('../core.php');
header('Content-Type: application/json');
$result = $DBCon->query("SELECT Title AS Name, Alias FROM cms_menu");
while ($row = $DBCon->fetch_assoc($result)){
    $rows[] = $row;
}

echo json_encode($rows);





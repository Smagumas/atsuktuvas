<?php
require_once('../core.php');

if (isset($_POST['action']) && $_POST['action'] == 'dnd' && $_POST['parent'] != '#') {
    $parent = $_POST['parent'];
    $p = is_numeric($parent) ? $parent : null;
    $DBCon->query("UPDATE cms_menu SET Position = '$_POST[position]' , Parent = '$p', PositionOrder = '$_POST[order]' WHERE Id = '$_POST[id]'");
    foreach($_POST['children_all'] AS $child){
        $DBCon->query("UPDATE cms_menu SET Position = '$_POST[position]' WHERE Id = '$child'");
    }
    foreach($_POST['elements'] AS $elem){
        $DBCon->query("UPDATE cms_menu SET PositionOrder = '$elem[order]' WHERE Id = '$elem[node_id]'");
    }
} else if ($_GET['action'] == 'delete') {
    $DBCon->query("DELETE FROM cms_menu WHERE Id = '$_GET[id]'");
} else if ($_GET['action'] == 'disable' || $_GET['action'] == 'enable') {
    $boolas = $_GET['action'] == 'enable'? 1:0;
    $DBCon->query("UPDATE cms_menu SET IsVisible = '$boolas' WHERE Id = '$_GET[id]' ");
} else if ($_GET['action'] == 'menu') {
    header('Content-Type: application/json');
    function build($rootId, $parent = 0) {
        global $cms_menu;

        $output = [];
        foreach ($cms_menu as $row) {
            if ($parent != $row['Parent'] || $rootId != $row['Position'])
                continue;
            //$disabled = ['disabled' => $row['IsVisible'] == '1' ? false : true];
            $disabled = ['disabled' => false];
            if($row['IsVisible'] == '0'){
                $type = 'disabled';
            }else{
                $type = 'file';
            }
            $info = ['id' => (int)$row['Id'], 'parentId' => $row['Parent']];
            $item = [
                'mainid' => $row['Id'],
                'id' => $row['Id'],
                'text' => $row['Title'],
                'parentId' => $row['Parent'],
                'position' => $row['PositionOrder'],
                'positionId' => $row['Position'],
                'state' => $disabled,
                'type' => $type,
                'data' => $info
            ];
            $childs = build($rootId, $row['Id']);
            if ($childs) {
                $item['children'] = $childs;
                $item['type'] = 'parent';
            }
            if ($row['Parent'] > 0)
                $item['parentId'] = $row['Parent'];
            $output[] = $item;
        }

        return $output;
    }

    $input = $DBCon->query("SELECT Id, Title, Alias, IsVisible, Parent, Position, PositionOrder FROM cms_menu WHERE
    Lang='$_GET[lang]' ORDER BY PositionOrder");
    $cms_menu = array();
    while ($menu_row = $DBCon->fetch_assoc($input)) {
        $cms_menu[] = $menu_row;
    }

    $cats = $DBCon->query('SELECT Id, Title, IsVisible FROM cms_menu_positions_list');

    $output = [];
    while ($cats_row = $DBCon->fetch_assoc($cats)) {
        $categories = [
            'id' => 'root_' . $cats_row['Id'],
            'text' => $cats_row['Title'],
            'data' => [
                'id' => (int)$cats_row['Id'],
                'disabled' => true
            ],
            'state' => ['disabled' => true],
            'type' => 'root',
            'children' => build($cats_row['Id'])
        ];
        $output[] = $categories;
    }

    echo json_encode($output, JSON_PRETTY_PRINT);
} else if ($_POST['action'] == 'new_menu') {
    $rand = substr(md5(microtime()),rand(0,26),5) . 'NewPage';
    $DBCon->query("INSERT INTO cms_menu (Title, Alias, Lang, Template_Id, Module_Id, IsVisible, Position)
VALUES ('$rand', '$rand', '$_POST[lang]', 1, 1, 0, '$_POST[parent]')");

}


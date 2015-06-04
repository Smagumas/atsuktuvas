<?php

function Load_Module(){
    global $DBCon;

    if(isset($_GET['id'])){
        $Id = $_GET['id'];
    }else{
        $Id = '';
    }

    if(isset($_POST['action']) && $_POST['action'] == 'edit_menu'){
        $visible = isset($_POST['visible'])?1:0;
        $robots = isset($_POST['robots'])?1:0;
        $text = $_POST['content'];
        $_POST['title'] = addslashes($_POST['title']);
        $text = addslashes($text);
        $text = str_replace("../uploads", "uploads", $text);
        $alias = aliasOf($_POST['title']);

        $DBCon->query("UPDATE cms_menu SET Title = '$_POST[title]', Alias = '$alias', IsVisible = '$visible',
        Content = '$text', Robots = '$robots', Meta_keywords = '$_POST[keywords]',
        Meta_description = '$_POST[description]', Module_Id = '$_POST[modules]', Template_Id = '$_POST[templates]'
        WHERE Id = '$Id '");
    }

    $menu_result = $DBCon->query("SELECT Id, Title, Alias, Link, Content, IsVisible, Robots, Meta_keywords, Meta_description, Module_Id, Template_Id
    FROM cms_menu WHERE Id='$Id'");
    $menu_row = $DBCon->fetch_assoc($menu_result);

    $module_result = $DBCon->query("SELECT Id, Title, Module FROM cms_modules WHERE IsActive='1' AND Show_In_List='1'");
    while($module_row = $DBCon->fetch_assoc($module_result)) {
        $module_rows[] = $module_row;
    }
    $template_result = $DBCon->query("SELECT Id, Title, Template FROM cms_templates WHERE IsActive='1'");
    while($template_row = $DBCon->fetch_assoc($template_result)) {
        $template_rows[] = $template_row;
    }

    $DBCon->Assign('menu_content', $menu_row);
    $DBCon->Assign('modules', $module_rows);
    $DBCon->Assign('templates', $template_rows);
    $DBCon->Assign('template', dirname( __FILE__ ). '/content.tpl');
}
<?php
function globals_set() {
    global $DBCon;
    $baseLink = '/' . $DBCon->url_arr[1];
    //Load specific news item
    if (isset($DBCon->url_arr[2]) && !is_numeric($DBCon->url_arr[2])) {
        $query = "SELECT Title_$DBCon->lang AS Title, Description_$DBCon->lang AS Content, Alias_$DBCon->lang AS Alias,
Date_Created FROM mod_news WHERE Alias_$DBCon->lang = '$DBCon->Alias'ORDER BY Date_Created";
        $row = $DBCon->query($query);
        $row = $DBCon->fetch_assoc($row);

        $DBCon->Assign('item', $row);
        $DBCon->Assign('template', dirname(__FILE__) . '/newsItem.tpl');
    } else { // Load news list
        $query = "SELECT Title_$DBCon->lang AS Title, Short_Desc_$DBCon->lang AS Short, Alias_$DBCon->lang AS Alias,
Date_Created FROM mod_news ORDER BY Date_Created " . getLimit(5);
        $result = $DBCon->query($query);
        $news = null;
        while ($row = $DBCon->fetch_assoc($result)) {
            $news[] = $row;
        }
        $DBCon->Assign('pages', do_pagination(getTableRowCount('mod_news'), 5, $baseLink));
        $DBCon->Assign('news', $news);
        $DBCon->Assign('template', dirname(__FILE__) . '/news.tpl');
    }
    $DBCon->Assign('baseLink', $baseLink);
}
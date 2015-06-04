<?php
/* @var Array - Left menu blocks goes into this array*/
$leftMenu = array();
/**
 * Initial function which will be called before rendering
 * use it to assing values for smarty and other calculations.
 */
function start_function(){
    global $DBCon;


}

/**
 * Renders main site menu, we are not doing this in smarty
 * because we call this function recursively
 */
function mainMenu($parentKey = 0, $linkChain = null, $depth = 0) {
    global $DBCon;
    $menu = null;
    foreach($DBCon->meniu_array as $key => $item) {
        if($parentKey <= 0 && $item['parent'] > 0)
            continue;
        if($parentKey > 0 && ($parentKey != $item['parent'] || $key == $parentKey))
            continue;
        $item['alias'] = empty($item['url']) ? $item['alias'] : $item['url'];
        if($parentKey == 0) {
            $linkChain = array($item['alias']);
        } else {
            $linkChain[] = $item['alias'];
        }
        $liClass = null;
        $childMenu = null;
        if($item['alias'] === $DBCon->url_arr[$depth+1]) {
            $liClass .= ' class="current"';
        }
        $childMenu = mainMenu($key, $linkChain, $depth+1);
        $menu .= "<li$liClass><a href='".$DBCon->abs(implode('/',$linkChain))."'>$item[name]</a><span></span>$childMenu</li>\n";
        if($parentKey != 0) {
            array_pop($linkChain);
        }
    }
    if($menu !== null )
        return "<ul>$menu</ul>";
    return null;
}

/**
 * @param $viso
 * @param $rows
 * @param $kelias
 * @return string
 * @deprecated
 */
function do_pagination($viso, $rows, $kelias) {
    global $DBCon;
    $tmp = '';
    $maxPage = $viso / $rows;
    $mmm = $viso / $rows;
    $pageNum = (int)$DBCon->url_arr[count($DBCon->url_arr) - 1];
    if (is_int($maxPage)) {
        $maxPage = (int)$mmm;
    } else {
        $maxPage = (int)$mmm + 1;
    }
    if ($pageNum >= $viso) {
    }
    if ($pageNum == '') {
        $pageNum = 1;
        $iki = 7;
        if ($maxPage <= $iki) {
            $iki = $maxPage;
        }
    } else {
        $imti = $pageNum;
        $iki = 7 + $imti;
        if ($iki >= $maxPage) {
            $iki = $maxPage;
        }
    }

    if ($maxPage <= 7) {
        $imti = 1;
        $iki = $maxPage;
    } elseif ($maxPage - $pageNum < 7) {
        $imti = $maxPage - 7;
        $iki = $maxPage;
    }

    $pref = $_SERVER['QUERY_STRING'];
    if ($pref != '') {
        $pref = '?' . $pref;
    }
    $tt1 = $kelias;
    $nav = null;
    for ($page = 1; $page <= $iki; $page++) {
        if ($page == $pageNum) {
            $nav .= " <a href=\"$tt1/$page$pref\" class='active'>$page</a>";
        } else {
            $nav .= " <a href=\"$tt1/$page$pref\">$page</a> ";
        }
    }

    if ($pageNum > 1) {
        $page = $pageNum - 1;
        $prev = <<<A
			<a href="$tt1/$page$pref" class="previous">Ankstesnis</a>
A;
    } else {
        $prev = '&nbsp;'; // we're on page one, don't print previous link
    }

    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = <<<A
			<a href="$tt1/$page$pref" class="next">Sekantis</a>
A;
    } else {
        $next = '&nbsp;'; // we're on the last page, don't print next link
    }
    if ($maxPage > 1) {
        $tmp = <<<A
		$prev $nav $next
A;
    }
    return $tmp;

}
function &getTableRowCount($table) {
    global $DBCon;
    $count = $DBCon->query("SELECT COUNT(*) FROM ".$table);
    $count = $DBCon->fetch_row($count);
    return $count[0];
}
function getLimit($rows) {
    global $DBCon;
    $pageNum = (int)$DBCon->url_arr[count($DBCon->url_arr) - 1];
    return "LIMIT ".($pageNum ? ($pageNum-1)*$rows : 0).",$rows";
}
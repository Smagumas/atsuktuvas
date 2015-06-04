<?php

class DataBaseConnection {
    public $db;
    public $connection;
    public $lang;
    public $Alias;
    public $ModuleAlias;
    public $prefix;
    public $FullLink;
    public $smarty;
    public $template;
    public $module;
    public $url_arr = array();
    public $Modules = array();
    public $url_id;
    public $chain;
    public $menu_array = array();
    public $grupes_id;
    public $include_text;

    function DataBaseConnection() {
        $this->db = 'mysql';
    }

    function connect($host, $user, $pass, $table) {
        if (class_exists('Smarty')) {
            $this->smarty = new Smarty;
        }

        $connectFunction = $this->db . '_connect';
        $dbSelectFuntion = $this->db . '_select_db';

        $this->connection = $connectFunction($host, $user, $pass);
        if (!$this->connection)
            die("Klaida: nepavyko prisijungti prie duomenų bazės.");
        $dbSelectFuntion($table);
        if ($this->db == 'mysql') {
            mysql_query("SET NAMES utf8 ");
        }
    }

    function FetchRows($sql) {
        $result = mysql_query($sql) or die ($sql);
        $tmp = array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $tmp[] = $row;
        }
        return $tmp;
    }

    function FetchRow($sql) {
        $result = mysql_query($sql) or die ($sql);
        return mysql_fetch_array($result, MYSQL_ASSOC);
    }

    function NumRows($sql) {
        $result = mysql_num_rows($sql) or die ($sql);
        return $result;
    }

    function query($sql) {
        $function = $this->db . '_query';
        $result = @$function($sql);
        $sql_log = null;
        if (!$result) {
            if ($this->db == 'mysql') {
                $sql_log = mysql_error($this->connection);
            }
            $today = date("Y-m-d H:i:s");

            ob_start();
            print_r($_SESSION);
            $sesija = ob_get_contents();
            ob_clean();

            $mesage = <<<A
                Klaida: $today
                $sql
                $sql_log
                IP: $_SERVER[REMOTE_ADDR]
                adresas: $_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]
                sesija: $sesija
                ---
A;
            @file_put_contents('../static/logs/sql_error_log.txt', $mesage, FILE_APPEND | LOCK_EX);
            die("Klaida: klaida jūsų SQL užklausos sintaksėje<br />\n" . $sql);
        }
        return $result;
    }

    function num_rows(&$result) {
        $function = $this->db . '_num_rows';
        return $function($result);
    }

    function free_result(&$result) {
        $function = $this->db . '_free_result';
        $function($result);
        unset($result);
    }

    function insert_id() {
        return mysql_insert_id();
    }

    function fetch_row(&$result) {
        $function = $this->db . '_fetch_row';
        return $function($result);
    }

    function fetch_array(&$result) {
        $function = $this->db . '_fetch_array';
        return $function($result);
    }

    function fetch_assoc(&$result) {
        $function = $this->db . '_fetch_assoc';
        return $function($result);
    }

    function fetch_object(&$result) {
        $function = $this->db . '_fetch_object';
        return $function($result);
    }

    function close() {
        $closeFunction = $this->db . '_close';
        $closeFunction($this->connection);
    }
    function languageChooser() {
        global $DBCon;
        $query = "SELECT Short_Title, Title FROM cms_languages ORDER BY Title";
        $rows = $DBCon->query($query);
        $content = null;
        while($row = $DBCon->fetch_assoc($rows)) {
            if($row['Short_Title'] === $DBCon->lang) {
                $row['Title'] = '<b>'.$row['Title'].'</b>';
            }
            $alternate = !empty($DBCon->chain[$row['Short_Title']]) ? '/'.$DBCon->chain[$row['Short_Title']] : '';
            $content[] = '<a href="/'.$row['Short_Title'].$alternate.'">'.$row['Title'].'</a>';
        }

        //return implode('|',$content);
        $this->smarty->assign('languages', implode('|',$content));
    }

    function SetTransalteVars($lang = null) {

        if (empty($lang)) {
            $lang = $this->lang;
        }

        $this->Assign('lang', $lang);
        $rows = $this->FetchRows("SELECT ValueString, KeyString FROM cms_translates WHERE Lang = '$lang'");

        if (!$rows) {
            return;
        }
        foreach ($rows as $value) {

            $this->Assign('tr_' . $value['KeyString'], $value['ValueString']);
            $GLOBALS['tr_' . $value['KeyString']] = $value['ValueString'];
        }
    }

    /**
     * Loads all site menus.
     */
    function SetMeniu() {
        $menu_array = array();
        $rows = $this->FetchRows("SELECT Id, Parent, Title, Alias, Link, IsVisible, Content, Position, Module_Id FROM cms_menu WHERE
Lang='$this->lang' ORDER BY  PositionOrder");
        if (!$rows) {
            return;
        }
        foreach ($rows as $row) {
            $menu_array[$row['Id']] = array('name' => $row['Title'], 'parent' => $row['Parent'],
                'alias' => $row['Alias'], 'show' => $row['IsVisible'], 'url' => $row['Link'],
                'tekstas' => $row['Content'], 'position' => $row['Position'], 'module_id' => $row['Module_Id']);
        }
        unset($menu_array['tekstas']);
        $this->menu_array = $menu_array;
        $this->smarty->assign('bottom_menu', $this->generate_menu(2, 0, '', '', 0, $menu_array));
        $this->smarty->assign('top_menu', $this->generate_menu(1, 0, '', '', 0, $menu_array));
        //$this->smarty->assign('main_menu', $this->generate_menu(0, 0, '', '', 0, $menu_array));
    }

    /**
     * Recursively generates all menus.
     * @param $parent
     * @param $parent_alias
     * @param $link
     * @param $dir_count
     * @param $menu_array
     * @return null|string
     */
    function generate_menu($pos, $parent, $parent_alias, $link, $dir_count, $menu_array) {
        $dir_count++;
        $tmp = null;
        $has_childs = false;
        $linkChain = null;
        $class = '';
        foreach ($menu_array as $key => $value) {
            if ($value['parent'] == $parent && $value['position'] == $pos) {
                if ($has_childs === false && $value['show'] == 1) {
                    $has_childs = true;
                    $tmp .= '<ul id="menu">';
                }

                if($value['parent'] == 0) {
                    $linkChain = array($value['alias']);
                } else {
                    $linkChain[] = $value['alias'];
                }

                if ($value['show'] == 1) {
                    $alias = $value['alias'];
                    if ($this->Alias == $value['alias']) {
                        $class = ' class="active"';
                    }

                    if (isset($value['url'])) {
                        $alias = $value['url'];
                    }
                    $tmp .= "<li $class><a href='".$this->abs(implode('/',
                            $linkChain))."' title='$value[name]'>$value[name]</a>";

                    $temp_meniu = $this->generate_menu($pos, $key, $value['alias'], $link . '/' . $value['alias'],
                        $dir_count, $menu_array);
                    $tmp .= $temp_meniu;
                    $tmp .= '</li>';
                }
            }

        }
        if ($has_childs === true) {
            $tmp .= '</ul>';
        }
        return $tmp;
    }

    function valid_utf8_bytes($str) {
        $return = '';
        $length = strlen($str);
        $invalid = array_flip(array("\xEF\xBF\xBF" /* U-FFFF */, "\xEF\xBF\xBE" /* U-FFFE */));

        for ($i = 0; $i < $length; $i++) {
            $c = ord($str[$o = $i]);

            if ($c < 0x80)
                $n = 0; # 0bbbbbbb
            elseif (($c & 0xE0) === 0xC0)
                $n = 1; # 110bbbbb
            elseif (($c & 0xF0) === 0xE0)
                $n = 2; # 1110bbbb
            elseif (($c & 0xF8) === 0xF0)
                $n = 3; # 11110bbb
            elseif (($c & 0xFC) === 0xF8)
                $n = 4; # 111110bb
            else continue; # Does not match

            for ($j = ++$n; --$j;) # n bytes matching 10bbbbbb follow ?
                if ((++$i === $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                    continue 2;

            $match = substr($str, $o, $n);

            if ($n === 3 && isset($invalid[$match])) # test invalid sequences
                continue;

            $return .= $match;
        }
        //die($return);
        return $return;
    }

    function SetLangAndAlias() {
        $url1 = explode('?', strtolower($_SERVER['REQUEST_URI']));
        $url = explode('/', $url1[0]);

        //-------------------kalbu uzsetinimas----------------
        if (in_array(strtolower($url[1]), $GLOBALS['kalbos'])) {
            $this->lang = strtolower($url[1]);
            unset($url[1]);
        } else {
            $this->lang = strtolower($GLOBALS['main_lang']);

        }
        if (strtolower($this->lang) == strtolower($GLOBALS['main_lang'])) {
            $this->prefix = '';
        } else {
            $this->prefix = $this->lang;
        }
        //---------------- end kalbu uzsetinimas--------------
        $url = array_values($url);
        if (!isset($url[1]) || $url[1] == '') {
            $url[1] = $this->first_alias();
        }
        $this->url_arr = $url;
        $this->FullLink = $url1[0]; //yrašom full linką reiks vėliau
        $this->Alias = $url[count($url) - 1];
        //jei linko nera arba jis tuscias reikis pirmas puslapis reikia gauti jo aliasą
        if ($this->FullLink == '/') {
            $query = "SELECT Alias, Title FROM cms_menu WHERE (Lang = '$this->lang') AND (Parent = 0) ORDER BY PositionOrder LIMIT 1";
            $arr = $this->FetchRow($query);
            $this->FullLink = $arr['Alias'];

        }
        $query = "SELECT * FROM cms_modules";
        $result = $this->query($query);
        while($module = $this->fetch_assoc($result)) {
            $this->Modules[$module['Module']] = $module;
        }
    }

    public function first_alias() {
        $query = "SELECT Alias FROM  cms_menu WHERE (Lang = '$this->lang') AND (Parent = 0) ORDER BY PositionOrder LIMIT 1";
        $row = $this->FetchRow($query);
        return $row['Alias'];
    }

    public function getMenuByModule($module) {
        if(!isset($this->Modules[$module]))
            throw new Exception('Given module does not exists.');
        $module = $this->Modules[$module]['id'];
        foreach($this->menu_array as $key => $value) {
            if($value['module_id'] == $module)
                return $value;
        }
    }

    function SetContents() {
        global $DBCon;
        $arr = array_reverse($this->url_arr);
        //printdie($arr);
        foreach ($arr as $val) {
            $query = <<<A
            SELECT cms_menu.Id, cms_menu.Title, Alias, Link, Content, cms_menu.Lang,
            Template_Id, Module_Id, cms_modules.Module, cms_templates.Template

    FROM cms_menu
    INNER JOIN cms_modules ON cms_modules.Id=Module_Id
    INNER JOIN cms_templates ON cms_templates.Id=Template_Id
    WHERE cms_menu.Lang = '$this->lang'
    AND Alias = '$val'

A;
            $row = $this->FetchRow($query);

            if ($row['Title'] != '') {
                $this->ModuleAlias = $val;
                $this->smarty->assign('h1', $row['Title']);

                $row['Content'] = str_replace('../static/', '/static/', $row['Content']);

                $DBCon->include_text = $row['Content'];

                $this->smarty->assign('Content', $row['Content']);

                $this->smarty->assign('page_title', $row['Title']);
                if ($this->url_arr[1] != '' and $this->url_arr[1] != 'titulinis') {
                    $this->smarty->assign('is_first_page', '0');
                } else {
                    $this->smarty->assign('is_first_page', '1');
                }

                $this->url_id = $row['Id'];

                $this->module = $row['Module'];
                $this->smarty->assign('module', $row['Module']);
                $this->template = $row['Template'];
                //$this->smarty->assign('template', 'text.tpl');
                //$this->smarty->assign('module', $row['module']);
                if ($row['Module'] != 'main') {
                    include('functions/pages/' . $row['Module'] . '/functions.php');
                    globals_set();
                }
                break;
            }
        }

        if (!isset($this->smarty->tpl_vars['h1'])) {
            $_SESSION['redirect_cycle'] += 1;
            if ($_SESSION['redirect_cycle'] > 5) {
                unset($_SESSION['redirect_cycle']);
                die('Infinite redirect cycle to ' . $GLOBALS['base'] . '?404');
            }
            header('location:' . $GLOBALS['base'] . '?404');
        }
    }

    function DisplayPage() {
        $this->smarty->display('templates/' . $this->template . '.tpl');
    }

    function Display($val) {
        $this->smarty->display('templates/' . $val . '.tpl');
    }

    function Assign($key, $val) {
        $this->smarty->assign($key, $val);
    }

    function AssignRef($key, &$val) {
        $this->smarty->assign($key, $val);
    }

    function JsArray() {
        $temp = array();
        $compressed_file_contents = null;
        $url = substr($GLOBALS['base'], 0, -1);
        if ($GLOBALS['compres_css_js'] == 1) {
            foreach ($GLOBALS['js_arr'] as $js) {
                $compressed_file_contents .= file_get_contents("$url/static/js/$js");
            }
            require_once 'additional/jsmin.php';
            $compressed_file_contents = JSMin::minify($compressed_file_contents);

            file_put_contents($_SERVER['DOCUMENT_ROOT'] . 'static/js/' . $GLOBALS['compres_css_js_filename'] . '.js', $compressed_file_contents);
            $temp[] = "$GLOBALS[compres_css_js_filename].js";
        } else if ($GLOBALS['compres_css_js'] == 2) {
            $temp[] = "$GLOBALS[compres_css_js_filename].js";
        } else {
            return $GLOBALS['js_arr'];
        }

        return $temp;
    }

    function CssArray() {
        $temp = array();
        $compresed_file_contents = null;
        if ($GLOBALS['compres_css_js'] == 1) {
            foreach ($GLOBALS['css_arr'] as $js) {
                $compresed_file_contents .= file_get_contents("/static/css/$js");
            }
            $compresed_file_contents = preg_replace('/\s+/', ' ', $compresed_file_contents);
            $compresed_file_contents = preg_replace('/\/\*.*?\*\//', '', $compresed_file_contents);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . 'static/css/' . $GLOBALS['compres_css_js_filename'] . '.css', $compresed_file_contents);
            $temp[] = $GLOBALS['compres_css_js_filename'] . '.css';
        } else if ($GLOBALS['compres_css_js'] == 2) {
            $temp[] = $GLOBALS['compres_css_js_filename'] . '.css';
        } else {
            return $GLOBALS['css_arr'];
        }
        return $temp;
    }

    public function aliasOf($text) {

        $text = strtolower($text);
        //die($text);
        $from = array('Ё', 'Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ', 'Ф', 'Ы', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю');
        $to = array('ё', 'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю');
        $text = str_replace($from, $to, $text);
        $from = array('ą', 'č', 'ę', 'ė', 'į', 'š', 'ų', 'ū', 'ž', ' ', 'Ą', 'Č', 'Ę', 'Ė', 'Į', 'Š', 'Ų', 'Ū', 'Ž');
        $to = array('a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z', '-', 'a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z');
        $text = str_replace($from, $to, $text);
        $from = array('ё', 'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю');
        $to = array('io', 'i', 'c', 'u', 'k', 'e', 'n', 'g', 's', 's', 'z', 'ch', '', 'f', 'i', 'v', 'a', 'p', 'r', 'o', 'l', 'd', 'z', 'e', 'ja', 'c', 's', 'm', 'i', 't', '', 'b', 'ju');
        $text = str_replace($from, $to, $text);
        $from = array(':', ';', '.', ',');
        $to = array('-', '-', '-', '-');
        $text = str_replace($from, $to, $text);
        $text = ereg_replace("[^A-Za-z0-9-]", "-", $text);
        $text = ereg_replace("--", "", $text);

        if (EndsWith1($text, "-")) {
            $text = substr($text, 0, -1);
        }
        return $text;
    }

    public function abs($link) {
        $link = ltrim($link, '/');
        if (!empty($this->prefix))
            $link = $this->prefix . '/' . $link;
        return '/' . $link;
    }
}
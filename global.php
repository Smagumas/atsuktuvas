<?php
function aliasof($string) {
    $transliterationtable = array(
        'á' => 'a', 'à' => 'a','ă' => 'a','â' => 'a',  'å' => 'a',  'ã' => 'a', 'ą' => 'a','ā' => 'a',
        'Ą'=> 'a','Á'=> 'a','Ă'=> 'a','Â'=> 'a','À'=> 'a','Ã'=> 'a','Å'=> 'a','Ā'=> 'a',
        'ä' => 'ae', 'Ä' => 'ae', 'æ' => 'ae', 'Æ' => 'ae',
        'Ḃ'=> 'b','б' => 'b', 'Б' => 'b','ḃ' => 'b',
        'ć' => 'c' ,'ĉ' => 'c','č' => 'c','ċ' => 'c','ç' => 'c','ц' => 'c',
        'Ć'=> 'c',  'Ĉ'=> 'c',  'Č'=> 'c',  'Ċ'=> 'c', 'Ç'=> 'c', 'Ц'=> 'c',
        'ď' => 'd', 'Ď'=> 'd', 'ḋ' => 'd', 'Ḋ'=> 'd', 'đ' => 'd', 'Đ'=> 'd', 'ð' => 'dh',
        'Ð' => 'dh', 'é' => 'e', 'É'=> 'e', 'è' => 'e', 'È'=> 'e', 'ĕ' => 'e', 'Ĕ'=> 'e', 'ê' => 'e', 'Ê'=> 'e',
        'ě' => 'e', 'Ě'=> 'e', 'ë' => 'e', 'Ë'=> 'e', 'ė' => 'e', 'Ė'=> 'e', 'ę' => 'e', 'Ę'=> 'e', 'ē' => 'e',
        'Ē'=> 'e', 'ḟ' => 'f', 'Ḟ'=> 'f', 'ƒ' => 'f', 'Ƒ'=> 'f', 'ğ' => 'g', 'Ğ'=> 'g', 'ĝ' => 'g', 'Ĝ'=> 'g',
        'ġ' => 'g', 'Ġ'=> 'g', 'ģ' => 'g', 'Ģ'=> 'g', 'ĥ' => 'h', 'Ĥ'=> 'h', 'ħ' => 'h', 'Ħ'=> 'h', 'í' => 'i',
        'Í'=> 'i', 'ì' => 'i', 'Ì'=> 'i', 'î' => 'i', 'Î'=> 'i', 'ï' => 'i', 'Ï'=> 'i', 'ĩ' => 'i', 'Ĩ'=> 'i',
        'į' => 'i', 'Į'=> 'i', 'ī' => 'i', 'Ī'=> 'i', 'ĵ' => 'j', 'Ĵ'=> 'j', 'ķ' => 'k', 'Ķ'=> 'k', 'ĺ' => 'l',
        'Ĺ' => 'l', 'ľ' => 'l', 'Ľ' => 'l', 'ļ' => 'l', 'Ļ' => 'l', 'ł' => 'l', 'Ł' => 'l', 'ṁ' => 'm', 'Ṁ' => 'm',
        'ń' => 'n', 'Ń' => 'n', 'ň' => 'n', 'Ň' => 'n', 'ñ' => 'n', 'Ñ' => 'n', 'ņ' => 'n', 'Ņ' => 'n', 'ó' => 'o',
        'Ó' => 'o', 'ò' => 'o', 'Ò' => 'o', 'ô' => 'o', 'Ô' => 'o', 'ő' => 'o', 'Ő' => 'o', 'õ' => 'o', 'Õ' => 'o',
        'ø' => 'oe', 'Ø' => 'oe', 'ō' => 'o', 'Ō' => 'o', 'ơ' => 'o', 'Ơ' => 'o', 'ö' => 'oe', 'Ö' => 'oe',
        'ṗ' => 'p', 'Ṗ' => 'p', 'ŕ' => 'r', 'Ŕ' => 'r', 'ř' => 'r', 'Ř' => 'r', 'ŗ' => 'r', 'Ŗ' => 'r', 'ś' => 's',
        'Ś' => 's', 'ŝ' => 's', 'Ŝ' => 's', 'š' => 's', 'Š' => 's', 'ṡ' => 's', 'Ṡ' => 's', 'ş' => 's', 'Ş' => 's',
        'ș' => 's', 'Ș' => 's', 'ß' => 'ss', 'ť' => 't', 'Ť' => 't', 'ṫ' => 't', 'Ṫ' => 't', 'ţ' => 't', 'Ţ' => 't',
        'ț' => 't', 'Ț' => 't', 'ŧ' => 't', 'Ŧ' => 't', 'ú' => 'u', 'Ú' => 'u', 'ù' => 'u', 'Ù' => 'u', 'ŭ' => 'u',
        'Ŭ' => 'u', 'û' => 'u', 'Û' => 'u', 'ů' => 'u', 'Ů' => 'u', 'ű' => 'u', 'Ű' => 'u', 'ũ' => 'u', 'Ũ' => 'u',
        'ų' => 'u', 'Ų' => 'u', 'ū' => 'u', 'Ū' => 'u', 'ư' => 'u', 'Ư' => 'u', 'ü' => 'ue', 'Ü' => 'ue',
        'ẃ' => 'w', 'Ẃ' => 'w', 'ẁ' => 'w', 'Ẁ' => 'w', 'ŵ' => 'w', 'Ŵ' => 'w', 'ẅ' => 'w', 'Ẅ' => 'w', 'ý' => 'y',
        'Ý' => 'y', 'ỳ' => 'y', 'Ỳ' => 'y', 'ŷ' => 'y', 'Ŷ' => 'y', 'ÿ' => 'y', 'Ÿ' => 'y', 'ź' => 'z', 'Ź' => 'z',
        'ž' => 'z', 'Ž' => 'z', 'ż' => 'z', 'Ż' => 'z', 'þ' => 'th', 'Þ' => 'th', 'µ' => 'u', 'а' => 'a',
        'А' => 'a',  'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd',
        'е' => 'e', 'Е' => 'e', 'ё' => 'e', 'Ё' => 'e', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z',
        'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm',
        'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r',
        'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h',
        'Х' => 'h',  'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch',
        'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e',
        'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja');
    $string = str_replace(array_keys($transliterationtable), array_values($transliterationtable), $string);
    $string = preg_replace("/[\/_|+ -]+/", '-', $string);
    $string  = preg_replace("/[^a-zA-Z0-9]+/", "-", $string);
    $from =array(':',';','.',',');
    $to = array('-','-','-','-');
    $string = str_replace($from,$to,$string);
    $string  = preg_replace("--","",$string);
    if (endswith1($string,"-")){
        $string=substr($string,0,-1);
    }
    $string = strtolower($string);
    return $string;
}

function endswith1($Haystack, $Needle){
    return strrpos($Haystack, $Needle) === strlen($Haystack)-strlen($Needle);
}

function formatsize($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function println($string) {
    echo '<pre>'.print_r($string, true).'</pre>';
}
function printdie($obj) {
    die(print_r($obj, true));
}

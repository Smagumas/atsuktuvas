<?
function EndsWith1($Haystack, $Needle){
	// Recommended version, using strpos
	return strrpos($Haystack, $Needle) === strlen($Haystack)-strlen($Needle);
}

function get_alias($text) {
  
  $text=strtolower($text);
  $from = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
  $to = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
  $text = str_replace($from,$to,$text); 
  $from = array('ą','č','ę','ė','į','š','ų','ū','ž',' ', 'Ą', 'Č','Ę', 'Ė', 'Į', 'Š', 'Ų', 'Ū', 'Ž');
  $to = array('a','c','e','e','i','s','u','u','z','-', 'a','c','e','e','i','s','u','u','z');
  $text = str_replace($from,$to,$text); 
  $from = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в','а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
  $to = array('io','i','c','u','k','e','n','g','s','s','z','ch','','f','i','v','a','p','r','o','l','d','z','e','ja','c','s','m','i','t','','b','ju');
  $text = str_replace($from,$to,$text);
    $text  = preg_replace("/[^a-zA-Z0-9]+/", "-", $text);
    $from =array(':',';','.',',');
    $to = array('-','-','-','-');
    $text = str_replace($from,$to,$text);
    $text  = preg_replace("--","",$text);
  
  if (EndsWith1($text,"-")){
  $text=substr($text,0,-1);
  }
return $text;
}
?>
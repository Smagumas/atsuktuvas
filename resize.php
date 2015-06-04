<?php
/*
resize rezimai

x - mazina proporcingai paduotom dimensijom, bet neforsina (tinkamiausia kai reikia isdidinti foto pvz per fancybox)
xf - apkerpa foto butent tokio dydzio kokie paduoti parametrai
xn - kaip ir x tik nevirsija krastines paduotu reiksmiu
xv - sumazina foto butent iki tokiu parametru koki reik, jei neatitinka proporcijos uzdeda transparenta is sonu, tokiu atveju niekada nenusikirps foto, pvz ekspeertuose niekada nenukerpa galvu sita funkcija



*/

//$_SERVER['DOCUMENT_ROOT'] = "/www/sukikaima/sukikaima.lt/ ";
include('admin/class.imageris.php');
$_SERVER['REQUEST_URI'] = str_replace('//', '/', $_SERVER['REQUEST_URI']);



$temp = explode('/', $_SERVER['REQUEST_URI']);
$temp = explode('x', $temp[4]);

$s_x=$temp[0];
$s_y=$temp[1];
$resizeType = substr($s_y, 0,1);

if($resizeType == 'f') {
    $s_y=substr($s_y, 1, strlen($s_y));
    $plotis=$s_x;
    $aukstis=$s_y;
    $forcintas=1;
} if($resizeType == 'n'){
    $proporciju_nevirsyti=1;
    $s_y=substr($s_y, 1, strlen($s_y));
} if($resizeType == 'v'){
    $nekirpti=1;
    $s_y=substr($s_y, 1, strlen($s_y));
}

$mano=explode('x', $_SERVER['REQUEST_URI']);
$pirmo_ilgis=strlen($mano[0]); //ziuriu kox ilgis pirmos dalies
$antra_dalis_arr=explode('/', $mano[1]);
$laikinas_arr=$antra_dalis_arr[0];
$laikino_ilgis=strlen($laikinas_arr);
$tikra_pozicija=$pirmo_ilgis+$laikino_ilgis+1;


//$pos = strrpos($_SERVER['REQUEST_URI'], '/');
$pos=$tikra_pozicija;

$img1=$_SERVER['DOCUMENT_ROOT']. '/static/uploads/'. substr($_SERVER['REQUEST_URI'], $pos+1, strlen($_SERVER['REQUEST_URI']));
$img1=urldecode($img1);
$img1=str_replace('%20', ' ', $img1);
$img1=str_replace('%5B','[',$img1);
$img1=str_replace('%5D',']',$img1);
$img1=str_replace('%C3%A1','á',$img1);
$img1=str_replace('%C3%A9','é',$img1);
//$img1=str_replace('Å¡','š',$img1);
//%C3%A1
//die($img1);
//print_r($_SERVER);
//die();
//if(isMe()) {
//error_reporting(E_ALL);
$image = pathinfo($img1);
if($image['extension'] == "gif" && !file_exists($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])) {
    include("functions/gdenhancer/GDEnhancer.php");
    $gd = new GDEnhancer($img1);
    $option = "shrink";
    if($resizeType == "f") {
        $option = "fill";
    } else if($resizeType == "n"){
        $option = "stretch";
    }
    //die("REsize to: $s_x, $s_y");
    $gd->backgroundResize($s_x, $s_y, $option);
    $data = $gd->save();

    $path = str_replace("/resize.php","", $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
    $dirname = pathinfo($path);
    $dirname = $dirname['dirname'];
    mkdir($dirname,0777,true);
    //die(print_r(pathinfo($path),true));
    file_put_contents($path,$data['contents']);
    header('content-type:' . $data['mime']);
    die($data['contents']);
}
//}
if (!file_exists($img1)) {
    //$img1=str_replace('jpg', 'JPG', $img1);
    if (!file_exists($img1)) {
        $img1=str_replace('/static/uploads/', '/media/', $img1);
        $img1=lt_to_her($img1);
        if(!file_exists($img1)) {
            $img1=str_replace('jpg', 'JPG', $img1);
            if(!file_exists($img1)) {
                $img1=$_SERVER['DOCUMENT_ROOT']. '/static/img/nophoto.png';
            }
        }
    }
}

if (!file_exists($img1)) {
    if (!file_exists($img1)) {
        $img1=$_SERVER['DOCUMENT_ROOT']. '/static/img/nophoto.png';
    }
}

$img = new imageris(); //sukuriam objekt

$img->image_set($img1);
$w=''. $img->image_get_width();
$h=''.$img->image_get_height();

//is pradziu darome kropa resaizinimo proporcijomis
if (($w/$s_x)>($h/$s_y)){
    $ratio=$w/$s_x;
}
else{
    $ratio=$h/$s_y;
}
//die($ratio);
//$s_x=$s_x*$ratio;
if ($forcintas==1) {
    $w=''. $img->image_get_width();
    $h=''.$img->image_get_height();
    $img->image_crop((int)($plotis),(int)($aukstis),false);
    //$img->image_resize($s_x,$s_y,3); //forcinme dydi bet turetu buti padidinamas siek tiek daugiau negau ismatavimai, nes po to bus
}
else if ($proporciju_nevirsyti==1){
    $w=''. $img->image_get_width();
    $h=''.$img->image_get_height();
    //matematika reikia iskaiciuoti taip kad nei vienas dydis neviryt :)
    //ziurime kur proporcija didesne
    if (($w/$s_x)>($h/$s_y)){
        $ratio=$w/$s_x;
        $s_y=$h/$ratio;
    }
    else{
        $ratio=$h/$s_y;
        $s_x=$w/$ratio;
    }
    $img->image_resize($s_x,$s_y,2);
    //die($s_x. 'a-'. $s_y);
}

elseif($nekirpti==1) {
    $img3 = new imageris(); //sukuriam objekt
    $img3->image_set($img1);
    $width=''. $img3->image_get_width();
    $height=''.$img3->image_get_height();
    $s_x1=$s_x; //nauji kintamieji, kad neperasyt senu
    $s_y1=$s_y;
    //pasiskaiciuojam pagal proporcijas nauja auksti ir ploti
    if (($width/$s_x)>($height/$s_y)){
        $ratio=$width/$s_x;
        $s_y1=$height/$ratio;
    }
    else{
        $ratio=$height/$s_y;
        $s_x1=$width/$ratio;
    }
    $img3->image_resize($s_x1,$s_y1,2);
    $savint=str_replace('/uploads/', '/uploads/laikini/', $img1);
    $img3->image_set_output_type("png");
    $img3->image_save($savint);
    unset($img3);
    //$atitraukti_x=($s_x-$s_x1)/2; //paskaiciuojam kiek atritraukti nuo desines sumazinta paveikslli
    //$atitraukti_y=($s_y-$s_y1)/2; //paskaiciuojam kiek atritraukti nuo desines sumazinta paveikslli
    $img = new imageris(); //sukuriam objekt
    $img->image_set($_SERVER['DOCUMENT_ROOT'].'/static/img/transparent.png');
    $img->image_resize($s_x,$s_y,2);
    $img->image_put_image($savint,"center","top");
    unlink($savint);
}
else {
    $img->image_resize($s_x,$s_y,2);
}

/*
if($s_x>400 and $s_y>500) {
$img->image_put_image('static/uploads/water.png','right','bottom');
}
*/
$temp = explode('/', $_SERVER['REQUEST_URI']);
$temp = array_values($temp);
//print_r($temp);
for ($i=0;$i<count($temp)-1; $i++) {
    $dir_tree.='/' . $temp[$i];
    if (!is_dir($_SERVER['DOCUMENT_ROOT'].$dir_tree)){
        mkdir($_SERVER['DOCUMENT_ROOT'].$dir_tree);
        //echo $_SERVER['DOCUMENT_ROOT'].$dir_tree;
    }
}

$img->image_save($_SERVER['DOCUMENT_ROOT']. $_SERVER['REQUEST_URI']);
//echo @file_get_contents($_SERVER['DOCUMENT_ROOT']. $_SERVER['REQUEST_URI']);
$img->image_display();



function remoteFileExists($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    $result = curl_exec($curl);
    $ret = false;
    if ($result) {
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($statusCode != 404) {
            $ret = true;
        }
    }
    curl_close($curl);
    return $ret;
}


function image_get_type1 ($filename,$is_str=false) {
    if(!$is_str) {
        if (!is_readable($filename))
            return false;
        $f=fopen($filename,"r");
        $header=fread($f,3);
        fclose($f);
    }
    else {
        $header=substr($filename,0,3);
    }
    if ($header=="\xFF\xD8\xFF")
        return "jpeg";
    elseif ($header=="\x89\x50\x4e")
        return "png";
    elseif ($header=="\x47\x49\x46")
        return "gif";
    elseif ($header=="\x49\x49\x2A")
        return "tiff";
    elseif (substr($header,0,2)=="\x42\x4D")
        return "bmp";
    else
        return false;
}

function vertikali($image){
    $str=false;
    $source="";
    if (!is_readable($image)) {
        return false;
    }
    else {
        $str=true;
        $source=$image;
    }
    $ext=image_get_type1($source,$false);
    if ($ext == "jpeg" or $ext=='jpg') {
        $img = ImageCreateFromJPEG($image);
    }
    elseif ($ext == "gif") {
        $img = ImageCreateFromGIF($image);
    }
    elseif ($ext == "png") {
        $img = ImageCreateFromPNG($image);
    }
    elseif ($ext == "wbmp") {
        $img = imagecreatefromwbmp($image);
    }


    $img2 = imagecreatetruecolor($box['w'], $box['h']);
    imagecopy($img2, $img, 0, 0, $box['l'], $box['t'], $box['w'], $box['h']);
    $savint=str_replace('/uploads/', '/laikini/', $image);

    $aa=explode($_SERVER['DOCUMENT_ROOT'], $savint);
    $temp = explode('/', $aa[1]);
    $temp = array_values($temp);
    for ($i=0;$i<count($temp)-1; $i++) {
        $dir_tree.='/' . $temp[$i];
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].$dir_tree)){
            mkdir($_SERVER['DOCUMENT_ROOT'].$dir_tree);
        }
    }

    imagepng($img2, $savint);
    return $savint;
}


function lt_to_her($title) {
    $from=array('ą','š','Ė','ž','į','ę','ų','Į','ū','č','Ž','Č','ė');
    $to=array('Ä…','Å¡','Ä—','Å¾','Ä¯','Ä™','Å³','Ä®','Å«','Ä','Å½','Ć„Å’','Ć„ā€”');
    $title = str_replace($from,$to,$title);
    return $title;
}
?>
<?php
// NOTICE! This file is in UTF-8 encoding

// author: Marius Vitkevičius aka Dissimilis <marius.vitkevicius@mif.vu.lt> 
// date:   2006.02.09
// info:   image class

/*
Dėmesio: klasė nėra visiškai transparent safe, tai reiškia, kad kai kurie metodai gali panaikinti png/gif failų permatomumą.
Pagrindiniai metodai (crop, resize, put_image ir text) yra transparent safe.


Pagrindinių metodų naudojimo pavyzdžai:

$img =& new imageris(); //sukuriam objektą

$img->image_set("failas.jpg"); //grąžina false, jei nepavyko atidaryti. Jei gif failas bus su jpg extention'u, atpažins kaip gif'ą

$img->image_get_width(); //gražina nuotraukos plotį
$img->image_get_height(); //gražina nuotraukos aukštį

$img->image_set_output_type("png"); //apdirbtos foto (rezultato) formatas (galimi: png, gif ir jpeg).

$img->image_set_output_quality(72); //nustato kokia kokybe bus pateiktas rezultatas (tinkas tik išvedant jpeg).

$img->image_display(); //išveda paveikslėlį į naršyklę (prieš tai negali būti niekas išvesta).

$img->image_save("failas"); //išsaugo paveikslėlį.

$img->image_crop(200,294,false); //apkarpo (ne resizina) foto. Trečias parametras padeda kai nuotraukoje yra žmogaus veidas.
Kai trečias parametras true - keletą kartų sumažėja greitis, jokiu būdu nenaudoti on-the-fly nuotraukų crop'inimui.
Tačiau patartina nurodyti jei karpoma žmogaus nuotrauka, taip rečiau nukerpamas veidas.

$img->image_resize(200,294); //proporcingai pakeičia nuotraukos dydį.
Trečias parametras nurodo resizinimo būdą: griežtas, proporcingas, nedidint mažų foto.
Pvz.: $img->image_resize(200,294,3); nepakeis nuotraukos, kurios dydis 100x50, tačiau proporcingai sumažins 1024x768 dydžio foto.
By default nustatytas antras (proporcingai) būdas.

$img->image_unsharpmask(70,0.5,3); //metodas paryškinantis neryškias foto. Labai labai tinka darant thumnail'us.
//parametrų nurodyti nebūtina, default'iniai veiks puikiai.

$img->image_blur(1); //paveikslėlių blurinimas. Nenaudoti didesnių parametrų nei 2!

$img->image_greyscale() //padaro nuotrauką nespalvotą.

$img->image_put_image('kitas.png',"right","bottom"); //uždeda kitą paveiksliuką ant užsetinto.
Galima dėti permatomus png'us.
Kordinatės gali būti nurodomos žodžiais (X: center, left, right; Y: bottom, top, middle) arba paprastom koordinatėm (skaičiais)

$img->image_rouded_border(7); //uždeda gražų apvalėjantį permatomą rėmelį (dar reiks patobulinti).
Pirmas parametras nurodo rėmelio storį pikseliais.
Antras parametras nurodo į kokią spalvą (hex formatu) pereiti. Nereikalingas išvedant png'ą.
Pvz.: $img->image_rouded_border(7,'FFFFFF'); rėmelis pereis į baltą spalvą.
Dėmesio: png permatomumas matomas tik ant normalių naršyklių, IE png permatomumo nepalaiko! 
ddd
$img->image_text(string text, mixed X, mixed Y, string text_color [,string bg_color,int font_size,string ttf_font_file,string shadow_color,int shadow_radius]); //užrašo tekstą ant nuotraukos
Pvz.: $img->image_text("Myliu mamą","center","middle","FF0000","",12,"Arial.ttf","FFFFFF",1); arba $img->image_text("Myliu tėtę",10,10,"FF0000");
Koordinatės nurodomos kaip ir image_put_image metode.
Nenurodžius fono spalvos fonas bus permatomas. Nenurodžius šrifto bylos bus naudojamas integruotas PHP šriftas.

*/


class imageris {
    var $include_dir="";
    var $input_file = "";
    var $output_file = "";
    var $image;
    var $filename="";
    var $height=0;
    var $width=0;
    var $quality=100;
    var $type="";
    var $output_type="jpeg";
    var $config=array(
        "sensitivity" => 0.172,
        "min_sviesumas"=>152,
        "max_sviesumas"=>702,
        "min_red" => 142,
        "max_r/g"=>1.52,
        "min_r/g"=>1.2,
        "max_g/b"=>1.52,
        "min_g/b"=>0.7,
        "min_r/b"=>1.02,
        "r/g/b"=>0.006,
        "pix_in_line"=>7,
        "min_pixels_divizor"=>157,
    );


    /**
     *    constructor
     * @param string $include_dir
     */
    function imageris ($include_dir = '') {
        $incdir_len=strlen($include_dir);
        if ($include_dir and $include_dir[$incdir_len-1] != "/")
            $include_dir.="/";
        if (is_dir($include_dir))
            $this->include_dir=$include_dir;
    }

    /**
     *	gets image image type (jpeg, gif, bmp or png)
     *
     *	@param $filename - image filename of whole image as string
     *	@param $is_str - wether look for file $filename or use $filename as string-image
     *	@return string (type) or false
     */
    function image_get_type ($filename,$is_str=false) {

        if (!$is_str) {
            if (empty($filename))
                $filename=$this->filename;
            if (!is_readable($filename))
                return false;
            $f=fopen($filename,"r");
            $header=fread($f,3);
            fclose($f);
        }
        else
            $header=substr($filename,0,3);

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
    /**
     *	gets image width
     *	@return integer
     */
    function image_get_width () {
        return $this->width;
    }
    /**
     *	gets image width
     *	@return integer
     */
    function image_get_height () {
        return $this->height;
    }
    /**
     *	sets image
     *
     *	@param $filename - image filename of whole image as string
     *	@return bool
     */
    function image_set ($filename) {
        $str=false;
        $source="";
        if (!is_readable($filename) and strlen($filename) < 47)
            return false;
        elseif (!is_readable($filename)) {

            if ($this->image=imagecreatefromstring($filename) === false) {
                return false;
            }
            else {
                $str=true;
                $source=substr($filename,12);
            }
        }

        $this->filename = $this->include_dir.$filename;
        $this->type=$this->image_get_type($source,$str);
        if ($this->type == "jpeg")
            $this->image = ImageCreateFromJPEG($filename);
        elseif ($this->type == "gif")
            $this->image = ImageCreateFromGIF($filename);
        elseif ($this->type == "png") {
            $this->image = ImageCreateFromPNG($filename);

        }
        elseif ($this->type == "wbmp")
            $this->image = imagecreatefromwbmp($filename);
        else
            return false;
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        $this->image_set_output_type($this->type);


        return true;
    }
    /**
     *	sets jpeg file output quality
     * 	no effect on other image types
     *
     *	@param int $quality - jpeg quality {1..100}
     */
    function image_set_output_quality ($quality) {
        $this->quality=$quality;
    }
    /**
     *	sets output image type (jpeg, gif, png)
     *
     *	@param string $type - image type
     *	@return bool
     */
    function image_set_output_type ($type) {
        $type = strtolower($type);
        if ($type=="jpg") {
            $type="jpeg";
        }
        if (($type=="jpeg") or ($type=="gif") or ($type=="wbmp") or ($type=="png")) {
            $this->output_type=$type;
            return true;
        }
        return false;
    }

    /**
     *	resizes image
     * 	type 1 - just resizes image without saving proportions
     * 	type 2 - resizes image and keeps its proportions
     * 	type 3 - only resizes if image is bigger (does not fit into given dimentions) than requested size
     *
     *	@param int $width
     *	@param int $height
     *	@param int $type - one of three resizing types (simple, proportional, fit in dimentions) {1..3}
     *	@return true if resized, false if unchanged
     */
    function image_resize($width,$height,$type=2) { //resizing
        if (($width < 1) or ($height) < 1)
            return false;
        if ($type==3 and ($width > $this->width) and ($height > $this->height)) {
            return false;
        } elseif ($type==2) {
            $ratio =  ( $this->width <> $width ) ? ($width / $this->width) : 1 ;
            $new_width = round(($this->width * $ratio));	//full-size width
            $new_height = round(($this->height * $ratio));	//full-size height
            $ratio =  ( $new_height > $height ) ? ($height / $new_height) : 1 ;
            $new_width = round(($new_width * $ratio));	//mid-size width
            $new_height = round(($new_height * $ratio));	//mid-size height
            if ( $new_width == $this->width && $new_height == $this->height )
                return true;
        } else {
            $new_width  = $width;
            $new_height = $height;
        }
        $tmpimage = imagecreatetruecolor($new_width , $new_height);


        if ($this->type == "png") {


            imagealphablending($tmpimage, false); //cia funkcija reikalinga alfa kanalui 'transparentui'
            imagesavealpha($tmpimage,true); //cia irgi prie to paties funkcija reikalinga 'transparentui'
            $transparent = imagecolorallocatealpha($tmpimage, 255, 255, 255, 127); // cia kuriam permatomuma
            //imagefilledrectangle($tmpimage, 0, 0, $thumbX, $thumbY, $transparent); //padarom staciakampi kokio reik
           // dydziu ir uzpildom sukurtu transparentu

        }
        ImageCopyResampled($tmpimage, $this->image, 0,0,  0,0, $new_width, $new_height, $this->width, $this->height);

        imagedestroy($this->image);
        $this->image=$tmpimage;
        $this->width=$new_width;
        $this->height=$new_height;


        return true;
    }
    /**
     *	crops image with ability to TRY not to crop human face
     *
     *	@param int $width
     *	@param int $height
     *	@param bool $smart - tries no to crop face if set
     *	@return true if croped, false if unchanged
     */
    function image_crop ($width, $height, $smart=false) {
        if ($width == $this->width and $height == $this->height)
            return false;
        $temp_size=$this->_calculate_crop($width,$height);
        $this->image_resize($temp_size['width'],$temp_size['height']);
        $smart_ratio=($smart)?$this->_calculate_smart_ratio():1;
        $x = ($this->width > $width) ? floor(($this->width - $width)/(2*$smart_ratio)) : 0;
        //echo floor(($this->width - $width)/(2));
        $y = ($this->height > $height) ? floor(($this->height - $height)/2.2) : 0;
        $tmpimage = imagecreatetruecolor($width, $height);
        if ($this->type == "png") {
            imagealphablending($tmpimage,false);
            imagesavealpha($tmpimage,true);
            $transparent = imagecolorallocatealpha($tmpimage, 255, 255, 255, 0);
            for($j=0;$j<$width;$j++) {
                for($i=0;$i<$height;$i++) {
                    imageSetPixel( $tmpimage, $j, $i, $transparent );
                }
            }
        }
        imagecopyresampled($tmpimage, $this->image, 0, 0, $x, $y,$width, $height, $width, $height);
        imagedestroy($this->image);
        $this->image=$tmpimage;
        $this->width=$width;
        $this->height=$height;
        return true;
    }

    function image_crop2 ($width, $height, $smart=false) {
        if ($width == $this->width and $height == $this->height)
            return false;
        $temp_size=$this->_calculate_crop($width,$height);
        $this->image_resize($temp_size['width'],$temp_size['height']);
        $smart_ratio=($smart)?$this->_calculate_smart_ratio():1;
        $x = ($this->width > $width) ? floor(($this->width - $width)/(2*$smart_ratio)) : 0;
        //echo floor(($this->width - $width)/(2));
        $y = ($this->height > $height) ? floor(($this->height - $height)/2.2) : 0;
        $tmpimage = imagecreatetruecolor($width, $height);
        if ($this->type == "png") {
            imagealphablending($tmpimage,false);
            imagesavealpha($tmpimage,true);
            $transparent = imagecolorallocatealpha($tmpimage, 255, 255, 255, 0);
            for($j=0;$j<$width;$j++) {
                for($i=0;$i<$height;$i++) {
                    imageSetPixel( $tmpimage, $j, $i, $transparent );
                }
            }
        }
        imagecopyresampled($tmpimage, $this->image, 0, 0, 0, 0,$width, $height, $width, $height);
        imagedestroy($this->image);
        $this->image=$tmpimage;
        $this->width=$width;
        $this->height=$height;
        return true;
    }
    /**
     *	gives more clarity to image
     *	very useful for blured thumbnails
     * 	@author Torstein Hønsi
     *	@param int $amount
     *	@param int $radius
     *	@param int $threshold
     */
    function image_unsharpmask($amount=70, $radius=0.5, $threshold=3)    {
        ////    Unsharp mask algorithm by Torstein Hønsi 2003 (thoensi_at_netcom_dot_no).
        if ($amount > 500)    $amount = 500;
        $amount = $amount * 0.016;
        if ($radius > 50)    $radius = 50;
        $radius = $radius * 2;
        if ($threshold > 255)    $threshold = 255;

        $radius = abs(round($radius));     // Only integers make sense.
        if ($radius == 0) {
            return true;
            //break;
        }
        $w = $this->width; $h = $this->height;
        $imgCanvas = imagecreatetruecolor($w, $h);
        $imgCanvas2 = imagecreatetruecolor($w, $h);
        $imgBlur = imagecreatetruecolor($w, $h);
        $imgBlur2 = imagecreatetruecolor($w, $h);
        imagecopy ($imgCanvas, $this->image, 0, 0, 0, 0, $w, $h);
        imagecopy ($imgCanvas2, $this->image, 0, 0, 0, 0, $w, $h);

        // Move copies of the image around one pixel at the time and merge them with weight
        // according to the matrix. The same matrix is simply repeated for higher radii.
        for ($i = 0; $i < $radius; $i++)    {
            imagecopy ($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1); // up left
            imagecopymerge ($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50); // down right
            imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333); // down left
            imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25); // up right
            imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333); // left
            imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25); // right
            imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 ); // up
            imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // down
            imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50); // center
            imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

            // During the loop above the blurred copy darkens, possibly due to a roundoff
            // error. Therefore the sharp picture has to go through the same loop to
            // produce a similar image for comparison. This is not a good thing, as processing
            // time increases heavily.
            imagecopy ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 20 );
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 16.666667);
            imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
            imagecopy ($imgCanvas2, $imgBlur2, 0, 0, 0, 0, $w, $h);

        }
        // Calculate the difference between the blurred pixels and the original
        // and set the pixels
        for ($x = 0; $x < $w; $x++) { // each row
            for ($y = 0; $y < $h; $y++) { // each pixel

                $rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
                $rOrig = (($rgbOrig >> 16) & 0xFF);
                $gOrig = (($rgbOrig >> 8) & 0xFF);
                $bOrig = ($rgbOrig & 0xFF);

                $rgbBlur = ImageColorAt($imgCanvas, $x, $y);

                $rBlur = (($rgbBlur >> 16) & 0xFF);
                $gBlur = (($rgbBlur >> 8) & 0xFF);
                $bBlur = ($rgbBlur & 0xFF);

                // When the masked pixels differ less from the original
                // than the threshold specifies, they are set to their original value.
                $rNew = (abs($rOrig - $rBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
                    : $rOrig;
                $gNew = (abs($gOrig - $gBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
                    : $gOrig;
                $bNew = (abs($bOrig - $bBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
                    : $bOrig;



                if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
                    $pixCol = ImageColorAllocate($this->image, $rNew, $gNew, $bNew);
                    ImageSetPixel($this->image, $x, $y, $pixCol);
                }
            }
        }

        imagedestroy($imgCanvas);
        imagedestroy($imgCanvas2);
        imagedestroy($imgBlur);
        imagedestroy($imgBlur2);

    }
    /**
     *	blurs image
     *	don't use radius more than 2, it's damn slow.
     *	@param int $radius
     *	@return true if resized, false if unchanged
     */
    function image_blur ($radius=1) {

        for ($x = 0; $x < $this->width; ++$x) {
            for ($y = 0; $y < $this->height; ++$y) {
                $newr = 0;
                $newg = 0;
                $newb = 0;
                $colours = array();
                $thiscol = imagecolorat($this->image, $x, $y);
                for ($k = $x - $radius; $k <= $x + $radius; ++$k) {
                    for ($l = $y - $radius; $l <= $y + $radius; ++$l) {
                        if ($k < 0) { $colours[] = $thiscol; continue; }
                        if ($k >= $this->width) { $colours[] = $thiscol; continue; }
                        if ($l < 0) { $colours[] = $thiscol; continue; }
                        if ($l >= $this->height) { $colours[] = $thiscol; continue; }
                        $colours[] = imagecolorat($this->image, $k, $l);
                    }
                }
                foreach($colours as $colour) {
                    $newr += ($colour >> 16) & 0xFF;
                    $newg += ($colour >> 8) & 0xFF;
                    $newb += $colour & 0xFF;
                }
                $numelements = count($colours);
                $newr /= $numelements;
                $newg /= $numelements;
                $newb /= $numelements;
                $newcol = imagecolorallocate($this->image, $newr, $newg, $newb);
                imagesetpixel($this->image, $x, $y, $newcol);
            }
        }


    }
    /**
     *	coverts image into grayscale
     */
    function image_greyscale () {
        for ($x = 0; $x <$this->width; ++$x) {
            for ($y = 0; $y <$this->height; ++$y) {
                $rgb = imagecolorat($this->image, $x, $y);
                $red = ($rgb >> 16) & 255;
                $green = ($rgb >> 8) & 255;
                $blue = $rgb & 255;
                $grey = (int)(($red+$green+$blue)/3);
                $newcol = imagecolorallocate($this->image, $grey,$grey,$grey);
                imagesetpixel($this->image, $x, $y, $newcol);
            }
        }
    }

    /**
     *	Puts png image on the top of image (transparency supported)
     *	@param int $x could be string describing relative position: center, left or right
     *	@param int $y could be string describing relative position: middle, top or bottom
     */
    function image_put_image ($filename,$kx,$ky) {
        $filename=$this->include_dir.$filename;
        if ((!is_readable($filename)) or ($this->image_get_type($filename) != "png"))
            return false;
        $temp=imagecreatefrompng($filename);
        $y=imagesy($temp);
        $x=imagesx($temp);
        $kx=$this->_string_to_coordinate($kx,$x,$y);
        $ky=$this->_string_to_coordinate($ky,$x,$y);

        imagealphablending($this->image,true);
        imagesavealpha($this->image,true);
        imagecopy($this->image,$temp,$kx,$ky,0,0,$x,$y);
        return true;
    }
    /**
     *	outputs image to browser and kills script
     *	make sure thre are no sent header
     */
    function image_display() {
        if (!headers_sent() and preg_match("/^jpeg|gif|png|wbmp$/i",$this->type)) {
            header("Content-type: image/{$this->output_type}");
            if ($this->output_type == "jpeg")
                imagejpeg($this->image, null, $this->quality);
            elseif ($this->output_type == "gif")
                imagegif($this->image);
            elseif ($this->output_type == "png"){
                imagepng($this->image); die('bb');}
            elseif ($this->output_type == "wbmp")
                imagewbmp($this->image);
            else
                return false;
            imagedestroy($this->image);
            exit;
        }
        else die("Fatal error: headers already sent or wrong img type!");
    }
    /**
     *	saves image
     *
     *	@param string $dest_filename
     *	@return bool
     */
    function image_save ($dest_filename) {
        $dest_filename=$this->include_dir.$dest_filename;
        if ($this->output_type == "jpeg")
            return imagejpeg($this->image,$dest_filename,$this->quality);
        elseif ($this->output_type == "gif")
            return imagegif($this->image,$dest_filename);
        elseif ($this->output_type == "png")
            return imagepng($this->image,$dest_filename);
        elseif ($this->output_type == "wbmp")
            return image2wbmp($this->image,$dest_filename);
        return false;
    }
    /**
     *	rounds image
     *
     *	@param int $radius - rounding radius
     *	@param int $background_color - optional for png output. Must be in hex formant (i.e FFFFFF)
     */
    function image_round ($radius,$background_color="") {
        $radius*=2;
        $max=min($this->width/2,$this->height/2);
        $radius=min($max,$radius);
        if ($this->_is_valid_color($background_color)) {
            $br=hexdec(substr($background_color,0,2));
            $bg=hexdec(substr($background_color,2,2));
            $bb=hexdec(substr($background_color,4,2));
            $maxtransp=0;
        }
        else {
            $maxtransp=127;
            $br=220;
            $bg=220;
            $bb=220;
            imagealphablending($this->image,false);
            imagesavealpha($this->image,true);
        }
        for ($x=0;$x<$this->width;$x++) {
            for ($y=0;$y<$this->height;$y++) {
                $cordx=($x > $radius)?$this->width-$x:$x;
                $cordy=($y > $radius)?$this->height-$y:$y;
                if (($cordx <= $radius) and ($cordy <= $radius)) {
                    $d = ceil(sqrt(pow($cordx-$radius,2)+pow($cordy-$radius,2)));
                    if ($d > $radius) {
                        $transparent=imagecolorallocatealpha($this->image,$br,$bg,$bb,$maxtransp);
                        imagesetpixel($this->image,$x,$y,$transparent);
                    }
                }
            }
        }
    }
    /**
     *	puts nice rounded fading out border on the image
     *
     *	@param int $radius - size of border
     *	@param string $background_color - optional for png output. Must be in hex formant (i.e FFFFFF)
     */
    function image_rounded_border ($len,$background_color="") {
        $radius=$len*2;
        //$tmpimage=imagecreatetruecolor($this->width,$this->height);
        if ($this->_is_valid_color($background_color)) {
            $br=hexdec(substr($background_color,0,2));
            $bg=hexdec(substr($background_color,2,2));
            $bb=hexdec(substr($background_color,4,2));
            $maxtransp=0;
        }
        else {
            $maxtransp=127;
            $br=220;
            $bg=220;
            $bb=220;
            imagealphablending($this->image,false);
            imagesavealpha($this->image,true);
        }
        for ($x=0;$x<$this->width;$x++)
            for ($y=0;$y<$this->height;$y++) {
                $color=imagecolorat($this->image,$x,$y);
                $colors=imagecolorsforindex($this->image,$color);
                if ((($x < $len) or ($x > $this->width-$len)) and ($y > $radius) and ($y < $this->height-$radius)) {
                    $cord=($x<$len) ? $x : $this->width-$x;
                    $i=round($maxtransp-($maxtransp/($len))*$cord);
                    $i=($i>$maxtransp)?$maxtransp:$i;
                    $transparent=imagecolorallocatealpha($this->image,$colors['red'],$colors['green'],$colors['blue'],$i);
                    imagesetpixel($this->image,$x,$y,$transparent);
                }
                elseif ((($y < $len) or ($y > $this->height-$len)) and ($x > $radius) and ($x < $this->width-$radius))  {
                    $cord=($y<$len) ? $y : $this->height-$y;
                    $i=round($maxtransp-($maxtransp/$len)*$cord);
                    $i=($i>$maxtransp)?$maxtransp:$i;
                    $transparent=imagecolorallocatealpha($this->image,$colors['red'],$colors['green'],$colors['blue'],$i);
                    imagesetpixel($this->image,$x,$y,$transparent);
                }
                else {
                    $cordx=($x > $radius)?$this->width-$x:$x;
                    $cordy=($y > $radius)?$this->height-$y:$y;

                    $d = round (sqrt(pow($cordx-$radius,2)+pow($cordy-$radius,2)));
                    //if ($cordx == 8) echo "x=$x; cx=$cordx; d=$d<br>";
                    if (($d > $radius/2) and ($d < $radius) and ($cordx <= $radius) and ($cordy <= $radius)) {
                        $d = round (sqrt(pow($cordx-$radius,2)+pow($cordy-$radius,2)) - $radius/2);
                        $d0=round (sqrt(pow($radius-0,2)+pow($radius-0,2))-$radius/2);
                        //$d=round (sqrt(pow($cordx-0,2)+pow($cordy-0,2)));
                        $i=round(($maxtransp/($radius/2)*$d));
                        //echo "d0=$d0, d=$d x=$cordx; y=$cordy; i=$i<br>";
                        $i=($i>$maxtransp)?$maxtransp:$i;
                        $transparent=imagecolorallocatealpha($this->image,$colors['red'],$colors['green'],$colors['blue'],$i);
                        imagesetpixel($this->image,$x,$y,$transparent);
                    }
                    elseif (($d >= $radius) and ($cordx <= $radius) and ($cordy <= $radius)) {
                        $transparent=imagecolorallocatealpha($this->image,$br,$bg,$bb,$maxtransp);
                        imagesetpixel($this->image,$x,$y,$transparent);
                    }
                }
            }
    }
    /**
     *	writes text on image
     *	transparency will be supported in future (useful for watermarks)
     *	@param string $text
     *	@param mixed $x - can be represented as relative coordinate (left, right or center)
     *	@param mixed $y - can be represented as relative coordinate (top, middle, bottom)
     *	@param string $fg_color - text color (in hexidecimal, i.e FFFFFF)
     *	@param string $bg_color - text background color (optional)
     *	@param int $fontsize - size of font
     *	@param string $font_file - ttf font filename
     *	@param string $shadow_color - color of text shadow
     *	@param int $shadow_radius - text shadow radius
     */
    function image_text($text,$x,$y,$fg_color,$bg_color="",$fontsize=7,$font_file="",$shadow_color="",$shadow_radius=0) {
        $font_file=realpath($this->include_dir.$font_file);
        $shadow_color=($this->_is_valid_color($shadow_color))?$shadow_color:0;
        $fg_color=($this->_is_valid_color($fg_color))?hexdec($fg_color):0;
        $bg_color=($this->_is_valid_color($bg_color))?hexdec($bg_color):false;
        if (is_readable($font_file)) {
            $mas=imagettfbbox($fontsize,0,$font_file,$text);
            $obj_w=$mas[4]-$mas[6]; //take a look at the manual for this strange thing: http://lt.php.net/imagettfbox
            $obj_h=$mas[1]-$mas[7];
            $x=$this->_string_to_coordinate($x,$obj_w,$obj_h);
            $y=$this->_string_to_coordinate($y,$obj_w,$obj_h);
            if ($bg_color)
                imagefilledrectangle($this->image,$x,$y,$x+$obj_w,$y+$obj_h,$bg_color);
            if ($shadow_radius != 0)
                $this->image_text($text,$x+$shadow_radius,$y+$shadow_radius,$shadow_color,"",$fontsize,$font_file);
            //if (!$this->_is_valid_color($bg_color))
            imagettftext($this->image,$fontsize,0,$x,$y,$fg_color,$font_file,$text);
        }
        else {
            $fontsize=($fontsize > 5)?5:$fontsize;
            $obj_w=imagefontwidth($fontsize)*strlen($text);
            $obj_h=imagefontheight($fontsize);
            $x=$this->_string_to_coordinate($x,$obj_w,$obj_h);
            $y=$this->_string_to_coordinate($y,$obj_w,$obj_h);
            if ($bg_color)
                imagefilledrectangle($this->image,$x,$y,$x+$obj_w,$y+$obj_h,$bg_color);
            if ($shadow_radius != 0)
                $this->image_text($text,$x+$shadow_radius,$y+$shadow_radius,$shadow_color,"",$fontsize,$font_file);
            imagestring($this->image,$fontsize,$x,$y,$text,$fg_color);
        }

    }
    function _calculate_crop($width,$height) {
        if (($height/$width > $this->height/$this->width))
            $ratio=($this->height <> $height) ? ($height/$this->height) : 1;
        else
            $ratio=($this->width <> $width) ? ($width/$this->width) : 1;
        $nwidth = round($this->width * $ratio);
        $nheight = round($this->height * $ratio);
        if (($nheight > $this->height) and !($width > $this->width) and !($height > $this->height)) {
            $ratio=$height/$this->height;
            $nwidth=$nwidth*$ratio;
            $nheight=$nheight*$ratio;
        }
        return array("width"=>$nwidth,"height"=>$nheight);
    }
    function _calculate_smart_color($color) {
        $color=imagecolorsforindex($this->image,$color);
        $color['red']=($color['red']==0)?1:$color['red'];
        $color['green']=($color['green']==0)?1:$color['green'];
        $color['blue']=($color['blue']==0)?1:$color['blue'];
        if (($color['red'] > 187) and ($color['red'] < 232) and ($color['green'] > 182) and ($color['blue']) > 182)
            $color['red']=1;
        if ($color['red']/$color['green']/$color['blue']<$this->config['r/g/b'])
            $color['red']=1;
        if (($color['red'] > $this->config['min_red']) and ($color['red']+$color['green']+$color['blue'] > $this->config['min_sviesumas']) and ($color['red']+$color['green']+$color['blue'] < $this->config['max_sviesumas']) and ($color['red']>$color['green']) and ($color['green'] > $color['blue']-17) and ($color['red']/$color['green'] < $this->config['max_r/g'])and ($color['red']/$color['green'] > $this->config['min_r/g']) and ($color['green']/$color['blue'] < $this->config['max_g/b'])and ($color['green']/$color['blue'] > $this->config['min_g/b']) and ($color['red']/$color['blue'] > $this->config['min_r/b']))
            return true;
        else
            return false;
    }
    function _calculate_smart_ratio() {
        $mas=array(0,0,0,0,0);
        $x_kr=round($this->width/10);
        $y_kr=round($this->height/10);
        $cnt=0;
        $pcnt=0;
        $vid=0;
        $pvid=0;
        for ($y=$y_kr; $y<=$this->height-($y_kr*2);$y++) {
            for ($x=$x_kr; $x<=$this->width-$x_kr;$x++) {
                $bool=true;
                $pvid=$vid;
                $pcnt=$cnt;
                for ($i=0;$i<=$this->config['pix_in_line'];$i++) {
                    $color=imagecolorat($this->image,$x+$i,$y);
                    $bool=$this->_calculate_smart_color($color) && $bool;
                    $pvid+=$x+$i;
                    $pcnt++;
                }
                if ($bool) {
                    $vid=$pvid;
                    $cnt=$pcnt;
                    //for ($i=0;$i<=$this->config['pix_in_line'];$i++)
                    //	imagesetpixel($this->image,$x+$i,$y,0);
                }
                $x=$x+$i-1;
            }
        }
        if ($cnt > $this->height*$this->width/$this->config["min_pixels_divizor"]) {
            $vid=$vid/$cnt;
            $ratio=2-(2/$this->width)*$vid;
            if (($cnt > ($this->height*$this->width/$this->config["min_pixels_divizor"])*3) and (($ratio < 1-0.3) or ($ratio > 1+0.3)))
                $this->config['sensitivity']+=0.72;
            $ratio=($vid>1)?$ratio+$this->config['sensitivity']:$ratio-$this->config['sensitivity']; //padidinam jautruma
            $ratio=($ratio<0)?1:$ratio;
        }
        else
            $ratio=1;
        return $ratio;
    }
    /**
     *	is this a valid color in hex?
     *	@access	private
     */
    function _is_valid_color ($color) {
        if (eregi("^[a-f0-9]{6}$",$color))
            return true;
        else
            return false;
    }
    /**
     *	makes real coordinate from relative coordinate
     * 	if given coordinate is real (integer) then returns it unchanged
     *	@param string $string - relative coordinate (center, middle, left, right, top or bottom)
     *	@param int $obj_w - object width
     *	@param int $obj_h - object height
     * 	@return int coordinate
     *	@access	private
     */
    function _string_to_coordinate ($string,$obj_w,$obj_h) {
        $coordinate=0;
        if (!is_numeric($string) and is_string($string)) {
            switch ($string) {
                case "left":
                    $coordinate=0;
                    break;
                case "right":
                    $coordinate=$this->width-$obj_w;
                    break;
                case "center":
                    $coordinate=round(($this->width/2)-($obj_w/2));
                    break;
                case "top":
                    $coordinate=0;
                    break;
                case "bottom":
                    $coordinate=$this->height - $obj_h;
                    break;
                case "middle":
                    $coordinate=round($this->height/2);
                    break;
                default:
                    $coordinate=0;
            }
            return $coordinate;
        }
        elseif (is_numeric($string))
            return $string;
        else
            return false;
    }
}
?>

<?php
/*
* Hit counter script
* By virtualanup
* http://www.virtualanup.com
      .__         __               .__
___  _|__|_______/  |_ __ _______  |  | _____    ____  __ ________
\  \/ /  \_  __ \   __\  |  \__  \ |  | \__  \  /    \|  |  \____ \
 \   /|  ||  | \/|  | |  |  // __ \|  |__/ __ \|   |  \  |  /  |_> >
  \_/ |__||__|   |__| |____/(____  /____(____  /___|  /____/|   __/
                                 \/          \/     \/      |__|
 
               ,---.
            ,.'-.   \
           ( ( ,'"""""-.
           `,X          `.
           /` `           `._
          (            ,   ,_\
          |          ,---.,'o `.
          |         / o   \     )
           \ ,.    (      .____,
            \| \    \____,'     \
          '`'\  \        _,____,'
          \  ,--      ,-'     \
            ( C     ,'         \
             `--'  .'           |
               |   |         .O |
             __|    \        ,-'_
            / `L     `._  _,'  ' `.
           /    `--.._  `',.   _\  `
           `-.       /\  | `. ( ,\  \
          _/  `-._  /  \ |--'  (     \
         '  `-.   `'    \/`.   `.    )
               \  -hrr-    \ `.  |    |
 
*/

$directory="";//the directory to store the data. it is optional. add "/" at the last. if you want to store the data inside directory "apple", add $directory="apple/"
$filename="counter.txt";//the name of the file you want to store the data in. Give the file NAME not its PATH. use $directory if you want the file in seperate path
$startcount=123;//the starting counter for a page. Whenever a new page with no data is encountered, the counter is started with this value
$fontname="coolfont.ttf";
$fontsize=20;//size of the font to print
$textcolor=array(255,52,84);//text color
$backcolor=array(255,255,255);//background color
	




//check if page id is passed
if(isset($_GET['page']))
{
	//first of all, check that all characters are clean
	$page=strtolower($_GET['page']);
	if (! preg_match ('/[^a-z]/i', $page))//we allow only alphabets
		$filename=$page."_".$filename;
}
$file=0;
$counter=0;
$filename=$directory.$filename;

if(file_exists($filename))
{
	$file=fopen($filename,"rb+");//open for reading and writing
	$counter=fread($file,filesize($filename));
}
else
{
	$file=fopen($filename,"wb+");//create the file for reading and writing
	$counter=$startcount;
}
//increase the counter
$counter++;
//write it back in the file
rewind($file);
fwrite($file,$counter,strlen($counter));

//now print the count in the image
//first of all, calculate the size of the image
$dimensions = imagettfbbox($fontsize, 0, $fontname, $counter);

$height=$dimensions[3]+35;
$width=$dimensions[2]+20;

//create the image of requires width and height
$image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
$background_color = imagecolorallocate($image, $backcolor[0], $backcolor[1], $backcolor[2]);
$text_color = imagecolorallocate($image, $textcolor[0], $textcolor[1], $textcolor[2]);
//calculate the position to aligh the text in the middle
$x = ($width - $dimensions[4])/2;
$y = ($height - $dimensions[5])/2;
imagettftext($image, $fontsize, 0, $x, $y, $text_color, $fontname , $counter) or die('Error in imagettftext function');


// Date in the past so that it doesnot cache it
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
// send the content type header so the image is displayed properly
header('Content-type: image/jpeg');
imagejpeg($image);
imagedestroy($image);
?>
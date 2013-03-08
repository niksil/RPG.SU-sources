<?php
if (isset($_GET['img'])) {

ini_set("user_agent", "Mozilla/5.0 Gecko/20070219 Firefox/2.0.0.2");

$url = "../images/item/".urldecode($_GET['img']).".gif"; 

$img_array = getimagesize($url);
if ($img_array[2]==1)
{
	$portrait = imagecreatefromgif($url);
}
elseif ($img_array[2]==2)
{
	$portrait = imagecreatefromjpeg($url);
}
elseif ($img_array[2]==3)
{
	$portrait = imagecreatefrompng($url);
}
elseif ($img_array[2]==15)
{
	$portrait = imagecreatefromwbmp($url);
}
elseif ($img_array[2]==16)
{
	$portrait = imagecreatefromxbm($url);
}
else
{
	exit ('Не определен рисунок!');
}

$img_add = imagecreatefromgif("../images/item/kleymo.gif"); 
imagecopy($portrait,$img_add,imagesx($portrait)-imagesx($img_add),imagesy($portrait)-imagesy($img_add),0,0,imagesx($img_add),imagesy($img_add));

$filename = $_GET['img'];
$ext = "gif";
//header("Content-disposition: inline; filename=$filename.$ext");
header("content-type: image/$ext");
header("cache-control: max-age=86400");
ImageGIF($portrait);
}
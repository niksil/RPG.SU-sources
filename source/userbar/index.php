<?php
//error_reporting(0);
$dirclass = "../class";
require_once("../inc/xray.inc.php");
require_once("../inc/lib.inc.php");

function getHexColors($c) {
	$c = preg_replace("/[^a-f0-9]/i", "", $c);
	return array(
		hexdec(substr($c, 0, 2)),
		hexdec(substr($c, 2, 2)),
		hexdec(substr($c, 4, 2))
	);
}

function width($txt, $size, $font) {
		$b = ImageTTFBBox($size, 0, $font, $txt);
		return $b[2];
}


if (isset($_GET['name'])) {

$mainFont = "FrizqtRus.ttf";
$plainFont = "verdana.TTF";

ini_set("user_agent", "Mozilla/5.0 Gecko/20070219 Firefox/2.0.0.2");
	   
$sql = "SELECT 
game_users.clevel,
game_users.name,
game_users.clan_id,
game_har.id AS race_id,
game_har.name AS race,
game_users.STR_MAX,
game_users.VIT_MAX,
game_users.DEX_MAX,
game_users.SPD_MAX,
game_users.NTL_MAX,
game_users.HP_MAX,
game_users.MP_MAX,
game_users.PIE_MAX,
game_users.lucky,
game_users_active.last_active,
game_users.WIN,
game_users.LOSE,
game_users.avatar,
game_users.sklon,
game_users.user_id,
game_users.MS_KUZN,
game_users.MS_LEK,
game_users.MS_VOR
FROM 
game_users,game_har,game_users_active 
WHERE game_har.id=game_users.race AND game_users_active.user_id=game_users.user_id";

if ((int)$_GET['name']>0)
{
	$sql.=' AND game_users.user_id='.(int)$_GET['name'].'';
}
else
{
	$char_name = get_magic_quotes_gpc() ? $_GET['name'] : mysql_escape_string($_GET['name']);
	$sql.=' AND game_users.name=\''.$char_name.'\'';
}

$sql = myquery($sql);
if ($sql==false OR mysql_num_rows($sql)==0)
{
	$sql = "SELECT 
	game_users_archive.clevel,
	game_users_archive.name,
	game_users_archive.clan_id,
	game_har.id AS race_id,
	game_har.name AS race,
	game_users_archive.STR_MAX,
	game_users_archive.VIT_MAX,
	game_users_archive.DEX_MAX,
	game_users_archive.SPD_MAX,
	game_users_archive.NTL_MAX,
	game_users_archive.HP_MAX,
	game_users_archive.MP_MAX,
	game_users_archive.PIE_MAX,
	game_users_archive.lucky,
	game_users_active.last_active,
	game_users_archive.WIN,
	game_users_archive.LOSE,
	game_users_archive.avatar,
	game_users_archive.sklon,
	game_users_archive.user_id,
	game_users_archive.MS_KUZN,
	game_users_archive.MS_LEK,
	game_users_archive.MS_VOR
	FROM 
	game_users_archive,game_har,game_users_active 
	WHERE game_har.id=game_users_archive.race AND game_users_active.user_id=game_users_archive.user_id";

	if ((int)$_GET['name']>0)
	{
		$sql.=' AND game_users_archive.user_id='.(int)$_GET['name'].'';
	}
	else
	{
		$char_name = get_magic_quotes_gpc() ? $_GET['name'] : mysql_escape_string($_GET['name']);
		$sql.=' AND game_users_archive.name=\''.$char_name.'\'';
	}
	$sql = myquery($sql);
}

if (mysql_num_rows($sql)!=1) {die("Ошибка! Персонаж не найден!");} 

$row = mysql_fetch_array($sql, MYSQL_ASSOC);

$char_name = $row['name'];

if (DOMAIN==TEST_SERVER)
{
	$url = getenv("DOCUMENT_ROOT")."/../images/avatar/".$row['avatar'];
}
else
{
	$url = getenv("DOCUMENT_ROOT")."/../../images/avatar/".$row['avatar'];
}
if (!is_file($url))
{
	die("Аватар не найден");
}

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
	exit ('Не определен аватар!');
}

$MainHeight = 100;    
	
$img = ImageCreateTrueColor(490, $MainHeight);

function win2uni($s)
{
  $s = convert_cyr_string($s,'w','i');
  for ($result='', $i=0; $i<strlen($s); $i++)
  {
	$charcode = ord($s[$i]);
	$result .= ($charcode>175)?"&#".(1040+($charcode-176)).";":$s[$i];
  }
  return $result;
}

$reason = '';
if (time()-$row['last_active']<=300)
{
	$reason = get_delay_reason(get_delay_reason_id($row['user_id']));
}
if ($row['sklon']==1)
{
	//нейтральные
	$text_color_1 = "FFFF00";
	$text_color_2 = "FFFFFF";
	$color_1 = "000000";
	$color_2 = "00CC66";
}
elseif ($row['sklon']==2)
{
	//светлые
	$text_color_1 = "FFFF00";
	$text_color_2 = "FFFFFF";
	$color_1 = "000000";
	$color_2 = "FF0000";
}
elseif ($row['sklon']==3)
{
	//темные
	$text_color_1 = "FFFF00";
	$text_color_2 = "FFFFFF";
	$color_1 = "000000";
	$color_2 = "006BFF";
}
else
{
	//никакие
	$text_color_1 = "FFFF00";
	$text_color_2 = "FFFFFF";
	$color_1 = "000000";
	$color_2 = "ACACAC";
}

$staName[1] = "Сила";
$sta[1] = $row['STR_MAX'];

$staName[2] = "Выносливость";
$sta[2] = $row['DEX_MAX'];

$staName[3] = "Защита";
$sta[3] = $row['VIT_MAX'];

$staName[4] = "";
$sta[4] = "";

$staName[5] = "Здоровье";
$sta[5] = $row['HP_MAX'];

$staName[6] = "Выиграл";
$sta[6] = $row['WIN'];

$statName[1] = "Интеллект";
$stat[1] = $row['NTL_MAX'];

$statName[2] = "Мудрость";
$stat[2] = $row['SPD_MAX'];

$statName[3] = "Ловкость";
$stat[3] = $row['PIE_MAX'];

$statName[4] = "";
$stat[4] = "";

$statName[5] = "Мана";
$stat[5] = $row['MP_MAX'];

$statName[6] = "Проиграл";
$stat[6] = $row['LOSE'];

$profs = array();
if ($row['MS_KUZN']>0)
{
	$prof["name"] = "Кузнец";
	$prof["value"] = $row['MS_KUZN'];
	$profs[] = $prof;
}
if ($row['MS_LEK']>0)
{
	$prof["name"] = "Лекарь";
	$prof["value"] = $row['MS_LEK'];
	$profs[] = $prof;
}
if ($row['MS_VOR']>0)
{
	$prof["name"] = "Вор";
	$prof["value"] = $row['MS_VOR'];
	$profs[] = $prof;
}
 
$char_name = strtoupper(substr($char_name,0,1)). substr($char_name,1,strlen($char_name)-1);

$white = ImageColorAllocate($img, 255, 255, 255);
$whitea = ImageColorAllocateAlpha($img, 255, 255, 255, 90);
$black = ImageColorAllocate($img, 0, 0, 0);
$blacka = ImageColorAllocateAlpha($img, 0, 0, 0, 100);

$text_r = 255;
$text_g = 210;
$text_b = 0;

$s_text_r = 255;
$s_text_g = 255;
$s_text_b = 255;

if($text_color_1) list($text_r, $text_g, $text_b) = getHexColors($text_color_1);
if($text_color_2) list($s_text_r, $s_text_g, $s_text_b) = getHexColors($text_color_2);

$textcolor = ImageColorAllocate($img, $text_r, $text_g, $text_b);
$secondaryTextColor = ImageColorAllocate($img, $s_text_r, $s_text_g, $s_text_b);

ImageFill($img, 0, 0, $black);

$base_r = 0;
$base_g = 0;
$base_b = 0;

$dest_r = 0;
$dest_g = 120;
$dest_b = 255;

if($color_1) list($base_r, $base_g, $base_b) = getHexColors($color_1);
if($color_2) list($dest_r, $dest_g, $dest_b) = getHexColors($color_2);

for($i=0; $i<ImageSY($img); $i++) {
	$r = min($base_r + (($dest_r - $base_r) / ImageSY($img) * $i) ,255);
	$g = min($base_g + (($dest_g - $base_g) / ImageSY($img) * $i) ,255);
	$b = min($base_b + (($dest_b - $base_b) / ImageSY($img) * $i) ,255);
	$c = ImageColorAllocate($img, $r, $g, $b);
	ImageLine($img, 0, $i, ImageSX($img), $i, $c);
	ImageColorDeallocate($img, $c);
}

$offset = 3;
ImageFilledRectangle($img, $offset, $offset, ImageSX($img) - $offset - 1, ImageSY($img) - $offset - 1, $blacka);
ImageRectangle($img, $offset, $offset, ImageSX($img) - $offset - 1, ImageSY($img) - $offset - 1, $blacka);

if ($img_array[1]==0)
{
	$new_width = 0;
}
else
{
	$new_width = $img_array[0]*($MainHeight/$img_array[1]);
}
ImageCopyResampled($img, $portrait, 0, 0, 0, 0, $new_width, $MainHeight, ImageSX($portrait), ImageSY($portrait));
ImageRectangle($img, 0, 0, ImageSX($img) - 1, ImageSY($img) - 1, $black);

$left = 0;
$RIGHT_EDGE = 0;
$fontSize = 24;
if(strlen($char_name) > 22)
	$fontSize = 8;
elseif(strlen($char_name) > 18)
	$fontSize = 12;
elseif(strlen($char_name) > 14)
	$fontSize = 14;
elseif(strlen($char_name) > 12)
	$fontSize = 16;
elseif(strlen($char_name) > 10)
	$fontSize = 20;
ImageTTFText($img, $fontSize, 0, $new_width+5, 30, $textcolor, $mainFont, $char_name);
$left = width($char_name, $fontSize, $mainFont) + 70;

$textLeft = 85;
for($i=1; $i<=3; $i++) {
	if(isset($profs[$i-1]) > 0) {
		$prof = $profs[$i-1];
		$name = $prof['name'];
		$v = $prof['value'];
		$txt = "$v $name";
		$textLeft+=100;
		ImageTTFText($img, 8, 0, $textLeft, 90, $textcolor, $plainFont, win2uni($txt));
	}
}

$lineWidth = 495 - $new_width - 45 - 45;
$midWidth = $lineWidth;
$y = 43;
ImageLine($img, $new_width+45, $y, 445, $y, $textcolor);
for($i=0; $i<40; $i++) {
	$c = ImageColorAllocateAlpha($img, $text_r, $text_g, $text_b, floor(87 / 40 * $i)+40);
	ImageLine($img, $new_width+45+1-$i, $y, $new_width+45-$i, $y, $c);
	ImageLine($img, 445+$i, $y, 446+$i, $y, $c);
	ImageColorDeallocate($img, $c);
}

ImageTTFText($img, 10, 0, $new_width+5, 59, $textcolor, $mainFont, win2uni($row['race'])." ".win2uni($row['clevel'])." ур.  - RELIZED BY X-ray");
 
if ($row['clan_id']>0)
{
	$clan_name = mysql_result(mysql_query("SELECT nazv FROM game_clans WHERE clan_id=".$row['clan_id'].""),0,0);
	ImageTTFText($img, 9, 0, $new_width+5, 72, $textcolor, $mainFont, "<" . $clan_name . "> " . "");
}
else ImageTTFText($img, 9, 0, $new_width+5, 72, $textcolor, $mainFont, "X-ray" . "");
 
ImageTTFText($img, 8, 0, $new_width+25, 10, $textcolor, $mainFont, $reason);
for($n = 1; $n <= 6; $n++)
{
	$w = $sta[$n];
	$texu = "$w";
	$bbo = ImageTTFBBox(9, 0, $mainFont, $texu);
	ImageTTFText($img, 9, 0, ImageSX($img) - $bbo[2] - 110, 3 + ($n*11), $secondaryTextColor, $mainFont, win2uni($texu));
}	
for($n = 1; $n <= 6; $n++)
{
	$nam = $staName[$n];
	$texu2 = "$nam";
	$bbo2 = ImageTTFBBox(9, 0, $mainFont, $texu2);
	ImageTTFText($img, 9, 0, ImageSX($img) - $bbo2[2] - 144, 3 + ($n*11), $secondaryTextColor, $mainFont, win2uni($texu2));
}	

for($i = 1; $i <= 6; $i++)
{
	$v = $stat[$i];
	$text = "$v";
	$bbox = ImageTTFBBox(9, 0, $mainFont, $text);
	ImageTTFText($img, 9, 0, ImageSX($img) - $bbox[2] - 68, 3 + ($i*11), $secondaryTextColor, $mainFont, win2uni($text));
}
for($i = 1; $i <= 6; $i++)
{
	$name = $statName[$i];
	$text2 = "$name";
	$bbox2 = ImageTTFBBox(9, 0, $mainFont, $text2);
	ImageTTFText($img, 9, 0, ImageSX($img) - 60, 3 + ($i*11), $secondaryTextColor, $mainFont, win2uni($text2));
}
	
$filename = $char_name;
$ext = "png";
header("Content-disposition: inline; filename=$filename.$ext");
header("content-type: image/$ext");
header("cache-control: max-age=86400");
ImagePNG($img);

}
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<FORM ACTION="index.php" METHOD="GET">
<INPUT name="name" TYPE="text" VALUE="Введите Ваш ник...">
<INPUT TYPE="submit" VALUE="Получить код*">
</FORM>
* - код скопируете из адресной строки браузера! Для получения подписи на форуме необходимо вставить полученную ссылку между [IMG] и [/IMG]
<?
}
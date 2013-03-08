<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/lib/town.php")
{
	die();
}

//ob_start('ob_gzhandler',9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
$start_time = StartTiming();

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '9');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');

$select=myquery("select town from game_map where xpos=".$char['map_xpos']." and ypos=".$char['map_ypos']." and name=".$char['map_name']." AND to_map_name=0 AND town>0");
if(mysql_num_rows($select) <= 0)
{
	header('Location: ../act.php');
	die();
}

list($town) = mysql_fetch_array($select);

$sel = myquery("SELECT * FROM game_gorod WHERE town=$town");
$gorod = mysql_fetch_array($sel);
$rustown = $gorod['rustown'];
$opis = $gorod['opis'];
$clan = $gorod['clan'];
$race = $gorod['race'];
if($rustown == "" || $gorod['vhod'] == "")
{
	header('Location: ../act.php');
	die();
}
if($clan=='' or $clan==0) $clan=$char['clan_id'];
if($race==0) $race=$char['race'];

if($char['clan_id']==$clan and $char['race']==$race)
{}
else
{
	header('Location: ../act.php');
	die();
}

$dostup=1;
$race = myquery("SELECT race FROM game_har WHERE id=".$char['race']."");
if (mysql_num_rows($race))
{
	list($race) = mysql_fetch_array($race);
	$race = 'enter_'.$race;
	if (!isset($gorod[$race])) $dostup=1;
	elseif ($gorod[$race]!='1') $dostup=0;
}
if ($dostup!=1)
{
	header('Location: ../act.php');
	die();
}
	
set_delay_reason_id($user_id,2);
?>
<html>
<head>
	<? 
require_once('../inc/engine.inc.php');
echo"<title>".GAME_NAME."</title>";
?>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="">
<meta name="Keywords" content="">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<script language="JavaScript" type="text/javascript" src="../js/cookies.js"></script>
<?php
?>
<script type="text/javascript">
/* URL to the PHP page called for receiving suggestions for a keyword*/
var getFunctionsUrl = "../suggest/suggest.php?keyword=";
</script>
<script type="text/javascript" src="../suggest/suggest.js"></script>
<body>
<?php
echo '<table cellspacing="1" cellpadding="1">
<tr>
	<td style="width:530px;" valign="top">
		<div style="text-align:center;text-weight:900;font-size:14px;color:gold">Ты находишься в городе '.strtoupper($gorod['rustown']).'</div>';
		$center=str_replace('&option', "lib/town.php?option" ,$gorod['center']);
		echo $center;
		echo'
		<table cellspacing=0 cellpadding=0 width="100%" align=center border=1 bgcolor="'.$gorod['color'].'">
		<tr>
			<td style="text-align:center">
			Городские новости:
			</td>
		</tr>
		<tr>
			<td bgcolor=000000 align="center">';
			echo nl2br(stripslashes($gorod['news']));
			$mag=myquery("select * from game_tavern where town=$town and vladel=".$user_id."");
			if (mysql_num_rows($mag))
			{
				echo'<br>Ты владеешь таверной этого города! Тебе разрешено изменять новости города:';
				include('../inc/gorod/news.inc.php');
			}
			echo '
			</td>
		</tr>
		</table>
	</td>
	<td valign="top" width="100%" align="center">
	<table cellspacing=0 cellpadding=0 width="100%" align=center border=1 bgcolor="'.$gorod['color'].'">
	<tr bgcolor=000000>
		<td style="padding-top:3px;padding-bottom:3px;text-align:center">';
		$selopt = myquery("SELECT * FROM game_gorod_set_option WHERE gorod_id=$town");
		while ($opt = mysql_fetch_array($selopt))
		{
			$opt_id = $opt['option_id'];
			list($nameopt)=mysql_fetch_array(myquery("SELECT name FROM game_gorod_option WHERE id=$opt_id"));
			echo '[<a href=town.php?option='.$opt_id.'>'.preg_replace("/ /","&nbsp;",$nameopt).'</a>] ';
		}
		echo '[<a href="../act.php">Выйти&nbsp;из&nbsp;города</a>]';
		echo '</td>
	</tr>
	</table>';
	if (!isset($_GET['option']))
		$option = 0;
	else
		$option = (int)$_GET['option'];

	$check = myquery("SELECT * FROM game_gorod_set_option WHERE gorod_id=$town AND option_id=$option");
	if (mysql_num_rows($check) > 0)
	{
		list($file) = mysql_fetch_array(myquery("SELECT link FROM game_gorod_option WHERE id=$option"));
		/* Variables to use:
			$town - town id;
			$clan - owner clan;
			$rustown
			.......
		 */
		include('../inc/gorod/'.$file);
	}
	echo '
	</td>
</tr>
</table>';
echo '</body></html>';

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug();
?>
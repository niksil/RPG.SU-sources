<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

require('inc/template.inc.php');
require('inc/template_header.inc.php');
require('inc/lib_events.inc.php');
echo '<meta http-equiv="refresh" content="30">';
OpenTable('title');
echo '<img src="http://'.IMG_DOMAIN.'/nav/game.gif" align=right>';

if (!empty($reason))
{
    include('inc/template_reason.inc.php');
}

echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td>
<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top">';
include('inc/template_nav.inc.php');
echo '</td><td valign="top" width="100%">';

list($map_name) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id='".$char['map_name']."'"));
if ($map_name=='Море')
	{
	$sel=myquery("select * from game_port_bil where user_id='".$char['user_id']."' and stat='2'");
	$q=mysql_fetch_array($sel);
	
	$sell=myquery("select * from game_port where id='".$q['bil']."'");
	$qq=mysql_fetch_array($sell);

	echo'<b>Вы плывете в <font color=ff0000>'.$qq['kuda'].'</font>!<br>Прибытие ровно в: <font color=ff0000>'.$qq['dlit'].'</font><br>Сейчас:  <font color=ff0000>'.date("H:i").'</font>';

	if (date("H:i")>=$qq['dlit'])
	{
	echo'<br><br><font color=ff0000 size=3><b>Вы прибыли!!!</b></font>';
	$up=myquery("update game_users_map set map_name='".$qq['kuda']."', map_xpos='".$qq['kuda_x']."', map_ypos='".$qq['kuda_y']."' where user_id='".$char['user_id']."'");
	$up=myquery("delete from game_port_bil where user_id='".$char['user_id']."'");
	}

}



include('inc/template_choose.inc.php');
include('inc/template_local.inc.php');
include('inc/template_dropped.inc.php');
echo '</td><td valign="top" width="150"><div align="right">';

echo '</div></td><td valign="top"><div align="right">';
echo '</div></td></tr><tr>
<td valign="top" height="35">&nbsp;</td>
<td valign="top" width="100%" height="35">';

echo'</td>
<td valign="top" width="150" height="35">&nbsp;</td>
<td valign="top" height="35">&nbsp;</td>
</tr></table>';

echo'</div></td><td valign="top">';
OpenTable('close');
echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';
if($char['delay_reason']!=8) 
	set_delay_reason_id($user_id,1);


if (function_exists("save_debug")) save_debug(); 

?>
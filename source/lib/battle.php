<?php
if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (function_exists("start_debug")) start_debug(); 


include_once('inc/template.inc.php');


if (isset($option) AND $option == 'chat')
{
   if (!empty($voice))
        {
    		$voice = htmlspecialchars($voice);
        	$voice = strip_tags($voice);
        	$voice = mysql_real_escape_string($voice);
			$userban=myquery("select * from game_ban where user_id='".$char['user_id']."' and type=2 and time>'".time()."'");
			if (mysql_num_rows($userban))
			{
				echo 'На тебя наложено проклятие. Тебе запрещено разговаривать.';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			$result = myquery("INSERT game_chat (name, map_name, map_xpos, map_ypos, contents, post_time) VALUES ('".$char['name']."', '".$char['map_name']."', ".$char['map_xpos'].", ".$char['map_ypos'].", '$voice', '" . time() . "')");
        }
}
require('inc/template_header.inc.php');
echo '
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td valign="top">';

OpenTable('title');
echo '<div align="right"><img src="http://'.IMG_DOMAIN.'/nav/battle.gif" align=right></div>';
if (!empty($reason))
{
    include('inc/template_reason.inc.php');
}

if($char['delay_reason']!=12)
set_delay_reason_id($char['user_id'],21);


echo '
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td valign="top">';

include('inc/template_nav2.inc.php');

echo '</td><td valign="top" width="100%">';
include('inc/template_chat.inc.php');

echo'<br>';
QuoteTable('open');
include('spt.php');
QuoteTable('close');

echo'<br>';
include('inc/template_dropped.inc.php');

echo '
</td>
</tr>
</table>
</div>';

OpenTable('close');

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');

echo '</td></tr></table>';

if (function_exists("save_debug")) save_debug(); 

?>
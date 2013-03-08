 <?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('index.php');
}
else
{
	$online_range = time()-300;
	$player_list = array();
	$result = myquery("SELECT name, MAX(post_time) AS max_time FROM game_chat WHERE map_name='" . $char['map_name'] . "' AND map_xpos='" . $char['map_xpos'] . "' AND map_ypos='" . $char['map_ypos'] . "' AND post_time>$online_range GROUP BY name ORDER BY max_time DESC");
	if (mysql_num_rows($result) > 0)
	{
		while ($player = mysql_fetch_array($result))
		{
			$result_check = myquery("SELECT user_id, avatar, clevel FROM game_users WHERE name='" . $player['name'] . "' and user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."') and user_id in (SELECT user_id FROM game_users_active WHERE last_active>$online_range) LIMIT 1");
			if (mysql_num_rows($result_check) == 1)
			{
				$player_list[] = $player['name'];
				$player_avatar = mysql_fetch_array($result_check);
				$result_messages = myquery("SELECT contents FROM game_chat WHERE name='" . $player['name'] . "' AND map_name='" . $char['map_name'] . "' AND map_xpos='" . $char['map_xpos'] . "' AND map_ypos='" . $char['map_ypos'] . "' ORDER BY post_time DESC LIMIT 6");
	echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/quote_ul.gif" width="15" height="7" border="0" alt=""></td>
	<td background="http://'.IMG_DOMAIN.'/nav/quote_tp.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/quote_ur.gif" width="15" height="7" border="0" alt=""></td></tr><tr>
	<td><div align="center"><img src="http://'.IMG_DOMAIN.'/avatar/' . $player_avatar['avatar'] . '" border="0" alt="' . $player['name'] . '"><br><font size="1">' . $player['name'] . ' [' . $player_avatar['clevel'] . ']</div></td>';
	echo '<td background="http://'.IMG_DOMAIN.'/nav/quote_lt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td><td>';
				$i = 0;
				while ($player_messages = mysql_fetch_array($result_messages))
				{
					switch ($i)
					{
						case 0:
							$font_color = 'eeeeee';
							break;
						case 1:
							$font_color = 'c6c6c6';
							break;
						case 2:
							$font_color = 'bbbbbb';
							break;
						case 3:
							$font_color = '969696';
							break;
						case 4:
							$font_color = '888888';
							break;
						case 5:
							$font_color = '666666';
							break;
						case 6:
							$font_color = '555555';
							break;
					}
					$i++;
					echo '<font size="1" color="#' . $font_color . '">&gt; ' . $player_messages['contents'] . '</font><br>';
				}
				mysql_free_result($result_messages);
				echo '</td><td background="http://'.IMG_DOMAIN.'/nav/quote_rt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
				</tr><tr><td></td><td><img src="http://'.IMG_DOMAIN.'/nav/quote_dl.gif" width="15" height="7" border="0" alt=""></td>
				<td background="http://'.IMG_DOMAIN.'/nav/quote_bt.gif"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" border="0"></td>
				<td><img src="http://'.IMG_DOMAIN.'/nav/quote_dr.gif" width="15" height="7" border="0" alt=""></td>
				</tr></table><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="1" height="1" vspace="5" border="0"><br>';
			}
			mysql_free_result($result_check);
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>
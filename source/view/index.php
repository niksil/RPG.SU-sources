<?php
//ob_start("ob_gzhandler",9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');


if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '11');
}
else
{
	die();
}

if (function_exists("start_debug")) start_debug();

$admin = false;
$keeper = false;
$guest = true;
if (isset($_COOKIE['rpgsu_sess']) AND isset($_COOKIE['rpgsu_login']) AND isset($_COOKIE['rpgsu_pass']))
{
	require('../inc/lib_session.inc.php');
	$sel = mysql_result(myquery("SELECT COUNT(*) FROM game_prison WHERE user_id=$user_id"),0,0);
	if ($sel>0) die('Ты на каторге!');
	
	set_delay_reason_id($user_id,27);

	$seladmin = myquery("select * from game_admins where user_id='$user_id'");
	if ($seladmin!=false AND mysql_num_rows($seladmin)>0)
	{
		$keeper = true;
	}
	$guest = false;
	if ($char['clan_id']==1)
	{
		$admin = true;
	}
}
else
{
	$char = array();
	$char['name'] = 'Гость';
	$char['user_id'] = 0;
	$char['clan_id'] = 0;
	$char['clevel'] = 0;
}

  
?>
<title><? echo GAME_NAME; ?> :: <? echo DOMAIN; ?>/view&#8482;</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">
@import url("global.css");.style1 {color: #FFFFFF}
</style>
<table width="600" height=10 border="0" cellspacing="0" cellpadding="0" align=center>
<tr><td width="15"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_01.jpg" width="15" height="33"></td>
<td width="23" background="http://<? echo IMG_DOMAIN; ?>/nav/1_03.jpg"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_04_1.jpg" width="70" height="33"></td>
<td width="879" background="http://<? echo IMG_DOMAIN; ?>/nav/1_03.jpg"><div align="center"><br>
<span class="style1"><? echo DOMAIN; ?>/view&#8482;
<? 
	if($guest)
	{
		echo ' Гость';
	}		
	if($admin)
	{
		echo ' Доступ админа';
	}
	else
	{
		if($keeper)
		{
			echo ' Доступ стража';
		} 
	}
?>
</span></div></td>
<td width="70" background="http://<? echo IMG_DOMAIN; ?>/nav/1_03.jpg"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_04.jpg" width="70" height="33"></td>
<td width="18"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_05.jpg" width="15" height="33"></td>
</tr>
<tr><td width="15" height="50" background="http://<? echo IMG_DOMAIN; ?>/nav/1_16.jpg"></td>
<td colspan="3"><div align="center">

<table width="99%" border="0" cellspacing="0" cellpadding="0">
<tr><td width="8" height="8"><img src="http://<? echo IMG_DOMAIN; ?>/nav/2_01.jpg" width="8" height="8"></td>
<td colspan="2" background="http://<? echo IMG_DOMAIN; ?>/nav/2_02.jpg"></td>
<td width="8"><img src="http://<? echo IMG_DOMAIN; ?>/nav/2_04.jpg" width="8" height="8"></td>
</tr><tr>
<td background="http://<? echo IMG_DOMAIN; ?>/nav/2_05.jpg"></td>
<td width="244" bgcolor="#000000"><div align="left">
			  &nbsp;<a href=?name>Информация об игроке</a><br>              
			  &nbsp;<a href=?zakon>Законы</a><br>
			  &nbsp;<a href=?map>Карта</a> <br>
			  &nbsp;<a href=?clan>Кланы</a> <br>
			  &nbsp;<a href=?work>Заработок</a> <br>
			  &nbsp;<a href=?top>Рейтинг :: Топ 10 </a> <br>
			  &nbsp;<a href=?help>Помощь</a> <br>
			  &nbsp;<a href=?log>Логи боев</a> <br>
			  &nbsp;<a href=?exp>Таблица опыта</a> <br>
				&nbsp;<a href=?stat>Статистика</a> <br>
</div></td><td width="301" align="right" valign="bottom" bgcolor="#000000"><img src="logo.jpg" width="285" height="91"></td>
	  <td background="http://<? echo IMG_DOMAIN; ?>/nav/2_07.jpg"></td>
	</tr>
	<tr>
	  <td><img src="http://<? echo IMG_DOMAIN; ?>/nav/2_10.jpg" width="8" height="8"></td>
	  <td colspan="2" background="http://<? echo IMG_DOMAIN; ?>/nav/2_11.jpg"></td>
	  <td><img src="http://<? echo IMG_DOMAIN; ?>/nav/2_13.jpg" width="8" height="8"></td>
	</tr>
  </table>

</td>
<td background="http://<? echo IMG_DOMAIN; ?>/nav/333_17.jpg">&nbsp;</td>
</tr><tr><td width="15"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_23.jpg" width="15" height="14"></td>
<td colspan="3" background="http://<? echo IMG_DOMAIN; ?>/nav/1_25.jpg"></td><td><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_26.jpg" width="15" height="14"></td></tr></table>
<br>
<table border="0" cellspacing="0" cellpadding="0" align=center>
  <tr>
	<td width="15" height="33"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_01.jpg" width="15" height="33"></td>
	<td width="70" height="33" background="http://<? echo IMG_DOMAIN; ?>/nav/1_03.jpg"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_04_1.jpg" width="70" height="33"></td>
		  <?php
		  //if (isset($map)) 
		  //{
			  echo '<td width="800" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg">';
		  //}
		  //elseif (isset($userid) OR isset($name)) 
		  //{
		  //    echo '<td width="800" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg">';
		  //}
		  //else 
		  //{
			//  echo '<td width="440" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg">'; 
		  //} 
		  ?>          
	</td>
	<td width="70" height="33" background="http://<? echo IMG_DOMAIN; ?>/nav/1_03.jpg"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_04.jpg" width="70" height="33"></td>
	<td width="15" height="33"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_05.jpg" width="15" height="33"></td>
  </tr>
  <tr>
	<td width="15" height="50" background="http://<? echo IMG_DOMAIN; ?>/nav/1_16.jpg"></td>
	<td colspan="3"><div align="center">
	  <table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
		<tr>
		  <td width="5" height="6"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_07.jpg" width="5" height="6"></td>
		  <td width="100%" height="6" background="http://<? echo IMG_DOMAIN; ?>/nav/1_09.jpg"></td>
		  <td width="7" height="6"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_10.jpg" width="7" height="6"></td>
		</tr>
		<tr>
		  <td width="7" height=100% background="http://<? echo IMG_DOMAIN; ?>/nav/1_15.jpg"></td>
		  <?php
		  //if (isset($map)) 
		  //{
			  echo '<td width=100% height=100% bgcolor="313131">';
		  //}
		  //elseif (isset($userid) OR isset($name)) 
		  //{
		  //    echo '<td width=100% height=100% bgcolor="313131">';
		  //}
		  //else 
		  //{
			//  echo '<td width=540 height=100% bgcolor="313131">'; 
		  //}           
				if (isset($log))
				{
					include('inc/log.inc.php');
				}
			elseif (isset($name) or isset($userid))
			{
					include('inc/user.inc.php');
				}
				elseif (isset($map))
				{
					include('inc/map.inc.php');
				}
				elseif (isset($top))
				{
					include('inc/top.inc.php');
				}
				elseif (isset($zakon))
				{
					include('inc/zakon.inc.php');
				}
				elseif (isset($help))
				{
					include('inc/help.inc.php');
				}
				elseif (isset($clan))
				{
					include('inc/clan.inc.php');
				}
				elseif (isset($work))
				{
					include('inc/work.inc.php');
				}
				elseif (isset($exp))
				{
					include('inc/exp.inc.php');
				}
				elseif (isset($topclan))
				{
					include('inc/top_clan.inc.php');
				}
				elseif (isset($topmain))
				{
					include('inc/top_main.inc.php');
				}
				elseif (isset($topcombat))
				{
					include('inc/top_combat.inc.php');
				}
				elseif (isset($topcraft))
				{
					include('inc/top_craft.inc.php');
				}
				elseif (isset($stat))
				{
					include('inc/stat.inc.php');
				}
				elseif (isset($journal))
				 {
					include('inc/q_journal.php');
				}
				else
				{
					$help='';
					include('inc/help.inc.php');
				}

		  ?>
		  </td>
		  <td width="5" height=100% background="http://<? echo IMG_DOMAIN; ?>/nav/1_17.jpg"></td>
		</tr>
		<tr>
		  <td width="5" height="8" ><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_19.jpg" width="5" height="8"></td>
		  <td width=100% height="8" background="http://<? echo IMG_DOMAIN; ?>/nav/1_20.jpg" height="8"></td>
		  <td width="7" height="8" ><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_22.jpg" width="7" height="8"></td>
		</tr>
	  </table>
	  </div></td>
	<td background="http://<? echo IMG_DOMAIN; ?>/nav/333_17.jpg">&nbsp;</td>
  </tr>
  <tr>
	<td width="15"><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://<? echo IMG_DOMAIN; ?>/nav/1_25.jpg"></td>
	<td><img src="http://<? echo IMG_DOMAIN; ?>/nav/1_26.jpg" width="15" height="14"></td>
  </tr>
</table>
<center>
<?
include("../lib/banners.php");
echo '</center>';

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug(); 

?>
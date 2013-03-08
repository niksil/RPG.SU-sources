<?php
//ob_start('ob_gzhandler',9);
require_once('inc/engine.inc.php');
require('inc/xray.inc.php');
include('inc/lib.inc.php');
include('arcomage/inc/template.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '4');
}
else
{
	die();
}
require('inc/lib_session.inc.php');

//func
//1 - arcomage_boy
//2 - arcomage_wait
//3 - arcomage_lose
//4 - arcomage_win
//5 - arcomage_draw
//6 - arcomage_ojid
//7 - arcomage_podt
//8 - arcomage_otkaz
//9 - arcomage_net

if (function_exists("start_debug")) start_debug(); 


$check_boy = myquery("SELECT * FROM arcomage_users WHERE user_id=$user_id");
if (!mysql_num_rows($check_boy))
{
	//myquery("UPDATE game_users SET func='',arcomage=0,hod=0 WHERE user_id='$user_id'");
	ForceFunc($user_id,5);
	setLocation("act.php?error=arco1");
	{if (function_exists("save_debug")) save_debug(); die;}
}
else
{
	$charboy = mysql_fetch_array($check_boy);
}

$check_boy = myquery("SELECT * FROM arcomage WHERE arcomage.id=".$charboy['arcomage_id']."");
if (!mysql_num_rows($check_boy))
{
	myquery("DELETE FROM arcomage_users WHERE user_id='$user_id'");
	ForceFunc($user_id,5);
	setLocation("act.php?error=arco2");
	{if (function_exists("save_debug")) save_debug(); die;}
}
else
{
	$arcomage = mysql_fetch_array($check_boy);
}

$timeout=150;
//$timeout=15000;
$curtime=time()-$timeout;

if ($charboy['func']!=3 AND $charboy['func']!=4)
{
	$p = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users WHERE arcomage_id=".$charboy['arcomage_id']." AND user_id<>$user_id"),0,0);
	if ($p==0)
	{
		myquery("UPDATE arcomage_users SET func=4 WHERE user_id='$user_id'"); 
		setLocation("arcomage.php");  
		die(); 
	}
	$select=myquery("select user_id from arcomage_users where hod<$curtime and arcomage_id=".$charboy['arcomage_id']."");
	while (list($out)=mysql_fetch_array($select))
	{
		myquery("UPDATE arcomage_users SET func=3 WHERE user_id='$out'");
		if ($charboy['user_id']==$out) $charboy['func']=3;
		list($prot_hod,$prot_id) = mysql_fetch_array(myquery("SELECT hod,user_id FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id<>'$out'"));
		if ($prot_hod<$curtime)
		{
			myquery("UPDATE arcomage_users SET func=3 WHERE user_id='$prot_id'");
			if ($charboy['user_id']==$prot_id) $charboy['func']=3;
		}
		else
		{
			myquery("UPDATE arcomage_users SET func=4 WHERE user_id='$prot_id'");
			if ($charboy['user_id']==$prot_id) $charboy['func']=4;
		}
	}
}

switch($charboy['func'])
{
	case 1:
		include("arcomage/inc/otpravka.inc.php");
		include("arcomage/inc/boy.inc.php");
	break;

	case 2:
		include("arcomage/inc/wait.inc.php");
		require('arcomage/inc/template_header.inc.php');
		echo'
		<table width="100%" height="550" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td width="50%" height="550" valign="top" bgcolor="#000000">';

		include("arcomage/inc/left.inc.php");
		
		echo '<td align="center" valign="top" bgcolor="#000000" width="640" height="550">
		<div align="center">
		До конца хода осталось: <font color=ff0000><b><span id="timerr1">'.($arcomage['timehod']-time()+$timeout).'</span></b></font> секунд</div>
		<script language="JavaScript" type="text/javascript">
		function tim()
		{
			timer = document.getElementById("timerr1");
			if (timer.innerHTML<=0)
				location.reload();
			else
			{
				timer.innerHTML=timer.innerHTML-1;
				window.setTimeout("tim()",1000);
			}
		}
		tim();
		</script>
		</div>
		<div style="position:relative;">
		<img src="http://'.IMG_DOMAIN.'/arcomage/layout.jpg">';

		echo '<span style="width:640; position:absolute; left:0; top:30; "><center>
		<meta http-equiv="refresh" content="15">Ожидание хода противника<br>
		<input type="button" value="Обновить" onClick="location.reload()"><br /><br /><font color=#FFFFFF face="Verdana" size=2><b>Ход игры: <font color=#FF0000>'.$arcomage['hod'].'</font></b></font><br><font color=#FFFFFF face="Verdana">Условия победы: <br>1) уничтожить башню противника; <br>2) построить свою башню до <font color=#FF0000>'.$arcomage['tower_win'].'</font> единиц; <br>3) накопить любого ресурса до <font color=#FF0000>'.$arcomage['resource_win'].'</font> единиц</font>';
		if ($arcomage['money']>0) echo '<br>Ставка на игру: <font color=#FF0000><b>'.$arcomage['money'].'</b></font> монет.';
		echo '</span>';
		?>
		<script language="JavaScript" type="text/javascript">
		function put_card_wait(IMG_DOMAIN,card,k,alt_card,left,top,deltaY,deltaX)
		{
			document.write('<img border="0" width="94" height="126" src="http://'+IMG_DOMAIN+'/arcomage/card'+card+'.jpg"  style="position:absolute; left:'+(left+k*deltaX)+'px; top:'+top+'px;"  alt='+alt_card+'" title="'+card+'">');
		}
		</script>
		<?php 
		//покажем сходившие карты
		$sel_cards = myquery("SELECT card_id FROM arcomage_history WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id='$user_id' AND hod='".$arcomage['hod']."' AND fall=0");
		$k=0;
		while (list($card) = mysql_fetch_array($sel_cards))
		{
			?><script>put_card_wait("<?=IMG_DOMAIN;?>",<?=$card;?>,<?=$k;?>,"<?=htmlspecialchars(alt_card($card));?>",25,150,120,120);</script><?php
			$k++;
		}

		echo '</td>
		<td width="50%" height="550" valign="top" bgcolor="#000000">';
		include("arcomage/inc/right.inc.php");
		echo'</td>
		</tr></table>';
		//Обновим время последнего действия в аркомаге чтобы не выкинуло сходившего по тайму
		myquery("UPDATE arcomage_users SET hod=".time()." WHERE user_id=$user_id");
	break;

	case 4:
		require('arcomage/inc/template_header.inc.php');
		include("arcomage/inc/win.inc.php");
	break;

	case 3:
		require('arcomage/inc/template_header.inc.php');
		include("arcomage/inc/lose.inc.php");
	break;

	case 5:
		require('arcomage/inc/template_header.inc.php');
		include("arcomage/inc/draw.inc.php");
	break;

	case 6:
		require('arcomage/inc/template_header.inc.php');
		if (isset($no))
		{
			list($prot) = mysql_fetch_array(myquery("SELECT user_id FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id<>'$user_id'"));
			$update=myquery("update arcomage_users set func=9 where user_id='".$char['user_id']."'");
			$update=myquery("update arcomage_users set func=8 where user_id='".$prot."'");
			echo'<meta http-equiv="refresh" content="1">';
		}
		else
		{
			$uptime = time();
			$update=myquery("update arcomage_users set hod=$uptime where arcomage_id='".$charboy['arcomage_id']."'"); 
			$update=myquery("update arcomage set timehod=$uptime where id='".$charboy['arcomage_id']."'");
			echo'<center>Ожидание подтверждения противника<meta http-equiv="refresh" content="10"><br><input type="button" value="Отказаться от вызова на игру в Две Башни" OnClick=location.href="arcomage.php?no">';
		}
	break;

	case 7:
		require('arcomage/inc/template_header.inc.php');
		if (isset($ok))
		{
			myquery("update arcomage_users set func=1 where arcomage_id='".$charboy['arcomage_id']."'");
			myquery("delete from arcomage_call where user_id in (select user_id from arcomage_users where arcomage_id='".$charboy['arcomage_id']."')");
			setLocation("arcomage.php");
		}
		elseif (isset($no))
		{
			$prot = mysql_fetch_array(myquery("SELECT user_id FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id<>'$user_id'"));
			myquery("update arcomage_users set func=9 where user_id='".$char['user_id']."'");
			myquery("update arcomage_users set func=8 where user_id='".$prot['user_id']."'");
			echo'<meta http-equiv="refresh" content="1">';
		}
		else
		{
			$prot = mysql_fetch_array(myquery("SELECT game_users.name,game_users.clevel,game_users.clan_id,game_har.name AS race,game_users.user_id FROM game_users,game_har,arcomage_users WHERE arcomage_users.arcomage_id='".$charboy['arcomage_id']."' AND arcomage_users.user_id<>'$user_id' AND game_users.user_id=arcomage_users.user_id AND game_users.race=game_har.id"));
			echo'<center>Тебя вызвали на игру в Две Башни.<br>';
			echo'Твой противник: '.$prot['name'].' ('.$prot['race'].' '.$prot['clevel'].' уровня)';
			if ($prot['clan_id']!=0) echo ' <img src="http://'.IMG_DOMAIN.'/clan/'.$prot['clan_id'].'.gif">';

			echo'<br><br>'.echo_sex('Согласен','Согласна').' ли ты на игру?<br><br><br>
			<input type="button" value="&nbsp;&nbsp;&nbsp;Да&nbsp;&nbsp;&nbsp;" OnClick=location.href="arcomage.php?ok">
			<input type="button" value="&nbsp;&nbsp;&nbsp;Нет&nbsp;&nbsp;&nbsp;" OnClick=location.href="arcomage.php?no">
			<meta http-equiv="refresh" content="10">';
		}
	break;

	case 8:
		require('arcomage/inc/template_header.inc.php');
		//myquery("update game_users set func='',arcomage=0,hod=0 where user_id=$user_id");
		myquery("delete from arcomage where id='".$charboy['arcomage_id']."'");
		myquery("delete from arcomage_users where arcomage_id='".$charboy['arcomage_id']."'");
		myquery("delete from arcomage_users_cards where arcomage_id='".$charboy['arcomage_id']."'");
		ForceFunc($user_id,5);
		echo'<center>Противник отказался от игры<br><input type="button" value="Вернуться" onClick=location.replace("act.php")>';
	break;

	case 9:
		require('arcomage/inc/template_header.inc.php');
		//myquery("update game_users set func='',arcomage=0,hod=0 where user_id=$user_id");
		myquery("delete from arcomage where id='".$charboy['arcomage_id']."'");
		myquery("delete from arcomage_users where arcomage_id='".$charboy['arcomage_id']."'");
		myquery("delete from arcomage_users_cards where arcomage_id='".$charboy['arcomage_id']."'");
		ForceFunc($user_id,5);
		echo'<center>Ты '.echo_sex('отказался','отказалась').' от игры<br><input type="button" value="Вернуться" onClick=location.replace("act.php")>';
	break;
}
?>
<script type="text/javascript" language="JavaScript">
function upchat()
{
	ch_fr = top.window.frames.chat;
	if (ch_fr)
	{
		bt = ch_fr.document.getElementById("select_game_chat");
		if (bt)
		{
			if (bt.style.display!="inline")
			{
				bt.style.display="inline";
			}
		}
		bt = ch_fr.document.getElementById("select_combat_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_arcomage_chat");
		if (bt)
		{
			if (bt.style.display!="inline")
			{
				bt.style.display="inline";
			}
		}
	}
}
upchat();
</script>
<?
if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
mysql_close();

if (function_exists("save_debug")) save_debug(); 

?>
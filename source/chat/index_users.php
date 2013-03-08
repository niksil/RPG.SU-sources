<?php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '8');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

//include('../inc/template_header.inc.php');
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
<style type="text/css">@import url("http://<?=DOMAIN;?>/style/global.css");</style>
</head>
<?php

$select=myquery("select * from game_chat_option where user_id='$user_id'");
$chato=mysql_fetch_array($select);

if (!isset($chato['font'])) $chato['font']='helvetica';
if (!isset($chato['color'])) $chato['color']='white';

$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
if (!mysql_num_rows($select)) {if (function_exists("save_debug")) save_debug(); exit;}

list($town)=mysql_fetch_array($select);

$is_mag = mysql_result(myquery("SELECT COUNT(*) FROM game_mag WHERE name='".$char['name']."' AND town=$town"),0,0);
if ($char['clan_id']==1) $is_mag=1;

switch($opt)
{
	case 'mag_info':
		echo '
		<title>Печать мага</title><style type="text/css">
			BODY {          FONT-WEIGHT: normal; BACKGROUND: #000000; MARGIN: 5px; COLOR: #c0c0c0;	scrollbar-face-color: #620706;
							scrollbar-shadow-color: #340403;
							scrollbar-highlight-color: #340403;
							scrollbar-3dlight-color: #620706;
							scrollbar-darkshadow-color: #620706;
							scrollbar-track-color: #1D1D1D;
							scrollbar-arrow-color: #FBF891;
				 }
		</style>';

		$mod=myquery("select name,town,status,mol,izgn,obn,slep,prok,teleport,lab from game_mag where town='$town' and name='$ma'");
		list($name,$town,$status,$mol,$izgn,$obn,$slep,$prok,$teleport,$lab)=mysql_fetch_array($mod);
		$admin = myquery("SELECT COUNT(*) FROM game_users WHERE name='$ma' AND clan_id=1");
		echo'<table border=0 width=100%>';
		if($mol=='1' or mysql_num_rows($admin)) echo'<tr><td width=30><img src="mag/mol.gif" border=0></td><td>Печать молчания</td></tr>';
		if($izgn=='1' or mysql_num_rows($admin)) echo'<tr><td width=30><img src="mag/izgn.gif" border=0></td><td>Печать изгнания</td></tr>';
		if($obn=='1' or mysql_num_rows($admin)) echo'<tr><td width=30><img src="mag/obn.gif" border=0></td><td>Печать обновления</td></tr>';
		if($slep=='1' or mysql_num_rows($admin)) echo'<tr><td width=30><img src="mag/slep.gif" border=0></td><td>Печать слепоты</td></tr>';
		if($prok=='1' or mysql_num_rows($admin)) echo'<tr><td width=30><img src="mag/prok.gif" border=0></td><td>Печать проклятия</td></tr>';
		//if($teleport=='1' or mysql_num_rows($admin)) echo'<tr width=30><td><img src="mag/teleport.gif" border=0></td><td>Печать телепортации</td></tr>';
		//if($lab=='1' or mysql_num_rows($admin)) echo'<tr><td width=30><img src="mag/lab.gif" border=0></td><td>Печать лабиринта</td></tr>';
		echo'</table>';
	break;

	case 'mag':
		if ($is_mag!=1) break;
		echo '<title>Печать мага</title><style type="text/css">
		BODY {          FONT-WEIGHT: normal; BACKGROUND: #000000; MARGIN: 5px; COLOR: #c0c0c0;	scrollbar-face-color: #620706;
						scrollbar-shadow-color: #340403;
						scrollbar-highlight-color: #340403;
						scrollbar-3dlight-color: #620706;
						scrollbar-darkshadow-color: #620706;
						scrollbar-track-color: #1D1D1D;
						scrollbar-arrow-color: #FBF891;
}
		</style>';
		$m=myquery("select name,town,status,mol,izgn,obn,slep,prok,teleport,lab from game_mag where town='$town' and name='".$char['name']."'");
		list($name,$town,$status,$mol,$izgn,$obn,$slep,$prok,$teleport,$lab)=mysql_fetch_array($m);

		if ($char['clan_id']=='1')  $mol='1'; $izgn='1'; $obn='1'; $slep='1'; $prok='1'; $town=$town; $name=$char['name'];

		if (!isset($p)) $p='';
		if (!isset($dei)) $dei='';
		
		if($p=='mol')
		{
			echo'<form action="index_users.php?opt=mag&fun=mol&ma='.$ma.'" method="post" name="form_mol"><font face=verdana size=2>Наложить печать молчания на: <font face=verdana size=2 color=ff0000><b>'.$ma.'</b></font></font><br><br>';
			echo '<select name="time">
			<option value="300">5 мин</option>
			<option value="900">15 мин</option>
			<option value="1800">30 мин</option>
			<option value="2700">45 мин</option>
			<option value="3600">1 час</option>
			</select>
			<br><br><input type="submit" value="Наложить печать"></form><br><br>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if($p=='obn')
		{
			echo '<form action="index_users.php?opt=mag&dei=obn" method="post" name="form_mol"><font face=verdana size=2>Использовать печать обновления</font><br><br>';
			echo '<input type="submit" name="obnov" value="Использовать печать"><br><br>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if($p=='izgn')
		{
			echo'<form action="index_users.php?opt=mag&fun=izgn&ma='.$ma.'" method="post" name="form_izgn"><font face=verdana size=2>Наложить печать изгнания на: <font face=verdana size=2 color=ff0000><b>'.$ma.'</b></font></font><br><br>';
			echo '<select name="time">
			<option value="300">5 мин</option>
			<option value="900">15 мин</option>
			<option value="1800">30 мин</option>
			<option value="2700">45 мин</option>
			<option value="3600">1 час</option>
			<option value="10800">3 часа</option>
			<option value="36000">10 часов</option>
			</select>
			<br><br><input type="submit" value="Наложить печать"></form><br><br>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if($p=='slep')
		{
			echo'<form action="index_users.php?opt=mag&fun=slep&ma='.$ma.'" method="post" name="form_slep"><font face=verdana size=2>Наложить печать слепоты на: <font face=verdana size=2 color=ff0000><b>'.$ma.'</b></font></font><br><br>';
			echo '<select name="time">
			<option value="300">5 мин</option>
			<option value="900">15 мин</option>
			<option value="1800">30 мин</option>
			<option value="2700">45 мин</option>
			<option value="3600">1 час</option>
			<option value="10800">3 часа</option>
			<option value="36000">10 часов</option>
			<option value="86400">24 часа</option>
			</select>
			<br><br><input type="submit" value="Наложить печать"></form><br><br>';
		{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if($p=='prok')
		{
			echo'<form action="index_users.php?opt=mag&fun=prok&ma='.$ma.'" method="post" name="form_prok"><font face=verdana size=2>Наложить печать проклятья на: <font face=verdana size=2 color=ff0000><b>'.$ma.'</b></font></font><br><br>';
			echo '<select name="time">
			<option value="300">5 мин</option>
			<option value="900">15 мин</option>
			<option value="1800">30 мин</option>
			<option value="2700">45 мин</option>
			<option value="3600">1 час</option>
			<option value="10800">3 часа</option>
			<option value="36000">10 часов</option>
			</select>
			<br><br><input type="submit" value="Наложить печать"></form><br><br>';
			{if (function_exists("save_debug")) save_debug(); exit;}
		}

		if ($dei=='obn')
		{
			$m=myquery("select name from game_mag where town='$town' and name='".$char['name']."' and obn='1'");
			if (mysql_num_rows($m) >'0' or $char['clan_id']=='1')
			{
				echo'<font face=verdana size=2>Наложил печать обновления</font><br><br>';
				$mol=myquery("DELETE FROM game_log WHERE town='$town'");
				$dat_gorod = 0;
				$soob='<img src="mag/obn.gif" border=0> Наложил печать обновления';
				$update_log=myquery("insert into game_log (town,fromm,too,message,date) values ('$town','".$char['user_id']."','','$soob','".time()."')");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
		}

		if (isset($fun))
		{
			if (isset($time))
			{
				$m=myquery("select name from game_mag where town='$town' and name='".$char['name']."'");
				if (mysql_num_rows($m) >'0' or $char['clan_id']=='1')
				{
					echo'<font face=verdana size=2>Ты накладываешь печать на: <font face=verdana size=2 color=ff0000><b>'.$ma.'</b></font><br> Время: '.($time/60).' минут</font><br><br>';
					$ma_id = @mysql_result(@myquery("SELECT user_id FROM game_users WHERE name='$ma'"),0,0);
		    
					$mol=myquery("insert into game_chat_nakaz (id,town,user_id,nakaz,date_nak,date_zak,mag) values ('','$town','$ma_id','$fun','".time()."','".($time+time())."','".$char['name']."')");

					if($fun=='mol') $mes='<img src="mag/mol.gif" border=0> Наложил печать молчания на '.$ma.' (Время: '.($time/60).' минут)';
					if($fun=='izgn') $mes='<img src="mag/izgn.gif" border=0> Наложил печать изгнания на '.$ma.' (Время: '.($time/60).' минут)';
					if($fun=='slep') $mes='<img src="mag/slep.gif" border=0> Наложил печать слепоты на '.$ma.' (Время: '.($time/60).' минут)';
					if($fun=='prok') 
					{
						$mes='<img src="mag/prok.gif" border=0> Наложил печать проклятья на '.$ma.' (Время: '.($time/60).' минут)';
						//$ban=myquery("insert into game_ban (user_id,time,ip,adm,za,type) values ('$ma_id',".($time+time()).",0,'".$char['name']."','','2')");
					}

					$update_log=myquery("insert into game_log (town,too,message,date,fromm) values ('$town','','$mes','".time()."','".$char['user_id']."')");
		    $da = getdate();
		    $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		     VALUES (
		     '".$char['name']."',
		     '".mysql_escape_string($fun)."',
		     '".time()."',
		     '".$da['mday']."',
		     '".$da['mon']."',
		     '".$da['year']."')")
			     or die(mysql_error());
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
			}
			{if (function_exists("save_debug")) save_debug(); exit;}
		}


		echo'<font face=verdana size=2>Наложить печать на: <font face=verdana size=2 color=ff0000><b>'.$ma.'</b></font></font><br><br>';
		echo'<table border=0 width=100%>';
		if($mol=='1') echo'<tr><td width=30><a href="index_users.php?opt=mag&p=mol&ma='.$ma.'"><img src="mag/mol.gif" border=0></a></td><td>Печать молчания</td></tr>';
		if($izgn=='1') echo'<tr><td width=30><a href="index_users.php?opt=mag&p=izgn&ma='.$ma.'"><img src="mag/izgn.gif" border=0></td><td>Печать изгнания</td></tr>';
		if($obn=='1') echo'<tr><td width=30><a href="index_users.php?opt=mag&p=obn&ma='.$ma.'"><img src="mag/obn.gif" border=0></td><td>Печать обновления</td></tr>';
		if($slep=='1') echo'<tr><td width=30><a href="index_users.php?opt=mag&p=slep&ma='.$ma.'"><img src="mag/slep.gif" border=0></td><td>Печать слепоты</td></tr>';
		if($prok=='1') echo'<tr><td width=30><a href="index_users.php?opt=mag&p=prok&ma='.$ma.'"><img src="mag/prok.gif" border=0></td><td>Печать проклятия</td></tr>';
		//if($teleport=='1') echo'<tr width=30><a href="index.php?opt=mag&p=tel&ma='.$ma.'"><td><img src="mag/teleport.gif" border=0></td><td>Печать телепортации</td></tr>';
		//if($lab=='1') echo'<tr><td width=30><a href="index.php?opt=mag&p=lab&ma='.$ma.'"><img src="mag/lab.gif" border=0></td><td>Печать лабиринта</td></tr>';
		echo'</table>';
	break;

	case 'users':
        if (!isset($chato['ref'])) $chato['ref']=10;
		if ($chato['ref']<10) $chato['ref']=10;
		echo '<meta http-equiv="refresh" content="'.$chato['ref'].'">';
		?>
		<style type="text/css">
		BODY {        FONT-WEIGHT: normal; FONT-SIZE: 12px; BACKGROUND: #223344; MARGIN: 5px; COLOR: #c0c0c0;	scrollbar-face-color: #620706;
				scrollbar-shadow-color: #340403;
				scrollbar-highlight-color: #340403;
				scrollbar-3dlight-color: #620706;
				scrollbar-darkshadow-color: #620706;
				scrollbar-track-color: #1D1D1D;
				scrollbar-arrow-color: #FBF891;
			}
		TD {FONT-SIZE: 13px; FONT-FAMILY: Verdana}
		A:link {FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #aaaaff; FONT-FAMILY: Verdana; TEXT-DECORATION: none}
		A:visited {        FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #aaaaff; FONT-FAMILY: Verdana; TEXT-DECORATION: none}
		</style><center>

		<br>

		<?
		echo '
			</center>';

		echo '<script language="JavaScript">
		function priv(name)
		{
			top.window.frames.chat.menu.document.form.chat_mess.focus();
			if (name.length>10) top.window.frames.chat.menu.document.form.too.size=name.length;
			if (name=="") name="Всем";
			top.window.frames.chat.menu.document.form.to.value=name;
			top.window.frames.chat.menu.document.form.too.value=name;
			top.window.frames.chat.menu.document.form.chat_mess.value=top.window.frames.chat.document.menu.form.chat_mess.value;
			if (name.length>10)
				top.window.frames.chat.menu.document.form.too.size=name.length;
			else
				top.window.frames.chat.menu.document.form.too.size=10;
		}
		function cha(name)
		{
			top.window.frames.chat.menu.document.form.chat_mess.focus();
			top.window.frames.chat.menu.document.form.chat_mess.value=name+", "+top.window.frames.chat.menu.document.form.chat_mess.value;
			top.window.frames.chat.menu.document.form.to.value="";
			top.window.frames.chat.menu.document.form.too.value="Всем";
			top.window.frames.chat.menu.document.form.too.size=10;

		}
		</script>';
		
		//Сначала выводим магов
		$mags=myquery("select * from game_chat_log where town='$town' and ((user_id IN (SELECT user_id FROM game_users WHERE clan_id=1)) OR (name IN (SELECT name FROM game_mag WHERE town='$town'))) and user_id IN (SELECT user_id FROM game_users_active WHERE last_active>=".(time()-300).")  ORDER BY `name` asc");
		if (mysql_num_rows($mags))
		{
			 echo'<font face="Verdana" size="2" color=ff0000><b>Маги:</b></font><br>';
			 echo '<font size="2" face="Verdana" color=ffffff>';
			 while($p=mysql_fetch_array($mags))
			 {
				 if ($char['name']==$p['name'])
				 {
					 echo '<img src="http://'.IMG_DOMAIN.'/nav/i.gif" onClick=window.open("'."index_users.php?opt=mag_info&ma=".$p['name']."".'","what","height=280,width=200") style="cursor:hand"> '.$p['name'].'&nbsp;<br>';
				 }
				 else
				 {
				 echo '<img src="http://'.IMG_DOMAIN.'/nav/i.gif" onClick=window.open("'."index_users.php?opt=mag_info&ma=".$p['name']."".'","what","height=280,width=200") style="cursor:hand"> <b><span style="cursor:hand" onClick="cha(\''.$p['name'].'\')">'.$p['name'].'</span><span style="cursor:hand" onClick="priv(\''.$p['name'].'\')">&nbsp;<img src="img/pr.gif"></span></b><br>';
				 }
			 }
			 echo'<br>';
		}

		$players=myquery("select * from game_chat_log where town='$town' and ((user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1)) AND (name NOT IN (SELECT name FROM game_mag WHERE town='$town'))) and user_id IN (SELECT user_id FROM game_users_active WHERE last_active>=".(time()-300).") ORDER BY `name` asc");
		if (mysql_num_rows($players))
		{
			 echo '<br><font face="Verdana" size="2" color=ff0000><b>Жители:</b></font>';
			 echo '<br><font size="2" face="Verdana" color=ffffff>';
			 while($chat_users=mysql_fetch_array($players))
			 {
				if ($chat_users['name']==$char['name'])
				{
					echo ''.$chat_users['name'].'&nbsp;<br>';
				}
				else
				{
					if ($is_mag == 1)
					{
						echo '<img src="mag/i.gif" onClick=window.open("'."index_users.php?opt=mag&ma=".$chat_users['name']."".'","what","height=310,width=250") style="cursor:hand"> <b><span style="cursor:hand" onClick="cha(\''.$chat_users['name'].'\')">'.$chat_users['name'].'</span><span style="cursor:hand" onClick="priv(\''.$chat_users['name'].'\')">&nbsp;<img src="img/pr.gif"></span></b><br>';
					}
					else
					{
						echo '<b><span style="cursor:hand" onClick="cha(\''.$chat_users['name'].'\')">'.$chat_users['name'].'</span><span style="cursor:hand" onClick="priv(\''.$chat_users['name'].'\')">&nbsp;<img src="img/pr.gif"></span></b><br>';
					}
				}
			 }
		}
		
		if (!mysql_num_rows($mags) AND !mysql_num_rows($players)) echo '<center><font face="Verdana" size="2">Здесь никого нет</font></center>';

		//проверка наказаний
		myquery("DELETE FROM game_chat_nakaz WHERE date_zak<".time()."");
		
		//echo '<script>alert('.$dat_gorod.');</script>';
		
		$die = 0;
		$str='';
		$slep=myquery("select * from game_chat_nakaz where town='$town' and user_id='$user_id' and nakaz='slep'");
		if (mysql_num_rows($slep))
		{
			$str = '<table border=0><tr><td><img src=\"mag/slep.gif\" border=0></td><td>На тебя наложена печать слепоты</td></tr></table>';
			echo '<script>top.window.frames.chat.index.document.getElementById("chat_text").innerHTML="'.$str.'"</script>';
			$die = 1;
		}

		$str='';
		$mol=myquery("select * from game_chat_nakaz where town='$town' and user_id='$user_id' and nakaz='mol'");
		if (mysql_num_rows($mol))
		{
			if (isset($_POST['chat_mess']))
			{
				$str = '<table border=0><tr><td><img src=\"mag/mol.gif\" border=0></td><td>На тебя наложена печать молчания</td></tr></table>';
				echo '<script>top.window.frames.chat.index.document.getElementById("chat_text").innerHTML="'.$str.'"+top.window.frames.chat.index.document.getElementById("chat_text").innerHTML</script>';
				unset($_POST['chat_mess']);
			}
		}

		$izgn=myquery("select * from game_chat_nakaz where town='$town' and user_id='$user_id' and nakaz='izgn'");
		if (mysql_num_rows($izgn))
		{
			$str='<table border=0><tr><td><img src=\"mag/izgn.gif\" border=0></td><td>На тебя наложена печать изгнания</td></tr></table>';
			echo '<script>top.window.frames.chat.index.document.getElementById("chat_text").innerHTML="'.$str.'"</script>';
			$del=myquery("delete from game_chat_log where user_id='$user_id'");
			echo"<script>top.window.location.replace(\"../act.php\");</script>";
			$die = 1;
		}

		$prok=myquery("select * from game_chat_nakaz where user_id='$user_id' and nakaz='prok'");
		if (mysql_num_rows($prok))
		{
			$str = '<table border=0><tr><td><img src=\"mag/prok.gif\" border=0></td><td>На тебя наложена печать проклятия</td></tr></table>';
			echo '<script>top.window.frames.chat.index.document.getElementById("chat_text").innerHTML="'.$str.'"</script>';
			$del=myquery("delete from game_chat_log where user_id='$user_id'");
			echo"<script>top.window.location.replace(\"../act.php\");</script>";
			$die = 1;
		}
		
		if ($die == 1) die();
		
		
		//Запиiем новое сообщение в базу
		if (isset($_POST['chat_mess']))
		{
			if ($_POST['chat_mess']!='')
			{
				$chat_mess = htmlspecialchars($_POST['chat_mess']);
				if ($chato['b']) $chat_mess='<b>'.$chat_mess.'</b>';
				if ($chato['i']) $chat_mess='<i>'.$chat_mess.'</i>';
				$chat_mess='<font color="'.$chato['color'].'">'.$chat_mess.'</font>';
				if ($to=='Всем') $to = 0;
                else
                {
                    $to = mysql_result(myquery("SELECT user_id FROM game_chat_log WHERE name='$to'"),0,0);
                }
				echo '<script>top.window.frames.chat.menu.document.form.chat_mess.value=\'\'</script>';
				$update=myquery("insert into game_log (town,message,date,fromm,too) values ('$town','".mysql_real_escape_string($chat_mess)."','".time()."','".$char['user_id']."','".$to."')");
			}
		}
		
		//Покажем новые сообщения в чате
        if (!isset($dat_gorod)) $dat_gorod = 0;
		$messag=myquery("select * from game_log where town=$town and id>".$dat_gorod." ORDER BY id DESC");
		if (mysql_num_rows($messag))
		{
			$text='';
			while ($message=mysql_fetch_array($messag))
			{
                if ($message['fromm']>0)
                {
				    $fromm = mysql_result(myquery("SELECT name FROM game_chat_log WHERE user_id=".$message['fromm'].""),0,0);
                }
                else
                {
                    $fromm = '';
                }
                if ($message['too']>0)
                {
				    $too = mysql_result(myquery("SELECT name FROM game_chat_log WHERE user_id=".$message['too'].""),0,0);  
                }
                else
                {
                    $too = '';
                }
				$user_date = date('H:i', $message['date']);

				if (strpos($message['message'],'Наложил печать обновления')>0)
				{
					$dat_gorod=$message['id'];
					setcookie("rpg_chat_dat",$dat_gorod);
					echo '<script>top.window.frames.chat.location.replace("index.php?opt=main")</script>';
					break;
				}

				if ($message['fromm']==$char['user_id'])
				{
					if ($message['too']!=0)
					{
						//свое сообщение в приват
						$text.='<div style="width:99%;vertical-align:text-top;background-color:4B4B4B">';
						$text.='<span style="font-family:'.$chato['font'].';font-size:'.$chato['size'].'px"><span style="color:c0c0c0">'.$user_date.' <b>'.$fromm.'</b>(лично-></span><span style="color:#80FF80;cursor:hand;" onClick="priv(\''.$too.'\')"><b>'.$too.'</b></span>)<span style="color:c0c0c0">:></span>'.$message['message'].'</span></div>';
					}
					else
					{
						//свои сообщения
						$text.='<div style="width:99%;vertical-align:text-top;">';
						$text.='<span style="font-family:'.$chato['font'].';font-size:'.$chato['size'].'px;color:c0c0c0">'.$user_date.' <b>'.$fromm.'</b>:>'.$message['message'].'</span></div>';
					}
				}
				elseif ($message['too']==$char['user_id'])
				{
					//личное(приват) обращение к игроку
					$text.='<div style="width:99%;vertical-align:text-top;background-color:4B4B4B">';
					$text.='<span style="font-family:'.$chato['font'].';font-size:'.$chato['size'].'px"><span style="cursor:hand" onClick="priv(\''.$fromm.'\')"><img src="img/p.gif" alt="Приват" title="Приват"></span>&nbsp;<font color=c0c0c0>'.$user_date.' </font><b><span style="cursor:hand;color:#80FF80" onClick="priv(\''.$fromm.'\')">'.$fromm.'</span></b><span style="color:c0c0c0">(лично-><b>'.$too.'</b>):></span>';
					$text.=$message['message'];
					$text.='</span></div>';
				}
				elseif ($message['too']==0 or $user_id==1)
				{
					if ($message['too']!=0)
					{
						//личное(приват) обращение к игроку
						$text.='<div style="width:99%;vertical-align:text-top;background-color:4B4B4B">';
						$text.='<span style="font-family:'.$chato['font'].';font-size:'.$chato['size'].'px"><span style="cursor:hand" onClick="priv(\''.$fromm.'\')"><img src="img/p.gif" alt="Приват" title="Приват"></span>&nbsp;<font color=c0c0c0>'.$user_date.' </font><b><span style="cursor:hand" onClick="priv(\''.$fromm.'\')">'.$fromm.'</span></b><span style="color:c0c0c0">(лично-><b>'.$too.'</b>):></span>'.$message['message'].'</span></div>';
					}
					else
					{
						if (preg_match($char['name'].",",$message['message']))
						{
							//в сообщение есть имя игрока
							$text.='<div style="width:99%;vertical-align:text-top;background-color:222222">';
						}
						else
						{
							//просто сообщения в чат или сообщения другим игрокам
							$text.='<div style="width:99%;vertical-align:text-top">';
						}
						$text.='<span style="font-family:'.$chato['font'].';font-size:'.$chato['size'].'px"><font color=c0c0c0><span style="cursor:hand" onClick="priv(\''.$fromm.'\')"><img src="img/p.gif" alt="Приват" title="Приват"></span>&nbsp;'.$user_date.' <span style="cursor:hand" onClick="cha(\''.$fromm.'\')"><b>'.$fromm.'</b></span>:></font>'.$message['message'].'</span></div>';
					}
				}
				$dat_gorod=max($dat_gorod,$message['id']);
			}
		}
		if (isset($text) and $text!='')
		{
			$text=addslashes($text);
			echo '<script>top.window.frames.chat.index.document.getElementById("chat_text").innerHTML="'.$text.'"+top.window.frames.chat.index.document.getElementById("chat_text").innerHTML;</script>';
		}		
	break;
}
mysql_close();  

if (function_exists("save_debug")) save_debug(); 

?>
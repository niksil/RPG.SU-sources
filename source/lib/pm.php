<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

function display_header($param)
{
	global $folder,$user_id;
	echo'<table border="0" cellspacing=0 cellpadding=3 width="80%"><tr><td><a href="act.php?func=pm&folder=0">Непрочитанные</a> | <a href="act.php?func=pm&folder=1">Прочитанные</a> | <a href="act.php?func=pm&folder=2">Архив</a> | <a href="act.php?func=pm&folder=3">Отправленные</a> | <a href="act.php?func=pm&folder=4">С рынков</a> | <a href="act.php?func=pm&folder=5">От торговцев</a>';
	$sel = myquery("SELECT * FROM game_pm_folder WHERE user_id=$user_id");
	$folder_name = '';
	while ($row = mysql_fetch_array($sel))
	{
		echo ' | <a href="act.php?func=pm&folder='.$row['folder_id'].'">'.$row['folder_name'].'</a>';
		if ($row['folder_id']==$folder) $folder_name=$row['folder_name'];
	}
	echo '</td></tr></table>';
	if ($param!=0)
	{
		echo '<br><center><font face="Verdana,Tahoma,Arial" size=3><b>';
		if ($folder==0) echo 'НЕПРОЧИТАННЫЕ';
		elseif ($folder==1) echo 'ПРОЧИТАННЫЕ';
		elseif ($folder==2) echo 'АРХИВ';
		elseif ($folder==3) echo 'ОТПРАВЛЕННЫЕ';
		elseif ($folder==4) echo 'РЫНОК';
		elseif ($folder==5) echo 'ТОРГОВЦЫ';
		else echo strtoupper($folder_name);		
		echo '</b></font></center><br>';
		echo '<table border="0" cellspacing=0 cellpadding=3 width="80%"><form action="" method="post" name=mutliact><tr bgcolor="555555"><td><INPUT onclick=InboxCheckAll(); type=checkbox value="Отметить все" name=allbox></td><td></td><td width="20%">Дата:</td><td width="10%">';
		if ($folder==3) {echo 'Кому';}
		else {echo 'От кого';}
		echo '</td><td width="70%">Тема:</td></tr>';
	}
}

function bb_to_tag(&$str)
{
	$str=str_replace('[quote]','<table width="100%" border="1" bordercolor="444444" cellspacing="2" cellpadding="2" bgcolor="#000000"><tr><td valign="top">',$str);
	$str=str_replace('[/quote]','</td></tr></table>',$str);

	$str=str_replace('quote]','<table width="100%" border="1" bordercolor="444444" cellspacing="2" cellpadding="2" bgcolor="#000000"><tr><td valign="top">',$str);
	$str=str_replace('/quote]','</td></tr></table>',$str);

	$str=str_replace('[quote','<table width="100%" border="1" bordercolor="444444" cellspacing="2" cellpadding="2" bgcolor="#000000"><tr><td valign="top">',$str);
	$str=str_replace('[/quote','</td></tr></table>',$str);

	$str=str_replace('quote','<table width="100%" border="1" bordercolor="444444" cellspacing="2" cellpadding="2" bgcolor="#000000"><tr><td valign="top">',$str);
	$str=str_replace('/quote','</td></tr></table>',$str);

	$str=str_replace('[b]','<b>',$str);
	$str=str_replace('[/b]','</b>',$str);

	$str=str_replace('[i]','<i>',$str);
	$str=str_replace('[/i]','</i>',$str);

	$str=str_replace('[u]','<u>',$str);
	$str=str_replace('[/u]','</u>',$str);

	$str=replace_enter($str);
}

include('inc/template.inc.php');
echo'<style type="text/css">@import url("style/global.css");</style>
<SCRIPT src="js/pm.js"  language="JavaScript" type="text/javascript"></SCRIPT>';
echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
?>
<script type="text/javascript">
/* URL to the PHP page called for receiving suggestions for a keyword*/
var getFunctionsUrl = "../suggest/suggest.php?keyword=";
</script>
<?
echo'<script type="text/javascript" src="../suggest/suggest.js"></script>';
$name=''.$char['name'].'';
echo'<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top">';
OpenTable('title');
echo '<img src="http://'.IMG_DOMAIN.'/nav/pm.gif" align=right>';

echo '<script language="JavaScript" type="text/javascript">
function plus()
{
	area = document.getElementById("post");
	area.rows+=2;
}
function minus()
{
	area = document.getElementById("post");
	area.rows-=2;
}
</script>';

if (isset($_POST['do']))
{
	$folder = $_POST['folder'];
	$id = $_POST['id'];
	if ($_POST['folder']==3)
	{
		$prov = myquery("SELECT * FROM game_pm WHERE id=".$_POST['id']."");
	}
	else
	{
		$prov = myquery("SELECT * FROM game_pm WHERE id=".$_POST['id']."");
	}
	if (mysql_num_rows($prov))
	{
		if (isset($_POST['delete']))
		{
			$del = array($_POST['id']);
		}
		if (isset($_POST['move']))
		{
			myquery("UPDATE game_pm SET folder=".$_POST['move_folder']." WHERE id=".$_POST['id']."");
		}
		if (isset($_POST['otvet']) AND $_POST['folder']!=3)
		{
			$pm='otvet';
		}
		if (isset($_POST['email']))
		{
			$pm_for_email = mysql_fetch_array(myquery("SELECT * FROM game_pm WHERE id=".$_POST['id'].""));
			list($otkogo) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id='".$pm_for_email['otkogo']."') UNION (SELECT name FROM game_users_archive WHERE user_id='".$pm_for_email['otkogo']."')"));		
			list($email) = mysql_fetch_array(myquery("SELECT email FROM game_users_data WHERE user_id=$user_id"));	
			
			$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";
			
			$message  = "[http://".DOMAIN."] ".GAME_NAME.". Письмо от $otkogo\n\n";
			$message .= "Тема: ".$pm_for_email['theme']."\n";
			$message .= "Дата: ".date("d-m-Y H-i-s",$pm_for_email['time'])."\n";
			//bb_to_tag($pm_for_email['post']); 
			$message .= "Содержание: ".$pm_for_email['post']."\n";

			$subject = ''.GAME_NAME.'. Письмо от '.$otkogo.'.';
			
            mail($email, $subject, $message, $headers); 
			
		}
	}
}

if (isset($pm))
{
	display_header(0);
	
	if ($pm=='setup')
	{
		include("pm_setup.php");
	}
	
	if ($pm=='read')
	{
		if (isset($id))
		{
			$id=(int)$id;
			$prov=myquery("select * from game_pm where id='$id' and komu='$user_id'");
			//Читается полученное письмо
			if (mysql_num_rows($prov))
			{
				$row=mysql_fetch_array($prov);
				$otkogo = get_user('name',$row['otkogo']);
				bb_to_tag($row['post']);
				
				echo '<table border="0" cellpadding="0" cellpadding="3" width="100%">';
				echo '<tr><td width="80" align="right">Имя:</td><td>'.$otkogo.'</td></tr>';
				echo '<tr><td align="right">Тема:</td><td>'.$row['theme'].'</td></tr>';
				echo '<tr><td align="right">Дата:</td><td>'.date("d-m-Y H-i-s",$row['time']).'</td></tr>';
				echo '<tr><td valign="top" align="right">Сообщение:</td><td style="border:solid;border-width:2px;padding:2px;border-color:#969696" bgcolor="black">'.$row["post"].'</td></tr>';
				echo '</table>';
				echo '
				 <script language="JavaScript" type="text/javascript">
				 function change_move()
				 {
					if (form1.move.checked)
					{
						document.getElementById("move_choice").style.display="inline";
					}
					else
					{
						document.getElementById("move_choice").style.display="none";
					}
				 }
				 </script>
				 <table cellspacing=0 cellpadding=0>
				 <form name="form1" action="act.php?func=pm" method="post">
				 <tr><td><input type="hidden" name="id" value='.$row['id'].'><input type="hidden" name="folder" value='.$row['folder'].'></td></tr>
				 <tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="otvet" value="checked" checked><b>Ответить<br></td></tr>
				 <tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="delete"><b>Удалить<br></td></tr>';
				 list($email) = mysql_fetch_array(myquery("SELECT email FROM game_users_data WHERE user_id=$user_id"));
				 echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="email"><b>Отправить копию этого письма на ящик <i>'.$email.'</i><br></td></tr>
				 <tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" onClick="change_move();" id="move" name="move"><b>Переместить<span id="move_choice" style="display:none">&nbsp;в&nbsp;<SELECT name="move_folder">
				 <OPTION value=2>В архив</OPTION>
				 <OPTION value=3>С рынков</OPTION>
				 <OPTION value=4>От торговцев</OPTION>';
				 $sel_folder=myquery("SELECT * FROM game_pm_folder WHERE user_id=$user_id");
				 while ($fold = mysql_fetch_array($sel_folder))
				 {
					 echo '<OPTION value='.$fold['folder_id'].'>'.$fold['folder_name'].'</OPTION>';
				 }
				 echo '</select></span><br><br></td></tr>
				 <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Выполнить" name="do"></td></tr>
				 </form></table><br><br>';
				 // Перекинем в папку "Прочитанные" из папки "Непрочитанные"
				 $result=myquery("update game_pm set view=1,folder=1 where id='$id' and folder=0");
				 $result=myquery("update game_pm set view=1 where id='$id' and folder>0");
			}
			else
			{
				$prov=myquery("select * from game_pm where id='$id' and otkogo='$user_id' and folder=3");
				//Читается письмо из "Отправленных"
				if (mysql_num_rows($prov))
				{
				   $row=mysql_fetch_array($prov);
				   $komu=get_user('name',$row['komu']);
				   bb_to_tag($row['post']);
				   
				   echo '<table border="0" cellpadding="3">';
				   echo '<tr><td width="80" align="right">Имя:</td><td>'.$komu.'</td></tr>';
				   echo '<tr><td align="right">Тема:</td><td>'.$row['theme'].'</td></tr>';
				   echo '<tr><td align="right">Дата:</td><td>'.date("d-m-Y H-i-s",$row['time']).'</td></tr>';
				   echo '<tr><td valign="top" align="right">Сообщение:</td><td>'.$row["post"].'</td></tr>';
				   echo '</table><br />&nbsp;&nbsp;&nbsp;<input type="button" onClick=location.href="act.php?func=pm&del[0]='.$row['id'].'&folder=3" value="Удалить">';
				}
			}
		}
	}
	
	

	if ($pm=='otvet')
	{
		if (isset($id))
		{
			$id=(int)$id;
			$prov=myquery("select * from game_pm where id='$id' and komu='$user_id'");
			// Отвечать можно только на полученные письма
			if (mysql_num_rows($prov))
			{
				if (isset($_POST['pos'] ))
				{
					// Отправляем ответ
					$sel = myquery("(SELECT user_id FROM game_users WHERE name='$komu') UNION (SELECT user_id FROM game_users_archive WHERE name='$komu')");
					if (mysql_num_rows($sel))
					{
						$userid = mysql_result($sel,0,0);
						if (empty($theme))
						{
							echo 'Надо указать тему сообщения!';
						}
						elseif (empty($post))
						{
							echo 'Пустые письма мы не отправляем!';
						}
						else
						{
							$theme=htmlspecialchars($theme);
							$post=htmlspecialchars($post);
							$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$userid', '$user_id', '$theme', '$post', '0','".time()."',0)");
						   
							$pm_id = mysql_insert_id();
							if (isset($_POST['copy_save']))
							{
								$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$userid', '$user_id', '$theme', '$post', '3','".time()."',3)");
							}
							echo 'Сообщение отправлено';
							$check = myquery("SELECT user_id FROM view_active_users WHERE user_id=$userid");
							if ($check!=false AND mysql_num_rows($check)>0)
							{
								$chat_mess = '<br><center>Для тебя пришел ответ на сообщение (тема: <font color=#0000FF>'.$theme.'</font>) от игрока <font size=2 color=#FF0000><b><u>'.$char['name'].'</u></b></font> !&nbsp;&nbsp;&nbsp;&nbsp;</center><br>';
								$say = myquery("insert into game_log (town,fromm,too,message,date,pm_id,ptype) values ('0',0,'$userid','".iconv("Windows-1251","UTF-8//IGNORE",$chat_mess)."','".time()."',".$pm_id.",1)");
							}
							list($send_pm_email,$email_komu) = mysql_fetch_array(myquery("SELECT send_pm,email FROM game_users_data WHERE user_id=$userid"));
							if ($send_pm_email==1)
							{
								$otkogo = $char['name'];		
								
			$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";
			
								$message  = "[http://".DOMAIN."] ".GAME_NAME.". Письмо от $otkogo\n\n";
								$message .= "Тема: $theme\n";
								$message .= "Дата: ".date("H-i d-m-Y")."\n";
								$message .= "Содержание: \n$post\n";

								$subject = ''.GAME_NAME.'. Письмо от '.$otkogo.'.';
								
                                mail($email_komu, $subject, $message, $headers);
							}
						}
					}
					else
					{
						echo 'Такого игрока не существует';
					}
					echo '<meta http-equiv="refresh" content="1;url=act.php?func=pm&new">';
				}
				else
				{
					// Пишем ответ
					$row=mysql_fetch_array($prov);
					$otkogo = get_user('name',$row['otkogo']);
					$selkomu = myquery("(SELECT name FROM game_users WHERE user_id='".$row['otkogo']."') UNION (SELECT name FROM game_users_archive WHERE user_id='".$row['otkogo']."')");
					echo '<table width="100%" border="0" cellpadding="3">';
					echo '<form action="act.php?func=pm&id='.$row['id'].'&pm=otvet" method="post">';
					echo '<tr><td width="40" align="right" maxsize="25">Имя:</td><td align="left"><input type="text" name="komu" value="'.$otkogo.'"></td><td></td></tr>';
					echo '<tr><td width="40" align="right">Тема:</td><td align="left"><input style="width:100%;" type="text" name="theme" value="Re:'.$row['theme'].'"></td><td></td></tr>';
					echo '<tr><td valign="top" align="right">Сообщение:<br>Открытые теги<br>[b][/b]<br>[i][/i]<br>[u][/u]</td><td><textarea style="width:100%;" id="post" name="post" rows="10" cols="80">[quote]'.$row['post'].'[/quote]</textarea></td><td valign="top" align="left"><input type="button" value="+" id="add" style="background-color: black; color: white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:0px; margin:0px;" onClick="plus();"><br><input type="button" value="-" id="del" style="background-color:black; color:white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:2px; margin:2px;" onClick="minus();"></td></tr>';
					echo '<tr><td></td><td align="left"><input type="checkbox" checked name="copy_save" value="checked">Сохранить копию письма в папке "Отправленные"</td><td></td></tr>';
					echo '<tr><td><input type="hidden" name="pos" value=""></td><td align="left"><input type="submit" value="Отправить сообщение"></td><td></td></tr>';
					echo '</form></table>';
				}
			}
		}
	}

	if ($pm=='write')
	{
		if (isset($pos))
		{
		   // Отправляем письмо
		   $komu = explode(',',$komu);
		   for ($i=0;$i<count($komu);$i++)
		   {
				if (empty($theme))
				{
					echo 'Надо указать тему сообщения!';
				}
				elseif (empty($post))
				{
					echo 'Пустые письма мы не отправляем!';
				}
				else
				{
					$theme=htmlspecialchars($theme);
					$post=htmlspecialchars($post);

					$komu[$i] = trim($komu[$i]);

					$userid=get_user('user_id',trim($komu[$i]),1);
					if ($userid!="~~~")
					{
						$cur_time = time();
						$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$userid', '$user_id', '$theme', '$post', '0','".$cur_time."',0)");
						$id_pm = mysql_insert_id();
						if (isset($_POST['copy_save']))
						{
							$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$userid', '$user_id', '$theme', '$post', '3','".$cur_time."',3)");
						}
						$check = myquery("SELECT user_id FROM view_active_users WHERE user_id=$userid");
						if ($check!=false AND mysql_num_rows($check)>0)
						{
							$chat_mess = '<br><center>Для тебя пришло новое личное сообщение (тема: <font color=#0000FF>'.$theme.'</font>) от игрока <font size=2 color=#FF0000><b><u>'.$name.'</u></b></font> !&nbsp;&nbsp;&nbsp;&nbsp;</center><br>';
							$say = myquery("insert into game_log (town,fromm,too,message,date,pm_id,ptype) values ('0',0,'$userid','".iconv("Windows-1251","UTF-8//IGNORE",$chat_mess)."','".time()."',".$id_pm.",1)");
						}
						echo 'Сообщение игроку <b><font color=red>'.$komu[$i].'</font></b> отправлено<br>';
						list($send_pm_email,$email_komu,$send_ICQ,$ICQnumber,$ICQ_pm) = mysql_fetch_array(myquery("SELECT send_pm,email,send_ICQ,ICQnumber,ICQ_pm FROM game_users_data WHERE user_id=$userid"));
						if ($send_pm_email==1)
						{
							$otkogo = $char['name'];
			$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";
			
							$message  = "[http://".DOMAIN."] ".GAME_NAME.". Письмо от $otkogo\n\n";
							$message .= "Тема: $theme\n";
							$message .= "Дата: ".date("H-i d-m-Y")."\n";
							$message .= "Содержание: \n$post\n";

							$subject = ''.GAME_NAME.'. Письмо от '.$otkogo.'.';

                            mail($email_komu, $subject, $message, $headers); 
						}
					}
					else
					{
						echo 'Игрока <b><font color=red>'.$komu[$i].'</font></b> не существует!<br>';
					}
				}
			}
			echo '<meta http-equiv="refresh" content="3;url=act.php?func=pm&new">';
		}
		else
		{
			//Пишем письмо
			if (!isset($komu))
			{ 
				echo '<div id="content" onclick="hideSuggestions();">';
			}
			echo '<table width="100%" border="0" cellpadding="3">';
			echo '<tr><td></td><td>Чтобы написать сразу нескольким игрокам - напишите их имена через знак &quot;,&quot;</td><td></td></tr>';
			echo '<form action="" method="post"';
			echo '<tr><td width="40" align="right">Имя:</td><td>';
			if (!isset($komu))
			{
				echo '<input  style="width:100%;" type="text"  id="keyword" onkeyup="handleKeyUp(event)" name="komu" value=""><div style="display:none;" id="scroll"><div id="suggest"></div></div>';
			}
			else
			{
				echo '<input  style="width:100%;" type="text"  id="keyword" name="komu" value="'.$komu.'">';
			}
			echo '</td><td></td></tr>';
			echo '<tr><td width="40" align="right">Тема:</td><td ><input style="width:100%;" type="text" name="theme" size=80></td><td></td></tr>';
			echo '<tr><td valign="top" align="right">Сообщение:<br>Открытые теги<br>[b][/b]<br>[i][/i]<br>[u][/u]</td><td><textarea style="width:100%;" id="post" name="post" rows="10" cols="80"></textarea></td><td valign="top" align="left"><input type="button" value="+" id="add" style="background-color: black; color: white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:0px; margin:0px;" onClick="plus();"><br><input type="button" value="-" id="del" style="background-color:black; color:white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:2px; margin:2px;" onClick="minus();"></td></tr>';
			echo '<tr><td></td><td align="left"><input type="checkbox" checked name="copy_save" value="checked">Сохранить копию письма в папке "Отправленные"</td><td></td></tr>';
			echo '<tr><td><input type="hidden" name="pos" value=""></td><td align="left"><input type="submit" value="Отправить сообщение"></td><td></td></tr>';
			echo '</form></table>';
			if (!isset($komu))
			{ 
				echo '</div><script>init();</script>';
			}
		}
	}

	if ($pm=='write_clan')
	{
		if (isset($pos))
		{
			$theme=htmlspecialchars($theme);
			$post=htmlspecialchars($post);

			$cl=$char['clan_id'];
			$clan=myquery("(select user_id from game_users where clan_id='$cl') UNION (select user_id from game_users_archive where clan_id='$cl')");
			if (mysql_num_rows($clan))
			{
				while (list($userid)=mysql_fetch_array($clan))
				{
					$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time,folder) VALUES ('$userid', '$user_id', '$theme', '$post', '0','1','".time()."',0)");
				}
				echo 'Сообщение разослано всему клану';
				echo '<meta http-equiv="refresh" content="1;url=act.php?func=pm&new">';
			}
		}
		else
		{
			if (!isset($komu)) $komu='';
			echo '<table border="0" cellpadding="3"><form action="" method="post"';
			echo '<tr><td align="right">Тема:</td><td><input type="text" name="theme"></td><td></td></tr>';
			echo '<tr><td valign="top" align="right">Сообщение:</td><td><textarea id="post" name="post" rows="10" cols="80"></textarea></td><td valign="top" align="left"><input type="button" value="+" id="add" style="background-color: black; color: white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:0px; margin:0px;" onClick="plus();"><br><input type="button" value="-" id="del" style="background-color:black; color:white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:2px; margin:2px;" onClick="minus();"></td></tr>';
			echo '<tr><td><input type="hidden" name="pos" value=""></td><td align="left"><input type="submit" value="Отправить сообщение клану"></td><td></td></tr>';
			echo '</form></table>';
		}
	}

	if ($pm=='write_clan_online')
	{
		if (isset($pos))
		{
			$theme=htmlspecialchars($theme);
			$post=htmlspecialchars($post);

			$cl=(int)$cl;
			$clan=myquery("select user_id from view_active_users where clan_id='$cl' ");
			if (mysql_num_rows($clan))
			{
				while (list($userid)=mysql_fetch_array($clan))
				{
					$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time,folder) VALUES ('$userid', '$user_id', '$theme', '$post', '0','1','".time()."',0)");
				}
				echo 'Сообщение разослано всему клану в онлайн';
				echo '<meta http-equiv="refresh" content="1;url=act.php?func=pm&new">';
			}
		}
		else
		{
			if (!isset($komu)) $komu='';
			echo '<table border="0" cellpadding="3"><form action="" method="post"';
			echo '<tr><td align="right">Тема:</td><td><input type="text" name="theme"></td><td></td></tr>';
			echo '<tr><td valign="top" align="right">Сообщение:</td><td><textarea id="post" name="post" rows="10" cols="80"></textarea></td><td valign="top" align="left"><input type="button" value="+" id="add" style="background-color: black; color: white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:0px; margin:0px;" onClick="plus();"><br><input type="button" value="-" id="del" style="background-color:black; color:white; text-align:center; font-size:14px; font-weight:bold; width:25px; height:25px; border:1px solid darkgold; padding:2px; margin:2px;" onClick="minus();"></td></tr>';
			echo '<tr><td><input type="hidden" name="pos" value=""></td><td align="left"><input type="submit" value="Отправить сообщение клану"></td><td></td></tr>';
			echo '</form></table>';
		}
	}
}
else
{
	display_header(1);
	if (isset($arx))
	{
		$prov = myquery("SELECT COUNT(*) FROM game_pm WHERE id='$arx' and komu='$user_id'");
		if (mysql_num_rows($prov))
		{
			$arx=(int)$arx;
			$result=myquery("update game_pm set view=2 where id='$arx' and komu='$user_id'");
			echo 'Сообщение перенесено в архив';
		}
	}

	if (isset($new)) $folder=0;
	if (isset($old)) $folder=1;
	if (isset($sent)) $folder=3;
	if (isset($arhiv)) $folder=2;
	if (isset($old_items)) $folder=4;
	if (isset($shops)) $folder=5;

	if(isset($folder) and is_numeric($folder))
	{
		if (isset($del))
		{
			while (list($as,$delete)=each($del))
			{
				$delete=(int)$delete;
				if ($folder!=3)
				{
					myquery("insert into game_pm_deleted select * from game_pm where id='$delete' and komu='$user_id'");
					myquery("delete from game_pm where id='$delete' and komu='$user_id'");
				}
				else
				{
					myquery("delete from game_pm where id='$delete' and otkogo='$user_id'");
				}
			}
		}
		if ($folder==3)
		{
			$pm=myquery("select count(*) from game_pm where otkogo='$user_id' and folder=$folder ORDER BY time DESC");
		}
		elseif ($folder==0)
		{
			$pm=myquery("select count(*) from game_pm where komu='$user_id' and view=0 ORDER BY time DESC");
		}
		else
		{
			$pm=myquery("select count(*) from game_pm where komu='$user_id' and folder=$folder ORDER BY time DESC");
		}
		if (!isset($page)) $page=1;
		$page=(int)$page;
		if ($page<1) $page=1;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		if ($folder==3)
		{
			$pm=myquery("select * from game_pm where otkogo='$user_id' and folder='$folder' ORDER BY time DESC limit ".(($page-1)*$line).", $line");
		}
		elseif ($folder==0)
		{
			$pm=myquery("select * from game_pm where komu='$user_id' and view=0 ORDER BY time DESC limit ".(($page-1)*$line).", $line");
		}
		else
		{
			$pm=myquery("select * from game_pm where komu='$user_id' and folder='$folder' ORDER BY time DESC limit ".(($page-1)*$line).", $line");
		}

		if (mysql_num_rows($pm))
		{
			$i=0;
			while($row=mysql_fetch_array($pm))
			{
				if ($folder==3)
				{
					$otkogo = get_user('name',$row['komu']);
				}
				else
				{
					$otkogo = get_user('name',$row['otkogo']);
				}
				echo'<tr><td><input type="checkbox" name="del['.$i.']" value="'.$row['id'].'"></td><td>';
				$i++;
				echo'</td><td>'.date("d-m-Y H-i-s",$row["time"]).'</td><td>'.$otkogo.'</td><td><a href="act.php?func=pm&id='.$row["id"].'&pm=read"><img src="http://'.IMG_DOMAIN.'/forum/img/lastpost.gif" border="0" alt="=>"> '.$row["theme"].'</a></td></tr>';
			}
			echo '<tr><td colspan="4" align="left"><input type="submit" value="Удалить"></td></tr>';
			echo'</form></table>';
			$href = 'act.php?func=pm&folder='.$folder.'&';
			echo'<center>Страница: ';
			show_page($page,$allpage,$href);
		}
		else
		{
			echo '</table><br><center style="color:red"><font size=2><b>У тебя нет сообщений в этой папке</b></font></center>';
		}
	}
	else
	{
		echo '</table>';
	}

	echo '<br><br><input type="button" onClick=location.href="act.php?func=pm&pm=write" value="Написать новое сообщение">';
}


echo '<br><br><div align="right" width="100%"><input type="button" onClick=location.href="act.php?func=pm&pm=setup" value="Настроить свой почтовый ящик"></div>';
OpenTable('close');
echo'</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo'</td></tr></table>';
set_delay_reason_id($user_id,26);

if (function_exists("save_debug")) save_debug(); 

?>
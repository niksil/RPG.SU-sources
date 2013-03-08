<?php
function exit_quest()
{
	global $user_id;
	ForceFunc($user_id,5);
	?>
	<meta http-equiv="refresh" content="10"> 
	<?
}
function take_win()
{
	global $char, $quest_id, $book_id;
	$new_clevel=get_new_level($char['clevel']);
	$get_exp = floor(0.1*$new_clevel);
	setEXP($char['user_id'],$get_exp,$book_id+7); 
	$get_gp = mysqlresult(myquery("SELECT gp FROM bookgame_users WHERE user_id=".$char['user_id']." AND bookgame=$book_id"),0,0);
	setGP($char['user_id'],$get_gp,$book_id+100); 
	myquery("UPDATE game_users SET EXP=EXP+$get_exp,GP=GP+$get_gp WHERE user_id=".$char['user_id']."");
	myquery("DELETE FROM bookgame_users WHERE user_id=".$char['user_id']." AND bookgame=$book_id");
	myquery("UPDATE game_quest_users SET last_time=UNIX_TIMESTAMP(),finish=1 WHERE user_id=".$char['user_id']." AND quest_id=$quest_id");
	exit_quest();
}
function take_lose()
{
	global $char, $quest_id, $book_id;
	myquery("UPDATE game_users SET HP=1,MP=1,STM=1 WHERE user_id=".$char['user_id']."");
	myquery("DELETE FROM bookgame_users WHERE user_id=".$char['user_id']." AND bookgame=$book_id");
	myquery("UPDATE game_quest_users SET last_time=UNIX_TIMESTAMP() WHERE user_id=".$char['user_id']." AND quest_id=$quest_id");
	exit_quest();
}
function make_start()
{
	global $gp_start,$user_id,$book_id;
	mt_srand(make_seed());
	$master = 6+mt_rand(1,6);
	mt_srand(make_seed());
	if ($book_id==3 OR $book_id==4)
	{
		$dex = 6+mt_rand(1,6);
	}
	else
	{
		$dex = 12+mt_rand(1,6)+mt_rand(1,6);
	}
	mt_srand(make_seed());
	if ($book_id==3 OR $book_id==4)
	{
		$lucky = mt_rand(1,6)+mt_rand(1,6);
	}
	else
	{
		$lucky = 6+mt_rand(1,6);
	}
	myquery("UPDATE bookgame_users SET gp=$gp_start,master=$master,master_start=$master,dex=$dex,dex_start=$dex,lucky=$lucky,lucky_start=$lucky WHERE user_id=$user_id AND bookgame=$book_id");
	myquery("DELETE FROM bookgame_users_flags WHERE user_id=$user_id AND bookgame=$book_id");
}
function print_combat()
{
	global $char, $book_id, $user_book, $user_id;
	$win = false;
	$lose = false;
	
	$book_user = mysql_fetch_array(myquery("SELECT * FROM bookgame_users WHERE bookgame=$book_id AND user_id=".$char['user_id'].""));
	$curr_step = $book_user['step'];
	$sel=myquery("SELECT * FROM bookgame_step_npc WHERE bookgame=$book_id AND step=$curr_step");
	while ($npc = mysql_fetch_array($sel))
	{
		myquery("INSERT IGNORE INTO bookgame_users_npc SET name='".$npc['name']."',master='".$npc['master']."',dex='".$npc['dex']."',lucky='".$npc['lucky']."',npc_id='".$npc['id']."',user_id='".$char['user_id']."'");
	}
	
	$r_user = 0;
	$damage_user = 0;
	$uron = 2;
	if ($book_id==3)
	{
		if ($_GET['page']==24)
		{
			$uron = 3;
			$sel = myquery("SELECT * FROM bookgame_users_flags WHERE bookgame=$book_id AND user_id=".$char['user_id']." AND flag=6");
			if (mysql_num_rows($sel))
			{
				$book_users['master']+=2;
			}
		}
	}
	if (isset($_GET['udar']))
	{
		//расчет хода
		mt_srand(make_seed());
		$r_user = mt_rand(1,6)+mt_rand(1,6);
		
		$ar = array();
		$sel_npc=myquery("SELECT * FROM bookgame_users_npc ORDER BY dex ASC");
		$i = 0;
		while ($npc = mysql_fetch_array($sel_npc))
		{
			if ($npc['dex']>0)
			{
				mt_srand(make_seed());
				$r_npc = mt_rand(1,6)+mt_rand(1,6);
				$npc['rand'] = $r_npc;
				$npc['damage'] = 0;
				$dex = $npc['dex'];
				if (($book_user['master']+$r_user)>($npc['master'] + $r_npc))
				{
					if ($i==0)
					{
						myquery("UPDATE bookgame_users_npc SET dex=GREATEST(dex-$uron,0) WHERE user_id=".$char['user_id']." AND npc_id=".$npc['npc_id']."");
						$i++;
						$dex-=2;
						$npc['damage'] = 2;
					}
				}
				if ($dex>0)
				{
					if (($book_user['master']+$r_user)<($npc['master'] + $r_npc))
					{
						$damage_user+=2;
						myquery("UPDATE bookgame_users SET dex=GREATEST(dex-$uron,0) WHERE bookgame=$book_id AND user_id=".$char['user_id']."");     
					}
				}
			}
			else
			{
				$npc['rand'] = 0;
				$npc['damage'] = 0;
			}
			$ar[] = $npc;
		}
	}
	else
	{
		$sel_npc=myquery("SELECT * FROM bookgame_users_npc ORDER BY dex ASC");
		while ($npc = mysql_fetch_array($sel_npc))
		{
			$npc['rand'] = 0;
			$npc['damage'] = 0;
			$ar[] = $npc;
		}
	}
	if (($book_user['dex']<=0) OR (($book_user['dex']-$damage_user)<=0)) $lose=true;
	

   //вывод интерфейса
	$prot = mysqlresult(myquery("SELECT COUNT(*) FROM bookgame_users_npc WHERE dex>0 AND user_id=".$char['user_id'].""),0,0);
	if ($prot==0) $win=true;
	
	//ход боя
	echo '<center><table border=1 cellspacing=5 cellpadding=8><tr><th>Ты</th><th>Противник(и)</th></tr>
	<tr><td>
	<table cellspacing=3 cellpaddin=2';
	if ($damage_user>0) echo ' bgcolor=#000080';
	echo '>
	<tr><td>Мастерство (сила удара)</td><td>'.$book_user['master'].' + '.$r_user.' = '.($book_user['master']+$r_user).'</td></tr>
	<tr><td>Выносливость (уровень жизни)</td><td>'.$book_user['dex'].' - '.$damage_user.' = '.($book_user['dex']-$damage_user).'</td></tr>
	<tr><td>Удача</td><td>'.$book_user['lucky'].'</td></tr>
	</table></td><td>';
	foreach ($ar as $key => $npc)
	{
		echo '
		<table cellspacing=3 cellpaddin=2';
		if ($npc['damage']>0) echo ' bgcolor=#800000';
		echo '>
		<tr><th colspan="2" align="center">'.$npc['name'].'</th></tr>';
		if ($npc['dex']>0)
		{
			echo '
			<tr><td>Мастерство (сила удара)</td><td>'.$npc['master'].' + '.$npc['rand'].' = '.($npc['master']+$npc['rand']).'</td></tr>
			<tr><td>Выносливость (уровень жизни)</td><td>'.$npc['dex'].' - '.$npc['damage'].' = '.($npc['dex']-$npc['damage']).'</td></tr>
			<tr><td>Удача</td><td>'.$npc['lucky'].'</td></tr>';
		}
		else
		{
			echo '<tr><td colspan="2" align="center">Противник повержен!</td></tr>';
		}
		echo '
		</table>
		';
	}
	echo '</td></tr>';
	echo '</table>';
	//проверим возможность сбежать
	
	$can_retreat = 0;
	$retreat_step = myquery("SELECT step_to FROM bookgame_step_to_step WHERE bookgame=$book_id AND step_from=$curr_step AND retreat=1 LIMIT 1");
	if (mysql_num_rows($retreat_step)>0)
	{
		$can_retreat = mysqlresult($retreat_step,0,0);
	}
	if ($can_retreat>0)
	{
		if (isset($_GET['retreat']))
		{
			myquery("DELETE FROM bookgame_users_npc WHERE user_id=".$char['user_id']."");
			myquery("UPDATE bookgame_users SET step=$can_retreat,dex=GREATEST(0,dex-2) WHERE user_id=".$char['user_id']." AND bookgame=$book_id");
			echo '<br /><br /><br /><center>Вы сбежали из боя!<meta http-equiv="refresh" content="1;url=?page='.$can_retreat.'">';
		}
		else
		{
			echo '<br /><br />Во время этого боя вы, если хотите, можете попробовать убежать (<a href="?page='.$_GET['page'].'&retreat">'.$can_retreat.'</a>).<br />При этом вы потеряете 2 Выносливости!';
		}
	}
	if ($win)
	{
		myquery("DELETE FROM bookgame_users_npc WHERE user_id=".$char['user_id']."");
		$next_step = mysqlresult(myquery("SELECT step_to FROM bookgame_step_to_step WHERE bookgame=$book_id AND step_from=$curr_step AND retreat=0 LIMIT 1"),0,0);
		myquery("UPDATE bookgame_users SET step=$next_step WHERE user_id=".$char['user_id']." AND bookgame=$book_id");
		echo '<br /><br /><br /><center><input type="button" onClick="location.replace(\'http://'.DOMAIN.'/'.$_SERVER['PHP_SELF'].'?page='.$next_step.'\')" value="Победа">';
		if ($book_id==3)
		{
			if ($_GET['page']==45)
			{
				myquery("UPDATE bookgame_users_flags SET value=value-1 WHERE bookgame=3 AND user_id=$user_id AND flag=5");
			}
		}
		if ($book_id==5)
		{
			if ($_GET['page']==6)
			{
				myquery("UPDATE bookgame_users SET gp=gp+1,lucky=GREATEST(0,lucky-1) WHERE user_id=$user_id AND bookgame=5");
			}
		}
	}
	elseif ($lose)
	{
		myquery("DELETE FROM bookgame_users_npc WHERE user_id=".$char['user_id']."");
		echo '<br /><center>Ты проиграл бой! Твое приключение бесславно закончено!';
		take_lose();
	}
	else
	{
		echo '<br /><br /><br /><center><input type="button" onClick="location.replace(\'http://'.DOMAIN.'/'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&udar\')" value="Сходить">';
	}
	$user_book = mysql_fetch_array(myquery("SELECT * FROM bookgame_users WHERE user_id=$user_id AND bookgame=$book_id"));
}
function check_lucky()
{
	global $char, $book_id;
	$win = false;
	$lose = false;
	
	$book_user = mysql_fetch_array(myquery("SELECT * FROM bookgame_users WHERE bookgame=$book_id AND user_id=".$char['user_id'].""));
	
	if ($book_id==5 AND !isset($_GET['lucky']))
	{
		echo 'Тебе предоставляется возможность проверить свою удачу!<br /><br />
		<a href="?page='.$book_user['step'].'&lucky=yes">Нажми сюда, чтобы проверить свою удачу</a><br /><br />     
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?page='.$book_user['step'].'&lucky=no">Нажми сюда, если ты не хочешь проверять свою удачу</a>';  
	}
	else
	{
		$user_lucky = $book_user['lucky'];
		mt_srand(make_seed());
		$check_lucky = mt_rand(1,6)+mt_rand(1,6); 
		if (isset($_GET['lucky']) AND $_GET['lucky']=='no')
		{
			$user_lucky = 0;
		}
		else
		{
			if ($book_id==5)
			{
				//уменьшаем удачу игрока
				myquery("UPDATE bookgame_users SET lucky=GREATEST(0,lucky-1) WHERE user_id=".$char['user_id']." AND bookgame=$book_id");
			}    
		}
		
		echo '<b>Настало время проверить твою удачу!</b><br /><br /><br />
		<table cellspacing=2 cellpadding=2>
		<tr><td>Твоя удача:</td><td style="color:#FFFF00">'.$user_lucky.'</td></tr>
		<tr><td>Проверка удачи:</td><td style="color:#00FF00">'.$check_lucky.'</td></tr>
		<tr><td align="center" colspan="2"';
		if ($user_lucky>=$check_lucky)
		{
			$step = mysqlresult(myquery("SELECT step_to FROM bookgame_step_to_step WHERE bookgame=$book_id AND step_from=".$book_user['step']." AND lucky_win=1"),0,0);
			echo ' style="background-color:#005858">Поздравляю! Ты оказался удачливее!&nbsp;
			<a href="http://'.DOMAIN.''.$_SERVER['PHP_SELF'].'?page='.$step.'">иди на '.$step.'</a>';
		}
		else
		{
			$step = mysqlresult(myquery("SELECT step_to FROM bookgame_step_to_step WHERE bookgame=$book_id AND step_from=".$book_user['step']." AND lucky_lose=1"),0,0);
			echo ' style="background-color:#580000">Жаль! Но тебе сегодня не везет!&nbsp;
			<a href="http://'.DOMAIN.''.$_SERVER['PHP_SELF'].'?page='.$step.'">иди на '.$step.'</a>';
		}
		echo '</td></tr>
		</table>';
	}
}

if (!isset($_GET['page']))
{
	$curr_step = mysqlresult(myquery("SELECT step FROM bookgame_users WHERE bookgame=$book_id AND user_id=$user_id"),0,0);
	if ($curr_step>0)
	{
		setLocation("http://".DOMAIN.''.$_SERVER['PHP_SELF'].'?page='.$curr_step);
	}
}

if (!isset($gp_start)) $gp_start=0;

$questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." AND id=$quest_id");
if (!mysql_num_rows($questsel))
{
	ForceFunc($user_id,5);
	setLocation("../act.php");
}
$sel_sost = myquery("SELECT finish,last_time FROM game_quest_users WHERE user_id=$user_id AND quest_id=$quest_id");
$new = 1;
if ($sel_sost!=false AND mysql_num_rows($sel_sost)>0)
{
	$new = 0;
	$sst = mysql_fetch_array($sel_sost);
	if ($sst['finish']>=1 AND DOMAIN!=TEST_SERVER)
	{
		ForceFunc($user_id,5);
		setLocation("../act.php");
	}
	if ($sst['last_time']>(time()-10*60) AND DOMAIN!=TEST_SERVER)
	{
		ForceFunc($user_id,5);
		setLocation("../act.php");
	}
}
if ($new==0)
{
	if (!mysql_num_rows(myquery("SELECT * FROM bookgame_users WHERE user_id=$user_id AND bookgame=$book_id")))
	{
		$new = 1;
	}
}
if ($new==1)
{
	//заходим в первый раз
	myquery("INSERT INTO game_quest_users SET quest_id=$quest_id,user_id=$user_id,last_time=UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE last_time=UNIX_TIMESTAMP()");
	$curr_step = mysqlresult(myquery("SELECT step FROM bookgame_step WHERE bookgame=$book_id AND flag=3"),0,0);
	myquery("INSERT INTO bookgame_users SET user_id=$user_id,bookgame=$book_id,step=$curr_step ON DUPLICATE KEY UPDATE step=$curr_step");
	make_start();
}
if (isset($_GET['exit']))
{
	myquery("UPDATE game_quest_users SET last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND quest_id=$quest_id");
	ForceFunc($user_id,5);
	setLocation("../act.php");
}
if (!isset($curr_step))
{
	$curr_step = mysqlresult(myquery("SELECT step FROM bookgame_users WHERE bookgame=$book_id AND user_id=$user_id"),0,0);
}
if (isset($_GET['page']))
{
	$check_step = myquery("SELECT step_to FROM bookgame_step_to_step WHERE bookgame=$book_id AND step_from=$curr_step AND step_to=".((int)$_GET['page'])."");
	if (mysql_num_rows($check_step) OR $curr_step == $_GET['page'])
	{
		$curr_step = $_GET['page'];
		myquery("UPDATE bookgame_users SET step=$curr_step WHERE bookgame=$book_id AND user_id=$user_id");
	}
	else
	{
		ForceFunc($user_id,5);
		setLocation("../act.php");
	}
}
before_print();
echo"<html>
<head>
<title>".GAME_NAME."</title>";
?>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="">
<meta name="Keywords" content="">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<?
set_delay_reason_id($user_id,13);

$step = mysql_fetch_array(myquery("SELECT * FROM bookgame_step WHERE bookgame=$book_id AND step=$curr_step"));
if ($step['add_dex']!=0)
{
	myquery("UPDATE bookgame_users SET dex=dex+".$step['add_dex']." WHERE user_id=$user_id AND bookgame=$book_id");
}
if ($step['add_master']!=0)
{
	myquery("UPDATE bookgame_users SET master=master+".$step['add_master']." WHERE user_id=$user_id AND bookgame=$book_id");
}
if ($step['add_lucky']!=0)
{
	myquery("UPDATE bookgame_users SET lucky=lucky+".$step['add_lucky']." WHERE user_id=$user_id AND bookgame=$book_id");
}
if ($step['add_gp']!=0)
{
	myquery("UPDATE bookgame_users SET gp=gp+".$step['add_gp']." WHERE user_id=$user_id AND bookgame=$book_id");
}
$user_book = mysql_fetch_array(myquery("SELECT * FROM bookgame_users WHERE user_id=$user_id AND bookgame=$book_id"));

$string = $step['text'];
$pattern = '/\[page=(\d+)\](.+?)\[\/page\]/';
$replacement = '<a href="http://'.DOMAIN.''.$_SERVER['PHP_SELF'].'?page=\1">\2</a>';

$text = preg_replace($pattern, $replacement, $string);

echo '&nbsp;Параграф №'.$user_book['step'];

echo '<table width="100%" cellspacing=2 cellpadding=2><tr><td style="padding:15px;"><span style="text-align:center;font-family:Georgia,Tahoma,Verdana,helvetica;font-size:15px;">';
if ($print_text===true)
{
	echo $text;

	if ($step['flag']==4)
	{
		print_combat();
	}
	if ($step['flag']==5)
	{
		check_lucky();
	}  
	if ($step['flag']==1)
	{
		take_lose();
	}
	if ($step['flag']==2)
	{
		take_win();
	}
}
else
{
	echo $alt_text;
	if ($print_text==4)
	{
		print_combat();
	}
	if ($print_text==5)
	{
		check_lucky();
	}  
	if ($print_text==1)
	{
		take_lose();
	}
	if ($print_text==2)
	{
		take_win();
	}
}  

function print_stat()
{
	global $user_book;
	echo '<br /><br /><table style="font-size:13px;"><tr><td cellspacing=2 cellpadding=2 colspan=2 align=center><b>Твои характеристики:</b></td></tr>
	<tr><td>Мастерство:</td><td>'.$user_book['master'].'</td></tr>
	<tr><td>Выносливость:</td><td>'.$user_book['dex'].'</td></tr>
	<tr><td>Удача:</td><td>'.$user_book['lucky'].'</td></tr>
	<tr><td>Монеты:</td><td>'.$user_book['gp'].'</td></tr>
	</table><br /><br />';
}

echo '</span></td><td width="200">';
print_stat();
if (function_exists("print_right_add"))
{
	echo '<br /><br />';
	print_right_add();
}
echo '</td></tr></table>';
?>
<?Php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/lib_session.inc.php');
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
if($char['map_name']==18 AND $char['map_xpos']==26 AND $char['map_ypos']==21)
{
	OpenTable('title');
	echo'<br><center><font size=4 face=verdana color=#fce66b>Добро пожаловать в подземелья гномов</font><br><br>';
	echo '<hr align=center size=2 width=80%>';
	
	//массив в виде Уровень_Подземелий=>Минимальный левел допуска
	$clevel_for_level=array(1=>6,2=>10,3=>15);
	//Массив в виде Уровнь_Подземелий=>ИД_Карты_Уровня_Подземелий
	$map_id_level=array(1=>691,2=>692,3=>804);
	//ИД ресурса "Пропуск в подземелья"
	$propusk_id=propusk_item_id;
	
	$timeout = 10*60*60;
	
	//проверим на использования пропуска
	$id_propusk = 0;
	if(isset($with_propusk))
	{
		$propusk=myquery("SELECT id FROM game_items WHERE user_id=".$user_id." AND item_id=".$propusk_id." AND priznak=0 AND used=0 AND ref_id=0");
		if(mysql_num_rows($propusk)>0)
		{
			list($id_propusk)=mysql_fetch_array($propusk);
			$with_propusk = 1;
		}
		else 
			$with_propusk=0;
	}
	else 
		$with_propusk=0;

	//проверим данные юзера
	$dungeon_user=myquery("SELECT last_visit,current_level FROM dungeon_users_data WHERE user_id=".$user_id."");
	if(mysql_num_rows($dungeon_user)<=0)
	{
		$ins=myquery("INSERT INTO dungeon_users_data (user_id,last_visit,current_level,current_quest,done_quests_num) VALUES (".$user_id.",0,1,0,0)");
		$last_visit=0;
		$current_level=1;
	}else
		list($last_visit,$current_level) = mysql_fetch_array($dungeon_user);

	if($with_propusk>0)
	{
		$Item = new Item($id_propusk);
		$Item->admindelete();

		$set_access=myquery("UPDATE dungeon_users_data SET last_visit=0 WHERE user_id=".$user_id."");
		$last_visit = 0;
	}
	
	
	if (($last_visit+$timeout)<=time())
	{
		echo '<br><a href="?level=1" target="game">Войти на первый уровень подземелий</a><font color=orange> (доступны с '.$clevel_for_level[1].' уровня)</font><br>';
		echo '<br><a href="?level=2" target="game">Войти на второй уровень подземелий</a> <font color=orange> (доступны с '.$clevel_for_level[2].' уровня)</font><br>';
		echo '<br><a href="?level=3" target="game">Войти на третий уровень подземелий</a> <font color=orange> (доступны с '.$clevel_for_level[3].' уровня)</font><br>';
		if (isset($_GET['level']))
		{
			$level = (int)$_GET['level'];
			
			if($char['clevel']<$clevel_for_level[$level])
			{
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><center>';
				echo '<br><font color=#ff4433><hr align=center size=2 width=50%><br>
				Гномы-охранники не пропустили тебя в это подземелье, так как ты '.echo_sex('должен','должна').' быть не менее '.($clevel_for_level[$level]).' уровня для доступа туда.</font><br><br>';
				echo '</center></tr></td></table><hr align=center size=2 width=50%>';
			}
			elseif($current_level<$level)			
			{
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><center>';
				echo '<br><font color=#ff4433><hr align=center size=2 width=50%><br>
				Гном-охранник на входе окинул тебя оценивающим взглядом. <b>"Нет, боец"</b>, - сказал он. - <b>"Рано тебе еще на этот уровень, наберись сначала опыта в менее глубоких подземельях."</b></font><br><br>';
				echo '</center></tr></td></table><hr align=center size=2 width=50%>';
			}
			else
			switch ($level)		
			{
				case 1: case 2: case 3:
					$move=myquery("UPDATE game_users_map SET map_name='".($map_id_level[$level])."', map_xpos=0, map_ypos=0 WHERE user_id=".$user_id."");
					echo '<br><font size=4 color=green><hr align=center size=2 width=50%><br>Ты спускаешься в подземелье!</font><br><br><hr align=center size=2 width=50%>';
					echo '<br><a href="../act.php" target="game">Скатиться кубарем</a><br><br>';
					echo '<meta http-equiv="refresh" content="2;url=../act.php">';
					OpenTable('close');
					include("../inc/template_footer.inc.php");
					exit();
				break;
				default:
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><center>';
				echo '<br><font color=#ff4433><hr align=center size=2 width=50%><br>
				Гномы-охранники с огромным удивлением смотрели, как ты '.echo_sex('бился','билась').' головой о стену, пытаясь пройти на несуществующие уровни подземелий.</b></font><br><br>';
				echo '</center></tr></td></table><hr align=center size=2 width=50%>';
				break;
			}
		}
		echo '<br><a href="../act.php" target="game">Вернуться</a><br><br>';
	}
	else
	{
		$mins=ceil(($last_visit+$timeout-time())/60);
		$mins.=' '.pluralForm($mins,'минута','минуты','минут');

		echo '<br><font color=#ff4433>
		Ты не сможешь войти в подземелья еще '.$mins.'!</font><br>';
		$propusk=myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$user_id." AND item_id=".$propusk_id." AND ref_id=0 AND priznak=0");
		if(mysql_num_rows($propusk)>0)
		{
			$propusk=mysql_result($propusk,0,0);
		}else $propusk=0;
		if($propusk>0)
		echo '<br><a href="?with_propusk=1" target="game">Использовать пропуск</a><br>';
		echo '<br><a href="../act.php" target="game">Вернуться</a><br><br>';
	}
	
	OpenTable('close');
	include("../inc/template_footer.inc.php");
}else 
	echo  '<meta http-equiv="refresh" content="0;url=../act.php">';
?>
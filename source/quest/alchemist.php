<?php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '5');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');

require_once('../inc/craft/craft.inc.php');

$gp = 5;

if (function_exists("start_debug")) start_debug(); 

?>
<html>
<head>
<title>Алхимическая Лаборатория</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<body>
<?
$user_func = getFunc($user_id);
$from_house = false;
if (isset($_GET['house']) AND (get_delay_reason_id($user_id)==32))
{
	$from_house = true;
	$gp = 0;
}
$id_alchemist="15,16";

$questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." AND id IN ($id_alchemist)");
if (mysql_num_rows($questsel) OR $from_house)
{
	if ($from_house==false)
	{
		echo '<span style="z-index:-100;position:absolute;left:0%;top:0%;"><img src="http://'.IMG_DOMAIN.'/quest/lab_left.jpg"></span>';
		echo '<span style="z-index:-100;position:absolute;right:0%;top:0%;"><img src="http://'.IMG_DOMAIN.'/quest/lab_right.jpg"></span>';
	}
	echo '<br><center><font size=1 face=verdana>';
	//echo 'Извините, Лаборатория временно закрыта на восстановительные работы после недавно прошедшего по Средиземью Шторма Ужаса!';
	//echo '<br><br><br><a href="../act.php" target="game">Выйти из Лаборатории</a><br>';
	//exit;
	if ($user_func==2)
	{
		$local_func_id=craft_getFunc($user_id);
	}
	else
	{  
		$local_func_id = 0;
	}
	if (isset($_GET['mes'])) echo ('<center><b><font color="white">'.$_GET['mes'].'</font></b><br/></center>');
	if ($local_func_id!=2)
	{
		if (!isset($_GET['make']))
		{
			echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';		
			//echo '<img src="http://'.IMG_DOMAIN.'/quest/alchemist_lab.gif" align="left" border=0>';
			if (!$from_house) echo '<br><a href="../act.php" target="game">Выйти из Лаборатории</a><br><br/><b>Для работы в общественной лаборатории необходимо заплатить '.$gp.' монет!</b><br />';
			echo 'Ты видишь в лаборатории стол, на котором можно приготовить волшебные зелья.<br>Подойдя поближе, ты замечаешь рядом со столом табличку,<br> в которой приведен список волшебных зелий и из чего они варятся. Вот она:<br>';
			$eliksir = CreateArrayForCraftEliksir();
			echo '<table style="border-style:double;border-width:2px;border-color:gold;" cellspacing=2 cellpadding=2 border=1>
			<tr style="color:white;font-weight:700;text-align:center;vertical-align:middle;"><td>Название</td><td>Время</td><td>Требования</td><td>Эликсир</td></tr>';
			
			$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$char['user_id']." AND type=1"),0,0);
			$res = array();
			$sel_res = myquery("SELECT craft_resource.id,craft_resource.name,craft_resource_user.col FROM craft_resource LEFT JOIN (craft_resource_user) ON (craft_resource.id=craft_resource_user.res_id AND craft_resource_user.user_id=$user_id)");
			while ($r = mysql_fetch_array($sel_res))
			{
				$res[$r['id']]['name'] = $r['name'];
				if ($r['col']==NULL)
				{
					$res[$r['id']]['col'] = 0;
				}
				else
				{
					$res[$r['id']]['col'] = $r['col'];
				}
			}
			$kolba = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used=0 AND ref_id=0 AND item_id=".kolba_item_id."");
			
			for ($i=0;$i<sizeof($eliksir);$i++)
			{
				echo '<tr><td>';
				$craft = 1;
				if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist']) $craft = 0;
				if ($char['clevel']<$eliksir[$i]['clevel']) $craft = 0;
				if (($char['CC']-$char['CW'])<$eliksir[$i]['weight'])
				{
					if ($prov==0) $craft = 0;
				}
				if (mysql_num_rows($kolba)==0) $craft = 0;
				for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
				{
					if ($res[$eliksir[$i]['resource'][$j]['id']]['col']<$eliksir[$i]['resource'][$j]['kol']) 
					{
						$craft = 0;
						$res[$j]['f'] = 0;
					}
					else
					{
						$res[$j]['f'] = 1;
					}
				}
				
				if ($craft==1)
				{
					if ($from_house)
					{
						echo '<br><a href="?make='.$i.'&house">'.$eliksir[$i]['name'].'</a>';
					}
					elseif ($char['GP']>=$gp)
					{
						echo '<br><a href="?make='.$i.'">'.$eliksir[$i]['name'].'</a>';
					}
				}
				else
				{
					echo $eliksir[$i]['name'];
				}
				echo '</td><td>'.$eliksir[$i]['time'].'
				</td>
				<td>';
				$str_level = ''.$eliksir[$i]['clevel'].' уровень игрока';
				if ($char['clevel']<$eliksir[$i]['clevel'])
				{
					$str_level = '<span style="color:red;font-weight:900;">'.$str_level.'</span>';
				}
				$str_level_aclhemist = ''.$eliksir[$i]['alchemist'].' уровень алхимика';
				if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist'])
				{
					$str_level_aclhemist = '<span style="color:red;font-weight:900;">'.$str_level_aclhemist.'</span>';
				}
				$str_kolba = 'Колба - 1 шт (можно купить у торговцев)';
				if (mysql_num_rows($kolba)<1)
				{
					$str_kolba = '<span style="color:red;font-weight:900;">'.$str_kolba.'</span>';
				}
				echo $str_level.'<br>'.$str_level_aclhemist.'<br>'.$str_kolba.'<br>';
				for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
				{
					$str_res = $res[$eliksir[$i]['resource'][$j]['id']]['name'].' - '.$eliksir[$i]['resource'][$j]['kol'].' шт.';
					if ($res[$j]['f']==0) 
					{
						$str_res = '<span style="color:red;font-weight:900;">'.$str_res.'</span>';
					}
					echo $str_res.'<br />';
				}
				echo '</td>
				<td style="text-align:center;">';
				?><a onmousemove=movehint(event,1) onmouseover="showhint('<font color=ff0000><b><?php
					echo '<center><font color=#0000FF>'.$eliksir[$i]['name'].'</font>';
					?></b></font>','<?php
					echo '<font color=000000>';
					echo 'Вес: '.$eliksir[$i]['weight'].'<br>';
					if ($eliksir[$i]['hp']>0) echo 'Увеличивает здоровье на '.$eliksir[$i]['hp'].' '.pluralForm($eliksir[$i]['hp'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['hp']<0) echo 'Уменьшает здоровье на '.$eliksir[$i]['hp'].' '.pluralForm($eliksir[$i]['hp'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['mp']>0) echo 'Увеличивает ману на '.$eliksir[$i]['mp'].' '.pluralForm($eliksir[$i]['mp'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['mp']<0) echo 'Уменьшает ману на '.$eliksir[$i]['mp'].' '.pluralForm($eliksir[$i]['mp'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['stm']>0) echo 'Увеличивает энергию на '.$eliksir[$i]['stm'].' '.pluralForm($eliksir[$i]['stm'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['stm']<0) echo 'Уменьшает энергию на '.$eliksir[$i]['stm'].' '.pluralForm($eliksir[$i]['stm'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['str']>0) echo 'Увеличивает силу на '.$eliksir[$i]['str'].' '.pluralForm($eliksir[$i]['str'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['str']<0) echo 'Уменьшает силу на '.$eliksir[$i]['str'].' '.pluralForm($eliksir[$i]['str'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['pie']>0) echo 'Увеличивает ловкость на '.$eliksir[$i]['pie'].' '.pluralForm($eliksir[$i]['pie'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['pie']<0) echo 'Уменьшает ловкость на '.$eliksir[$i]['pie'].' '.pluralForm($eliksir[$i]['pie'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['dex']>0) echo 'Увеличивает выносливость на '.$eliksir[$i]['dex'].' '.pluralForm($eliksir[$i]['dex'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['dex']<0) echo 'Уменьшает выносливость на '.$eliksir[$i]['dex'].' '.pluralForm($eliksir[$i]['dex'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['vit']>0) echo 'Увеличивает защиту на '.$eliksir[$i]['vit'].' '.pluralForm($eliksir[$i]['vit'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['vit']<0) echo 'Уменьшает защиту на '.$eliksir[$i]['vit'].' '.pluralForm($eliksir[$i]['vit'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['ntl']>0) echo 'Увеличивает интеллект на '.$eliksir[$i]['ntl'].' '.pluralForm($eliksir[$i]['ntl'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['ntl']<0) echo 'Уменьшает интеллект на '.$eliksir[$i]['ntl'].' '.pluralForm($eliksir[$i]['ntl'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['spd']>0) echo 'Увеличивает мудрость на '.$eliksir[$i]['spd'].' '.pluralForm($eliksir[$i]['spd'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['spd']<0) echo 'Уменьшает мудрость на '.$eliksir[$i]['spd'].' '.pluralForm($eliksir[$i]['spd'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['lucky']>0) echo 'Увеличивает удачу на '.$eliksir[$i]['lucky'].' '.pluralForm($eliksir[$i]['lucky'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['lucky']<0) echo 'Уменьшает удачу на '.$eliksir[$i]['lucky'].' '.pluralForm($eliksir[$i]['lucky'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['cc']>0) echo 'Увеличивает вес на '.$eliksir[$i]['cc'].' '.pluralForm($eliksir[$i]['cc'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['cc']<0) echo 'Уменьшает вес на '.$eliksir[$i]['cc'].' '.pluralForm($eliksir[$i]['cc'],'единицу','единицы','единиц').'<br>';
					if ($eliksir[$i]['dlit']>0) echo 'Время действия эликсира: '.ceil($eliksir[$i]['dlit']/60).' '.pluralForm(ceil($eliksir[$i]['dlit']/60),'минута','минуты','минут').'<br>';    
					//echo 'Время приготовления: '.ceil($eliksir[$i]['time']/60).' '.pluralForm(ceil($eliksir[$i]['time']/60),'минута','минуты','минут').'<br>';
					/*echo ''.$eliksir[$i]['alchemist'].' уровень алхимика<br>Колба - 1 шт (можно купить у торговцев)<br>';
					for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
					{
						echo $res[$eliksir[$i]['resource'][$j]['id']]['name'].' - '.$eliksir[$i]['resource'][$j]['kol'].' шт.<br>';
						if ($res[$eliksir[$i]['resource'][$j]['id']]['col']<$eliksir[$i]['resource'][$j]['kol']) $craft = 0;
					}*/
					echo '</font>';
					?>',0,1,event,1)" onmouseout="showhint('','',0,0,event,1)"><?php echo '<img border=0 src="http://'.IMG_DOMAIN.'/item/'.$eliksir[$i]['img'].'.gif"></a></td><td>';
				/*if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist']) $craft = 0;
				if ($char['clevel']<$eliksir[$i]['clevel']) $craft = 0;
				if (($char['CC']-$char['CW'])<$eliksir[$i]['weight'])
				{
					if ($prov==0) $craft = 0;
				}
				if (mysql_num_rows($kolba)==0) $craft = 0;
				if ($craft==1)
				{
					if ($from_house)
					{
						echo '<br><a href="?make='.$i.'&house">Сварить зелье</a>';
					}
					elseif ($char['GP']>=$gp)
					{
						echo '<br><a href="?make='.$i.'">Сварить зелье</a>';
					}
				}
				else
				{
					echo '&nbsp;';
				}*/
				echo '</tr>';
			}
			
			echo '</table><br><br>';
			echo 'У тебя сейчас '.getCraftLevel($user_id,2).' уровень знания приготовления волшебных зелий (уровень алхимика)';
		}
		else
		{
			if (isset($_POST['digit']) OR isset($_POST['begin']))
			{
				//начинаем варку зелий
				$i = (int)$_GET['make'];
				$craft = 1;
				
				$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$char['user_id']." AND type=1"),0,0);
				$res = array();
				$sel_res = myquery("SELECT craft_resource.id,craft_resource.name,craft_resource_user.col FROM craft_resource LEFT JOIN (craft_resource_user) ON (craft_resource.id=craft_resource_user.res_id AND craft_resource_user.user_id=$user_id)");
				while ($r = mysql_fetch_array($sel_res))
				{
					$res[$r['id']]['name'] = $r['name'];
					if ($r['col']==NULL)
					{
						$res[$r['id']]['col'] = 0;
					}
					else
					{
						$res[$r['id']]['col'] = $r['col'];
					}
				}
				$kolba = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used=0 AND ref_id=0 AND item_id=".kolba_item_id."");
				
				$eliksir = CreateArrayForCraftEliksir(); 
				if ($i>=0 AND $i<sizeof($eliksir))
				{
				
//$dlit=5;
//$dlit = $eliksir[$i]['time'];

					$dlit = $eliksir[$i]['time'];
					//$dlit-= $char['alchemist']*60;
					if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist']) {$craft = 0;}
					elseif ($char['clevel']<$eliksir[$i]['clevel']) {$craft = 0;}
					else
					{
						for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
						{
							if ($res[$eliksir[$i]['resource'][$j]['id']]['col']<$eliksir[$i]['resource'][$j]['kol']) $craft = 0;
						}
					}
					if ($craft==1)
					{
						if (mysql_num_rows($kolba)==0) $craft = 0;
					}
					if (($char['CC']-$char['CW'])<$eliksir[$i]['weight'])
					{
						if ($prov==0) $craft = 0;
					}
				}
				else
				{
					$craft = 0;
				}
				if (!$from_house AND $char['GP']<$gp)
				{
					$craft = 0;
				}
				
				if ($craft==1 and isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'])
				{
					craft_setFunc($user_id,2);
					set_delay_reason_id($user_id,4);
					myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
					myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit,eliksir) VALUES ($user_id,'alchemist',".time().",$dlit,$i)");
					$change_weight = 0;
					//удалим колбу
					list($kolba_weight)=mysql_fetch_array(myquery("SELECT weight FROM game_items_factsheet WHERE id=".kolba_item_id." LIMIT 1"));
					$change_weight=$change_weight+$kolba_weight;
					$kolba_check=myquery("Select count_item FROM game_items WHERE user_id=$user_id AND used=0 AND item_id=".kolba_item_id." AND priznak=0");
					$kolba_check=mysql_fetch_array($kolba_check);
					if ($kolba_check['count_item']>1)
					{
						myquery("Update game_items Set count_item=count_item-1 WHERE user_id=$user_id AND used=0 AND ref_id=0 AND item_id=".kolba_item_id." AND priznak=0");
					}
					elseif ($kolba_check['count_item']==1)
					{
						myquery("DELETE FROM game_items WHERE user_id=$user_id AND used=0 AND ref_id=0 AND item_id=".kolba_item_id." AND priznak=0 LIMIT 1");
					}
					//удаляем ресурсы
					$eliksir = CreateArrayForCraftEliksir();
					for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
					{
						$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$eliksir[$i]['resource'][$j]['id'].""));
						list($kol) = mysql_fetch_array(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id'].""));
						$change_weight=$change_weight+($eliksir[$i]['resource'][$j]['kol']*$ress['weight']);
						if ($kol>$eliksir[$i]['resource'][$j]['kol'])
						{
							myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$eliksir[$i]['resource'][$j]['kol'].") WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id']."");
						}
						else
						{
							myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id']."");
						}
					}
					if (!$from_house)
					{
						myquery("UPDATE game_users SET CW=CW-$change_weight-".($gp*money_weight).",GP=GP-$gp WHERE user_id=$user_id");
						setGP($user_id,-$gp,66);
					}
					else
					{
						myquery("UPDATE game_users SET CW=CW-$change_weight WHERE user_id=$user_id");
					}
							
					ForceFunc($user_id,2);			
					if (!$from_house)
					{
						setLocation("../craft.php");
					}
					else
					{
						echo '<script>top.window.frames.game.location.replace("../craft.php?house");</script>';
					}
				}
				else
				{
					echo 'Ты не можешь сварить это зелье!<br><br><br><br>';
					if (!$from_house) echo '<a href="../act.php" target="game">Выйти из Лаборатории</a>'; 
				}
			}
			else
			{
				echo 'Для начала работы введи указанный ниже код <br>и нажми кнопку "Начать варку зелий"<br>';
				echo '<br><img src="../captcha/index.php?'.time().'">';
				echo '<form autocomplete="off" action="" method="POST" name="captcha"><br>
				<input type="text" size=6 maxsize=6 name="digit"><br>
				<input type="submit" name="subm" value="Начать варку зелий">
				</form>';
			}
		}
	}
	if (!$from_house)
	{
		echo '<div style="position:absolute;right:0%;top:0%;">Разработано при участии игрока <a href="http://'.DOMAIN.'/view/?name=SVukraine" target="_blank">SVukraine</a><br>';
		echo 'Рисунки созданы игроком <a href="http://'.DOMAIN.'/view/?name=Tagava" target="_blank">Tagava</a></div>';
	}
}
																   

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug(); 

?>
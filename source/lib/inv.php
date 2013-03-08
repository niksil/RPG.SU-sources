<?php
if (function_exists("start_debug")) start_debug(); 
if ($_SERVER['PHP_SELF']=="/act.php" OR $_SERVER['PHP_SELF']=="/craft.php" OR ($_SERVER['PHP_SELF']=="/lib/inv.php" AND isset($_GET['house'])))
{
}
else
{
	die($_SERVER['PHP_SELF']);
}
$js_dir="";
$from_house = false;
$from_craft = false;
if (isset($_GET['house']))
{
	$js_dir = "../";
	$dirclass = '../class';
	require_once('../inc/engine.inc.php');
	include('../inc/xray.inc.php');
	include('../inc/lib.inc.php');
	include('../inc/lib_session.inc.php');
	include('../inc/functions.php');
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
	<script language="JavaScript" type="text/javascript" src="../js/cookies.js"></script>
	<style type="text/css">@import url("../style/global.css");</style>
	</head>
	<body>
	<?
	if (getFunc($user_id)==9)
	{
		$from_house = true;
	}
}
if (getFunc($user_id)==2)
{
	$from_craft = true;
}

echo'<SCRIPT language=javascript src="'.$js_dir.'js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';

echo'
<div id="PopupDiv" style="position:absolute; visibility:hide;"></div>
<script language="JavaScript">
var closeb=\'<img src="http://'.IMG_DOMAIN.'/close.gif" border=0>\';
</script><script language="JavaScript" src="'.$js_dir.'js/popup.js"></script>';

if (isset($_GET['option']) AND $_GET['option']=='eliksir' AND isset($_GET['id']) AND $_GET['id']>0 AND !$from_house AND !$from_craft)
{   
	$Item = new Item();
	$Item->use_item($_GET['id']);
	QuoteTable('open');
	echo $Item->message;
	QuoteTable('close');
}

if (isset($_GET['option']) AND $_GET['option']=='charge' AND !$from_house AND !$from_craft AND isset($_GET['id']) AND $_GET['id']>0)
{   
	$Item = new Item($_GET['id']);
	$zar = $Item->getFact('item_uselife')-$Item->getItem('count_item');
	if ($Item->getFact('type')==3 AND $zar>0)
	{
		$sel_last_event = myquery("SELECT timestamp FROM game_users_event WHERE user_id=$user_id AND event=1");
		if ($sel_last_event!=false AND mysql_num_rows($sel_last_event)>0)
		{
			list($last_event) = mysql_fetch_array($sel_last_event);
		}
		else
		{
			$last_event = 0;
		}
		if (($last_event+$Item->getFact('cooldown'))<time())
		{
			if ($Item->item['item_uselife_max']>1 OR $Item->fact['breakdown']==0)
			{
				//формула зарядки артефакта - 10 маны и 5 энергии на 1 заряд
				if ($char['STM_MAX']>$char['MP_MAX'])
				{
					$mp = 10;
					$stm = 5;
				}
				else
				{
					$mp = 5;
					$stm = 10;
				}
				
				$kol_mp = floor($char['MP']/$mp);
				$kol_stm = floor($char['STM']/$stm);
				$kol = min($kol_mp,$kol_stm);
				$kol = min($kol,$zar);
				if ($kol>0)
				{
					$minus_mp = $mp*$kol;
					$minus_stm = $stm*$kol;
					$update = myquery("UPDATE game_users SET MP=MP-$minus_mp,STM=STM-$minus_stm WHERE user_id=$user_id");
					$update = myquery("UPDATE game_items SET count_item = count_item+$kol WHERE id = '".$_GET['id']."'");
					$char = myquery("SELECT * FROM game_users WHERE user_id=$user_id");
					$char = mysql_fetch_array($char);
					list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
					list($last_active) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$user_id'"));
					$char['map_name']=$char_map_name;
					$char['map_xpos']=$char_map_xpos;
					$char['map_ypos']=$char_map_ypos;
					$char['last_active']=$last_active;
					if ($last_event==0)
					{
						myquery("INSERT INTO game_users_event (user_id,event,timestamp) VALUES ($user_id,1,".time().")");
					}
					else
					{
						myquery("UPDATE game_users_event SET timestamp=".time()." WHERE user_id=$user_id AND event=1");
					}
					QuoteTable('open');
					echo 'Артефакт заряжен на '.$kol.' '.pluralForm($kol,'заряд','заряда','зарядов').'!';
					QuoteTable('close');
				}
			}
			else
			{
				QuoteTable('open');
				echo 'Магия артефакта была полностью израсходована. Артефакт рассыпался в твоих руках в пыль!';
				$Item->admindelete();
				QuoteTable('close');
			}
		}
		else
		{
			$razn = ($last_event+$Item->getFact('cooldown'))-time();
			$min = floor($razn/60);
			$sec = $razn-$min*60;
			QuoteTable('open');
			echo 'Ты уже '.echo_sex('заряжал','заряжала').' артефакт '.date("d.m.Y H:i:s",$last_event).'. Тебе надо набраться сил. Попробуй еще раз через '.$min.' мин. '.$sec.' сек.';
			QuoteTable('close');
		}
	}
}

if (!empty($reason))
{
	if ($_SERVER['PHP_SELF']=="/act.php")
	{
		include('inc/template_reason.inc.php');
	}
	else
	{
		include('../inc/template_reason.inc.php');
	}
}

$sel_used_items = myquery("SELECT id,item_id,used FROM game_items WHERE user_id=$user_id AND priznak=0 AND used>0");
$used_items = array();
while ($it = mysql_fetch_array($sel_used_items))
{
	$used_items[$it['used']]['id']=$it['id'];
	$used_items[$it['used']]['item_id']=$it['item_id'];
}

echo '<table class=m background="http://'.IMG_DOMAIN.'/nav/image_01.jpg" width="100%" cellpadding="15" cellspacing="0" border="0"><tr><td valign="top">';
if (!$from_house)
{
	echo '<div align="right"><img src="http://'.IMG_DOMAIN.'/nav/inv.gif" align=right></div>';
}
  
PrintInv($user_id,0);

$result_ves = myquery("SELECT CW, CC FROM game_users WHERE user_id=$user_id LIMIT 1");
$items = mysql_fetch_array($result_ves);

echo '
</td>
<td valign="top">
';


if ((!isset($_GET['make_amulet']) AND !isset($_GET['make_svitok'])) OR $from_house OR $from_craft)
{
	QuoteTable('open');
	echo '<b><center> Общий вес:  '.$items['CW'].' / '.$items['CC'].'</b></center>';
	$result_items = myquery("SELECT DISTINCT game_items_factsheet.type FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.used=0 and game_items_factsheet.type<99 and game_items_factsheet.type!=12 AND game_items_factsheet.type!=13 ORDER BY game_items_factsheet.type");
	if (mysql_num_rows($result_items))
	{
		while($result=mysql_fetch_array($result_items))
		{
			$typ=$result['type'];
			echo '<a name="anchor'.$typ.'" href="#anchor'.$typ.'" onClick=\'expand( "d'.$typ.'", "d'.$typ.'", "d'.$typ.'", "http://'.DOMAIN.'/funct.php?item='.$typ.''.(($from_house) ? '&house&option='.$option.'' : '').'" );\'><li><b>'.type_str($result['type']).'</b></li></a>'; 
			echo '<div id="d'.$typ.'"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
		}
	}
	QuoteTable('close');

	echo '<br />';

	$result_items = mysql_result(myquery("SELECT COUNT(*) from game_items LEFT JOIN game_items_factsheet ON game_items_factsheet.id=game_items.item_id WHERE game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.used=0 AND game_items.count_item>0 and game_items_factsheet.type=12"),0,0);
	if ($result_items > 0)
	{
		QuoteTable('open');
		echo '<a name="anchor12" href="#anchor12" onClick=\'expand( "d12", "d12", "d12", "http://'.DOMAIN.'/funct.php?item=12'.(($from_house) ? '&house&option='.$option.'' : '').'" );\'><li><b>Свитки</b></li></a>';
		echo '<div id="d12"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
		QuoteTable('close');
		echo '<br />';
	}

	$result_items = mysql_result(myquery("SELECT COUNT(*) from game_items LEFT JOIN game_items_factsheet ON game_items_factsheet.id=game_items.item_id WHERE game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.used=0 AND game_items.count_item>0 and game_items_factsheet.type=13"),0,0);
	if ($result_items > 0)
	{
		QuoteTable('open');
		echo '<a name="anchor13" href="#anchor13" onClick=\'expand( "d13", "d13", "d13", "http://'.DOMAIN.'/funct.php?item=13'.(($from_house) ? '&house&option='.$option.'' : '').'" );\'><li><b>Эликсиры</b></li></a>';
		echo '<div id="d13"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
		QuoteTable('close');
		echo '<br />';
	}

	//wm
	if (!$from_house AND !$from_craft)
	{
		//заклейменные вещи
		$is_glava = false;
		$sel_clan_items = false;
		if (mysql_num_rows(myquery("SELECT clan_id FROM game_clans WHERE glava=$user_id AND raz=0"))>0)
		{
			$is_glava = true;
			$sel_clan_items = myquery("SELECT game_items.id,game_items_factsheet.img,game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.kleymo=1 AND game_items.kleymo_id=".$char['clan_id']." AND (game_items.user_id<>".$char['user_id']." OR (game_items.user_id=".$char['user_id']." AND game_items.priznak NOT IN (0))) ORDER BY game_items.kleymo_nomer ASC");
		}
		$sel_user_items = myquery("SELECT game_items.id,game_items_factsheet.img,game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.kleymo=2 AND game_items.kleymo_id=".$char['user_id']." AND (game_items.user_id<>".$char['user_id']." OR (game_items.user_id=".$char['user_id']." AND game_items.priznak NOT IN (0))) ORDER BY game_items.kleymo_nomer ASC");  
		
		if (($sel_clan_items!=false AND mysql_num_rows($sel_clan_items)>0)OR($sel_user_items!=false AND mysql_num_rows($sel_user_items)>0))
		{
			QuoteTable('open');
			if ($sel_clan_items!=false AND mysql_num_rows($sel_clan_items)>0)
			{
				echo '<a name="anchor228" href="#anchor228" onClick=\'expand( "d228", "d228", "d228", "funct.php?item=228'.((isset($_GET['house'])) ? '&house&option='.$option.'' : '').'" );\'><li><b>Клановые клейменные предметы</b></li></a>';
				echo '<div id="d228"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
			}
			if ($sel_user_items!=false AND mysql_num_rows($sel_user_items)>0)
			{
				echo '<a name="anchor229" href="#anchor229" onClick=\'expand( "d229", "d229", "d229", "funct.php?item=229'.((isset($_GET['house'])) ? '&house&option='.$option.'' : '').'" );\'><li><b>Личные клейменные предметы</b></li></a>';
				echo '<div id="d229"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
			}
			QuoteTable('close');
		}
		
		$result_items = myquery("SELECT * FROM game_wm WHERE user_id=$user_id ORDER BY type");
		if (mysql_num_rows($result_items) > 0)
		{
			QuoteTable('open');
			echo '<table cellpadding="0" cellspacing="4" border="0">';
			while ($items = mysql_fetch_array($result_items))
			{
				$ar = wm_str($items['type']);
				$ss='&#149; '.$ar[0].' активирован';
				if ($items['type']==2)
				{
					$ss='';//&#149; <a href="act.php?tel&func=inv">Свиток телепорта</a>';
					if (!isset($see_teleport))
					{
						$ss.='<center><form action="" method="post" autocomplete="off">';
						$ss.='Позиция: <input name="map_xpos" type="text" value="'.$char['map_xpos'].'" size="2" maxlength="2">
						<input name="map_ypos" type="text" value="'.$char['map_ypos'].'" size="2" maxlength="2">
						<input name="see_teleport" type="submit" value="Телепортироваться">';
					}
					else
					{
						$prov=myquery("select count(*) from game_map where xpos='$map_xpos' and ypos='$map_ypos' and name=".$char['map_name']."");
						if (@mysql_result($prov,0,0)>0)
						{
							$ss.='Ты '.echo_sex('телепортировался','телепортировалась').'';
							$result=myquery("update game_users_map set map_xpos='$map_xpos',map_ypos='$map_ypos' where user_id='$user_id'");
						}
						else
						{
							$ss.='Такой гексы не существует';
						}
					}
				 }
				if ($items['type']==3)
				{
					$ss='';//&#149; <a href="act.php?full&func=inv">Использовать свиток полного восстановления</a>';
					if (!isset($see_full))
					{
						$ss.='<center><form action="" method="post">';
						$ss.='<input name="see_full" type="submit" value="Полностью восcтановиться">';
					}
					else
					{
						$ss.='Ты полностью '.echo_sex('восстановлен','восстановлена').'';
						$result=myquery("update game_users set HP=HP_MAX,MP=MP_MAX,STM=STM_MAX where user_id='$user_id'");
					}
				}
				if ($items['type']==4)
				{
					$ss='';//&#149; <a href="act.php?mal&func=inv">Использовать свиток частичного восстановления</a>';
					if (!isset($see_mal))
					{
						$ss.='<center><form action="" method="post">';
						$ss.='<input name="see_mal" type="submit" value="Частично восcтановиться">';
					}
					else
					{
						$ss.='Ты частично '.echo_sex('восстановлен','восстановлена').'';
						$result=myquery("update game_users set HP=HP+HP_MAX/2,MP=MP+MP_MAX/2,STM=STM+STM_MAX/2 where user_id='$user_id'");
					}
				}
				echo '<tr><td><img src="http://'.IMG_DOMAIN.'/item/' . $ar[1] . '.gif" width="32" height="32" border="0" alt=""></td>
				<td>'.$ss.'</td>
				</tr>';
			}
			echo '</table>';
			QuoteTable('close');
			echo '<br />';
		}
	}

	if (isset($_GET['dropres']) AND !$from_house AND !$from_craft)
	{
		$resid = (int)$_GET['dropres'];
		$selres = myquery("SELECT * FROM craft_resource_user WHERE user_id=$user_id AND res_id=$resid");
		if ($selres!=false AND mysql_num_rows($selres)>0)
		{
			$res = mysql_fetch_array($selres);
			$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$resid"));
			if (!isset($_POST['submit']))
			{
				QuoteTable('open');
				echo 'Укажи кол-во ресурса, которые ты хочешь выбросить:<br>';
				echo '<form action="act.php?func=inv&dropres='.$resid.'" method="POST">';
				echo '<img src=http://'.IMG_DOMAIN.'/item/resources/'.$ress['img3'].'.gif border=0 width=50 height=50> '.$ress['name'].' - есть '.$res['col'].' ед.<br><br>';
				echo 'Выбросить: <input type="text" name="kol" value="0" size=5 maxsize=5> ед.    ';
				echo '<input type="submit" name="submit" value="Выбросить">';
				echo '</form>';
				QuoteTable('close');
			}
			else
			{
				$kol_res = (int)$_POST['kol'];
				if ($kol_res<=$res['col'] AND $kol_res>0)
				{
					if ($kol_res<$res['col'])
					{
						myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$kol_res.") WHERE user_id=$user_id AND res_id=$resid");
					}
					else
					{
						myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=$resid");
					}
					myquery("UPDATE game_users SET CW=CW-".($kol_res*$ress['weight'])." WHERE user_id=$user_id");
					$seldrop = myquery("SELECT * FROM craft_resource_market WHERE town=0 AND map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND res_id=$resid LIMIT 1");
					if ($seldrop!=false AND mysql_num_rows($seldrop)>0 AND $kol_res>0)
					{
						$drop = mysql_fetch_array($seldrop);
						myquery("UPDATE craft_resource_market SET col=col+'".$kol_res."' WHERE id=".$drop['id']."");	
					}
					else
					{
						myquery("INSERT INTO craft_resource_market (town,col,map_name,map_xpos,map_ypos,res_id) VALUES (0,'".$kol_res."',".$char['map_name'].",".$char['map_xpos'].",".$char['map_ypos'].",$resid)");
					}
					QuoteTable('open');
					echo 'Выброшено '.$kol_res.' ед. из '.$res['col'].' ед '.$ress['name'].'';
					QuoteTable('close');
				}
			}
		}
	}

	if (!$from_house)
	{
		include(getenv('DOCUMENT_ROOT').'/craft/inv.inc.php');
		if (!$from_craft) include ('wm.php');
	}
}
elseif (isset($_GET['make_amulet']))
{
	$count_rune=0;
	if (isset($_POST['save']))
	{ 
		$result_items = myquery("SELECT game_items.id as idd, game_items.count_item, game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
		$kol=0;
		while ($items1 = mysql_fetch_array($result_items))
		{
			if (($_POST[$items1['idd']])>0) $kol=$kol+$_POST[$items1['idd']];
			if ($_POST[$items1['idd']]>$items1['count_item']) $count_rune=1;
		}
		if ($count_rune==1)
		{
			echo '<b>У тебя недостаточно Рун для создания предмета</b>';
		}
		elseif ($kol!=7)
		{
			echo '<b>Для создания Рунного предмета необходимо использовать 7 Рун</b>';
		}
		else
		{
			$minus_weight=0;
			$ar = array();
			$ar['str']=0;$ar['dex']=0;$ar['vit']=0;$ar['spd']=0;$ar['ntl']=0;$ar['pie']=0;$ar['lucky']=0;$ar['hp_p']=0;$ar['mp_p']=0;$ar['stm_p']=0;
			$result_items = myquery("SELECT game_items.id as idd, game_items.count_item, game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
			while ($items1 = mysql_fetch_array($result_items))
			{
				if (($_POST[$items1['idd']])>0)
				{
					$ar['str']+=$items1['dstr']*$_POST[$items1['idd']];    
					$ar['dex']+=$items1['ddex']*$_POST[$items1['idd']];    
					$ar['vit']+=$items1['dvit']*$_POST[$items1['idd']];    
					$ar['spd']+=$items1['dspd']*$_POST[$items1['idd']];    
					$ar['ntl']+=$items1['dntl']*$_POST[$items1['idd']];    
					$ar['pie']+=$items1['dpie']*$_POST[$items1['idd']];    
					$ar['lucky']+=$items1['dlucky']*$_POST[$items1['idd']];    
					$ar['hp_p']+=$items1['hp_p']*$_POST[$items1['idd']];    
					$ar['mp_p']+=$items1['mp_p']*$_POST[$items1['idd']];    
					$ar['stm_p']+=$items1['stm_p']*$_POST[$items1['idd']];  
					$minus_weight+=$items1['weight']*$_POST[$items1['idd']];  
				}
			}
			$ar_type = array(7,2,8,6,5);
			$type = $ar_type[mt_rand(0,4)];
			$name = '';
			$img = '';
			$img_big = '';
			switch ($type)
			{
				case 7: 
				{
					$name = 'Редкая Магия';
					$img = 'constr/R-magia';
					$img_big = '';
				} 
				break;
				case 2: 
				{
					$name='Редкое Кольцо'; 
					$img = 'constr/R-ring';
					$img_big = '';
				}
				break;
				case 8: 
				{
					$name='Редкий Пояс'; 
					$img = 'constr/R-poyas';
					$img_big = '';
				}
				break;
				case 6: 
				{
					$name='Редкий Шлем'; 
					$img = 'constr/R-shlem';
					$img_big = '';
				}
				break;
				case 5:
				{
					$name='Редкий Доспех'; 
					$img = 'constr/R-dospeh';
					$img_big = '';
				}
				break;
			}
			$max = max($ar['str'],$ar['dex'],$ar['vit'],$ar['spd'],$ar['ntl'],$ar['pie'],$ar['lucky']);
			$kol_har = 0;
			if ($ar['str']==$max)
			{
				$name_har=' Силы';
				$kol_har++;
			}
			if ($ar['ntl']==$max)
			{
				$name_har=' Интеллекта';
				$kol_har++;
			}
			if ($ar['spd']==$max)
			{
				$name_har=' Мудрости';
				$kol_har++;
			}
			if ($ar['pie']==$max)
			{
				$name_har=' Ловкости';
				$kol_har++;
			}
			if ($ar['dex']==$max)
			{
				$name_har=' Выносливости';
				$kol_har++;
			}
			if ($ar['vit']==$max)
			{
				$name_har=' Защиты';
				$kol_har++;
			}
			if ($ar['lucky']==$max)
			{
				$name_har=' Удачи';
				$kol_har++;
			}
			if ($kol_har==1)
			{
				$name.=$name_har;
			}		

			$selhave = myquery("SELECT * FROM quest_constructor ORDER BY create_time ASC");
			$count=mysql_num_rows($selhave);
			while ($count>=5)
			{
				//удаляем старый предмет
				$old_it = mysql_fetch_assoc($selhave);
				$ItemDel = new Item($old_it['item_id']);
				$ItemDel->admindelete();
				$pismo = "Твой предмет ".$ItemDel->fact['name']." был разрушен, т.к. был создан новый [b]Редкий Предмет[/b]! <br />Магические силы Мира не могут удерживать более 5 Редких Предметов и твой предмет, как самый старый из всех существующих, был разрушен, оказавшись без подпитки магической энергией Средиземья!";
				$theme = "Твой предмет ".$ItemDel->fact['name']." был разрушен";
				myquery("DELETE FROM game_items_factsheet WHERE id=".$ItemDel->fact['id']."");
				myquery("INSERT INTO game_pm (komu,theme,post,time) VALUES (".$old_it['user_id'].",'".$theme."','".$pismo."',UNIX_TIMESTAMP())");
				myquery("DELETE FROM quest_constructor WHERE id=".$old_it['id']."");
				$count=$count-1;
			}
			// Создаём новый предмет
			$item_fact_id = mysqlresult(myquery("SELECT id FROM game_items_factsheet ORDER BY id DESC LIMIT 1"),0,0);
			$item_fact_id++;
			myquery("INSERT INTO game_items_factsheet SET 
			id=$item_fact_id,
			name='$name',
			type='$type',
			weight='1',
			img='$img',
			dstr='".$ar['str']."',
			dntl='".$ar['ntl']."',
			dpie='".$ar['pie']."',
			dvit='".$ar['vit']."',
			ddex='".$ar['dex']."',
			dspd='".$ar['spd']."',
			dlucky='".$ar['lucky']."',
			hp_p='".$ar['hp_p']."',
			mp_p='".$ar['mp_p']."',
			stm_p='".$ar['stm_p']."',
			curse='Очень редкий предмет, который создается силой Магии 7 разных Рун Амулета',
			view='1',
			redkost='K',
			imgbig='$img_big',
			personal='1',
			item_uselife_max='10',
			breakdown=1
			");
			$Item = new Item();
			$ar = $Item->add_user($item_fact_id,$user_id,0);
				myquery("INSERT INTO quest_constructor SET user_id=".$user_id.",item_id=".$ar[1].",create_time=UNIX_TIMESTAMP()");
				echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
				$Item->info($ar[1]);
				$result_items = myquery("SELECT game_items.id as idd, game_items.count_item, game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
				while ($items1 = mysql_fetch_array($result_items))
				{
					if (($_POST[$items1['idd']])>0 and ($items1['count_item']-$_POST[$items1['idd']])>0)
					{
						myquery("UPDATE game_items SET count_item=count_item-".$_POST[$items1['idd']]." WHERE id=".$items1['idd']."");
					}
					elseif (($_POST[$items1['idd']])>0 and ($items1['count_item']-$_POST[$items1['idd']])==0)
					{
						myquery("DELETE FROM game_items WHERE id=".$items1['idd']."");
					}
				
				}
				myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=".$this->char['user_id']."");                
			}
	}	
		
		/*
		$col = 0;
		$str_id = implode("','",$_POST['items']);
		$str_id = "'".$str_id."'";
		$minus_weight=0;
		$result_items = myquery("SELECT game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id AND game_items.id IN (".$str_id.") ORDER BY RAND()");
		if (mysql_num_rows($result_items)!=7)
		{
			echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки амулета необходимо выбрать ровно 7 Рун</b><br /><br />';
		}
		else
		{
			//собираем амулет
			//для каждого амулета создаем новую запись в game_items_factsheet
			$ar = array();
			$ar['str']=0;$ar['dex']=0;$ar['vit']=0;$ar['spd']=0;$ar['ntl']=0;$ar['pie']=0;$ar['lucky']=0;$ar['hp_p']=0;$ar['mp_p']=0;$ar['stm_p']=0;
			while ($it = mysql_fetch_array($result_items))
			{
				$ar['str']+=$it['dstr'];    
				$ar['dex']+=$it['ddex'];    
				$ar['vit']+=$it['dvit'];    
				$ar['spd']+=$it['dspd'];    
				$ar['ntl']+=$it['dntl'];    
				$ar['pie']+=$it['dpie'];    
				$ar['lucky']+=$it['dlucky'];    
				$ar['hp_p']+=$it['hp_p'];    
				$ar['mp_p']+=$it['mp_p'];    
				$ar['stm_p']+=$it['stm_p'];  
				$minus_weight+=$it['weight'];  
			}
			//die("<pre>".print_r($ar,true)."</pre>");
			$ar_type = array(7,2,8,6,5);
			$type = $ar_type[mt_rand(0,4)];
			$name = '';
			$img = '';
			$img_big = '';
			switch ($type)
			{
				case 7: 
				{
					$name = 'Редкая Магия';
					$img = 'constr/R-magia';
					$img_big = '';
				} 
				break;
				case 2: 
				{
					$name='Редкое Кольцо'; 
					$img = 'constr/R-ring';
					$img_big = '';
				}
				break;
				case 8: 
				{
					$name='Редкий Пояс'; 
					$img = 'constr/R-poyas';
					$img_big = '';
				}
				break;
				case 6: 
				{
					$name='Редкий Шлем'; 
					$img = 'constr/R-shlem';
					$img_big = '';
				}
				break;
				case 5:
				{
					$name='Редкий Доспех'; 
					$img = 'constr/R-dospeh';
					$img_big = '';
				}
				break;
			}
			$max = max($ar['str'],$ar['dex'],$ar['vit'],$ar['spd'],$ar['ntl'],$ar['pie'],$ar['lucky']);
			$kol_har = 0;
			if ($ar['str']==$max)
			{
				$name_har=' Силы';
				$kol_har++;
			}
			if ($ar['ntl']==$max)
			{
				$name_har=' Интеллекта';
				$kol_har++;
			}
			if ($ar['spd']==$max)
			{
				$name_har=' Мудрости';
				$kol_har++;
			}
			if ($ar['pie']==$max)
			{
				$name_har=' Ловкости';
				$kol_har++;
			}
			if ($ar['dex']==$max)
			{
				$name_har=' Выносливости';
				$kol_har++;
			}
			if ($ar['vit']==$max)
			{
				$name_har=' Защиты';
				$kol_har++;
			}
			if ($ar['lucky']==$max)
			{
				$name_har='Удачи';
				$kol_har++;
			}
			if ($kol_har==1)
			{
				$name.=$name_har;
			}
			

			//создаем новый предмет
			
			$item_fact_id = mysqlresult(myquery("SELECT id FROM game_items_factsheet ORDER BY id DESC LIMIT 1"),0,0);
			$item_fact_id++;
			myquery("INSERT INTO game_items_factsheet SET 
			id=$item_fact_id,
			name='$name',
			type='$type',
			weight='1',
			img='$img',
			dstr='".$ar['str']."',
			dntl='".$ar['ntl']."',
			dpie='".$ar['pie']."',
			dvit='".$ar['vit']."',
			ddex='".$ar['dex']."',
			dspd='".$ar['spd']."',
			dlucky='".$ar['lucky']."',
			hp_p='".$ar['hp_p']."',
			mp_p='".$ar['mp_p']."',
			stm_p='".$ar['stm_p']."',
			curse='Очень редкий предмет, который создается силой Магии 7 разных Рун Амулета',
			view='1',
			redkost='K',
			imgbig='$img_big',
			personal='1',
			item_uselife_max='10',
			breakdown=1
			");
			$Item = new Item();
			$ar = $Item->add_user($item_fact_id,$user_id,0);
			
			if ($ar[0]>0)
			{
				$selhave = myquery("SELECT * FROM quest_constructor ORDER BY create_time ASC");
				if (mysql_num_rows($selhave)==5)
				{
					//удаляем старый предмет
					$old_it = mysql_fetch_assoc($selhave);
					$ItemDel = new Item($old_it['item_id']);
					$ItemDel->admindelete();
					$pismo = "Твой предмет ".$ItemDel->fact['name']." был разрушен, т.к. был создан новый [b]Редкий Предмет[/b]! <br />Магические силы Мира не могут удерживать более 5 Редких Предметов и твой предмет, как самый старый из всех существующих, был разрушен, оказавшись без подпитки магической энергией Средиземья!";
					$theme = "Твой предмет ".$ItemDel->fact['name']." был разрушен";
					myquery("DELETE FROM game_items_factsheet WHERE id=".$ItemDel->fact['id']."");
					myquery("INSERT INTO game_pm (komu,theme,post,time) VALUES (".$old_it['user_id'].",'".$theme."','".$pismo."',UNIX_TIMESTAMP())");
					myquery("DELETE FROM quest_constructor WHERE id=".$old_it['id']."");
				}
				
				myquery("INSERT INTO quest_constructor SET user_id=".$user_id.",item_id=".$ar[1].",create_time=UNIX_TIMESTAMP()");
			
				//QuoteTable('open'); 
				echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
				$Item->info($ar[1]);
				//QuoteTable('close'); 
				myquery("DELETE FROM game_items WHERE id IN (".$str_id.")");
				myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=".$this->char['user_id']."");                
			}
			else
			{
				myquery("DELETE FROM game_items_factsheet WHERE id=".$item_fact_id."");
			}
		} 
	}*/
	else
	{
		QuoteTable('open');
		echo '<center><br /><b>Создание Рунного Предмета из частей:</b><br /><br />Выбери части для сборки Рунного Предмета (7 рун!):</center><br />';
		$result_items = myquery("SELECT game_items.id, game_items.count_item, game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
		if ($result_items!=false AND mysql_num_rows($result_items)>0)
		{
			echo '<form name="constr" method="post" action="act.php?func=inv&make_amulet">
			<table cellpadding="0" cellspacing="2" border="0" width="360">';
			while ($items = mysql_fetch_array($result_items))
			{
				$Item = new Item($items['id']);
				echo '<tr>
				<td>';
				$Item->hint(0,1,'<span '); 
				ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
				echo '</span>
				</td>
				<td>
				'.$items['name'].'
				</td>
				<td>
				<input type="textbox" name='.$items['id'].' value="0" size="1" maxlength="1" border="5" > 
				</td>
				<td>
				(Есть: '.$items['count_item'].' '.pluralForm($items['count_item'],'руна','руны','рун').')
				</td>
				</tr>';
			}
			echo '</table>
			<center><input type="submit" name="save" value="Собрать амулет из отмеченных частей">
			</form>';
		}
		QuoteTable('close');
	}
}
elseif (isset($_GET['make_svitok']))
{
     $minus_weight = 5*mysqlresult(myquery("SELECT weight FROM game_items_factsheet WHERE id=".item_id_part_svitok_hranitel.""),0,0);
     list($result_items) = mysql_fetch_array(myquery("SELECT game_items.count_item FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_hranitel.""));
	 if ($result_items<5)
	 {
		echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки Свитка Хранителя необходимо 5 частей</b><br /><br />';
		echo ''.$result_items.'aa';
	 }
	 else
	 {
		$Item = new Item();
        $ar = $Item->add_user(item_id_svitok_hranitel,$user_id,0);
        if ($ar[0]>0)
        {
             echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').': Свиток Хранителя</i><br /><br />';
			$Item->info($ar[1]);
			if ($result_items==5) myquery("DELETE FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_hranitel."");
			else myquery("Update game_items Set count_item=count_item-5 WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_hranitel.""); 
            myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=$user_id");                
        }
	 }
	 /*   if (isset($_POST['save']))
    {
        $col = 0;
        $str_id = implode("','",$_POST['items']);
        $str_id = "'".$str_id."'";
        $minus_weight = 5*mysqlresult(myquery("SELECT weight FROM game_items_factsheet WHERE id=".item_id_part_svitok_hranitel.""),0,0);
        $result_items = myquery("SELECT game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.id=".item_id_part_svitok_hranitel." AND game_items.item_id=game_items_factsheet.id AND game_items.id IN (".$str_id.") ORDER BY RAND()");
        if (mysql_num_rows($result_items)!=5)
        {
            echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки Свитка Хранителя необходимо выбрать ровно 5 частей</b><br /><br />';
        }
        else
        {
            $Item = new Item();
            $ar = $Item->add_user(item_id_svitok_hranitel,$user_id,0);
            
            if ($ar[0]>0)
            {
                //QuoteTable('open'); 
                echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
                $Item->info($ar[1]);
                //QuoteTable('close'); 
                myquery("DELETE FROM game_items WHERE id IN (".$str_id.")");
                myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=$user_id");                
            }
        }
    }
    else
    {
        QuoteTable('open');
        echo '<center><br /><b>Создание Свитка Хранителя из частей:</b><br /></center><br />Отметь нужные части для сборки Свитка Хранителя (не менее 5 частей!):<br />';
        $result_items = myquery("SELECT game_items.id FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.id=".item_id_part_svitok_hranitel." AND game_items.item_id=game_items_factsheet.id ORDER BY game_items_factsheet.name");
        if ($result_items!=false AND mysql_num_rows($result_items)>0)
        {
            echo '<form name="constr" method="post" action="act.php?func=inv&make_svitok">
            <table cellpadding="0" cellspacing="4" border="0">';
            while ($items = mysql_fetch_array($result_items))
            {
                $Item = new Item($items['id']);
                echo '<tr>
                <td>
                <input type="checkbox" value="'.$items['id'].'" name="items[]">
                </td>
                <td>';
                $Item->hint(0,1,'<span '); 
                ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
                echo '</span>';
                echo '</td>
                <td>&nbsp;|&nbsp;';
                if ($Item->personal==0)
                {                                                                                           
                    echo '<a href="item.php?inv_option=drop&id='.$items['id'].'">Выкинуть</a></td><td>&nbsp;|&nbsp;';
                }
                $ss = 'act.php?identify_id='.$items['id'].'&func=inv';
                $Item->hint(0,1,'<a href="'.$ss.'" ');
                echo 'Посмотреть</a>';
                echo'</td></tr>';
            }
            echo '</table>
            <center><input type="submit" name="save" value="Собрать Свиток Хранителя из отмеченных частей">
            </form>';
        }
        QuoteTable('close');
    }*/
}
elseif (isset($_GET['make_svitok_ice']))
{
	 $minus_weight = 10*mysqlresult(myquery("SELECT weight FROM game_items_factsheet WHERE id=".item_id_part_svitok_ice_portal.""),0,0);
     $result_items = myquery("SELECT Sum(game_items.count_item) as Count FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_ice_portal."");
	 $result_items = mysql_fetch_array($result_items);
	 if ($result_items['count']<10)
	 {
		echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки Свитка Снежного Портала необходимо 10 частей</b><br /><br />';
	 }
	 else
	 {
		$Item = new Item();
        $ar = $Item->add_user(item_id_svitok_ice_portal,$user_id,0);
        if ($ar[0]>0)
        {
             echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
			$Item->info($ar[1]);
			if ($result_items['count']=5) myquery("DELETE FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_ice_portal.")");
			else myquery("DELETE game_items Set count_item=count_item-5 WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_ice_portal.")"); 
            myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=$user_id");                
        }
	 }
    /*
	if (isset($_POST['save']))
    {
	
        $col = 0;
        $str_id = implode("','",$_POST['items']);
        $str_id = "'".$str_id."'";
        $minus_weight=0;
        $result_items = myquery("SELECT game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.id=".item_id_part_svitok_ice_portal." AND game_items.item_id=game_items_factsheet.id AND game_items.id IN (".$str_id.") ORDER BY RAND()");
        if (mysql_num_rows($result_items)!=10)
        {
            echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки Свитка Снежного Портала необходимо выбрать ровно 10 частей</b><br /><br />';
        }
        else
        {
            $Item = new Item();
            $ar = $Item->add_user(item_id_svitok_ice_portal,$user_id,0);
            
            if ($ar[0]>0)
            {
                //QuoteTable('open'); 
                echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
                $Item->info($ar[1]);
                //QuoteTable('close'); 
                myquery("DELETE FROM game_items WHERE id IN (".$str_id.")");
                myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=".$this->char['user_id']."");                
            }
        }
	
    }
    else
    {
        QuoteTable('open');
        echo '<center><br /><b>Создание Свитка Снежного Портала из частей:</b><br /></center><br />Отметь нужные части для сборки Свитка Снежного Портала (не менее 10 частей!):<br />';
        $result_items = myquery("SELECT game_items.id FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.id=".item_id_part_svitok_ice_portal." AND game_items.item_id=game_items_factsheet.id ORDER BY game_items_factsheet.name");
        if ($result_items!=false AND mysql_num_rows($result_items)>0)
        {
            echo '<form name="constr" method="post" action="act.php?func=inv&make_svitok_ice">
            <table cellpadding="0" cellspacing="4" border="0">';
            while ($items = mysql_fetch_array($result_items))
            {
                $Item = new Item($items['id']);
                echo '<tr>
                <td>
                <input type="checkbox" value="'.$items['id'].'" name="items[]">
                </td>
                <td>';
                $Item->hint(0,1,'<span '); 
                ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
                echo '</span>';
                echo '</td>
                <td>&nbsp;|&nbsp;';
                if ($Item->personal==0)
                {                                                                                           
                    echo '<a href="item.php?inv_option=drop&id='.$items['id'].'">Выкинуть</a></td><td>&nbsp;|&nbsp;';
                }
                $ss = 'act.php?identify_id='.$items['id'].'&func=inv';
                $Item->hint(0,1,'<a href="'.$ss.'" ');
                echo 'Посмотреть</a>';
                echo'</td></tr>';
            }
            echo '</table>
            <center><input type="submit" name="save" value="Собрать Свиток Снежного Портала из отмеченных частей">
            </form>';
        }
        QuoteTable('close');
    }*/
}



if (isset($_SESSION['error_inv']) and $_SESSION['error_inv']=='error_ident')
{
	QuoteTable('open');
	echo '<span class="ERROR">Не идентифицировано</span>';
	QuoteTable('close');
	$_SESSION['error_inv'] = '';
};
if (isset($_SESSION['error_inv']) and $_SESSION['error_inv']=='error_broken')
{
	QuoteTable('open');
	echo '<span class="ERROR">Предмет сломан (или в нем закончились заряды)</span>';
	QuoteTable('close');
	$_SESSION['error_inv'] = '';
};
if (isset($_SESSION['error_inv']) and $_SESSION['error_inv']=='error_stat')
{
	QuoteTable('open');
	$printerror='Нужно: '.$_SESSION['error_stat'];
	echo'<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="center">'.$printerror.'</td></tr></table>';
	QuoteTable('close');
	$_SESSION['error_inv'] = '';
}
if (isset($identify_id))
{
	$Item = new Item();
	$Item->info($identify_id,1);
}
echo '</td></tr></table>';

if (!$from_house AND !$from_craft)
{
	echo '</td><td width="172" valign="top">';
	include('inc/template_stats.inc.php');
	echo '</td></tr></table>';
	set_delay_reason_id($user_id,23);
}
elseif (!$from_craft)
{
	echo '</td>';
	echo '<td valign="top">';
	QuoteTable('open','250px');
	echo'
			<font color="#FFFF00"><b>Характеристики:</b></font><br><br>
			<table cellpadding="2" cellspacing="0" border="0" width=100%>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/sil.gif" alt="Сила"> Сила: </td><td align=right>' . $char['STR'] . ''; if ($char['STR']>$char['STR_MAX']) echo '(+'.($char['STR']-$char['STR_MAX']).')'; echo '</td>
				</tr>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/int.gif" alt="Интеллект"> Интеллект: </td><td align=right>' . $char['NTL'] . ''; if ($char['NTL']>$char['NTL_MAX']) echo '(+'.($char['NTL']-$char['NTL_MAX']).')'; echo '</td>
				</tr>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/lov.gif" alt="Ловкость"> Ловкость: </td><td align=right>' . $char['PIE'] . ''; if ($char['PIE']>$char['PIE_MAX']) echo '(+'.($char['PIE']-$char['PIE_MAX']).')'; echo '</td>
				</tr>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/vit.gif" alt="Защита"> Защита: </td><td align=right>' . $char['VIT'] . ''; if ($char['VIT']>$char['VIT_MAX']) echo '(+'.($char['VIT']-$char['VIT_MAX']).')'; echo '</td>
				</tr>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/dex.gif" alt="Выносливость"> Выносливость: </td><td align=right>' . $char['DEX'] . ''; if ($char['DEX']>$char['DEX_MAX']) echo '(+'.($char['DEX']-$char['DEX_MAX']).')'; echo '</td>
				</tr>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/mud.gif" alt="Мудрость"> Мудрость: </td><td align=right>' . $char['SPD'] . ''; if ($char['SPD']>$char['SPD_MAX']) echo '(+'.($char['SPD']-$char['SPD_MAX']).')'; echo '</td>
				</tr>
				<tr>
					<td><img src="http://'.IMG_DOMAIN.'/har/ud.gif" alt="Удача"> Удача: </td><td align=right>' . $char['lucky'] . ''; if ($char['lucky']>$char['lucky_max']) echo '(+'.($char['lucky']-$char['lucky_max']).')'; echo '</td>
				</tr>
	</table>';

	QuoteTable('close');    
	QuoteTable('open','250px');
	echo'<font face=Verdana size=2 color="white"><b><center>'.$char['name'].'  <span title="Твой уровень">['.$char['clevel'].']</span></center></b></font>';

	echo '<table cellpadding="1" cellspacing="0" width="100%" border="0">';
	if ($char['HP_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['HP'] / $char['HP_MAX'] * 100, 0);
	}

	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '
	<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Здоровье</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['HP'] . ' / ' . $char['HP_MAX'] . '</font>';
	if ($char['HP_MAX']<$char['HP_MAXX'])
	{
		echo '<span title="Ты '.echo_sex('получил','получила').' травму!" style="font-weight:800;font-size:10px;color:red;">(-'.($char['HP_MAXX']-$char['HP_MAX']).')</span>';
	}
	echo'<br>
	<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	if ($char['MP_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['MP'] / $char['MP_MAX'] * 100, 0);
	}
	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Мана</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['MP'] . ' / ' . $char['MP_MAX'] . '</font><br>
	<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	if ($char['STM_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['STM'] / $char['STM_MAX'] * 100, 0);
	}
	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_yellow.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_yellow.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Энергия</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['STM'] . ' / ' . $char['STM_MAX'] . '</font><br>
	<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	echo '</table>';
	QuoteTable('close');    
	echo '</td>';
	echo '</tr>';
	echo '</table>';
}
else
{
	echo '</td><td>';
	echo '</td></tr></table>';
}

?>
<script type="text/javascript" language="JavaScript">
p = new Array();
function ge(a)
{
	if( document.all )
		return document.all[a];
	else
		return document.getElementById( a );
}
function load(pp,str)
{
	if( p[pp] )
		return;
	p[pp] = 1;
	<?
	if (!$from_house)
		echo 'parent.game.xssa.location.href = str;';
	else
		echo 'this.xssa.location.href = str;';
	?>
 }
function expand(a,b,pp,str)
{
	if( ge( b ).style )
		dsp = ge( b ).style.display;
	else
		dsp = ge( b ).display;
	if( dsp == 'none' )
	{
		if( ge( b ).style )
			dsp = ge( b ).style.display = 'block';
		else
			dsp = ge( b ).display = '';
	}
	else
	{
		if( ge( b ).style )
			ge( b ).style.display = 'none';
		else
			ge( b ).display = 'none';
	}
	load(pp,str)
}
</script>
<?
echo'<iframe style="width:0px;height:0px;border:1px;" name="xssa" id="frame_xssa" src=""></iframe>';
if (function_exists("save_debug")) save_debug(); 
?>
<?
if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (!defined('money_weight'))
	{
		define('money_weight',0);
	}
	if (isset($town_id) AND $town_id!=$town)
	{                                                                                                                                         
		echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в город" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");

	$info=myquery("select * from game_tavern where town='$town' limit 1");
	$info=mysql_fetch_array($info);

	$vladelec = '';
	if ($info['vladel']>0)
	{
		$selname = myquery("(SELECT name FROM game_users WHERE user_id='".$info['vladel']."') UNION (SELECT name FROM game_users_archive WHERE user_id='".$info['vladel']."')");
		if ($selname!=false AND mysql_num_rows($selname)>0)
		{
			list($vladelec) = mysql_fetch_array($selname);
		}
	}

$hp_cost = 5;
$mp_cost = 2;
$stm_cost = 3;

echo'
<center><font size=2 face=verdana color=000000><b>';
//проверим на квест (движок)
$quest_user=myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." ");
if(mysql_num_rows($quest_user) OR isset($quest_answer))
{
	$quest_user=mysql_fetch_array($quest_user);
	include("../quest/quest_engine_types/quest_engine_tavern.php");
}
else 
{
	if (isset($addstore))
	{
		if ($info['vladel']==$user_id or $char['clan_id']==1)
		{
			echo '<center><font color=#FF0000><b>Пополнение запасов на складе таверны</b></font></center>';
			echo '<br><br>
			';
			if (!isset($save))
			{
				echo'<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1">
				<form action="" method="post">
				<tr bgcolor=001122>
				<td valign="middle" align="left">Пополнить запасы HP на </td><td><input type="text" name="hp_store" size=10></td><td> единиц<br>(стоимость за 1000 единиц = '.$hp_cost.' монет)</td></tr><tr bgcolor=001122>
				<td valign="middle" align="left">Пополнить запасы MP на </td><td><input type="text" name="mp_store" size=10></td><td> единиц<br>(стоимость за 1000 единиц = '.$mp_cost.' монет)</td></tr><tr bgcolor=001122>
				<td valign="middle" align="left">Пополнить запасы STM на </td><td><input type="text" name="stm_store" size=10></td><td> единиц<br>(стоимость за 1000 единиц = '.$stm_cost.' монет)</td></tr><tr><tr></tr>
				<td colspan="3" valign="top" align="left"><input type="hidden" name="save"><input name="store" type="submit" value="Пополнить" ><input name="town_id" type="hidden" value="'.$town.'">     <input type="button" value="Выйти" OnClick=location.href="town.php?option='.$option.'"></td></tr></form></table>';
			}
			else
			{
				if (!isset($hp_store)) $hp_store=0;
				$gp = max(0,round($hp_store/1000*$hp_cost,2));
				if ($char['GP']>=$gp)
				{
					myquery("UPDATE game_users SET GP=GP-'$gp',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,-$gp,54);
					myquery("UPDATE game_tavern SET hp_store=hp_store+'$hp_store' WHERE town='$town'");
					echo '<b><font size=2 color=#FFFF00>На складах запасы HP увеличены на '.$hp_store.' единиц<br>Ты '.echo_sex('заплатил','заплатила').' '.$gp.' монет</font></b><br><br>';
					$char['GP']-=$gp;
				}
				else
				{
					echo '<b><font size=2 color=#00FF00>У тебя недостаточно денег для пополнения запасов HP</font></b><br><br>';
				}
				if (!isset($mp_store)) $mp_store=0;
				$gp = max(0,round($mp_store/1000*$mp_cost,2));
				if ($char['GP']>=$gp)
				{
					myquery("UPDATE game_users SET GP=GP-'$gp',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,-$gp,54);
					myquery("UPDATE game_tavern SET mp_store=mp_store+'$mp_store' WHERE town='$town'");
					echo '<b><font size=2 color=#FFFF00>На складах запасы MP увеличены на '.$mp_store.' единиц<br>Ты '.echo_sex('заплатил','заплатила').' '.$gp.' монет</font></b><br><br>';
					$char['GP']-=$gp;
				}
				else
				{
					echo '<b><font size=2 color=#00FF00>У тебя недостаточно денег для пополнения запасов MP</font></b><br><br>';
				}
				if (!isset($stm_store)) $stm_store=0;
				$gp = max(0,round($stm_store/1000*$stm_cost,2));
				if ($char['GP']>=$gp)
				{
					myquery("UPDATE game_users SET GP=GP-'$gp',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,-$gp,54);
					myquery("UPDATE game_tavern SET stm_store=stm_store+'$stm_store' WHERE town='$town'");
					echo '<b><font size=2 color=#FFFF00>На складах запасы STM увеличены на '.$stm_store.' единиц<br>Ты '.echo_sex('заплатил','заплатила').' '.$gp.' монет</font></b><br><br>';
					$char['GP']-=$gp;
				}
				else
				{
					echo '<b><font size=2 color=#00FF00>У тебя недостаточно денег для пополнения запасов STM</font></b><br><br>';
				}
			}
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
	}
}

if (($info['vladel']==$user_id or $char['clan_id']==1) and isset($item) and isset($gp) and (isset($addeda) OR isset($edieda)))
{
	$item=htmlspecialchars(mysql_real_escape_string($item));
	$hp=(int)$hp;$mp=(int)$mp;$st=(int)$st;
	list($hp_store,$mp_store,$stm_store) = mysql_fetch_array(myquery("SELECT hp_store,mp_store,stm_store FROM game_tavern WHERE town='$town'"));
	if ($hp_store<$hp) $error = 'Не хватает запасов HP на складе';
	elseif ($mp_store<$mp) $error = 'Не хватает запасов MP на складе';
	elseif ($stm_store<$st) $error = 'Не хватает запасов STM на складе';
	elseif ($hp<=-100) $error = 'Нельзя указывать такие минусы!';
	elseif ($mp<=-100) $error = 'Нельзя указывать такие минусы!';
	elseif ($st<=-100) $error = 'Нельзя указывать такие минусы!';
	elseif ($hp>=1000) $error = 'Нельзя указывать такие плюсы!';
	elseif ($mp>=1000) $error = 'Нельзя указывать такие плюсы!';
	elseif ($st>=1000) $error = 'Нельзя указывать такие плюсы!';
	elseif ($gp>=1000) $error = 'Большая цена!';
	else
	{
		if (isset($addeda))
		{
			$insert=myquery("insert into game_tavern_shop (town,item,hp,mp,stm,gp) values ('$town','$item','$hp','$mp','$st','$gp')");
		}
		elseif (isset($edieda) AND isset($edi))
		{
			$update=myquery("update game_tavern_shop SET item='".$item."',hp='$hp',mp='$mp',stm='$st',gp='$gp' WHERE id=$edi");
		}
	}
}

if (isset($del) and ($info['vladel']==$user_id or $char['clan_id']==1))
{
	$delete=myquery("delete from game_tavern_shop where id='".(int)$del."'");
}

if (isset($del_spl) and ($info['vladel']==$user_id or $char['clan_id']==1))
{
	$delete=myquery("delete from game_tavern_spletni where id='".(int)$del_spl."'");
}

if (isset($nymnym))
{
	//$nymnym=(int)$nymnym;
	$select=myquery("select * from game_tavern_shop where id='$nymnym' AND town=$town LIMIT 1");
	if (mysql_num_rows($select))
	   {
		$row=mysql_fetch_array($select);
		//echo '<hr><font color=#FFFFFF>'; var_dump($row); echo'</font><hr>';
		If ($row['gp']<0) $row['gp']=0;
		$gp = $row['gp'];
		if (mysql_num_rows($userban))
		{
			$gp=$gp*5;
			if ($gp==0) $gp=3;
		}
		if ($char['HP']+$row['hp']>=$char['HP_MAX']) $hp=$char['HP_MAX'];
		else $hp=$char['HP']+$row['hp'];
		if ($char['MP']+$row['mp']>=$char['MP_MAX']) $mp=$char['MP_MAX'];
		else $mp=$char['MP']+$row['mp'];
		if ($char['STM']+$row['stm']>=$char['STM_MAX']) $stm=$char['STM_MAX'];
		else $stm=$char['STM']+$row['stm'];
		if ($char['GP']-$gp>=0)
		{
			$upd=myquery("update game_users set HP='$hp',MP='$mp',STM='$stm',GP=GP-'".$gp."',CW=CW-'".($gp*money_weight)."' where user_id='$user_id'");
			setGP($user_id,-$gp,55);
			$char['HP']=$hp;
			$char['MP']=$mp;
			$char['STM']=$stm;
			$char['GP']-=$gp;
			If ($row['hp']<0) $row['hp']=0;
			If ($row['mp']<0) $row['mp']=0;
			If ($row['stm']<0) $row['stm']=0;
			//$gp = round($gp*0.9);
			$vurychka = $gp;
			$sebestoimost = ($row['hp']/1000*$hp_cost) + ($row['mp']/1000*$mp_cost) + ($row['stm']/1000*$stm_cost);
			$dohod = $vurychka-$sebestoimost;
			$np_pribyl = 0;
			if ($dohod>0)
			{
				$np_pribyl = round($dohod*0.24,6);
			}
			$dohod_vladelec = round($vurychka-$np_pribyl,2);
			$dohod_taverna = round($dohod-$np_pribyl,2);
			if($user_id==$info['vladel']) {
				$upd=myquery("update game_tavern set hp_store=hp_store-'".$row['hp']."',mp_store=mp_store-'".$row['mp']."',stm_store=stm_store-'".$row['stm']."' where town='$town'");
				$upd=myquery("update game_users set GP=GP+'$dohod_vladelec',CW=CW+'".($dohod_vladelec*money_weight)."' where user_id='".$info['vladel']."'");
				$upd=myquery("update game_users_archive set GP=GP+'$dohod_vladelec',CW=CW+'".($dohod_vladelec*money_weight)."' where user_id='".$info['vladel']."'");
				setGP($info['vladel'],$dohod_vladelec,56);
				$time = time();
				if ($dohod_taverna<0)
				{
					$upd=myquery("update game_tavern set dohod=dohod+'".$dohod_taverna."' where town='$town'");
				}
			}
			else
			{
				$upd=myquery("update game_tavern set dohod=dohod+'".$dohod."',hp_store=hp_store-'".$row['hp']."',mp_store=mp_store-'".$row['mp']."',stm_store=stm_store-'".$row['stm']."' where town='$town'");
				$upd=myquery("update game_users set GP=GP+'".($gp-$np_pribyl)."',CW=CW+'".(($gp-$np_pribyl)*money_weight)."' where user_id='".$info['vladel']."'");
				$upd=myquery("update game_users_archive set GP=GP+'".($gp-$np_pribyl)."',CW=CW+'".(($gp-$np_pribyl)*money_weight)."' where user_id='".$info['vladel']."'");
				setGP($info['vladel'],($gp-$np_pribyl),56);
				$time = time();
				//$stat = myquery("INSERT DELAYED INTO game_stat (user_id, town, stat_id, gp, item_id, enemy_id,time) VALUES ('$user_id','$town','14','".$row['gp']."', '$nymnym', '".$info['vladel']."','$time')");
				save_stat($user_id,'',$town,14,'',$nymnym,$info['vladel'],$row['gp'],'','','','');
			}
			$upd=myquery("update game_tavern_shop set kol=kol+1 where id='$nymnym'");
			$error='Ты вкусно '.echo_sex('пообедал','пообедала').'';
		}
		else
			$error='Не хватает денег';
	}
}

if (isset($tavernnews) and isset($addnews))
{
	if ($char['clan_id']==1 or $info['vladel']==$user_id)
	{
		$news=htmlspecialchars(mysql_escape_string($tavernnews));
		$upd=myquery("update game_tavern set info='$news' where town='$town' and vladel='".$info['vladel']."'");
	}
}
echo'<span style="color:white;">ВАШИ: Здоровье = '.$char['HP'].'/'.$char['HP_MAX'].' :: Мана = '.$char['MP'].'/'.$char['MP_MAX'].' :: Энергия = '.$char['STM'].'/'.$char['STM_MAX'].'</span>';

if (isset($error)) echo '<center><font color=red>'.$error.'</font></center><br>';

echo'<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1"><tr><td><table border="0" width="100%" bgcolor="223344" cellpadding="0" cellspacing="1">
<tr bgcolor=001122><td colspan="2" valign="top" align=center>';

echo $info['info'];

echo'<br>Владелец: <font color=ff0000><b>'.$vladelec.'<br>';
$select=myquery("select * from game_tavern where town='$town'");
$doh=mysql_fetch_array($select);
echo'Общий доход составляет: '.$doh['dohod'].' золотых';
echo'</td></tr>';

if ($info['vladel']==$user_id or $char['clan_id']==1)
{
	if (!isset($edi))
	{
		echo '<tr bgcolor=111111><form action="" method="post"><td><font color=#80FFFF>Добавление новой еды:</font><br><nobr>Название <input type="text" name="item" size=50 maxsize="50"><br><input type="text" name="hp" size="5" maxsize="5"> - HP <input type="text" name="mp" size="5" maxsize="5"> - MP <input type="text" name="st" size="5" maxsize="5"> - STM <input type="text" name="gp" size="5" maxsize="5"> - Gold</td><td valign=bottom><input type="submit" name="addeda" value="Добавить"></td></tr>';
	}
	else
	{
		$row = mysql_fetch_array(myquery("SELECT * FROM game_tavern_shop WHERE id=$edi"));
		echo '<tr bgcolor=111111><form action="" method="post"><td><font color=#80FFFF>Редактирование еды:</font><br><nobr>Название <input type="text" name="item" size=50 maxsize="50" value="'.$row['item'].'"><br><input type="text" name="hp" size="5" maxsize="5" value='.$row['hp'].'> - HP <input type="text" name="mp" size="5" maxsize="5" value='.$row['mp'].'> - MP <input type="text" name="st" size="5" maxsize="5" value='.$row['stm'].'> - STM <input type="text" name="gp" size="5" maxsize="5" value='.$row['gp'].'> - Gold</td><td valign=bottom><input type="hidden" name="edi" value='.$row['id'].'><input type="submit" name="edieda" value="Редактировать"></td></tr>';
	}

	echo '<tr bgcolor=222222><form action="" method="post"><td>Редактирование главной новости:<br><textarea name="tavernnews" cols="45" class="input" rows="5">'.$doh['info'].'</textarea><br><input type="submit" value="Изменить новость" name="addnews"><br><br></td></form><td><font color=#80FF80><b>Запасы на складе:</b><br>
		HP = '.$doh['hp_store'].'<br>MP = '.$doh['mp_store'].'<br>STM = '.$doh['stm_store'].'<br><input type="button" onClick=location.replace("?town_id='.$town.'&option='.$option.'&addstore") value="Пополнить"></td></form></tr>';
}



echo'<tr><form action="" method="post"><td colspan="2" align="left">';

$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
list($town)=mysql_fetch_array($select);

$select=myquery("select * from game_tavern_shop where town='$town' order by gp DESC");
while ($row=mysql_fetch_array($select))
{
	if ($row['hp']>=0) $hp='Жизнь:+'.$row['hp'].'|';
	else $hp='Жизнь:'.$row['hp'].'|';
	if ($row['hp']>=0) $mp='Мана:+'.$row['mp'].'|';
	else $mp='Мана:'.$row['mp'].'|';
	if ($row['hp']>=0) $stm='Энергия:+'.$row['stm'].'|';
	else $stm='Энергия:'.$row['stm'].'|';
	if (mysql_num_rows($userban))
	{
		$row['gp']=$row['gp']*5;
		if ($row['gp']==0) $row['gp']=3;
	}
	if (($row['hp']<=$doh['hp_store'] and $row['mp']<=$doh['mp_store'] and $row['stm']<=$doh['stm_store'] and $row['gp']<=$char['GP']) OR ($info['vladel']==$user_id))
		echo '<br><span align="left"><input type="radio" name="nymnym" value="'.$row['id'].'"><b><font color=#FF0000>'.stripslashes($row['item']).'</font></b></span><span align="right">('.$hp.' '.$mp.' '.$stm.' <font color=white>Цена:'.$row['gp'].'</font>)</span>'.(($info['vladel']==$user_id or $char['clan_id']==1)?'<br>(съедено  <b><font color=white>'.$row['kol'].'</font></b>  раз)  <input type="button" value="Редактировать" onClick=location.replace("?option='.$option.'&edi='.$row['id'].'")>       <input type="button" value="Удалить" onClick=location.replace("?option='.$option.'&del='.$row['id'].'")>':'').'';
}

echo '<br><br><input name="town_id" type="hidden" value="'.$town.'"><input name="obed" type="submit" value="Съесть" style="COLOR: #СССССС; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000"></td></form></tr>';
echo'</tr></table></table>';
echo'<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1"><tr><td></td></tr></table>
<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1"><tr><td><table border="0" width="100%" bgcolor="223344" cellpadding="0" cellspacing="1">
<tr><td valign=top>&nbsp;<table border=0 width=100% cellpadding="0" cellspacing="2">';

$splet=myquery("select * from game_tavern_spletni where town='$town' order by id DESC LIMIT 8");
while($row=mysql_fetch_array($splet))
{
	echo'<tr><td align="center" rowspan="2">'.stripslashes($row["spletni"]).'</td></tr>
	<tr><td align="right">'.(($info['vladel']==$user_id or $char['clan_id']==1)?' <input type="button" onClick=location.replace("?town_id='.$town.'&option='.$option.'&del_spl='.$row['id'].'") value="Удалить">':'').'</td></tr>
	<tr><td align=center><b><font color=ff0000>'.$row["name"].'</font></b></td></tr>';
};
echo'</table></td><td align=right valign=top>';

if (!isset($add_spletni))
{
	echo'<form action="" method="post">
	<textarea name="spletni" cols="20" class="input" rows="15"></textarea><br>
	<input name="town_id" type="hidden" value="'.$town.'"><input name="add_spletni" type="submit" value="Оставить сплетню" style="COLOR: #СССССС; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000">&nbsp;&nbsp;&nbsp;</td></tr></table></table>';
}
elseif ($spletni!='')
{
	$spletni=htmlspecialchars($spletni);
	$spletni=replace_enter($spletni);
	$result=myquery("insert into game_tavern_spletni (town, spletni, name) values ('$town', '$spletni', '".$char['name']."')");
	echo '<center><b><br>Добавлено</b>
	<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">
	</td></tr></table></table>';
};

$sel = myquery("SELECT * FROM game_tavern WHERE town!=$town AND vladel=$user_id");
if(mysql_num_rows($sel))
{
	QuoteTable('open');
	echo'<center><font size=1 color=#FF6600>Ты владеешь другой таверной. Ты можешь попросить твоего коллегу - владельца этой таверны <font color=#FF0000><b>'.$vladelec.'</b></font> - попросить отправить гонца в твою таверну(ы) для пополнения запасов. За эти услуги ты '.echo_sex('должен','должна').' будешь заплатить 80 монет.<br><br>';
	if (!isset($_POST['store']))
	{
		while($tav = mysql_fetch_array($sel))
		{
			echo '<br><font color=#FF00FF size=2>Таверна в городе: '.mysql_result(myquery("SELECT rustown FROM game_gorod WHERE town='".$tav['town']."'"),0,0).'<font><br><font size=1 color=#80FF80><b>Запасы на складе:</b>
			HP = '.$tav['hp_store'].', MP = '.$tav['mp_store'].', STM = '.$tav['stm_store'].'';
			echo'
			<form autocomplete="off" method="POST" name="tavern'.$tav['town'].'">
			<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1">
			<tr bgcolor=001122>
			<td valign="middle" align="left">Пополнить запасы HP на </td><td><input type="text" name="hp_store" size=10></td><td> единиц (стоимость за 1000 единиц = '.$hp_cost.' монет)</td></tr><tr bgcolor=001122>
			<td valign="middle" align="left">Пополнить запасы MP на </td><td><input type="text" name="mp_store" size=10></td><td> единиц (стоимость за 1000 единиц = '.$mp_cost.' монет)</td></tr><tr bgcolor=001122>
			<td valign="middle" align="left">Пополнить запасы STM на </td><td><input type="text" name="stm_store" size=10></td><td> единиц (стоимость за 1000 единиц = '.$stm_cost.' монет)</td></tr><tr><tr></tr>
			<td colspan="3" valign="top" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="hidden" name="save_tavern" value="1">
			<input type="hidden" name="town_tavern" value="'.$tav['town'].'">
			<input type="hidden" name="town_id" value="'.$town.'">
			</td></tr></table>
			<input name="store" type="submit" value="Пополнить запасы моей таверны">
			</form><br>';
		}
	}
	else
	{
		if($char['GP']>=80)
		{
			myquery("UPDATE game_users SET GP=GP-'80',CW=CW-'".(80*money_weight)."' WHERE user_id='$user_id'");
			setGP($user_id,-80,57);
			$char['GP']-=80;
			if (!isset($hp_store)) $hp_store=0;
			$gp = max(0,round($hp_store/1000*$hp_cost,2));
			if ($char['GP']>=$gp)
			{
				myquery("UPDATE game_users SET GP=GP-'$gp',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
				setGP($user_id,-$gp,54);
				myquery("UPDATE game_tavern SET hp_store=hp_store+'$hp_store' WHERE town='$town_tavern'");
				echo '<b><font size=2 color=#FFFF00>На складах запасы HP увеличены на '.$hp_store.' единиц<br>Ты '.echo_sex('заплатил','заплатила').' '.$gp.' монет</font></b><br><br>';
				$char['GP']-=$gp;
			}
			else
			{
				echo '<b><font size=2 color=#00FF00>У тебя недостаточно денег для пополнения запасов HP</font></b><br><br>';
			}
			if (!isset($mp_store)) $mp_store=0;
			$gp = max(0,round($mp_store/1000*$mp_cost,2));
			if ($char['GP']>=$gp)
			{
				myquery("UPDATE game_users SET GP=GP-'$gp',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
				setGP($user_id,-$gp,54);
				myquery("UPDATE game_tavern SET mp_store=mp_store+'$mp_store' WHERE town='$town_tavern'");
				echo '<b><font size=2 color=#FFFF00>На складах запасы MP увеличены на '.$mp_store.' единиц<br>Ты '.echo_sex('заплатил','заплатила').' '.$gp.' монет</font></b><br><br>';
				$char['GP']-=$gp;
			}
			else
			{
				echo '<b><font size=2 color=#00FF00>У тебя недостаточно денег для пополнения запасов MP</font></b><br><br>';
			}
			if (!isset($stm_store)) $stm_store=0;
			$gp = max(0,round($stm_store/1000*$stm_cost,2));
			if ($char['GP']>=$gp)
			{
				myquery("UPDATE game_users SET GP=GP-'$gp',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
				setGP($user_id,-$gp,54);
				myquery("UPDATE game_tavern SET stm_store=stm_store+'$stm_store' WHERE town='$town_tavern'");
				echo '<b><font size=2 color=#FFFF00>На складах запасы STM увеличены на '.$stm_store.' единиц<br>Ты '.echo_sex('заплатил','заплатила').' '.$gp.' монет</font></b><br><br>';
				$char['GP']-=$gp;
			}
			else
			{
				echo '<b><font size=2 color=#00FF00>У тебя недостаточно денег для пополнения запасов STM</font></b><br><br>';
			}
		}
		else
		{
			echo 'У тебя недостаточно средств';
		}
	}
	QuoteTable('close');
}
echo'<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1"><tr><td></td></tr></table><table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1"><tr><td><table border="0" width="100%" bgcolor="223344" cellpadding="0" cellspacing="1">
<tr bgcolor=001122><td>
Обед - частично или полностью пополняет запас сил в зависимости от того, что ты съешь.<br>
Сплетни - городские сплетни, которые оставляют местные или приезжие...
</td></tr></table>';
}


if (function_exists("save_debug")) save_debug(); 

?>
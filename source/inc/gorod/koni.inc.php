<?
if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
	echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться конюшней!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}
$img='http://'.IMG_DOMAIN.'/race_table/orc/table';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top">';
echo'Конюшня:<br>';

$img='http://'.IMG_DOMAIN.'/race_table/orc/table';
if (isset($buy))
{
	$sel=myquery("select * from game_vsadnik where town='$town' and id='$buy'");
	$row=mysql_fetch_array($sel);
	
	$est_horses = 0;
	$max_horse = 1;
	$est_horses3 = 0;
	$est_horses4 = 0;
	$count_horses = mysql_result(myquery("SELECT COUNT(*) FROM game_users_horses WHERE user_id=$user_id"),0,0);
	if ($row['vsad']>=13)
	{
		$est_horses = mysql_result(myquery("SELECT COUNT(*) FROM houses_users WHERE build_id=8 AND buildtime<".time()." AND user_id=$user_id"),0,0);
		if ($est_horses) $max_horse=4;
	}
	elseif ($row['vsad']>=9)
	{
		$est_horses = mysql_result(myquery("SELECT COUNT(*) FROM houses_users WHERE build_id IN (7,8) AND buildtime<".time()." AND user_id=$user_id"),0,0);
		if ($est_horses) $max_horse=3;
	}
	elseif ($row['vsad']>=5)
	{
		$est_horses = mysql_result(myquery("SELECT COUNT(*) FROM houses_users WHERE build_id IN (6,7,8) AND buildtime<".time()." AND user_id=$user_id"),0,0);
		if ($est_horses) $max_horse=2;
	}
	else
	{
		$est_horses = 1;
	}

	if ($char['GP'] >= $row['cena'] AND $char['MS_VSADNIK'] >= $row['vsad'] AND mysql_num_rows($sel) AND $est_horses==1 AND $count_horses<$max_horse)
	{
		if (!isset($see))
		{
			echo '<form name=koni action="" method=post>';
			echo '<font color=#00FFFF><h3>'.$row['nazv'].' (Ур. всадника: '.$row['vsad'].', Перенос предметов +'.$row['ves'].', Цена: '.$row['cena'].' золотых';
			if ($row['vsad']>=13)
			{
				echo ', Здание в личном землевладении: загон';
			}
			elseif ($row['vsad']>=9)
			{
				echo ', Здание в личном землевладении: конюшня';
			}
			elseif ($row['vsad']>=5)
			{
				echo ', Здание в личном землевладении: стойло';
			}
			echo ').</h5></font>';
			echo '<br>';
			if ($max_horse>1)
			{
				echo 'У тебя во владении '.$count_horses.' из '.$max_horse.' питомцев<br />';
			}
			if ($row['img']!='') echo '<img src=http://'.IMG_DOMAIN.'/vsd/'.$row['img'].'.jpg>';
			echo '<p align=justify>'.$row['opis'].'</p>';
			echo '<input type="submit" value="Купить"><input type="hidden" name="see"><input name="town_id" type="hidden" value="'.$town.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input type="button" value="Назад" onClick=location.replace("town.php?option='.$option.'")>';
			echo '</form>';
		}
		else
		{
			$already_horses = mysqlresult(myquery("SELECT COUNT(*) FROM game_users_horses WHERE user_id=$user_id AND horse_id=".$row['id'].""),0,0);
			if ($already_horses==0)
			{
				echo'<img src="http://'.IMG_DOMAIN.'/gorod/rohan/k.jpg"><br>';
				$ves=$row['ves'];
				$cena=$row['cena'];
				if ($char['vsadnik']!=0)
				{
					$ves_minus = mysqlresult(myquery("SELECT ves FROM game_vsadnik WHERE id=".$char['vsadnik'].""),0,0);
					$ves-=$ves_minus;
				}
				$up=myquery("UPDATE game_users SET vsadnik='".$row['id']."', GP=GP-$cena,CW=CW-'".($cena*money_weight)."', CC=CC+$ves WHERE user_id=$user_id LIMIT 1");
				myquery("INSERT INTO game_users_horses (user_id,horse_id,life,golod,used) VALUES ($user_id,".$row['id'].",".($row['life_horse']-1).",0,1)");
				setGP($user_id,-$cena,42);
				echo'<center>Куплено!';
				echo '<meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
			}
			else
			{
				echo 'У тебя уже есть такой питомец. Нельзя покупать двух одинаковых питомцев<br /><br />';
			}
		}
	}
	elseif ($char['GP'] < $row['cena'])
	{
		echo 'У тебя недостаточно денег';
	}
	elseif ($est_horses!=1)
	{
		echo 'У тебя нет нужной постройки';
	}
	elseif ($max_horse<=$count_horses)
	{
		echo 'Ты не можешь содержать более чем '.$max_horse.' '.pluralForm($max_horse,'питомца','питомцев','питомцев').'';
	}
	else
	{
		echo'У тебя недостаточная специализация верховой езды или не хватает денег<br>';
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

if (isset($_GET['sell']))
{
	echo'<img src="http://'.IMG_DOMAIN.'/gorod/rohan/k.jpg"><br>';
	if ($char['vsadnik'] > 0)
	{
		if (isset($_GET['sellnow']))
		{
			$sel=myquery("select * from game_vsadnik where id='".$char['vsadnik']."'");
			$rowww=mysql_fetch_array($sel);

			$sel=myquery("select * from game_vsadnik where town='$town' and id='".$char['vsadnik']."'");
			if (mysql_num_rows($sel))
			{
				$g=ceil($rowww['cena']/2);
			}
			else
			{
				$g=ceil($rowww['cena']/4);
			}
			$c=$rowww['ves'];
			$up=myquery("UPDATE game_users SET vsadnik='0', GP=GP+$g,CW=CW+'".($g*money_weight)."', CC=CC-$c WHERE user_id=$user_id LIMIT 1");
			myquery("DELETE FROM game_users_horses WHERE user_id=$user_id AND horse_id=".$char['vsadnik']."");
			setGP($user_id,$g,43);
			echo'<center>Продано!';
			echo '<meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
		}
		else
		{
			echo '<br /><br />Ты действительно хочешь продать свое животное?<br /><br /><br /><a href="town.php?option='.$option.'&sell&sellnow">Да, я хочу продать животное</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="town.php?option='.$option.'">Нет, я не хочу продавать животное</a><br /><br />';
		}
	}
	$img='http://'.IMG_DOMAIN.'/race_table/orc/table';
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

if (isset($_GET['eat']))
{
	echo'<img src="http://'.IMG_DOMAIN.'/gorod/rohan/k.jpg"><br>';
	if ($char['vsadnik'] > 0)
	{
		$golod = mysql_result(myquery("SELECT golod FROM game_users_horses WHERE user_id=$user_id AND horse_id=".$char['vsadnik'].""),0,0);
		if ($golod>0)
		{
			switch ($golod)
			{
				case 0: $state= 'сытое'; $k = 0; break;
				case 1: $state= 'слегка голодное'; $k = 1; break;
				case 2: $state= 'голодное'; $k = 2; break;
				case 3: $state= 'очень голодное'; $k = 3; break;
				case 4: $state= 'обессиленное'; $k = 4; break;
				default: $state= 'умирающее'; $k = 10; break;
			}
			$sel=myquery("select * from game_vsadnik where town='$town' and id='".$char['vsadnik']."'");
			if (!mysql_num_rows($sel))
			{
				$k=$k*1.5;
			}
			$row = mysql_fetch_array(myquery("select * from game_vsadnik where id='".$char['vsadnik']."'"));
			if ($char['clevel']<12) $gp=0;
			else $gp = $k*$row['price_eat'];
			if (!isset($_GET['do']))
			{
				if ($char['GP']<$gp)
				{
						echo 'У тебя недостаточно денег для покупки еды для своего питомца.<br />';
				}
				elseif ($gp==0)
				{
					echo 'Твое ездовое животное проголодалось. Его состояние оценивается как <b>'.$state.'</b>. Ты можешь бесплатно покормить его.
						 <br />Если ты не будешь кормить своего питомца - он очень быстро умрет!<br /><br />
					     ';
					echo '<a href="town.php?option='.$option.'&eat&do">Покормить животное</a>';	 
				}
				else 
				{
					echo 'Твое ездовое животное проголодалось. Его состояние оценивается как <b>'.$state.'</b>. Стоимость еды для твоего питомца составляет: '.$gp.' '.pluralForm($k,'монета','монеты','монет').'.<br />Если ты не будешь кормить своего питомца - он очень быстро умрет!<br /><br />';
					echo '<a href="town.php?option='.$option.'&eat&do">Заплатить '.$gp.' '.pluralForm($gp,'монета','монеты','монет').' за кормежку животного</a>';
				}
			}
			elseif ($char['GP']>=$gp)
			{
				if ($gp!=0)
				{
					$up=myquery("UPDATE game_users SET GP=GP-$gp,CW=CW-'".($gp*money_weight)."' WHERE user_id=$user_id LIMIT 1");
					setGP($user_id,-$gp,62);
				}
				myquery("UPDATE game_users_horses SET golod=0 WHERE horse_id=".$char['vsadnik']." AND user_id=$user_id");
				echo'<center>Питомец накормлен!';
				echo '<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
			}
		}
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}



if ($char['vsadnik'] > 0)
{
	echo'<img src="http://'.IMG_DOMAIN.'/gorod/rohan/k.jpg"><br>';
	$sel=myquery("select * from game_vsadnik where id='".$char['vsadnik']."'");
	$ro=mysql_fetch_array($sel);
	if ($ro['town']==$town)
	{
		echo'<br />У тебя уже есть конь! <br><br /><br />'.$ro['nazv'].' (Ур. всадника: '.$ro['vsad'].', Перенос предметов +'.$ro['ves'].', Цена: '.$ro['cena'].' золотых)<br>Может быть ты хочешь <br><br /><a href="town.php?option='.$option.'&sell">Продать '.$ro['nazv'].'  (за '.ceil($ro['cena']/2).' монет)</a>?';
	}
	else
	{
		echo'<br />У тебя уже есть конь! <br /><br /><br />'.$ro['nazv'].' (Ур. всадника: '.$ro['vsad'].', Перенос предметов +'.$ro['ves'].', Цена: '.$ro['cena'].' золотых)<br>Но ты его '.echo_sex('покупал','покупала').' не у нас! <br><br /><br />Может быть ты хочешь <br><br /><a href="town.php?option='.$option.'&sell">Продать '.$ro['nazv'].'  (за '.ceil($ro['cena']/4).' монет)</a>?';
	}
	echo '<br /><br /><br />';
	$selgolod = myquery("SELECT golod FROM game_users_horses WHERE user_id=$user_id AND horse_id=".$char['vsadnik']."");
	if ($selgolod!=false AND mysql_num_rows($selgolod)>0)
	{
		$golod = mysql_result($selgolod,0,0);
		if ($golod>0)
		{
			echo 'Твое ездовое животное проголодалось! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="town.php?option='.$option.'&eat">Покормить животное</a>';
		}
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}


echo'<img src="http://'.IMG_DOMAIN.'/gorod/rohan/k.jpg" width=480><br>';
echo'<table border=0>';
$sel=myquery("select * from game_vsadnik where town='$town'");
while ($row=mysql_fetch_array($sel))
{
	echo'<tr><td><a href="town.php?option='.$option.'&buy='.$row['id'].'">'.$row['nazv'].' (Ур. всадника: '.$row['vsad'].', Перенос предметов +'.$row['ves'].', Цена: '.$row['cena'].' золотых';
	if ($row['vsad']>=13)
	{
		echo ', Здание в личном землевладении: загон';
	}
	elseif ($row['vsad']>=9)
	{
		echo ', Здание в личном землевладении: конюшня';
	}
	elseif ($row['vsad']>=5)
	{
		echo ', Здание в личном землевладении: стойло';
	}
	echo ')</a></td><td></td></td>';
}
echo'</table>';
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

}

if (function_exists("save_debug")) save_debug(); 

?>
<STYLE TYPE="text/css">
<!--
.Заголовок1 {
	color: #FFFF00;
	text-decoration: underline;
	font-weight: normal;
	text-align: center;
	width: 100%;
}
.Заголовок2 {
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	width: 100%;
}
-->
</STYLE>
<?

if (function_exists("start_debug")) start_debug(); 

$da = getdate();
$current_month = GetGameCalendar_Year($da['year'],$da['mon'],$da['mday'])*12+GetGameCalendar_Month($da['year'],$da['mon'],$da['mday']);
if ($town!=0)
{
	echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
	echo'</head><center><font size=2 face=verdana color=ff0000>';

	$user_id = $char['user_id'];

	$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться хранилищем!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	$bank_user=myquery("select * from game_bank where user_id='$user_id'");
	$bank=mysql_fetch_array($bank_user);

	if (!isset($do)) $do='';

	echo '<font color=#F4F4F4 fave=Verdana,Tahoma,Arial size=2>';

	if ($do==1)
	{
		//Положим деньги в банк
		if (!isset($see))
		{
			echo'<form action="" method="post"><img src="http://'.IMG_DOMAIN.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>Пополнение лицевого счета! У тебя '.$char['GP'].' наличных золотых</td></tr></table>';
			echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
			echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td align="center">
			Введи сумму: <input name="money" type="text" size="10" maxlength="10">&nbsp;&nbsp;&nbsp;<input type="submit" value="Пополнить"></td></tr><tr><td align="center"><input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")>
			</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form>';
		}
		else
		{
			if ($town_id==$town)
			{
				$money=(int)$money;
				if ($money>0 and $money<=9999999999)
				{
					$prov=myquery("select user_id from game_users where gp>=$money and user_id='$user_id'");
					if (mysql_num_rows($prov))
					{
						$check = mysql_result(myquery("SELECT COUNT(*) FROM game_bank WHERE user_id='$user_id'"),0,0);
						if ($check!=0)
						{
							$result=myquery("update game_bank set summa=summa+'$money' where user_id='$user_id'");
						}
						else
						{
							$result=myquery("insert into game_bank (user_id, summa) values ('$user_id', '$money')");
						}
						$result=myquery("update game_users set GP=GP-'$money',CW=CW-'".($money*money_weight)."' where user_id='$user_id'");
						setGP($user_id,-$money,30);
						echo'<br><br><font color=ff0000><b>Ты '.echo_sex('внес','внесла').' '.$money.' золотых на лицевой счет</b></font><br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
						$result=myquery("insert into game_bank_log (user_id_from, name_from, summa,time) values ('$user_id', '".$char['name']."','$money',".time().")");
					}
					else echo 'Нехватает золота<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
				}
				else
					echo 'Введена неправильная сумма<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
			}
			else
				echo 'Ты находишься не в том городе<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==2)
	{
		//Возьмем деньги из банка
		if (!isset($see))
		{
			echo'<form action="" method="post"><img src="http://'.IMG_DOMAIN.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>На твоем лицевом счете '.$bank['summa'].' золотых!</td></tr></table>';
			echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
			echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td align="center">
			Введите сумму которую хотите взять: <input name="money" type="text" size="10" maxlength="10">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Взять"></td></tr><tr><td align="center"><input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")>
			</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form>';
		}
		else
		{
			if ($town_id==$town)
			{
				$money=(int)$money;
				if ($money>0 and $money<=9999999999)
				{
					$prov=myquery("select * from game_bank where summa>=$money and user_id='$user_id'");
					if (mysql_num_rows($prov))
					{
						$result=myquery("update game_bank set summa=summa-'$money' where user_id='$user_id'");
						$result=myquery("update game_users set GP=GP+'$money',CW=CW+'".($money*money_weight)."' where user_id='$user_id'");
						setGP($user_id,$money,31);
						echo'<br><br><font color=ff0000><b>Ты '.echo_sex('взял','взяла').' '.$money.' золотых</b></font><br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
						$result=myquery("insert into game_bank_log (user_id_to, name_to, summa,time) values ('$user_id', '".$char['name']."','$money',".time().")");
					}
					else echo 'Нехватает золота<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}
				else
					echo 'Введена неправильная сумма<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';
			}
			else
				echo 'Ты находишься не в том городе<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}


	if ($do==3)
	{
		$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
		//Перешлем деньги
		if (!isset($see))
		{
			echo'<div id="content" onclick="hideSuggestions();"><form action="" method="post" autocomplete="off"><img src="http://'.IMG_DOMAIN.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>У тебя на лицевом счете '.$bank['summa'].' золотых!';
			if($char['clevel']<=5) 
			{
				echo'<br />Но ты только '.$char['clevel'].' уровня, тебе не разрешено пересылать деньги';
				echo'</td></tr></table>
				<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")';
			}
			elseif (mysql_num_rows($prov)) 
			{
				echo'<br />У тебя есть непогашенный кредит! Тебе не разрешено пересылать деньги';
				echo'</td></tr></table>
				<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")';
			}
			else
			{ 
				echo'</td></tr></table>';
				echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
				echo'<tr><td align="center"><span class="Заголовок1">За перевод денег взимается комиссия банка в размере 5% от суммы (переводу между главой/замами клана бесплатны)</span></td></tr></table>';
				echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344">
				<tr><td>Введи сумму для пересылки: </td><td><input id="money" name="money" type="text" size="10" maxlength="10"></td></tr>
				<tr><td>Кому: </td><td><input name="name" type="text" id="keyword" size="30" maxsize="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>
				<tr><td>Введи сообщение для получателя: </td><td><textarea name="messaga" cols="30" rows="6"></textarea></td></tr>
				<tr><td colspan="2" align="center"><input type="submit" value="Переслать"> &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")>
				</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form></div><script>init();document.getElementById("money").focus();</script>';
			}
		}
		else
		{
			if ($town_id==$town)
			{
				if (!mysql_num_rows($prov))
				{
					$money=(double)$money;
					if ($money>0 and $money<=9999999999 and $char['clevel']>8)
					{
						$name_komu = $name;
						$name=myquery("select user_id from game_users where name='$name_komu'");
						if (!mysql_num_rows($name)) $name=myquery("select user_id from game_users_archive where name='$name_komu'");
						if (mysql_num_rows($name))
						{
							$kom = round($money*0.05,2);
							//$kom = $kom*(1-0.05*$char['MS_TORG']);
							list($nam)=mysql_fetch_array($name);
							if ($char['clan_id']>0)
							{
								$checkclan = myquery("SELECT * FROM game_clans WHERE clan_id=".$char['clan_id']." AND (glava=$nam OR zam1=$nam OR zam2=$nam OR zam3=$nam) AND (glava=$user_id OR zam1=$user_id OR zam2=$user_id OR zam3=$user_id)");
								if (mysql_num_rows($checkclan)>0)
								{
									$kom=0;
								}
							}
							$prov=myquery("select user_id from game_bank where summa>=('".($money+$kom)."') and user_id='$user_id'");
							if (mysql_num_rows($prov))
							{
								list($host1) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id='$user_id'"));
								list($host2) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id='$nam'"));
								//if($host1!=$host2)
								//{
								$sel=myquery("select user_id from game_bank where user_id='$nam'");
								if(mysql_num_rows($sel))
								{
									$mes=''.$char['name'].' перечислил на твой счёт во Всесредиземском  Центральном Банке '.$money.' золотых.';
									if (!empty($_POST['messaga']))
									{
										$mes.="<br><br><hr>Комментарий к переводу:<br /><br />".mysql_real_escape_string($_POST['messaga']);
									}
									$result=myquery("update game_bank set summa=summa+'$money' where user_id='$nam'");
									$result=myquery("update game_bank set summa=summa-'$money'-'$kom' where user_id='$user_id'");

									$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$nam', '0', 'Всесредиземский Центральный Банк', '$mes','0','".time()."')");

									$result=myquery("insert into game_bank_log (user_id_from, name_from, user_id_to, name_to, summa,host_from,host_to,time) values ('$user_id', '".$char['name']."','$nam','$name_komu','$money','$host1','$host2','".time()."')");
									echo'<br><font color=ff0000><b>Ты '.echo_sex('переслал','переслала').' '.$money.' золотых</b></font><br>';
									echo'<br><font color=ff0000><b>С тебя взыскана комиссия банка -  '.$kom.' золотых</b></font><br><meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
								}
								else echo 'У данного игрока нет открытых лицевых счетов<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
								//}
								//else echo 'Запрещенный перевод<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
							}
							else echo 'Нехватает золота на твоем лицевом счете (с учетом комиссионого сбора)<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else echo 'Игрока не существует<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
					else
						echo 'Введена неправильная сумма, нельзя передавать деньги игрокам до 9 уровня<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
				}
				else
					echo 'Нельзя пересылать деньги при непогашенном кредите<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"'; 
			}         
			else
				echo 'Ты находишься не в том городе<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==5)
	{
		die('Мы временно не выдаем кредиты игрокам!');
		$max_kredit = $char['clevel']*200;
		//Взять кредит
		if (!isset($see))
		{
			echo'<form action="" method="post"><img src="http://'.IMG_DOMAIN.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>У тебя на лицевом счете '.$bank['summa'].' золотых!';
			if($char['clevel']<=7) echo' Но ты только '.$char['clevel'].' уровня, тебе не разрешено брать кредиты';
			echo'</td></tr></table>';
			echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td align="center"><span class="Заголовок2">Мы рады приветствовать тебя в нашем кредитном отделе банка!</span></td></tr></table>';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
			if (mysql_num_rows($prov))
			{
			$kredit = mysql_fetch_array($prov);
			$summa_begin = $kredit['summa_begin'];
			$month_begin = $kredit['game_month_begin'];
			$month_end = $kredit['game_month_end'];
			$summa_end = $kredit['summa_end'];
			$procent = $summa_end-$summa_begin;
			$delta1 = $month_end - $month_begin;
			$delta2 = $current_month - $month_begin;
			$current_kredit = $summa_begin + round($procent*($delta2/$delta1),2);
			echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
			У тебя уже имеется кредит. Ты хочешь его закрыть?</td></tr><tr><td>
			На текущий момент: сумма кредита = '.$summa_begin.', начисленные проценты ='.round($procent*($delta2/$delta1),2).' монет.
			</td></tr>
			<tr><td colspan="2">Можно погасить только сразу всю сумму кредита</td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="Погасить кредит">';
			}
			else
			{
			echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
			На какой срок ты хочешь взять кредит?: </td><td>
			<select name="kredit_srok">
			<option value="0"></option>
			<option value="1">На 6 игровых месяцев(ставка 15% годовых)</option>
			<option value="2">На 1 игровой год(ставка 25% годовых)</option>
			<option value="3">На 2 игровых года(ставка 35% годовых)</option>
			<option value="4">На 3 игровых года(ставка 45% годовых)</option>
			</select>
			</td></tr>
			<tr><td colspan="2" class="Заголовок1">
			(Внимание! 1 игровой месяц = 1 дню реального календаря!)<br />
			(Внимание! За открытие ссудного счета с тебя будет взыскана сумма в размере 100 монет)<br />
			(Внимание! За невозврат кредита более чем спустя 7 игровых месяцев с тебя будет сумма кредита удержана автоматически. Если у тебя на тот момент не будет хватать денег для покрытия кредита - с тебя будут сняты и проданы вещи для погашения суммы кредита! Также ты будешь отправлен на каторгу!)<br />
			</td></tr>
			<tr><td>Максимальная сумма доступного тебе кредита:</td><td>'.$max_kredit.' монет</td></tr>
			<tr><td>Какую сумму ты возьмешь:</td><td><input type="text" value="0" maxsize=15 size=15 name="money"></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="Взять кредит">';
			}
			echo ' &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")>
			</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form>';
		}
		else
		{
			if ($town_id==$town)
			{
				$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
				if (!mysql_num_rows($prov))
				{
					$money=(int)$_POST['money'];
					if ($money>0 and $money<=9999999999 and $char['clevel']>=8 AND isset($_POST['money']) AND isset($_POST['kredit_srok']) AND $_POST['kredit_srok']>0 AND $_POST['kredit_srok']<5)
					{
						if ($money>$max_kredit) $money=$max_kredit;
						$kom = 100;
						//$kom = $kom*(1-0.05*$char['MS_TORG']);
						switch ($_POST['kredit_srok'])
						{
							case 1:
								$money_end=$money+$money*0.15/2;
								$time_end = time()+6*24*60*60;
								$game_month_end = $current_month+6;
							break;
							
							case 2:
								$money_end=$money+$money*0.25;
								$time_end = time()+12*24*60*60;
								$game_month_end = $current_month+12;
							break;
							
							case 3:
								$money_end=$money+$money*0.35+$money*0.35;
								$time_end = time()+24*24*60*60;
								$game_month_end = $current_month+24;
							break;
							
							case 4:
								$money_end=$money+$money*0.45+$money*0.45+$money*0.45;
								$time_end = time()+36*24*60*60;
								$game_month_end = $current_month+36;
							break;
						}
						$prov=myquery("select user_id from game_bank where summa>=$kom and user_id='$user_id'");
						if (mysql_num_rows($prov))
						{
							myquery("INSERT INTO game_bank_db_kr (user_id,vid,summa_begin,time_begin,summa_end,time_end,game_month_end,game_month_begin) VALUES ($user_id,1,$money,".time().",$money_end,$time_end,$game_month_end,$current_month)");
							myquery("update game_bank set summa=summa-$kom where user_id=$user_id");
							myquery("update game_bank set summa=summa+$money where user_id=$user_id");
							echo '<br /><br /><br /><span class="Заголовок2">Поздравляю! Кредитная сумма '.$money.' монет успешно переведена на твой лицевой счет в банке!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else echo '<br /><br /><br />Нехватает золота на твоем лицевом счете для уплаты сбора за открытие ссудного счета<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
					else
						echo '<br /><br /><br />Введена неправильная сумма или произошла ошибка<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
				}
				else
				{
					$kredit = mysql_fetch_array($prov);
					$summa_begin = $kredit['summa_begin'];
					$month_begin = $kredit['game_month_begin'];
					$month_end = $kredit['game_month_end'];
					$summa_end = $kredit['summa_end'];
					$procent = $summa_end-$summa_begin;
					$delta1 = $month_end - $month_begin;
					$delta2 = $current_month - $month_begin;
					$current_kredit = $summa_begin + round($procent*($delta2/$delta1),2);
					$prov1=myquery("select user_id from game_bank where summa>=$current_kredit and user_id='$user_id'");
					if (mysql_num_rows($prov1))
					{
						myquery("UPDATE game_bank SET summa=summa-$current_kredit WHERE user_id=$user_id");
						myquery("DELETE FROM game_bank_db_kr WHERE id=".$kredit['id']."");
						echo '<br /><br /><br /><span class="Заголовок2">Твой кредит успешно погашен! Спасибо что '.echo_sex('обратился','обратилась').' в наш банк!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
					else
						echo '<br /><br /><br />У тебя нет суммы '.$current_kredit.' на твоем лицевом счете<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
				} 
		   }
			else
				echo '<br /><br /><br />Ты находишься не в том городе<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==6)
	{
		die('Мы временно не принимаем вклады от населения!');
		//Сделать вклад
		if (!isset($see))
		{
			echo'<img src="http://'.IMG_DOMAIN.'/gorod/bank/1.jpg">
			<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>У тебя на лицевом счете '.$bank['summa'].' золотых!';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
			if($char['clevel']<=7) 
			{
				echo'<br />Но ты только '.$char['clevel'].' уровня, тебе не разрешено делать вклады';
				echo'</td></tr></table>
				<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")';
			}
			elseif (mysql_num_rows($prov)) 
			{
				echo'<br />У тебя есть непогашенный кредит! Тебе не разрешено делать вклады';
				echo'</td></tr></table>
				<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")';
			}
			else
			{
				$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=2 AND user_id=$user_id AND game_month_end<=$current_month"); 
				if (mysql_num_rows($prov))
				{
					echo '<form name="form2" action="" method="post">
					<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
					У тебя есть вклады, которые ты можешь получить:</td></tr>';
					while ($vklad = mysql_fetch_array($prov))
					{
						echo '<tr><td>';
						echo '<input type="radio" name="vklad_down" value="' . $vklad['id'] . '">Сумма вклада: '.$vklad['summa_begin'].', начисленные проценты: '.($vklad['summa_end']-$vklad['summa_begin']).', всего: '.$vklad['summa_end'].'</td></tr>';
					}
					echo '<tr><td align="center"><input type="submit" value="Забрать вклад и проценты">';
					echo '<input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></td></tr></table></form><br />';
				}            
				echo'<form action="" method="post">
				<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
				echo'<tr><td align="center"><span class="Заголовок2">Мы рады приветствовать тебя в нашем банка!</span></td></tr></table>';
				echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
				На какой срок ты хочешь оформить срочный вклад?: </td><td>
				<select name="vklad_srok">
				<option value="0"></option>
				<option value="1">На 1 игровой год(ставка 5% годовых)</option>
				<option value="2">На 2 игровых год(ставка 7% годовых)</option>
				<option value="3">На 3 игровых года(ставка 8% годовых)</option>
				<option value="4">На 5 игровых лет(ставка 10% годовых)</option>
				<option value="5">На 8 игровых лет(ставка 15% годовых)</option>
				</select>
				</td></tr>
				<tr><td colspan="2" class="Заголовок1">
				(Внимание! 1 игровой месяц = 1 дню реального календаря!)<br />
				(Внимание! За оформление вклада с тебя будет взыскана сумма в размере 100 монет)<br />
				(Внимание! По вкладам не действует автоматическая пролонгация! Для продления вклада ты '.echo_sex('должен','должна').' оформить новый вклад!)<br />
				</td></tr>
				<tr><td>На какую сумму ты оформишь вклад:</td><td><input type="text" value="0" maxsize=15 size=15 name="money"></td></tr>
				<tr><td colspan="2" align="center"><input type="submit" value="Сделать вклад">';
				echo ' &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в управление счетом" onClick=location.replace("town.php?option='.$option.'")>
				</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form></td></tr></table>';
			}
		}
		else
		{
			if ($town_id==$town)
			{
				$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
				if (!mysql_num_rows($prov))
				{
					if (isset($_POST['vklad_down']) AND $_POST['vklad_down']>0)
					{
						//забираем свой вклад
						$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=2 AND user_id=$user_id AND game_month_end<=$current_month AND id=".$_POST['vklad_down'].""); 
						if (mysql_num_rows($prov))
						{
							$vklad = mysql_fetch_array($prov);
							$add = $vklad['summa_end'];
							myquery("DELETE FROM game_bank_db_kr WHERE id=".$_POST['vklad_down']."");
							myquery("UPDATE game_bank SET summa=summa+$add WHERE user_id=$user_id");
							echo '<br /><br /><br /><span class="Заголовок2">Твой вклад переведен на твой лицевой счет! Спасибо что '.echo_sex('обратился','обратилась').' в наш банк!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else
							echo '<br /><br /><br />Ошибка при погашении вклада<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"'; 
					}
					else
					{
						//делаем новый вклад
						$money=(int)$_POST['money'];
						if ($money>0 and $money<=9999999999 and $char['clevel']>=8 AND isset($_POST['money']) AND isset($_POST['vklad_srok']) AND $_POST['vklad_srok']>0 AND $_POST['vklad_srok']<6)
						{
							$kom = 100;
							//$kom = $kom*(1-0.05*$char['MS_TORG']);
							switch ($_POST['vklad_srok'])
							{
								case 1:
									$money_end=$money+$money*0.05;
									$time_end = time()+12*24*60*60;
									$game_month_end = $current_month+12;
								break;
								
								case 2:
									$money_end=$money+$money*0.07+$money*0.07;
									$time_end = time()+24*24*60*60;
									$game_month_end = $current_month+24;
								break;
								
								case 3:
									$money_end=$money+$money*0.08+$money*0.08+$money*0.08;
									$time_end = time()+36*24*60*60;
									$game_month_end = $current_month+36;
								break;
								
								case 4:
									$money_end=$money+$money*0.1+$money*0.1+$money*0.1+$money*0.1+$money*0.1;
									$time_end = time()+60*24*60*60;
									$game_month_end = $current_month+60;
								break;

								case 5:
									$money_end=$money+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15;
									$time_end = time()+96*24*60*60;
									$game_month_end = $current_month+96;
								break;
	}
							$prov=myquery("select user_id from game_bank where summa>=".($kom+$money)." and user_id='$user_id'");
							if (mysql_num_rows($prov))
							{
								myquery("INSERT INTO game_bank_db_kr (user_id,vid,summa_begin,time_begin,summa_end,time_end,game_month_end,game_month_begin) VALUES ($user_id,2,$money,".time().",$money_end,$time_end,$game_month_end,$current_month)");
								myquery("update game_bank set summa=summa-".($kom+$money)." where user_id=$user_id");
								echo '<br /><br /><br /><span class="Заголовок2">Спасибо! Твой вклад в размере '.$money.' монет успешно принят в наш в банк!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
							}
							else echo '<br /><br /><br />Нехватает золота на твоем лицевом счете для внесения указанной суммы вклада (с учетом суммы сбора за принятие вклада)<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else
							echo '<br /><br /><br />Введена неправильная сумма или произошла ошибка<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"'; 
					  }         
				}
				else
					echo '<br /><br /><br />У тебя имеется непогашенный кредит. Вклад от тебя не принимается!<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
		   }
			else
				echo '<br /><br /><br />Ты находишься не в том городе<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==4)
	{
		OpenTable('title');
		echo'<br><center>';
		QuoteTable('open');
		//открываем лицевой счет
		if($char['GP']>=10)
		{
			myquery("INSERT IGNORE INTO game_bank (user_id,summa) VALUES ('$user_id','0')");
			myquery("UPDATE game_users SET GP=GP-10,CW=CW-'".(10*money_weight)."' WHERE user_id='$user_id'");
			setGP($user_id,10,32);
			echo'Поздравляю с открытием в нашем банке своего лицевого счета!';
		}
		else
		{
			echo'У тебя нет 10 монет. Мы не можем открывать лицевые счета таким беднякам';
		}
		echo'<center><br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в управление счетом" onClick=location.href="town.php?option='.$option.'">&nbsp;&nbsp;&nbsp;';
		QuoteTable('close');
		echo'<br>';
		OpenTable('close');
	}


	if($do=='')
	{
		echo'<img src="http://'.IMG_DOMAIN.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
		echo'<tr><td><center><font color=#FFFF00 size=2 face=Verdana,Tahoma,Arial><b>Всесредиземский Центральный Банк</td></tr></table><center>';

		if(!mysql_num_rows($bank_user))
		{
			OpenTable('title');
			echo'<br>';
			QuoteTable('open');
			echo'<center> Ты еще не открыл в нашем банке свой лицевой счет. Хочешь это сделать сейчас? Стоимость услуги составялет 10 монет.<br><br>';
			echo'<input type="button" value="Да, я хочу открыть новый лицевой счет" onClick=location.href="town.php?option='.$option.'&do=4">';
			QuoteTable('close');
			echo'<br>';
			OpenTable('close');
		}
		else
		{
			OpenTable('title');
			echo'<br>';
			QuoteTable('open',"100%");
			echo '<center><font color=#F4F4F4 fave=Verdana,Tahoma,Arial size=2>';

			echo'<br>У тебя уже открыт в нашем банке лицевой счет №'.$user_id.'';
			echo'<br>Сумма на твоем лицевом счете: '.$bank['summa'].' монет';
			echo'<br>==========================================';
			/*echo'<br>Кредиты: ';
			$str_kredit='У тебя нет оформленных кредитов';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE user_id=$user_id AND vid=1 AND game_month_end>=$current_month");
			if (mysql_num_rows($prov))
			{
				$kredit = mysql_fetch_array($prov);
				$summa_begin = $kredit['summa_begin'];
				$month_begin = $kredit['game_month_begin'];
				$month_end = $kredit['game_month_end'];
				$summa_end = $kredit['summa_end'];
				$procent = $summa_end-$summa_begin;
				$delta1 = $month_end - $month_begin;
				$delta2 = $current_month - $month_begin;
				$procent = round($procent*($delta2/$delta1),2);
				$str_kredit = "<br /><span class=\"Заголовок2\">У тебя оформлен кредит на сумму: ".$kredit['summa_begin'].". Сумма процентов за кредит составляет: ".($kredit['summa_end']-$kredit['summa_begin'])." ( на текущий момент начисленные проценты = $procent монет). Срок погашения кредита - через ".($kredit['game_month_end']-$current_month)." игровых месяцев!</span>";
			}
			echo $str_kredit;
			echo'<br>Срочные вклады: ';
			$str_kredit='У тебя нет срочных вкладов';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE user_id=$user_id AND vid=2");
			if (mysql_num_rows($prov))
			{
				$str_kredit="";
				while ($kredit = mysql_fetch_array($prov))
				{
					$summa_begin = $kredit['summa_begin'];
					$month_begin = $kredit['game_month_begin'];
					$month_end = $kredit['game_month_end'];
					$summa_end = $kredit['summa_end'];
					$procent = $summa_end-$summa_begin;
					$delta1 = $month_end - $month_begin;
					$delta2 = $current_month - $month_begin;
					$procent = round($procent*($delta2/$delta1),2);
					$str_kredit = "<br /><span class=\"Заголовок2\">У тебя оформлен вклад на сумму: ".$kredit['summa_begin'].". Сумма процентов по вкладу составляет: ".($kredit['summa_end']-$kredit['summa_begin']).".";
					if (($kredit['game_month_end']-$current_month)<=0) $str_kredit.='Ты можешь забрать этот вклад</span>';
					else $str_kredit.="Срок возврата вклада - через ".($kredit['game_month_end']-$current_month)." игровых месяцев!</span>";
					$str_kredit.="<br />";
				}
			}
			echo $str_kredit;*/
			echo'<br>';
			echo'<br>Ты можешь<ol><div align="left">';
			echo'<li><a href="town.php?option='.$option.'&do=1">Пополнить сумму на лицевом счете</a>';
			echo'<li><a href="town.php?option='.$option.'&do=2">Снять сумму с лицевого счета</a>';
			echo'<li><a href="town.php?option='.$option.'&do=3">Переслать сумму с лицевого счета на лицевой счет другого игрока</a>';
			//echo'<li><a href="town.php?option='.$option.'&do=5">Взять кредит</a>';
			//echo'<li><a href="town.php?option='.$option.'&do=6">Сделать срочный вклад</a>';
			echo'</div>';
			QuoteTable('close');
			echo'<br>';
			OpenTable('close');
		}
	}
	echo'<br /><br /><br /><div style="font-size:small;text-align:right;width:100%">Разработано при участии игрока "Вячеслав"</div>';
}

if (function_exists("save_debug")) save_debug(); 

?>
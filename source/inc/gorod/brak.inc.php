<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
	echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в город" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено заходить в храм бракосочетаний!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

$gp1 = 100;
$gp2 = 500;
$gp3 = 250;
$da = getdate();
if (($da['mon']==1 AND $da['mday']<=31)or($da['mon']==2 AND $da['mday']<=14)or($da['mon']==3 AND $da['mday']==8)or($da['mon']==7 AND $da['mday']==15))
{
	$gp1 = 0;
	$gp2 = 0;
	$gp3 = 0;
}

echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
$img='http://'.IMG_DOMAIN.'/race_table/orc/table';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top">';

if (!isset($brakopt))
{
  echo'<img src="http://'.IMG_DOMAIN.'/wedding/screen1.jpg"><br>';
  echo'Храм бракосочетаний:<br>';

  echo '<br><font face="verdana">Приветствую тебя в Храме Бракосочетаний, добро пожаловать! Надеюсь, ты '.echo_sex('пришел','пришла').' к нам, повинуясь зову сердца, чтобы скрепить свою любовь священными узами. Но если ты '.echo_sex('обнаружил','обнаружила').', что, к сожалению, '.echo_sex('ошибся','ошиблась').', заключая брак, то и в этом случаем сможем мы помочь тебе... Входи в Храм Бракосочетания, оставив за его вратами все прочие заботы! Наш священник за символическую плату - 100 золотых - зарегистрирует ваш брак и подарит вам обручальные кольца, с помощью которых вы всегда сможете быть рядом с любимым человеком, или оформит ваш развод за плату в 500 золотых</br>';
  $img='http://'.IMG_DOMAIN.'/race_table/orc/table';

  echo '<br><a href="?option='.$option.'&brakopt=reg">Войти в храм бракосочетаний</a><br>';
}
else
{
	if ($brakopt=='reg')
	{
	$check1 = myquery("SELECT * FROM game_users_brak WHERE (user1='".$char['user_id']."' OR user2='".$char['user_id']."') LIMIT 1");
	$check2 = myquery("SELECT * FROM game_users_brak WHERE (user2='".$char['user_id']."' AND status=0) LIMIT 1");
	$check3 = myquery("SELECT * FROM game_users_brak WHERE (user1='".$char['user_id']."' AND status=0) LIMIT 1");
	$check4 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status=1) LIMIT 1");
	$check5 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status='".$char['user_id']."') LIMIT 1");
	$check6 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status>1  AND status<>'".$char['user_id']."') LIMIT 1");
		echo'
		<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td>';

		if (!mysql_num_rows($check1))  echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen2.jpg" width="470"><br>';
		elseif (mysql_num_rows($check2))  echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen5.jpg"><br>';
		elseif (mysql_num_rows($check3))  echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen9.jpg"><br>';
		elseif (mysql_num_rows($check4))  echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen11.jpg"><br>';
		elseif (mysql_num_rows($check5))  echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen1314.jpg"><br>';

		echo '</td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
		$sost = '<...неопределенно...>';
		if (!mysql_num_rows($check1)) $sost = 'Ты не зарегистрирован в браке';
		if (mysql_num_rows($check3)) $sost = 'Тобою подана заявка на регистрацию брака';
		if (mysql_num_rows($check2)) $sost = 'На твое имя подана заявка на регистрацию брака';
		if (mysql_num_rows($check4)) $sost = 'Ты состоишь в зарегистрированном браке';
		if (mysql_num_rows($check5)) $sost = 'На твое имя подана заявка на оформление развода';
		if (mysql_num_rows($check6)) $sost = 'Тобою подана заявка на оформление развода';
		echo '<tr><td><font size=2 face="verdana,tahoma"><center>Твое состояние: <u><font color="#00FFFF">'.$sost.'</font></u></center></font></td></tr>';
		echo '<tr><td>&nbsp;</td></tr>';
		if (!mysql_num_rows($check1))
		echo '<tr><td>Как замечательно, что ты '.echo_sex('смог','смогла').' найти в этом огромном мире, среди обыденной суеты, в серой безликой толпе то, ради чего и стоит жить - свою Любовь, свою вторую половину! Так заключите священный брак с благословения Высших сил, и оставайтесь вместе отныне и навеки, став единым целым!<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=newreg">Подать заявку на регистрацию нового брака</a></center></font></td></tr>';
		if (mysql_num_rows($check2))
		{
		$usr = mysql_fetch_array($check2);
		$selec = myquery("SELECT name FROM game_users WHERE user_id='".$usr['user1']."'");
		if (!mysql_num_rows($selec)) $selec = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr['user1']."'");
		list($name1) = mysql_fetch_array($selec);
		echo '<tr><td>'.echo_sex('Согласен','Согласна').' ли ты заключить брак с <b><font color="#FF0066">'.$name1.'</font></b>, с кем ты будешь делить все радости и огорчения, с кем не расстанешься никогда, не взирая ни на какие трудности и преграды, с кем будешь '.echo_sex('счастлив','счастлива').' вечно, назло сложностям и невзгодам? Но подумай дважды, ибо это не пустые слова, и выбором своим ты определишь свою дальнейшую жизнь…<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=confirmreg">Подтвердить согласие на бракосочетание c игроком "'.$name1.'"</a></center></font></td></tr>';
		}
		if (mysql_num_rows($check3))
		{
		$usr = mysql_fetch_array($check3);
		$selec = myquery("SELECT name FROM game_users WHERE user_id='".$usr['user2']."'");
		if (!mysql_num_rows($selec)) $selec = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr['user2']."'");
		list($name1) = mysql_fetch_array($selec);
		echo '<tr><td>Ты ветреная особа! Твой выбор меняется чаще, чем бьётся твое сердце - нельзя так относиться к таинству Бракосочетания! Но только ради тебя мы посмотрим, что можно сделать.<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=delreg">Удалить заявку на бракосочетание c игроком "'.$name1.'"</a></center></font></td></tr>';
		}
		if (mysql_num_rows($check4))
		echo '<tr><td>Ты '.echo_sex('решил','решила').' развестись? Что же послужило причиной столь грустному деянию? В любом случае мы тебе поможем, итак ты '.echo_sex('уверен','уверена').' что хочешь подать на развод?<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=razvod">Подать заявку на оформление развода</a></center></font></td></tr>';
		if (mysql_num_rows($check5))
		echo '<tr><td>Похоже, ты твердо '.echo_sex('решил','решила').'?!… Согласно нашим расценкам, с каждого из вас будет взыскана плата в '.$gp2.' монет<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=confirmrazvod">Подтвердить согласие на оформление развода</a></center></font></td></tr>';
		if (mysql_num_rows($check1) AND !mysql_num_rows($check4) AND !mysql_num_rows($check5) AND !mysql_num_rows($check2) AND !mysql_num_rows($check3))
		echo '<tr><td><center><b><font face="arial" color="#FF0066" size=2>Извини, но для тебя нет доступных услуг в нашем храме</font><b></center></td></tr>';
		echo '<tr><td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>';
	}
	if ($brakopt=='delreg')
	{
		echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen10.jpg"><br>';
		echo '<center><b><font face="arial" color="#FF0066" size=2>Тебе повезло, на этот раз мы успели помешать твоему легкомыслию, но в следующий раз выкручиваться будешь '.echo_sex('сам','сама').'. Что ж, надеюсь, теперь ты станешь осмотрительнее.</font><b></center>';
		$up = myquery("DELETE FROM game_users_brak WHERE (user1 = '".$char['user_id']."' AND status=0)");
	}
	if ($brakopt=='newreg')
	{
		if ($char['GP']>=$gp1)
		{
		  if (!isset($name))
		  {
			echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen3.jpg" height="300"><br>';
			echo'<div id="content" onclick="hideSuggestions();">Кто же '.echo_sex('она','он').', с кем ты будешь делить все радости и огорчения, с кем не расстанешься никогда, не взирая ни на какие трудности и преграды, с кем будешь '.echo_sex('счастлив','счастлива').' вечно, назло сложностям и невзгодам? Кому ты посвятишь свой ум, честь и храбрость? Кто встретит с тобой саму Смерть и даже пойдёт дальше?';
		  echo '<br><center>Укажи имя твоего избранника:<font size="1" face="Verdana" color="#ffffff"><input type="text" size="15" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div><br><br>
  <input name="" type="button" value="Подать заявку на регистрацию брака" onClick="location.href=\'?option='.$option.'&brakopt=newreg&name=\'+document.getElementById(\'keyword\').value"></center></div><script>init();</script>';
		  }
		  else
		  {
		  if ($name!=$char['name'])
		  {
			echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen4.jpg"><br>';
			echo '<center>Ну что ж, вот момент истины, скоро мы узнаем '.echo_sex('достоин','достойна').' ли ты права называться супругом или твой зов так и не найдёт отклика в сердце игрока <b><font color="#FF0066">'.$name.'</font></b>)… Но в случае неудачи не отчаивайся, ведь мир полон хороших людей, которые жаждут твоего внимания, стоит только присмотреться ! После подтверждения в нашем храме твоим избранником поданой тобой заявки ваш брак будет официально зарегистрирован и вам будут одеты обручальные кольца. С тебя взыскана плата за услуги нашего храма - '.$gp1.' золотых';
			$up = myquery("UPDATE game_users SET GP=GP-$gp1,CW=CW-'".($gp1*money_weight)."' WHERE user_id='".$char['user_id']."'");
			setGP($user_id,-$gp1,33);
			$sel = myquery("SELECT user_id FROM game_users WHERE name='".$name."'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT user_id FROM game_users_archive WHERE name='".$name."'"); 
			list($user_id1) = mysql_fetch_array($sel);
			$up = myquery("INSERT INTO game_users_brak (user1,user2,status) VALUES ('".$char['user_id']."','$user_id1','0')");
		  }
		  else
		  {
			echo '<center>За такую шутку с тебя взыскан штраф в размере '.$gp1.' золотых</center>';
			setGP($user_id,-$gp1,33);
			$up = myquery("UPDATE game_users SET GP=GP-$gp1,CW=CW-'".($gp1*money_weight)."' WHERE user_id='".$char['user_id']."'");
		  }
		  }
		}
		else
		{
		echo 'У тебя недостаточно средств для оплаты услуг нашего храма (тебе необходимо иметь при себе '.$gp1.' золотых). Приходи позже, когда сможешь оплатить наши услуги';
		}
	}
	if ($brakopt=='confirmreg')
	{
	$check2 = myquery("SELECT * FROM game_users_brak WHERE (user2='".$char['user_id']."' AND status=0) LIMIT 1");
	if (mysql_num_rows($check2))
	{
		if (!isset($otvet))
		{
			$user_id1 = mysql_fetch_array($check2);
			$sel = myquery("SELECT name FROM game_users WHERE user_id='".$user_id1['user1']."'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='".$user_id1['user1']."'");
			list($name1)=mysql_fetch_array($sel);
			echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen5.jpg"><br>';
			echo 'Тебе предлагают союз, союз который свяжет вас сильнее чем любые путы или заклятья - Священный Союз Брака! Помни, что ты делаешь выбор, от которого зависит вся твоя дальнейшая жизнь. Сейчас ты можешь обрести верного спутника или разбить чьё-то сердце…';
			echo '<center>';
			echo ''.echo_sex('Согласен','Согласна').' ли ты на регистрацию брака с игроком: <b><font color="#FF0066">'.$name1.'</font></b><br><br>
			<input name="" type="button" value="Да, зарегистрируйте наш брак" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmreg&otvet=1\'"><br><br>
			<input name="" type="button" value="Нет, я против регистрации брака" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmreg&otvet=0\'"><br><br></center>';

		}
		else
		{
		if ($otvet=='0')
		{
			echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen8.jpg"><br>';
			echo '<center><b><font face="arial" color="#FF0066" size=2>Как ни прискорбно, но мы вынуждены сообщить, что ты '.echo_sex('решил','решила').' отказаться от заключения брака. Что ж, поверь, это значит только одно - твой выбор был неверен, если даже ты поначалу не '.echo_sex('замечал','заечала').' этого… Ищи - и ты найдешь свою истинную любовь!</font><b></center>';
			$up = myquery("DELETE FROM game_users_brak WHERE (user2 = '".$char['user_id']."' AND status=0)");
		}
		elseif ($otvet=='1')
		{
			echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen6.jpg"><br>';
			echo '<center><b><font face="arial" color="#FF0066" size=2>Именем Тьмы, Света и волею самого Илуватара объявляетесь вы мужем и женой! И да пронесете вы свою любовь  сквозь время и пространство, и да не разлучит вас отныне никто и ничто, ни человек, ни валар, ни несчастия, ни сама Смерть! Поздравляем вас! </font><b></center>';
			$cur_time=date("d.m.Y",time());
			$up = myquery("UPDATE game_users_brak SET status=1,datareg='$cur_time' WHERE (user2 = '".$char['user_id']."' AND status=0)");
		}

		}
	}
	}

	if ($brakopt=='razvod')
	{
		if ($char['GP']>=$gp2)
		{
		   $check4 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status=1) LIMIT 1");
		   $usr = mysql_fetch_array($check4);
		   if ($usr['user1']==$char['user_id']) $usr2 = $usr['user2'];
		   elseif ($usr['user2']==$char['user_id']) $usr2 = $usr['user1'];
		   $sel = myquery("SELECT name FROM game_users WHERE user_id='$usr2'");
		   if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='$usr2'");
		   list($name)=mysql_fetch_array($sel);
		   echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen12.jpg"><br>';
		   echo '<center>Итак, если игрок <b><font color="#FF0066">'.$name.'</font></b> согласится, мы избавим тебя от уз брака, ставших цепями оков. После подтверждения в нашем храме твоим избранником поданой тобой заявки ваш брак будет официально расторгнут. С каждого из вас будет взыскана плата за услуги нашего храма - '.$gp3.' золотых';
			$up = myquery("UPDATE game_users_brak SET status='$usr2' WHERE id='".$usr['id']."'");
	   }
		else
		{
		echo 'У тебя недостаточно средств для оплаты услуг нашего храма (необходимо иметь при себе '.$gp2.' золотых). Приходи позже, когда сможешь оплатить наши услуги';
		}
	}
	if ($brakopt=='confirmrazvod')
	{
	$check5 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status='".$char['user_id']."') LIMIT 1");
	if (mysql_num_rows($check5))
	{
		if (!isset($otvet))
		{
		   $usr = mysql_fetch_array($check5);
		   if ($usr['user1']==$char['user_id']) $usr2 = $usr['user2'];
		   elseif ($usr['user2']==$char['user_id']) $usr2 = $usr['user1'];
		   $sel = myquery("SELECT name FROM game_users WHERE user_id='$usr2'");
		   if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='$usr2'");
		   list($name)=mysql_fetch_array($sel);
		   echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen1314.jpg"><br>';
			echo '<center>'.echo_sex('Согласен','Согласана').' ли ты расторгнуть ставший, возможно, ненавистным, брак с игроком: <b><font color="#FF0066">'.$name.'</font></b><br><br>
			<input name="" type="button" value="Да, оформите наш развод" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmrazvod&otvet=1\'"><br><br>
			<input name="" type="button" value="Нет, я против развода" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmrazvod&otvet=0\'"><br><br></center>';

		}
		else
		{
		if ($otvet=='1')
		{
		   $usr = mysql_fetch_array($check5);
		   echo'<center><img src="http://'.IMG_DOMAIN.'/wedding/screen15.jpg"><br>';
			echo '<center><b><font face="arial" color="#FF0066" size=2>Ты вновь '.echo_sex('свободен','свободна').'... Но постарайся усвоить этот печальный опыт и не повторять подобной ошибки в будущем… С каждого из вас взыскана плата за услуги - '.$gp3.' монет!</font><b></center>'; 
			$up = myquery("DELETE FROM game_users_brak WHERE ((user1 = '".$char['user_id']."' OR user2 = '".$char['user_id']."') AND status='".$char['user_id']."')");
			$up = myquery("UPDATE game_users SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user1']."'");
			$up = myquery("UPDATE game_users_archive SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user1']."'");
			setGP($usr['user1'],-$gp3,33); 
			$up = myquery("UPDATE game_users SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user2']."'");
			$up = myquery("UPDATE game_users_archive SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user2']."'");
			setGP($usr['user2'],-$gp3,33); 
		}
		elseif ($otvet=='0')
		{
			echo '<center><b><font face="arial" color="#FF0066" size=2>И правильно! В семье же все-таки лучше</font><b></center>';
			$up = myquery("UPDATE game_users_brak SET status=1 WHERE ((user1 = '".$char['user_id']."' OR user2 = '".$char['user_id']."') AND status='".$char['user_id']."')");
		}

		}
	}
	}

}
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

}

if (function_exists("save_debug")) save_debug(); 

?>
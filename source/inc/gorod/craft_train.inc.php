<?

if (function_exists("start_debug")) start_debug(); 

$cost = 80;
$count_prof = floor($char['clevel']/6);

//$count_prof+=10;
//$count_prof+=20;$cost = 0;
if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
		echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти" onClick=location.href="act.php">&nbsp;&nbsp;&nbsp;';
	}

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено изучать профессии!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	$img='http://'.IMG_DOMAIN.'/race_table/human/table';
	$width='100%';
	$height='100%';

	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="padding-left:25px;" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';

	//основной экран
	echo '<center><b>Здравствуй! Здесь ты можешь выучить базовые знания различных профессий!</b><br><br><br>Но учти, что ты сможешь развить свои способности в изучении только '.$count_prof.' из представленных ниже профессий!<br /><br />Стоимость изучения одной профессии составляет '.$cost.' монет</center><br /><br />';
	
	if (isset($_GET['train']))
	{
		echo '<center>';
		QuoteTable('open');
		echo '<center>';
		if ($char['GP']>=$cost)
		{
			$alr_known = mysqlresult(myquery("SELECT COUNT(*) FROM game_users_crafts WHERE user_id=$user_id AND profile=1 AND craft_index IN (4,5,6,7,8,9,10,11,12)"),0,0);
			if ($alr_known>=$count_prof)
			{
				echo 'Ты уже знаешь максимально тебе доступное - '.$count_prof.' - количество профессий';
			}
			else
			{
				$craft_index = (int)$_GET['train'];
				if (isset($_GET['agree']))
				{
					myquery("INSERT INTO game_users_crafts SET user_id=$user_id,craft_index=$craft_index,profile=1 ON DUPLICATE KEY UPDATE profile=1");
					setGP($user_id,-$cost,64);
					myquery("UPDATE game_users SET GP=GP-".($cost).",CW=CW-".($cost*money_weight)." WHERE user_id=$user_id");
					echo '<br />ПОЗДРАВЛЯЮ! <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ты узнал базовые знания профессии: <span style="font-weight:900;font-size:13px;font-family:Arial;color:#FFFF00">'.get_craft_name($craft_index).'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />&nbsp;
					<meta http-equiv="refresh" content="10;url=town.php?option='.$_GET['option'].'">';
				}
				else
				{
					echo 'Ты выбрал для обучения профессию: <span style="font-weight:900;font-size:13px;font-family:Arial;color:#FFFF00">'.get_craft_name($craft_index).'</span><br /><br />Ты действительно хочешь ее выучить? <br />
					<br />
					<input type="button" onClick="location.href=\'town.php?option='.$_GET['option'].'&agree&train='.$craft_index.'\'" value="Да, я действительно хочу выучить эту профессию">
					<br /><br />
					<input type="button" onClick="location.href=\'town.php?option='.$_GET['option'].'\'" value="Нет, я не хочу учить эту профессию"><br />&nbsp;';
				}
			}
		}
		else
		{
			echo 'У тебя недостаточно денег (нужно '.$cost.' монет)';
		}
		QuoteTable('close');
		echo '<br /><br /></center>';
	}
	
	if ($char['GP']<$cost)
	{
		echo '<br />Мои услуги стоят денег - '.$cost.' монет. Приходи ко мне, когда у тебя будет эта сумма';
	}
	elseif (!isset($_GET['train']))
	{
		echo '<table width="100%" cellspacing=3 cellpadding=3>';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=4 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Лесоруб" - позволяет заниматься рубкой леса</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=4\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=5 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Каменотес" - позволяет добывать и обрабатывать камни</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=5\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=6 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Рудокоп" - позволяет заниматься добычей руды и драгоценностей</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=6\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=7 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Плотник" - позволяет обрабатывать бревна</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'town.php?option='.$_GET['option'].'&train=7\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=8 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Охотник" - позволяет заниматься охотой</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=8\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=9 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Скорняк" - позволяет заниматься обработкой дичи, пойманной охотником</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=9\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=10 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Литейщик" - позволяет заниматься переплавкой руды, добытой рудокопом</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=10\'" value="Выучить эту профессию"></span></td></tr>
			';
		$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=11 AND profile=1");
		if (!mysql_num_rows($sel))
			echo'
			<tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Оружейник" - позволяет создавать различные предметы из результатов труда литейщика, рудокопа и плотника</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=11\'" value="Выучить эту профессию"></span></td></tr>
		';
        $sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=12 AND profile=1");
        if (!mysql_num_rows($sel))
            echo'
            <tr><td><span style="color:white;font-size:11px;font-weight:800;">Профессия "Кузнец" - позволяет ремонтировать частично или полностью сломанные предметы</span></td><td>&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'?option='.$_GET['option'].'&train=12\'" value="Выучить эту профессию"></span></td></tr>
        ';
		echo '</table>';
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>
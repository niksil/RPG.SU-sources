<?

if (function_exists("start_debug")) start_debug(); 

$cost = round(4/5*$char['clevel'],2);
$da = getdate();
if ($da['mon']==7 AND $da['mday']==15)
{
    $cost = 0;
}
if ($da['mon']==12 AND $da['mday']==31)
{
    $cost = 0;
}
if ($da['mon']==1 AND $da['mday']==1)
{
    $cost = 0;
}
if ($da['mon']==1 AND $da['mday']==2)
{
    $cost = 0;
}
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
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено посещать лекаря!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	$img='http://'.IMG_DOMAIN.'/race_table/human/table';
	$width='100%';
	$height='100%';

	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';

	//основной экран
	echo '<b>Добро пожаловать к лучшему лекарю!</b><br><br><br>';
	
	if (isset($_POST['submit']))
	{
        echo '<center>';
        QuoteTable('open');
		if ($char['HP_MAXX']>$char['HP_MAX'])
		{
            $add = min((int)$_POST['kol'],$char['HP_MAXX']-$char['HP_MAX']);
            $price = $add*$cost;
			if ($char['GP']>=$price)
			{
 				myquery("UPDATE game_users SET HP_MAX=HP_MAX+$add,GP=GP-$price,CW=CW-".($price*money_weight)." WHERE user_id=$user_id");
                setGP($user_id,-$price,44);
				$char = mysql_fetch_array(myquery("SELECT * FROM game_users WHERE user_id=$user_id"));
                echo 'Вылечено '.$add.' единиц здоровья за '.$price.' монет';
			}
            else
            {
                echo 'У тебя недостаточно денег для лечения '.$add.' единиц здоровья (нужно '.$price.' монет)';
            }
		}
        else
        {
            echo 'У тебя нет травм!';
        }
        QuoteTable('close');
        echo '</center>';
	}
	
	if ($char['HP_MAX']>=$char['HP_MAXX'])
	{
		echo '<br />С тобой все в порядке. Ты не нуждаешься в моих услугах. Приходи ко мне, когда тебе понадобится моя помощь';
	}
	else
	{
		if ($char['GP']<10)
		{
			echo '<br />Мои услуги стоят денег. Минимум - 10 монет. Приходи ко мне, когда у тебя будет минимальная сумма';
		}
		else
		{
			echo'
			<form action="" method="POST">
			<br><span style="color:white;font-size:11px;font-weight:800;">Твое максимальное количество здоровья: <span style="color:red;font-size:12px;font-weight:800;">'.$char['HP_MAXX'].'</span> единиц</span>
			<br><span style="color:white;font-size:10px;">Твое текущее максимальное количество здоровья: <span style="color:red;font-size:11px;">'.$char['HP_MAX'].'</span> единиц</span>
			<br><span style="color:#F0F0F0;font-size:10px;">Ты можешь поднять свое текущее максимальное здоровье на: <span style="color:#80FF80;font-size:11px;">'.($char['HP_MAXX']-$char['HP_MAX']).'</span> единиц</span>
			<br><br><br>Лечение не бесплатное! Цена лечения 1 единицы здоровья = '.$cost.' монет<br><br><span style="width:100%;text-align:right;">Укажи кол-во единиц для лечения: <input type="text" size="5" name="kol" value="'.($char['HP_MAXX']-$char['HP_MAX']).'"><input type="submit" name="submit" value="Заплатить лекарю за лечение"></span>
			';
		}
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>
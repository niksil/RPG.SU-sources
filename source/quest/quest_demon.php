<?php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '16');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');
$quest_id=25;
$book_id=3;

//флаги квеста:
//1 - игрок знает пароль для страницы 9
//2 - игрок имеет Щит Древней Тьмы и Щит Огня для страницы 16
//3 - количество воды в фляге
//4 - игрок имеет траву Феникса
//5 - количество еды игрока
//6 - у игрока есть меч с золотой змейкой
//7 - игрок имеет Ледяной Кинжал
//8 - игрок имеет Щит Времени
//9 - игрок имеет Лед

$print_text = true;
$alt_text = '';

function before_print()
{
	global $book_id,$user_id,$print_text,$alt_text;
	if (!isset($_GET['page'])) return;
	if ($_GET['page']==16)
	{
		$sel = myquery("SELECT * FROM bookgame_users_flags WHERE bookgame=$book_id AND user_id=$user_id AND flag=2");
		if (!mysql_num_rows($sel))
		{
			$print_text = 1;
			$alt_text = "Неимоверной силой воли ты отправляешь Пожирателей Душ туда, откуда они были призваны. Аргалакс в бешенстве и кричит:
			«Теперь я вызышаю Костяного Занбара и Балтуса Страшного!!!»
			Они материализуются и ты смог бы противиться их заклинаниям, только если бы у тебя есть были Щит Древней Тьмы и Щит Огня. Но их у тебя не оказалось, и ты бесславно погиб на поле боя!"; 	
		}
	}
	if ($_GET['page']==17)
	{
		myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=2");
	}
	if ($_GET['page']==20)
	{
		myquery("INSERT INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=3,value=0 ON DUPLICATE KEY UPDATE value=LEAST(4,value*2)");
		myquery("UPDATE bookgame_users SET dex=dex+LEAST(4,dex*2) WHERE bookgame=$book_id AND user_id=$user_id");
	}
	if ($_GET['page']==21)
	{
		$check = mysqlresult(myquery("SELECT COUNT(*) FROM bookgame_users_flags WHERE bookgame=$book_id AND user_id=$user_id AND (flag=4 OR flag=7)"),0,0);
		if ($check!=2)
		{
			$print_text = 1;
			$alt_text = "«НЕЕЕЕТ!!!» - Аргалакс корчится в конвульсиях и его энергия выходит наружу. Вы впитываете её часть и восстанавливаете 3 выносливости.На этом твое приключение заканчивается";     
		}
	}
	if ($_GET['page']==22)
	{
		myquery("INSERT INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=5,value=1 ON DUPLICATE KEY UPDATE value=value+1");
		myquery("UPDATE bookgame_users SET dex=dex+1 WHERE bookgame=$book_id AND user_id=$user_id");
	}
	if ($_GET['page']==41)
	{
		myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=4");
	}
	if ($_GET['page']==8)
	{
		myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=4");
	}
	if ($_GET['page']==19)
	{
		myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=6");
	}
	if ($_GET['page']==10)
	{
		myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=8");
		myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=9");
	}
	if ($_GET['page']==44)
	{
		list($gp) = mysqlresult(myquery("SELECT gp FROM bookgame_users WHERE bookgame=$book_id AND user_id=$user_id"),0,0);
		if ($gp>=21)
		{
			myquery("INSERT IGNORE INTO bookgame_users_flags SET user_id=$user_id,bookgame=$book_id,flag=7");
			myquery("UPDATE bookgame_users SET gp=gp-21 WHERE user_id=$user_id AND bookgame=$book_id");
		}
	}
	if ($_GET['page']==45)
	{
		$check = mysqlresult(myquery("SELECT COUNT(*) FROM bookgame_users_flags WHERE bookgame=$book_id AND user_id=$user_id AND (flag=8 OR flag=9)"),0,0);
		if ($check!=2)
		{
			$print_text = 1;
			$alt_text = "«ААА!!!,- крик Аргалакса звенит под потолком.- Я вызываю Жаррадана Мара и Шарелу, Ведьму Снега!!» Ты можешь противиться заклинаниям двух колдунов, только если есть Щит Времени и Лёд. Но их у тебя не оказалось, и ты бесславно погиб на поле боя!";     
		}
	}
}

include("quest_bookgame.inc.php");

if (isset($_GET['page']) AND $_GET['page']==9)
{
	/*
	$sel = myquery("SELECT * FROM bookgame_users_flags WHERE bookgame=$book_id AND user_id=$user_id AND flag=1");
	if (mysql_num_rows($sel))
	{
		$flag = mysql_fetch_array($sel);
		echo '<br />Ты знаешь пароль! Переходи к <a href="http://'.DOMAIN.''.$_SERVER['PHP_SELF'].'?page='.$flag['value'].'">'.$flag['value'].'</a><br />';
	}
	*/
	if (!isset($_GET['pass']))
	{
		echo 'Если ты знаешь пароль, то введи его: <input type="text" size="5" value="0" id="pass">&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href=\'http://'.DOMAIN.''.$_SERVER['PHP_SELF'].'&page=9&pass=\'+document.getElementById(\'pass\').value+\'\'" value="Ввести пароль">'; 
	}
	else
	{
		$pass = (int)$_GET['pass'];
		if ($pass==23)
		{
			myquery("UPDATE bookgame_users SET step=23 WHERE bookgame=$book_id AND user_id=$user_id");
		}
		else
		{
			myquery("UPDATE bookgame_users SET step=37 WHERE bookgame=$book_id AND user_id=$user_id");
		}
		setLocation("http://".DOMAIN.''.$_SERVER['PHP_SELF']);
	}                        
}

echo '<br /><br /><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?exit">Выйти из квеста</a>';

?>
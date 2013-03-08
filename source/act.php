<?
require_once('inc/engine.inc.php');
include('inc/xray.inc.php');
include('inc/lib.inc.php');


if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '5');
}
else
{
	die();
}

include('inc/lib_session.inc.php');
include('inc/functions.php');

setFunc($user_id,5);

if (function_exists("start_debug")) start_debug();

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (isset($_GET['func']))
{
	$func = $_GET['func'];
}
else
{
	$func = 'main';
}

$obnul_free = mysql_result(mysql_query("SELECT obnul_free FROM game_users_data WHERE user_id=$user_id"),0,0);
if ($obnul_free>0)
{
    Header("Location:obnul.php");
    exit;
}

require_once('inc/template_header.inc.php');

function pay_ref($user_id,$gp_amount)
{

		// реферальные
		$ref_pay = myquery("SELECT * FROM game_invite WHERE invite_id='".$user_id."' ");
		if (mysql_num_rows($ref_pay)>0)
		{
			$arr_ref_pay=mysql_fetch_array($ref_pay);
			$up=mysql_query("update game_users SET GP=GP+".$gp_amount.",CW=CW+'".($gp_amount*money_weight)."' where user_id='".$arr_ref_pay['user_id']."'");
			setGP($arr_ref_pay['user_id'],$gp_amount,3);
			$up=mysql_query("update game_users_archive SET GP=GP+".$gp_amount.",CW=CW+'".($gp_amount*money_weight)."' where user_id='".$arr_ref_pay['user_id']."'");

		}


}

// Init google gears
echo ("<script type=\"text/javascript\">var MANIFEST_FILENAME = \"http://".IMG_DOMAIN."/gears/sz.json\";</script>\n");
echo ("<script type=\"text/javascript\" src=\"http://".IMG_DOMAIN."/gears/gears_init.js\"></script>\n");
echo ("<script type=\"text/javascript\" src=\"http://".IMG_DOMAIN."/gears/go_offline.js\"></script>\n");
//echo ("<script type=\"text/javascript\"></script>\n");
?>
<script type="text/javascript" language="JavaScript">
function upchat()
{
	ch_fr = top.window.frames.chat;
	if (ch_fr)
	{
		bt = ch_fr.document.getElementById("chat_text");
		if (bt)
		{
			if (bt.style.display!="block")
			{
				bt.style.display="block";
			}
		}
		bt = ch_fr.document.getElementById("combat_text");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("arcomage_text");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_game_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_combat_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_arcomage_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
	}
}
upchat();
</script>
<?

$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
$map_save = $map;
$add_har = 0;
$add_nav = 0;
$add_gp = 0;
$col_up = 0;

$clevel = $char['clevel'];
if ($clevel < 40 && $char['map_name'] != 666)
{
    $new_clevel = get_new_level($clevel);
    $all_exp = $char['EXP'];
    $col_up = 0;
    $minus_exp = 0;
    $add_clevel = 0;
    $up_newbie = 0;
    while ($all_exp > $new_clevel)
    {
        $col_up++;
        $minus_exp+=$new_clevel;
        $add_clevel++;
        if (($char['clevel']+$add_clevel)<=5) $up_newbie++;
        $all_exp-=$new_clevel;
        $clevel++;
        $new_clevel = get_new_level($clevel);
        $new_level = $char['clevel']+$add_clevel;
        if ($new_level >= 0 and $new_level < 10) { $add_gp+=50; $add_nav+=1; $add_har+=2;}
        elseif ($new_level == 10) {$add_gp+=300; $add_nav+=3; $add_har+=3;}
        elseif ($new_level > 10 and $new_level < 20) {$add_gp+=100; $add_nav+=1; $add_har+=2;}
        elseif ($new_level == 20) {$add_gp+=500; $add_nav+=3; $add_har+=3;}
        elseif ($new_level > 20 and $new_level < 30){ $add_gp+=200; $add_nav+=1; $add_har+=2;}
        elseif ($new_level == 30) {$add_gp+=1000; $add_nav+=3; $add_har+=3;}
        elseif ($new_level > 30 and $new_level < 40){ $add_gp+=300; $add_nav+=1; $add_har+=2;}
        elseif ($new_level == 40) {$add_gp+=1500; $add_nav+=3; $add_har+=3;$all_exp=0;}
        else {$all_exp=0;};
    }
}
if ($col_up>0)
{
	// Игроку добавление денег и увеличение уровня
	$up = myquery("UPDATE game_users SET clevel = clevel+$add_clevel,EXP=EXP-$minus_exp,bound=bound+$add_har,exam=exam+$add_nav,GP=GP+$add_gp,CW=CW+'".($add_gp*money_weight)."' where user_id='$user_id'");
	setGP($user_id,$add_gp,21);

    if ($char['map_name']==700)
    {
		pay_ref($user_id,$up_newbie*10);
    }
    $char['clevel']+=$add_clevel;
        
	echo'<br><center><b><font face=verdana size=2 color=ff0000>Ты '.echo_sex('развился','развилась').' до '.$char['clevel'].' уровня!
	<br>Ты получаешь: '.$add_gp.' золотых, '.$add_nav.pluralForm($add_nav,' дополнительный навык',' дополнительных навыка',' дополнительных навыков').' и '.$add_har.pluralForm($add_har,' дополнительную характеристику',' дополнительные характеристики',' дополнительных характеристик').'!</font></b></center>';

	if (($char['clevel']-$add_clevel)<5 AND $char['clevel']>=5 AND $char['map_name']==700)
	{
		pay_ref($user_id,50);
        myquery("UPDATE game_users_data SET obnul=1 WHERE user_id=$user_id");
		include("lib/newbie.php");
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
    else
    {
        echo '<meta http-equiv="refresh" content="6;url=act.php">';        
    }
}
elseif ($char['clevel']>=5 AND $char['map_name']==700)
{
    include("lib/newbie.php");
    {if (function_exists("save_debug")) save_debug(); exit;}
}


if (isset($do_exit))
{
	include("lib/newbie.php?do_exit");
	{if (function_exists("save_debug")) save_debug(); exit;}
}

if ($func=='main' OR $func=='inv' OR $func=='hero' OR $func=='online' OR $func=='setup' OR $func=='pm' OR $func=='npc_fav' OR $func=='help_newbie')
{
	if (isset($userban) and $userban['type']==3)
	{
		OpenTable('title');
		echo '
		<table border=0><tr><td align=center><b><font color=#FF0000 face=Verdana size=3>ВНИМАНИЕ!!! Администраторами игры ТЕБЕ вынесено предупреждение сроком на '.ceil(($userban['time']-time())/60).' минут!<br>Требуем от ТЕБЯ соблюдения законов игры! В противном случае в след.раз ТЫ будешь '.echo_sex('отправлен','отправлена').' в бан и не сможешь играть!</font></b></td></tr><tr><td align=center><font color=#00FF00 face=Verdana size=3><br><br>'.$userban['za'].'</td></tr><tr><td align=right><b><font color=#FF0000 face=Verdana size=3><br><br>Администрация проекта &quot;'.GAME_NAME.'&quot;<b></td></tr></table>';
		OpenTable('close');
	}
}

switch($func)
{
	case 'main':
	include('lib/menu.php');
	include('lib/main.php');
	break;

	case 'battle':
	include('lib/menu.php');
	include('lib/main.php');
	break;

	case 'inv':
	include('lib/menu.php');
	include('lib/inv.php');
	break;

	case 'hero':
	include('lib/menu.php');
	include('lib/hero.php');
	break;

	case 'online':
	include('lib/menu.php');
	include('lib/online.php');
	break;

	case 'action':
	include('lib/menu.php');
	include('lib/action.php');
	break;

	case 'npc':
	include('lib/menu.php');
	include('lib/action_npc.php');
	break;

	case 'setup':
	include('lib/menu.php');
	include('lib/options.php');
	break;

	case 'pm':
	include('lib/menu.php');
	include('lib/pm.php');
	break;

	case 'shop':
	include('lib/menu.php');
	include('lib/shop.php');
	break;

	case 'npc_fav':
	include('lib/menu.php');
	include('lib/npc_fav.php');
	break;
	
	case 'gift':
	include('lib/menu.php');
	include('lib/gift.php');
	break;

	case 'help_newbie':
	include('lib/menu.php');
	include('lib/help_newbie.php');
	break;

	case 'boy':
	include('lib/menu.php');
	include('lib/boy.php');
	break;

	case 'spell_book':
	include('lib/spell_book.php');
	break;

	case 'jaloba':
	include('lib/menu.php');
	include('lib/jaloba.php');
	break;

	// Окно Журанла, где в будущем будет располагаться общая статистика для пользователя в игре
	// по задумке в том числе и игровые события
	case 'jurnal':
	include('lib/menu.php');
	include('lib/jurnal.php');
	break;
}

include("inc/template_footer.inc.php");

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
if (function_exists("save_debug")) save_debug();
mysql_close();
?>
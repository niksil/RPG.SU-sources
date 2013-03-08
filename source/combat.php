<?php
require_once('inc/engine.inc.php');
require_once('inc/xray.inc.php');
require_once('inc/lib.inc.php');
require_once('combat/class_combat.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '1');
}
else
{
	die();
}
require_once('inc/lib_session.inc.php');
//функция вызывается перед окончанием/остановкой скрипта
function end_script_combat() 
{
	global $_SERVER;
	if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
	{
		show_debug();
	}
	mysql_close();
	if (function_exists("save_debug")) save_debug(); 
}

function PrintCombatHeader()
{
	?>
	<html>
	<head>
	<title><?php echo GAME_NAME;?> :: Ролевая on-line игра</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<style type="text/css">@import url("combat/combat.css");</style>
	<style type="text/css">
	INPUT.BUTTON
	{
		font-size: 11px;
		font-family: Verdana;
		vertical-align:middle; 
		padding: 2px 15px 2px 15px;
	}
	</style>
	</head>
	<SCRIPT language="JavaScript" src="js/combajax.js" type="text/javascript"></script>
	<script language="JavaScript" type="text/javascript">
	var ScriptUrl = "combat/ajax.php";
	function createXmlHttpRequestObject()
	{
		var xmlHttp;
		try
		{
			xmlHttp = new XMLHttpRequest();
		}
		catch(e)
		{
			var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
			"MSXML2.XMLHTTP.5.0",
			"MSXML2.XMLHTTP.4.0",
			"MSXML2.XMLHTTP.3.0",
			"MSXML2.XMLHTTP",
			"Microsoft.XMLHTTP");
			for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
			{
				try
				{
					xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
				}
				catch (e) {}
			}
		}

		if (xmlHttp) return xmlHttp;
	}
	</script>
	<body onload="init();">
	<div align="center">        
	<?
}

function ShowCombatLog($combat_id,$log_hod)
{
	global $char;
	$log_message = show_combat_log($combat_id,$log_hod,'99%');
	if ($log_message!='')
	{
		echo'<center><div id="log_boy" style="width:95%; border: 2px groove #008000; padding:2px; max-height:160px; overflow: auto;">';
		echo $log_message;
		echo '</div></center>';
		?>
		<script language="JavaScript" type="text/javascript">
		try
		{
			log = document.getElementById("log_table");
			for (var i = log.rows.length-1; i>=0; i--) {
				if (log.rows[i].cells[1].textContent=="<?=$char['name'];?>") {
					log.rows[i].bgColor="#282828";    
				}
				else if (log.rows[i].cells[2].textContent.indexOf("<?=$char['name'];?>")>-1) {
					log.rows[i].bgColor="#580000";    
				}
			}
		}
		catch(e)
		{}
		</script>
		<?
	}
}

if (function_exists("start_debug")) start_debug(); 

$selstate = myquery("SELECT * FROM combat_users_state WHERE user_id=$user_id");
if ($selstate==false OR mysql_num_rows($selstate)==0)
{
	ForceFunc($user_id,5);
	end_script_combat();
	setLocation("act.php");
}

$state = mysql_fetch_array($selstate);

//ДАЛЬШЕ ИДЕТ ПЕРЕСТРАХОВКА 
$est_combat = true;
if (in_array($state['state'],array(3,4,7,8,9)))
{
	//в этих состояниях записей в combat уже может не быть, скрипт вызван только для отображения картинки для игрока
	$est_combat = false;
}
else
{
	$check_combat = myquery("SELECT * FROM combat WHERE combat_id='".$state['combat_id']."'");
	if (!mysql_num_rows($check_combat))
	{
		myquery("UPDATE combat_users_state SET state=7 WHERE user_id='".$state['user_id']."' AND combat_id='".$state['combat_id']."'");
		$state['state'] = 9;
		$state['combat_id'] = 0;
		$est_combat = false;
	}
}
if ($est_combat) 
{
	$combat = new Combat($state['combat_id'],$user_id,$state);
	if (in_array($state['state'],array(1,2,5,6,10)))
	{
		//здесь уже должны быть записи в combat_users, иначе убираем игрока из боя
		$check = myquery("SELECT combat_id FROM combat_users WHERE user_id=$user_id");
		if ($check==false OR mysql_num_rows($check)==0)
		{
			$combat->clear_user($user_id);
			combat_delFunc($user_id);
			ForceFunc($user_id,5);
			setLocation("act.php");
		}
		else
		{
			list($combat_id)=mysql_fetch_array($check);
			$check = myquery("SELECT combat_id FROM combat WHERE combat_id=$combat_id");
			if ($check==false OR mysql_num_rows($check)==0)
			{
				$combat->clear_user($user_id);
				combat_delFunc($user_id);
				ForceFunc($user_id,5);
				setLocation("act.php");
			}
		}
	}
}

//$state:
//1 - на игрока напали и с игрока затребовано подтверждение
//2 - игрока напал и с противника затребовано подтверждение
//3 - игрок отказался от начала боя
//4 - противник отказался от начала боя
//5 - игрок находится в бою в интерфейсе выбора хода
//6 - игрок находится в бою и сделал ход
//7 - игрок выиграл
//8 - игрок проиграл
//9 - ничья
//10 - ожидание начала боя (бой начинается с задержкой)

//TODO написать возможность начала боя с задержкой и учесть что в этот бой можно присоединяться до его начала

if (isset($_GET['otprav']))
{
	if ($state['state']==5)
	{
		//игрок нажал кнопку "Ходить"
		//надо по его ходу создать записи в бд, а игрока перенаправить на состояние 6
		$combat->otpravka_hoda($_GET['otprav']);
		setLocation("combat.php");
	}
}

//ВЫЛЕТ ПО ТАЙМУ
if ($est_combat)
{
	$time = time();
	$sel = myquery("SELECT user_id FROM combat_users WHERE npc=0 AND `join`=0 AND time_last_active<".($time-$combat->timeout)." AND combat_id=".$state['combat_id']."");
	while (list($user_out)=mysql_fetch_array($sel))
	{
		$combat->user_out($user_out);
		if ($user_out==$user_id) 
		{
			$state['state']=8;
		}
	}
	if (mysql_num_rows($sel)>0)
	{
		//надо проверить на удаление всего боя, т.к. может вызываться вылет по тайму со стороны единственного игрока (бой с ботами)
		$sel_boy = myquery("SELECT user_id FROM combat_users WHERE npc=0 AND `join`=0 AND time_last_active>=".($time-$combat->timeout)." AND combat_id=".$state['combat_id']."");
		if ($sel_boy==false OR mysql_num_rows($sel_boy)==0 OR mysql_result($sel_boy,0,0)==0)
		{
			$combat->clear_combat();
		}
	}
}

switch($state['state'])
{
	case 1:
	{
		$combat->print_state1();   
	}
	break;
	
	case 2:
	{
		$combat->print_state2();
	}
	break;
	
	case 3:
	{
		PrintCombatHeader();
		echo'<center>Ты '.echo_sex('отказался','отказалась').' от поединка<br><input type="button" value="Вернуться" onClick=location.replace("act.php")>';
		ForceFunc($user_id,5);
	}
	break;
	
	case 4:
	{
		PrintCombatHeader();
		echo'<center>Противник отказался от поединка<br><input type="button" value="Вернуться" onClick=location.replace("act.php")>';
		ForceFunc($user_id,5);
	}
	break;
	
	case 5:
	{
		$combat->print_boy();  
	}
	break;
	
	case 6:
	{
		$combat->print_wait();
	}
	break;
	
	case 7:
	{
		PrintCombatHeader();
		combat_delFunc($user_id);
		ForceFunc($user_id,5); 
		?>
		<center>Ты <?=echo_sex('выиграл','выиграла');?>!<br>
		<input type="button" value="Вернуться" onClick=location.replace("act.php")><br>
		<img src="http://<?=IMG_DOMAIN;?>/combat/1.jpg">
		<?
		if ($state['combat_id']>0 AND $state['hod']>0)
		{
			ShowCombatLog($state['combat_id'],$state['hod']);
		}
	}
	break;
	
	case 8:
	{
		PrintCombatHeader();
		combat_delFunc($user_id);
		ForceFunc($user_id,9); 
		?>
		<center>Ты <?=echo_sex('проиграл','проиграла');?>!<br>
		<input type="button" value="Вернуться" onClick=location.replace("lib/town.php")><br>
		<img src="http://<?=IMG_DOMAIN;?>/combat/lose.jpg">
		<?
		if ($state['combat_id']>0 AND $state['hod']>0)
		{
			ShowCombatLog($state['combat_id'],$state['hod']);
		}
	}
	break;
	
	case 9:
	{
		PrintCombatHeader();
		combat_delFunc($user_id);
		ForceFunc($user_id,5); 
		?>
		<center>Ничья!<br>
		<input type="button" value="Вернуться" onClick=location.replace("act.php")><br>
		<img src="http://<?=IMG_DOMAIN;?>/combat/n.jpg">
		<?
		if ($state['combat_id']>0 AND $state['hod']>0)
		{
			ShowCombatLog($state['combat_id'],$state['hod']);
		}
	}
	break;
	
	case 10:
	{
		$combat->print_begin();
	}
	break;
	
	default:
	{
		$combat->clear_combat();
		end_script_combat();
		setLocation("act.php");
	}
	break;
	
}
?>
<script type="text/javascript" language="JavaScript">
function upchat()
{
	ch_fr = top.window.frames.chat;
	if (ch_fr)
	{
		bt = ch_fr.document.getElementById("select_game_chat");
		if (bt)
		{
			if (bt.style.display!="inline")
			{
				bt.style.display="inline";
			}
		}
		bt = ch_fr.document.getElementById("select_combat_chat");
		if (bt)
		{
			if (bt.style.display!="inline")
			{
				bt.style.display="inline";
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
end_script_combat();
?>
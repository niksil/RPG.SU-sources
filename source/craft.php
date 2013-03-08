<?
//ob_start('ob_gzhandler',9);
require_once('inc/engine.inc.php');
include('inc/xray.inc.php');
include('inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '2');
}
else
{
	die();
}

// Расшифровка локального func_id:
// 1 - Собиратель
// 2 - Алхимия
// 3 - Строитель ( не введено )
// 4 - Лесоруб
// 5 - Каменотес
// 6 - Рудокоп
// 7 - Плотник
// 8 - Охотник
// 9 - Мясник
// 10 - Литейщик
// 11 - Оружейник
// 12 - Кузнец

include('inc/lib_session.inc.php');

require_once('inc/craft/craft.inc.php');

$local_func_id=craft_getFunc($user_id);

if (function_exists("start_debug")) start_debug(); 

$select1=myquery("select * from craft_build_user where user_id=$user_id and create_time!=0");
$select2=myquery("select * from craft_build_rab where user_id=$user_id");
if (!mysql_num_rows($select1) and !mysql_num_rows($select2))  
{
	ForceFunc($user_id,5);
	craft_DelFunc($user_id);
	setLocation("act.php");
}

function exit_from_craft($add_query='',$exit=1,$userid=0)
{
	global $user_id;
	if ($userid == 0) $userid = $user_id;
	if ($exit==1)
	{
		craft_DelFunc($userid);
		myquery("delete from craft_build_rab where user_id=$userid");
		myquery("delete from craft_user_func where user_id=$userid");
	}
	else
	{
		myquery("update craft_build_rab set date_rab=0,dlit=0,eliksir=0,`add`=0 where user_id=$userid");
	}
	myquery("update craft_build_lumberjack set user_id=0, end_time=0 where user_id=$userid");
	myquery("update craft_build_stonemason set user_id=0, end_time=0 where user_id=$userid");
	myquery("update craft_build_lumberjack set reserve_user_id=0, reserve_time=0 where reserve_user_id=$userid");
	myquery("update craft_build_stonemason set reserve_user_id=0, reserve_time=0 where reserve_user_id=$userid");
	myquery("update craft_build_mining set user_id=0, end_time=0 where user_id=$userid");
	if ($add_query!='')
	{
		$str_query = "update game_users set user_id=$userid";
		$str_query.=$add_query;
		$str_query.=' where user_id='.$userid;
		myquery($str_query); 
	}
	if ($exit==1)
	{
		set_delay_reason_id($userid,1); 
		ForceFunc($userid,5);
	}
}

function broken()
{
	global $user_id;
	$brok = 1;
	$sel = myquery("SELECT item_uselife FROM game_items WHERE user_id=$user_id AND used=21 AND priznak=0");
	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		list($use) = mysql_fetch_array($sel);
		if ($use>0)
		{
			$brok = 0;
		}
	}
	return $brok;
}

include('inc/template_header.inc.php');
//include("lib/menu.php");
echo'
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TR><TD width=80><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/1.gif" width=80></TD>
<TD vAlign=top width=18 background="http://'.IMG_DOMAIN.'/nav1/spacer.gif">
<IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/2.gif" width=18></TD>
<TD vAlign=top width=22 background="http://'.IMG_DOMAIN.'/nav1/3.gif">
<IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></TD>
<TD width="100%" background="http://'.IMG_DOMAIN.'/nav1/3.gif" align=center vAlign=center>

<table cellSpacing=0 cellPadding=0 width="100%" border=0><tr>
<td align="center"><a href="http://'.DOMAIN.'/craft.php" target="game">Крафт</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>
<td align="center"><a href="http://'.DOMAIN.'/craft.php?inv" target="game">Инвентарь</a></td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';

$result = myquery("SELECT hide,privat FROM game_admins WHERE user_id=".$user_id."");
if (mysql_num_rows($result))
{
	echo'<td><nobr><a href="http://'.DOMAIN.'/admin.php" target="_blank">Admin</a>';
	list($hide,$adprivat) = mysql_fetch_array($result);
	if ($hide>0)
	{
		if (isset($privat))
		{
			$privat=(int)$privat;
			$adprivat = $privat;
			$up=myquery("update game_admins set privat='$privat' where user_id=".$user_id." limit 1");
			$up=myquery("update game_users set hide='$privat' where user_id=".$user_id." limit 1");
		}
		if (!isset($func)) $func='main';
		if ($adprivat==1)
		{
			echo'&nbsp;<a href="http://'.DOMAIN.'/act.php?func='.$func.'&privat=0"><img src="http://'.IMG_DOMAIN.'/nav/ball_red.jpg" width=14 height=14 border="0" title="выйти из тени"></a>';
		}
		else
		{
			echo'&nbsp;<a href="http://'.DOMAIN.'/act.php?func='.$func.'&privat=1"><img src="http://'.IMG_DOMAIN.'/nav/ball_green.jpg" width=14 height=14 border="0" title="войти в тень"></a>';
		}
	}
	echo'</td><td><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/4.gif" width=22></td></td>';
}

echo'<td><a href="http://'.DOMAIN.'/logout.php" target="game">Выход</a></td></tr></table>
</TD>
<TD vAlign=top width=22 background="http://'.IMG_DOMAIN.'/nav1/3.gif">
<IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/5.gif" width=22></TD>
<TD vAlign=top width=18 background="http://'.IMG_DOMAIN.'/nav1/spacer.gif"><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/6.gif" width=18></TD>
<TD width=80><IMG height=43 src="http://'.IMG_DOMAIN.'/nav1/7.gif" width=80></TD>
</TR>
</TABLE>';

if (isset($_GET['inv']))
{
	include("lib/inv.php");
}
else
{

echo'<br><center>';

if (mysql_num_rows($select2))
{
	$rab=mysql_fetch_array($select2);
	if (
	($rab['build_id']!='sawmill')
	and($rab['build_id']!='meating')
	and($rab['build_id']!='founder')
	and($rab['build_id']!='oruj')
	)
	{
		$build=mysql_fetch_array(myquery("select craft_build.* from craft_build,craft_build_user where craft_build_user.type=craft_build.id AND craft_build_user.id='".$rab['build_id']."'"));
	}
	else
	{
		$build=array();
		$build['include']=$rab['build_id'];
	}

	$timeout=$rab['dlit'];
	$hod=$rab['date_rab'];
	$build_id=$rab['build_id'];
	
	if (isset($act) AND $act=='no' AND $local_func_id!=3)
	{
		if ($local_func_id==6)
		{
			echo'<br /><br />Ты '.echo_sex('отменил','отменила').' работу<meta http-equiv="refresh" content="4;url=craft.php"><br /><br />';
			myquery("UPDATE craft_build_mining SET user_id=0,end_time=0 WHERE user_id=$user_id");
			die();
		}
		elseif ($local_func_id==4)
		{
			$sel = myquery("SELECT * FROM craft_build_lumberjack WHERE user_id=$user_id");
			if (mysql_num_rows($sel)>0)
			{
				echo'<br /><br />Ты '.echo_sex('отменил','отменила').' работу<meta http-equiv="refresh" content="4;url=craft.php"><br /><br />';
				myquery("UPDATE craft_build_lumberjack SET user_id=0,end_time=0,reserve_user_id=0,reserve_time=0 WHERE user_id=$user_id");
				die();
			}
			else
			{
				myquery("UPDATE craft_build_lumberjack SET reserve_user_id=0,reserve_time=0 WHERE reserve_user_id=$user_id");
				echo'<br /><br />Ты '.echo_sex('вышел','вышла').' из лесоповала<meta http-equiv="refresh" content="4;url=act.php?func=main"><br /><br />';
				exit_from_craft();
			}
		}
		else
		{
			echo'<br /><br />Ты '.echo_sex('отменил','отменила').' работу<meta http-equiv="refresh" content="4;url=act.php?func=main"><br /><br />';
			exit_from_craft();
		}
	}
	
	$broken_instrument = broken();
	if ($build['include']!='')
	{
		//для лесоруба/каменотеса/рудокопа/лесопилки делаем перенаправление на их скрипты
		include("craft/inc/".$build['include'].".inc.php");
	}
	else
	{
		//для собирателя/алхимика/плотника окончание работы будем обрабатывать здесь
		if ($hod-time()+$timeout<=0)
		{
			$add_query = '';
			if ($local_func_id==2)
			{
				//инклудим окончание работы алхимика
				include("craft/inc/alchemist_endtime.inc.php"); 
			}
			elseif($local_func_id==1)
			{
				//инклудим окончание обычного крафта (пока у нас это только собиратель)
				include("craft/inc/craft_endtime.inc.php"); 
			}
			elseif($local_func_id==9)
			{
				//инклудим окончание работы мясника
				include("craft/inc/meating_endtime.inc.php"); 
			}
			exit_from_craft($add_query);
			echo'<meta http-equiv="refresh" content="10;url=act.php?func=main">';
			if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
			{
				show_debug();
			}
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		else
		{
			// Время еще не прошло
			if ($local_func_id==2)
			{
				echo '<span style="float:left"><img src="http://'.IMG_DOMAIN.'/quest/lab_left.jpg"></span>';
				echo '<span style="float:right"><img src="http://'.IMG_DOMAIN.'/quest/lab_right.jpg"></span>';
				echo'<strong>Ты '.echo_sex('занят','занята').' варкой зелий. </strong>';
			}
			elseif($local_func_id==1)
			{
				echo'<strong>Ты '.echo_sex('занят','занята').' добычей ресурсов. </strong>';
			}
			elseif($local_func_id==7)
			{
				echo'<strong>Ты '.echo_sex('занят','занята').' работой на лесопилке. </strong>';
			}
			
			echo '<br>Во время работы не обязательно находиться в игре. <br>До конца работы осталось: <font color=ff0000><b><span id="timerr1">'.($hod-time()+$timeout).'</span></b></font> секунд</div> 
			<script language="JavaScript" type="text/javascript">
			function tim()
			{
				timer = document.getElementById("timerr1");
				if (timer.innerHTML<=0)
					location.reload();
				else
				{
					timer.innerHTML=timer.innerHTML-1;
					window.setTimeout("tim()",1000);
					if (timer.innerHTML%120==0)
					{
						location.reload();
					}
				}
			}
			tim();
			</script>';
		}
	}
	if ($local_func_id!=3 AND $local_func_id!=6)
	{    
		echo'<br /><br /><br /><br /><a href="?craft&act=no">Остановить работу</a>';
	}
}
		
//строителсьтво здания
if (mysql_num_rows($select1))
{
	$create=mysql_fetch_array($select1);
	$hod=$create['create_date'];
	$timeout=$create['create_time'];
	list($build_name) = mysql_fetch_array(myquery("SELECT name,res_dob FROM craft_build WHERE id=".$create['type'].""));

	if ($hod-time()+$timeout<='0')
	{
		echo "Строительство закончено";
		myquery("update craft_build_user set create_time=0, status='1' where user_id=$user_id and status='0'");
		craft_DelFunc($user_id);
		ForceFunc($user_id,5);
		{if (function_exists("save_debug")) save_debug(); exit;}
	}



	echo'Ты '.echo_sex('занят','занята').' строительством здания. <br>Во время работы не обязательно находиться в игре. <br>До конца строительства <b><font color=red size=3>'.$build_name.'</font></b> осталось: <font color=ff0000><b><span id="timerr1">'.($hod-time()+$timeout).'</span></b></font> секунд</div>
	<script language="JavaScript" type="text/javascript">
	function tim()
	{
		timer = document.getElementById("timerr1");
		if (timer.innerHTML<=0)
			location.reload();
		else
		{
			timer.innerHTML=timer.innerHTML-1;
			window.setTimeout("tim()",1000);
			if (timer.innerHTML%120==0)
			{
				location.reload();
			}
		}
	}
	tim();
	</script>';
		
} 
}

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
if (function_exists("save_debug")) save_debug(); 
?>
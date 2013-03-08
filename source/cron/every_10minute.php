<?php
// рон дл€ запуска каждые 10 минут

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

move_teleport(10);

myquery("DELETE FROM game_cron_log WHERE cron='every_10minute' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_10minute','Ќачало',".time().")");
$idcronlog = mysql_insert_id();

myquery("UPDATE game_cron_log SET step='ѕроверка игроков по обелискам', timecron=".time()." WHERE id=$idcronlog");
//проверка по обелискам
//type = 0 - обелиски вз€тые на карте
//type = 1 - зелье глубин, среднее зелье глубин
//type = 2 - сваренные зель€ повышающие харки
//type = 3 - зелье бодрости
//type = 4 - зелье зоркости
//type = 5 - зелье невидимости
$sel = myquery("SELECT * FROM game_obelisk_users WHERE user_id>0 AND time_end<".time()."");
while ($ob = mysql_fetch_array($sel))
{
	if ($ob['type']==0 OR $ob['type']==2)
	{
		if ($ob['value']==0)
		{
			myquery("UPDATE game_users SET ".$ob['harka']."=".$ob['harka']."_MAX WHERE user_id = ".$ob['user_id']."");
			myquery("UPDATE game_users_archive SET ".$ob['harka']."=".$ob['harka']."_MAX WHERE user_id = ".$ob['user_id']."");
		}
		else
		{
			if ($ob['harka']=='STM')
			{
				myquery("UPDATE game_users SET STM=STM-".$ob['value'].",STM_MAX=STM_MAX-".$ob['value']." WHERE user_id = ".$ob['user_id']."");
				myquery("UPDATE game_users_archive SET STM=STM-".$ob['value'].",STM_MAX=STM_MAX-".$ob['value']." WHERE user_id = ".$ob['user_id']."");
			}
			else
			{
				myquery("UPDATE game_users SET ".$ob['harka']."=".$ob['harka']."-".$ob['value']." WHERE user_id = ".$ob['user_id']."");
				myquery("UPDATE game_users_archive SET ".$ob['harka']."=".$ob['harka']."-".$ob['value']." WHERE user_id = ".$ob['user_id']."");
			}
		}
	}
	elseif ($ob['type']==1)
	{
		//перекачка HP_MAX, возвращаем его к HP_MAXX
		myquery("UPDATE game_users SET HP_MAX=HP_MAXX WHERE user_id = ".$ob['user_id']."");
		myquery("UPDATE game_users_archive SET HP_MAX=HP_MAXX WHERE user_id = ".$ob['user_id']."");
	}
	myquery("DELETE FROM game_obelisk_users WHERE id=".$ob['id']."");
}

myquery("UPDATE game_cron_log SET step='ќчистка чатов', timecron=".time()." WHERE id=$idcronlog");

echo 'ќчистка чатов<br>';
$online_range = time() - 600;
$result_delete = myquery("DELETE FROM game_chat WHERE post_time < $online_range");
$result_delete = myquery("DELETE FROM game_battles WHERE post_time < $online_range");

$time1 = time()-30*60;
myquery("DELETE FROM game_log WHERE date<$time1");

myquery("UPDATE game_cron_log SET step='ѕеремещение обелисков', timecron=".time()." WHERE id=$idcronlog");

//перемещение обелисков
$sel = myquery("SELECT * FROM game_obelisk");
while ($obel = mysql_fetch_array($sel))
{
	$map_name = $obel['map_name'];
	list($max_xpos,$max_ypos) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_map WHERE name=$map_name ORDER BY xpos DESC, ypos DESC LIMIT 1"));
	$map_xpos = mt_rand(1,$max_xpos-3);
	$map_ypos = mt_rand(1,$max_ypos-3);
	myquery("UPDATE game_obelisk SET map_xpos=$map_xpos, map_ypos=$map_ypos WHERE id=".$obel['id']."");
}


myquery("UPDATE game_cron_log SET step='”даление билетов в порт опоздавших', timecron=".time()." WHERE id=$idcronlog");

myquery("DELETE FROM game_port_bil WHERE buydate<".(time()-120)." AND stat=0");

myquery("UPDATE game_cron_log SET step='ќбновление энергии, жизни и маны игроков в лабиринтах', timecron=".time()." WHERE id=$idcronlog");

$new_year_lab = "(809,810,811,812,813)";
myquery("UPDATE game_users,game_users_map SET game_users.STM = LEAST(game_users.STM + 3,game_users.STM_MAX),game_users.MP = LEAST(game_users.MP + 3,game_users.MP_MAX),game_users.HP = LEAST(game_users.HP + 3,game_users.HP_MAX) WHERE game_users.user_id=game_users_map.user_id AND game_users_map.map_name IN ".$new_year_lab." ");

myquery("UPDATE game_cron_log SET step='ќказание помощи новичкам', timecron=".time()." WHERE id=$idcronlog");

$sel = myquery("SELECT user_id,name FROM view_active_users WHERE clevel<5");
while ($cha = mysql_fetch_array($sel))
{
	$r = mt_rand(1,1);
	if ($r==1)
	{
		$fp=fopen("http://".DOMAIN."/chat/nub.txt", 'r');
		if ($fp!==false)
		{
			$stroka = mt_rand(1,30);
			$ctroka = 0;
			while (!feof($fp))
			{
				$ctroka++;
				$chat_mess = fgets($fp);
				if ($ctroka==$stroka)
				{
					$chat_mess=trim($chat_mess);
					$chat_mess='<font color=yellow>'.$chat_mess.'</font>';
					$chat_mess=iconv("Windows-1251","UTF-8//IGNORE",$chat_mess);
					$message = $chat_mess;
					$update=myquery("insert into game_log 
					(town,message,date,fromm,too,ptype) 
					values 
					('0','".$message."','".time()."','-1','".iconv("Windows-1251","UTF-8//IGNORE",$cha['user_id'])."',1)");
					echo 'Ќафан€ оказал помощь новичку: '.$cha['name'].'<br>';
					break;
				}
			}
		}
	}
}

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

?>
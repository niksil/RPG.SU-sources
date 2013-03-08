<?php
//Крон для запуска каждые 2 часа

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

move_teleport(120);

myquery("DELETE FROM game_cron_log WHERE cron='every_2hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_2hour','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='Создаем квест гильдии охотников за монстрами', timecron=".time()." WHERE id=$idcronlog");


echo'<br><br>Создадим квест "Охотники за монстрами"';

myquery("UPDATE game_npc SET npc_quest_id=0,npc_quest_end_time=0,npc_quest_guild=0");

for ($i=2;$i<=7;$i++)
{
	$del_npc = myquery("SELECT npc_quest_end_time FROM game_npc WHERE npc_quest_id='$i'");
	list($npc_quest_end_time)=mysql_fetch_array($del_npc);
	if (mysql_num_rows($del_npc)==0 or $npc_quest_end_time==0 or $npc_quest_end_time<=time())
	{
		myquery("DELETE FROM game_quest_users WHERE quest_id='$i'");
		myquery("DELETE FROM game_items WHERE item_for_quest='$i'");
		myquery("UPDATE game_npc SET npc_quest_guild=0,npc_quest_id=0,npc_quest_end_time=0 WHERE npc_quest_id='$i'");

		echo '<br>Создаем квест №'.$i.'';
		if ($i>=5)
		{
			$map=18;
		}
		else
		{
			$map=5;
		}
		
		$selitem = myquery("SELECT id FROM game_items_factsheet WHERE type=98");
		jump_random_query($selitem);
		$r = mysql_fetch_assoc($selitem);

		while(1==1)
		{
			$sel = myquery("SELECT Distinct game_gorod.town, game_gorod.rustown FROM game_gorod 
							Join game_map On game_gorod.town=game_map.town 
							Join game_gorod_set_option On game_gorod.town=game_gorod_set_option.gorod_id
							WHERE rustown<>'' and game_map.name=$map and game_gorod_set_option.option_id=13 and game_map.to_map_name=0 ");
			jump_random_query($sel);
			$guild=mysql_fetch_assoc($sel);
			$guild_id = $guild['town'];
			$rustown = $guild['rustown']; 
			$check_npc = mysql_result(myquery("SELECT COUNT(*) FROM game_npc WHERE npc_quest_guild='$guild_id'"),0,0);
			if ($check_npc==0)
			{
				echo '<br>guild_id='.$guild_id.' - '.$rustown;
				echo '<br>npc_map_name='.$map.'';
				$npc_select = myquery("SELECT game_npc.id FROM game_npc 
									   Join game_npc_template On game_npc_template.npc_id=game_npc.npc_id
                                       WHERE game_npc_template.npc_level>=5 and game_npc_template.npc_level<=50 and game_npc.prizrak='0' AND map_name='".$map."' AND npc_quest_id=0");
				jump_random_query($npc_select);
				$npc=mysql_fetch_assoc($npc_select);
				$npc_id=$npc['id'];
				myquery("UPDATE game_npc SET npc_quest_guild='$guild_id',npc_quest_end_time='".(time()+45*60)."',npc_quest_item=".$r['id'].",npc_quest_id='$i' WHERE id='$npc_id'");
				break;
			}
		}
	}
}

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>
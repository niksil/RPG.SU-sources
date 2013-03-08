<?PHP
require_once('inc/quest_define.inc.php');

//дл€ типа 1
list($q_e_id)=mysql_fetch_array(myquery("SELECT npc_quest_engine_id FROM game_npc WHERE id='$npc_id'"));
//если это квестовый монстр
if($q_e_id>0)
{
	//» если квест на него брал именно этот перс
	if($q_e_id==$char['user_id'])
	{
		list($procent)=mysql_fetch_array(myquery("SELECT par4_value FROM quest_engine_users WHERE quest_type=1 AND par1_value='$npc_id' "));
			
			//тут вообще бардак, в Ѕƒ ботов и игроков перепутаны имена и значени€ параметров, причем перепутаны по-разному))
			//надеюсь, € расшифровал правильно)
			if($char['STR']>$char['NTL'])
				$k=mt_rand(250,300)/100;//1,5
			else	
				$k=mt_rand(350,400)/100;//2,5
			//$npc_hp = $k*$char['HP_MAX'];
			$npc_max_hp = $k* $char['HP_MAX'];
			$npc_hp = 3;//ceil(($procent*$npc_max_hp)/100);
			if($char['STR']>$char['NTL'])
				$k=mt_rand(130,150)/10;
			else	
				$k=mt_rand(40,60)/10;;
			$npc_mp = $k*abs($char['MP_MAX']);
			$npc_max_mp =$k*abs($char['MP_MAX']);
	
			//прибавл€ем защиту к силе
			$npc_str = round($char['HP_MAX'] / 3) + $char['VIT'];
			//это вынослиловть
			$k=mt_rand(180,280);
			$npc_dex = abs($char['DEX'])*$k/100;        
			//это ловкость
			$k=mt_rand(180,280);
			$npc_wis = abs($char['PIE'])*$k/100;
			//это защита
			$k=mt_rand(70,150);
			$npc_basefit = round(abs($char['STR'])*$k / 100);
			//это мудрость
			$k=mt_rand(35,55);
			$npc_basedef = abs($char['SPD'])*$k/100;
			$k=mt_rand(35,55);        
			//это, как ни странно, интеллект ;)
			$npc_ntl = round(abs($char['NTL'])*$k / 100);
			
			
			$up = myquery("UPDATE game_npc,game_npc_template SET game_npc.HP='$npc_hp', game_npc_template.npc_max_hp='$npc_max_hp', game_npc.MP='$npc_mp', game_npc_template.npc_max_mp='$npc_max_mp', game_npc_template.npc_str='$npc_str', game_npc_template.npc_dex='$npc_dex', game_npc_template.npc_pie='$npc_wis', game_npc_template.npc_vit='$npc_basefit', game_npc_template.npc_spd='$npc_basedef', game_npc_template.npc_ntl='$npc_ntl' WHERE game_npc.nid='$npc_id' AND game_npc_template.npc_id=game_npc.npc_id");
		}
}
?>
<?PHP
require_once('inc/quest_define.inc.php');

//для типа 1
list($q_e_id)=mysql_fetch_array(myquery("SELECT npc_quest_engine_id FROM game_npc WHERE id='$npc_id'"));    
//если это квестовый монстр
if($q_e_id>0)
{    	
	//И если квест на него брал именно этот перс    	    
	if($q_e_id==$char['user_id'])
	{
		//апдейтим процент
		list($hp,$hp_max)=mysql_fetch_array(myquery("SELECT game_npc.HP, game_npc_template.npc_max_hp FROM game_npc,game_npc_template WHERE game_npc.id='$npc_id' AND game_npc.npc_id=game_npc_template.npc_id"));
		$procent=ceil(($hp/$hp_max)*100);
		$up = myquery("UPDATE quest_engine_users SET par4_value='$procent' WHERE quest_type=1 AND par1_value='$npc_id'");
	}    
} 
?>
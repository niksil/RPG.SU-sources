<?php
function admin_delete_user($user)
{
	$user = (int)$user;
	if ($user>0)
	{
		$name = get_user('name',$user);
		myquery("delete from arcomage_call where user_id='".$user."'");
		myquery("delete from arcomage_history where user_id='".$user."'");
		myquery("delete from arcomage_users where user_id='".$user."'");
		myquery("delete from arcomage_users_cards where user_id='".$user."'");
		
        myquery("delete from blog_closed where user_id='".$user."'");
		myquery("delete from blog_closed where close_id='".$user."'");
		myquery("delete from blog_friends where user_id='".$user."'");
		myquery("delete from blog_friends where friend_id='".$user."'");
		myquery("delete from blog_love where user_id='".$user."'");
		myquery("delete from blog_love where friend_id='".$user."'");
		myquery("delete from blog_comm where post_id in (select post_id from blog_post where user_id='".$user."')");
		myquery("delete from blog_post where user_id='".$user."'");
		myquery("delete from blog_rating where user_id='".$user."'");
		myquery("delete from blog_users where user_id='".$user."'");
		myquery("update blog_comm set user_id=0 where user_id='".$user."'");
		
        myquery("delete from combat_actions where user_id='".$user."'");
		//myquery("delete from combat_history where user_id='".$user."'");
		myquery("delete from combat_lose_user where user_id='".$user."'");
		//myquery("delete from combat_new_user where user_id='".$user."'");
		myquery("delete from combat_users where user_id='".$user."'");
		myquery("delete from combat_users_exp where user_id='".$user."' or prot_id='".$user."'");
		myquery("delete from combat_users_state where user_id='".$user."'");
		
        myquery("delete from craft_build_rab where user_id='".$user."'");
		myquery("delete from craft_build_user where user_id='".$user."'");
        myquery("delete from craft_build_founder where user_id='".$user."'");
        myquery("update craft_build_lumberjack set user_id=0 where user_id='".$user."'");
        myquery("update craft_build_mining set user_id=0 where user_id='".$user."'");
        myquery("update craft_build_stonemason set user_id=0 where user_id='".$user."'");
		myquery("delete from craft_resource_market where user_id='".$user."'");
		myquery("delete from craft_resource_user where user_id='".$user."'");
        myquery("delete from craft_stat where user='".$user."'");
		myquery("delete from craft_user_func where user_id='".$user."'");
		
        myquery("delete from dungeon_quests_done where user_id='".$user."'");
		myquery("delete from dungeon_users_data where user_id='".$user."'");
		myquery("delete from dungeon_users_progress where user_id='".$user."'");
		
        myquery("delete from forum_read where user_id='".$user."'");
        myquery("delete from forum_setup where user_id='".$user."'");
        myquery("delete from forum_thanks where user_id='".$user."'");

        myquery("delete from game_activity where name='".$name."'");
		myquery("delete from game_activity_mult where name='".$name."'");
		myquery("delete from game_ban where user_id='".$user."'");
		myquery("delete from game_bank where user_id='".$user."'");
		myquery("delete from game_bank_db_kr where user_id='".$user."'");
		myquery("delete from game_chat_ignore where user_id='".$user."' or ignore_id='".$user."'");
		myquery("delete from game_chat_log where user_id='".$user."'");
		myquery("delete from game_chat_nakaz where user_id='".$user."'");
		myquery("delete from game_chat_option where user_id='".$user."'");
		myquery("delete from game_combats_users where user_id='".$user."'");
		myquery("delete from game_gift where user_to='".$user."'");
		myquery("delete from game_invite where user_id='".$user."'");
		myquery("delete from game_items_opis where item_id in (select id from game_items where user_id='".$user."')");
		myquery("delete from game_items where user_id='".$user."'");
		myquery("delete from game_mag where name='".$name."'");
		myquery("delete from game_medal_users where user_id='".$user."'");
		myquery("delete from game_nakaz where user_id='".$user."'");
        myquery("delete from game_npc where for_user_id='".$user."'");
		myquery("delete from game_npc_guild_log where user_id='".$user."'");
		myquery("delete from game_obelisk_users where user_id='".$user."'");
		myquery("delete from game_pm where komu='".$user."'");
		myquery("delete from game_pm where otkogo='".$user."'");
		myquery("delete from game_pm_deleted where komu='".$user."'");
		myquery("delete from game_pm_deleted where otkogo='".$user."'");
		myquery("delete from game_pm_folder where user_id='".$user."'");
		myquery("delete from game_port_bil where user_id='".$user."'");
		myquery("delete from game_prison where user_id='".$user."'");
		myquery("delete from game_quest_users where user_id='".$user."'");
		myquery("delete from game_stats_timemarker where user_id='".$user."'");
		myquery("delete from game_spets_item where user_id='".$user."'");
        myquery("delete from game_turnir_users where user_id='".$user."'");
		myquery("update game_tavern set vladel=612 where vladel='".$user."'");
		//myquery("delete from game_tavern_spletni where name='".$name."'");
		myquery("delete from game_users_active where user_id='".$user."'");
		myquery("delete from game_users_active_delay where user_id='".$user."'");
		myquery("delete from game_users_active_host where user_id='".$user."'");
		myquery("delete from game_users_brak where user1='".$user."'");
		myquery("delete from game_users_brak where user2='".$user."'");
        myquery("delete from game_users_crafts where user_id='".$user."'");
		myquery("DELETE FROM game_users_guild WHERE user_id=".$user."");
		myquery("delete from game_users_clan_reg where user_id='".$user."'");
		myquery("delete from game_users_data where user_id='".$user."'");
		myquery("delete from game_users_event where user_id='".$user."'");
        myquery("delete from game_users_horses where user_id='".$user."'");
		myquery("delete from game_users_func where user_id='".$user."'");
		myquery("delete from game_users_intro where user_id='".$user."'");
		myquery("delete from game_users_map where user_id='".$user."'");
		myquery("delete from game_users_maze where user_id='".$user."'");
		myquery("delete from game_users_npc where user_id='".$user."'");
		myquery("delete from game_users_stat_exp where user_id='".$user."'");
		myquery("delete from game_users_stat_gp where user_id='".$user."'");
		myquery("update game_items set kleymo=0,kleymo_nomer=0,kleymo_id=0 where kleymo=2 and kleymo_id=".$user."");
        myquery("DELETE FROM game_lr_services_hist WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_hunter WHERE user_id=".$user."");
		
        myquery("DELETE FROM houses_market WHERE user_id=".$user."");
        myquery("DELETE FROM houses_nalog WHERE user_id=".$user."");
        myquery("DELETE FROM houses_users WHERE user_id=".$user."");
		
		myquery("DELETE FROM game_admins WHERE user_id=".$user."");
		myquery("DELETE FROM game_admins_ip WHERE user_id=".$user."");
        
		myquery("DELETE FROM game_users WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_archive WHERE user_id=".$user."");
	}
	
	return 1;
}
?>